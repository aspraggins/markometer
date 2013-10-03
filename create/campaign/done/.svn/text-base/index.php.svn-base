<?php # Campaign Success/Done Page
$metatitle = 'Your New Marketing Campaign Has Been Created';
$returnurl = '/login/?returnpage=create/campaign/prestep1.php';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Heading and Help Button -->

<div class="heading">
<h1>Your New Marketing Campaign Has Been Created
<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/current-account.php'); ?>
</h1>
</div>

<!-- Main Content -->

<?php
//Database:
require_once(MYSQL);

//Handle Submission if choosing an existing action page:
if (isset($_POST['submitted'])) {
	if(isset($_POST['chooseaction'])) {
		if(strlen(trim($_POST['chooseaction']))>0) {
			$chooseaction = trim($_POST['chooseaction']);
			
			//update campaign record to include this chosen action page:
			$q = "UPDATE campaigns " .
			"SET updateflag=1-updateflag, action_page='$chooseaction' " . 
			"WHERE id='{$_SESSION['campaignid']}' LIMIT 1"; 
			
			$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
			. mysqli_error($dbc));

			//Check success of campaigns table update:
			if (mysqli_affected_rows($dbc) == 1) {
				// Success! Move to actionpage done page, with action page chosen message:
				// $url = '/create/actionpage/done/?action=pagechosen&actionid=' . $chooseaction;
				$url = '/manage/campaigns/?action=pagechosen';
				header("Location: $url");
			} else {
				// If it did not run okay
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>This action page could not be chosen due to a system	error. We apologize for any inconvenience.</p>
			</div>
		</div>
	</div>
</div>';
			}
		}
	}
}

?>

<div class="main-container">

<div class="text-holder">
<p><strong>Congratulations!</strong> You've successfully created a new marketing campaign called <strong><?php echo $_SESSION['campaign_name']?></strong> that will <strong>start on <?php echo date('F j, Y', strtotime($_SESSION['campaign_begin'])); ?></strong> and <strong><?php if(strlen($_SESSION['campaign_end']!='Open')) {echo ' end on ' . date('F j, Y', strtotime($_SESSION['campaign_end'])) . '';} else{echo 'run indefinitely';} ?></strong>.</p>
<p>Use these tool(s) to measure your marketing campaign. Make these your only calls to action in your ad or you may not track your results accurately. We suggest you copy and paste them into the ad so you don't accidentally mistype them. You can also see and copy these tools at any time from the Manage Campaigns page under the Manage tab.</p>
</div>

<div class="main-items">
		
<div class="column">
<div class="column-box">

<h2>Measurement Tools</h2>
<ul class="list">
<?php
// display domain info if relevant:
if($_SESSION['campaign_domain']!='nodomain') {
echo '
<li>
';
	if($_SESSION['campaign_domain']!='actionpagelink') { // registered domain name:
echo '
<strong>Unique Domain Name To Use:</strong>
';
	} else { // if PPC action page link:
echo '
<strong>Action Page Link to Use:</strong>
';
	}
echo '
<div class="list-holder">
	<div class="list-frame">
		<div class="list-wrap">
			<span>
';
echo $_SESSION['campaign_domain'] ;
echo
'
			</span>
		</div>
	</div>
</div>
</li>
';

}
?>

<?php
// display phone info if relevant:
if($_SESSION['campaign_pnumber']!='nophone') {
echo '
<li class="add">
<strong>Unique Phone Number To Use:</strong>
	<div class="list-holder">
		<div class="list-frame">
			<div class="list-wrap">
				<span>';
echo $_SESSION['campaign_pnumber'] . '</span>
			</div>
		</div>
	</div>
</li>
';
}
?>
</ul>

<h3>See tips below on how to implement</h3>

</div>
</div>


<div class="column">
<div class="column-box">

<?php
// Show Action Page Links?

echo '
<h2>Next Steps: Action Page</h2>
<p>Now you need to choose an existing action page or create a new action page to use with this marketing campaign.</p>
<h4>Choose an Existing Action Page:</h4>
';

// query for available action pages for this account:
$q = "SELECT id, name " . 
"FROM action_pages " . 
"WHERE account_id='{$_SESSION['account_id']}' AND id>5 ORDER BY name";

$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

// if action pages available, display in scrolling table:
if (mysqli_num_rows($r) > 0) {
echo '
<form action="" method="post" class="select-form" name="actionpagechooser" id="choose_actionpage_campaign">
<fieldset>
		<select name="chooseaction" id="select-steps-1" size="4">
';
while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
}
echo '</select>';
}else{
	echo '<h4>No Action Pages Found</h4>';
}


if (mysqli_num_rows($r) > 0) {
	echo '
<input type="hidden" name="submitted" value="TRUE" />

<div class="content-link">
	<div class="link-holder">
		<div class="link-frame">
			<a href="#" class="btn" onclick="document.getElementById(\'choose_actionpage_campaign\').submit();" /><span>Choose This Action Page &gt;</span></a>
		</div>
	</div>
</div>

</fieldset>
</form>
';
} 

echo '
<h4>Or Create a New Action Page:</h4>

<div class="content-link">
	<div class="link-holder">
		<div class="link-frame">
			<a class="btn" href="/create/actionpage/choose/?camp=';
			if(isset($_SESSION['campaignid'])) echo $_SESSION['campaignid'];
echo '
			"><span>Create a New Action Page &gt;</span></a>
		</div>
	</div>
</div>
';

?>

</div>
</div>

</div>

<h2>How To Use Your Measurement Tools:</h2>

<div class="main-items">
	
<div class="column">
<div class="text-holder">
<p><strong>For print and traditional ads</strong> just put your unique domain and/or phone number in the ad <strong>(shown in red below)</strong> as the <strong>only</strong> call(s) to action. This will ensure we measure 100% of your leads:</p>
</div>
<img src="/images/example-print.png" alt="Traditional Ads Example" width="424" height="270" />
</div>

<div class="column">
<div class="text-holder">
<p><strong>For online pay-per-click ads</strong> you'll just use the Display URL as the link they see in the ad <strong>(shown in green below)</strong> and you'll use the Destination URL as the link the ad actually goes to:</p>
</div>
<img src="/images/example-ppc.png" alt="Pay-Per-Click Ads Example" width="424" height="229" />
</div>

</div>

</div>

<!-- / Main Content -->

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
