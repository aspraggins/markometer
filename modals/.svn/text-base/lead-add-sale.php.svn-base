<?php # mark a quote as "sold" from within a modal box
//Programmer: Ray Neel 10/2009
require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.inc.php");

//MYSQL Setup:
require_once(MYSQL);

//Build quote info:
$q = "SELECT name, number, amount, description " . 
"FROM quotes " . 
"WHERE id='{$_GET['id']}' " . 
"ORDER BY name";
		
$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

$row = mysqli_fetch_array($r,MYSQLI_ASSOC);
?>

<div style="text-align:center;">
<form action="/modals/lead-add-sale-done.php" method="get" id="soldform" onsubmit="return false;">

<p><strong>Original Quote Amount: $<?php echo $row['amount'];?></strong></p>

<label for="body"><strong>Sold Amount*</strong> (numbers only)</label><br />
$ <input type="text" name="soldamount" width="45" /><br /><br />

<label for="body"><strong>Sold Notes/Description</strong></label><br />
<textarea name="notes" cols="60" rows="7" /></textarea>

<p>(We'll also add this to your notes when you press "Mark as Sold")</p>
<div class="modal-link">
	<div class="modal-link-holder">
		<div class="modal-link-frame">
			<a href="#" class="btn" onclick="Modalbox.show('/modals/lead-add-sale-done.php', {title: 'New Sale Status', afterHide:function() { location.reload(true) }, width: 500, params:Form.serialize('soldform') }); document.getElementById('soldform').submit();" /><span>Mark as Sold &gt;</span></a>
			<em>or</em>
			<a href="#" onclick="Modalbox.hide()">Cancel</a>
		</div>
	</div>
</div>
<input type="hidden" name="quoteid" value="<?php echo $_GET['id'];?>" />
<input type="hidden" name="submitted" value="TRUE" />
</form>

<p><strong>* Sold Amount is Required</strong></p>

</div>
