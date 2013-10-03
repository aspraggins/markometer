<?php 
/* Please see the online documentation for a step by step walkthrough */

// Step 1: (required) include the WysiwygPro script file:
include_once('../../wysiwygPro/wysiwygPro.class.php');

// Step 2: (required) start a session.
session_start();

// Step 3: (required) create a new editor object:
$editor = new wysiwygPro();

// Step 4: (required) give the editor a name (equivalent to the name attribute on a regular textarea):
$editor->name = 'content';

// Step 5: (optional) set some HTML code to edit:
$editor->value = '<p>This is the HTML code I want to edit</p>';

// Step 6: (optional) configure the file browser and add any other configuration options you require:

// Note: folders used by the file browser must be made writable otherwise uploading and file editing will not work.

// Full file path of your images folder:
$editor->imageDir = dirname(__FILE__).'/images/';

// URL of your images folder:
$editor->imageURL = dirname($_SERVER['SCRIPT_NAME']).'/images/';

// set file browser editing permissions:
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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<?php
// Step 7: (optional) Print WysiwygPro script and CSS files into the head of the page:
// This step is optional but will provide better performance.
// This must be called after the editor has been configured.
$editor->displayHeadContent();
?>
</head>

<body>
<p>Basic example</p>
<form name="form1" action="action.php" method="post">
<?php

// Step 8: (required) display the editor:
$editor->display('100%', 400);
?>
<br /><br />
<input type="submit" name="submit" value="Submit Form" />
</form>
</body>
</html>
