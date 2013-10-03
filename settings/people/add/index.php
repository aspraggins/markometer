<?php # Settings - Add User Page
$metatitle = 'Add User';
$returnurl = '/login/?returnpage=settings/people/add';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Heading and Help Button -->

<div class="heading">
<h1>Add User</h1>
<?php # Help Button
$helphref = 'settings-add-user.html';
$helptitle = 'Help with Adding Users';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
?>
</div>

<!-- Main Content -->

<?php 
if (isset($_POST['submitted']))
{
    //MySQL setup:
    require_once(MYSQL);
  
    //Assume invalid values:
    $fn = $ln = $e = $p = $co = $roleselect = FALSE;
  
    //Check for a first name:
    if(preg_match('/^[A-Z \'.-]{2,20}$/i', trim($_POST['first_name']))) {
        $fn = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
    } else {
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
    if(preg_match('/^[A-Z \'.-]{2,40}$/i', trim($_POST['last_name']))) {
		$ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
    } else {
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
    if(preg_match('/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/', trim($_POST['email']))) {
        $e = strtolower(mysqli_real_escape_string($dbc, trim($_POST['email'])));
    } else {
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
 
	//Check for a password and match against the confirmed password:
    if(preg_match ('/^\w{4,20}$/', trim($_POST['password']) )) {
		if (trim($_POST['password']) == trim($_POST['passwordconfirm'])) {
			$p = mysqli_real_escape_string($dbc, trim($_POST['password']));
		} else {
					echo '
			<div class="alert-holder">
				<div class="alert-box red">
					<div class="holder">
						<div class="frame">
							<a class="close" href="#">X</a>
							<p>Your passwords do not match!</p>
						</div>
					</div>
				</div>
			</div>';
		}	
    } else {
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

    if(preg_match("/^[A-Z0-9\ \',_\.\-]{2,50}$/i", trim($_POST['company']))) {
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

 //code for role selection validation and processing:
	//Validate that a role was selected for at least one account:
	foreach ($_POST['role'] as $roleoutput)	{
		if ($roleoutput!='N') $roleselect = TRUE;
	}

 	$timezone = mysqli_real_escape_string($dbc, trim($_POST['timezone']));
	$title = mysqli_real_escape_string($dbc, trim($_POST['title']));
	$officeno = mysqli_real_escape_string($dbc, trim($_POST['officeno']));
	$cellno = mysqli_real_escape_string($dbc, trim($_POST['cellno']));
  
    if ($fn && $ln && $e && $p && $co && $timezone) 
    {
	    //if everything validates
		//Make sure the email address is available:
		$q = "SELECT id FROM users WHERE email='$e'";
		$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
		mysqli_error($dbc));
		
		if (mysqli_num_rows($r) == 0)
		{
			if (isset($_POST['assignsleads'])) {
				$assignsleads = $_POST['assignsleads'];
			} else {
				$assignsleads = 'N';
			}
		
		    //Initialize SQL Insert check variables:
		    $userinsert = $account_lookupinsert = FALSE;
		  
		    //Add the user to the user table:
		    $q = "INSERT INTO users (email, password, first_name, last_name, 
			date_created, default_account, company, timezone, office_number,
			cell_number, title, created_by, new, master_account) 
		    VALUES('$e', ENCODE('$p','{ENCODEPASS}'), '$fn', '$ln', 
			NOW(), '{$_SESSION['account_id']}', '$co', '$timezone',
			'$officeno','$cellno','$title', {$_SESSION['id']}, 'Y', {$_SESSION['master_account']})";
			
		    $r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
		    . mysqli_error($dbc));

		    //Check success of user table insert:
		    if (mysqli_affected_rows($dbc) == 1) {
				$userinsert = TRUE;
		    }	  

			//Do SQL hit to acquire new user_id for use in the account_lookup table insert:
			$q = "SELECT id FROM users WHERE email='$e'";

			$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
			. mysqli_error($dbc));

			$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
			$user_id = $row['id'];

			//Add in records to account_lookup to link user with accounts and correct roles:
			//Put assigns_leads values in array:
			$assignsleadsarray = array();
			if (isset($_POST["assignsleads"]))	{
				$assignsleadsarray = $_POST["assignsleads"];
			}
			
			//loop through accounts and write off roles and assigns_leads values to account_lookup:
			foreach ($_POST['role'] as $key => $roleoutput)
			{
				$assignsleadsvalue = 'N';
				if (in_array($key, $assignsleadsarray)) $assignsleadsvalue = 'Y';
				
				if ($roleoutput!='N')
				{
					$q = "INSERT INTO account_lookup (user_id, account_id, role, assigns_leads) 
				    VALUES('$user_id', '$key', '$roleoutput', '$assignsleadsvalue')";
			    
					$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
				    . mysqli_error($dbc));
				}
				//Check success of account_lookup table insert;
			    if (mysqli_affected_rows($dbc) == 1) {
					$account_lookupinsert = TRUE;
			    } else {
					$account_lookupinsert = FALSE;
					break;
				}
			}

		    //If both SQL Inserts were successful:
		    if ($userinsert && $account_lookupinsert)
		    {
		        //If it ran okay
		        //open template for this email:
				$filetoopen = $_SERVER['DOCUMENT_ROOT'] . '/templates/emails/addusermail.txt';
				$fp = @fopen($filetoopen, "rb") or die("Couldn't open file");
				$data = '';
				while(!feof($fp)) {
					$data .= fgets($fp, 1024);	
				}	
				fclose($fp); 

				//Process $data to replace tags ([fname] and [recipient]) with strings:
				$data = str_replace("[login]", $e, $data);
				$data = str_replace("[fname]", $fn, $data);				
				$body = str_replace("[pass]", $p, $data);
		        
				//Send the email:
				//$body = "You have been registered at Markometer!\nYour login will be: \n$e\nYour temporary password is: \n$p\n";
				
				if($_SESSION['privatelabel']=='Y') {
						$from = 'From:' . str_replace(" ","_",$_SESSION['master_account_name']) . '@mp41.com';
				} else {
						$from = 'From:Markometer@mp41.com';
				}

				mail(trim($_POST['email']),
				'Markometer Registration', $body, $from);
				$url = '/settings/people/?action=useradded';	
				//Redirect back to user management main page:
				header ("Location: $url");
				//Include the HTML footer
				exit();//Stop the page
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
				<p>This user could not be registered due to a system error. We apologize for any inconvenience.</p>
			</div>
		</div>
	</div>
</div>';
		    }
		}
		else
		{
			//Check for if an admin or account owner is creating this user:
			if ($_SESSION['role']=='O') {
				echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>That email address has already been registered.</p>
			</div>
		</div>
	</div>
</div>';
			} else {			
				echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>That email address has already been registered. Please ask the account owner to include him in this account.</p>
			</div>
		</div>
	</div>
</div>';
			}		
			/*//The email address is taken, offer to include in this account
			$buttonstring = "'addexisting_user/?id=" . $row['id'] . "'";

			echo '<table border="1" bgcolor="red"><tr><td>That email address has already 
			been registered. Would you like to include ' . $e . 
			' as a user in this account?</td></tr>
			<tr><td align="center"><input type="button" value="Add User to Account"
			onclick="window.location.href=' . $buttonstring . '"></td>
			<td><input type="button" value="Try Different User" onClick="window.location.reload()">
			</td></tr></table>'; */
		}
		
    }
} //End of main Submit conditional
?>

<?php 
//Test to see if the user is an admin for this account, 
//or an account owner, and notify of privileges if not:
if($_SESSION['role']!='O' && $_SESSION['role']!='A') {
	//notify that no soup for them:
	echo '
	<div class="alert-holder">
		<div class="alert-box red">
			<div class="holder">
				<div class="frame">
					<a class="close" href="#">X</a>
					<p>You do not have rights to add users for this account!</p>
				</div>
			</div>
		</div>
	</div>';
	exit();
}

//Generate Form:	
echo '<form id="add_user" action="/settings/people/add/" method="post" class="contact-items-form">
<fieldset>

<!-- User Information -->

<div class="contact-items">

';								
?>

<div class="row">
	<label class="title">First Name:</label>
	<input type="text" class="text middle" name="first_name" maxlength="40" value="<?php if (isset($_POST['first_name'])) echo $_POST['first_name']; ?>" />
	<label>Last Name:</label>
	<input type="text" class="text edit" name="last_name" maxlength="40" value="<?php if (isset($_POST['last_name'])) echo $_POST['last_name']; ?>" />
</div>
<div class="row">
	<label class="title">Title:</label>
	<input type="text" class="text" name="title" maxlength="50" value="<?php if (isset($_POST['title'])) echo trim($_POST['title']); ?>" />
</div>
<div class="row">
	<label class="title">Company:</label>
	<input type="text" class="text" name="company" maxlength="50" value="<?php if (isset($_POST['company'])) echo trim($_POST['company']); ?>" />
</div>
<div class="row">
	<label class="title">Office #:</label>
	<input type="text" class="text" name="officeno" maxlength="25" value="<?php if (isset($_POST['officeno'])) echo trim($_POST['officeno']); ?>" />
</div>
<div class="row">
	<label class="title">Cell #:</label>
	<input type="text" class="text" name="cellno" maxlength="25" value="<?php if (isset($_POST['cellno'])) echo trim($_POST['cellno']); ?>" />
</div>
<div class="row">
	<label class="title">Email:</label>
	<input type="text" class="text" name="email" maxlength="80" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" />
</div>

<!-- Middle Centered Area -->
<div class="col">
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
		<label class="title-edit">Password:</label>
		<input type="password" class="text middle-edit" name="password" maxlength="20" />
		<span>At least 6 characters or longer. Letters, numbers, punctuation or symbols.</span>
	</div>
	<div class="row">
		<label class="title-edit">Confirm Password:</label>
		<input type="password" class="text middle-edit" name="passwordconfirm" maxlength="20" />
	</div>
</div>
<!-- / Password Area -->

<!-- User Role Area -->
<div class="col">
	<div class="heading heading-add">
		<h2>User Role:</h2>
<?php # Help Button
$helphref = 'settings-user-roles.html';
$helptitle = 'Help with Understanding User Roles';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
?>
	</div>
<?php 
//Array for accounts to check:
$checkaccounts = array();

//Put account numbers in $checkaccounts based on admin being single or multi accounts:
//Query for account names and id's for this admin:
//MySQL setup:
require_once(MYSQL);

//Tailor wherestring to give all accounts associated with this user is an owner, just this account if an admin:
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

//Build table header:
echo '
<!-- User Role Table -->
	<div class="main-box">
		<div class="main-table">
			<table class="sortable">
				<thead>
					<tr>
						<th class="long nosort"><span>Account</span></th>
						<th class="nosort"><span>Admin</span></th>
						<th class="nosort"><span>Marketing</span></th>
						<th class="nosort"><span>Sales</span></th>
						<th class="nosort"><span>Executive</span></th>
						<th class="nosort"><span>Assign Leads</span></th>
						<th class="nosort"><span>Disable</span></th>
					</tr>
				</thead>
				<tbody>';

while ($row = mysqli_fetch_array($r,MYSQLI_ASSOC))
{
	//Add role account radio buttons to table:
	echo '<tr>
	<td class="long"><span>' . $row['name'] . '</span></td>
	
	<td><input type="radio" name="role[' . $row['account_id'] . ']" value="A"></td>

	<td><input type="radio" name="role[' . $row['account_id'] . ']" value="M"></td>

	<td><input type="radio" name="role[' . $row['account_id'] . ']" value="S"></td>
	
	<td><input type="radio" name="role[' . $row['account_id'] . ']" value="V"></td>

	<td><input type="checkbox" name="assignsleads[]" value="' . $row['account_id'] . '"></td>

	<td><input type="radio" name="role[' . $row['account_id'] . ']" value="N" checked></td>
	</tr>';
}

?>

<!-- tr class="add" for alternating colors -->

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
			<a href="#" class="btn" onclick="document.getElementById('add_user').submit()" /><span>Add New User &gt;</span></a>
			<em>or</em>
			<a href="javascript:history.back();">Cancel</a>
			</div>
		</div>
	</div>

<input type="hidden" name="submitted" value="TRUE" />

</div>
</fieldset>
</form>

<!-- / Main Content -->

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
