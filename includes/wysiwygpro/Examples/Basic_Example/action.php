<?php

// Get the code from the editor:
// The $_POST variable has the same name as the name given to the editor.
if (isset($_POST['content'])) {
	$html = stripslashes($_POST['content']); 
	echo $html;
}
?>