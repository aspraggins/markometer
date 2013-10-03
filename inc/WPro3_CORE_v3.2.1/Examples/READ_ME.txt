These examples demonstrate how to use WysiwygPro 3.x

To install and run these examples upload this "Examples" folder to your website. This folder should be placed in the same directory as the wysiwygPro folder, just like it is in this distribution. If you change the location and or name of the wysiwygPro folder or this Examples folder then you will need to change the include paths in all the examples.

**Ajax_Example**

Demonstrates how to dynamically load and unload editors using Ajax without refreshing the page.
See the Read Me file for more info.


**Basic_Example**

This is a very basic example showing you how to display an editor in a web page.
See the Read Me file for more info.


**File_Manager**

Demonstrates how to use the file browser windows for selecting files in forms outside of WysiwygPro.
See the Read Me file for more info.


**Multiple_Folders**

Demonstrates how to configure multiple folders for image, media and document management. Each folder appears in the sidebar on the file manager.
See the Read Me file for more info.


**Pop_Up_Editor**

This set of scripts shows you how to quickly add WysiwygPro editing to an existing html form. Users simply click a link beneath the existing textarea. WysiwygPro will popup in a new window allowing them to edit the contents of the textarea.
See the Read Me file for more info.


**Simple_File_Editor**

This is a simple application that allows you to edit documents in a particular directory using WysiwygPro.
This example demonstrates how to build a more complex application using WysiwygPro.
See the Read Me file for more info.


**OTHER EXAMPLES**
There is an example of how to create plugins for WysiwygPro located in the wysiwygPro/plugins/EXAMPLE/ folder.
You can load the EXAMPLE plugin using the loadPlugin method like this:

<?php
include_once('wysiwygPro/wysiwygPro.class.php');
$editor = new wysiwygPro();
$editor->name = 'content';
$editor->value = '<p>Some HTML code..</p>';
$editor->loadPlugin('EXAMPLE');
$editor->display();
?>

You can also get ideas from the other plugins in the wysiwygPro/plugins/ folder.
Many of wysiwygPro's core plugins are located at wysiwygPro/core/plugins/, these plugins
use the same architecture as the regular plugins, they are just located somewhere
different. You can read the source code and get ideas from them too.