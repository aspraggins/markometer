<?php 
// include the wysiwygPro class:
include_once('../../wysiwygPro/wysiwygPro.class.php');

// start a session
session_start();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<p>File manager example</p>
<?php

// create a new instance of wysiwygPro:
$editor = new wysiwygPro();

// configure your directories:

// Note: folders used by the file browser must be made writable otherwise uploading and file editing will not work.

// full directory path for your images folder:
$editor->imageDir = dirname(__FILE__).'/images/';
// URL of your images folder:
$editor->imageURL = dirname($_SERVER['SCRIPT_NAME']).'/images/';

// full directory path of your documents folder for storing PDF and Word files etc:
$editor->documentDir = dirname(__FILE__).'/documents/';
// url of your documents folder:
$editor->documentURL = dirname($_SERVER['SCRIPT_NAME']).'/documents/';

// full directory path of your media folder for storing video files:
$editor->mediaDir = dirname(__FILE__).'/media/';
// url of your media folder:
$editor->mediaURL = dirname($_SERVER['SCRIPT_NAME']).'/media/';

// File editing permissions:
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

// Note: to learn how to configure multiple folders for storing images, documents and media see the multiple folders example.

// display the JavaScript function for displaying the file browser with the above configuration:
$editor->displayFileBrowserJS('OpenFileBrowser');

/* 
Now to display the file browser simply call the function 'OpenFileBrowser' with 3 paramaters:
	1. A string specifying the type of browser to open, values are 'image', 'document', 'media', or 'link'
	2. A function to be called by the file browser to populate your form. The function will be passed the URL of the chosen file.
	3. Optional: A function to be called by the file browser to populate the browser when it opens. The function should return 
	the current value from your form.
*/
?>
<form action="" method="post" name="form1">
Choose Image:<br>
<input type="text" name="chosenImage" /><button type="button" onClick="OpenFileBrowser('image', function(url) {document.form1.chosenImage.value=url;}, function() {return document.form1.chosenImage.value;} )">Choose Image...</button><br><br>
Choose Document:<br>
<input type="text" name="chosenDocument" /><button type="button" onClick="OpenFileBrowser('document', function(url) {document.form1.chosenDocument.value=url;}, function() {return document.form1.chosenDocument.value;} )">Choose Document...</button><br><br>
Choose Media:<br>
<input type="text" name="chosenMedia" /><button type="button" onClick="OpenFileBrowser('media', function(url) {document.form1.chosenMedia.value=url;}, function() {return document.form1.chosenMedia.value;} )">Choose Media...</button><br><br>
Choose link:<br>
<input type="text" name="chosenLink" /><button type="button" onClick="OpenFileBrowser('link', function(url) {document.form1.chosenLink.value=url;}, function() {return document.form1.chosenLink.value;} )">Choose Link...</button>
</form>
</body>
</html>