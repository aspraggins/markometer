<?php # edit quote details from within a modal box
//Programmer: Ray Neel 10/2009
require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.inc.php");

//MYSQL Setup:
require_once(MYSQL);

//Build query for quotes:
$q = "SELECT name, number, amount, description " . 
"FROM quotes " . 
"WHERE id='{$_GET['id']}' " . 
"ORDER BY name";
		
$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

$row = mysqli_fetch_array($r,MYSQLI_ASSOC);
?>

<div style="text-align:center;">
<form action="/modals/lead-edit-quote-done.php" method="get" id="quoteform" onsubmit="return false;">

<label for="body"><strong>Quote Name*</strong></label><br />
<input type="text" name="name" width="45" value="<?php echo $row['name'];?>" /><br /><br />

<label for="body"><strong>Quote Number</strong></label><br />
# <input type="text" name="number" width="45" value="<?php echo $row['number'];?>" /><br /><br />

<label for="body"><strong>Quote Amount*</strong> (numbers only)</label><br />
$ <input type="text" name="amount" width="45" value="<?php echo $row['amount'];?>" /><br /><br />

<label for="body"><strong>Quote Description</strong></label><br />
<textarea name="description" cols="60" rows="7" /><?php echo $row['description'];?></textarea>

<p>(We'll also update this in your notes when you press "Update Quote")</p>
<div class="modal-link">
	<div class="modal-link-holder">
		<div class="modal-link-frame">
			<a href="#" class="btn" onclick="Modalbox.show('/modals/lead-edit-quote-done.php', {title: 'Edit Quote Status', afterHide:function() { location.reload(true) }, width: 500, params:Form.serialize('quoteform') }); document.getElementById('quoteform').submit();" /><span>Update Quote &gt;</span></a>
			<em>or</em>
			<a href="#" onclick="Modalbox.hide()">Cancel</a>
		</div>
	</div>
</div>
<input type="hidden" name="quoteid" value="<?php echo $_GET['id'];?>" />
<input type="hidden" name="submitted" value="TRUE" />
</form>

<p><strong>* Quote Name and Quote Amount are Required</strong></p>

</div>
