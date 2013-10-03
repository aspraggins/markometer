<?php # Create a New Action Page
include_once ($_SERVER['DOCUMENT_ROOT'] . '/inc/WPro3_CORE_v3.2.1/wysiwygPro/wysiwygPro.class.php');
$metatitle = 'Create a New Action Page';
$returnurl = '/login/?returnpage=build/actionpages/new/choose';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Heading and Help Button -->

<div class="heading">
<h1>Create a New Action Page
<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/current-account.php'); ?>
</h1>
</div>

<!-- Main Content -->

<?php
// Handle Submission:
if (isset($_POST['submitted']))
{
	// MySQL setup:
	require_once(MYSQL);

	// Set up variableS:
	$namecheck = $actionpage = $thankyou = $name = $redirectlink = $formselected = FALSE;
	
	// Check for a name:
	if(preg_match('/^[A-Z0-9 \'.-]{2,60}$/i', trim($_POST['actionname']))) {
		$name = mysqli_real_escape_string($dbc, trim($_POST['actionname']));
	} else {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>Please enter a valid Action Page Name - it can\'t be blank!</p>
			</div>
		</div>
	</div>
</div>';
	}

	//SQL hit to recheck availability of name:
	$q = "SELECT * FROM action_pages WHERE name='$name' AND account_id={$_SESSION['account_id']}";

	$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
	Error: " . mysqli_error($dbc));

	$num = mysqli_num_rows($r);

	if($num!=0) {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>The Action Page Name ""' . $name . '" is taken, please try another.</p>
			</div>
		</div>
	</div>
</div>';
	} else {
		$namecheck = TRUE;
	}

	if (strlen($_POST['actionpageeditor'])>2) {
		$actionpage = nl2br(mysqli_real_escape_string($dbc, $_POST['actionpageeditor']));
	} else {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>Please create a valid Action Page in the WYSIWYG Editor - it can\'t be blank!</p>
			</div>
		</div>
	</div>
</div>';		
	}

	// Evaluate thankyoutype radio button:
	if($_POST['thankyoutype']=='thankyou') {
		$thankyou_redirect = 'N';
	} else {
		$thankyou_redirect = 'Y';
	}
	
	if (strlen($_POST['thankyoueditor'])>2) {
		$thankyou = nl2br(mysqli_real_escape_string($dbc, $_POST['thankyoueditor']));
	}elseif($_POST['thankyoutype']=='redirect') {
		$thankyou = ' ';
	} else {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>Please create a valid Thank You Page in the WYSIWYG Editor - it can\'t be blank!</p>
			</div>
		</div>
	</div>
</div>';	
	}
	
	$urlregex = "^(https?|ftp)\:\/\/([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)*(\:[0-9]{2,5})?(\/([a-z0-9+\$_-]\.?)+)*\/?(\?[a-z+&\$_.-][a-z0-9;:@/&%=+\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?\$";
	if(strlen(trim($_POST['redirectlocation']))>5 && eregi($urlregex, trim($_POST['redirectlocation']))) {
		$redirectlink = mysqli_real_escape_string($dbc, $_POST['redirectlocation']);
	}elseif($_POST['thankyoutype']!='redirect') {
		$redirectlink = 'http://';
	} else {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>Please enter a valid Thank You Page Redirect Link - it can\'t be blank and must be a valid http:// address!</p>
			</div>
		</div>
	</div>
</div>';		
	}
	
	//Confirm selection of a form:
	
	if($_POST['chooseexisting']!="") {
		$chosenform = $_POST['chooseexisting'];
		$formselected = TRUE;
	} else {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>Please choose a form for your action page!</p>
			</div>
		</div>
	</div>
</div>';	
	}

	if ($name && $actionpage && $thankyou && $namecheck && $redirectlink && $formselected) {
		//Everything validates:
		//Store campaign id for use when updating campaign record:
		$campaignid = FALSE;
		if(isset($_REQUEST['camp'])) $campaignid = $_REQUEST['camp'];
		
		//Add the action page to the table:
		$q = "INSERT INTO action_pages " . 
		"(account_id, name, action_page, thankyou, thankyou_redirect, redirect_link, date_created, last_updated, form_id) " . 
		"VALUES('{$_SESSION['account_id']}', '$name', '$actionpage', '$thankyou', " . 
		"'$thankyou_redirect', '$redirectlink', NOW(), NOW(), '$chosenform')";
		
		$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
		. mysqli_error($dbc));

		$action_pageid = mysqli_insert_id($dbc);		
		
		//Check success of user table insert:
		if (mysqli_affected_rows($dbc) == 1) {
			$action_pageid = mysqli_insert_id($dbc);		
			//Insert record number for newly created action page into current campaign record:
			if($campaignid) {
				$q = "UPDATE campaigns SET action_page=$action_pageid 
				WHERE id='$campaignid'";

				$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
				. mysqli_error($dbc));
			}	
			$url = '/build/actionpages/?new=added';
						
			//Reset campaign session variables so that campaign form isn't autofilled with old values:
			$_SESSION['campaign_name'] = NULL; $_SESSION['campaign_type'] = NULL;
			$_SESSION['campaign_cost'] = NULL; $_SESSION['campaign_subject'] = NULL;
			
			//Done! Proceed to create-actionpage-done page:
			header("Location: $url");
		} else {
			//If it did not run okay
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>This action page could not be registered due to a system	error. We apologize for any inconvenience.</p>
			</div>
		</div>
	</div>
</div>';
		}
	}
}

require_once (MYSQL);

//Process incoming variables to create needed action page creation form:
$preload = FALSE;
if(isset($_REQUEST['action'])) {
	if( ($_REQUEST['action']=='prebuilt' || $_REQUEST['action']=='existing') && isset($_REQUEST['id']) ) {
		$actionpageid = $_REQUEST['id'];
		
		if($_REQUEST['action']=='existing') {
			//query for selected action pages for this account:
			$q = "SELECT name, action_page, thankyou, thankyou_redirect, redirect_link " . 
			"FROM action_pages " . 
			"WHERE account_id='{$_SESSION['account_id']}' AND id='$actionpageid'";
		} else {
			$q = "SELECT name, action_page, thankyou, thankyou_redirect " . 
			"FROM action_pages " . 
			"WHERE id='$actionpageid' AND id<5";
		}
		$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
		mysqli_error($dbc));

		$pagename = FALSE; $pagecontent = FALSE; $thankyoucontent = FALSE; $redirect_link = FALSE;
		//if action pages available, display in scrolling table:
		if (mysqli_num_rows($r) > 0) {
			while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
				$pagename = $row['name'];
				$pagecontent = $row['action_page'];
				$thankyoucontent = $row['thankyou'];
				$redirect = $row['thankyou_redirect'];
				$redirect_link = $row['redirect_link'];
				$preload = TRUE;
			}
		}
	} elseif($_REQUEST['action']=='scratch') {

	} else {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>There was a problem parsing this request.</p>
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
			<p>This page has been reached in error. No Action Page type was passed to this page. Please <a href="/build/actionpages/new/choose/">choose an action page type</a> first.</p>
			</div>
		</div>
	</div>
</div>';
exit();
}
?>

<div class="content-items content-steps">

<form action="" method="post" id="create_actionpage" name="createactionpageform" class="steps-form">
<fieldset>
	
<div class="row">
	<span class="num"><em>1</em></span>
	<div class="steps-holder">
		<div class="heading">
			<h2>Name Your Action Page</h2>
<?php # Help Button
$helphref = 'build-action-pages-name.html';
$helptitle = 'Help with Naming Your Action Page';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
?>
		</div>
<?php
if(isset($_POST['actionname'])) {
	$action_page_name = $_POST['actionname'];
} elseif($pagename) {
	$action_page_name = 'Copy of ' . $pagename; 
} else {
	$action_page_name = ''; 
}
?>

<input type="text" name="actionname" id="actionname" class="text large-steps" value="<?echo $action_page_name; ?>" />
	</div>
</div>

<div class="row">
	<span class="num"><em>2</em></span>
	<div class="steps-holder">
		<div class="heading">
			<h2>Design Your Action Page</h2>
<?php # Help Button
$helphref = 'build-action-pages-design.html';
$helptitle = 'Help with Designing Your Action Page';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
?>
		</div>
		<div class="choose-bar">
			<strong>What You See Is What You Get (WYSIWYG):</strong>
			<a href="#" onclick="WPro.actionpageeditor.openDialogPlugin('wproCore_fileBrowser&amp;action=image',760,480)" class="btn btn-add"><span>Upload/Manage Images &gt;</span></a>
		</div>

<?php
// create a new wysiwygPro editor instance, name, set content, display: 
$editor = new wysiwygPro(); 
$editor->name = 'actionpageeditor'; 
$editor->id = 'actionpageeditor';
if(isset($_POST['actionpageeditor'])) {
	$editor->value = stripslashes($_POST['actionpageeditor']);
} elseif($pagecontent) {
//	$editor->value = $pagecontent;
	$editor->value = stripslashes($pagecontent);
} else {
	$editor->value = '<p>Begin Here!</p>'; 
}

// CONFIGURE THE FILE MANAGER:
// full directory path for your images folder:
$editor->imageDir = $_SERVER['DOCUMENT_ROOT'] . '/images/actionpages/' . $_SESSION['master_account'] . '/';
//$editor->imageDir = dirname(dirname(__FILE__)) . '/images/' . $_SESSION['master_account'] . '/';

// URL of your images folder:
$editor->imageURL = '/images/actionpages/' . $_SESSION['master_account'] . '/';

$editor->maxImageSize = '1 MB';
$editor->maxImageHeight = '768';
$editor->maxImageWidth = '1024';

// File editing permissions:
$editor->editImages = true;
$editor->renameFiles = true;
$editor->renameFolders = false;
$editor->deleteFiles = true;
$editor->deleteFolders = false;
$editor->copyFiles = true;
$editor->copyFolders = false;
$editor->moveFiles = false;
$editor->moveFolders = false;
$editor->upload = true;
$editor->overwrite = false;
$editor->createFolders = false;

$editor->diskQuota = '4 MB';

// register a new button:
$editor->registerButton('addform', 'Add Form', 'WPro.editors["actionpageeditor"].insertAtSelection ("[form]")', '/images/add-form-wysiwyg.gif', 120, 22);

// add the new button to the toolbar layout:
$editor->addRegisteredButton('addform', 'after:indent'); 

$editor->display('98%', '400'); 
?>

	</div>
</div>

<div class="row">
	<span class="num"><em>3</em></span>
	<div class="steps-holder">
		<div class="heading">
			<h2>Choose Your Form</h2>
<?php # Help Button
$helphref = 'build-action-pages-form.html';
$helptitle = 'Help with Choosing a Form for Your Action Page';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
?>
		</div>
<?php
//see if form was chosen on last pass through this page (error happened?)
//choose form again if available
$chosenform = FALSE;
if(isset($_POST['chooseexisting'])) {
	if($_POST['chooseexisting']!="") $chosenform = $_POST['chooseexisting'];
}

//query for available forms for this account:
$q = "SELECT id, name " . 
"FROM forms " . 
"WHERE account_id='{$_SESSION['account_id']}' " . 
"AND id>20 " . 
"ORDER BY name";
	
$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

//if forms available, display in scrolling table:
//if (mysqli_num_rows($r) > 0) {
echo '
	<div class="choose-bar">
		<strong>Select from an existing form (you can always select another form later):</strong>
	</div><select name="chooseexisting" id="select-steps-3" size="4">';
	//Label for his forms:
	echo '<option value="">---YOUR FORMS---</option>';
	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		echo '<option ';
		if($chosenform) {
			if($chosenform==$row['id']) echo 'selected="selected" ';
		}
		echo 'value="' . $row['id'] . '">' . $row['name'] . '</option>';
	}
	echo '<option value="">---PRE-BUILT FORMS---</option>';
	echo '<option ';
	if($chosenform) {
		if($chosenform=="1") echo 'selected="selected" ';
	}
	echo 'value="1">single</option>';
	echo '<option ';
	if($chosenform) {
		if($chosenform=="2") echo 'selected="selected" ';
	}
	echo 'value="2">email only</option>';
	echo '<option ';
	if($chosenform) {
		if($chosenform=="3") echo 'selected="selected" ';
	}
 	echo 'value="3">address</option>';
	echo '</select>';
/*}else{
	echo '
		<div class="choose-bar">
			<strong>No Forms Found. You need to create forms so you can choose which one to use on your action page. Don\'t worry though, you can finish creating your action page and then come choose a form later.</strong>
		</div>';
}*/
?>
	</div>
</div>

<div class="row">
	<span class="num"><em>4</em></span>
	<div class="steps-holder">
		<div class="heading">
			<h2>Create Your Thank You Page</h2>
<?php # Help Button
$helphref = 'build-action-pages-thank-you.html';
$helptitle = 'Help with Setting up a Thank You Page for Your Action Page';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
?>
		</div>
		<div class="choose-bar">
			<span>Choose One:</span>
			<?php
			if(isset($_POST['thankyoutype'])) {
				if($_POST['thankyoutype']=='redirect') {
				$thankyouchecked = ''; $redirectchecked = ' checked="checked"';
				$thankyoudiv = 'none'; $redirectdiv = 'block';
			} else {
				$thankyouchecked = ' checked="checked"'; $redirectchecked = '';
				$thankyoudiv = 'block'; $redirectdiv = 'none';		
			}
			} elseif($redirect=='Y') {
				$thankyouchecked = ''; $redirectchecked = ' checked="checked"';
				$thankyoudiv = 'none'; $redirectdiv = 'block';	
			} else {
				$thankyouchecked = ' checked="checked"'; $redirectchecked = '';
				$thankyoudiv = 'block'; $redirectdiv = 'none';			
			}
			?>
			<input type="radio"  name="thankyoutype" value="thankyou"<?php echo $thankyouchecked;?> onclick="showThankyou()" id="radio-1" class="radio" />
			<label for="radio-1">Create Your Own Thank You Page</label>
			<input type="radio" name="thankyoutype" value="redirect"<?php echo $redirectchecked;?> onclick="showRedirect()" id="radio-2" class="radio" />
			<label for="radio-2" class="add">Redirect To Your Own Web Page</label>
		</div>
		
<?php 
// Create div to try to hide/show Thank you Wysiwygpro editor:
echo '
		<div id="thankyoudiv" style="display:' . $thankyoudiv . ';">
';
?>

		<div class="choose-bar">
			<strong>What You See Is What You Get (WYSIWYG):</strong>
			<a href="#" onclick="WPro.thankyoueditor.openDialogPlugin('wproCore_fileBrowser&amp;action=image',760,480)" class="btn btn-add"><span>Upload/Manage Images &gt;</span></a>
		</div>

<?php
// create a new editor instance, name, set content, display: 
$editor = new wysiwygPro(); 
$editor->name = 'thankyoueditor'; 
$editor->id = 'thankyoueditor';
if(isset($_POST['thankyoueditor'])) {
	$editor->value = stripslashes($_POST['thankyoueditor']);
} elseif($thankyoucontent) {
//	$editor->value = $thankyoucontent;
	$editor->value = stripslashes($thankyoucontent);
} else {
	$editor->value = '<p>Thank You!</p>'; 
}

// CONFIGURE THE FILE MANAGER:
// full directory path for your images folder:
$editor->imageDir = $_SERVER['DOCUMENT_ROOT'] . '/images/actionpages/' . $_SESSION['master_account'] . '/';
//$editor->imageDir = dirname(dirname(__FILE__)) . '/images/' . $_SESSION['master_account'] . '/';

// URL of your images folder:
$editor->imageURL = '/images/actionpages/' . $_SESSION['master_account'] . '/';

$editor->maxImageSize = '1 MB';
$editor->maxImageHeight = '768';
$editor->maxImageWidth = '1024';

// File editing permissions:
$editor->editImages = true;
$editor->renameFiles = true;
$editor->renameFolders = false;
$editor->deleteFiles = true;
$editor->deleteFolders = false;
$editor->copyFiles = true;
$editor->copyFolders = false;
$editor->moveFiles = false;
$editor->moveFolders = false;
$editor->upload = true;
$editor->overwrite = false;
$editor->createFolders = false;

$editor->diskQuota = '4 MB'; 

$editor->display('98%', '400'); 
?>
		</div>

<?php
echo '
		<div id="redirectdiv" style="display:' . $redirectdiv . ';">
';
if(isset($_POST['redirectlocation'])) {
	$redirectlocation = $_POST['redirectlocation'];
} elseif($redirect_link) {
	$redirectlocation = $redirect_link;
} else {
	$redirectlocation = 'http://'; 
}
?>

		<div class="text-bar">
			<strong>Redirect URL:</strong>
			<div class="bar-holder">
				<input type="text" id="redirectlocation" name="redirectlocation" class="text long" value="<?echo $redirectlocation; ?>" />
				<span class="caption">URL must include http:// to work correctly. Example: http://www.yourdomain.com/thanks.html</span>
			</div>
		</div>

		</div>		
		
	</div>
</div>

<div class="content-link">
	<div class="link-holder">
		<div class="link-frame">
			<a href="javascript: void(0)" class="btn" onclick="needToConfirm=false; document.getElementById('create_actionpage').submit(); return false;" /><span>Save Action Page &gt;</span></a>
			<em>or</em>
			<a href="/build/actionpages/">Cancel</a>
		</div>
	</div>
</div>

<input type="hidden" name="submitted" value="TRUE" />
</fieldset>
</form>

</div>

<!-- / Main Content -->

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
