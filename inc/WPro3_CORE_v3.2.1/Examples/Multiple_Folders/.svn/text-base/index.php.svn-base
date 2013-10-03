<?php 
// include the wysiwygPro class file
include_once('../../wysiwygPro/wysiwygPro.class.php');

// start a session
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>
<h1>Multiple folders example</h1>
<p>Each folder appears down the sidebar on the file manager. Click the &quot;Insert/Edit Image&quot; button to see the multiple image directories. Click the &quot;Insert/Edit Media&quot; button to see the multiple media directories. Click the &quot;Insert/Edit Hyperlink&quot; button to see the multiple document directories and since you can link to media and image files you will see those directories too. </p>
<?php

// create a new wysiwygPro editor:
$editor = new wysiwygPro();

// create and configure the first image directory:
$dir1 = new wproDirectory();
// set type of folder:
$dir1->type = 'image';
// full file path of the folder:
$dir1->dir = dirname(__FILE__).'/images1/';
// URL of the folder:
$dir1->URL = dirname($_SERVER['SCRIPT_NAME']).'/images1/';
// Set access permissions:
$dir1->editImages = true;
$dir1->renameFiles = true;
$dir1->renameFolders = true;
$dir1->deleteFiles = true;
$dir1->deleteFolders = true;
$dir1->copyFiles = true;
$dir1->copyFolders = true;
$dir1->moveFiles = true;
$dir1->moveFolders = true;
$dir1->upload = true;
$dir1->overwrite = true;
$dir1->createFolders = true;
// You can give the directory a custom label using the name attribute:
$dir1->name = 'My Images';
// If you do not do this the name of the folder will be used instead.
// Note: you can give the directory a custom icon using the icon attribute. Set it to the URL of the icon image.
// add the directory to the editor
$editor->addDirectory($dir1);

// create and configure the second image directory:
$dir2 = new wproDirectory();
// set type of folder:
$dir2->type = 'image';
// full file path of the folder:
$dir2->dir = dirname(__FILE__).'/images2/';
// URL of the folder:
$dir2->URL = dirname($_SERVER['SCRIPT_NAME']).'/images2/';
// Set access permissions:
$dir2->editImages = true;
$dir2->renameFiles = true;
$dir2->renameFolders = true;
$dir2->deleteFiles = true;
$dir2->deleteFolders = true;
$dir2->copyFiles = true;
$dir2->copyFolders = true;
$dir2->moveFiles = true;
$dir2->moveFolders = true;
$dir2->upload = true;
$dir2->overwrite = true;
$dir2->createFolders = true;
// You can give the directory a custom label using the name attribute:
$dir2->name = 'Shared Images';
// add the directory to the editor
$editor->addDirectory($dir2);

// create and configure the first documents directory:
$dir3 = new wproDirectory();
// set type of folder:
$dir3->type = 'document';
// full file path of the folder:
$dir3->dir = dirname(__FILE__).'/documents1/';
// URL of the folder:
$dir3->URL = dirname($_SERVER['SCRIPT_NAME']).'/documents1/';
// Set access permissions:
$dir3->editImages = true;
$dir3->renameFiles = true;
$dir3->renameFolders = true;
$dir3->deleteFiles = true;
$dir3->deleteFolders = true;
$dir3->copyFiles = true;
$dir3->copyFolders = true;
$dir3->moveFiles = true;
$dir3->moveFolders = true;
$dir3->upload = true;
$dir3->overwrite = true;
$dir3->createFolders = true;
// You can give the directory a custom label using the name attribute:
$dir3->name = 'My Downloads';
// add the directory to the editor
$editor->addDirectory($dir3);

// create and configure the second documents directory:
$dir4 = new wproDirectory();
// set type of folder:
$dir4->type = 'document';
// full file path of the folder:
$dir4->dir = dirname(__FILE__).'/documents2/';
// URL of the folder:
$dir4->URL = dirname($_SERVER['SCRIPT_NAME']).'/documents2/';
// Set access permissions:
$dir4->editImages = true;
$dir4->renameFiles = true;
$dir4->renameFolders = true;
$dir4->deleteFiles = true;
$dir4->deleteFolders = true;
$dir4->copyFiles = true;
$dir4->copyFolders = true;
$dir4->moveFiles = true;
$dir4->moveFolders = true;
$dir4->upload = true;
$dir4->overwrite = true;
$dir4->createFolders = true;
// You can give the directory a custom label using the name attribute:
$dir4->name = 'Shared Downloads';
// add the directory to the editor
$editor->addDirectory($dir4);

// create and configure the first media directory:
$dir5 = new wproDirectory();
// set type of folder:
$dir5->type = 'media';
// full file path of the folder:
$dir5->dir = dirname(__FILE__).'/media1/';
// URL of the folder:
$dir5->URL = dirname($_SERVER['SCRIPT_NAME']).'/media1/';
// Set access permissions:
$dir5->editImages = true;
$dir5->renameFiles = true;
$dir5->renameFolders = true;
$dir5->deleteFiles = true;
$dir5->deleteFolders = true;
$dir5->copyFiles = true;
$dir5->copyFolders = true;
$dir5->moveFiles = true;
$dir5->moveFolders = true;
$dir5->upload = true;
$dir5->overwrite = true;
$dir5->createFolders = true;
// You can give the directory a custom label using the name attribute:
$dir5->name = 'My Media';
// add the directory to the editor
$editor->addDirectory($dir5);

// create and configure the second media directory:
$dir6 = new wproDirectory();
// set type of folder:
$dir6->type = 'media';
// full file path of the folder:
$dir6->dir = dirname(__FILE__).'/media2/';
// URL of the folder:
$dir6->URL = dirname($_SERVER['SCRIPT_NAME']).'/media2/';
// Set access permissions:
$dir6->editImages = true;
$dir6->renameFiles = true;
$dir6->renameFolders = true;
$dir6->deleteFiles = true;
$dir6->deleteFolders = true;
$dir6->copyFiles = true;
$dir6->copyFolders = true;
$dir6->moveFiles = true;
$dir6->moveFolders = true;
$dir6->upload = true;
$dir6->overwrite = true;
$dir6->createFolders = true;
// You can give the directory a custom label using the name attribute:
$dir6->name = 'Shared Media';
// add the directory to the editor
$editor->addDirectory($dir6);

// display the editor:
$editor->display();
?>
</body>
</html>
