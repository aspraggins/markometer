<p><strong>If you aren't comfortable designing your own action page or don't have the time to do it, we'll be glad to help.</strong></p>
<p><strong>Details:</strong></p>
<ul>
	<li><strong>Cost</strong> - A $499, one-time charge will be incurred for our design services when you fill out the form below.</li>
	<li><strong>Design</strong> - We'll contact you based on the info you leave below. Please have ideas for copy and any images you'd like to use ready to send us.</li>
	<li><strong>Revisions</strong> - We'll create the first concept for you and then we'll make two rounds of revisions for you so you can get the page exactly right.</li>
	<li><strong>Ownership</strong> - Once we're done it's your page - you own it. You can reuse it and edit it as much as you like.</li>	
</ul>
<p>To get started just fill out the form below and we'll contact you ASAP to get started. If you'd rather not just click "Cancel" below.</p>
<br />

<?php # Let us design your action page form
# Can we grab their account ID, username, user email and pass it to us so they don't have to fill it out?

echo '
<div style="text-align:center;">
<form action="" method="get" id="designform" onsubmit="return false;">
<label for="email"><strong>Your Email:</strong></label><br />
<input type="text" name="email" id="budgeted" size="25" maxlength="25" value="' . $row['email'] . '"><br /><br />
<label for="actual"><strong>Your Phone</strong></label><br />
<input type="text" name="phone" id="actual" size="25" maxlength="25" value="' . $row['phone'] . '"><br /><br />
Contact me via: <input type="radio" name="contact_via" value="email" id="contact_via_email" class="check-2" />
<label for="contact_via_email"><strong>Email</strong></label> &nbsp; &nbsp; &nbsp;<input type="radio" name="contact_via" value="phone" id="contact_via_phone" class="check-2" />
<label for="contact_via_phone"><strong>Phone</strong></label>
<br/><br/>
<input type="checkbox" name="finalize" value="Y" id="agree_to_charges" class="check-2" />
<label for="agree_to_charges"><strong>Yes, I agree</strong> that when I click the "Order My Action Page" button I will be billed <strong>a one-time charge of $499</strong> for the action page design services and that I agree to the conditions above.</label>
';
?>
<br/><br/>
<div class="modal-link">
	<div class="modal-link-holder">
		<div class="modal-link-frame">
			<a href="#" class="btn" onclick="Modalbox.show('/modals/design-action-page-template-confirm.php', {title: 'Confirm Your Order', afterHide:function() { history.go(0) }, width: 500, params:Form.serialize('designform') }); document.getElementById('designform').submit();" /><span>Order My Action Page &gt;</span></a>
			<em>or</em>
			<a href="#" onclick="Modalbox.hide()">Cancel</a>
		</div>
	</div>
</div>
<?php echo '<input type="hidden" name="cid" value="' . $_REQUEST['id'] . '" />'; ?>
<input type="hidden" name="submitted" value="TRUE" />
</form>

</div>
