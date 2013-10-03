<?php #config.inc.php
//Script to define constants, settings, error handling, useful functions

//Created by R. Neel, 5/20/08 for Marketing Payback app

//Settings**************************************

//Flag variables for site status:
define('LIVE', FALSE);

//Admin contact address:
define('EMAIL', 'rayfield.neel@gmail.com');

//Location of the MySQL connection script:
define('MYSQL', '/home/rneel/web/mysqli_connect.php');

//Variables for URL management:
define('BASE_URL', '/');
define('LOGIN', '/login/');
define('BASELOC', '/home/rneel/web/htdocs/');

//Adjust the time zone for PHP 5.1 and greater:
//date_default_timezone_set('US/Eastern');

//SETTINGS************************************

//ERROR MANAGEMENT**************************

//Create the error handler:
function my_error_handler($e_number, $e_message, $e_file, $e_line, $e_vars)
{
  //Build the error message:
  $message = "<p>An error occurred in script '$e_file' on line $e_line:
  $e_message\n<br />";
  
  //Add the date and time:
  $message .= "Date/Time: " . date('n-j-Y H:i:s') . "\n<br />";
  
  //Append $e_vars to the $message:
  $message .= "<pre>" . print_r ($e_vars,1) . "</pre>\n</p>";
  
  if(!LIVE)
  {
    //Developement (print the error):
	echo '<div id="Error">' . $message . '</div><br />';
  }
  else
  {
    //Don't show the error
    //Send an email to the admin:
	mail(EMAIL, 'Site Error!', $message, 'From: email@192.168.1.200');
	
	//Only print an error message if the error isn't a notice:
	if($e_number != E_NOTICE)
	{
	  echo '<div id="Error">A system error occurred. We apologize for the 
	  inconvenience.</div><br />';
	}
  }//End of LIVE IF

}//End of my_error_handler defintion

//Use my error handler:
if ($_SERVER['PHP_SELF']!='/create/actionpage/new/index.php' && $_SERVER['PHP_SELF']!='/build/actionpages/new/index.php'
	&& $_SERVER['PHP_SELF']!='/build/actionpages/edit/index.php' && $_SERVER['PHP_SELF']!='/manage/actionpages/edit/index.php') {
	set_error_handler('my_error_handler');
}

//ERROR MANAGEMENT**************************

?>