<?php # code for ajax quick save of actionpage that user is creating:

//Initialize a session if one not already in use:
if (session_id()=="") session_start();

require_once('/home/rneel/web/htdocs/includes/config.inc.php');

//database connection:
require_once (MYSQL);
	
//Grab value passed from campaign page:
$title = mysqli_real_escape_string($dbc, trim($_GET['title']));
$content = mysqli_real_escape_string($dbc, trim($_GET['content']));	
	
$q = "INSERT INTO action_pages " . 
"(account_id, name, action_page, date_created, last_updated) " . 
"VALUES('{$_SESSION['account_id']}', '$title', '$content', NOW(), NOW())";

$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
Error: " . mysqli_error($dbc));

//Check success of action page table insert:
if (mysqli_affected_rows($dbc) == 1) {
	echo "<font color='green'>Saved!</font>";
} else {
	echo "<font color='red'>There has been a problem saving your action page.</font>";
}
?>

