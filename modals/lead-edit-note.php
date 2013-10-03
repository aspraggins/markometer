<?php # edit note from within a modal box,
//Programmer: Ray Neel 1/2009

require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.inc.php");

//MySQL setup:
require_once(MYSQL);

//Query for note content:
$q = "SELECT note " . 
"FROM notes " . 
"WHERE id='{$_REQUEST['id']}'";

$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
. mysqli_error($dbc));

if (mysqli_affected_rows($dbc) == 1) {
	echo '
<div style="text-align:center;">
<form action="/modals/lead-edit-note-done.php" method="get" id="noteform" onsubmit="return false;">
<label for="body"><strong>Edit Note</strong></label><br />
';
// Create textarea, populate with note that was selected to edit:
$row = mysqli_fetch_array($r,MYSQLI_ASSOC);
echo '
<textarea name="body" id="body" cols="60" rows="7">' . $row['note'] . '</textarea>
';
} else {
	echo 'You have reached this page in error. Please hit close and try again.';
	exit();
}
?>
<br /><br />
<div class="modal-link">
	<div class="modal-link-holder">
		<div class="modal-link-frame">
			<a href="#" class="btn" onclick="Modalbox.show('/modals/lead-edit-note-done.php', {title: 'Edit Note Status', afterHide:function() { location.reload(true) }, width: 500, params:Form.serialize('noteform') }); document.getElementById('noteform').submit();" /><span>Update Note &gt;</span></a>
			<em>or</em>
			<a href="#" onclick="Modalbox.hide()">Cancel</a>
		</div>
	</div>
</div>
<?php echo '<input type="hidden" name="leadid" value="' . $_REQUEST['id'] . '" />'; ?>
<input type="hidden" name="submitted" value="TRUE" />
</form>

</div>
