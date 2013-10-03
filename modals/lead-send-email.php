<?php # send_email functionality from within a modal box
//Programmer: Ray Neel 1/2009 revised 8/2009
//Initialize a session if one not already in use:
if (session_id()=="") session_start();
$_SESSION['emailSend'] = $_GET['email'];
$_SESSION['idlead'] = $_GET['idlead'];
?>
<div style="text-align:center;">
<form action="/modals/lead-send-email-done.php" method="get" id="emailform" onsubmit="return false;">

<label for="subject"><strong>Subject</strong></label><br />
<input type="text" name="subject" id="subject" size="45" maxlength="125" value=""><br /><br />
<label for="body"><strong>Message</strong></label><br />
<textarea name="body" id="body" cols="60" rows="7"></textarea><br /><br />
<input type="checkbox" name="copy" id="copy" value="TRUE" />
<label for="copy">Send Me a Copy (<?php echo $_SESSION['email'];?>)</label><br />
<p>(We'll also add this to your notes when you press "Send Email")</p>
<div class="modal-link">
	<div class="modal-link-holder">
		<div class="modal-link-frame">
			<a href="#" class="btn" onclick="Modalbox.show('/modals/lead-send-email-done.php', {title: 'Email Sending Status', afterHide:function() { location.reload(true) }, width: 500, params:Form.serialize('emailform') }); document.getElementById('emailform').submit();" /><span>Send Email &gt;</span></a>
			<em>or</em>
			<a href="#" onclick="Modalbox.hide()">Cancel</a>
		</div>
	</div>
</div>
<input type="hidden" name="submitted" value="TRUE" />
</form>

</div>
