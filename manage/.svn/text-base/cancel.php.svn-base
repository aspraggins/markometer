<?php # Cancel campaign changing sequence
ob_start();
if (session_id()=="") session_start(); 
	
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
$_SESSION['campaign_phone'] = NULL; unset($_SESSION['campaign_phone']);

//Redirect back to create home page:
$url = '/manage/';
header("Location: $url");
?>
