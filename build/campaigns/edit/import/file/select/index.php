<?php # Import Leads File into Campaign:
// Ray Neel - 5/2011
$metatitle = 'Import Leads into Your Existing Campaign';
$returnurl = '/login/?returnpage=build/campaigns';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Heading and Help Button -->

<div class="heading">
<h1>Import Leads Into Your Existing Campaign
<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/current-account.php'); ?>
</h1>
</div>

<!-- Main Content -->
<div class="content-items content-steps">
	
<?php 
//get campaign id number:
if(isset($_GET['id'])) $campaign_id = $_GET['id'];
//File Import:
$file_open = 0;
echo'
<form enctype="multipart/form-data" action="/build/campaigns/edit/import/file/map/?id=' . $campaign_id . '" method="post" id="import_leads_upload" class="steps-form">
<fieldset>
	
<div class="row">
	<div class="steps-holder">
		<div class="heading">
			<h2>Select a .CSV File on Your Computer to Upload</h2>
';
			# Help Button
			$helphref = 'build-campaigns-import-file-select.html';
			$helptitle = 'Help with Selecting a File to Import';
			include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
echo '
		</div>
		<div class="text-bar">
			<input name="csvfile" type="file" class="text small" />
		</div>
		<span class="caption">(You can create a .CSV file using a program like Excel or you can <a href="#">download a sample file here</a> and fill in your own information)</span>
	</div>
</div>

';
if(isset($_GET['id'])) $campaign_id = $_GET['id'];
$starturl = '/build/campaigns/edit/?id=' . $campaign_id;
echo'

<div class="content-link">
	<div class="link-holder">
		<div class="link-frame">
			<a href="#" class="btn" onclick="document.getElementById(\'import_leads_upload\').submit();" /><span>Upload Leads &amp; Go To Next Step &gt;</span></a>
			<em>or</em>
			<a href="' . $starturl  . '">Cancel</a>
		</div>
	</div>
</div>

<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
<input type="hidden" name="option" value="yes" /> 

</fieldset>
</form>
';

?>

</div>
<br/><br/>

<!-- / Main Content -->

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
