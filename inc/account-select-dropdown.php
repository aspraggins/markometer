<?php
//Script to select new account from header's account pulldown menu test

require_once ('config.inc.php');
$page_title = 'Account Selection';
include ('header.php');

//Check for valid ID being passed:
if ( (isset($_REQUEST['AccountID'])) && (is_numeric($_REQUEST['AccountID'])) )
{
	$account_id = $_REQUEST['AccountID'];
}
else
{
	echo '<p class="error">This page has been reached in error.</p>';
	exit();
}

require_once (MYSQL);

//Look up selected account based on passed account_id value:
$_SESSION['account_id'] = $account_id;

$q = "SELECT company, mp_subdomain, ccexp_month, ccexp_year, active, new as newaccount " . 
    "FROM accounts " . 
    "WHERE id='$account_id'";

$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
	Error: " . mysqli_error($dbc));

$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
$_SESSION['accountname'] = $row['company'];
$_SESSION['mp_subdomain'] = $row['mp_subdomain'];
$_SESSION['ccexp_month'] = $row['ccexp_month'];
$_SESSION['ccexp_year'] = $row['ccexp_year'];
$_SESSION['account_active'] = $row['active'];
$_SESSION['newaccount'] = $row['newaccount'];

//Look up role,  assigns_leads data based on account_id and user id value:
$q = "SELECT role, assigns_leads 
FROM account_lookup 
WHERE account_id='$account_id' AND user_id={$_SESSION['id']}";

$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
	Error: " . mysqli_error($dbc));

$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
$_SESSION['role'] = $row['role'];
$_SESSION['assignsleads'] = $row['assigns_leads'];

mysqli_free_result($r);
mysqli_close($dbc);

//Redirect and exit script:
ob_end_clean(); //Delete the buffer
$url = $_SERVER['HTTP_REFERER'];
header("Location: $url");
exit(); //Quit the script

?>