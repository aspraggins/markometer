<?php # complete script for followups..sets due_date for this lead to base value
//Programmer: Ray Neel 9/2009
require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.inc.php");
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
//If not logged in, or not an admin for this account, then  redirect the user:
if ( (!isset($_SESSION['first_name']))) {
	ob_end_clean(); //Delete the buffer
	header("Location: /");
	exit(); //Quit the script
}

//Check incoming passed id variable:
//Check for valid ID being passed:
if ( isset($_REQUEST['id']) && is_numeric($_REQUEST['id']) && isset($_REQUEST['note']) ) {
	$idlead = $_REQUEST['id'];
	$note = $_REQUEST['note'];
} else {
	echo '
<h1>This page has been reached in error.</h1>
<div class="main-block main-block-add">
<div class="heading">
<h2>Use the navigation above to extricate yourself from this mess.</h2>
</div>
</div>
';
	exit();
}

//MySQL setup:
require_once(MYSQL);

$q = "DELETE FROM lead_followups WHERE lead_id='$idlead' LIMIT 1";

$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
Error: " . mysqli_error ($dbc));

if (mysqli_affected_rows ($dbc) != 1) {			
	echo '
<h1>An error occurred.</h1>
<div class="main-block main-block-add">
<div class="heading">
<h2>Please try your request again. We apologize for the inconvenience.</h2>
</div>
</div>
';
	exit();
}

if($note=='yes') {
	// Record note if followup was "completed", as opposed to "deleted":
	$notesmsg = 'Follow Up For This Lead Completed!';
	$username = $_SESSION['first_name'] . " " . $_SESSION['last_name'];

	$q = "INSERT INTO notes (note, lead_id, type, last_updated, date_created, username) " . 
	"VALUES('$notesmsg', '$idlead', 'N', NOW(), NOW(), '$username')";

	$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
	. mysqli_error($dbc));

	if (mysqli_affected_rows ($dbc) != 1) {			
		echo '
<h1>An error occurred.</h1>
<div class="main-block main-block-add">
<div class="heading">
<h2>Please try your request again. We apologize for the inconvenience.</h2>
</div>
</div>
';
		exit();
	}
}

$url = '/leads/manage/id/?id=' . $idlead;
header("Location: $url");
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php');
?>