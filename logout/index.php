<?php # Logout
$metatitle = 'Logout';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');

$_SESSION['multiaccounts'] = array();

// Destroy session variables to logout, redirect to login page:
if (isset($_SESSION['first_name']))
{
	// Log out the user
	$_SESSION = array(); // Destroy the variables
	session_destroy(); // Destroy the session itself
	setcookie (session_name(), '', time()-300);// Destroy the cookie
	if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass']))
	{
	   setcookie("cookname", "", time()-60*60*24*100, "/");
	   setcookie("cookpass", "", time()-60*60*24*100, "/");
	}				
  
}
$url = BASE_URL . '/'; // Define the URL
ob_end_clean(); // Delete the buffer
header("Location: /");
exit(); // Quit the script

include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
