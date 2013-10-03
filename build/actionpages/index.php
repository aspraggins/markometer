<?php # Build Action Pages Page
$metatitle = 'Create and Edit Your Action Pages';
$returnurl = '/login/?returnpage=build/actionpages';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Heading and Help Button -->

<?php
//MYSQL Setup:
require_once(MYSQL);
?>


<div class="heading">
<h1>Create &amp; Edit Your Action Pages
<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/current-account.php'); ?>
</h1>
<?php # Help Button
$helphref = 'build-action-pages.html';
$helptitle = 'Help with Creating and Editing Your Action Pages';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
?>
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

<?php
// Build Page:
//query for action pages:
$q = "SELECT action_pages.id, action_pages.name, action_pages.last_updated, " . "action_pages.date_created, forms.name AS formName " .   
"FROM action_pages LEFT JOIN forms " . 
"ON action_pages.form_id=forms.id " . 
"WHERE action_pages.account_id={$_SESSION['account_id']} " .
"AND action_pages.id>10 " . 
"ORDER BY action_pages.last_updated DESC";
		
		//echo $q;exit();
		
$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

if (mysqli_num_rows($r) > 0) {
	//Table header:
	echo '
<div class="main-block">
							
<div class="content-link top-link">
	<div class="link-holder">
		<div class="link-frame">
			<a class="btn" href="/build/actionpages/new/choose/"><span>Create a New Action Page &gt;</span></a>
		</div>
	</div>
</div>

<br/>

<div class="heading">
<h2>Your Existing Action Pages</h2>
</div>

<form action="#" id="archive_actionpages" name="archiveactionpagesform" class="main-form">
<fieldset>

<div class="main-box">

<div class="main-table">
	<table class="sortable">
		<thead>
			<tr>
				<!-- <th class="nosort"></th> -->
				<th class="sortcol sortfirstasc"><span>Action Page</span></th>
				<th class="sortcol"><span>Created</span></th>
				<th class="sortcol"><span>Last Updated</span></th>
				<th class="sortcol"><span>Form</span></th>
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
		<a href="/build/actionpages/edit/?id=' . $row['id'] . '">' . $row['name'] . '</a> 
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
		
		echo '<td>'. $row['formName'] . '</td>'; 

		echo '<td>' . $actionpageused . '</td></tr>'; 	
	}
	echo '
		</tbody>
	</table>
</div>

</div>

</fieldset>
</form>

</div>

	';
} else {
	echo '
<div class="main-block main-block-add">

<div class="content-link top-link">
	<div class="link-holder">
		<div class="link-frame">
			<a class="btn" href="/build/actionpages/new/choose/"><span>Create a New Action Page &gt;</span></a>
		</div>
	</div>
</div>

<br/>

<div class="heading">
<h2>Your Existing Action Pages</h2>
</div>

<p>Unfortunately you don\'t have any existing Action Pages. There\'s a really easy fix though. Just click the "Create a New Action Page" button right up there and you\'ll be on your way.</p>

</div>

	';
}

?>

<!-- / Main Content -->

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
