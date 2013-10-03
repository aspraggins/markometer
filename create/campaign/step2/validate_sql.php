<?php # code for ajax validation of campaign start page

//Initialize a session if one not already in use:
if (session_id()=="") session_start();

require_once('/home/rneel/web/htdocs/includes/config.inc.php');

//database connection:
require_once (MYSQL);
	
//Grab value passed from campaign page:
$cname = trim($_GET['name']);
	
//regular expression validation of submitted string
$validated = FALSE;
if(preg_match('/^[A-Z0-9 \'.-]{2,200}$/i', trim($cname))) {
	$validated = TRUE;
}

$q = "SELECT * FROM campaigns WHERE name='$cname' AND account_id={$_SESSION['account_id']}";

$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
Error: " . mysqli_error($dbc));

$num = mysqli_num_rows($r);

if($num==0 && $validated) {
	echo '<font color="green">That campaign name is available!</font>';
} elseif(!$validated) {
	echo "Please enter between 2 and 20 characters.";	
} else {
	echo "<font color='red'>The campaign name <b>$cname</b> is taken, please try another.</font>";	
}

?>

