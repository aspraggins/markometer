<?php # Import Individual Leads One-At-A-Time Into Campaign
$metatitle = 'Import a Lead Into Your Existing Campaign';
$returnurl = '/login/?returnpage=build/campaigns';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Heading and Help Button -->

<div class="heading">
<h1>Import a Lead Into Your Existing Campaign
<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/current-account.php'); ?>
</h1>
<?php # Help Button
$helphref = 'build-campaigns-import-one.html';
$helptitle = 'Help with Importing Leads One-At-A-Time';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
?>
</div>

<!-- Main Content -->

<?php
if (isset($_POST['submitted']))
{
    // Handle the form
    require_once(MYSQL);
  
    // Trim all of the incoming data:
    $trimmed = array_map('trim', $_POST);
  
    // Assume invalid values:
    $fn = $ln = FALSE;
	$e = $p = $a = $city = $state = $zip = $country = '';
  
    // Check for a first name:
    if(preg_match('/^[A-Z \'.-]{2,80}$/i', $trimmed['first'])) {
        $fn = mysqli_real_escape_string($dbc, $trimmed['first']);
    } else {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Please enter a valid first name!</p>
			</div>
		</div>
	</div>
</div>';
    }

    // Check for a last name:
    if(preg_match('/^[A-Z \'.-]{2,80}$/i', $trimmed['last'])) {
		$ln = mysqli_real_escape_string($dbc, $trimmed['last']);
    } else {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Please enter a valid last name!</p>
			</div>
		</div>
	</div>
</div>';
    }
	
	// Handle unrequired fields:
	$t = mysqli_real_escape_string($dbc, $trimmed['title']);
	$c = mysqli_real_escape_string($dbc, $trimmed['company']);
	$p = mysqli_real_escape_string($dbc, $trimmed['phone']);
	$e = mysqli_real_escape_string($dbc, $trimmed['email']);
	$a = mysqli_real_escape_string($dbc, $trimmed['address']);	
	$city = mysqli_real_escape_string($dbc, $trimmed['city']);
	$state = mysqli_real_escape_string($dbc, $trimmed['state']);	
	$zip = mysqli_real_escape_string($dbc, $trimmed['zip']);	
	$country = mysqli_real_escape_string($dbc, $trimmed['country']);	
	
	if($fn && $ln) {
		$campaign_id = $_GET['id'];
		
		//delete currently marked "last_imported" lead flags for this campaign:
		$q = "UPDATE leads " . 
		"SET last_imported='N'" . 
		"WHERE campaign_id='$campaign_id'";

		$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
		. mysqli_error($dbc));
		
		// build insert string for writing this lead off to table:
		$q = "INSERT INTO leads (account_id, campaign_id, contact_first, contact_last, title, company, telephone, " . 
		"email, address, city, state, zip, country,status,date_created,last_updated,last_imported) " . 
		"VALUES ('{$_SESSION['account_id']}','$campaign_id', '$fn', '$ln', '$t', '$c', '$p', '$e',  '$a'," .
		"'$city', '$state', '$zip', '$country', 'N', NOW(), NOW(),'Y')";

		// Insert into table:
		$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
		. mysqli_error($dbc));
	
		// Success?:
		if (mysqli_affected_rows ($dbc) == 1) {
			// Move to manage campaigns overview:
			$url = '/build/campaigns/edit?id=' . $campaign_id . '&saved=one';
			header("Location: $url");
			exit();
		}else{
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Sorry, there was a problem adding that lead. Please try again!</p>
			</div>
		</div>
	</div>
</div>';
		}
	}
}

?>

<form id="add_leadinfo" action="" method="post" name="leadform" class="contact-items-form">
<fieldset>

<div class="contact-items">

<div class="row">
	<label class="title">First Name:</label>
	<input tabindex="1" type="text" class="text middle" name="first" maxlength="80" value="<?php if (isset($_POST['first'])) echo $_POST['first']; ?>" />
	<label>Last Name:</label>
	<input tabindex="2" type="text" class="text edit" name="last" maxlength="80" value="<?php if (isset($_POST['last'])) echo $_POST['last']; ?>" />
</div>
<div class="row">
	<label class="title">Title:</label>
	<input tabindex="3" type="text" class="text" name="title" maxlength="80" value="<?php if (isset($_POST['title'])) echo $_POST['title']; ?>" />
</div>
<div class="row">
	<label class="title">Company:</label>
	<input tabindex="4" type="text" class="text" name="company" maxlength="80" value="<?php if (isset($_POST['company'])) echo $_POST['company']; ?>" />
</div>
<div class="row">
	<label class="title">Phone:</label>
	<input tabindex="5" type="text" class="text" name="phone" maxlength="80" value="<?php if (isset($_POST['phone'])) echo $_POST['phone']; ?>" />
</div>
<div class="row">
	<label class="title">Email:</label>
	<input tabindex="6" type="text" class="text" name="email" maxlength="80" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" />
</div>
<div class="row">
	<label class="title">Address:</label>
	<textarea tabindex="7" rows="10" cols="30" id="area" name="address"><?php if (isset($_POST['address'])) echo $_POST['address']; ?></textarea>
</div>
<div class="row">
	<label class="title">City:</label>
	<input tabindex="8" type="text" class="text" name="city" maxlength="80" value="<?php if (isset($_POST['city'])) echo $_POST['city']; ?>" />
</div>
<div class="row">
	<label class="title">State:</label>
	<input tabindex="9" type="text" class="text" name="state" maxlength="80" value="<?php if (isset($_POST['state'])) echo $_POST['state']; ?>" />
</div>
<div class="row">
	<label class="title">Zip/Postal Code:</label>
	<input tabindex="10" type="text" class="text" name="zip" maxlength="80" value="<?php if (isset($_POST['zip'])) echo $_POST['zip']; ?>" />
</div>
<div class="row">
	<label class="title">Country:</label>
	<input tabindex="11" type="text" class="text" name="country" maxlength="80" value="<?php if (isset($_POST['country'])) echo $_POST['country']; ?>" />
</div>

	<div class="content-link">
		<div class="link-holder">
			<div class="link-frame">
			<a href="#" class="btn" onclick="document.getElementById('add_leadinfo').submit()" /><span>Import This Lead &gt;</span></a>
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
