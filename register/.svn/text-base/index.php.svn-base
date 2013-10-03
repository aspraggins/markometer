<?php # Registration Page
// HTML header for the Registration page

//Start output buffering:
ob_start();

//Initialize a session if one not already in use:
if (session_id()=="") session_start();

//Include the configuration file:
require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Sign Up for an Account</title>

<link rel="stylesheet" href="/css/all.css" media="screen" type="text/css"/>

<!--[if lt IE 8]><link rel="stylesheet" type="text/css" href="css/ie.css" media="screen"/><![endif]-->

<script type="text/javascript" src="/js/prototype-1.6.1.js"></script>
<script type="text/javascript" src="/js/effects.js"></script>
<script type="text/javascript" src="/js/prototype.main.js"></script>
<script type="text/javascript" src="/js/common.js"></script>

<script type='text/javascript'>
//Ajax Initialize:
window.onload = init;

function init() {
	var ajax = getXMLHttpRequestObject();
}

//Ajax code to hit MySQL datebase to look for availability of site address:
function checkURL(name, field) {
	//Firstly check field to see if anything is there:
	if(name.length<2) { 
		document.getElementById('siteaddressresults').innerHTML='<font color="red">Please type in up to 200 characters for your site address!</font>';
		return false;
	} /*else {
		document.getElementById('nameresults').innerHTML='<font color="green">Okay!</font>';		
	}*/

	var ajax = getXMLHttpRequestObject();
	//var cname = document.getElementById('campaignname').value;
	var cname = name;
	var requestURL = 'validate_sql.php?name='+cname;
	ajax.open('get', requestURL);
	ajax.onreadystatechange = function() {
		handleResponse(ajax);
	}
	ajax.send(null);
	return;
}

//Handle output from Ajax request:
function handleResponse(ajax) {
	if (ajax.readyState==4) {
		if ((ajax.status==200) || (ajax.status==304)) {
			var results = document.getElementById('siteaddressresults');
			results.innerHTML = ajax.responseText;
			results.style.display = 'block';
		} else {
			//document.getElementById('autoform').submit();
		}
	}
}
</script>

</head>

<body class="signin-page" onload="javascript:document.getElementById('firstname').focus();">

<div id="wrapper" class="login-page">

<!-- Header -->
<div id="header">
	<div class="header-holder">
		<div class="bar">
		<strong class="logo"><a href="#"><img src="/images/default-logo.png" alt="logo" width="400" height="100" /></a></strong>
		<h1>Create Your Account For Free</h1>
		</div>
	</div>
</div>

<div class="login-box error">
<div class="login-holder">
<div class="login-frame">

<form action="/register/" id="signup" name="signup" class="signin-form" method="post">
<fieldset>

<?php 
if (isset($_POST['submitted']))
{
    //Handle the form
    require_once(MYSQL);
  
    //Trim all of the incoming data:
    $trimmed = array_map('trim', $_POST);
  
    //Assume invalid values:
    $fn = $ln = $e = $p = $co = $siteaddr = $ccnum = $ccmonth = $ccyear = $bz = $cctype = FALSE;
  
    //Check for a first name:
    if(preg_match('/^[A-Z \'.-]{2,20}$/i', $trimmed['first_name'])) {
        $fn = mysqli_real_escape_string($dbc, $trimmed['first_name']);
    } else {
			echo '<div class="error-box"><div class="error-holder"><div class="error-frame"><span>Please enter your first name!</span></div></div></div>';	
    }

    //Check for a last name:
    if(preg_match('/^[A-Z \'.-]{2,40}$/i', $trimmed['last_name'])) {
		$ln = mysqli_real_escape_string($dbc, $trimmed['last_name']);
    } else {
			echo '<div class="error-box"><div class="error-holder"><div class="error-frame"><span>Please enter your last name!</span></div></div></div>';
    }
  
    //Check for an email address:
    if(preg_match('/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/', $trimmed['email'])) {
        $e = strtolower(mysqli_real_escape_string($dbc, $trimmed['email']));
    } else {
			echo '<div class="error-box"><div class="error-holder"><div class="error-frame"><span>Please enter a valid email address!</span></div></div></div>';
    }

    //Check for a company:
    if(preg_match('/^[A-Z0-9 \',_.-]{2,40}$/i', $trimmed['company'])) {
		$co = mysqli_real_escape_string($dbc, $trimmed['company']);
    } else {
			echo '<div class="error-box"><div class="error-holder"><div class="error-frame"><span>Please enter your company name!</span></div></div></div>';
    }
  
    //Check for a password and match against the confirmed password:
    if(preg_match ('/^\w{4,20}$/', $trimmed['password1']) )
    {
		if ($trimmed['password1'] == $trimmed['password2']) {
			$p = mysqli_real_escape_string($dbc, $trimmed['password1']);
		} else {
			echo '<div class="error-box"><div class="error-holder"><div class="error-frame"><span>Your passwords did not match!</span></div></div></div>';
		}
	
    } else {
			echo '<div class="error-box"><div class="error-holder"><div class="error-frame"><span>Please enter a valid password (letters and numbers only)!</span></div></div></div>';
    }

    //Check for a valid Site Address:
    if(preg_match('/^[A-Z0-9-]{3,40}$/i', $trimmed['mpsiteaddress'])) {
		$siteaddr = strtolower(mysqli_real_escape_string($dbc, $trimmed['mpsiteaddress']));
    } else {
			echo '<div class="error-box"><div class="error-holder"><div class="error-frame"><span>Please enter a valid Markometer site address!</span></div></div></div>';
    }
  
    //Check for a valid Credit Card Number, discover type of card from number:
    if( preg_match('/^[0-9 ]{10,25}$/i', $trimmed['creditcardnumber']))
	{
		$tempstring = substr($trimmed['creditcardnumber'],0,1);
		switch ($tempstring)
		{
			case '3':
				$cctype = 'AMEX';
				break;
			case '4':
				$cctype = 'VISA';
				break;
			case '5':
				$cctype = 'MC';
				break;
			default:
			echo '<div class="error-box"><div class="error-holder"><div class="error-frame"><span>Please enter a valid credit card number!</span></div></div></div>';
				break;
		}
		$ccnum = mysqli_real_escape_string($dbc, $trimmed['creditcardnumber']);		
    }
    else
    {
			echo '<div class="error-box"><div class="error-holder"><div class="error-frame"><span>Please enter a valid credit card number!</span></div></div></div>';
    }

    //Check for a billing zip code:
    if(preg_match('/^[0-9 -]{5,10}$/i', $trimmed['billingzipcode'])) {
		$bz = mysqli_real_escape_string($dbc, $trimmed['billingzipcode']);
    } else {
			echo '<div class="error-box"><div class="error-holder"><div class="error-frame"><span>Please enter your billing zip code!</span></div></div></div>';
    }
	
    //$ccbrand = mysqli_real_escape_string($dbc, $trimmed['creditcardbrand']);
    $ccmonth = mysqli_real_escape_string($dbc, $trimmed['ccmonth']);  
    $ccyear = mysqli_real_escape_string($dbc, $trimmed['ccyear']);  
    $timezone = mysqli_real_escape_string($dbc, $trimmed['timezone']);
  
    if ($fn && $ln && $e && $p && $co && $siteaddr && $ccmonth && $ccyear && $timezone && $bz && $cctype && $ccnum) 
    {
	    //Initialize email and subdomain check variables:
		$echeck = $subcheck = FALSE;
		
	    //if everything's okay...
		//Make sure the email address is available:
		$q = "SELECT id FROM users WHERE email='$e'";
		$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
		mysqli_error($dbc));
		
		if (mysqli_num_rows($r) == 0) $echeck = TRUE;

		//Make sure the selected subdomain is available:
		$q = "SELECT id FROM accounts WHERE mp_subdomain='$siteaddr'";
		$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
		mysqli_error($dbc));
		
		if (mysqli_num_rows($r) == 0) $subcheck = TRUE;
		
	    //Change below to evaluate success of email and subdomain check:
		if ($echeck && $subcheck)
		{
	  
		    //Initialize SQL Insert check variables:
		    $userinsert = $accountinsert = $account_lookupinsert = FALSE;
		  
		    //Add the user to the account table:
		    $q = "INSERT INTO accounts (company, date_created, mp_subdomain, ccnumber, 
		    ccexp_year, ccexp_month, active, agency, bill_agency, billing_zipcode, 
			cctype, new) 
		    VALUES('$co', NOW(), '$siteaddr', '$ccnum', '$ccyear', '$ccmonth', 'Y', 
			'Y', 'Y', '$bz', '$cctype', 'Y')";
		    $r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
		    . mysqli_error($dbc));
		  
		    //Check success of account table insert:
		    if (mysqli_affected_rows($dbc) == 1) { 
				$accountinsert = TRUE;
		    }

		    //Do SQL hit to acquire new account_id for use in the user table insert:
		    $q = "SELECT id FROM accounts WHERE mp_subdomain='$siteaddr'";
		    $r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
		    . mysqli_error($dbc));
	        $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
		    $account_id = $row['id'];
		  
		    //Add the user to the user table:
		    $q = "INSERT INTO users (email, password, first_name, last_name, date_created, 
		    timezone, default_account, company, new, master_account) 
		    VALUES('$e', ENCODE('$p','{ENCODEPASS}'), '$fn', '$ln', NOW(), '$timezone', 
			'$account_id', '$co', 'Y', '$account_id')";
		
		    $r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
		    . mysqli_error($dbc));

		    //Check success of user table insert:
		    if (mysqli_affected_rows($dbc) == 1) $userinsert = TRUE;	  

			//Do SQL hit to acquire new user_id for use in the user_openid and account_lookup table inserts:
			$q = "SELECT id FROM users WHERE email='$e'";

			$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
			. mysqli_error($dbc));

			$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
			$user_id = $row['id'];

			//Add in record to account_lookup to link user with account:
		    $q = "INSERT INTO account_lookup (user_id, account_id, role, assigns_leads) 
		    VALUES('$user_id', '$account_id', 'O', 'N')";
		    
			$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
		    . mysqli_error($dbc));

			//Check success of account_lookup table insert;
		    if (mysqli_affected_rows($dbc) == 1) $account_lookupinsert = TRUE;	  
			
			//Put in "created_by" field in newly created master account, so traceable to this user/admin:
			$q = "UPDATE accounts 
			SET created_by=$user_id 
			WHERE id=$account_id 
			LIMIT 1";
			
			$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
		    . mysqli_error($dbc));
		
			//Create action page images directory:
			$imagedir = $_SERVER['DOCUMENT_ROOT'] . '/images/actionpages/' . $account_id . '/';
			$md = mkdir($imagedir);
			
		    //If both SQL Inserts were successful:
		    if ($userinsert && $accountinsert && $account_lookupinsert)
		    {
			    //If OpenID identity given, add to user_openids database:
			    /*if ($oid)
				{
					//Insert new Openid record:
					$q = "INSERT INTO user_openids (openid_url, user_id)
					VALUES('$oid', '$user_id')";
		            $r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
			        . mysqli_error($dbc));
				}*/

		        //If it ran okay
				//open template for this email:
				$fopenstring = $_SERVER["DOCUMENT_ROOT"] . '/templates/emails/welcomemail.txt';
				$fp = @fopen($fopenstring, "rb") or die("Couldn't open file");
				$data = '';
				while(!feof($fp)) {
					$data .= fgets($fp, 1024);	
				}	
				fclose($fp); 

				//Process $data to replace tags ([fname] and [recipient]) with strings:
				$data = str_replace("[fname]", $fn, $data);
				$body = str_replace("[login]", $e, $data);
		        
				//Send the email:
				$from = 'From:Markometer@mp41.com';
				//$body = "Thank you for registering at Markometer!\nYour login will be:\n$e\n";
				mail($trimmed['email'],
				'Markometer Registration', $body, $from);
				
				//Finish the page:
				//Redirect to login screen with username filled in for them:
				$url = 'http://' . $siteaddr . '.mp41.com/login/?email=' . $e;
				header("Location: $url");
				exit();//Stop the page
	     	}
		    else
		    {
			    //If it did not run okay
				echo '<div class="error-box"><div class="error-holder"><div class="error-frame"><span>You could not be registered due to a system error. We apologize for any inconvenience.</span></div></div></div>';
		    }
		}
		else
		{
		    if ($subcheck)
			{
		      //The email address is not available
				echo '<div class="error-box"><div class="error-holder"><div class="error-frame"><span>That email address has already been registered.</span></div></div></div>';
			}
			else
			{
			  //The selected subdomain is not available
				echo '<div class="error-box"><div class="error-holder"><div class="error-frame"><span>That site address has already been registered.</span></div></div></div>';
			}
		}
    }
    else
    {
	    //If one of the data tests failed
				echo '<div class="error-box"><div class="error-holder"><div class="error-frame"><span>Please re-enter your passwords and try again.</span></div></div></div>';
    }
	
	mysqli_close($dbc);
} //End of main Submit conditional
?>

<div class="row">
	<div class="col">
		<label>First Name</label>
		<input tabindex="1" type="text" class="text short" name="first_name" maxlength="40" id="firstname" value="<?php if (isset($trimmed['first_name'])) echo $trimmed['first_name']; ?>" />
	</div>
	<div class="col">
		<label>Last Name</label>
		<input tabindex="2" type="text" class="text short" name="last_name" maxlength="40" value="<?php if (isset($trimmed['last_name'])) echo $trimmed['last_name']; ?>" />
	</div>
</div>
<div class="row">
	<label>Email <span>(this will be your login)</span></label>
	<input tabindex="3" type="text" class="text" name="email" maxlength="80" value="<?php if (isset($trimmed['email'])) echo $trimmed['email']; ?>" />
</div>
<div class="row">
	<label>Company</label>
	<input tabindex="4" type="text" name="company" maxlength="40" class="text" value="<?php if (isset($trimmed['company'])) echo $trimmed['company']; ?>" />
</div>
<div class="row">
	<label>Time Zone</label>
	<select tabindex="5" id="timezone" name="timezone">
<option value="Hawaii" selected="selected">(GMT-10:00) Hawaii</option>
<option value="Alaska">(GMT-09:00) Alaska</option>
<option value="Pacific Time (US &amp; Canada)">(GMT-08:00) Pacific (US &amp; Canada)</option>
<option value="Arizona">(GMT-07:00) Arizona</option>
<option value="Mountain Time (US &amp; Canada)">(GMT-07:00) Mountain (US &amp; Canada)</option>
<option value="Central Time (US &amp; Canada)">(GMT-06:00) Central (US &amp; Canada)</option>
<option value="Eastern Time (US &amp; Canada)">(GMT-05:00) Eastern (US &amp; Canada)</option>
<option value="Indiana (East)">(GMT-05:00) Indiana (East)</option>
<option value="">-------------</option>
<option value="International Date Line West">(GMT-11:00) International Date Line West</option>
<option value="Midway Island">(GMT-11:00) Midway Island</option>
<option value="Samoa">(GMT-11:00) Samoa</option>
<option value="Tijuana">(GMT-08:00) Tijuana</option>
<option value="Chihuahua">(GMT-07:00) Chihuahua</option>
<option value="Mazatlan">(GMT-07:00) Mazatlan</option>
<option value="Central America">(GMT-06:00) Central America</option>

<option value="Guadalajara">(GMT-06:00) Guadalajara</option>
<option value="Mexico City">(GMT-06:00) Mexico City</option>
<option value="Monterrey">(GMT-06:00) Monterrey</option>
<option value="Saskatchewan">(GMT-06:00) Saskatchewan</option>
<option value="Bogota">(GMT-05:00) Bogota</option>
<option value="Lima">(GMT-05:00) Lima</option>
<option value="Quito">(GMT-05:00) Quito</option>
<option value="Atlantic Time (Canada)">(GMT-04:00) Atlantic Time (Canada)</option>
<option value="Caracas">(GMT-04:00) Caracas</option>

<option value="La Paz">(GMT-04:00) La Paz</option>
<option value="Santiago">(GMT-04:00) Santiago</option>
<option value="Newfoundland">(GMT-03:30) Newfoundland</option>
<option value="Brasilia">(GMT-03:00) Brasilia</option>
<option value="Buenos Aires">(GMT-03:00) Buenos Aires</option>
<option value="Georgetown">(GMT-03:00) Georgetown</option>
<option value="Greenland">(GMT-03:00) Greenland</option>
<option value="Mid-Atlantic">(GMT-02:00) Mid-Atlantic</option>
<option value="Azores">(GMT-01:00) Azores</option>

<option value="Cape Verde Is.">(GMT-01:00) Cape Verde Is.</option>
<option value="Casablanca">(GMT) Casablanca</option>
<option value="Dublin">(GMT) Dublin</option>
<option value="Edinburgh">(GMT) Edinburgh</option>
<option value="Lisbon">(GMT) Lisbon</option>
<option value="London">(GMT) London</option>
<option value="Monrovia">(GMT) Monrovia</option>
<option value="Amsterdam">(GMT+01:00) Amsterdam</option>
<option value="Belgrade">(GMT+01:00) Belgrade</option>

<option value="Berlin">(GMT+01:00) Berlin</option>
<option value="Bern">(GMT+01:00) Bern</option>
<option value="Bratislava">(GMT+01:00) Bratislava</option>
<option value="Brussels">(GMT+01:00) Brussels</option>
<option value="Budapest">(GMT+01:00) Budapest</option>
<option value="Copenhagen">(GMT+01:00) Copenhagen</option>
<option value="Ljubljana">(GMT+01:00) Ljubljana</option>
<option value="Madrid">(GMT+01:00) Madrid</option>
<option value="Paris">(GMT+01:00) Paris</option>

<option value="Prague">(GMT+01:00) Prague</option>
<option value="Rome">(GMT+01:00) Rome</option>
<option value="Sarajevo">(GMT+01:00) Sarajevo</option>
<option value="Skopje">(GMT+01:00) Skopje</option>
<option value="Stockholm">(GMT+01:00) Stockholm</option>
<option value="Vienna">(GMT+01:00) Vienna</option>
<option value="Warsaw">(GMT+01:00) Warsaw</option>
<option value="West Central Africa">(GMT+01:00) West Central Africa</option>
<option value="Zagreb">(GMT+01:00) Zagreb</option>

<option value="Athens">(GMT+02:00) Athens</option>
<option value="Bucharest">(GMT+02:00) Bucharest</option>
<option value="Cairo">(GMT+02:00) Cairo</option>
<option value="Harare">(GMT+02:00) Harare</option>
<option value="Helsinki">(GMT+02:00) Helsinki</option>
<option value="Istanbul">(GMT+02:00) Istanbul</option>
<option value="Jerusalem">(GMT+02:00) Jerusalem</option>
<option value="Kyev">(GMT+02:00) Kyev</option>
<option value="Minsk">(GMT+02:00) Minsk</option>

<option value="Pretoria">(GMT+02:00) Pretoria</option>
<option value="Riga">(GMT+02:00) Riga</option>
<option value="Sofia">(GMT+02:00) Sofia</option>
<option value="Tallinn">(GMT+02:00) Tallinn</option>
<option value="Vilnius">(GMT+02:00) Vilnius</option>
<option value="Baghdad">(GMT+03:00) Baghdad</option>
<option value="Kuwait">(GMT+03:00) Kuwait</option>
<option value="Moscow">(GMT+03:00) Moscow</option>
<option value="Nairobi">(GMT+03:00) Nairobi</option>

<option value="Riyadh">(GMT+03:00) Riyadh</option>
<option value="St. Petersburg">(GMT+03:00) St. Petersburg</option>
<option value="Volgograd">(GMT+03:00) Volgograd</option>
<option value="Tehran">(GMT+03:30) Tehran</option>
<option value="Abu Dhabi">(GMT+04:00) Abu Dhabi</option>
<option value="Baku">(GMT+04:00) Baku</option>
<option value="Muscat">(GMT+04:00) Muscat</option>
<option value="Tbilisi">(GMT+04:00) Tbilisi</option>
<option value="Yerevan">(GMT+04:00) Yerevan</option>

<option value="Kabul">(GMT+04:30) Kabul</option>
<option value="Ekaterinburg">(GMT+05:00) Ekaterinburg</option>
<option value="Islamabad">(GMT+05:00) Islamabad</option>
<option value="Karachi">(GMT+05:00) Karachi</option>
<option value="Tashkent">(GMT+05:00) Tashkent</option>
<option value="Chennai">(GMT+05:30) Chennai</option>
<option value="Kolkata">(GMT+05:30) Kolkata</option>
<option value="Mumbai">(GMT+05:30) Mumbai</option>
<option value="New Delhi">(GMT+05:30) New Delhi</option>

<option value="Kathmandu">(GMT+05:45) Kathmandu</option>
<option value="Almaty">(GMT+06:00) Almaty</option>
<option value="Astana">(GMT+06:00) Astana</option>
<option value="Dhaka">(GMT+06:00) Dhaka</option>
<option value="Novosibirsk">(GMT+06:00) Novosibirsk</option>
<option value="Sri Jayawardenepura">(GMT+06:00) Sri Jayawardenepura</option>
<option value="Rangoon">(GMT+06:30) Rangoon</option>
<option value="Bangkok">(GMT+07:00) Bangkok</option>
<option value="Hanoi">(GMT+07:00) Hanoi</option>

<option value="Jakarta">(GMT+07:00) Jakarta</option>
<option value="Krasnoyarsk">(GMT+07:00) Krasnoyarsk</option>
<option value="Beijing">(GMT+08:00) Beijing</option>
<option value="Chongqing">(GMT+08:00) Chongqing</option>
<option value="Hong Kong">(GMT+08:00) Hong Kong</option>
<option value="Irkutsk">(GMT+08:00) Irkutsk</option>
<option value="Kuala Lumpur">(GMT+08:00) Kuala Lumpur</option>
<option value="Perth">(GMT+08:00) Perth</option>
<option value="Singapore">(GMT+08:00) Singapore</option>

<option value="Taipei">(GMT+08:00) Taipei</option>
<option value="Ulaan Bataar">(GMT+08:00) Ulaan Bataar</option>
<option value="Urumqi">(GMT+08:00) Urumqi</option>
<option value="Osaka">(GMT+09:00) Osaka</option>
<option value="Sapporo">(GMT+09:00) Sapporo</option>
<option value="Seoul">(GMT+09:00) Seoul</option>
<option value="Tokyo">(GMT+09:00) Tokyo</option>
<option value="Yakutsk">(GMT+09:00) Yakutsk</option>
<option value="Adelaide">(GMT+09:30) Adelaide</option>

<option value="Darwin">(GMT+09:30) Darwin</option>
<option value="Brisbane">(GMT+10:00) Brisbane</option>
<option value="Canberra">(GMT+10:00) Canberra</option>
<option value="Guam">(GMT+10:00) Guam</option>
<option value="Hobart">(GMT+10:00) Hobart</option>
<option value="Melbourne">(GMT+10:00) Melbourne</option>
<option value="Port Moresby">(GMT+10:00) Port Moresby</option>
<option value="Sydney">(GMT+10:00) Sydney</option>
<option value="Vladivostok">(GMT+10:00) Vladivostok</option>

<option value="Magadan">(GMT+11:00) Magadan</option>
<option value="New Caledonia">(GMT+11:00) New Caledonia</option>
<option value="Solomon Is.">(GMT+11:00) Solomon Is.</option>
<option value="Auckland">(GMT+12:00) Auckland</option>
<option value="Fiji">(GMT+12:00) Fiji</option>
<option value="Kamchatka">(GMT+12:00) Kamchatka</option>
<option value="Marshall Is.">(GMT+12:00) Marshall Is.</option>
<option value="Wellington">(GMT+12:00) Wellington</option>
<option value="Nuku'alofa">(GMT+13:00) Nuku'alofa</option>

<?php 
if (isset($trimmed['timezone'])) {
	echo '<option value="' . $trimmed['timezone'] . '" selected="selected">' . 
	$trimmed['timezone'] . '</option>';
}
?>

	</select>
</div>
<div class="row">
	<label>Password <span>(letters and numbers, between 4 and 20 characters)</span></label>
	<input tabindex="6" type="password" name="password1" maxlength="40" class="text" />
</div>
<div class="row">
	<label>Confirm Password <span>(enter again to verify)</span></label>
	<input tabindex="7" type="password" name="password2" maxlength="40" class="text" />
</div>
<div class="holder">
	<h2>Create Your Site Address</h2>
	<strong>Every site has its own web address - letters and numbers only.</strong>
	<div class="row">
		<div class="row-holder">
			<span>http://</span>
			<input tabindex="8" type="text" name="mpsiteaddress" maxlength="40" value="<?php if (isset($trimmed['mpsiteaddress'])) echo $trimmed['mpsiteaddress']; ?>" class="text" onblur="checkURL(document.signup.mpsiteaddress.value)" />
			<span>.mk41.com</span>
		</div>
		<div class="error" id="siteaddressresults"></div>
	</div>
</div>
<div class="holder">
	<h3>Billing Informations (SECURE)</h3>
	<strong>You <em>won't be billed</em> until you create a campaign or enter leads.</strong>
	<div class="row">
		<label>Credit Card Number <span>(we only take credit cards)</span></label>
		<div class="row-holder">
			<input tabindex="9" type="text" name="creditcardnumber" maxlength="25" value="" class="text middle" />
			<a href="#" class="ico"><img src="/images/cc-logos.gif" alt="ico" width="150" height="33" /></a>
		</div>
	</div>
	<div class="row">
		<label>Expires On</label>
		<div class="row-holder">
			<select tabindex="10" id="ccmonth" name="ccmonth">
			<option value="1" selected="selected">1 - January</option>
			<option value="2">2 - February</option>
			<option value="3">3 - March</option>
			<option value="4">4 - April</option>
			<option value="5">5 - May</option>
			<option value="6">6 - June</option>
			<option value="7">7 - July</option>
			<option value="8">8 - August</option>
			<option value="9">9 - September</option>
			<option value="10">10 - October</option>
			<option value="11">11 - November</option>
			<option value="12">12 - December</option>
			</select>
			<select tabindex="11" id="ccyear" name="ccyear">
			<option value="2011" selected="selected">2011</option>
			<option value="2012">2012</option>
			<option value="2013">2013</option>
			<option value="2014">2014</option>
			<option value="2015">2015</option>
			<option value="2016">2016</option>
			<option value="2017">2017</option>
			<option value="2018">2018</option>
			<option value="2019">2019</option>
			<option value="2020">2020</option>
			<option value="2021">2021</option>
			<option value="2022">2022</option>
			<option value="2023">2023</option>
			<option value="2024">2024</option>
			<option value="2025">2025</option>
			</select>
		</div>
	</div>
	<div class="row">
		<label>Billing Zip/Postal Code</label>
		<input tabindex="12" type="text" name="billingzipcode" maxlength="20" value="<?php if (isset($trimmed['billingzipcode'])) echo $trimmed['billingzipcode']; ?>" class="text" />
	</div>
	<div class="text-holder">
		<p>By clicking Create Account you agree to the <a href="#">Terms of Service</a>, <a href="#">Privacy</a>, and <a href="#">Refund</a> Policies.</p>
	</div>
</div>
<div class="content-link">
	<div class="link-holder">
		<div class="link-frame">
			<a href="#" class="btn" onclick="document.getElementById('signup').submit()" /><span>Create Account &gt;</span></a>
		</div>
	</div>
</div>
<input type="hidden" name="submitted" value="TRUE" />

</fieldset>
</form>
</div>
</div>
</div>

<br/><br/>

</div>
</body>
</html>
