<?php # mark quote as being a lost sale, 2nd box to process request
//Programmer: Ray Neel 10/2009
require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.inc.php");
//Initialize a session if one not already in use:
if (session_id()=="") session_start();

echo '<div style="text-align:center">';

if (isset($_GET['submitted'])) {
	//MySQL setup:
	require_once(MYSQL);
	
	$reason = mysqli_real_escape_string($dbc, $_GET['reason']); 
	$notes = mysqli_real_escape_string($dbc, $_GET['notes']);
	$quoteid = mysqli_real_escape_string($dbc, $_GET['quoteid']);
	
	//Write to quotes table:		
	$q = "UPDATE quotes " . 
	"SET lostsalereason='$reason', lostsalenotes='$notes', updateflag=1-updateflag, " . 
	"sold='L' " . 
	"WHERE id=$quoteid LIMIT 1";
	
	$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
	. mysqli_error($dbc));
	
	if (mysqli_affected_rows($dbc) == 1) {
		//lead info for note:
		$note = 'Quote Marked As Lost Sale. \n';
		$note .= 'Reason: ' . stripslashes($reason) . '\n';
		$note .= 'Notes: ' . stripslashes($notes) . '\n';
		$username = $_SESSION['first_name'] . " " . $_SESSION['last_name'];
				
		$q = "INSERT INTO notes " . 
		"(note, lead_id, type, date_created, username) " . 
		"VALUES ('$note', '{$_SESSION['idlead']}', 'N', NOW(), '$username')";

		$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
		. mysqli_error($dbc));
	
		echo 'Your quote has been marked as a lost sale and recorded in the notes for this lead.<br /><br/ >Sorry it didn\'t work out. We hope to have some tools soon that will help you better analyze your lost sales so you can see what\'s going on.';			
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
