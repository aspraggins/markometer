<?php #add_message for the backend
//Include the configuration file:
require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.inc.php");

//Set the page title and include the HTML header:
$page_title = 'Edit Message';
include('/home/rneel/web/htdocs/includes/header.html');

//Form Submission
if (isset($_POST['submitted']))
{
    //MySQL setup:
    require_once(MYSQL);
  
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
		
		//Update message record for changes made during edit:
		$q = "INSERT INTO messages 
		SET message='$msg', adminonly='$adminonly', active='Y', date_modified=NOW()";

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
		echo '<h1>Add Message</h1>';
		echo '<fieldset><form action="/be/be_index/add_message/" method="post">
		<p><b>Create Message:</b></p>';
		
		//Build table:
		echo '<table cellspacing="3" cellpadding="5" width="75%" border="1">
		<tr><td align="left">Message</td>
		<td align="left">Admin Only?</td></tr>';
				
		//Build table for message and options:
		//display message text box:
		echo '<td align="left"><textarea name="messagebody" cols="60" 
		rows="10"> </textarea></td>';
		//admin only checkbox:
		echo '<td align="center"><input type="checkbox" name="adminonly" value="Y"></td>';
		
		echo '</tr></table></fieldset>';

		echo '<div align="center"><input type="submit" name="submit" value="Add" />
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