<?php # Create New Campaign Step 2
$metatitle = 'Create a New Marketing Campaign - Step 2 of 3';
$returnurl = '/login/?returnpage=build/campaigns/new';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Heading and Help Button -->

<div class="heading">
<h1>Create a New Marketing Campaign - Step 2/3
<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/current-account.php'); ?>
</h1>
</div>

<!-- Main Content -->

<?php
if (isset($_POST['submitted'])) {
	//save off to session variables for persistence:
	$_SESSION['needdomain'] = $_POST['needdomain'];
	$_SESSION['needphone'] = $_POST['needphone'];
	$_SESSION['phonetype'] = $_POST['phonetype'];
	
	//Check to see if they selected Yes for a domain or a phone:
	$optionselected = FALSE;
	if($_POST['needdomain']=='N' && $_POST['needphone']=='N') {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>You\'ll need either a domain or a phone number (or both) to launch your campaign (This is how you\'ll get leads).</p>
			</div>
		</div>
	</div>
</div>';
	} else {
		$optionselected = TRUE;
	}
	if($optionselected) {
		//Database:
		require_once(MYSQL);
      
		//Assume invalid values:
		$domainname = FALSE;

		//process domain name:
		if(isset($_POST['domainnametext'])) { //non-PPC:
			if($_POST['needdomain']=='Y') {
				//Check for a valid domain name: (regular expressions)
				if (preg_match ("/^[a-z0-9][a-z0-9\-]+[a-z0-9](\.[a-z]{2,4})+$/i", trim($_POST['domainnametext']))) {
					$_SESSION['campaign_domain'] = mysqli_real_escape_string($dbc, trim($_POST['domainnametext']));
					$domainname = TRUE;
					//Code here will have to use API with domain partner to confirm availability.
				} else {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Please enter or select a valid domain name.</p>
			</div>
		</div>
	</div>
</div>';
				}
			} else {
				$domainname = TRUE;
				$_SESSION['campaign_domain'] = 'nodomain';
			}
		} else { //pay per click, handle action page link:
			$domainname = TRUE;
			if($_POST['needdomain']=='Y') {
				$_SESSION['campaign_domain'] = 'actionpagelink';
			} else {
				$_SESSION['campaign_domain'] = 'nodomain';
			}
		}
		//process phone number:
		if($_POST['needphone']=='Y') {
			if($_POST['phonetype']=='local') { //code with api partner will be in here
				$_SESSION['campaign_pnumber'] = '704-249-5555';
			} else {
				$_SESSION['campaign_pnumber'] = '866-439-5555';
			}
		} else {
			$_SESSION['campaign_pnumber'] = 'nophone';
		}
		
		if($domainname) { //validation for domain field, move forward to step 3:
			$url = '/build/campaigns/new/step3';
			header("Location: $url");
		}
	}
}
?>

<div class="content-items content-steps">
	
<?php 
//Test to see if this is lead importation:
if($_SESSION['leadimport']=='N') { //is this a file importation?
	if($_SESSION['campaign_type']!=6) { //(NOT PPC)
		//set variables for display of page for action page domains and links:
		$actionpagelabel = 'Action Page Domain Name';
		$actionpagechooseyes = 'Yes, I Need a Unique Domain Name';
		$actionpagechooseno = 'No, I Don\'t Need a Domain Name';
		$actionpageie = '(i.e. MarketingSolutions.com, ProductBenefit.com or CompanyProduct.com)';
		$helphref = 'build-campaigns-domain-name.html';
		$helptitle = 'Help with Domain Names';
	} else { //(IS PPC)
		$actionpagelabel = 'Action Page Links';
		$actionpagechooseyes = 'Yes, I Need To Use Action Page Links';
		$actionpagechooseno = 'No, I Don\'t Need To Use Links';
		$actionpageie = '';
		$helphref = 'build-campaigns-ppc.html';
		$helptitle = 'Help with Action Page Links for PPC';
	}
	
// Display the Results:

echo '
<form action="#" method="post" id="new_campaign_step2" name="campform" class="steps-form">
<fieldset>
	
<div class="row">
	<span class="num"><em>7</em></span>
	<div class="steps-holder">
		<div class="heading">
			<h2>' . $actionpagelabel . '</h2>
';
			# Help Button
			include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
			
echo '			
		</div>
		<div class="choose-bar">
			<span>Choose One:</span>
			<input type="radio" name="needdomain" id="radio-1" value="Y" checked="yes" class="radio" />
			<label for="radio-1">' . $actionpagechooseyes . '</label>
			<input type="radio" name="needdomain" value="N" id="radio-2" class="radio" ';
			
			if (isset($_SESSION['needdomain'])) {
				if ($_SESSION['needdomain']=='N') echo 'checked="yes"';
			}
			echo '
			 />
			<label for="radio-2" class="add">' . $actionpagechooseno . '</label>
		</div>
';

	if($_SESSION['campaign_type']!=6) { // (NOT PPC)
echo '
		<input type="text" maxlength="75" class="text long" id="domainnametext" name="domainnametext" value="'; 
		if(isset($_SESSION['campaign_domain'])) echo $_SESSION['campaign_domain'];
echo '" />
		<span class="caption">(i.e. MarketingSolutions.com, ProductBenefit.com or CompanyProduct.com)</span>
';

	} else { // (IS PPC)
echo '
		<div class="text large">
			<div class="text-bar">
				<div class="text-col-1"><strong>Display URL <span>(to show in ad):</span></strong></div>
				<div class="text-col-2"><strong>' . $_SESSION['mp_subdomain'] . '.mp41.com</strong></div>
			</div>
			<div class="text-bar">
				<div class="text-col-1"><strong>Destination URL <span>(to link to from ad):</span></strong></div>
';
		$name = $_SESSION['campaign_name'];
		$special_chars = array("?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}", chr(0));
		$name = str_replace($special_chars, '', $name);
		$name = preg_replace('/[\s-]+/', '-', $name);
		$name = trim($name, '.-_');		
echo '
				<div class="text-col-2"><strong>' . $_SESSION['mp_subdomain'] . '.mp41.com/' . $name .'</strong></div>
			</div>
		</div>
		
';
		$_SESSION['campaign_name_dashes'] = $name;
	}
	
echo '
	</div>
</div>
';

// Display Phone Number and Buttons Stuff:

echo '
<div class="row">
	<span class="num"><em>8</em></span>
	<div class="steps-holder">
		<div class="heading">
			<h2>Phone Number</h2>
';
			# Help Button
			$helphref = 'build-campaigns-phone-number.html';
			$helptitle = 'Help with Phone Numbers';
			include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
echo '			
		</div>
		<div class="choose-bar">
			<span>Choose One:</span>
			<input type="radio" name="needphone" id="radio-3" value="Y" checked="yes" class="radio" />
			<label for="radio-3">Yes, I Need a Unique Phone Number</label>
			<input type="radio" name="needphone" id="radio-4" value="N" class="radio" '; 
			
			if (isset($_SESSION['needphone'])) {
				if ($_SESSION['needphone']=='N') echo 'checked="yes"';
			}
			echo '
			/>
			<label for="radio-4" class="add">No, I Don\'t Need a Phone Number</label>
		</div>
		<div class="text text-choose long">
			<div class="text-col-1">
				<input type="radio" name="phonetype" id="radio-5" value="local" checked="yes" class="radio" />
				<label for="radio-5">Local: 704-944-5346</label>
			</div>
			<div class="text-col-2">
				<input type="radio" name="phonetype" id="radio-6" value="tollfree" class="radio" ';

			if (isset($_SESSION['phonetype'])) {
				if ($_SESSION['phonetype']=='tollfree') echo 'checked="yes"';
			}
			echo '
				/>
				<label for="radio-6">Toll-Free: 1-800-551-8900</label>
			</div>
		</div>
	</div>
</div>

<div class="content-link">
	<div class="link-holder">
		<div class="link-frame">
			<a class="btn btn-left" href="/build/campaigns/new/step1/"><span>&lt; Previous Step</span></a>
			<a href="#" class="btn" onclick="document.getElementById(\'new_campaign_step2\').submit();" /><span>Save &amp; Go To Next Step &gt;</span></a>
			<em>or</em>
			<a href="/build/campaigns/new/cancel/">Cancel</a>
		</div>
	</div>
</div>

<input type="hidden" name="submitted" value="TRUE" /> 
	
</fieldset>
</form>

';
	
} else {
	// One Time File Import:
	$file_open = 0;
echo'
<form enctype="multipart/form-data" action="/build/campaigns/new/step2/map/" method="post" id="new_campaign_step2_upload" class="steps-form">
<fieldset>
	
<div class="row">
	<span class="num"><em>7</em></span>
	<div class="steps-holder">
		<div class="heading">
			<h2>Select a .CSV File on Your Computer to Upload</h2>
';
			# Help Button
			$helphref = 'build-campaigns-import-file-select.html';
			$helptitle = 'Help with Selecting a File to Import';
			include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
echo '
		</div>
		<div class="text-bar">
			<input name="csvfile" type="file" class="text small" />
		</div>
		<span class="caption">(You can create a .CSV file using a program like Excel or you can <a href="#">download a sample file here</a> and fill in your own information)</span>
	</div>
</div>

<div class="content-link">
	<div class="link-holder">
		<div class="link-frame">
			<a class="btn btn-left" href="/build/campaigns/new/step1/"><span>&lt; Previous Step</span></a>
			<a href="#" class="btn" onclick="document.getElementById(\'new_campaign_step2_upload\').submit();" /><span>Upload Leads &amp; Go To Next Step &gt;</span></a>
			<em>or</em>
			<a href="/build/campaigns/new/cancel/">Cancel</a>
		</div>
	</div>
</div>

<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
<input type="hidden" name="option" value="yes" /> 

</fieldset>
</form>
';
// exit();	
}
?>

</div>

<div class="alert-holder">
	<div class="alert-box yellow">
		<div class="holder">
			<div class="frame">
				<p>Don't worry - you'll have a chance to review your campaign before you finalize it.</p>
			</div>
		</div>
	</div>
</div>

<br/><br/>

<!-- / Main Content -->

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
