<?php

// WHAT THIS FILE DOES:
// First we are going to check if we need to perform a save function and if so we will perform one.
// If not we will check if a file has been requested for editing, if one has then we will display the editor.
// Else if nothing has been requested we will display a form where the user can choose a file to edit.

// SETUP:

// NOTE: user authentication is only available with Apache Servers.

// change this value to the User Name you want to use:
$user_name = "admin";

// change this value to the password you want to use:
$password =  "password";

// Change this to the physical file path of the folder containing the documents you want to edit:

$editable_files_directory = dirname(__FILE__).'/editable_files/';

// Change this to the URL of the folder containing the documents you want to edit:
// For best results make this an absolute URL

$editable_files_url = dirname($_SERVER['SCRIPT_NAME']).'/editable_files/';

// CHECK THAT THESE INCLUDES POINT CORRECTLY:

$WPRO_INC_PATH = '../../wysiwygPro/';

include_once ($WPRO_INC_PATH.'wproUtilities.class.php');
include_once ($WPRO_INC_PATH.'wysiwygPro.class.php');

// ADVANCED OPTIONS:

// This is an optional array of files to be excluded:

$excluded_files = array(".htaccess",".DS_Store", "images");

// This is an array of the types of files you can edit.

$editiable_file_types = array('.htm','.html','.php','.asp','.txt');

// start a new wysiwygPro object
$editor = new wysiwygPro();

// add any other editor configuration options here...

// FILE BROWSER SETTINGS:

// Note: folders used by the file browser must be made writable otherwise uploading and file editing will not work.

// full directory path of your images folder
$editor->imageDir = dirname(__FILE__).'/editable_files/images/';

// URL of your images folder
$editor->imageURL = dirname($_SERVER['SCRIPT_NAME']).'/editable_files/images/';

// file browser permissions
$editor->editImages = true;
$editor->renameFiles = true;
$editor->renameFolders = true;
$editor->deleteFiles = true;
$editor->deleteFolders = true;
$editor->copyFiles = true;
$editor->copyFolders = true;
$editor->moveFiles = true;
$editor->moveFolders = true;
$editor->upload = true;
$editor->overwrite = true;
$editor->createFolders = true;

// NO NEED TO CHANGE ANYTHING BEYOND HERE :-) -------------------------------------------------------------------

// check authentication
if  (!isset($_SERVER['PHP_AUTH_USER'])) { 
//If empty send header causing dialog box to appear 
	header('WWW-Authenticate: Basic realm="Editable Files"'); 
	header('HTTP/1.0 401 Unauthorized'); 
	echo 'Authorization required.'; 
	exit; 
} else if (($_SERVER['PHP_AUTH_USER'] != $user_name) || ($_SERVER['PHP_AUTH_PW'] != $password)) { 
	header('WWW-Authenticate: Basic realm="Editable Files"'); 
	header('HTTP/1.0 401 Unauthorized'); 
	echo 'Authorization required.'; 
	exit; 
}

// find out the URL of the WysiwygPro folder
$editorURL = $editor->getEditorURL();

// display a links browser on the hyperlink dialog. ----------------------------------------------------------
$editor->linksBrowserURL = $_SERVER['SCRIPT_NAME'].'?linksBrowser=true';
if (isset($_GET['linksBrowser'])) {

	define('WPRO_ALLOW_GLOBALS', true);
	$DIALOG = NULL; $EDITOR = NULL;
	include_once($WPRO_INC_PATH.'core/libs/common.inc.php');
	include_once(WPRO_DIR.'core/libs/wproDialog.class.php');
	$DIALOG = & new wproDialog();
	$EDITOR->triggerEvent('onLoadDialog');
	
	// dynamically generate links for the hyperlink window
	// this is a recursive function for automatically generating links to files and subdirectories!
	// exclusion added by Ben Johnson
	function build_link_array($directory, $address, $exclude=array()) {
		$links = array();
		// first put the files in an array, then sort them alphabetically.
		$files = array();
		$handle=opendir($directory);
		while (false!==($file = readdir($handle))) {
			if ($file != "." && $file != ".." && !in_array($file,$exclude)) {
				array_push($files, $file);
			}
		}
		// sort the files alphabetically
		sort($files);
		// now generate the data
		foreach ($files as $file) {
			if (file_exists($directory.$file)) {
				$data = array();
				$data['URL'] = $address.$file;
				$data['title'] = $file;
				// if file is a folder
				if (!is_file($directory.$file)) {
					$data['children'] = build_link_array($directory.$file.'/', $address.$file.'/', $exclude);
				}
				array_push($links, $data);
			}
		}
		closedir($handle);
		return $links;
	}
	$EDITOR->links = build_link_array($editable_files_directory, $editable_files_url, $excluded_files);
	
	$DIALOG->loadPlugin('wproCore_fileBrowser', true);
	$DIALOG->runPluginAction('wproCore_fileBrowser', 'linksBrowser', array());
	$DIALOG->display();
	exit;
	
// checks whether to perform a save or not -------------------------------------------------------------------
} else if (isset($_POST['htmlCode'])) { 
		
	// Perform Save:
		
	// put the HTML code in a variable for processing
	$code = stripslashes($_POST['htmlCode']);
	
	// break apart excessively long words
	$code = wproUtilities::longwordbreak ($code, 40, ' ');
	
	// remove unwanted tags
	// Uncomment this block of code to remove unwanted tags. You can change the tags that are removed.
	/*
	$code = wproUtilities::removeTags ($code, array(
		'object' => true,
		'embed' => true,
		'applet' => true,
		'script' => true
	));
	*/
	
	// remove unwanted attributes
	// uncomment to remove unwanted attributes
	// this will remove script event handlers:
	//$code = wproUtilities::removeAttributes($code, array("on[A-Z]+"));
			
	// encode and protect email addresses
	$code = wproUtilities::emailEncode($code);
	
	// get file path for the file to edit:
	$file = $editable_files_directory.$_POST['working_file'];
	
	// check if we can write to the file
	if (!is_writable($file)) {
		
		echo "<p>An error occured while attempting to save changes.</p>
			<p>This file is not writable.</p>
			<p><a href=\"index.php\">Start Over</a></p>";
	
	} else {
		
		// open the file
		$writeme=fopen(stripslashes($editable_files_directory.$_POST['working_file']),"w"); 
	
		// write to the file
		// include the save action in an 'if' statement so we can check if it worked:
		if (fputs($writeme, $code)) {
			echo "<p>Thank you. Your changes have been saved.</p>
			<p><a href=\"index.php\">Start Over</a></p>";
		} else {
			echo "<p>An error occured while attempting to save changes.</p>
			<p>Check you have permission to modify the 'editable_files' directory</p>
			<p><a href=\"index.php\">Start Over</a></p>";
		}
		
		// close the file
		fclose($writeme); 
	}
		
	// if no actions were requested but a file has been requested for editing then generate the editor:
} else if (isset($_POST['working_file'])) {

// -----------------------------------------------------
// Generate the editor page:
// -----------------------------------------------------

ob_start();
?>
<html> 
<head> 
<title>Edit the page</title> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head> 
<body>

<form id="editorForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

<!-- begin PHP code for displaying the editor -->
<?php

// insert the code into the editor
$editor->loadValueFromFile($editable_files_directory.$_POST['working_file']);

// add a save button to the toolbar:
$editor->set_savebutton('save');

// add a custom cancel button:
$editor->addButton('Cancel', 'before:print', "document.location.replace('".$_SERVER['PHP_SELF']."')", 'cancel.gif');

// add a separator:
$editor->addSeparator('mySeparator', 'after:cancel');

// set the baseurl to the url of the file being edited:
$editor->baseURL = preg_replace("/\/[^\/]+$/smi", "/", $editable_files_url.$_POST['working_file']);

// make the editor fill the entire window:
$editor->addJSEditorEvent('load', 'function(editor){editor.fullWindow();}');

// remove the full window button
$editor->removeButtons('fullwindow');

// remove the resize corner:
$editor->disableFeature('dragresize');

// print the editor 
$editor->display();

?>
<!-- record which file we are editing so that when we come to save we know what we are saving!!!! -->
<input type="hidden" value="<?php echo stripslashes($_POST['working_file']); ?>" name="working_file">

</form>
</body> 
</html>
<?php
ob_end_flush();
// if no actions have been requested and no file has been requested for editing then display a form where the user can select a file to edit:
} else {
?>
<html> 
<head> 
<title>Choose a file to edit</title> 
<script type="text/javascript">
// this function powers the expanding tree menu
function clickHandler(src) {
	if (document.getElementById('d_'+src.id)) {
		var targetElement = document.getElementById('d_'+src.id);
		if (targetElement.style.display == "none") {
			targetElement.style.display = "";
			src.src = 'open.gif';
		} else {
			var bods = targetElement.getElementsByTagName('TBODY')
			for (i=0; i>bods.length; i++) {
				bods[i].style.display = "none";
			}
			targetElement.style.display = "none";
			src.src = 'closed.gif';
		}
	}
}
</script>
<style type="text/css">
#treeBar {
	border-bottom: 1px solid #999999;
	background-color: #cccccc;
	font-weight: bold;
}
#treeHolder {
	border: 1px solid #999999;
}
.treeRow {
	border-top: 1px solid #eeeeee;
}
</style>
</head> 
<body> 

<h3>Choose a file to edit</h3> 

<p>Choose a page to edit and then hit the submit button:</p> 

<form name="choose" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 

<div id="treeHolder">
<div id="treeBar">&nbsp;Pick&nbsp&nbsp<span style="color:#999999">|</span>&nbsp;Name&nbsp;</div>
<?php 
// open the directory
// this is a recursive function that builds the list of files including subfolders so that you can select a file to edit
$fnum = 0;
function build_file_list($file_directory, $web_directory, $exclude = array(), $level = 1) {
	global $fnum, $editorURL;
	$handle=opendir($file_directory);
	// loop through the files in the directory and put them in an array, we will then sort the array alphabetically
	$files = array();
	while (false!==($file = readdir($handle))) {
		if ($file != "." && $file != ".." && !in_array($file,$exclude)) {
			array_push($files, $file);
		}
	}
	// sort files alphabetically
	sort($files);
	// display file list
	$i=0;
	$padding = $level*16;
	foreach ($files as $file) {
		if (file_exists($file_directory.$file) && !is_file($file_directory.$file)) {
			$type = 'folder';
		} else {
			$type = 'file';
		}
		echo "<div class=\"treeRow\"><label>";
		if ($type == 'folder') {
			//
		//} else if ($extension == '.htm' || $extension == '.html' || $extension == '.php') {
		} else {
			echo "<input style=\"margin-right:".($padding-3)."px\" type=\"radio\" value=\"$web_directory$file\" name=\"working_file\">";
		}
		if ($type == 'folder') {
			echo "<img style=\"margin-left:".$padding."px;cursor: pointer; cursor: hand\" id=\"_".preg_replace("/[^A-Za-z0-9]/smi", "", $file_directory.$file)."\" onclick=\"clickHandler(this)\" src=\"closed.gif\" width=\"16\" height=\"16\" align=\"absmiddle\" alt=\"\"><img src=\"".$editorURL."images/folder.gif\" width=\"22\" height=\"22\" align=\"absmiddle\" alt=\"\">$file";
		//} else if ($extension == '.htm' || $extension == '.html' || $extension == '.php') {
		} else {
			echo "<img src=\"".$editorURL."images/htm_icon.gif\" width=\"22\" height=\"22\" align=\"absmiddle\" alt=\"\">$file";
		}
		
		if ($type == 'folder') {
			echo "<div id=\"d__".preg_replace("/[^A-Za-z0-9]/smi", "", $file_directory.$file)."\" style=\"display:none;\">";
			build_file_list($file_directory.$file.'/', $web_directory.$file.'/', $exclude, $level + 1) ;
			echo "</div>";
			$fnum ++;
		}
		echo "
		</label></div>"; 
		$i ++;
	} 
	closedir($handle); 
}
build_file_list($editable_files_directory, '', $excluded_files);
?>
</table></div>
<p><input type="submit" value="Submit"></p>
</form> 

</body> 
</html>
<?php } ?>