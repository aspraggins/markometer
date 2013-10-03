<?php # Landing page AND Submission script, consolidated 4/2011

//Programmer: Ray Neel 12/2008, revised 5/2009
//revamping 3/2011 to accomodate new chocoform methodology

require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.inc.php");
//include('/home/rneel/web/htdocs/includes/header.html');
//Start output buffering:
ob_start();

//Initialize a session if one not already in use:
if (session_id()=="") session_start();

echo '<html><body><div align="center">';

//Decipher requested action page from url:
if ( (isset($_REQUEST['camp'])) && (is_numeric($_REQUEST['camp'])) ) {
	$camp_id = $_REQUEST['camp'];
} else { 
	echo '<p class="error">This page has been reached in error.
	</p>';
	exit();
}

//Process requested variable to usable campaign id number:
$camp_id = intval($camp_id);

//decide if showing landing page or submission from a page:
if (isset($_POST['submitted'])) { //handle submission of form:

	//MySQL setup:
	require_once(MYSQL);

	//Set up variableS:
	$firstname = $lastname = $email = $company = $phone = $state = $country = $zip = $city = $address = $title = $inquiry = $errorstatement = NULL;

	//Check for a first name:
	if (isset($_POST['firstname'])) {
		if(isset($_POST['firstnamerequired'])) {
			if(preg_match('/^[A-Z0-9 \'.-]{2,60}$/i', trim($_POST['firstname']))) {
				$firstname = mysqli_real_escape_string($dbc, trim($_POST['firstname']));
			} else {
				$errorstatement .= '<table border="1" bgcolor="red"><tr><td>Please enter a valid first name!</td></tr></table>';		
			}
		} else {
			$firstname = mysqli_real_escape_string($dbc, trim($_POST['firstname']));
		}
	}

	//Check for a last name:
	if (isset($_POST['lastname'])) {
		if(isset($_POST['lastnamerequired'])) {		
			if(preg_match('/^[A-Z0-9 \'.-]{2,60}$/i', trim($_POST['lastname']))) {
				$lastname = mysqli_real_escape_string($dbc, trim($_POST['lastname']));
			} else {
				$errorstatement .= '<table border="1" bgcolor="red"><tr><td>Please enter a valid last name!</td></tr></table>';		
				exit();
			}
		} else {
			$lastname = mysqli_real_escape_string($dbc, trim($_POST['lastname']));			
		}
	}

	//Check for a title:
	if (isset($_POST['title'])) {
		if(isset($_POST['titlerequired'])) {		
			if(preg_match('/^[A-Z0-9 \'.-]{2,80}$/i', trim($_POST['title']))) {
				$title = mysqli_real_escape_string($dbc, trim($_POST['title']));
			} else {
				$errorstatement .= '<table border="1" bgcolor="red"><tr><td>Please enter a valid title!</td></tr></table>';
				exit();
			}
		} else {
			$title = mysqli_real_escape_string($dbc, trim($_POST['title']));			
		}
	}

	//Check for a company name:
	if (isset($_POST['company'])) {
		if(isset($_POST['companyrequired'])) {		
			if(preg_match('/^[A-Z0-9 \',_.-]{2,40}$/i', trim($_POST['company']))) {
				$company = mysqli_real_escape_string($dbc, trim($_POST['company']));
			} else {
				$errorstatement .= '<table border="1" bgcolor="red"><tr><td>Please enter a valid company name!</td></tr></table>';		
				exit();
			}
		} else {
			$company = mysqli_real_escape_string($dbc, trim($_POST['company']));			
		}
	}

	//Check for email:
	if (isset($_POST['email'])) {
		if(isset($_POST['emailrequired'])) {		
			if(preg_match('/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/', trim($_POST['email']))) {
		        $email = strtolower(mysqli_real_escape_string($dbc, trim($_POST['email'])));
		    } else {
				$errorstatement .= '<p class="error">Please enter a valid email address!</p>';
				exit();
		    }
		} else {
	        $email = strtolower(mysqli_real_escape_string($dbc, trim($_POST['email'])));			
		}
	}

	//Check for a address:
	if (isset($_POST['address'])) {
		if(isset($_POST['addressrequired'])) {		
			if(preg_match('/^[A-Z0-9 \'.-]{2,80}$/i', trim($_POST['address']))) {
				$address = mysqli_real_escape_string($dbc, trim($_POST['address']));
			} else {
				$errorstatement .= '<table border="1" bgcolor="red"><tr><td>Please enter a valid address!</td></tr></table>';		
				exit();
			}
		} else {
			$address = mysqli_real_escape_string($dbc, trim($_POST['address']));			
		}
	}

	//Check for a telephone number:
	if (isset($_POST['telephone'])) {
		if(isset($_POST['telephonerequired'])) {		
			if(preg_match('/^[A-Z0-9 \',_.-]{2,40}$/i', trim($_POST['telephone']))) {
				$phone = mysqli_real_escape_string($dbc, trim($_POST['telephone']));
			} else {
				$errorstatement .= '<table border="1" bgcolor="red"><tr><td>Please enter a valid telephone number!</td></tr></table>';		
				exit();
			}
		} else {
			$phone = mysqli_real_escape_string($dbc, trim($_POST['telephone']));			
		}
	}

	//Check for a city:
	if (isset($_POST['city'])) {
		if(isset($_POST['cityrequired'])) {		
			if(preg_match('/^[A-Z0-9 \',_.-]{2,40}$/i', trim($_POST['city']))) {
				$city = mysqli_real_escape_string($dbc, trim($_POST['city']));
			} else {
				$errorstatement .= '<table border="1" bgcolor="red"><tr><td>Please enter a valid city!</td></tr></table>';		
				exit();
			}
		} else {
			$city = mysqli_real_escape_string($dbc, trim($_POST['city']));			
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
		if(isset($_POST['ziprequired'])) {		
			if(preg_match('/^[0-9 \'.-]{2,12}$/i', trim($_POST['zip']))) {
				$zip = mysqli_real_escape_string($dbc, trim($_POST['zip']));
			} else {
				$errorstatement .= '<table border="1" bgcolor="red"><tr><td>Please enter a valid zip code!</td></tr></table>';		
				exit();
			}
		} else {
			$zip = mysqli_real_escape_string($dbc, trim($_POST['zip']));			
		}
	}

	//Check for an inquiry:
	if (isset($_POST['inquiry'])) {
		if(isset($_POST['inquiryrequired'])) {		
			if(strlen(trim($_POST['inquiry']))<300) {
				$inquiry = mysqli_real_escape_string($dbc, trim($_POST['inquiry']));
			} else {
				$errorstatement .= '<table border="1" bgcolor="red"><tr><td>Please enter a valid inquiry!</td></tr></table>';		
				exit();
			}
		} else {
			$inquiry = mysqli_real_escape_string($dbc, trim($_POST['inquiry']));			
		}
	}

	//Acquire campaign id and account id that are relevant to this campaign:
	$campaignid = $_REQUEST['camp'];

	//Look up account id for this campaign:
	$q = "SELECT account_id " .
	"FROM campaigns " . 
	"WHERE id=$campaignid";

	$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
	. mysqli_error($dbc));

	$row = mysqli_fetch_array($r,MYSQLI_ASSOC);
	$account_id = $row['account_id'];

	//Begin building string for Insert to Leads table:
	$query1 = "INSERT INTO leads (campaign_id, account_id, contact_first, contact_last, title, " . 
	"company, email, state, country, telephone, address, city, zip, inquiry, stage, ";

	$query2 = "VALUES($campaignid, $account_id, '$firstname', '$lastname', '$title', " . 
	"'$company', '$email', '$state', '$country', '$phone', '$address', '$city', '$zip', '$inquiry', 'IC', ";

	//begin check for custom fields:
	for($i=1;$i<21;$i++) {
		$qtest = 'q' . $i;
		if(isset($_POST[$qtest])) {
			//check to see if required field, if is, do standard regex string check:
			if(isset($_POST[$qtest.'required'])) {
				if(!preg_match('/^[A-Z0-9 \'.-]{2,60}$/i', trim($_POST[$qtest]))) {
					$errorstatement .= '<table border="1" bgcolor="red"><tr><td>Please enter a valid entry for ' . $_POST[$qtest.'label'] . '</td></tr></table>';		
				}					
			}
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

	//make decision for whether or not to process form based on $errorstatement:
	if($errorstatement) {
		//There were issues with required fields:
		echo '<br />' . $errorstatement;
	} else {
		//Form validates, write to leads table:
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

			//	Query for thank you:
			$q = "SELECT action_pages.thankyou, action_pages.thankyou_redirect, " .  
			"action_pages.redirect_link FROM action_pages " . 
			"INNER JOIN campaigns " .
			"ON action_pages.id=campaigns.action_page " . 
			"WHERE campaigns.id=$camp_id";

			$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
			. mysqli_error($dbc));

			//thank you info found?:
			if (mysqli_num_rows($r) > 0) {			
				$row = mysqli_fetch_array($r,MYSQLI_ASSOC);
				$thankyoustring = $row['thankyou'];
				$redirect = $row['thankyou_redirect'];
				$thankyouURL = $row['redirect_link'];
				if($redirect=='Y'){
					//redirect to page in table:
					header("Location: $thankyouURL");					
				} else {
					//output page info specified in wysiwygpro:
					echo '<br />' . $thankyoustring;
				}
			}
			exit();//Stop the page
		}
	}
} 
//Create landing page:
//Data connection:
require_once (MYSQL);

//Register this page hit in campaigns table:
$q1 = "SET pageloads=pageloads+1 ";
$cookiename = 'MPvisit' . $camp_id;
if(!isset($_COOKIE[$cookiename]) || !isset($_SESSION[$cookiename])) {
	$q1 = "SET pageloads=pageloads+1, uniques=uniques+1 ";
	if(!isset($_COOKIE[$cookiename])) setcookie($cookiename, '1', time()+60*60*24*7);
	if(!isset($_SESSION[$cookiename])) $_SESSION[$cookiename] = '1';	
}

$q = "UPDATE campaigns " . $q1 . "WHERE id='$camp_id' LIMIT 1";

$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
Error: " . mysqli_error ($dbc));

if (mysqli_affected_rows ($dbc) != 1) {		
	echo 'An Error Has Occurred. Please try reloading the page!';
}

//Query for requested action page:
$q = "SELECT action_pages.action_page, action_pages.form_id 
FROM action_pages 
INNER JOIN campaigns 
ON action_pages.id=campaigns.action_page 
WHERE campaigns.id=$camp_id";

$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
. mysqli_error($dbc));

if (mysqli_num_rows($r) > 0) {
	//Fetch action page from dbase record:
	$row = mysqli_fetch_array($r,MYSQLI_ASSOC);
	$actionpage = stripslashes($row['action_page']);
	$formid = $row['form_id'];
	$form = '<form action="" method="post">';

	//query:
	$q = "SELECT fontsize, fonttype, fontcolor, fontbackground, buttonlabel, fieldorder, fieldtype, " . 
	"fieldrequired, fieldsize, fieldmaxsz, fieldrows, fieldtext, fieldoptions " . 
	"FROM forms " . 
	"WHERE account_id='{$_SESSION['account_id']}' AND id='$formid'";

	$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
	mysqli_error($dbc));

	//echo results of form query:
	if (mysqli_num_rows($r) > 0) {
		//iterate through form members and assign to variables and arrays:
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			$existfontsize = $row['fontsize']; $existfonttype = $row['fonttype'];
			$existfontcolor = $row['fontcolor']; $existfontbackground = $row['fontbackground'];
			$existbuttonlabel = $row['buttonlabel']; $existfieldorder = explode(",", $row['fieldorder']);
			$existfieldtype = explode(",", $row['fieldtype']); $existfieldrequired = explode(",", $row['fieldrequired']);
			$existfieldsize = explode(",", $row['fieldsize']); $existfieldmaxsz = explode(",", $row['fieldmaxsz']);
			$existfieldrows = explode(",", $row['fieldrows']); $existfieldtext = explode(",", $row['fieldtext']);
			$existfieldoptions = explode(",", $row['fieldoptions']);
		}
		//count number of elements:
		$numelements = count($existfieldorder);
		//Apply a div to the form for characteristics that apply to whole thing:
		$form .= '<div style="background-color:' . $existfontbackground . ';width:480px;">';
		//cycle through fields and parse elements of form:
		$elementcounter = 1;
		while($elementcounter <= $numelements) {
			//Table for organizing form elements:
			$tablestring = '<table width="450px" border="0" cellpadding="0"' . 
			'cellspacing="0">' . 
			'<tr><td id="utext_' . $elementcounter . '" width="45%" style="font-size:' . 
			$existfontsize . ';font-family:' . $existfonttype . ';color:' . 
			$existfontcolor . ';">';
		
			//Create string depending on type of field:
			switch ($existfieldtype[$elementcounter-1]) {
			    case "TEXTADDRESS":
			        $label = "Address"; 
					$fieldstring = '<input type="text" name="address" id="text_' . $elementcounter . '" style="width:' . $existfieldsize[$elementcounter-1] . ';" maxlength="' . 
					$existfieldmaxsz[$elementcounter-1] . '">';
					//if required field, set hidden variable:
					if($existfieldrequired[$elementcounter-1]=="Yes") {
						$fieldstring .= '<input type="hidden" name="addressrequired" value="Y" />';
					}
					break;
			    case "TEXTCITY":
			        $label = "City";
					$fieldstring = '<input type="text" name="city" id="text_' . $elementcounter . '" style="width:' . $existfieldsize[$elementcounter-1] . ';" maxlength="' . 
					$existfieldmaxsz[$elementcounter-1] . '">';
					if($existfieldrequired[$elementcounter-1]=="Yes") {
						$fieldstring .= '<input type="hidden" name="cityrequired" value="Y" />';
					}
					
					break;
			    case "SELECTSTATE":
			        $label = "State"; 
				    $fieldstring = '<select name="state" id="state_' . $elementcounter . '"><option value="AL">Alabama</option><option value="AK">Alaska</option><option value="AZ">Arizona</option><option value="AR">Arkansas</option><option value="CA">California</option><option value="CO">Colorado</option><option value="CT">Connecticut</option><option value="DE">Delaware</option><option value="DC">District Of Columbia</option><option value="FL">Florida</option><option value="GA">Georgia</option><option value="HI">Hawaii</option><option value="ID">Idaho</option><option value="IL">Illinois</option><option value="IN">Indiana</option><option value="IA">Iowa</option><option value="KS">Kansas</option><option value="KY">Kentucky</option><option value="LA">Louisiana</option><option value="ME">Maine</option><option value="MD">Maryland</option><option value="MA">Massachusetts</option><option value="MI">Michigan</option><option value="MN">Minnesota</option><option value="MS">Mississippi</option><option value="MO">Missouri</option><option value="MT">Montana</option><option value="NE">Nebraska</option><option value="NV">Nevada</option><option value="NH">New Hampshire</option><option value="NJ">New Jersey</option><option value="NM">New Mexico</option><option value="NY">New York</option><option value="NC">North Carolina</option><option value="ND">North Dakota</option><option value="OH">Ohio</option><option value="OK">Oklahoma</option><option value="OR">Oregon</option><option value="PA">Pennsylvania</option><option value="RI">Rhode Island</option><option value="SC">South Carolina</option><option value="SD">South Dakota</option><option value="TN">Tennessee</option><option value="TX">Texas</option><option value="UT">Utah</option><option value="VT">Vermont</option><option value="VA">Virginia</option><option value="WA">Washington</option><option value="WV">West Virginia</option><option value="WI">Wisconsin</option><option value="WY">Wyoming</option></select>';
					if($existfieldrequired[$elementcounter-1]=="Yes") {
						$fieldstring .= '<input type="hidden" name="staterequired" value="Y" />';
					}
								
					break;
			    case "SELECTCOUNTRY":
			        $label = "Country"; 
					$fieldstring = '<select name="country" id="country_' . $elementcounter . '"><option selected="selected" value="United States">United States</option><option value="United Kingdom">United Kingdom</option><option value="Afghanistan">Afghanistan</option><option value="Albania">Albania</option><option value="Algeria">Algeria</option><option value="American Samoa">American Samoa</option><option value="Andorra">Andorra</option><option value="Angola">Angola</option><option value="Anguilla">Anguilla</option><option value="Antarctica">Antarctica</option><option value="Antigua and Barbuda">Antigua and Barbuda</option><option value="Argentina">Argentina</option><option value="Armenia">Armenia</option><option value="Aruba">Aruba</option><option value="Australia">Australia</option><option value="Austria">Austria</option><option value="Azerbaijan">Azerbaijan</option><option value="Bahamas">Bahamas</option><option value="Bahrain">Bahrain</option><option value="Bangladesh">Bangladesh</option><option value="Barbados">Barbados</option><option value="Belarus">Belarus</option><option value="Belgium">Belgium</option><option value="Belize">Belize</option><option value="Benin">Benin</option>		<option value="Bermuda">Bermuda</option><option value="Bhutan">Bhutan</option><option value="Bolivia">Bolivia</option><option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option><option value="Botswana">Botswana</option><option value="Bouvet Island">Bouvet Island</option><option value="Brazil">Brazil</option><option value="British Indian Ocean Territory">Brit. Indian Ocean Terr</option><option value="Brunei Darussalam">Brunei Darussalam</option><option value="Bulgaria">Bulgaria</option><option value="Burkina Faso">Burkina Faso</option><option value="Burundi">Burundi</option><option value="Cambodia">Cambodia</option><option value="Cameroon">Cameroon</option><option value="Canada">Canada</option><option value="Cape Verde">Cape Verde</option><option value="Cayman Islands">Cayman Islands</option><option value="Central African Republic">Central African Republic</option><option value="Chad">Chad</option><option value="Chile">Chile</option>		<option value="China">China</option><option value="Christmas Island">Christmas Island</option><option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option><option value="Colombia">Colombia</option>		<option value="Comoros">Comoros</option><option value="Congo">Congo</option><option value="Congo, The Democratic Republic of The">Congo,Dem Rep of</option><option value="Cook Islands">Cook Islands</option>		<option value="Costa Rica">Costa Rica</option><option value="Cote Divoire">Cote Divoire</option><option value="Croatia">Croatia</option><option value="Cuba">Cuba</option><option value="Cyprus">Cyprus</option>	<option value="Czech Republic">Czech Republic</option><option value="Denmark">Denmark</option><option value="Djibouti">Djibouti</option><option value="Dominica">Dominica</option><option value="Dominican Republic">Dominican Republic</option><option value="Ecuador">Ecuador</option><option value="Egypt">Egypt</option>		<option value="El Salvador">El Salvador</option><option value="Equatorial Guinea">Equatorial Guinea</option>	<option value="Eritrea">Eritrea</option><option value="Estonia">Estonia</option><option value="Ethiopia">Ethiopia</option><option value="Falkland Islands (Malvinas)">Falkland Islands</option><option value="Faroe Islands">Faroe Islands</option><option value="Fiji">Fiji</option>		<option value="Finland">Finland</option><option value="France">France</option><option value="French Guiana">French Guiana</option><option value="French Polynesia">French Polynesia</option><option value="French Southern Territories">French Southern Terr.</option><option value="Gabon">Gabon</option><option value="Gambia">Gambia</option><option value="Georgia">Georgia</option><option value="Germany">Germany</option>		<option value="Ghana">Ghana</option><option value="Gibraltar">Gibraltar</option><option value="Greece">Greece</option><option value="Greenland">Greenland</option><option value="Grenada">Grenada</option><option value="Guadeloupe">Guadeloupe</option><option value="Guam">Guam</option>		<option value="Guatemala">Guatemala</option><option value="Guinea">Guinea</option><option value="Guinea-bissau">Guinea-bissau</option><option value="Guyana">Guyana</option><option value="Haiti">Haiti</option><option value="Heard Island and Mcdonald Islands">Heard Isl & Mcdonald Isl</option><option value="Holy See (Vatican City State)">Holy See(Vatican City)</option><option value="Honduras">Honduras</option><option value="Hong Kong">Hong Kong</option><option value="Hungary">Hungary</option><option value="Iceland">Iceland</option><option value="India">India</option>		<option value="Indonesia">Indonesia</option><option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option><option value="Iraq">Iraq</option><option value="Ireland">Ireland</option><option value="Israel">Israel</option><option value="Italy">Italy</option><option value="Jamaica">Jamaica</option>		<option value="Japan">Japan</option><option value="Jordan">Jordan</option><option value="Kazakhstan">Kazakhstan</option><option value="Kenya">Kenya</option><option value="Kiribati">Kiribati</option><option value="Korea, Democratic Peoples Republic of">Korea, Dem Ppls Rep</option><option value="Korea, Republic of">Korea, Republic of</option><option value="Kuwait">Kuwait</option><option value="Kyrgyzstan">Kyrgyzstan</option><option value="Lao Peoples Democratic Republic">Lao Peoples Dem Rep</option><option value="Latvia">Latvia</option><option value="Lebanon">Lebanon</option><option value="Lesotho">Lesotho</option><option value="Liberia">Liberia</option>		<option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option><option value="Liechtenstein">Liechtenstein</option><option value="Lithuania">Lithuania</option><option value="Luxembourg">Luxembourg</option><option value="Macao">Macao</option><option value="Macedonia, The Former Yugoslav Republic of">Macedonia, Former</option><option value="Madagascar">Madagascar</option><option value="Malawi">Malawi</option><option value="Malaysia">Malaysia</option><option value="Maldives">Maldives</option><option value="Mali">Mali</option>	<option value="Malta">Malta</option><option value="Marshall Islands">Marshall Islands</option><option value="Martinique">Martinique</option><option value="Mauritania">Mauritania</option><option value="Mauritius">Mauritius</option><option value="Mayotte">Mayotte</option><option value="Mexico">Mexico</option><option value="Micronesia, Federated States of">Micronesia, Fed States</option><option value="Moldova, Republic of">Moldova, Republic of</option><option value="Monaco">Monaco</option><option value="Mongolia">Mongolia</option><option value="Montserrat">Montserrat</option><option value="Morocco">Morocco</option><option value="Mozambique">Mozambique</option><option value="Myanmar">Myanmar</option><option value="Namibia">Namibia</option><option value="Nauru">Nauru</option><option value="Nepal">Nepal</option>	<option value="Netherlands">Netherlands</option><option value="Netherlands Antilles">Netherlands Antilles</option><option value="New Caledonia">New Caledonia</option><option value="New Zealand">New Zealand</option><option value="Nicaragua">Nicaragua</option><option value="Niger">Niger</option><option value="Nigeria">Nigeria</option><option value="Niue">Niue</option><option value="Norfolk Island">Norfolk Island</option><option value="Northern Mariana Islands">Northern Mariana Islands</option><option value="Norway">Norway</option><option value="Oman">Oman</option><option value="Pakistan">Pakistan</option>		<option value="Palau">Palau</option><option value="Palestinian Territory, Occupied">Palestinian Terr., Occ.</option><option value="Panama">Panama</option><option value="Papua New Guinea">Papua New Guinea</option>		<option value="Paraguay">Paraguay</option><option value="Peru">Peru</option><option value="Philippines">Philippines</option><option value="Pitcairn">Pitcairn</option><option value="Poland">Poland</option><option value="Portugal">Portugal</option><option value="Puerto Rico">Puerto Rico</option><option value="Qatar">Qatar</option><option value="Reunion">Reunion</option><option value="Romania">Romania</option><option value="Russian Federation">Russian Federation</option><option value="Rwanda">Rwanda</option><option value="Saint Helena">Saint Helena</option><option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option><option value="Saint Lucia">Saint Lucia</option><option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option><option value="Saint Vincent and The Grenadines">St Vincent & Gren</option><option value="Samoa">Samoa</option><option value="San Marino">San Marino</option>		<option value="Sao Tome and Principe">Sao Tome and Principe</option><option value="Saudi Arabia">Saudi Arabia</option><option value="Senegal">Senegal</option><option value="Serbia and Montenegro">Serbia and Montenegro</option><option value="Seychelles">Seychelles</option><option value="Sierra Leone">Sierra Leone</option><option value="Singapore">Singapore</option><option value="Slovakia">Slovakia</option>		<option value="Slovenia">Slovenia</option><option value="Solomon Islands">Solomon Islands</option>		<option value="Somalia">Somalia</option><option value="South Africa">South Africa</option><option value="South Georgia and The South Sandwich Islands">So. Georgia</option><option value="Spain">Spain</option><option value="Sri Lanka">Sri Lanka</option><option value="Sudan">Sudan</option>		<option value="Suriname">Suriname</option><option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>		<option value="Swaziland">Swaziland</option><option value="Sweden">Sweden</option><option value="Switzerland">Switzerland</option><option value="Syrian Arab Republic">Syrian Arab Republic</option>		<option value="Taiwan, Province of China">Taiwan, Province of China</option><option value="Tajikistan">Tajikistan</option><option value="Tanzania, United Republic of">Tanzania, United Rep.</option><option value="Thailand">Thailand</option><option value="Timor-leste">Timor-leste</option>		<option value="Togo">Togo</option><option value="Tokelau">Tokelau</option>		<option value="Tonga">Tonga</option><option value="Trinidad and Tobago">Trinidad and Tobago</option><option value="Tunisia">Tunisia</option><option value="Turkey">Turkey</option><option value="Turkmenistan">Turkmenistan</option><option value="Turks and Caicos Islands">Turks and Caicos Islands</option><option value="Tuvalu">Tuvalu</option><option value="Uganda">Uganda</option>		<option value="Ukraine">Ukraine</option><option value="United Arab Emirates">United Arab Emirates</option><option value="United Kingdom">United Kingdom</option><option value="United States">United States</option>		<option value="United States Minor Outlying Islands">U.S. Minor Outly Isl</option><option value="Uruguay">Uruguay</option><option value="Uzbekistan">Uzbekistan</option><option value="Vanuatu">Vanuatu</option><option value="Venezuela">Venezuela</option>		<option value="Viet Nam">Viet Nam</option><option value="Virgin Islands, British">Virgin Islands, British</option><option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option><option value="Wallis and Futuna">Wallis and Futuna</option>		<option value="Western Sahara">Western Sahara</option><option value="Yemen">Yemen</option><option value="Zambia">Zambia</option><option value="Zimbabwe">Zimbabwe</option></select>';		
					if($existfieldrequired[$elementcounter-1]=="Yes") {
						$fieldstring .= '<input type="hidden" name="countryrequired" value="Y" />';
					}
		
					break;
			    case "TEXTZIP":
			        $label = "Zip"; 
					$fieldstring = '<input type="text" name="zip" id="text_' . $elementcounter . '" style="width:' . $existfieldsize[$elementcounter-1] . ';" maxlength="' . 
					$existfieldmaxsz[$elementcounter-1] . '">';
					if($existfieldrequired[$elementcounter-1]=="Yes") {
						$fieldstring .= '<input type="hidden" name="ziprequired" value="Y" />';
					}
				break;
			    case "USERTEXT":
			        $label = $existfieldtext[$elementcounter - 1]; 
					break;
			    case "INQUIRY":
			        $label = "Inquiry"; 
					$fieldstring = '<textarea name="inquiry" id="text_' . $elementcounter . '" style="width:' . $existfieldsize[$elementcounter-1] . ';height:' . 
				$existfieldrows[$elementcounter-1] . '"></textarea>';
					if($existfieldrequired[$elementcounter-1]=="Yes") {
						$fieldstring .= '<input type="hidden" name="inquiryrequired" value="Y" />';
					}
					break;
			    case "CUSTOMTEXTBOX": 
			        $label = '<input type="hidden" name="q' . $elementcounter . 'label" value="' . $existfieldtext[$elementcounter-1] . '" />' . $existfieldtext[$elementcounter - 1]; 
					$fieldstring = '<input type="text" name="q' . $elementcounter . '" id="customtext_' . $elementcounter . '" style="width:' . $existfieldsize[$elementcounter-1] . ';" maxlength="' . 
					$existfieldmaxsz[$elementcounter-1] . '">';
					if($existfieldrequired[$elementcounter-1]=="Yes") {
						$fieldstring .= '<input type="hidden" name="q' . 
						$elementcounter . 'required" value="Y" />';
					}
					break;
			    case "CUSTOMCHECKBOX":
			        $label = '<input type="hidden" name="q' . $elementcounter . 'label" value="' . $existfieldtext[$elementcounter-1] . '" />' . $existfieldtext[$elementcounter - 1]; 
					$optionsarry = explode("|", $existfieldoptions[$elementcounter - 1]);				
					$loopcounter = 1;
					$fieldstring = '<div id="checkboxdiv_' . $elementcounter . '" style="display:inline;">';
					foreach($optionsarry as $optionrow) {
						if($loopcounter>1) $fieldstring .= '<br />'; 
						$fieldstring .= '<input type="checkbox" name="q' . $elementcounter .
						'[' . $loopcounter . ']' . '" id="ccheckbox_' . $elementcounter . '" value="' . $optionrow . '" />' . $optionrow;
						$loopcounter++;
					}
					$fieldstring .= '</div>';
					break;
			    case "CUSTOMRADIO":
			        $label = '<input type="hidden" name="q' . $elementcounter . 'label" value="' . $existfieldtext[$elementcounter-1] . '" />' . $existfieldtext[$elementcounter - 1]; 
					$optionsarry = explode("|", $existfieldoptions[$elementcounter - 1]);				
					$loopcounter = 1;
					$fieldstring = '<div id="radiodiv_' . $elementcounter . '" style="display:inline;">';
					foreach($optionsarry as $optionrow) {
						if($loopcounter>1) $fieldstring .= '<br />'; 
						$fieldstring .= '<input type="radio" name="q' . $elementcounter . '" id="cradio_' . $elementcounter . '" value="' . $optionrow . '" />' . $optionrow;
						$loopcounter++;
					}
					$fieldstring .= '</div>';
					break;
			    case "CUSTOMTEXTAREA":
			        $label = '<input type="hidden" name="q' . $elementcounter . 'label" value="' . $existfieldtext[$elementcounter-1] . '" />' . $existfieldtext[$elementcounter - 1]; 
					$fieldstring = '<textarea name="q' . $elementcounter . '" id="text_' . $elementcounter . '" style="width:' . $existfieldsize[$elementcounter-1] . ';height:' . 
					$existfieldrows[$elementcounter-1] . '"></textarea>';	
					if($existfieldrequired[$elementcounter-1]=="Yes") {
						$fieldstring .= '<input type="hidden" name="q' . 
						$elementcounter . 'required" value="Y" />';
					}
					break;
			    case "CUSTOMDROPDOWN":
			        $label = '<input type="hidden" name="q' . $elementcounter . 'label" value="' . $existfieldtext[$elementcounter-1] . '" />' . $existfieldtext[$elementcounter - 1]; 
					$fieldstring = '<select name="q' . $elementcounter . '" id="cdropdown_' . $elementcounter . '">';
					$optionsarry = explode("|", $existfieldoptions[$elementcounter - 1]);
					foreach($optionsarry as $optionrow) {
				      	$fieldstring .= '<option>' . $optionrow . '</option>';
					}
					$fieldstring .= '</select>';
					break;
			    case "TEXTFIRSTNAME":
			        $label = "First Name"; 
					$fieldstring = '<input type="text" name="firstname" id="text_' . $elementcounter . '" style="width:' . $existfieldsize[$elementcounter-1] . ';" maxlength="' . 
					$existfieldmaxsz[$elementcounter-1] . '">';
					if($existfieldrequired[$elementcounter-1]=="Yes") {
						$fieldstring .= '<input type="hidden" name="firstnamerequired" value="Y" />';
					}
				break;
			    case "TEXTLASTNAME":
			        $label = "Last Name"; 
					$fieldstring = '<input type="text" name="lastname" id="text_' . $elementcounter . '" style="width:' . $existfieldsize[$elementcounter-1] . ';" maxlength="' . 
					$existfieldmaxsz[$elementcounter-1] . '">';
					if($existfieldrequired[$elementcounter-1]=="Yes") {
						$fieldstring .= '<input type="hidden" name="lastnamerequired" value="Y" />';
					}
				break;
			    case "TEXTEMAIL":
			        $label = "Email"; 
					$fieldstring = '<input type="text" name="email" id="text_' . $elementcounter . '" style="width:' . $existfieldsize[$elementcounter-1] . ';" maxlength="' . 
					$existfieldmaxsz[$elementcounter-1] . '">';	
					if($existfieldrequired[$elementcounter-1]=="Yes") {
						$fieldstring .= '<input type="hidden" name="emailrequired" value="Y" />';
					}
				break;
			    case "TEXTTITLE":
			        $label = "Title"; 
					$fieldstring = '<input type="text" name="title" id="text_' . $elementcounter . '" style="width:' . $existfieldsize[$elementcounter-1] . ';" maxlength="' . 
					$existfieldmaxsz[$elementcounter-1] . '">';
					if($existfieldrequired[$elementcounter-1]=="Yes") {
						$fieldstring .= '<input type="hidden" name="titlerequired" value="Y" />';
					}
				break;
			    case "TEXTCOMPANY":
			        $label = "Company"; 
					$fieldstring = '<input type="text" name="company" id="text_' . $elementcounter . '" style="width:' . $existfieldsize[$elementcounter-1] . ';" maxlength="' . 
					$existfieldmaxsz[$elementcounter-1] . '">';
					if($existfieldrequired[$elementcounter-1]=="Yes") {
						$fieldstring .= '<input type="hidden" name="companyrequired" value="Y" />';
					}
				break;
			    case "TEXTTELEPHONE":
			        $label = "Telephone"; 
					$fieldstring = '<input type="text" name="telephone" id="text_' . $elementcounter . '" style="width:' . $existfieldsize[$elementcounter-1] . ';" maxlength="' . 
					$existfieldmaxsz[$elementcounter-1] . '">';
					if($existfieldrequired[$elementcounter-1]=="Yes") {
						$fieldstring .= '<input type="hidden" name="telephonerequired" value="Y" />';
					}
				break;	
			    default:
				$label = "Unknown";
				break;
			}
		
			//Write to string for use in [form] element of action page:
			$form .= $tablestring . $label . '</td><td width="45%">' . $fieldstring . '</td></tr></table><br />';
			$elementcounter++;
		}
	}

	//Append Submit button for form, close out form and div:
	$form .= '<input type="hidden" name="submitted" value="TRUE" />'; 
	$form .= '<input type="submit" value="' . $existbuttonlabel . '" /></form></div>';

	//Search for [form] string in action page, replace with form:
	if (strchr($actionpage, '[form]')) {
		$output = str_replace('[form]', $form, $actionpage);
	} else {
		$output = $actionpage . $form;
	}
	echo $output;
} else {
	echo 'You have reached this page in error.';
}

echo '</div></body></html>';
?>