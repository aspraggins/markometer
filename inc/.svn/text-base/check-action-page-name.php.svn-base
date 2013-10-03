<?php # code for ajax validation of new action page title

//Initialize a session if one not already in use:
if (session_id()=="") session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.inc.php");

//database connection:
require_once (MYSQL);
	
//Grab value passed from campaign page:
$cname = trim($_GET['name']);
	
//regular expression validation of submitted string
$validated = FALSE;
if(preg_match('/^[A-Z0-9 \'.-]{2,100}$/i', trim($cname))) {
	$validated = TRUE;
}

$q = "SELECT * FROM action_pages WHERE name='$cname' AND account_id={$_SESSION['account_id']}";

$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));

$num = mysqli_num_rows($r);

if($num==0 && $validated) {
	echo "<font color='green'>That Action Page name is available!</font>";
} elseif(!$validated) {
	echo "<font color='red'>Please enter between 2 and 100 characters.</font>";	
} else {
	echo "<font color='red'>The Action Page name <b>$cname</b> is taken, please try another.</font>";	
}

?>

