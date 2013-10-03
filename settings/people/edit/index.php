<?php # Settings - Edit User Page
$metatitle = 'Edit User';
$returnurl = '/login/?returnpage=settings/people/edit';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Heading and Help Button -->

<div class="heading">
<h1>Edit User</h1>
<?php # Help Button
$helphref = 'settings-edit-user.html';
$helptitle = 'Help with Editing a User';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
?>
</div>

<!-- Main Content -->

<?php

function UpdateInfo($fn,$ln,$e,$dbc,$timezone,$title,$officeno,$cellno,$co,$user_id,$qp,$qop) 
{
	//Set up default account part of query if multiaccount:
	$q3 = FALSE;
	if (isset($_POST['defaultaccount'])) $q3 = ", default_account={$_POST['defaultaccount']}";
	// See if anything in optional information textboxes, set variables to use in query:
	$qtitle = $qofficeno = $qcellno = FALSE;
	if (strlen($title)>0) $qtitle = ", title='$title'";
	if (strlen($officeno)>0) $qofficeno = ", office_number='$officeno'";
	if (strlen($cellno)>0) $qcellno = ", cell_number='$cellno'";

	//Set up variables to track success of database writes:
	$updatestatus = FALSE;

	// Update user info based on form:
	$q = "UPDATE users 
		 SET updateflag=1-updateflag, first_name='$fn', last_name='$ln', email='$e', company='$co', 
		 timezone='$timezone'$qp 
		 $qtitle$qofficeno$qcellno$q3
		 WHERE id='$user_id' $qop LIMIT 1";
		 		
	$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
	Error: " . mysqli_error ($dbc));
	
	if (mysqli_affected_rows ($dbc) == 1) { //check success of write, return value:
		$updatestatus = TRUE;
		return $updatestatus;
	} else {
		$updatestatus = FALSE;
		return $updatestatus;	
	}
}

//Form Submission
if (isset($_POST['submitted']))
{
    //MySQL setup:
    require_once(MYSQL);
  
	//Set $user_id to POST value:
	$user_id = trim($_REQUEST['id']);
  
    //Assume invalid values:
    $fn = $ln = $e = $co = $oid = $p = $op = FALSE;
  
    //Check for a first name:
    if(preg_match('/^[A-Z \'.-]{2,20}$/i', trim($_POST['first_name'])))
    {
        $fn = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
    }
    else
    {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Please enter a first name!</p>
			</div>
		</div>
	</div>
</div>';		
	}

    //Check for a last name:
    if(preg_match('/^[A-Z \'.-]{2,40}$/i', trim($_POST['last_name'])))
    {
		$ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
    }
    else
    {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Please enter a last name!</p>
			</div>
		</div>
	</div>
</div>';		    
    }
  
    //Check for an email address:
    if(preg_match('/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/', trim($_POST['email'])))
    {
        $e = strtolower(mysqli_real_escape_string($dbc, trim($_POST['email'])));
    }
    else
    {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Please enter a valid email address!</p>
			</div>
		</div>
	</div>
</div>';	
	}

    //check for a new password, match against the confirmed password:
    if (preg_match ('/^(\w){4,20}$/', $_POST['password1']) )
    {
		if ($_POST['password1'] == $_POST['password2'])
		{
			$p = mysqli_real_escape_string ($dbc, $_POST['password1']);
		}
		else
		{
			echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Your password did not match the confirmed password! Please retype your password!</p>
			</div>
		</div>
	</div>
</div>';
		}
    }
    else
    {
		if (strlen($_POST['password1'] > 0))  //no error if user leaves this blank
		{	
			echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Please enter a valid password!</p>
			</div>
		</div>
	</div>
</div>';
		}
    }

    //Check for correct old password:
    if(preg_match ('/^\w{4,20}$/', $_POST['oldpassword']) ) {  
		$op = mysqli_real_escape_string ($dbc, $_POST['oldpassword']);  
    }
  
    //Initialize string variables used in case of password change:
    $qp = $qop = FALSE;
    if ($p && $op) {
		//Create strings that will be added to SQL Update if password changed:
	    $qp = ", password=ENCODE('$p','{ENCODEPASS}')";
		$qop = "AND password=ENCODE('$op','{ENCODEPASS}')";
    }

    //Check for a company:
    if(preg_match('/^[A-Z0-9 \',_.-]{2,40}$/i', trim($_POST['company']))) {
		$co = mysqli_real_escape_string($dbc, trim($_POST['company']));
    } else {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Please enter a company name!</p>
			</div>
		</div>
	</div>
</div>';	
    }

	$timezone = mysqli_real_escape_string($dbc, trim($_POST['timezone']));
	$title = mysqli_real_escape_string($dbc, trim($_POST['title']));
	$officeno = mysqli_real_escape_string($dbc, trim($_POST['officeno']));
	$cellno = mysqli_real_escape_string($dbc, trim($_POST['cellno']));


    if (($fn && $ln && $e && $p && $op && $timezone && $co) || ($fn && $ln && $e && $timezone && $co && !$p && !$op))
    {
	    //if everything validates
		//Make sure the email address is available:
		$q = "SELECT id FROM users WHERE email='$e'";
		$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
		mysqli_error($dbc));

		if (mysqli_num_rows($r) == 0 || $_SESSION['email_entry'] == $e)
		{		//Update name, email, password information in database and session:
						if(UpdateInfo($fn,$ln,$e,$dbc,$timezone,$title,$officeno,$cellno,$co,$user_id,$qp,$qop))
						{

							echo '
			<div class="alert-holder">
				<div class="alert-box green">
					<div class="holder">
						<div class="frame">
						<a class="close" href="#">X</a>
						<p>Your settings have been saved successfully.</p>
						</div>
					</div>
				</div>
			</div>';

						}
						else
						{
							//If it did not run okay
							echo '
			<div class="alert-holder">
				<div class="alert-box red">
					<div class="holder">
						<div class="frame">
							<a class="close" href="#">X</a>
							<p>Your information was not changed. Make sure that	your old password is correct, if you attempted to change it. Contact the system administrator if you think an error has occurred.</p>
						</div>
					</div>
				</div>
			</div>';
						}
				//UpdateInfo($fn,$ln,$e,$dbc,$timezone,$title,$officeno,$cellno,$co,$user_id,$qp,$qop);
			}

			//Put salesadmin values in array:
			$assignsleadsarray = array();
			if (isset($_POST["assignsleads"])) {
				$assignsleadsarray = $_POST["assignsleads"];
			}

			//loop through accounts and update roles and salesadmin values to account_lookup:
			foreach ($_POST['role'] as $key => $roleoutput)
			{
				$assignsleadsvalue = 'N';
				if (in_array($key, $assignsleadsarray)) $assignsleadsvalue = 'Y';
				
				if ($roleoutput!='N')
				{
					$q = "SELECT * 
					FROM account_lookup
					WHERE user_id='$user_id' AND account_id='$key'";

					$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
				    . mysqli_error($dbc));
					
					if (mysqli_num_rows($r) == 1)
					{
						$q = "UPDATE account_lookup 
						SET role='$roleoutput', assigns_leads='$assignsleadsvalue' 
						WHERE user_id='$user_id' AND account_id='$key' LIMIT 1";
						
						$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
					    . mysqli_error($dbc));
					}
					else
					{
						$q = "INSERT INTO account_lookup (user_id, account_id, role, assigns_leads) 
					    VALUES('$user_id', '$key', '$roleoutput', '$assignsleadsvalue')";
					
						$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
					    . mysqli_error($dbc));
					}
				}
				else
				{
					$q = "SELECT * 
					FROM account_lookup
					WHERE user_id='$user_id' AND account_id='$key'";

					$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
				    . mysqli_error($dbc));
					
					if (mysqli_num_rows($r) == 1)
					{
						$q = "DELETE FROM account_lookup 
						WHERE user_id='$user_id' AND account_id='$key' LIMIT 1";
						
						$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
					    . mysqli_error($dbc));
					}
				}
			}
			/*echo '
<div class="alert-holder">
	<div class="alert-box green">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>This user\'s required settings have been saved successfully</p>
			</div>
		</div>
	</div>
</div>';*/
		}
		else
		{
            //The email address is not available
			echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>That	email address has already been registered.</p>
			</div>
		</div>
	</div>
</div>';
		}
    }


//Check incoming passed id variable:
//Check for valid ID being passed:
if ( (isset($_REQUEST['id'])) && (is_numeric($_REQUEST['id'])) ) {
	$user_id = $_REQUEST['id'];
} else {
	echo '<h1>This page has been reached in error.</h1>';
	exit();
}

require_once (MYSQL);

//Variables to check success of user and account_lookup SQL hits:
$usersuccess = $accountlookupsuccess = FALSE;

//Query for user info to fill out form:
$q = "SELECT email, first_name, last_name, company, timezone,
office_number, cell_number, title 
FROM users 
WHERE id=$user_id";

$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
. mysqli_error($dbc));

//Check success of user query:
if (@mysqli_num_rows($r) == 1) 
{
	//pull in values from SQL hit to user database:
	$row = mysqli_fetch_array($r,MYSQLI_ASSOC);
	$first = $row['first_name'];
	$last = $row['last_name'];
	$email = $row['email'];
	$company = $row['company'];
	$timezone = $row['timezone'];
	$cellno = $row['cell_number'];
	$officeno = $row['office_number'];
	$title = $row['title'];
	$usersuccess = TRUE;
	
	//Set session variable to detect change in email field:
	$_SESSION['email_entry'] = $email;
}	  

//Query for openid info to fill out form:
$q = "SELECT openid_url  
FROM user_openids 
WHERE user_id=$user_id";

$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
. mysqli_error($dbc));

//Check success of user query:
if (mysqli_affected_rows($dbc) == 1) {
	//pull out role value:
	$row = mysqli_fetch_array($r,MYSQLI_ASSOC);
	$openid = $row['openid_url'];
}

//Check to make sure that both table hits were successful:
if (!$usersuccess) {
	echo '
<div class="main-block main-block-add">

<div class="heading">
<h2>Unfortunately you can\'t access that user\'s information. Some reasons for this might be:</h2>
</div>
<ul>
<li>The user doesn\'t exist</li>
<li>The user might be for another account (which means you don\'t have access to it)</li>
</ul>
<p>If you followed a link and got this message you might double check with the person who sent you the link.</p>

</div>
';
	exit();
}

?>

<?php 
echo '<form id="edit_user" action="/settings/people/edit/?id=' . $user_id . '" method="post" class="contact-items-form">
<fieldset>

<!-- User Information -->

<div class="contact-items">

<div class="head">
';

// Echo assembled name of user:

echo '<h2>' . $first . ' ' . $last . '</h2>';
	
// Echo role of user:

// <h3>Role: Something</h3>

echo '
</div>';								
?>

<div class="row">
	<label class="title">First Name:</label>
	<input type="text" class="text middle" name="first_name" maxlength="40" value="<?php if (isset($first)) echo $first; ?>" />
	<label>Last Name:</label>
	<input type="text" class="text edit" name="last_name" maxlength="40" value="<?php if (isset($last)) echo $last; ?>" />
</div>
<div class="row">
	<label class="title">Title:</label>
	<input type="text" class="text" name="title" maxlength="50" value="<?php if (isset($title)) echo $title; ?>" />
</div>
<div class="row">
	<label class="title">Company:</label>
	<input type="text" class="text" name="company" maxlength="50" value="<?php if (isset($company)) echo $company; ?>" />
</div>
<div class="row">
	<label class="title">Office #:</label>
	<input type="text" class="text" name="officeno" maxlength="25" value="<?php if (isset($officeno)) echo $officeno; ?>" />
</div>
<div class="row">
	<label class="title">Cell #:</label>
	<input type="text" class="text" name="cellno" maxlength="25" value="<?php if (isset($cellno)) echo $cellno; ?>" />
</div>
<div class="row">
	<label class="title">Email:</label>
	<input type="text" class="text" name="email" maxlength="80" value="<?php if (isset($email)) echo $email; ?>" />
</div>

<!-- Middle Centered Area -->
<div class="col">
	<?php if ($_SESSION['multiaccounts']) { // Code for setting up default account radio buttons:
		echo '<div class="select-row">
		<label>Default Account:</label>
		<select name="defaultaccount" id="select-2">';
			// Loop through accounts session variable, build radio buttons:
			foreach ($_SESSION['accounts'] as $key => $accountname)		
			{
				echo '<option value="' . $key . '"';
				if ( $key == $_SESSION['default_account'] ) echo ' selected';
				echo '>' . $accountname . '</option>';
			}
		}
		echo '</select></div>';
	    ?>
	<div class="select-row">
		<label>Time Zone:</label>
		<select id="timezone" name="timezone">
		<?php 
		echo '<option selected value="' . $timezone . '">' . $timezone . '</option>';
		?>
		<option value="Hawaii">(GMT-10:00) Hawaii</option>
		<option value="Alaska">(GMT-09:00) Alaska</option>
		<option value="Pacific Time (US &amp; Canada)">(GMT-08:00) Pacific Time (US &amp; Canada)</option>
		<option value="Arizona">(GMT-07:00) Arizona</option>
		<option value="Mountain Time (US &amp; Canada)">(GMT-07:00) Mountain Time (US &amp; Canada)</option>
		<option value="Central Time (US &amp; Canada)">(GMT-06:00) Central Time (US &amp; Canada)</option>
		<option value="Eastern Time (US &amp; Canada)">(GMT-05:00) Eastern Time (US &amp; Canada)</option>

		<option value="Indiana (East)">(GMT-05:00) Indiana (East)</option><option value="">-------------</option>
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
		</select>
	</div>
</div>
<!-- / Middle Centered Area -->

<!-- Password Area -->
<div class="col">
	<div class="row">
		<label class="title-edit">Old Password:</label>
		<input type="password" class="text middle-edit" name="oldpassword" maxlength="20" />
	</div>
	<div class="row">
		<label class="title-edit">New Password:</label>
		<input type="password" class="text middle-edit" name="password1" maxlength="20" />
		<span>At least 6 characters or longer. Letters, numbers, punctuation or symbols.</span>
	</div>
	<div class="row">
		<label class="title-edit">Confirm New Password:</label>
		<input type="password" class="text middle-edit" name="password2" maxlength="20" />
	</div>
</div>
<!-- / Password Area -->



<!-- User Role Area -->
<div class="col">
	<div class="heading heading-add">
		<h2>User Role:</h2>
<?php # Help Button
$helphref = 'settings-user-roles.html';
$helptitle = 'Help with User Roles';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
?>
	</div>
<?php 
//Array for accounts to check:
$checkaccounts = array();

//Query for account names and id's that this user can be associated with:
//MySQL setup:
require_once(MYSQL);

//Tailor wherestring to give all accounts associated with this user is an owner, just this account if an admin:
$wherestring = "WHERE 1=2";
if ($_SESSION['role']=='O') {
	$wherestring = "WHERE account_lookup.user_id={$_SESSION['id']}";
} elseif ($_SESSION['role']=='A') {
	$wherestring = "WHERE account_lookup.account_id={$_SESSION['account_id']} " . 
	"AND account_lookup.user_id={$_SESSION['id']}";
}

$q = "SELECT account_lookup.account_id, accounts.company as name 
FROM account_lookup 
INNER JOIN accounts 
ON account_lookup.account_id=accounts.id " . 
$wherestring;

$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

// Build table header:
echo '
<!-- User Role Table -->
	<div class="main-box">
		<div class="main-table">
			<table class="sortable">
				<thead>
					<tr>
						<th class="long nosort"><span>Account</span></th>
						<th class="nosort"><span>Admin</span></th>
						<th class="nosort"><span>Marketing</th>
						<th class="nosort"><span>Sales</span></th>
						<th class="nosort"><span>Executive</span></th>
						<th class="nosort"><span>Assign Leads</span></th>
						<th class="nosort"><span>Disable</span></th>
					</tr>
				</thead>
				<tbody>';

while ($row = mysqli_fetch_array($r,MYSQLI_ASSOC))
{
	$accountidplace = $row['account_id'];
	
	//query to find user's role (or lack thereof) in each account:
	$q2 = "SELECT role, assigns_leads 
	FROM account_lookup 
	WHERE user_id='$user_id' AND account_id='$accountidplace'";

	$r2 = mysqli_query($dbc,$q2) or trigger_error("Query: $q2\n<br />MySQL Error: " . 
	mysqli_error($dbc));
	
	$row2 = mysqli_fetch_array($r2,MYSQLI_ASSOC);
	
	//Add role account radio buttons to table, check with each entry
	//to show current role assignment:
	echo '<tr>
	<td class="long"><span>' . $row['name'] . '</span></td>
	
	<td><input type="radio" name="role[' . 
	$row['account_id'] . ']" value="A"';
	if ($row2['role']=="A") echo ' checked';  
	echo '></td>

	<td><input type="radio" name="role[' . 
	$row['account_id'] . ']" value="M"';
	if ($row2['role']=="M") echo ' checked';  
	echo '></td>

	<td><input type="radio" name="role[' . 
	$row['account_id'] . ']" value="S"';
	if ($row2['role']=="S") echo ' checked';
	echo '></td>

	<td><input type="radio" name="role[' . 
	$row['account_id'] . ']" value="V"';
	if ($row2['role']=="V") echo ' checked';  
	echo '></td>
	
	<td><input type="checkbox" name="assignsleads[]" 
	value="' . $row['account_id'] . '"';
	if ($row2['assigns_leads']=="Y") echo ' checked';
	echo '></td>

	<td><input type="radio" name="role[' . 
	$row['account_id'] . ']" value="N"';
	if ($row2['role']=="") echo ' checked';
	echo '></td>
	</tr>';
}

?>
				</tbody>
			</table>
		</div>
	</div>
<!-- / User Role Table -->
</div>
<!-- / User Role Area -->
  
	<div class="content-link">
		<div class="link-holder">
			<div class="link-frame">
			<a href="#" class="btn" onclick="document.getElementById('edit_user').submit()" /><span>Edit User &gt;</span></a>
			<em>or</em>
			<a href="/settings/people/">Cancel</a>
			</div>
		</div>
	</div>

<input type="hidden" name="submitted" value="TRUE" />

</div>
</fieldset>
</form>

<!-- / Main Content -->

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
