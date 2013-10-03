<?php
//Start output buffering:
ob_start();

//Initialize a session if one not already in use:
if (session_id()=="") session_start();
?>

<html>
<head>
<title>Draggables Elements</title>
<script type="text/javascript" 
        src="/includes/prototype.js"></script>
<script type="text/javascript"
        src="/includes/scriptaculous/scriptaculous.js"></script>
<script type="text/javascript" src="/includes/ajax.js"></script>

<script type='text/javascript'>	
var itemnum = 1;
//Ajax Initialize:
window.onload = init;

function init() {
	var ajax = getXMLHttpRequestObject();
}

//Ajax code to hit MySQL datebase to look for availability of form name:
function checkFormName() {
	var ajax = getXMLHttpRequestObject();
	var cname = document.getElementById('formname').value;
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
			var results = document.getElementById('results');
			//var quicksave = document.getElementById('quicksavebutton');			
			results.innerHTML = ajax.responseText;
			results.style.display = 'block';
		} else {
			document.getElementById('autoform').submit();
		}
	}
}	

//Init variables for use with form attributes:
var fieldrequired = new Array();
var fieldtype = new Array();
var fieldtext = new Array();
var fieldsize = new Array();
var fieldmaxsize = new Array();
var fieldrows = new Array();
var fieldoptions = new Array();
var fontsize = "16px";
var fonttype = "Times New Roman";
var fontcolor = "Black";
var fontbackground = "White";
var buttonlabel = "submit";
</script>

</head>
<body>

<p><b>Drag Fields Over to Build Your Form!</b></p>	
<table border = "0"><tr><td>Name of Form:</td><td><input type="text" width="250px" id="formname" onblur="checkFormName()" /></td><td>
<input type="button" value="Save Form" onclick="itemize();" /></td></tr></table>
<br /><div id="results"></div>

<table width="800px" border="1" cellpadding="0" cellspacing="0" id="allTable" style="height:90%;">
<tr><td valign="top" width="60%" id="formTable">
<ul id="thelist" style="padding: 10;
	margin-left: 0;"><br />


<?php //code to read if prebuilt or existing form:
if(isset($_REQUEST['action'])) {
	if( ($_REQUEST['action']=='prebuilt' || $_REQUEST['action']=='existing') && isset($_REQUEST['formID']) ) {
		$formid = $_REQUEST['formID'];
		require_once('/home/rneel/web/htdocs/includes/config.inc.php');

		//MySQL setup:
		require_once(MYSQL);
		
		if($_REQUEST['action']=='existing') {
			//query for selected action pages for this account:
			$q = "SELECT fontsize, fonttype, fontcolor, fontbackground, buttonlabel, fieldorder, fieldtype, " . 
			"fieldrequired, fieldsize, fieldmaxsz, fieldrows, fieldtext, fieldoptions " . 
			"FROM forms " . 
			"WHERE account_id='{$_SESSION['account_id']}' AND id='$formid'";
		} else { //go look up prebuilt form:
			$q = "SELECT fontsize, fonttype, fontcolor, fontbackground, buttonlabel, fieldorder, fieldtype, " . 
			"fieldrequired, fieldsize, fieldmaxsz, fieldrows, fieldtext, fieldoptions " . 
			"FROM forms " . 
			"WHERE id='$formid' AND id<5";			
		}
		$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
		mysqli_error($dbc));

		//echo results of form query:
		if (mysqli_num_rows($r) > 0) {
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
			//cycle through fields and parse elements of form:
			$elementcounter = 1;
			while($elementcounter <= $numelements) {
				//feed form  attributes from table into js variables
				echo '<script>fontsize="' . $existfontsize . '";</script>';
				echo '<script>fonttype="' . $existfonttype . '";</script>';
				echo '<script>fontcolor="' . $existfontcolor . '";</script>';
				echo '<script>fontbackground="' . $existfontbackground . '";</script>';
				echo '<script>buttonlabel="' . $existbuttonlabel . '";</script>';				
				//feed field attributes from table into js arrays for use by user
				echo '<script>fieldrequired["' . intval($elementcounter) . '"]="' . $existfieldrequired[intval($elementcounter-1)] . '";</script>';
				echo '<script>fieldtype["' . intval($elementcounter) . '"]="' . $existfieldtype[intval($elementcounter-1)] . '";</script>';
				echo '<script>fieldtext["' . intval($elementcounter) . '"]="' . $existfieldtext[intval($elementcounter-1)] . '";</script>';
				echo '<script>fieldsize["' . intval($elementcounter) . '"]="' . $existfieldsize[intval($elementcounter-1)] . '";</script>';
				echo '<script>fieldmaxsize["' . intval($elementcounter) . '"]="' . $existfieldmaxsz[intval($elementcounter-1)] . '";</script>';
				echo '<script>fieldrows["' . intval($elementcounter) . '"]="' . $existfieldrows[intval($elementcounter-1)] . '";</script>';
				echo '<script>fieldoptions["' . intval($elementcounter) . '"]="' . $existfieldoptions[intval($elementcounter-1)] . '";</script>';

				//build string for entry, <li> spec first:
				$listring = '<li id="item_' . $elementcounter . '" style="list-style-type: none">';
				//table element:
				$tablestring = '<table width="450px" border="0" cellpadding="0" cellspacing="0"><tr><td id="utext_' . $elementcounter . '" width="45%">';
				$dragfieldstring = '';
				//Select what text to show:
				switch ($existfieldtype[$elementcounter-1]) {
				    case "TEXTADDRESS":
				        $label = "Address"; 
						$dragfieldstring = '<input type="text" name="address" id="text_' . $elementcounter . '" style="width:150">';
						break;
				    case "TEXTCITY":
				        $label = "City";
						$dragfieldstring = '<input type="text" name="city" id="text_' . $elementcounter . '" style="width:150">';
						break;
				    case "SELECTSTATE":
				        $label = "State"; 
					    $dragfieldstring = '<select nafieldrequired["' . intval($elementcounter-1) . '"]me="state" id="state_' . $elementcounter . '"><option value="AL">Alabama</option><option value="AK">Alaska</option><option value="AZ">Arizona</option><option value="AR">Arkansas</option><option value="CA">California</option><option value="CO">Colorado</option><option value="CT">Connecticut</option><option value="DE">Delaware</option><option value="DC">District Of Columbia</option><option value="FL">Florida</option><option value="GA">Georgia</option><option value="HI">Hawaii</option><option value="ID">Idaho</option><option value="IL">Illinois</option><option value="IN">Indiana</option><option value="IA">Iowa</option><option value="KS">Kansas</option><option value="KY">Kentucky</option><option value="LA">Louisiana</option><option value="ME">Maine</option><option value="MD">Maryland</option><option value="MA">Massachusetts</option><option value="MI">Michigan</option><option value="MN">Minnesota</option><option value="MS">Mississippi</option><option value="MO">Missouri</option><option value="MT">Montana</option><option value="NE">Nebraska</option><option value="NV">Nevada</option><option value="NH">New Hampshire</option><option value="NJ">New Jersey</option><option value="NM">New Mexico</option><option value="NY">New York</option><option value="NC">North Carolina</option><option value="ND">North Dakota</option><option value="OH">Ohio</option><option value="OK">Oklahoma</option><option value="OR">Oregon</option><option value="PA">Pennsylvania</option><option value="RI">Rhode Island</option><option value="SC">South Carolina</option><option value="SD">South Dakota</option><option value="TN">Tennessee</option><option value="TX">Texas</option><option value="UT">Utah</option><option value="VT">Vermont</option><option value="VA">Virginia</option><option value="WA">Washington</option><option value="WV">West Virginia</option><option value="WI">Wisconsin</option><option value="WY">Wyoming</option></select>';				
						break;
				    case "SELECTCOUNTRY":
				        $label = "Country"; 
						$dragfieldstring = '<select name="country" id="country_' . $elementcounter . '"><option selected="selected" value="United States">United States</option><option value="United Kingdom">United Kingdom</option><option value="Afghanistan">Afghanistan</option><option value="Albania">Albania</option><option value="Algeria">Algeria</option><option value="American Samoa">American Samoa</option><option value="Andorra">Andorra</option><option value="Angola">Angola</option><option value="Anguilla">Anguilla</option><option value="Antarctica">Antarctica</option><option value="Antigua and Barbuda">Antigua and Barbuda</option><option value="Argentina">Argentina</option><option value="Armenia">Armenia</option><option value="Aruba">Aruba</option><option value="Australia">Australia</option><option value="Austria">Austria</option><option value="Azerbaijan">Azerbaijan</option><option value="Bahamas">Bahamas</option><option value="Bahrain">Bahrain</option><option value="Bangladesh">Bangladesh</option><option value="Barbados">Barbados</option><option value="Belarus">Belarus</option><option value="Belgium">Belgium</option><option value="Belize">Belize</option><option value="Benin">Benin</option>		<option value="Bermuda">Bermuda</option><option value="Bhutan">Bhutan</option><option value="Bolivia">Bolivia</option><option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option><option value="Botswana">Botswana</option><option value="Bouvet Island">Bouvet Island</option><option value="Brazil">Brazil</option><option value="British Indian Ocean Territory">Brit. Indian Ocean Terr</option><option value="Brunei Darussalam">Brunei Darussalam</option><option value="Bulgaria">Bulgaria</option><option value="Burkina Faso">Burkina Faso</option><option value="Burundi">Burundi</option><option value="Cambodia">Cambodia</option><option value="Cameroon">Cameroon</option><option value="Canada">Canada</option><option value="Cape Verde">Cape Verde</option><option value="Cayman Islands">Cayman Islands</option><option value="Central African Republic">Central African Republic</option><option value="Chad">Chad</option><option value="Chile">Chile</option>		<option value="China">China</option><option value="Christmas Island">Christmas Island</option><option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option><option value="Colombia">Colombia</option>		<option value="Comoros">Comoros</option><option value="Congo">Congo</option><option value="Congo, The Democratic Republic of The">Congo,Dem Rep of</option><option value="Cook Islands">Cook Islands</option>		<option value="Costa Rica">Costa Rica</option><option value="Cote Divoire">Cote Divoire</option><option value="Croatia">Croatia</option><option value="Cuba">Cuba</option><option value="Cyprus">Cyprus</option>	<option value="Czech Republic">Czech Republic</option><option value="Denmark">Denmark</option><option value="Djibouti">Djibouti</option><option value="Dominica">Dominica</option><option value="Dominican Republic">Dominican Republic</option><option value="Ecuador">Ecuador</option><option value="Egypt">Egypt</option>		<option value="El Salvador">El Salvador</option><option value="Equatorial Guinea">Equatorial Guinea</option>	<option value="Eritrea">Eritrea</option><option value="Estonia">Estonia</option><option value="Ethiopia">Ethiopia</option><option value="Falkland Islands (Malvinas)">Falkland Islands</option><option value="Faroe Islands">Faroe Islands</option><option value="Fiji">Fiji</option>		<option value="Finland">Finland</option><option value="France">France</option><option value="French Guiana">French Guiana</option><option value="French Polynesia">French Polynesia</option><option value="French Southern Territories">French Southern Terr.</option><option value="Gabon">Gabon</option><option value="Gambia">Gambia</option><option value="Georgia">Georgia</option><option value="Germany">Germany</option>		<option value="Ghana">Ghana</option><option value="Gibraltar">Gibraltar</option><option value="Greece">Greece</option><option value="Greenland">Greenland</option><option value="Grenada">Grenada</option><option value="Guadeloupe">Guadeloupe</option><option value="Guam">Guam</option>		<option value="Guatemala">Guatemala</option><option value="Guinea">Guinea</option><option value="Guinea-bissau">Guinea-bissau</option><option value="Guyana">Guyana</option><option value="Haiti">Haiti</option><option value="Heard Island and Mcdonald Islands">Heard Isl & Mcdonald Isl</option><option value="Holy See (Vatican City State)">Holy See(Vatican City)</option><option value="Honduras">Honduras</option><option value="Hong Kong">Hong Kong</option><option value="Hungary">Hungary</option><option value="Iceland">Iceland</option><option value="India">India</option>		<option value="Indonesia">Indonesia</option><option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option><option value="Iraq">Iraq</option><option value="Ireland">Ireland</option><option value="Israel">Israel</option><option value="Italy">Italy</option><option value="Jamaica">Jamaica</option>		<option value="Japan">Japan</option><option value="Jordan">Jordan</option><option value="Kazakhstan">Kazakhstan</option><option value="Kenya">Kenya</option><option value="Kiribati">Kiribati</option><option value="Korea, Democratic Peoples Republic of">Korea, Dem Ppls Rep</option><option value="Korea, Republic of">Korea, Republic of</option><option value="Kuwait">Kuwait</option><option value="Kyrgyzstan">Kyrgyzstan</option><option value="Lao Peoples Democratic Republic">Lao Peoples Dem Rep</option><option value="Latvia">Latvia</option><option value="Lebanon">Lebanon</option><option value="Lesotho">Lesotho</option><option value="Liberia">Liberia</option>		<option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option><option value="Liechtenstein">Liechtenstein</option><option value="Lithuania">Lithuania</option><option value="Luxembourg">Luxembourg</option><option value="Macao">Macao</option><option value="Macedonia, The Former Yugoslav Republic of">Macedonia, Former</option><option value="Madagascar">Madagascar</option><option value="Malawi">Malawi</option><option value="Malaysia">Malaysia</option><option value="Maldives">Maldives</option><option value="Mali">Mali</option>	<option value="Malta">Malta</option><option value="Marshall Islands">Marshall Islands</option><option value="Martinique">Martinique</option><option value="Mauritania">Mauritania</option><option value="Mauritius">Mauritius</option><option value="Mayotte">Mayotte</option><option value="Mexico">Mexico</option><option value="Micronesia, Federated States of">Micronesia, Fed States</option><option value="Moldova, Republic of">Moldova, Republic of</option><option value="Monaco">Monaco</option><option value="Mongolia">Mongolia</option><option value="Montserrat">Montserrat</option><option value="Morocco">Morocco</option><option value="Mozambique">Mozambique</option><option value="Myanmar">Myanmar</option><option value="Namibia">Namibia</option><option value="Nauru">Nauru</option><option value="Nepal">Nepal</option>	<option value="Netherlands">Netherlands</option><option value="Netherlands Antilles">Netherlands Antilles</option><option value="New Caledonia">New Caledonia</option><option value="New Zealand">New Zealand</option><option value="Nicaragua">Nicaragua</option><option value="Niger">Niger</option><option value="Nigeria">Nigeria</option><option value="Niue">Niue</option><option value="Norfolk Island">Norfolk Island</option><option value="Northern Mariana Islands">Northern Mariana Islands</option><option value="Norway">Norway</option><option value="Oman">Oman</option><option value="Pakistan">Pakistan</option>		<option value="Palau">Palau</option><option value="Palestinian Territory, Occupied">Palestinian Terr., Occ.</option><option value="Panama">Panama</option><option value="Papua New Guinea">Papua New Guinea</option>		<option value="Paraguay">Paraguay</option><option value="Peru">Peru</option><option value="Philippines">Philippines</option><option value="Pitcairn">Pitcairn</option><option value="Poland">Poland</option><option value="Portugal">Portugal</option><option value="Puerto Rico">Puerto Rico</option><option value="Qatar">Qatar</option><option value="Reunion">Reunion</option><option value="Romania">Romania</option><option value="Russian Federation">Russian Federation</option><option value="Rwanda">Rwanda</option><option value="Saint Helena">Saint Helena</option><option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option><option value="Saint Lucia">Saint Lucia</option><option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option><option value="Saint Vincent and The Grenadines">St Vincent & Gren</option><option value="Samoa">Samoa</option><option value="San Marino">San Marino</option>		<option value="Sao Tome and Principe">Sao Tome and Principe</option><option value="Saudi Arabia">Saudi Arabia</option><option value="Senegal">Senegal</option><option value="Serbia and Montenegro">Serbia and Montenegro</option><option value="Seychelles">Seychelles</option><option value="Sierra Leone">Sierra Leone</option><option value="Singapore">Singapore</option><option value="Slovakia">Slovakia</option>		<option value="Slovenia">Slovenia</option><option value="Solomon Islands">Solomon Islands</option>		<option value="Somalia">Somalia</option><option value="South Africa">South Africa</option><option value="South Georgia and The South Sandwich Islands">So. Georgia</option><option value="Spain">Spain</option><option value="Sri Lanka">Sri Lanka</option><option value="Sudan">Sudan</option>		<option value="Suriname">Suriname</option><option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>		<option value="Swaziland">Swaziland</option><option value="Sweden">Sweden</option><option value="Switzerland">Switzerland</option><option value="Syrian Arab Republic">Syrian Arab Republic</option>		<option value="Taiwan, Province of China">Taiwan, Province of China</option><option value="Tajikistan">Tajikistan</option><option value="Tanzania, United Republic of">Tanzania, United Rep.</option><option value="Thailand">Thailand</option><option value="Timor-leste">Timor-leste</option>		<option value="Togo">Togo</option><option value="Tokelau">Tokelau</option>		<option value="Tonga">Tonga</option><option value="Trinidad and Tobago">Trinidad and Tobago</option><option value="Tunisia">Tunisia</option><option value="Turkey">Turkey</option><option value="Turkmenistan">Turkmenistan</option><option value="Turks and Caicos Islands">Turks and Caicos Islands</option><option value="Tuvalu">Tuvalu</option><option value="Uganda">Uganda</option>		<option value="Ukraine">Ukraine</option><option value="United Arab Emirates">United Arab Emirates</option><option value="United Kingdom">United Kingdom</option><option value="United States">United States</option>		<option value="United States Minor Outlying Islands">U.S. Minor Outly Isl</option><option value="Uruguay">Uruguay</option><option value="Uzbekistan">Uzbekistan</option><option value="Vanuatu">Vanuatu</option><option value="Venezuela">Venezuela</option>		<option value="Viet Nam">Viet Nam</option><option value="Virgin Islands, British">Virgin Islands, British</option><option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option><option value="Wallis and Futuna">Wallis and Futuna</option>		<option value="Western Sahara">Western Sahara</option><option value="Yemen">Yemen</option><option value="Zambia">Zambia</option><option value="Zimbabwe">Zimbabwe</option></select>';				
						break;
				    case "TEXTZIP":
				        $label = "Zip"; 
					$dragfieldstring = '<input type="text" name="zip" id="text_' . $elementcounter . '" style="width:150">';
					break;
				    case "USERTEXT":
				        $label = $existfieldtext[$elementcounter - 1]; 
						break;
				    case "INQUIRY":
				        $label = "Inquiry"; 
					$dragfieldstring = '<textarea name="inquiry" id="text_' . $elementcounter . '" style="width:150;height:60"></textarea>';
						break;
				    case "CUSTOMTEXTBOX": 
				        $label = $existfieldtext[$elementcounter - 1]; 
					$dragfieldstring = '<input type="text" name="custom_textbox" id="customtext_' . $elementcounter . '" style="width:150">';
						break;
				    case "CUSTOMCHECKBOX":
				        $label = $existfieldtext[$elementcounter - 1]; 
						$optionsarry = explode("|", $existfieldoptions[$elementcounter - 1]);				
						$loopcounter = 1;
						$dragfieldstring = '<div id="checkboxdiv_' . $elementcounter . '" style="display:inline;">';
						foreach($optionsarry as $optionrow) {
							if($loopcounter>1) $dragfieldstring .= '<br />'; 
							$dragfieldstring .= '<input type="checkbox" name="custom_checkbox" id="ccheckbox_' . $elementcounter . '" value="' . $optionrow . '" />' . $optionrow;
							$loopcounter++;
						}
						$dragfieldstring .= '</div>';
						break;
				    case "CUSTOMRADIO":
				        $label = $existfieldtext[$elementcounter - 1]; 
						$optionsarry = explode("|", $existfieldoptions[$elementcounter - 1]);				
						$loopcounter = 1;
						$dragfieldstring = '<div id="radiodiv_' . $elementcounter . '" style="display:inline;">';
						foreach($optionsarry as $optionrow) {
							if($loopcounter>1) $dragfieldstring .= '<br />'; 
							$dragfieldstring .= '<input type="radio" name="custom_radiobutton" id="cradio_' . $elementcounter . '" value="' . $optionrow . '" />' . $optionrow;
							$loopcounter++;
						}
						$dragfieldstring .= '</div>';
						break;
				    case "CUSTOMTEXTAREA":
				        $label = $existfieldtext[$elementcounter - 1]; 
					$dragfieldstring = '<textarea name="custom_textarea" id="text_' . $elementcounter . '" style="width:150;height:60"></textarea>';				
						break;
				    case "CUSTOMDROPDOWN":
				        $label = $existfieldtext[$elementcounter - 1]; 
						$dragfieldstring = '<select name="custom_dropdown" id="cdropdown_' . $elementcounter . '">';
						$optionsarry = explode("|", $existfieldoptions[$elementcounter - 1]);
						foreach($optionsarry as $optionrow) {
					      	$dragfieldstring .= '<option>' . $optionrow . '</option>';
						}
						$dragfieldstring .= '</select>';
						break;
				    case "TEXTFIRSTNAME":
				        $label = "First Name"; 
					$dragfieldstring = '<input type="text" name="first_name" id="text_' . $elementcounter . '" style="width:150">';
					break;
				    case "TEXTLASTNAME":
				        $label = "Last Name"; 
					$dragfieldstring = '<input type="text" name="last_name" id="text_' . $elementcounter . '" style="width:150">';
					break;
				    case "TEXTEMAIL":
				        $label = "Email"; 
					$dragfieldstring = '<input type="text" name="email" id="text_' . $elementcounter . '" style="width:150">';				
					break;
				    case "TEXTTITLE":
				        $label = "Title"; 
					$dragfieldstring = '<input type="text" name="title" id="text_' . $elementcounter . '" style="width:150">';
					break;
				    case "TEXTCOMPANY":
				        $label = "Company"; 
					$dragfieldstring = '<input type="text" name="company" id="text_' . $elementcounter . '" style="width:150">';
					break;
				    case "TEXTTELEPHONE":
				        $label = "Telephone"; 
					$dragfieldstring = '<input type="text" name="telephone" id="text_' . $elementcounter . '" style="width:150">';
					break;	
				    default:
					$label = "Unknown";
					break;
				}
				$otherstuff = '</td><td width="5%"><img src="/images/delete.png" width="15" height="15" onclick="javascript: var d = document.getElementById(\'thelist\');var olddiv=document.getElementById(\'item_' . $elementcounter . '\'); d.removeChild(olddiv);"/></td><td width="5%"><input type="radio" name="fieldprops" value="' . $elementcounter . '" valign="top" onclick="selectedfield=' . $elementcounter . ';showfieldprops(' . $elementcounter . ');" /></td></tr></table><br />';

				//insert into chocoform:
				echo $listring . $tablestring . $label . '</td><td width="45%">' . $dragfieldstring . $otherstuff . '</li>';
				
				//increment element counter:
				$elementcounter++;
			}
			//create draggable list from inserted <li>'s:
			echo '<script>Sortable.create(\'thelist\')</script>';
			//set up itemnum for use in js scripting later:
			echo '<script>itemnum = ' . intval($elementcounter) . ';</script>';
		}
	}
} else {
	echo 'No parameter was passed to this page. You have reached this page in error.';
	exit();
}
?>

</ul>
<p><div align="center"><input type="button" value="submit" id="submitbutton">
&nbsp;<input type="radio" name="fieldprops" value="0" valign="top" onclick="selectedfield='0';showfieldprops('0');" />
	
</div></p>
</td>

<td width="16%" valign="top" align="center">
<p><b>Common Fields</b></p>
<div style="font-size:0.8em">
<div id="user_text" style="position: relative;" class="selections">
Text
</div>	
	
<div id="first_name" style="position: relative;" class="selections">
First Name
</div>

<div id="last_name" style="position: relative;" class="selections">
Last Name
</div>

<div id="email" style="position: relative;" class="selections">
Email
</div>

<div id="address" style="position: relative;" class="selections">
Address
</div>

<div id="city" style="position: relative;" class="selections">
City
</div>

<div id="state" style="position: relative;" class="selections">
State
</div>

<div id="zip" style="position: relative;" class="selections">
Zip
</div>

<div id="country" style="position: relative;" class="selections">
Country
</div>

<div id="title" style="position: relative;" class="selections">
Title
</div>

<div id="company" style="position: relative;" class="selections">
Company
</div>

<div id="telephone" style="position: relative;" class="selections">
Telephone
</div>

<div id="inquiry" style="position: relative;" class="selections">
Inquiry
</div>
<br /></div>

<p><b>Custom Fields</b></p>
<div style="font-size:0.8em">
<div id="custom_textbox" style="position: relative;" class="selections">
Custom TextBox
</div>	

<div id="custom_textarea" style="position: relative;" class="selections">
Custom Text Area
</div>	

<div id="custom_dropdown" style="position: relative;" class="selections">
Custom Dropdown
</div>	

<div id="custom_checkbox" style="position: relative;" class="selections">
Custom CheckBox
</div>	

<div id="custom_radiobutton" style="position: relative;" class="selections">
Custom Radio Button
</div>	

</div>
</td>
<td width="24%" valign="top" align="center">
<b>Form Attributes</b><br />
<div style="font-size:0.7em">
Font Size:<br />
<select name="fontsize" id="fontsize" onchange="changefontsize();">
<option id="16px">16px</option>
<option id="18px">18px</option>
<option id="20px">20px</option>
<option id="22px">22px</option>
<option id="24px">24px</option>
</select><br /><br />

<script type="text/javascript" charset="utf-8">	
//select font size if stored in table:
var d = document.getElementById('fontsize'); 
d.options[fontsize].selected = 'selected';

//apply font size to form:
var styles = {fontSize: d.value}; 
for (x in fieldrequired) {
	var x = document.getElementById('utext_'+x); 
	if(x) x.setStyle(styles);
}
</script>

Font:<br />
<select name="fontFamily" id="fontFamily" onchange="javascript: var d = document.getElementById('fontFamily'); var styles = {fontFamily: d.value}; var x = document.getElementById('thelist'); x.setStyle(styles);">
<option id="Times New Roman">Times New Roman</option>
<option id="Arial">Arial</option>
<option id="Verdana">Verdana</option>
<option id="Sans Serif">Sans Serif</option>
</select><br /><br />

<script type="text/javascript" charset="utf-8">	
//select font if stored in table:
var d = document.getElementById('fontFamily'); 
d.options[fonttype].selected = 'selected';

//apply font to form:
var styles = {fontFamily: d.value}; 
var x = document.getElementById('thelist');
x.setStyle(styles);
</script>

Font Color:<br />
<select name="color" id="color" onchange="changefontcolor();">
<option id="Black">Black</option>
<option id="Blue">Blue</option>
<option id="Red">Red</option>
<option id="Brown">Brown</option>
<option id="White">White</option>
</select><br /><br />

<script type="text/javascript" charset="utf-8">	
var d = document.getElementById('color'); 
d.options[fontcolor].selected = 'selected';

var d = document.getElementById('color'); 
var styles = {color: d.value}; 
for (x in fieldrequired) {
	var x = document.getElementById('utext_'+x); 
	if(x) x.setStyle(styles);		
}		
</script>

Bkgrnd Color:<br />
<select name="bckcolor" id="bckcolor" onchange="javascript: var d = document.getElementById('bckcolor'); var styles = {backgroundColor: d.value}; var x = document.getElementById('thelist'); x.setStyle(styles);">
<option id="White">White</option>	
<option id="Black">Black</option>
<option id="Blue">Blue</option>
<option id="Red">Red</option>
<option id="Brown">Brown</option>
</select></div>

<script type="text/javascript" charset="utf-8">	
var d = document.getElementById('bckcolor'); 
d.options[fontbackground].selected = 'selected';

var d = document.getElementById('bckcolor'); 
var styles = {backgroundColor: d.value}; 
var x = document.getElementById('thelist'); 
x.setStyle(styles);
</script>

<hr>
<div id="fieldattributes" style="display:none;">
<b>Field Attributes</b><br />
<div style="font-size:0.7em">
<!-- Label -->	
Label:<br />	
<input type="text" name="labeltext" size="15" id="labeltext" onkeydown="if(event.keyCode==13) labelchanged(value,selectedfield);" />
<br /><br />

<!-- Field Required Selector: -->
Required:<br />
<select name="required" id="required" onchange="requiredchanged(value,selectedfield);">
<option>No</option>
<option>Yes</option>
</select><br /><br />

<!-- Field Size Selector: -->
Size:<br />
<select name="size" id="size" onchange="sizechanged(value,selectedfield);">
<option>50</option>	
<option>100</option>
<option selected="selected">150</option>
<option>200</option>
</select><br /><br />

<!-- Field Maximum Size Selector: -->
Max Size:<br />
<select name="maxsize" id="maxsize" onchange="maxsizechanged(value,selectedfield);">
<option>10</option>	
<option selected="selected">30</option>
<option>50</option>
<option>70</option>
<option>90</option>
</select><br /><br />

<!-- Textarea Number of Rows Selector: -->
Height:<br />
<select name="numrows" id="numrows" onchange="numrowschanged(value,selectedfield);">
<option>40</option>	
<option selected="selected">60</option>
<option>80</option>
</select><br /><br />

<!-- Options -->	
Options (sep. by comma):<br />	
<input type="text" name="useroptions" size="15" id="useroptions" onkeydown="if(event.keyCode==13) optionschanged(value,selectedfield);" />

</div></div>
</td>	
</tr>
</table>
      
<script type="text/javascript" charset="utf-8">	
	//Set value for submit button label:
	fieldtext[0] = "submit";
	fieldtype[0] = "submitbutton";
	
	//functions to detect when user has selected different field options:
	labelchanged = function(value,selectedfd) {
		fieldtext[selectedfd] = value;
		if(selectedfd!=0) {
			document.getElementById('utext_'+selectedfd).innerHTML = value;
		}else{
			document.getElementById('submitbutton').value = value;
		}	
	}
	
	requiredchanged = function(value,selectedfd) {
		fieldrequired[selectedfd] = value;
	}

	sizechanged = function(value,selectedfd) {
		fieldsize[selectedfd] = value;
		var styles = {width: value}; https://www.google.com/bookmarks/l#!view=threadsmgmt&fo=Starred&q=label:%22Daily%22
		if(fieldtype[selectedfd].substr(0,4) == 'TEXT') {
			tempstring = 'text_';
		}else{
			tempstring = 'customtext_';
		}
		var x = document.getElementById(tempstring+selectedfd); 
		x.setStyle(styles);		
	}

	maxsizechanged = function(value,selectedfd) {
		fieldmaxsize[selectedfd] = value;
	}
	
	numrowschanged = function(value,selectedfd) {
		fieldrows[selectedfd] = value;
		var styles = {height: value}; 
		var x = document.getElementById('text_'+selectedfd); 
		x.setStyle(styles);		
	}
	
	optionschanged = function(value,selectedfd) {
		fieldoptions[selectedfd] = value;
		//parse out value in options field by commas, insert as options in pulldown, checkbox, radio:
		var optionsarry = fieldoptions[selectedfd].split(',');		
		if(fieldtype[selectedfd] == "CUSTOMDROPDOWN") {
			var obj = document.getElementById('cdropdown_'+selectedfd);
			obj.options.length = 0;
			for ( var i in optionsarry ) {
				obj.options[i] = new Option(optionsarry[i], optionsarry[i]);
			}	
		}else if(fieldtype[selectedfd] == "CUSTOMCHECKBOX") {
			checkboxcode = '';
			for ( i=0; i<=optionsarry.length-1; i++ ) {
				if(i>0) checkboxcode += '<br />';
				checkboxcode += '<input type="checkbox" name="ccheckbox_' + selectedfd + '" id="ccheckbox_' + selectedfd + '" value=' + optionsarry[i] + '" />' + optionsarry[i]; 
			}	
			document.getElementById('checkboxdiv_'+selectedfd).innerHTML = checkboxcode;
		}else if(fieldtype[selectedfd] == "CUSTOMRADIO") {
			radiocode = '';
			for (i=0; i<=optionsarry.length-1; i++) {
				if(i>0) radiocode += '<br />';
				radiocode += '<input type="radio" name="cradio_' + selectedfd + '" id="cradio_' + selectedfd + '" value=' + optionsarry[i] + '" />' + optionsarry[i]; 				
			}
		document.getElementById('radiodiv_'+selectedfd).innerHTML = radiocode;	
		}
	}

	//var itemnum = 1;
	var selectedfield = 0;

	new Draggable('user_text',{revert:true});
	new Draggable('first_name',{revert:true});
	new Draggable('last_name',{revert:true});
	new Draggable('email',{revert:true});
	new Draggable('address',{revert:true});
	new Draggable('city',{revert:true});
	new Draggable('state',{revert:true});
	new Draggable('zip',{revert:true});
	new Draggable('country',{revert:true});
	new Draggable('title',{revert:true});
	new Draggable('company',{revert:true})
	new Draggable('telephone',{revert:true});
	new Draggable('inquiry',{revert:true});
	new Draggable('custom_textbox',{revert:true});
	new Draggable('custom_textarea',{revert:true});
	new Draggable('custom_dropdown',{revert:true});	
	new Draggable('custom_checkbox',{revert:true});	
	new Draggable('custom_radiobutton',{revert:true});		

//debug:
	//alert(fieldrequired[1]+fieldrequired[2]);

Droppables.add(
 'thelist',
 {
    hoverclass: 'hoverActive',
	accept: 'selections',
    onDrop: moveItem
 }
);

function moveItem( draggable,droparea){
	//alert(draggable.identify);
   	//draggable.parentNode.removeChild(draggable);
	var newdiv = document.createElement('li');
  	newdiv.setAttribute('id', 'item_' + itemnum);
	newdiv.setAttribute('style','list-style-type: none');
	//divwidth = '60%';

	draglabelstring = '<table width="450px" border="0" cellpadding="0" cellspacing="0"><tr><td id="utext_' + itemnum + '" width="45%">' + draggable.innerHTML + '</td>';

	//determine type of field, text or something else, build proper string for element:
	if(draggable.identify()=='state') {
		fieldtype[itemnum] = 'SELECTSTATE';
	    dragfieldstring = '<select name="'+draggable.identify()+'" id="state_'+itemnum+'">'
	    +'<option value="AL">Alabama</option><option value="AK">Alaska</option><option value="AZ">Arizona</option><option value="AR">Arkansas</option><option value="CA">California</option><option value="CO">Colorado</option><option value="CT">Connecticut</option><option value="DE">Delaware</option><option value="DC">District Of Columbia</option><option value="FL">Florida</option><option value="GA">Georgia</option><option value="HI">Hawaii</option><option value="ID">Idaho</option><option value="IL">Illinois</option><option value="IN">Indiana</option><option value="IA">Iowa</option><option value="KS">Kansas</option><option value="KY">Kentucky</option><option value="LA">Louisiana</option><option value="ME">Maine</option><option value="MD">Maryland</option><option value="MA">Massachusetts</option><option value="MI">Michigan</option><option value="MN">Minnesota</option><option value="MS">Mississippi</option><option value="MO">Missouri</option><option value="MT">Montana</option><option value="NE">Nebraska</option><option value="NV">Nevada</option><option value="NH">New Hampshire</option><option value="NJ">New Jersey</option><option value="NM">New Mexico</option><option value="NY">New York</option><option value="NC">North Carolina</option><option value="ND">North Dakota</option><option value="OH">Ohio</option><option value="OK">Oklahoma</option><option value="OR">Oregon</option><option value="PA">Pennsylvania</option><option value="RI">Rhode Island</option><option value="SC">South Carolina</option><option value="SD">South Dakota</option><option value="TN">Tennessee</option><option value="TX">Texas</option><option value="UT">Utah</option><option value="VT">Vermont</option><option value="VA">Virginia</option><option value="WA">Washington</option><option value="WV">West Virginia</option><option value="WI">Wisconsin</option><option value="WY">Wyoming</option>'
	    +'</select>';
	}else if(draggable.identify()=='country') {
		fieldtype[itemnum] = 'SELECTCOUNTRY';
		dragfieldstring = '<select name="'+draggable.identify()+'" id="country_'+itemnum+'">'
		+'<option selected="selected" value="United States">United States</option><option value="United Kingdom">United Kingdom</option><option value="Afghanistan">Afghanistan</option><option value="Albania">Albania</option><option value="Algeria">Algeria</option><option value="American Samoa">American Samoa</option><option value="Andorra">Andorra</option><option value="Angola">Angola</option><option value="Anguilla">Anguilla</option><option value="Antarctica">Antarctica</option><option value="Antigua and Barbuda">Antigua and Barbuda</option><option value="Argentina">Argentina</option><option value="Armenia">Armenia</option><option value="Aruba">Aruba</option><option value="Australia">Australia</option><option value="Austria">Austria</option><option value="Azerbaijan">Azerbaijan</option><option value="Bahamas">Bahamas</option><option value="Bahrain">Bahrain</option><option value="Bangladesh">Bangladesh</option><option value="Barbados">Barbados</option><option value="Belarus">Belarus</option><option value="Belgium">Belgium</option><option value="Belize">Belize</option><option value="Benin">Benin</option>		<option value="Bermuda">Bermuda</option><option value="Bhutan">Bhutan</option><option value="Bolivia">Bolivia</option><option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option><option value="Botswana">Botswana</option><option value="Bouvet Island">Bouvet Island</option><option value="Brazil">Brazil</option><option value="British Indian Ocean Territory">Brit. Indian Ocean Terr</option><option value="Brunei Darussalam">Brunei Darussalam</option><option value="Bulgaria">Bulgaria</option><option value="Burkina Faso">Burkina Faso</option><option value="Burundi">Burundi</option><option value="Cambodia">Cambodia</option><option value="Cameroon">Cameroon</option><option value="Canada">Canada</option><option value="Cape Verde">Cape Verde</option><option value="Cayman Islands">Cayman Islands</option><option value="Central African Republic">Central African Republic</option><option value="Chad">Chad</option><option value="Chile">Chile</option>		<option value="China">China</option><option value="Christmas Island">Christmas Island</option><option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option><option value="Colombia">Colombia</option>		<option value="Comoros">Comoros</option><option value="Congo">Congo</option><option value="Congo, The Democratic Republic of The">Congo,Dem Rep of</option><option value="Cook Islands">Cook Islands</option>		<option value="Costa Rica">Costa Rica</option><option value="Cote Divoire">Cote Divoire</option><option value="Croatia">Croatia</option><option value="Cuba">Cuba</option><option value="Cyprus">Cyprus</option>	<option value="Czech Republic">Czech Republic</option><option value="Denmark">Denmark</option><option value="Djibouti">Djibouti</option><option value="Dominica">Dominica</option><option value="Dominican Republic">Dominican Republic</option><option value="Ecuador">Ecuador</option><option value="Egypt">Egypt</option>		<option value="El Salvador">El Salvador</option><option value="Equatorial Guinea">Equatorial Guinea</option>	<option value="Eritrea">Eritrea</option><option value="Estonia">Estonia</option><option value="Ethiopia">Ethiopia</option><option value="Falkland Islands (Malvinas)">Falkland Islands</option><option value="Faroe Islands">Faroe Islands</option><option value="Fiji">Fiji</option>		<option value="Finland">Finland</option><option value="France">France</option><option value="French Guiana">French Guiana</option><option value="French Polynesia">French Polynesia</option><option value="French Southern Territories">French Southern Terr.</option><option value="Gabon">Gabon</option><option value="Gambia">Gambia</option><option value="Georgia">Georgia</option><option value="Germany">Germany</option>		<option value="Ghana">Ghana</option><option value="Gibraltar">Gibraltar</option><option value="Greece">Greece</option><option value="Greenland">Greenland</option><option value="Grenada">Grenada</option><option value="Guadeloupe">Guadeloupe</option><option value="Guam">Guam</option>		<option value="Guatemala">Guatemala</option><option value="Guinea">Guinea</option><option value="Guinea-bissau">Guinea-bissau</option><option value="Guyana">Guyana</option><option value="Haiti">Haiti</option><option value="Heard Island and Mcdonald Islands">Heard Isl & Mcdonald Isl</option><option value="Holy See (Vatican City State)">Holy See(Vatican City)</option><option value="Honduras">Honduras</option><option value="Hong Kong">Hong Kong</option><option value="Hungary">Hungary</option><option value="Iceland">Iceland</option><option value="India">India</option>		<option value="Indonesia">Indonesia</option><option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option><option value="Iraq">Iraq</option><option value="Ireland">Ireland</option><option value="Israel">Israel</option><option value="Italy">Italy</option><option value="Jamaica">Jamaica</option>		<option value="Japan">Japan</option><option value="Jordan">Jordan</option><option value="Kazakhstan">Kazakhstan</option><option value="Kenya">Kenya</option><option value="Kiribati">Kiribati</option><option value="Korea, Democratic Peoples Republic of">Korea, Dem Ppls Rep</option><option value="Korea, Republic of">Korea, Republic of</option><option value="Kuwait">Kuwait</option><option value="Kyrgyzstan">Kyrgyzstan</option><option value="Lao Peoples Democratic Republic">Lao Peoples Dem Rep</option><option value="Latvia">Latvia</option><option value="Lebanon">Lebanon</option><option value="Lesotho">Lesotho</option><option value="Liberia">Liberia</option>		<option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option><option value="Liechtenstein">Liechtenstein</option><option value="Lithuania">Lithuania</option><option value="Luxembourg">Luxembourg</option><option value="Macao">Macao</option><option value="Macedonia, The Former Yugoslav Republic of">Macedonia, Former</option><option value="Madagascar">Madagascar</option><option value="Malawi">Malawi</option><option value="Malaysia">Malaysia</option><option value="Maldives">Maldives</option><option value="Mali">Mali</option>	<option value="Malta">Malta</option><option value="Marshall Islands">Marshall Islands</option><option value="Martinique">Martinique</option><option value="Mauritania">Mauritania</option><option value="Mauritius">Mauritius</option><option value="Mayotte">Mayotte</option><option value="Mexico">Mexico</option><option value="Micronesia, Federated States of">Micronesia, Fed States</option><option value="Moldova, Republic of">Moldova, Republic of</option><option value="Monaco">Monaco</option><option value="Mongolia">Mongolia</option><option value="Montserrat">Montserrat</option><option value="Morocco">Morocco</option><option value="Mozambique">Mozambique</option><option value="Myanmar">Myanmar</option><option value="Namibia">Namibia</option><option value="Nauru">Nauru</option><option value="Nepal">Nepal</option>	<option value="Netherlands">Netherlands</option><option value="Netherlands Antilles">Netherlands Antilles</option><option value="New Caledonia">New Caledonia</option><option value="New Zealand">New Zealand</option><option value="Nicaragua">Nicaragua</option><option value="Niger">Niger</option><option value="Nigeria">Nigeria</option><option value="Niue">Niue</option><option value="Norfolk Island">Norfolk Island</option><option value="Northern Mariana Islands">Northern Mariana Islands</option><option value="Norway">Norway</option><option value="Oman">Oman</option><option value="Pakistan">Pakistan</option>		<option value="Palau">Palau</option><option value="Palestinian Territory, Occupied">Palestinian Terr., Occ.</option><option value="Panama">Panama</option><option value="Papua New Guinea">Papua New Guinea</option>		<option value="Paraguay">Paraguay</option><option value="Peru">Peru</option><option value="Philippines">Philippines</option><option value="Pitcairn">Pitcairn</option><option value="Poland">Poland</option><option value="Portugal">Portugal</option><option value="Puerto Rico">Puerto Rico</option><option value="Qatar">Qatar</option><option value="Reunion">Reunion</option><option value="Romania">Romania</option><option value="Russian Federation">Russian Federation</option><option value="Rwanda">Rwanda</option><option value="Saint Helena">Saint Helena</option><option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option><option value="Saint Lucia">Saint Lucia</option><option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option><option value="Saint Vincent and The Grenadines">St Vincent & Gren</option><option value="Samoa">Samoa</option><option value="San Marino">San Marino</option>		<option value="Sao Tome and Principe">Sao Tome and Principe</option><option value="Saudi Arabia">Saudi Arabia</option><option value="Senegal">Senegal</option><option value="Serbia and Montenegro">Serbia and Montenegro</option><option value="Seychelles">Seychelles</option><option value="Sierra Leone">Sierra Leone</option><option value="Singapore">Singapore</option><option value="Slovakia">Slovakia</option>		<option value="Slovenia">Slovenia</option><option value="Solomon Islands">Solomon Islands</option>		<option value="Somalia">Somalia</option><option value="South Africa">South Africa</option><option value="South Georgia and The South Sandwich Islands">So. Georgia</option><option value="Spain">Spain</option><option value="Sri Lanka">Sri Lanka</option><option value="Sudan">Sudan</option>		<option value="Suriname">Suriname</option><option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>		<option value="Swaziland">Swaziland</option><option value="Sweden">Sweden</option><option value="Switzerland">Switzerland</option><option value="Syrian Arab Republic">Syrian Arab Republic</option>		<option value="Taiwan, Province of China">Taiwan, Province of China</option><option value="Tajikistan">Tajikistan</option><option value="Tanzania, United Republic of">Tanzania, United Rep.</option><option value="Thailand">Thailand</option><option value="Timor-leste">Timor-leste</option>		<option value="Togo">Togo</option><option value="Tokelau">Tokelau</option>		<option value="Tonga">Tonga</option><option value="Trinidad and Tobago">Trinidad and Tobago</option><option value="Tunisia">Tunisia</option><option value="Turkey">Turkey</option><option value="Turkmenistan">Turkmenistan</option><option value="Turks and Caicos Islands">Turks and Caicos Islands</option><option value="Tuvalu">Tuvalu</option><option value="Uganda">Uganda</option>		<option value="Ukraine">Ukraine</option><option value="United Arab Emirates">United Arab Emirates</option><option value="United Kingdom">United Kingdom</option><option value="United States">United States</option>		<option value="United States Minor Outlying Islands">U.S. Minor Outly Isl</option><option value="Uruguay">Uruguay</option><option value="Uzbekistan">Uzbekistan</option><option value="Vanuatu">Vanuatu</option><option value="Venezuela">Venezuela</option>		<option value="Viet Nam">Viet Nam</option><option value="Virgin Islands, British">Virgin Islands, British</option><option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option><option value="Wallis and Futuna">Wallis and Futuna</option>		<option value="Western Sahara">Western Sahara</option><option value="Yemen">Yemen</option><option value="Zambia">Zambia</option><option value="Zimbabwe">Zimbabwe</option>'
		+'</select>';
	}else if(draggable.identify()=='inquiry') {
		fieldtype[itemnum] = 'INQUIRY';
	    dragfieldstring = '<textarea name="'+draggable.identify() + '" id="text_' + itemnum 
	    + '" style="width:150;height:60"></textarea>';
	}else if(draggable.identify()=='user_text') {
		fieldtype[itemnum] = 'USERTEXT';
	    dragfieldstring = '';	
	}else if(draggable.identify()=='custom_textbox') {
		fieldtype[itemnum] = 'CUSTOMTEXTBOX';
	    dragfieldstring = '<input type="text" name="'+draggable.identify() + '" id="customtext_' + itemnum 
	    + '" style="width:150">';
	}else if(draggable.identify()=='custom_checkbox') {
		fieldtype[itemnum] = 'CUSTOMCHECKBOX';
	    dragfieldstring = '<div id="checkboxdiv_' + itemnum + '" style="display:inline;"><input type="checkbox" name="'+draggable.identify() + '" id="ccheckbox_' + itemnum + '" value="Check Me!" />Check Me!</div>&nbsp;&nbsp;';
	}else if(draggable.identify()=='custom_radiobutton') {
		fieldtype[itemnum] = 'CUSTOMRADIO';
	    dragfieldstring = '<div id="radiodiv_' + itemnum + '" style="display:inline;"><input type="radio" name="'+draggable.identify() + '" id="cradio_' + itemnum + '" value="Radio 1" />Radio 1</div>&nbsp;&nbsp;';
	}else if(draggable.identify()=='custom_textarea') {
		fieldtype[itemnum] = 'CUSTOMTEXTAREA';
	    dragfieldstring = '<textarea name="'+draggable.identify() + '" id="text_' + itemnum 
	    + '" style="width:150;height:60"></textarea>';
	}else if(draggable.identify()=='custom_dropdown') {
		fieldtype[itemnum] = 'CUSTOMDROPDOWN';
	    dragfieldstring = '<select name="'+draggable.identify() + '" id="cdropdown_' + itemnum 
	    + '"><option>Empty!</option></select>';
	}else if (draggable.identify()=='first_name') {
			fieldtype[itemnum] = 'TEXTFIRSTNAME';
		    dragfieldstring = '<input type="text" name="'+draggable.identify() + '" id="text_' + itemnum 
		    + '" style="width:150">';
	}else if (draggable.identify()=='last_name') {
			fieldtype[itemnum] = 'TEXTLASTNAME';
		  	dragfieldstring = '<input type="text" name="'+draggable.identify() + '" id="text_' + itemnum 
			+ '" style="width:150">';
	}else if (draggable.identify()=='email') {
			fieldtype[itemnum] = 'TEXTEMAIL';
			dragfieldstring = '<input type="text" name="'+draggable.identify() + '" id="text_' + itemnum 
			+ '" style="width:150">';
	}else if (draggable.identify()=='address') {
			fieldtype[itemnum] = 'TEXTADDRESS';
			dragfieldstring = '<input type="text" name="'+draggable.identify() + '" id="text_' + itemnum 
			+ '" style="width:150">';
	}else if (draggable.identify()=='city') {
			fieldtype[itemnum] = 'TEXTCITY';
			dragfieldstring = '<input type="text" name="'+draggable.identify() + '" id="text_' + itemnum 
			+ '" style="width:150">';
	}else if (draggable.identify()=='zip') {
			fieldtype[itemnum] = 'TEXTZIP';
			dragfieldstring = '<input type="text" name="'+draggable.identify() + '" id="text_' + itemnum 
			+ '" style="width:150">';
	}else if (draggable.identify()=='title') {
			fieldtype[itemnum] = 'TEXTTITLE';
			dragfieldstring = '<input type="text" name="'+draggable.identify() + '" id="text_' + itemnum 
			+ '" style="width:150">';
	}else if (draggable.identify()=='company') {
			fieldtype[itemnum] = 'TEXTCOMPANY';
			dragfieldstring = '<input type="text" name="'+draggable.identify() + '" id="text_' + itemnum 
			+ '" style="width:150">';
	}else if (draggable.identify()=='telephone') {
			fieldtype[itemnum] = 'TEXTTELEPHONE';
			dragfieldstring = '<input type="text" name="'+draggable.identify() + '" id="text_' + itemnum 
			+ '" style="width:150">';
	}else{
		fieldtype[itemnum] = 'TEXT';
	    dragfieldstring = '<input type="text" name="'+draggable.identify() + '" id="text_' + itemnum 
	    + '" style="width:150">';
	}

	newdiv.innerHTML = draglabelstring + '<td width="45%">' + dragfieldstring + '</td><td width="5%">' + '<img src="/images/delete.png" width="15" height="15" onclick="javascript: var d = document.getElementById(\'thelist\');var olddiv=document.getElementById(\'' + newdiv.id +'\'); d.removeChild(olddiv);"/>' + '</td><td width="5%">' + '<input type="radio" name="fieldprops" value="' + itemnum + '" valign="top" onclick="selectedfield=' + itemnum + ';showfieldprops(' + itemnum + ');" />' + '</td></tr></table><br />';

	fieldrequired[itemnum] = "No";
	fieldsize[itemnum] = 150;
	fieldmaxsize[itemnum] = 50;
	fieldrows[itemnum] = 60;
	fieldtext[itemnum] = '';
	fieldoptions[itemnum] = '';
	if(draggable.identify()=="user_text") fieldtext[itemnum] = 'User Text';
	if(draggable.identify()=="custom_dropdown") {
		fieldtext[itemnum] = "Custom Dropdown";
		fieldoptions[itemnum] = 'Empty! ';
	}
	if(draggable.identify()=="custom_checkbox") {
		fieldtext[itemnum] = "Custom CheckBox";		
		fieldoptions[itemnum] = 'Check Me!';
	}
	if(draggable.identify()=="custom_radiobutton") {
		fieldtext[itemnum] = "Custom Radio Button";
		fieldoptions[itemnum] = 'Radio 1';  
	}
	if(draggable.identify()=="custom_textarea") fieldtext[itemnum] = 'Custom Text Area';	
	if(draggable.identify()=="custom_textbox") fieldtext[itemnum] = 'Custom Text Box';	
	itemnum++;
  	droparea.appendChild(newdiv);

	//create new draggable:
	Sortable.destroy(droparea)
	Sortable.create(droparea, {constraint:false})
	
	//assert existing style (font size, color, type, etc) on new field:
	changefontsize();
	changefontcolor();
	//font type:
	var d = document.getElementById('fontFamily'); 
	var styles = {fontFamily: d.value}; 
	var x = document.getElementById('thelist'); 
	x.setStyle(styles);	
	//background color:
	var d = document.getElementById('bckcolor'); 
	var styles = {backgroundColor: d.value}; 
	var x = document.getElementById('thelist'); 
	x.setStyle(styles);
}

	showfieldprops = function(selectedfd) {
		Effect.SlideDown("fieldattributes");
		//From fieldrequired array, select Yes or No option for required:
		d = document.getElementById("required");
		//Visibility?:
		d.disabled=false;
		if(fieldtype[selectedfd].substr(0,4)!="TEXT" && fieldtype[selectedfd]!="INQUIRY" && fieldtype[selectedfd]!="CUSTOMTEXTBOX") d.disabled =true;
		if(fieldrequired[selectedfd]=='Yes') {
			d.options[1].selected = 'selected';
		}else{
			d.options[0].selected = 'selected';
		}
		//fieldsize array, select size for pulldown:
		s = document.getElementById("size");
		//Visibility?:
		s.disabled=false;
		if(fieldtype[selectedfd].substr(0,4)!="TEXT" && fieldtype[selectedfd]!="CUSTOMTEXTBOX") s.disabled=true;
		switch(fieldsize[selectedfd]) {
		case '50':
		  s.options[0].selected = 'selected';
		  break;
		case '100':
		  s.options[1].selected = 'selected';
		  break;
		case '150':
		  s.options[2].selected = 'selected';
		  break;
		case '200':
		  s.options[3].selected = 'selected';
		  break;
		default:
 		  s.options[2].selected = 'selected';
		}
		//from fieldmaxsize array, select default or set value:
		m = document.getElementById("maxsize");
		//Visibility?:
		m.disabled=false;
		if(fieldtype[selectedfd].substr(0,4)!="TEXT" && fieldtype[selectedfd]!="CUSTOMTEXTBOX") m.disabled =true;		
		switch(fieldmaxsize[selectedfd]) {
		case '10':
		    m.options[0].selected = 'selected';
		    break;
		case '30':
		    m.options[1].selected = 'selected';
		    break;
		case '50':
		    m.options[2].selected = 'selected';
		    break;
		case '70':
		    m.options[3].selected = 'selected';
		    break;
		case '90':
		    m.options[4].selected = 'selected';
		    break;
		default:
		    m.options[2].selected = 'selected';
		}
		//from fieldrows array, select default or set value:
		r = document.getElementById("numrows");
		//Visibility of Number of Rows Input?:
		r.disabled=false;
		if(fieldtype[selectedfd]!="INQUIRY" && fieldtype[selectedfd]!="CUSTOMTEXTAREA") r.disabled =true;		
		
		//Visibility of Label input?:
		l = document.getElementById("labeltext");		
		l.disabled=false;
		if(fieldtype[selectedfd]!="USERTEXT" && fieldtype[selectedfd]!="CUSTOMTEXTBOX" && fieldtype[selectedfd]!="CUSTOMTEXTAREA" && fieldtype[selectedfd]!="CUSTOMDROPDOWN" &&  fieldtype[selectedfd]!="CUSTOMCHECKBOX" && fieldtype[selectedfd]!="CUSTOMRADIO" && selectedfd!="0") l.disabled = true;
		l.value = fieldtext[selectedfd];
				
		//Visibility of User Options Input?:
		uo = document.getElementById("useroptions");		
		uo.disabled=false;
		if(fieldtype[selectedfd]!="CUSTOMDROPDOWN" && fieldtype[selectedfd]!="CUSTOMCHECKBOX" && fieldtype[selectedfd]!="CUSTOMRADIO") uo.disabled = true;
		uo.value = fieldoptions[selectedfd];
				//alert(selectedfd);
	}
	changefontsize = function() {
		var d = document.getElementById('fontsize'); 
		var styles = {fontSize: d.value}; 
		for (x in fieldrequired) {
			var x = document.getElementById('utext_'+x); 
			if(x) x.setStyle(styles);
		}
	}
	
	changefontcolor = function() {
		var d = document.getElementById('color'); 
		var styles = {color: d.value}; 
		for (x in fieldrequired) {
			var x = document.getElementById('utext_'+x); 
			if(x) x.setStyle(styles);		
		}		
	}
	
	itemize = function() {
		//determine sorted order of form, work through each to get details:
		var sortedliststring = Sortable.sequence('thelist').join(',');
		//Put in array for itemizing of other attributes:
		var sortedlistarray = sortedliststring.split(',');
		var displaytype = ''; i = 0;
		//initialize strings for use in posting to saveform.php:
		order = ''; type = ''; req = ''; sz = ''; maxsz = ''; rows = ''; txt = ''; opt = '';
		for each (x in sortedlistarray) {
			order += x;
			type += fieldtype[x];
			req += fieldrequired[x];
			sz += fieldsize[x];
			maxsz += fieldmaxsize[x];
			rows += fieldrows[x];
			txt += fieldtext[x];
			opt += fieldoptions[x].replace(/,/g,"|");
			
			i++;
			if(i==sortedlistarray.length) break;
			
			//add comma if this isn't the last entry:
			order += ','; type += ','; req += ','; sz += ','; maxsz += ','; rows += ',';
			txt += ','; opt += ',';
		}
		//general form attributes:
		formfontsize = ''; formfonttype = ''; formfontcolor = ''; formbackgrnd = '';
		var d = document.getElementById('fontsize'); 
		if(d) formfontsize = d.value;
		var d = document.getElementById('fontFamily'); 
		if(d) formfonttype = d.value;
		var d = document.getElementById('color'); 
		if(d) formfontcolor = d.value;
		var d = document.getElementById('bckcolor'); 
		if(d) formbackgrnd = d.value;
		var d = document.getElementById('formname');
		if(d) formname = d.value;
		submitbutton = fieldtext[0];
		
		//Build formdata string from form's attributes/inputs, send to page for processing:
		formdata = 'name=' + formname + '&fs=' + formfontsize+'&fF=' + formfonttype + '&fc=' + formfontcolor + '&fb=' + formbackgrnd + '&button=' + submitbutton;
		if(formname.length==0) {
		    alert('Please Give the Form a Name!');
		}else{
		    location.href="saveform.php?" + formdata+'&order='+order+'&type='+type+'&req='+req + '&sz='+sz+'&maxsz='+maxsz+'&rows='+rows+'&txt='+txt+'&opt='+opt;
		}
	}
</script>
</body></html>