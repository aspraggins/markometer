<?php # Settings - My Info Page
$metatitle = 'My Information';
$returnurl = '/login/?returnpage=settings/myinfo';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Heading and Help Button -->

<div class="heading">
<h1>My Info</h1>
<?php # Help Button
$helphref = 'settings-my-info.html';
$helptitle = 'Help with Updating Your Personal Information';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
?>
</div>

<!-- Main Content -->

<?php 

function UpdateInfo($fn,$ln,$e,$q1,$q2,$dbc,$timezone,$title,$officeno,$cellno,$co) 
{
	//Set up default account part of query if multiaccount:
	$q3 = FALSE;
	if (isset($_POST['defaultaccount'])) $q3 = ", default_account={$_POST['defaultaccount']} ";
	
	//See if anything in optional information textboxes, set variables to use in query:
	$qtitle = $qofficeno = $qcellno = FALSE;
	if (strlen($title)>0) $qtitle = ", title='$title'";
	if (strlen($officeno)>0) $qofficeno = ", office_number='$officeno'";
	if (strlen($cellno)>0) $qcellno = ", cell_number='$cellno'";

	//Set up variables to track success of database writes:
	$userupdate = $updatestatus = FALSE;
	
	//See if any user values have actually been changed:	
	//Update user info based on form:
	$q = "UPDATE users 
		 SET updateflag=1-updateflag, first_name='$fn', last_name='$ln', email='$e', company='$co', 
		 timezone='$timezone'$q1 
		 $qtitle$qofficeno$qcellno$q3
		 WHERE id={$_SESSION['id']} $q2 LIMIT 1";
	
	$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
	Error: " . mysqli_error ($dbc));

	if (mysqli_affected_rows ($dbc) == 1) 
	{  //If it ran okay
        //repopulate the session variables:
		//Query the database:
	    $q = "SELECT id, first_name, last_name, email, default_account, timezone, salesadmin,
		title, office_number, cell_number 
	    from users WHERE id={$_SESSION['id']}";
	
	    $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
	    Error: " . mysqli_error($dbc));
    
	    if (@mysqli_num_rows($r) == 1)
	    {
			$row = mysqli_fetch_array ($r, MYSQLI_ASSOC);
	        $_SESSION['first_name'] = $row['first_name'];
			$_SESSION['last_name'] = $row['last_name'];
			$_SESSION['email'] = $row['email'];
			$_SESSION['default_account'] = $row['default_account'];
			$_SESSION['timezone'] = $row['timezone'];
			$_SESSION['salesadmin'] = $row['salesadmin'];
			$_SESSION['title'] = $row['title'];
			$_SESSION['office_number'] = $row['office_number'];
			$_SESSION['cell_number'] = $row['cell_number'];
	        mysqli_free_result($r);
			//Update company session variable:
			$_SESSION['company'] = $co;
	    }
		$updatestatus = TRUE;
		return $updatestatus;
	}
	else
	{
		$updatestatus = FALSE;
		return $updatestatus;
	}
}

if (isset($_POST['submitted']))
{
    require_once (MYSQL);
  
    //Trim all of the incoming data:
    $trimmed = array_map('trim', $_POST);

    //Assume invalid values:
    $fn = $ln = $e = $p = $op = $co = FALSE;
    
    //check for validated entry for first name:
    if(preg_match('/^[A-Z \'.-]{2,20}$/i', $trimmed['first_name']))
    {
		$fn = mysqli_real_escape_string($dbc, $trimmed['first_name']);
    }
    else
    {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Please enter your first name!</p>
			</div>
		</div>
	</div>
</div>';
    }

    //Check for a last name:
    if(preg_match('/^[A-Z \'.-]{2,40}$/i', $trimmed['last_name']))
    {
		$ln = mysqli_real_escape_string($dbc, $trimmed['last_name']);
    }
    else
    {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Please enter your last name!</p>
			</div>
		</div>
	</div>
</div>';		
    }
  
    //Check for an email address:
    if(preg_match('/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/', $trimmed['email']))
    {
		$e = mysqli_real_escape_string($dbc, $trimmed['email']);
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
    if(preg_match ('/^\w{4,20}$/', $_POST['oldpassword']) )
    {  
		$op = mysqli_real_escape_string ($dbc, $_POST['oldpassword']);  
    }
  
    //Initialize string variables used in case of password change:
    $q1 = $q2 = FALSE;
    if ($p && $op) 
    {
		//Create strings that will be added to SQL Update if password changed:
	    $q1 = ", password=ENCODE('$p','{ENCODEPASS}')";
		$q2 = "AND password=ENCODE('$op','{ENCODEPASS}')";
    }

    //Check for a company:
    if(preg_match('/^[A-Z0-9 \',_.-]{2,40}$/i', $trimmed['company']))
    {
		$co = mysqli_real_escape_string($dbc, $trimmed['company']);
    }
    else
    {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Please enter your company name!</p>
			</div>
		</div>
	</div>
</div>';
    }
	
	$timezone = mysqli_real_escape_string($dbc, $trimmed['timezone']);
	$title = mysqli_real_escape_string($dbc, $trimmed['title']);
	$officeno = mysqli_real_escape_string($dbc, $trimmed['officeno']);
	$cellno = mysqli_real_escape_string($dbc, $trimmed['cellno']);
	
    if (($fn && $ln && $e && $p && $op && $timezone && $co) || ($fn && $ln && $e && $timezone && $co && !$p && !$op))
    {  //Everything validated...

	//Make sure the email address is available:
	$q = "SELECT id FROM users WHERE email='$e'";
	$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
	mysqli_error($dbc));
	
		if (mysqli_num_rows($r) == 0 || $_SESSION['email'] == $e)
		{
			//Update name, email, password information in database and session:
			if(UpdateInfo($fn,$ln,$e,$q1,$q2,$dbc,$timezone,$title,$officeno,$cellno,$co))
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
			
		}
		
		
		else
		{
			//Email is already in use:
			echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>That email address is already in use. Please try another email address.</p>
			</div>
		</div>
	</div>
</div>';
		}
    }
    else
    {
        //Failed the validation test
        //echo '<p class="error">Please try again</p>';
		/*			echo '
		<div class="alert-holder">
			<div class="alert-box red">
				<div class="holder">
					<div class="frame">
						<a class="close" href="#">X</a>
						<p>Please correctly enter all information.</p>
					</div>
				</div>
			</div>
		</div>'; */
    }
  
    mysqli_close ($dbc); //Close the database connection
  
}   // End of the main Submit

?>  

<?php 
echo '<form id="updatemyinfo" action="/settings/myinfo/" method="post" class="contact-items-form">
<fieldset>

<!-- My Personal Information -->

<div class="contact-items">

<div class="head">
';

// Echo assembled name of user:

echo '<h2>' . $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] . '</h2>';
	
// Echo role of user:
	
echo '<h3>Role: ';
	switch ($_SESSION['role'])
	{
		case 'O':
			echo 'Account Owner';
			break;
		case 'A':
			echo 'Administrator';
			break;
		case 'M':
			echo 'Marketing';
			break;
		case 'S':
			echo 'Sales';
			break;
		case 'V':
			echo 'Executive';
			break;
	}
echo '</h3>	
</div>';									
?>

<div class="row">
	<label class="title">First Name:</label>
	<input type="text" class="text middle" name="first_name" maxlength="40" value="<?php echo $_SESSION['first_name']; ?>" />
	<label>Last Name:</label>
	<input type="text" class="text edit" name="last_name" maxlength="40" value="<?php echo $_SESSION['last_name']; ?>" />
</div>
<div class="row">
	<label class="title">Title:</label>
	<input type="text" class="text" name="title" maxlength="50" value="<?php echo $_SESSION['title']; ?>" />
</div>
<div class="row">
	<label class="title">Company:</label>
	<input type="text" class="text" name="company" maxlength="50" value="<?php echo $_SESSION['company']; ?>" />
</div>
<div class="row">
	<label class="title">Office #:</label>
	<input type="text" class="text" name="officeno" maxlength="25" value="<?php echo $_SESSION['office_number']; ?>" />
</div>
<div class="row">
	<label class="title">Cell #:</label>
	<input type="text" class="text" name="cellno" maxlength="25" value="<?php echo $_SESSION['cell_number']; ?>" />
</div>
<div class="row">
	<label class="title">Email:</label>
	<input type="text" class="text" name="email" maxlength="80" value="<?php echo $_SESSION['email']; ?>" />
</div>


<!-- Middle Centered Area -->
<div class="col">
<?php if ($_SESSION['multiaccounts']) // Code for setting up default account radio buttons:
		{
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
			echo '</select></div>';
		}
	    ?>
	<div class="select-row">
		<label>Time Zone:</label>
		<select id="timezone" name="timezone">
		<?php 
		echo '<option selected value="' . $_SESSION['timezone'] . '">' . 
		$_SESSION['timezone'] . '</option>';
		?>
		<option value="Hawaii">(GMT-10:00) Hawaii</option>
		<option value="Alaska">(GMT-09:00) Alaska</option>
		<option value="Pacific Time (US &amp; Canada)">(GMT-08:00) Pacific Time (US &amp; Canada)</option>
		<option value="Arizona">(GMT-07:00) Arizona</option>
		<option value="Mountain Time (US &amp; Canada)">(GMT-07:00) Mountain Time (US &amp; Canada)</option>
		<option value="Central Time (US &amp; Canada)">(GMT-06:00) Central Time (US &amp; Canada)</option>
		<option value="Eastern Time (US &amp; Canada)">(GMT-05:00) Eastern Time (US &amp; Canada)</option>

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

	<div class="content-link">
		<div class="link-holder">
			<div class="link-frame">
			<a href="#" class="btn" onclick="document.getElementById('updatemyinfo').submit()" /><span>Update My Info &gt;</span></a>
			<em>or</em>
			<a href="/settings/">Cancel</a>
			</div>
		</div>
	</div>

<input type="hidden" name="submitted" value="TRUE" />

</div>
</fieldset>
</form>

<!-- / Main Content -->

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
