<?php # Manage Forms Page
$metatitle = 'Manage Forms';
$returnurl = '/login/?returnpage=manage/forms';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Heading and Help Button -->

<div class="heading">
<h1>Manage Your Forms
<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/current-account.php'); ?>
</h1>
<a href="/help/manage-forms.html" class="btn-help" title="Help" onclick="Modalbox.show(this.href, {title: this.title, width: 600, evalScript: true}); return false;"><span>Help?</span></a>
</div>

<!-- Main Content -->

<?php
//MYSQL Setup:
require_once(MYSQL);

if (isset($_REQUEST['saved'])) {
	if ($_REQUEST['saved']=='yes') {
		echo '
<div class="alert-holder">
	<div class="alert-box green">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>Your form changes were successfully saved!</p>
			</div>
		</div>
	</div>
</div>';
	}
}

if (isset($_REQUEST['new'])) {
	if ($_REQUEST['new']=='added') {
		echo '
<div class="alert-holder">
	<div class="alert-box green">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>Your new form has been successfully created!</p>
			</div>
		</div>
	</div>
</div>';
	}
}

?>

<div class="main-block">
							
<div class="content-link top-link">
	<div class="link-holder">
		<div class="link-frame">
			<a class="btn" href="/create/form/choose/"><span>Create a New Form &gt;</span></a>
		</div>
	</div>
</div>

<br/>

<div class="heading">
<!-- <h2>Active &amp; Current Forms</h2>
	<a href="/help/manage-active-forms.html" class="btn-help" title="Help" onclick="Modalbox.show(this.href, {title: this.title, width: 600, evalScript: true}); return false;"><span>Help?</span></a> -->
<h2>All Forms</h2>
</div>

<form action="#" id="archive_forms" name="archiveformsform" class="main-form">
<fieldset>

<div class="main-box">

<?php

//Build Page:
//Build query for forms:
$q = "SELECT id, name, date_modified, date_created, form_id " .   
"FROM forms " . 
"WHERE account_id={$_SESSION['account_id']} " .
"AND id>10 " . 
"ORDER BY date_modified DESC";
	
$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

if (mysqli_num_rows($r) > 0) {
	//Table header:
	echo '
<div class="main-table">
	<table class="sortable">
		<thead>
			<tr>
				<!-- <th class="nosort"></th> -->
				<th class="sortcol sortfirstasc"><span>Form</span></th>
				<th class="sortcol"><span>Created</span></th>
				<th class="sortcol"><span>Last Updated</span></th>
				<th class="sortcol"><span>Used In Action Page?</span></th>
			</tr>
		</thead>
		<tbody>	
';

	//Fetch records, build table:
	while ($row = mysqli_fetch_array($r,MYSQLI_ASSOC)) {
		//Determine if this action page is used in a campaign:
		$formid = $row['form_id'];
		$q = "SELECT action_pages.form_id " . 
		"FROM action_pages " . 
		"INNER JOIN campaigns ON action_pages.id=campaigns.action_page " . 
		"WHERE action_pages.form_id='$formid'";
		
		$r2 = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
		mysqli_error($dbc)); 

		$formused = 'No';
		if (mysqli_num_rows($r2) > 0) $formused = 'Yes';

		//Generate table:
		echo '
		<tr><!-- <td class="check-row"><input type="checkbox" class="check" /></td> -->
		<td> 
		<a href="/manage/forms/edit/?formID=' . $row['form_id'] . '&name=' . $row['name'] . '">' . $row['name'] . '</a> 
		</td>
		<td>' . date('n/j/y',strtotime($row['date_created'])) . '</td>';
		
		// calculate how many days ago since updated, format:
		if(strtotime(date('Y-m-d'))-strtotime($row['date_modified'])>86400) {
			$datediff = round((strtotime(date('Y-m-d'))-strtotime($row['date_modified']))/86400);
		} else {
			$datediff = 0;
		}
		$lastupdated = $datediff . ' Days Ago';		
		echo '<td sorttable_customkey="' . $datediff . 
		'">' . $lastupdated . '</td>';

		echo '<td>' . $formused . '</td></tr>'; 	
	}
	echo '
		</tbody>
	</table>
</div>

<!--
	<div class="content-link">
		<div class="link-holder">
			<div class="link-frame">
				<a href="#" class="btn" onclick="document.getElementById(\'archive_forms\').submit()" /><span>Archive Selected Forms &gt;</span></a>
			</div>
		</div>
	</div>
-->
	
	';
} else {
	echo 'No Forms Found!';
}

?>

</div>

</fieldset>
</form>

<!--

<div class="heading">
<h2>Archived Forms</h2>
	<a href="/help/manage-archived-forms.html" class="btn-help" title="Help" onclick="Modalbox.show(this.href, {title: this.title, width: 600, evalScript: true}); return false;"><span>Help?</span></a>
</div>

<form action="#" id="unarchive_forms" name="unarchiveformsform" class="main-form">
<fieldset>

<div class="main-box">

<div class="main-table">
	<table class="inactive sortable">
		<thead>
			<tr>
				<th class="nosort"></th>
				<th class="sortcol"><span>Form</span></th>
				<th class="sortcol"><span>Created</span></th>
				<th class="sortcol"><span>Last Updated</span></th>
				<th class="sortcol"><span>Used In Action Page?</span></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="check-row"><input type="checkbox" class="check" /></td>
				<td>
					<a href="#">Packaging World 2007 Print Ad Form</a>
				</td>
				<td>11/29/2007</td>
				<td>378 Days Ago</td>
				<td>No</td>
			</tr>
			<tr class="">
				<td class="check-row"><input type="checkbox" class="check" /></td>
				<td>
					<a href="#">Yahoo Pay-Per-Click Page Form</a>
				</td>
				<td>2/15/2007</td>
				<td>458 Days Ago</td>
				<td>No</td>
			</tr>
			<tr>
				<td class="check-row"><input type="checkbox" class="check" /></td>
				<td>
					<a href="#">Shreveport Times Form</a>
				</td>
				<td>5/17/2007</td>
				<td>521 Days Ago</td>
				<td>No</td>
			</tr>
			<tr class="">
				<td class="check-row"><input type="checkbox" class="check" /></td>
				<td>
					<a href="#">WNYC Radio Ad Form</a>
				</td>
				<td>2/5/2007</td>
				<td>465 Days Ago</td>
				<td>No</td>
			</tr>
		<tbody>
	</table>
</div>

	<div class="content-link">
		<div class="link-holder">
			<div class="link-frame">
				<a href="#" class="btn" onclick="document.getElementById('unarchive_forms').submit()" /><span>Unarchive Selected Forms &gt;</span></a>
			</div>
		</div>
	</div>
</div>

</fieldset>
</form>

-->

</div>

<!-- / Main Content -->

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
