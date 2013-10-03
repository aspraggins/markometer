<?php # Edit Existing Action Page
include_once ($_SERVER['DOCUMENT_ROOT'] . '/inc/WPro3_CORE_v3.2.1/wysiwygPro/wysiwygPro.class.php');
$metatitle = 'Edit an Action Page';
$returnurl = '/login/?returnpage=build/actionpages/';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Heading and Help Button -->

<div class="heading">
<h1>Edit an Action Page
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

	//Set $user_id to POST value:
	$action_id = trim($_REQUEST['id']);

	//Set up variables:
	$namecheck = $actionpage = $thankyou = $name = $redirectlink = $formselected = FALSE;

	//Check for a name:
	if(preg_match('/^[A-Z0-9 \'.-]{2,60}$/i', trim($_POST['actionname']))) {
		$name = mysqli_real_escape_string($dbc, trim($_POST['actionname']));
	} else {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>Please enter a Valid Action Page Name - it can\'t be blank!</p>
			</div>
		</div>
	</div>
</div>';
	}

	//SQL hit to recheck availability of name:
	$q = "SELECT * FROM action_pages WHERE name='$name' AND account_id={$_SESSION['account_id']}";

	$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br / f>MySQL
	Error: " . mysqli_error($dbc));

	$num = mysqli_num_rows($r);

	if($num!=0 && $_POST['beginactionname']!=$name) {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>The Action Page Name "' . $name . '" is taken, please try another.</p>
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
		//Update the action page to the table:
		$q = "UPDATE action_pages SET updateflag=1-updateflag, name='$name', " .  	 
		"action_page='$actionpage', thankyou='$thankyou', " .  
		"thankyou_redirect='$thankyou_redirect', redirect_link='$redirectlink', " . 
		"form_id='$chosenform', last_updated=NOW() " . 
		"WHERE id=$action_id LIMIT 1"; 

		$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
		. mysqli_error($dbc));

		//Check success of user table insert:
		if (mysqli_affected_rows($dbc) == 1) {
			//Move to build action pages overview:
			$url = '/build/actionpages/?saved=yes';
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
}   //End of main Submit conditional

//Check incoming passed id variable:
//Check for valid ID being passed:
if ( (isset($_REQUEST['id'])) && (is_numeric($_REQUEST['id'])) ) {
	$action_id = $_REQUEST['id'];
} else {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>This page has been reached in error. No Action Page was selected to edit. Please <a href="/build/actionpages/">select one</a>.</p>
			</div>
		</div>
	</div>
</div>';
	exit();
}

require_once (MYSQL);

//Query for action page info to fill out form:
$q = "SELECT name, action_page, thankyou, thankyou_redirect, redirect_link, form_id " . 
"FROM action_pages " . 
"WHERE id=$action_id";

$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
. mysqli_error($dbc));

//Check success of user query:
if (@mysqli_num_rows($r) == 1) {
	//Pull in action page variables from database:
	$row = mysqli_fetch_array($r,MYSQLI_ASSOC);	
	$content = $row['action_page'];
	$_SESSION['actionpagename'] = $name = $row['name'];
}else{
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>There was a problem accessing this action page. Please try again!</p>
			</div>
		</div>
	</div>
</div>';
	exit();
}
?>

<div class="content-items content-steps">

<form action="/build/actionpages/edit/?id=<?php if (isset($action_id)) echo $action_id; ?>" method="post" id="edit_actionpage" name="editactionpageform" class="steps-form">
<fieldset>
	
<div class="row">
	<span class="num"><em>1</em></span>
	<div class="steps-holder">
		<div class="heading">
			<h2>Your Action Page Name</h2>
<?php # Help Button
$helphref = 'build-action-pages-name.html';
$helptitle = 'Help with Naming Your Action Page';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
?>
		</div>
		<input type="text" name="actionname" id="actionname" class="text large-steps" value="<?php if (isset($name)) echo $name; ?>" />
	</div>
</div>

<div class="row">
	<span class="num"><em>2</em></span>
	<div class="steps-holder">
		<div class="heading">
			<h2>Your Action Page Design</h2>
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
// $editor->value = stripslashes($content);
if(isset($_POST['actionpageeditor'])) {
	$editor->value = stripslashes($_POST['actionpageeditor']);
} else {
	$editor->value = stripslashes($content);
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
			<h2>Your Form</h2>
<?php # Help Button
$helphref = 'build-action-pages-form.html';
$helptitle = 'Help with Choosing a Form for Your Action Page';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
?>
		</div>

<?php		
$chosenform = FALSE;
//see if a form was stored in database for this action page:
if($row['form_id']!="0" && $row['form_id']!="") $chosenform = $row['form_id'];

//see if form was chosen on last pass through this page (error happened?)
//choose form again if available
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

echo '
<div class="choose-bar">
	<strong>Your current form is highlighted (if you have chosen one). You can select from your existing forms:</strong>
</div><select name="chooseexisting" id="select-steps-3" size="4">';
//Label for his forms:
echo '<option value="">---YOUR FORMS---</option>';
while ($row2 = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	echo '<option ';
	if($chosenform) {
		if($chosenform==$row2['id']) echo 'selected="selected" ';
	}
	echo 'value="' . $row2['id'] . '">' . $row2['name'] . '</option>';
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
		
		
?>		
</div></div>	
<div class="row">
	<span class="num"><em>4</em></span>
	<div class="steps-holder">
		<div class="heading">
			<h2>Your Thank You Page</h2>
<?php # Help Button
$helphref = 'build-action-pages-thank-you.html';
$helptitle = 'Help with Setting up a Thank You Page for Your Action Page';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
?>
		</div>
		<div class="choose-bar">
			<span>Currently Using:</span>
			<?php
			if(isset($_POST['thankyoutype'])) {
				if($_POST['thankyoutype']=='redirect') {
					$thankyouchecked = ''; $redirectchecked = ' checked="checked"';
					$thankyoudiv = 'none'; $redirectdiv = 'block';
				} else {
					$thankyouchecked = ' checked="checked"'; $redirectchecked = '';
					$thankyoudiv = 'block'; $redirectdiv = 'none';		
				}
			} elseif($row['thankyou_redirect']!='N') {
				$thankyouchecked = ''; $redirectchecked = ' checked="checked"';
				$thankyoudiv = 'none'; $redirectdiv = 'block';	
			} else {
				$thankyouchecked = ' checked="checked"'; $redirectchecked = '';
				$thankyoudiv = 'block'; $redirectdiv = 'none';			
			}
			?>
			<input type="radio"  name="thankyoutype" value="thankyou" <?php echo $thankyouchecked;?> onclick="showThankyou()" id="radio-1" class="radio" />
			<label for="radio-1">Create Your Own Thank You Page</label>
			<input type="radio" name="thankyoutype" value="redirect" <?php echo $redirectchecked;?> onclick="showRedirect()" id="radio-2" class="radio" />
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
// $editor->value = stripslashes($row['thankyou']);
if(isset($_POST['thankyoueditor'])) {
	$editor->value = stripslashes($_POST['thankyoueditor']);
} else {
	$editor->value = stripslashes($row['thankyou']);
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
} else {
$redirectlocation = $row['redirect_link'];
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
			<a href="#" class="btn" onclick="document.getElementById('edit_actionpage').submit()" /><span>Save Action Page &gt;</span></a>
			<em>or</em>
			<a href="/build/actionpages/">Cancel</a>
		</div>
	</div>
</div>

<?php
//Store beginning value of action page name as POST variable for comparison during validation:
echo '<input type="hidden" name="beginactionname" value="' . $name . '" />';
?>

<input type="hidden" name="submitted" value="TRUE" />
</fieldset>
</form>
	
</div>

<!-- / Main Content -->

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
