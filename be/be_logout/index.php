<?php #be_logout

//Include the configuration file:
require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.inc.php");

//Set the page title and include the HTML header:
$page_title = 'Logout';
include('/home/rneel/web/htdocs/includes/header.html');

//Destroy session variables to logout, redirect to login page:
$_SESSION = array(); //Destroy the variables
session_destroy(); //Destroy the session itself
setcookie (session_name(), '', time()-300);//Destroy the cookie

$url = '/be/'; //Define the URL
ob_end_clean(); //Delete the buffer
header("Location: $url");
exit(); //Quit the script
?>
