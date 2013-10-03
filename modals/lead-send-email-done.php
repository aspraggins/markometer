<?php # send_email functionality from within a modal box, 2nd box
//Programmer: Ray Neel 1/2009, revised 8/2009 for use with leads/manage

require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.inc.php");
//Initialize a session if one not already in use:
if (session_id()=="") session_start();

echo '<div style="text-align:center">';

if (isset($_GET['submitted'])) {
	//Test for something in each box:
	if(strlen($_GET['subject'])>0 && strlen($_GET['body'])>0) {
		//Strip slashed and Send email:
		$subject = stripslashes($_GET['subject']); $body = stripslashes($_GET['body']);
		
		//open template for this email:
		$fp = @fopen($_SERVER["DOCUMENT_ROOT"]."/templates/emails/leadmail.txt", "rb") or die("Couldn't open file");
		$data = '';
		while(!feof($fp)) {
			$data .= fgets($fp, 1024);
		}
		fclose($fp); 

		//Process $data to replace tags ([fname] and [recipient]) with strings:
		$data = str_replace("[fname]", $_SESSION['first_name'], $data);
		$data = str_replace("[recipient]", $_SESSION['emailSend'], $data);
		$data = str_replace("[content]", $body, $data);

		if($_SESSION['privatelabel']=='Y') {
				$from = 'From:' . str_replace(" ","_",$_SESSION['master_account_name']) . '@mp41.com';
		} else {
				$from = 'From:Markometer@mp41.com';
		}
		
		$fromme = "From:" . $_SESSION['email'];
		$sent = mail($_SESSION['emailSend'], $subject, $body, $fromme);
		
		//Send copy to user if he requested it with the checkbox:s
		if(isset($_GET['copy'])) $sentuser = mail($_SESSION['email'], $subject, $data, $from);
		if($sent) {
			echo 'The message was successfully sent ';
			//Write to notes in lead table:
			//MySQL setup:
			require_once(MYSQL);
			$notesmsg = 'Subject: ' . mysqli_real_escape_string($dbc, $_GET['subject']) . '\n\n ' . mysqli_real_escape_string($dbc, $_GET['body']);
			$username = $_SESSION['first_name'] . " " . $_SESSION['last_name'];

			$q = "INSERT INTO notes (note, lead_id, type, last_updated, date_created, username) " . 
			"VALUES('$notesmsg', '{$_SESSION['idlead']}', 'E', NOW(), NOW(), '$username')";
			
			$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
			. mysqli_error($dbc));
			
			if (mysqli_affected_rows($dbc) == 1) {
				echo 'and we also recorded it in the notes for this lead.';			
			}
		} else {
			echo 'There was a problem delivering this message. Please try again later.';
		}
	} else {
		echo 'You need a subject and a body in your email before we\'ll send it. We don\'t want to be all spammy. Hit close and try again.';
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
