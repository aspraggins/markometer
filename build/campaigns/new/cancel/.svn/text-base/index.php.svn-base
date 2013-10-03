<?php # Cancel campaign creation
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
//ob_start();
//if (session_id()=="") session_start(); 
	
//Check for logged in user:
if (!isset($_SESSION['id'])) {
	header("Location: /");
	exit();
}

echo 'Canceling...';
	
//Unset session variables that stored campaign information:
$_SESSION['campaign_rate'] = NULL; unset($_SESSION['campaign_rate']);
$_SESSION['campaign_goal'] = NULL; unset($_SESSION['campaign_goal']);
$_SESSION['campaign_name'] = NULL; unset($_SESSION['campaign_name']);
$_SESSION['campaign_type'] = NULL; unset($_SESSION['campaign_type']);
$_SESSION['campaign_cost'] = NULL; unset($_SESSION['campaign_cost']);
$_SESSION['campaign_subject'] = NULL; unset($_SESSION['campaign_subject']);
$_SESSION['campaign_begin'] = NULL; unset($_SESSION['campaign_begin']);
$_SESSION['campaign_end'] = NULL; unset($_SESSION['campaign_end']);
$_SESSION['campaign_domain'] = NULL; unset($_SESSION['campaign_domain']);
$_SESSION['campaign_pnumber'] = NULL; unset($_SESSION['campaign_pnumber']);

//Delete "pending" dbase entries for campaign and leads:
//MYSQL Setup:
require_once(MYSQL);

//campaign
$q = "DELETE FROM campaigns " . 
	"WHERE account_id='{$_SESSION['account_id']}' AND imported='P'";

$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
. mysqli_error($dbc));

//leads
$q = "DELETE FROM leads " . 
	"WHERE account_id='{$_SESSION['account_id']}' AND status='P'";

$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
. mysqli_error($dbc));

// Redirect back to build campaigns home page:
$url = '/build/campaigns/';
header("Location: $url");
?>
