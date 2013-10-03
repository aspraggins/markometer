<?php # edit quote from within a modal box, 2nd box
//Programmer: Ray Neel 10/2009
require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.inc.php");
//Initialize a session if one not already in use:
if (session_id()=="") session_start();

echo '<div style="text-align:center;">';

if (isset($_GET['submitted'])) {
	//Test for something in each box:
	if(strlen($_GET['name'])>0 && strlen($_GET['amount'])>0) {
		//MySQL setup:
		require_once(MYSQL);
				
		$name = mysqli_real_escape_string($dbc, $_GET['name']); 
		$number = mysqli_real_escape_string($dbc, $_GET['number']);
		$amount = mysqli_real_escape_string($dbc, $_GET['amount']); 
		$description = mysqli_real_escape_string($dbc, $_GET['description']);
		$quoteid = mysqli_real_escape_string($dbc, $_GET['quoteid']);

		//Write to quotes table:
		$q = "UPDATE quotes " . 
		"SET name='$name', number='$number', amount='$amount', " . 
		"description = '$description', updateflag=1-updateflag " . 
		"WHERE id=$quoteid LIMIT 1";
		
		$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
		. mysqli_error($dbc));
		
		if (mysqli_affected_rows($dbc) == 1) {
			//lead info for note:
			$note = 'Quote Edited. \n';
			$note .= 'Name: ' . stripslashes($name) . '\n';
			$note .= 'Number: ' . stripslashes($number) . '\n';
			$note .= 'Amount: $' . stripslashes($amount) . '\n';
			$note .= 'Description: ' . stripslashes($description) . '\n';
			$username = $_SESSION['first_name'] . " " . $_SESSION['last_name'];
					
			$q = "INSERT INTO notes " . 
			"(note, lead_id, type, date_created, username) " . 
			"VALUES ('$note', '{$_SESSION['idlead']}', 'N', NOW(), '$username')";

			$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
			. mysqli_error($dbc));
		
			echo 'Your quote was updated and recorded in the notes for this lead.';			
		}
	} else {
		echo 'Quote name and amount fields are required (It wouldn\'t make a lot of sense otherwise).<br /><br /> Please hit close and try again.';
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
