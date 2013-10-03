<?php # add a quote from within a modal box
//Programmer: Ray Neel 9/2009
//Initialize a session if one not already in use:
if (session_id()=="") session_start();
$_SESSION['idlead'] = $_GET['idlead'];
?>
<div style="text-align:center;">
<form action="/modals/lead-add-quote-done.php" method="get" id="quoteform" onsubmit="return false;">

<!--
<table border="0" style="text-align:center;" width="95%" cellspacing="5" cellpadding="5">
<tbody>
	<tr>
		<td style="text-align:right;"><label for="body"><strong>Quote Name*</strong></label></td>
		<td style="text-align:left;"><input type="text" name="name" width="60" /></td>
	</tr>
	<tr>
		<td style="text-align:right;"><label for="body"><strong>Quote Number</strong></label></td>
		<td style="text-align:left;"># <input type="text" name="number" width="60" /></td>
	</tr>
	<tr>
		<td style="text-align:right;"><label for="body"><strong>Quote Amount*</strong></label></td>
		<td style="text-align:left;">$ <input type="text" name="amount" width="60" /</td>
	</tr>
	<tr>
		<td style="text-align:right;"><label for="body"><strong>Quote Description</strong></label></td>
		<td style="text-align:left;"><textarea name="description" cols="40" rows="7" /></textarea></td>
	</tr>
</tbody>
</table>
-->

<label for="body"><strong>Quote Name*</strong></label><br />
<input type="text" name="name" width="45" /><br /><br />

<label for="body"><strong>Quote Number</strong></label><br />
# <input type="text" name="number" width="45" /><br /><br />

<label for="body"><strong>Quote Amount*</strong> (numbers only)</label><br />
$ <input type="text" name="amount" width="45" /><br /><br />

<label for="body"><strong>Quote Description</strong></label><br />
<textarea name="description" cols="60" rows="7" /></textarea>



<p>(We'll also add this to your notes when you press "Add Quote")</p>
<div class="modal-link">
	<div class="modal-link-holder">
		<div class="modal-link-frame">
			<a href="#" class="btn" onclick="Modalbox.show('/modals/lead-add-quote-done.php', {title: 'New Quote Status', afterHide:function() { location.reload(true) }, width: 500, params:Form.serialize('quoteform') }); document.getElementById('quoteform').submit();" /><span>Add Quote &gt;</span></a>
			<em>or</em>
			<a href="#" onclick="Modalbox.hide()">Cancel</a>
		</div>
	</div>
</div>
<input type="hidden" name="submitted" value="TRUE" />
</form>

<p><strong>* Quote Name and Quote Amount are Required</strong></p>

</div>
