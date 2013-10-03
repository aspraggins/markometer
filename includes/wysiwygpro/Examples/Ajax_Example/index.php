<?php
/*
Uses wproAjax to display WysiwygPro

wproAjax is powered by XAJAX, a free BSD licensed AJAX framework. 
Please visit http://www.xajaxproject.org/ for more information.
*/

session_start();

// include wproAjax
require_once("../../wysiwygPro/wproAjax.class.php");

// This custom function is called to set directory permissions
// For security reasons directory permissions cannot be set directly through the Ajax call
// So you must register a constructor function to set these values.
function setFileLocations(&$EDITOR) {
	$EDITOR->editImages = true;
	$EDITOR->renameFiles = true;
	$EDITOR->renameFolders = true;
	$EDITOR->deleteFiles = true;
	$EDITOR->deleteFolders = true;
	$EDITOR->copyFiles = true;
	$EDITOR->copyFolders = true;
	$EDITOR->moveFiles = true;
	$EDITOR->moveFolders = true;
	$EDITOR->upload = true;
	$EDITOR->overwrite = true;
	$EDITOR->createFolders = true;
	
	$EDITOR->imageDir = dirname(__FILE__).'/FILES/';
	$EDITOR->imageURL = dirname($_SERVER['SCRIPT_NAME']).'/FILES/';
	$EDITOR->documentDir = dirname(__FILE__).'/FILES/';
	$EDITOR->documentURL = dirname($_SERVER['SCRIPT_NAME']).'/FILES/';
	$EDITOR->mediaDir = dirname(__FILE__).'/FILES/';
	$EDITOR->mediaURL = dirname($_SERVER['SCRIPT_NAME']).'/FILES/';
}

// load and configure wproAjax
$wproAjax = new wproAjax();

// register the custom function for setting directory permissions
$wproAjax->registerConstructorFunction('setFileLocations');

// register all the themes and languages we would like to use:
$wproAjax->addTheme('default');
$wproAjax->addTheme('aqua');
$wproAjax->addTheme('blue');
$wproAjax->addTheme('jetblack');

$wproAjax->addlanguage('en-us');
$wproAjax->addlanguage('en-gb');

// process editor requests, this must be called before any HTML output.
$wproAjax->processRequests();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/2000/REC-xhtml1-20000126/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Ajax Test</title>
<?php 
// print javascript for displaying the editor
$wproAjax->displayHeadContent();
?>
</head>
<body>

<script type="text/javascript">
function displayEditor1() {
	// id of a div tag to display editor within:
	var div = 'ec1';
	
	// set any wysiwygPro Properties:
	var properties = new Object();
	properties.name = 'editor1';
	properties.theme = 'blue';
	properties.iframeDialogs = true;
	properties.value = '<p>Hello World</p>';
	
	// set any wysiwygPro function calls:
	var methods = new Object();
	methods.loadPlugins = [['tagPath']];
	
	// set the registered constructor function to call:
	var cfunction = 'setFileLocations';
	
	// set a paramater to be passed to the constructor function:
	var cparam = '';
	
	// display the editor:
	displayWysiwygPro(div, properties, methods, cfunction, cparam);
}
</script>

<h1>Display WysiwygPro 3 Using Ajax</h1>
<p>This test demonstrates how to load editors dynamically using AJAX.</p>
<p>WPRO_COMPILE_JS_INCLUDES must be set to true in config.inc.php or this example will not function!

<div id="ec1"><!-- editor 1 will be displayed here --></div>
<p>
<input type="button" value="Draw Editor 1" onclick="displayEditor1()" />
<input type="button" value="Delete Editor 1" onclick="WPro.deleteEditor('editor1');" />
</p>

<div id="ec2"><!-- editor 2 will be displayed here --></div>
<p>
<!-- short-hand example: -->
<input type="button" value="Draw Editor 2" onclick="displayWysiwygPro('ec2', {'name':'editor2', 'value':'&lt;p&gt;some html...&lt;/p&gt;'}, {loadPlugins:[['tagPath']]}, 'setFileLocations', '');" />
<input type="button" value="Delete Editor 2" onclick="WPro.deleteEditor('editor2');" />
</p>


</body>
</html>