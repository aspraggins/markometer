<?php # mark quote as being sold, 2nd box to process request
//Programmer: Ray Neel 10/2009
require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.inc.php");
//Initialize a session if one not already in use:
if (session_id()=="") session_start();

echo '<div style="text-align:center">';

if (isset($_GET['submitted'])) {
	//Test for something in sold amount box:
	if(strlen($_GET['soldamount'])>0) {
		
		//MySQL setup:
		require_once(MYSQL);
		$soldamount = mysqli_real_escape_string($dbc, $_GET['soldamount']); 
		$notes = mysqli_real_escape_string($dbc, $_GET['notes']);
		$quoteid = mysqli_real_escape_string($dbc, $_GET['quoteid']);
		
		//Write to quotes table:			
		$q = "UPDATE quotes " . 
		"SET soldamount='$soldamount', notes='$notes', updateflag=1-updateflag, " . 
		"sold='Y' " . 
		"WHERE id=$quoteid LIMIT 1";
		
		$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
		. mysqli_error($dbc));
		
		if (mysqli_affected_rows($dbc) == 1) {
			//set "Sold" flag in lead record:
			$q = "UPDATE leads SET stage='SD' WHERE idlead='{$_SESSION['idlead']}'";
		
			$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
			. mysqli_error($dbc));
		
			//lead info for note:
			$note = 'Quote Marked As Sold. \n';
			$note .= 'Amount: $' . stripslashes($soldamount) . '\n';
			$note .= 'Notes: ' . stripslashes($notes) . '\n';
			$username = $_SESSION['first_name'] . " " . $_SESSION['last_name'];
					
			$q = "INSERT INTO notes " . 
			"(note, lead_id, type, date_created, username) " . 
			"VALUES ('$note', '{$_SESSION['idlead']}', 'N', NOW(), '$username')";

			$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
			. mysqli_error($dbc));
		
			echo 'This quote has been marked as sold and recorded in the notes for this lead. Congratulations!';			
		}
	} else {
		echo 'Sold amount field is required (It wouldn\'t make a lot of sense otherwise).<br /><br /> Please hit close and try again';
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
