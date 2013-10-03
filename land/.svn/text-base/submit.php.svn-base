<?php # process incoming form from end user
//Programmer: Ray Neel 12/2008 -- revising 5/2009

require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.inc.php");
$page_title = 'Submit';
//include('/home/rneel/web/htdocs/includes/header.html');

//MySQL setup:
require_once(MYSQL);

//Set up variableS:
$firstname = $lastname = $email = $company = $phone = $state = $country = $zip = $city = $address = $title = $inquiry = " ";

//Check for a first name:
if (isset($_POST['firstname'])) {
	if(preg_match('/^[A-Z0-9 \'.-]{2,60}$/i', trim($_POST['firstname']))) {
		$firstname = mysqli_real_escape_string($dbc, trim($_POST['firstname']));
	} else {
		$firstname = NULL;
		echo '<table border="1" bgcolor="red"><tr><td>Please press back and enter a valid first name!</td></tr></table>';		
		exit();
	}
}

//Check for a last name:
if (isset($_POST['lastname'])) {
	if(preg_match('/^[A-Z0-9 \'.-]{2,60}$/i', trim($_POST['lastname']))) {
		$lastname = mysqli_real_escape_string($dbc, trim($_POST['lastname']));
	} else {
		$lastname = NULL;
		echo '<table border="1" bgcolor="red"><tr><td>Please press back and enter a valid last name!</td></tr></table>';		
		exit();
	}
}

//Check for a first name:
if (isset($_POST['title'])) {
	if(preg_match('/^[A-Z0-9 \'.-]{2,80}$/i', trim($_POST['title']))) {
		$title = mysqli_real_escape_string($dbc, trim($_POST['title']));
	} else {
		$title = NULL;
		echo '<table border="1" bgcolor="red"><tr><td>Please press back and enter a valid title!</td></tr></table>';
		exit();
	}
}

//Check for a company name:
if (isset($_POST['company'])) {
	if(preg_match('/^[A-Z0-9 \',_.-]{2,40}$/i', trim($_POST['company']))) {
		$company = mysqli_real_escape_string($dbc, trim($_POST['company']));
	} else {
		$company = NULL;
		echo '<table border="1" bgcolor="red"><tr><td>Please press back and enter a valid company name!</td></tr></table>';		
		exit();
	}
}

//Check for email:
if (isset($_POST['email'])) {
	if(preg_match('/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/', trim($_POST['email']))) {
        $email = strtolower(mysqli_real_escape_string($dbc, trim($_POST['email'])));
    } else {
		$email = NULL;
		echo '<p class="error">Please press back and enter a valid email address!</p>';
		exit();
    }
}

//Check for a address:
if (isset($_POST['address'])) {
	if(preg_match('/^[A-Z0-9 \'.-]{2,80}$/i', trim($_POST['address']))) {
		$address = mysqli_real_escape_string($dbc, trim($_POST['address']));
	} else {
		$address = NULL;
		echo '<table border="1" bgcolor="red"><tr><td>Please press back and enter a valid address!</td></tr></table>';		
		exit();
	}
}

//Check for a telephone number:
if (isset($_POST['phone'])) {
	if(preg_match('/^[A-Z0-9 \',_.-]{2,40}$/i', trim($_POST['phone']))) {
		$phone = mysqli_real_escape_string($dbc, trim($_POST['phone']));
	} else {
		$phone = NULL;
		echo '<table border="1" bgcolor="red"><tr><td>Please press back and enter a valid telephone number!</td></tr></table>';		
		exit();
	}
}

//Check for a city:
if (isset($_POST['city'])) {
	if(preg_match('/^[A-Z0-9 \',_.-]{2,40}$/i', trim($_POST['city']))) {
		$city = mysqli_real_escape_string($dbc, trim($_POST['city']));
	} else {
		$city = NULL;
		echo '<table border="1" bgcolor="red"><tr><td>Please press back and enter a valid city!</td></tr></table>';		
		exit();
	}
}

if (isset($_POST['state'])) {
	$state = $_POST['state'];
}

if (isset($_POST['country'])) {
	$country = $_POST['country'];
}

//Check for a zip:
if (isset($_POST['zip'])) {
	if(preg_match('/^[0-9 \'.-]{2,12}$/i', trim($_POST['zip']))) {
		$zip = mysqli_real_escape_string($dbc, trim($_POST['zip']));
	} else {
		$zip = NULL;
		echo '<table border="1" bgcolor="red"><tr><td>Please press back and enter a valid zip code!</td></tr></table>';		
		exit();
	}
}

//Check for an inquiry:
if (isset($_POST['inquiry'])) {
	if(strlen(trim($_POST['inquiry']))<300) {
		$inquiry = mysqli_real_escape_string($dbc, trim($_POST['inquiry']));
	} else {
		$inquiry = NULL;
		echo '<table border="1" bgcolor="red"><tr><td>Please press back and enter a valid inquiry!</td></tr></table>';		
		exit();
	}
}

//Acquire campaign id and account id that are relevant to this campaign:
$campaignid = $_REQUEST['campID'];

//Look up account id for this campaign:
$q = "SELECT account_id " .
"FROM campaigns " . 
"WHERE id=$campaignid";

$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
. mysqli_error($dbc));

$row = mysqli_fetch_array($r,MYSQLI_ASSOC);
$account_id = $row['account_id'];

//echo '<br />Account: ' . $account_id . '<br />Campaign: ' . $campaignid;

//if($firstname && $lastname && $company && $email && $phone) {
	//Add the lead to the leads table:
	$query1 = "INSERT INTO leads (campaign_id, account_id, contact_first, contact_last, title, " . 
	"company, email, state, country, telephone, address, city, zip, inquiry, stage, ";

	$query2 = "VALUES($campaignid, $account_id, '$firstname', '$lastname', '$title', " . 
	"'$company', '$email', '$state', '$country', '$phone', '$address', '$city', '$zip', '$inquiry', 'IC', ";

	//begin check for custom fields:
	for($i=1;$i<21;$i++) {
		$qtest = 'q' . $i;
		if(isset($_POST[$qtest])) {
			//echo '<br />' . substr($qtest,1) . '   ' . $_POST[$qtest] . '   ' . $_POST[$qtest.'label'];
			$query1 .= ' userdef' . substr($qtest,1) . ',' . ' userdef' . substr($qtest,1) . 'label,';
			//check for array value...happens if this is a checkbox, convert to string if needed:
			if(is_array($_POST[$qtest])) $_POST[$qtest] = implode(",",$_POST[$qtest]);
			$query2 .= "'" . mysqli_real_escape_string($dbc, trim($_POST[$qtest])) . "','" . $_POST[$qtest.'label'] . "',";
		}
	}
	$query1 .= 'status, date_created) ';
	$query2 .= "'N', NOW())";
	$q = $query1 . $query2;
	//echo '<br />' . $q;
	
	$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
	. mysqli_error($dbc));

	//Check success of user table insert:
	if (mysqli_affected_rows($dbc) == 1) {
		//Pull out lead id so can make a record in notes:
		$leadid = mysqli_insert_id($dbc);
		
		//lead info for note:
		$note = 'Lead created.\n';
		$note .= 'Name: ' . stripslashes($firstname) . " " . stripslashes($lastname) . '\n';
		$note .= 'Email: ' . stripslashes($email) . '\n';
		$note .= 'Title: ' . stripslashes($title) . '\n';
		$note .= 'Company: ' . stripslashes($company) . '\n';
		$note .= 'Location: ' . stripslashes($address) . '\n';
		$note .= stripslashes($city) . ", " . $state . " " . stripslashes($zip) . '\n';
		$note .= $country . '\n';
		$note .= 'Phone: ' . stripslashes($phone) . '\n';
		$note .= 'Inquiry: ' . $inquiry . '\n';
				
		$q = "INSERT INTO notes " . 
		"(note, lead_id, type, date_created) " . 
		"VALUES ('$note', '$leadid', 'N', NOW())";

		$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
		. mysqli_error($dbc));

		//	Thank you note:
		echo '<br />Thank you for submitting your information!';
		exit();//Stop the page
	} else {
		//If it did not run okay
		echo '<p class="error">This information could not be registered due to a system
		error. We apologize for any inconvenience.</p>';
	}
//}
exit();
?>

