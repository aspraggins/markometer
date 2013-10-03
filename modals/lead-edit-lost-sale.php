<?php # edit a "lost sale" from within a modal box
//Programmer: Ray Neel 7/2011
require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.inc.php");

//MYSQL Setup:
require_once(MYSQL);

//Build form info:
$q = "SELECT lostsalereason, lostsalenotes " . 
"FROM quotes " . 
"WHERE id='{$_GET['id']}' " . 
"ORDER BY name";
		
$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

$row = mysqli_fetch_array($r,MYSQLI_ASSOC);
?>

<div style="text-align:center;">
<form action="/modals/lead-edit-lost-sale-done.php" method="get" id="lostsaleform" onsubmit="return false;">

<label for="body"><strong>Summary of Reason</label><br />
<input type="text" name="reason" width="45" value="<?php echo $row['lostsalereason'];?>" /><br /><br />

<label for="body"><strong>Notes/Description</strong></label><br />
<textarea name="notes" cols="60" rows="7" /><?php echo $row['lostsalenotes'];?></textarea>

<p>(We'll also add this to your notes when you press "Update Lost Sale Info")</p>
<div class="modal-link">
	<div class="modal-link-holder">
		<div class="modal-link-frame">
			<a href="#" class="btn" onclick="Modalbox.show('/modals/lead-edit-lost-sale-done.php', {title: 'Edit Lost Sale Status', afterHide:function() { location.reload(true) }, width: 500, params:Form.serialize('lostsaleform') }); document.getElementById('lostsaleform').submit();" /><span>Update Lost Sale Info &gt;</span></a>
			<em>or</em>
			<a href="#" onclick="Modalbox.hide()">Cancel</a>
		</div>
	</div>
</div>
<input type="hidden" name="quoteid" value="<?php echo $_GET['id'];?>" />
<input type="hidden" name="submitted" value="TRUE" />
</form>

</div>
