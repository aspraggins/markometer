<?php #hide_message functionality...not a displayed page, passes back to referring page. Called from header.
// Include the configuration file:
ob_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.inc.php");
if (session_id()=="") session_start();

//Check for valid ID being passed:
if ( (isset($_REQUEST['id'])) && (is_numeric($_REQUEST['id'])) )
{
	$message_id = $_REQUEST['id'];
}
else
{
	echo '<table border="1" bgcolor="red"><tr><td>This page has been reached in error.</td></tr></table>';		
	exit();
}

//Check for logged in, process message_user_lookup:
if (!isset($_SESSION['first_name']))
{
	$url = '/';
	header("Location: $url");
	exit();
}
else
{
    //MySQL setup:
    require_once(MYSQL);
  
	//Update message record to add hidden message:
	$q = "INSERT INTO message_user_lookup 
	SET message_id=$message_id, user_id={$_SESSION['id']}";
	
	$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
	mysqli_error($dbc));

	mysqli_close($dbc);

	//Remove message from message session array:
	$messagearray = array();
	$messagearray = $_SESSION['messages'];
	unset($_SESSION['messages']);
	unset($messagearray[$message_id]);
	$_SESSION['messages'] = $messagearray;
	
	//Return to referring page:
	ob_end_clean(); //Delete the buffer
	$url = $_SERVER['HTTP_REFERER'];
	header("Location: $url");
	exit(); //Quit the script
} 
?>