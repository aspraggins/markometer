<?php # edit costs from within a modal box,
//Programmer: Ray Neel 7/2009

require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.inc.php");

//MySQL setup:
require_once(MYSQL);

//Query for note content:
$q = "SELECT cost, actual_cost " . 
"FROM campaigns " . 
"WHERE id='{$_REQUEST['id']}'";

$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
. mysqli_error($dbc));

echo '<div style="text-align:center;">';

if (mysqli_affected_rows($dbc) == 1) {
	echo '<form action="/modals/track-individual-campaign-edit-cost-done.php" method="get" id="costsform" onsubmit="return false;">';
	//retrieve costs from table hit:
	$row = mysqli_fetch_array($r,MYSQLI_ASSOC);
	
	echo '<label for="budgeted"><strong>Budgeted Cost</strong> (Numbers only)</label><br />';
	echo '$ <input type="text" name="budgeted" id="budgeted" size="25" maxlength="25" value="' . $row['cost'] . '"><br /><br />';
	echo '<label for="actual"><strong>Actual Cost</strong> (Numbers only)</label><br />';
	echo '$ <input type="text" name="actual" id="actual" size="25" maxlength="25" value="' . $row['actual_cost'] . '"><br /><br />';
} else {
	echo 'You have reached this page in error!';
	exit();
}
?>
<div class="modal-link">
	<div class="modal-link-holder">
		<div class="modal-link-frame">
			<a href="#" class="btn" onclick="Modalbox.show('/modals/track-individual-campaign-edit-cost-done.php', {title: 'Update Your Campaign Costs', afterHide:function() { location.reload(true) }, width: 500, params:Form.serialize('costsform') }); document.getElementById('costsform').submit();" /><span>Update Costs &gt;</span></a>
			<em>or</em>
			<a href="#" onclick="Modalbox.hide()">Cancel</a>
		</div>
	</div>
</div>
<?php 
echo '<input type="hidden" name="cid" value="' . $_REQUEST['id'] . '" />'; 
//hidden variables to pass existing values before change made:
echo '<input type="hidden" name="prioractual" value="' . $row['actual_cost'] . '" />'; 
echo '<input type="hidden" name="priorbudgeted" value="' . $row['cost'] . '" />'; 
?>
<input type="hidden" name="submitted" value="TRUE" />
</form>

</div>
