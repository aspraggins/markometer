<?php # Create a New Action Page
include_once ($_SERVER['DOCUMENT_ROOT'] . '/inc/WPro3_CORE_v3.2.1/wysiwygPro/wysiwygPro.class.php');
$metatitle = 'Create a New Action Page';
$returnurl = '/login/?returnpage=create/actionpage/choose';
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
	$namecheck = $actionpage = $thankyou = $name = $redirectlink = FALSE;
	
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
			<p>Please enter a valid Action Page Name (it can\'t be blank)!</p>
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
			<p>The Action Page Name ' . $name . '</b> is taken,	please try another.</p>
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
			<p>Please create a valid Action Page in the WYSIWYG Editor (it can\'t be blank)!</p>
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
			<p>Please create a valid Thank You Page in the WYSIWYG Editor (it can\'t be blank)!</p>
			</div>
		</div>
	</div>
</div>';	
	}
	
	$urlregex = "^(https?|ftp)\:\/\/([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)*(\:[0-9]{2,5})?(\/([a-z0-9+\$_-]\.?)+)*\/?(\?[a-z+&\$_.-][a-z0-9;:@/&%=+\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?\$";
	if(strlen(trim($_POST['redirectlocation']))>5 && eregi($urlregex, trim($_POST['redirectlocation']))) {
		$redirectlink = mysqli_real_escape_string($dbc, $_POST['redirectlocation']);
	}elseif($_POST['thankyoutype']!='redirect') {
		$redirectlink = ' ';
	} else {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>Please enter a valid Thank You Page Redirect Link (it can\'t be blank and must be a valid http:// address)!</p>
			</div>
		</div>
	</div>
</div>';		
	}
		
	if ($name && $actionpage && $thankyou && $namecheck && $redirectlink) {
		//Everything validates:
		//Store campaign id for use when updating campaign record:
		$campaignid = FALSE;
		$chosenform = 0;
		if(isset($_POST['chooseexisting'])) $chosenform = $_POST['chooseexisting'];
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
//			$url = '/create/actionpage/done/?actionid=' . $action_pageid;				
			$url = '/manage/actionpages/?new=added';
						
			//Re-set campaign session variables so that campaign form isn't autofilled with old values:
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
			$q = "SELECT action_page, thankyou, thankyou_redirect, redirect_link " . 
			"FROM action_pages " . 
			"WHERE account_id='{$_SESSION['account_id']}' AND id='$actionpageid'";
		} else {
			$q = "SELECT action_page, thankyou, thankyou_redirect " . 
			"FROM action_pages " . 
			"WHERE id='$actionpageid' AND id<5";
		}
		$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
		mysqli_error($dbc));

		$pagecontent = FALSE; $thankyoucontent = FALSE; $redirect_link = FALSE;
		//if action pages available, display in scrolling table:
		if (mysqli_num_rows($r) > 0) {
			while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
				$pagecontent = $row['action_page'];
				$thankyoucontent = $row['thankyou'];
				$redirect = $row['thankyou_redirect'];
				$redirect_link = $row['redirect_link'];
				$preload = TRUE;
			}
		}
	} elseif($_REQUEST['action']=='scratch') {
		//echo '<br />From Scratch<br />';
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
			<p>This page has been reached in error. No parameter was passed to this page.</p>
			</div>
		</div>
	</div>
</div>';
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
			<a href="/help/action-page-name.html" class="btn-help" title="Help" onclick="Modalbox.show(this.href, {title: this.title, width: 600, evalScript: true}); return false;"><span>Help?</span></a>
		</div>
		<input type="text" name="actionname" id="actionname" class="text large-steps" value="<?if(isset($_POST['actionname'])) echo $_POST['actionname']; ?>" onblur="checkActionPageName()" />
		<div id="results"></div>
	</div>
</div>

<div class="row">
	<span class="num"><em>2</em></span>
	<div class="steps-holder">
		<div class="heading">
			<h2>Design Your Action Page</h2>
			<a href="/help/action-page-design.html" class="btn-help" title="Help" onclick="Modalbox.show(this.href, {title: this.title, width: 600, evalScript: true}); return false;"><span>Help?</span></a>
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
	$editor->value = $pagecontent;
} else {
	$editor->value = '<p>Begin Here!</p>'; 
}

// CONFIGURE THE FILE MANAGER:
// full directory path for your images folder:
$editor->imageDir = '/home/rneel/web/htdocs/create/actionpage/images/' . $_SESSION['master_account'] . '/';
//$editor->imageDir = dirname(dirname(__FILE__)) . '/images/' . $_SESSION['master_account'] . '/';

// URL of your images folder:
$editor->imageURL = '/create/actionpage/images/' . $_SESSION['master_account'] . '/';

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
			<a href="/help/action-page-form.html" class="btn-help" title="Help" onclick="Modalbox.show(this.href, {title: this.title, width: 600, evalScript: true}); return false;"><span>Help?</span></a>
		</div>
		<div class="choose-bar">
			<strong class="caption">Use an existing form:</span></strong>
<?php
//query for available forms for this account:
$q = "SELECT id, name " . 
"FROM forms " . 
"WHERE account_id='{$_SESSION['account_id']}' " . 
"AND id>20 " . 
"ORDER BY name";
	
$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

//if forms available, display in scrolling table:
if (mysqli_num_rows($r) > 0) {
	echo '<form name="submitExisting" action="" method="post">';
	echo '<select name="chooseexisting" id="select-steps-3" size="4">';
	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
	}
	echo '</select>';
}else{
	echo 'No Forms Found';
}
?>

		</div>
	</div>
</div>

<div class="row">
	<span class="num"><em>4</em></span>
	<div class="steps-holder">
		<div class="heading">
			<h2>Create Your Thank You Page</h2>
			<a href="/help/action-page-thank-you-page.html" class="btn-help" title="Help" onclick="Modalbox.show(this.href, {title: this.title, width: 600, evalScript: true}); return false;"><span>Help?</span></a>
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
	$editor->value = $thankyoucontent;
} else {
	$editor->value = '<p>Thank You!</p>'; 
}

// CONFIGURE THE FILE MANAGER:
// full directory path for your images folder:
$editor->imageDir = '/home/rneel/web/htdocs/create/actionpage/images/' . $_SESSION['master_account'] . '/';
//$editor->imageDir = dirname(dirname(__FILE__)) . '/images/' . $_SESSION['master_account'] . '/';

// URL of your images folder:
$editor->imageURL = '/create/actionpage/images/' . $_SESSION['master_account'] . '/';

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
			<a href="/create/">Cancel</a>
		</div>
	</div>
</div>

<input type="hidden" name="submitted" value="TRUE" />
</fieldset>
</form>

</div>

<!-- / Main Content -->

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
