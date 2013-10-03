<?php #change admin function
//Include the configuration file:
require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.inc.php");

//Set the page title and include the HTML header:
$page_title = 'Change Admin';
include('/home/rneel/web/htdocs/includes/header.html');

if (isset($_REQUEST['submitAdminChange']))
{
	if ($_REQUEST['submitAdminChange'])
	{
		//MySQL:
		require_once (MYSQL);

		//Set new Owner to be Owner status for old admin's account_lookup entries:
	    $q = "UPDATE account_lookup SET user_id={$_REQUEST['NewAdminID']} 
		WHERE user_id={$_REQUEST['OldAdminID']}";

	    $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
	    Error: " . mysqli_error($dbc));
		
		//Delete account_lookup entries by old owner, orphaning him, and delete new owner's old lookup entries:
		$q = "DELETE FROM account_lookup WHERE user_id={$_REQUEST['OldAdminID']} 
		OR (user_id={$_REQUEST['NewAdminID']} AND role!='O')";

	    $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
	    Error: " . mysqli_error($dbc));

		//Change created_by fields to New owner in accounts table, for those that were owned by old owner
		$q = "UPDATE accounts SET created_by={$_REQUEST['NewAdminID']} WHERE 
		created_by={$_REQUEST['OldAdminID']}";
		
	    $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
	    Error: " . mysqli_error($dbc));

		//Change created_by fields to New owner in users table, for those that were owned by old owner:
		$q = "UPDATE users SET created_by={$_REQUEST['NewAdminID']} 
		WHERE created_by={$_REQUEST['OldAdminID']} OR id={$_REQUEST['OldAdminID']}";

	    $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
	    Error: " . mysqli_error($dbc));

		//Nullify created_by field for new owner's user table entry, so it's clear that he's an owner, and to prevent superfluous listings:
		$q = "UPDATE users SET created_by=NULL WHERE id={$_REQUEST['NewAdminID']}";

	    $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
	    Error: " . mysqli_error($dbc));
		
		
		$url = '/be/be_index/';
		header("Location: $url");
		exit(); //Quit the script
		
		
	}
}
else
{
	//Check incoming passed id variable:
	//Check for valid AdminUser (New Admin being requested) being passed:
	if ( isset($_REQUEST['AdminUsers']) && is_numeric($_REQUEST['AdminUsers']) 
	&& isset($_REQUEST['OldAdmin']) && is_numeric($_REQUEST['OldAdmin']))
	{
		$NewAdmin = $_REQUEST['AdminUsers'];
		$OldAdmin = $_REQUEST['OldAdmin'];
	}
	else
	{
		echo '<table border="1" bgcolor="red"><tr><td>This page has been reached 
		in error.</td></tr></table>';		
		exit();
	}
}

//Generate page:
if(isset($_SESSION['heavyweight']) && isset($_SESSION['username']))
{
	if ($_SESSION['username']=='AdminX')
	{
		//Trap for user mistakenly selecting to change to current admin:
		if ($OldAdmin==$NewAdmin)
		{
			echo 'You have chosen the current account owner. No change will be 
			made.<br />';
			//Create Return button:
			$url = '"http://www.mp41.com/be/be_index/"';
			echo "<input type='button' value='RETURN' ONCLICK='window.location.href=" . 
			$url . "'>";
			exit();
		}
		
		//MySQL:
		require_once (MYSQL);

		//Query to find names for submitted old owner:
	    $q = "SELECT first_name, last_name 
		FROM users 
		WHERE id=$OldAdmin";

	    $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
	    Error: " . mysqli_error($dbc));
		
		$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
		$OldAdminName = $row['first_name'] . " " . $row['last_name'];
		
		//Query to find names for submitted new owner:
	    $q = "SELECT first_name, last_name 
		FROM users 
		WHERE id=$NewAdmin";

	    $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
	    Error: " . mysqli_error($dbc));
		
		$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
		$NewAdminName = $row['first_name'] . " " . $row['last_name'];

		echo 'Current Account Owner (' . $OldAdminName . ') will be orphaned and ' . 
		$NewAdminName . ' will be the new Account Owner for this account.<br /><br />';
		echo '<b>Are You Sure?</b>';
		
		//Create Yes button, hidden inputs to store old and new admin numbers for use in submission code:
		echo '<form action="/be/be_index/change_admin/">
		<input type="submit" value="YES" name="submitAdminChange">
		<input type="hidden" name="submitted" value="TRUE" />
		<input type="hidden" name="OldAdminID" value="';
		if (isset($OldAdmin)) echo $OldAdmin;
		echo '" />';
		echo '<input type="hidden" name="NewAdminID" value="';
		if (isset($NewAdmin)) echo $NewAdmin;
		echo '" />';
		
		//Create No button:
		$url = '"http://www.mp41.com/be/be_index/"';
		echo "<input type='button' value='NO' ONCLICK='window.location.href=" . 
		$url . "'>";
		echo '</form>';
	}

}
else
{
	$url = '/be/';
	header("Location: $url");
	exit();
}
?>