<?php # mark a quote as "lost sale" from within a modal box
//Programmer: Ray Neel 10/2009
?>

<div style="text-align:center;">
<form action="/modals/lead-add-lost-sale-done.php" method="get" id="quoteform" onsubmit="return false;">

<label for="body"><strong>Summary of Reason</strong></label><br />
<input type="text" name="reason" width="45" /><br /><br />

<label for="body"><strong>Notes/Description</strong></label><br />
<textarea name="notes" id="body" cols="60" rows="7"></textarea>

<br /><br />
<div class="modal-link">
	<div class="modal-link-holder">
		<div class="modal-link-frame">
			<a href="#" class="btn" onclick="Modalbox.show('/modals/lead-add-lost-sale-done.php', {title: 'Lost Sale Status', afterHide:function() { location.reload(true) }, width: 500, params:Form.serialize('quoteform') }); document.getElementById('quoteform').submit();" /><span>Mark as Lost Sale &gt;</span></a>
			<em>or</em>
			<a href="#" onclick="Modalbox.hide()">Cancel</a>
		</div>
	</div>
</div>
<input type="hidden" name="quoteid" value="<?php echo $_GET['id'];?>" />
<input type="hidden" name="submitted" value="TRUE" />
</form>

</div>
