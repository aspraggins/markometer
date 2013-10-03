<?php # edit "lost sale" info from within a modal box, 2nd box
//Programmer: Ray Neel 7/2011
require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.inc.php");
//Initialize a session if one not already in use:
if (session_id()=="") session_start();

echo '<div style="text-align:center;">';

if (isset($_GET['submitted'])) {
		//MySQL setup:
		require_once(MYSQL);

		$reason = mysqli_real_escape_string($dbc, $_GET['reason']; $notes = $_GET['notes']);
		$quoteid = mysqli_real_escape_string($dbc, $_GET['quoteid']);

		//Write to quotes table:
		$q = "UPDATE quotes " . 
		"SET lostsalereason='$reason', lostsalenotes='$notes', updateflag=1-updateflag " . 
		"WHERE id=$quoteid LIMIT 1";
		
		$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
		. mysqli_error($dbc));
		
		if (mysqli_affected_rows($dbc) == 1) {
			//lead info for note:
			$note = 'Lost Sale Info Edited. \n';
			$note .= 'Reason: ' . stripslashes($reason) . '\n';
			$note .= 'Notes: ' . stripslashes($notes) . '\n';
			$username = $_SESSION['first_name'] . " " . $_SESSION['last_name'];
					
			$q = "INSERT INTO notes " . 
			"(note, lead_id, type, date_created, username) " . 
			"VALUES ('$note', '{$_SESSION['idlead']}', 'N', NOW(), '$username')";

			$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
			. mysqli_error($dbc));
		
			echo 'This lost sale info has been updated and recorded in the notes for this lead.';			
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
