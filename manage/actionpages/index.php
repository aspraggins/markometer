<?php # Manage Action Pages Page
$metatitle = 'Manage Action Pages';
$returnurl = '/login/?returnpage=manage/actionpages';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Heading and Help Button -->

<?php
//MYSQL Setup:
require_once(MYSQL);


?>


<div class="heading">
<h1>Manage Your Action Pages
<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/current-account.php'); ?>
</h1>
<a href="/help/manage-action-pages.html" class="btn-help" title="Help" onclick="Modalbox.show(this.href, {title: this.title, width: 600, evalScript: true}); return false;"><span>Help?</span></a>
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
			<p>Your action page changes were successfully saved!</p>
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
			<p>Your new action page has been successfully created!</p>
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
			<a class="btn" href="/create/actionpage/choose/"><span>Create a New Action Page &gt;</span></a>
		</div>
	</div>
</div>

<br/>

<div class="heading">
<!-- <h2>Active &amp; Current Action Pages</h2>
	<a href="/help/manage-active-action-pages.html" class="btn-help" title="Help" onclick="Modalbox.show(this.href, {title: this.title, width: 600, evalScript: true}); return false;"><span>Help?</span></a> -->
<h2>All Action Pages</h2>
</div>

<form action="#" id="archive_actionpages" name="archiveactionpagesform" class="main-form">
<fieldset>

<div class="main-box">

<?php
// Build Page:
// Build query for action pages:
$q = "SELECT id, name, last_updated, date_created " .   
"FROM  action_pages " . 
"WHERE account_id={$_SESSION['account_id']} " .
"AND id>10 " . 
"ORDER BY last_updated DESC";
	
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
				<th class="sortcol sortfirstasc"><span>Action Page</span></th>
				<th class="sortcol"><span>Created</span></th>
				<th class="sortcol"><span>Last Updated</span></th>
				<th class="sortcol"><span>Used In Campaign?</span></th>
			</tr>
		</thead>
		<tbody>
'; 	

	//Fetch records, build table:
	while ($row = mysqli_fetch_array($r,MYSQLI_ASSOC)) {
		//Determine if this action page is used in a campaign:
		$actionpageid = $row['id'];
		$q = "SELECT action_page FROM campaigns WHERE action_page='$actionpageid'";
		
		$r2 = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
		mysqli_error($dbc));

		$actionpageused = 'No';
		if (mysqli_num_rows($r2) > 0) $actionpageused = 'Yes';

		//Generate table:
		echo '
		<tr><!-- <td class="check-row"><input type="checkbox" class="check" /></td> -->
		<td> 
		<a href="/manage/actionpages/edit/?id=' . $row['id'] . '">' . $row['name'] . '</a> 
		</td>
		<td>' . date('n/j/y',strtotime($row['date_created'])) . '</td>';
		// calculate how many days ago since updated, format:
		if(strtotime(date('Y-m-d'))-strtotime($row['last_updated'])>86400) {
			$datediff = round((strtotime(date('Y-m-d'))-strtotime($row['last_updated']))/86400);
		} else {
			$datediff = 0;
		}
		$lastupdated = $datediff . ' Days Ago';		
		echo '<td sorttable_customkey="' . $datediff . 
		'">' . $lastupdated . '</td>';

		echo '<td>' . $actionpageused . '</td></tr>'; 	
	}
	echo '
		</tbody>
	</table>
</div>

<!--
	<div class="content-link">
		<div class="link-holder">
			<div class="link-frame">
				<a href="#" class="btn" onclick="document.getElementById(\'archive_actionpages\').submit()" /><span>Archive Selected Action Pages &gt;</span></a>
			</div>
		</div>
	</div>
-->
	
	';
} else {
	echo 'No Action Pages Found!';
}

?>

</div>

</fieldset>
</form>

<!--

<div class="heading">
<h2>Archived Action Pages</h2>
	<a href="/help/manage-archived-action-pages.html" class="btn-help" title="Help" onclick="Modalbox.show(this.href, {title: this.title, width: 600, evalScript: true}); return false;"><span>Help?</span></a>
</div>

<form action="#" id="unarchive_actionpages" name="unarchiveactionpagesform" class="main-form">
<fieldset>

<div class="main-box">

<div class="main-table">
	<table class="inactive sortable">
		<thead>
			<tr>
				<th class="nosort"></th>
				<th class="sortcol"><span>Action Page</span></th>
				<th class="sortcol"><span>Created</span></th>
				<th class="sortcol"><span>Last Updated</span></th>
				<th class="sortcol"><span>Used In Campaign?</span></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="check-row"><input type="checkbox" class="check" /></td>
				<td>
					<a href="#">Packaging World 2007 Print Ad</a>
				</td>
				<td>11/27/2007</td>
				<td>380 Days Ago</td>
				<td>No</td>
			</tr>
			<tr class="">
				<td class="check-row"><input type="checkbox" class="check" /></td>
				<td>
					<a href="#">Yahoo Pay-Per-Click Page</a>
				</td>
				<td>2/13/2007</td>
				<td>460 Days Ago</td>
				<td>No</td>
			</tr>
			<tr>
				<td class="check-row"><input type="checkbox" class="check" /></td>
				<td>
					<a href="#">Shreveport Times Ad</a>
				</td>
				<td>5/17/2007</td>
				<td>521 Days Ago</td>
				<td>No</td>
			</tr>
			<tr class="">
				<td class="check-row"><input type="checkbox" class="check" /></td>
				<td>
					<a href="#">WNYC Radio Ad</a>
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
				<a href="#" class="btn" onclick="document.getElementById('unarchive_actionpages').submit()" /><span>Unarchive Selected Action Pages &gt;</span></a>
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
