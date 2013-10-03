<?php # write note from within a modal box, 2nd box
//Programmer: Ray Neel 1/2009, revising 8/2009

require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.inc.php");
//Initialize a session if one not already in use:
if (session_id()=="") session_start();

echo '<div style="text-align:center">';

if (isset($_GET['submitted'])) {
	//Test for something in each box:
	if(strlen($_GET['body'])>0) {
		$formatteddate = date("Y-m-d", strtotime($_GET['month'].'/'.$_GET['day'].'/'.$_GET['year']));
		$formattedtime = date("H:i:s");
		$tstamp = $formatteddate . ' ' . $formattedtime;	
			
		//Write to notes in lead table:
		//MySQL setup:
		require_once(MYSQL);
		$notesmsg = mysqli_real_escape_string($dbc, $_GET['body']);
		$username = $_SESSION['first_name'] . " " . $_SESSION['last_name'];
			
		$q = "INSERT INTO notes (note, lead_id, type, last_updated, date_created, username) " . 
		"VALUES('$notesmsg', '{$_SESSION['idlead']}', 'N', NOW(), '" . $tstamp . "', '$username')";

		$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
		. mysqli_error($dbc));
		
		if (mysqli_affected_rows($dbc) == 1) {
			echo 'Your note was added and recorded in the notes for this lead.';			
		}
	} else {
		echo 'This is going to sound crazy, we know, but before we can add a note you actually need to write something in the note box. Please hit close and try again.';
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
