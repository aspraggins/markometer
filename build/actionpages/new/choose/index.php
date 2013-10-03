<?php # Create New Action Page Chooser
$metatitle = 'Choose a New Action Page to Create';
$returnurl = '/login/?returnpage=build/actionpages/new/chooses';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Heading and Help Button -->

<div class="heading">
<h1>Choose a New Action Page to Create
<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/current-account.php'); ?>
</h1>
<?php # Help Button
$helphref = 'build-action-pages-choose.html';
$helptitle = 'Help with Choosing an Action Page to Create';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
?>
</div>

<!-- Main Content -->

<?php

// Database:
require_once(MYSQL);

if (isset($_POST['submitprebuilt'])) {
	if(isset($_POST['chooseprebuilt'])) {
		// Move to next page in campaign startup:
		$chosen = trim($_POST['chooseprebuilt']);
		$url = '/build/actionpages/new/create/?action=prebuilt&id=' . $chosen;
		if(isset($_POST['campaignseq'])) {
			$url .= '&camp=' . $_POST['campaignseq'];
		}
		header("Location: $url");
		exit();
	}else{
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>Please select a pre-built template from the chooser before clicking the Choose &amp; Edit button!</p>
			</div>
		</div>
	</div>
</div>';
	}
}
if (isset($_POST['submitexisting'])) {
	if(isset($_POST['chooseexisting'])) {
		// Move to next page in campaign startup:
		$chosen = trim($_POST['chooseexisting']);
		$url = '/build/actionpages/new/create/?action=existing&id=' . $chosen;
		if(isset($_POST['campaignseq'])) {
			$url .= '&camp=' . $_POST['campaignseq'];
		}
		header("Location: $url");
		exit();
	}else{
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>Please select an existing page from the chooser before clicking the Choose &amp; Edit button!</p>
			</div>
		</div>
	</div>
</div>';
	}
}

// Evaluate whether or not camp variable was passed to this page, 
// indicating that this is part of a campaign creation sequence:
$campaignid = FALSE;
if(isset($_REQUEST['camp'])) {
	$campaignid = $_REQUEST['camp'];
}
?>

<p class="head-caption">If these options don't tickle your fancy we'll be glad to design you one for a one-time charge. <a href="/modals/design-action-page-template.php" title="Our Action Page Design Services" onclick="Modalbox.show(this.href, {title: this.title, width: 600, evalScript: true}); return false;">Tell me more &gt;</a></p>

<div class="main-container choose-page">
<div class="main">
<div class="main-holder">
<div class="main-frame">

<!-- Pre-Built Template Box -->

<div class="main-item">
	<h2>Pre-Built Templates</h2>
	<p>Create an Action Page from Our Pre-Built Library of Action Pages.</p>

<form name="submitPrebuilt" id="choose_prebuilt_page" action="" method="post" class="select-form">
<fieldset>
	
<?php
//query for available action pages for this account:
$q = "SELECT id, name " . 
"FROM action_pages " . 
"WHERE id<4 ORDER BY id";
	
$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

//if action pages available, display in scrolling table:
if (mysqli_num_rows($r) > 0) {
echo '
<select name="chooseprebuilt" id="select-steps-1" size="5">
';
	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
	}
echo '
</select>
<input type="hidden" name="submitprebuilt" value="TRUE" /> 
';

if($campaignid) {
	echo '<input type="hidden" name="campaignseq" value="' . $campaignid . '" />';
}

echo '
<div class="content-link">
	<div class="link-holder">
		<div class="link-frame">
			<a class="btn" href="#" onclick="document.getElementById(\'choose_prebuilt_page\').submit(); return false;" /><span>Choose &amp; Edit &gt;</span></a>
		</div>
	</div>
</div>

<div class="content-link">
	<div class="link-holder">
		<div class="link-frame">
			<em>or</em>
			<a href="/build/actionpages/">Cancel</a>
		</div>
	</div>
</div>
';

}else{
	echo '
<h3>No Pre-Built<br/>Templates Found</h3>
';
}
?>
	
</fieldset>
</form>
</div>

<!-- / Pre-Built Template Box -->

<!-- Your Existing Pages Box -->

<div class="main-item">
	<h2>Your Existing Pages</h2>
	<p>Create a Copy of an Action Page You've Already Created.</p>

<form name="submitExisting" id="choose_existing_page" action="" method="post" class="select-form">
<fieldset>
			
<?php
//query for available action pages for this account:
$q = "SELECT id, name " . 
"FROM action_pages " . 
"WHERE account_id='{$_SESSION['account_id']}' " . 
"AND id>20 " . 
"ORDER BY name";
	
$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

//if action pages available, display in scrolling table:
if (mysqli_num_rows($r) > 0) {
	echo '<select name="chooseexisting" id="select-steps-2" size="5">';
	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
	}
	echo '
</select>
<input type="hidden" name="submitexisting" value="TRUE" /> 
';

if($campaignid) {
	echo '<input type="hidden" name="campaignseq" value="' . $campaignid . '" />';
}

echo '
<div class="content-link">
	<div class="link-holder">
		<div class="link-frame">
			<a class="btn" href="#" onclick="document.getElementById(\'choose_existing_page\').submit(); return false;" /><span>Choose &amp; Edit &gt;</span></a>
		</div>
	</div>
</div>

<div class="content-link">
	<div class="link-holder">
		<div class="link-frame">
			<em>or</em>
			<a href="/build/actionpages/">Cancel</a>
		</div>
	</div>
</div>
';

}else{
	echo '
<h3>No Existing<br/>Action Pages Found</h3>
<p>Once you create your first one (you\'re doing that right now) we\'ll show you your existing action pages here so you choose one to make a copy of next time.</p>
';
}
?>

</fieldset>
</form>
</div>

<!-- / Your Existing Pages Box -->

<!-- Create From Scratch Box -->

<div class="main-item">
	<h2>Create From Scratch</h2>
	<p>A Blank Canvas to Create Your Action Page Masterpiece from Scratch.</p>

<div class="content-link">
	<div class="link-holder">
		<div class="link-frame">
			<a class="btn" href="/build/actionpages/new/create/?action=scratch<?php if($campaignid) echo '&camp=' . $campaignid; ?>"><span>Choose &amp; Edit &gt;</span></a>
		</div>
	</div>
</div>

<div class="content-link">
	<div class="link-holder">
		<div class="link-frame">
			<em>or</em>
			<a href="/build/actionpages/">Cancel</a>
		</div>
	</div>
</div>

</div>

<!-- / Create From Scratch Box -->

</div>
</div>
</div>
</div>

<!-- / Main Content -->

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
