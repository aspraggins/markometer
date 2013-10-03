<?php # edit note from within a modal box, 2nd box
//Programmer: Ray Neel 1/2009

require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.inc.php");
//Initialize a session if one not already in use:
if (session_id()=="") session_start();

echo '<div style="text-align:center;">';

if (isset($_GET['submitted'])) {
	//Test for something in each box:
	if(strlen($_GET['body'])>0) {
		//Write to notes in lead table:
		//MySQL setup:
		require_once(MYSQL);
		$notesmsg = mysqli_real_escape_string($dbc, $_GET['body']);
		//Pull in id of note to be edited from passed variable:
		$id = $_GET['leadid'];
		
		$q = "UPDATE notes " . 
		"SET note='$notesmsg' " . 
		"WHERE id='$id' LIMIT 1";
		
		$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
		. mysqli_error($dbc));
		
		if (mysqli_affected_rows($dbc) == 1) {
			echo 'Your note has been updated successfully.';			
		} else {
			echo 'There was a problem updating this note. We are sorry for the inconvenience. Please hit close and try again.';
		}
	} else {
		echo 'We can\'t make your note blank, we need you to actually write something in the note box. Please hit close and try again.';
	}
	echo '
<br /><br />
<div class="modal-link">
	<div class="modal-link-holder">
		<div class="modal-link-frame">
			<a class="btn" href="#" onclick="Modalbox.hide()"><span>Close &gt;</span></a>
		</div>
	</div>
</div>
	
</div>';	
	exit();
}
?>
