<?php #edit_message for the backend
//Include the configuration file:
require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.inc.php");

//Set the page title and include the HTML header:
$page_title = 'Edit Message';
include('/home/rneel/web/htdocs/includes/header.html');

//Check incoming passed id variable:
//Check for valid ID being passed:
if ( (isset($_REQUEST['id'])) && (is_numeric($_REQUEST['id'])) )
{
	$message_id = $_REQUEST['id'];
}
else
{
	echo '<table border="1" bgcolor="red"><tr><td>This page has been reached 
	in error.</td></tr></table>';		
	exit();
}

//Form Submission
if (isset($_POST['submitted']))
{
    //MySQL setup:
    require_once(MYSQL);
  
	//Set $user_id to POST value:
	$message_id = trim($_REQUEST['id']);
  
    //Assume invalid values:
    $msg = FALSE;
  
	//Check for valid message:
	if (strlen($_POST['messagebody'])>0)
	{
		$msg = $_POST['messagebody'];
	}
	
    if ($msg)
    {
		//Set variable for admin only and dismiss checkboxes:
		$adminonly = 'N'; $active = 'Y';
		if (isset($_POST['adminonly'])) $adminonly = 'Y';
		if (isset($_POST['dismiss'])) $active = 'N';
		
		//Update message record for changes made during edit:
		$q = "UPDATE messages 
		SET updateflag=1-updateflag, message='$msg', adminonly='$adminonly', 
		active='$active' 
		WHERE id=$message_id";

		$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
		mysqli_error($dbc));

		//Return to adminx page:
		ob_end_clean(); //Delete the buffer
		$url = '/be/be_index/';
		header("Location: $url");
		exit(); //Quit the script
    }
	
	//mysqli_close($dbc);
} //End of main Submit conditional

//Generate page:
if(isset($_SESSION['heavyweight']) && isset($_SESSION['username']))
{
	if ($_SESSION['username']=='AdminX')
	{
		//MySQL:
		require_once (MYSQL);

		//Build Messaging interface:
		echo '<h1>Edit Message</h1>';
		echo '<fieldset><form action="/be/be_index/edit_message/?id=' . $message_id . '" method="post">
		<p><b>Chosen Message:</b></p>';
		
		//Query for selected message to edit:
	    $q = "SELECT id, message, adminonly 
		FROM messages 
		WHERE id=$message_id";

	    $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
	    Error: " . mysqli_error($dbc));
		
		//Build table:
		echo '<table cellspacing="3" cellpadding="5" width="75%" border="1">
		<tr><td align="left">ID</td>
		<td align="left">Message</td>
		<td align="left">Admin Only?</td>
		<td align="left">Dismiss</td></tr>';
				
		//Build table for message and options:
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC))
		{
			//display message number:
			echo '<tr><td align="left">' . $row['id'] . '</td>';
			//display message text:
			echo '<td align="left"><textarea name="messagebody" cols="60" 
			rows="10">' . $row['message'] . '</textarea></td>';
			//admin only checkbox:
			echo '<td align="center"><input type="checkbox" name="adminonly" value="Y"';
			if ($row['adminonly']=="Y") echo ' checked';
			echo '</td>';
			
			//dismiss checkbox:
			echo '<td align="center">
			<input type="checkbox" name="dismiss" value="Y"></td>';
		
			echo '</tr>';
		}
		echo '</table>';
		echo '</fieldset>';

		echo '<div align="center"><input type="submit" name="submit" value="Save" />
		&nbsp;&nbsp;or&nbsp;&nbsp;
		<a href="javascript:history.back();">Cancel</a>
		</div>
		<input type="hidden" name="submitted" value="TRUE" />
		</form>';

	}

}
else
{
	$url = '/be/';
	header("Location: $url");
	exit();
}

?>