<?php 

session_start();

// make sure this include is correct:
include_once ('../../wysiwygPro/wysiwygPro.class.php');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Edit Textarea</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">body {background-color:threedface; border: 0px 0px; padding: 0px 0px; margin: 0px 0px}</style>
</head>
<body>
<div>
<script language="javascript">
<!--//
// this function updates the code in the textarea and then closes this window
function do_save() {
	window.opener.currentTextArea.value = WPro.editors["htmlCode"].getValue();
	window.close();
	window.opener.focus();
}
//-->
</script>
<?php

// create a new instance of the wysiwygPro class:
$editor = new wysiwygPro();

// File browser configuration:

// Note: folders used by the file browser must be made writable otherwise uploading and file editing will not work.

// full directory path of your images folder
$editor->imageDir = dirname(__FILE__).'/images/';

// URL of your images folder
$editor->imageURL = dirname($_SERVER['SCRIPT_NAME']).'/images/';

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

// add a custom save button:
$editor->addbutton('Save', 'before:print', 'do_save();', $editor->getEditorURL().'images/save.gif', 22, 22);

// add a custom cancel button:
$editor->addbutton('Cancel', 'before:print', 'window.close();window.opener.focus();', 'cancel.gif', 22, 22);

// add a sseparator:
$editor->addSeparator('separator', 'after:cancel');

// make the editor fill the entire window:
$editor->addJSEditorEvent('load', 'function(editor){editor.fullWindow();}');

// remove the full window button
$editor->removeButtons('fullwindow');

// remove the resize corner:
$editor->disableFeature('dragresize');

// load code into the editor using javascript:
$editor->addJSEditorEvent('init', 'function(editor){editor.textarea.value=window.opener.currentTextArea.value;}');

// print the editor to the browser:
$editor->display();

?>
</div>
</body>
</html>
