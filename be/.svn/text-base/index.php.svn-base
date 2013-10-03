<?php # be_login.php

//table sa: fields username and password, plus heavyweight

//Include the configuration file:
require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.inc.php");

//Set the page title and include the HTML header:
$page_title = 'Marketing Payback';
$page_header = 'SuperAdmin Access';
include('/home/rneel/web/htdocs/includes/header.html');


if (isset($_SESSION['heavyweight'])) 
{
	header("Location: /be/be_index/");
	exit();
}

if (isset($_POST['submitted']))
{
    require_once (MYSQL);
  
    //Validate the username:
    if (!empty($_POST['username']))
    {
        $e = mysqli_real_escape_string ($dbc, $_POST['username']);
    }
    else
    {
        $e = FALSE;  
	    echo '<p class="error">Please enter your username!</p>';
    }
  
    //Validate the password:
    if (!empty($_POST['pass']))
    {
        $p = mysqli_real_escape_string ($dbc, $_POST['pass']);
    }
    else
    {
        $p = FALSE; 
        echo '<p class="error">Your forgot to enter your
	    password!</p>';
    }
  
    if ($e && $p)
    {
        //If everything's okay...
	    //Query the database for USER info:
	    $q = "SELECT * 
	    FROM sa 
	    WHERE username='$e' AND password=ENCODE('$p','{ENCODEPASS}')";
	  
	    $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
	    Error: " . mysqli_error($dbc));
	
	    if (@mysqli_num_rows($r) == 1)
	    {
            //A match was made
	        //Register the values and redirect:
		    $_SESSION = mysqli_fetch_array ($r, MYSQLI_ASSOC);
		    mysqli_free_result($r);
		  
		    ob_end_clean(); //Delete the buffer
			$url = '/be/be_index/'; //Define the URL
		    header("Location: $url");
		    exit(); //Quit the script
		}
		else
		{
	        //No match was made
		    echo '<p class="error">The email address and password do not 
		    seem to match those on file. If you believe that your email
		    is correct, please select Retrieve Password.</p>';
		}
    }
    else
    {
	    //If everything wasn't okay
		echo '<p class="error">Please try again</p>';
	}
  
    mysqli_close($dbc);
  
}//End of SUBMIT conditional
?>

<h1>Login</h1>
<form action="/be/" method="post">
  <fieldset>
  <p><b>Username:</b> <input type="text" name="username"
  size="20" maxlength="40" value="" /></p>
  
  <p><b>Password:</b> <input type="password" name="pass"
  size="20" maxlength="20" />
  <input type="submit" name="submit" value="Sign In" /></p>
  <input type="hidden" name="submitted" value="TRUE" />
  <br /><br />
  </fieldset>
</form>
