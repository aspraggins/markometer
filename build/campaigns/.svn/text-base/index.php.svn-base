<?php # Manage Campaigns Page
$metatitle = 'Create and Edit Your Campaigns';
$returnurl = '/login/?returnpage=build/campaigns';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Heading and Help Button -->

<div class="heading">
<h1>Create &amp; Edit Your Marketing Campaigns
<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/current-account.php'); ?>
</h1>
<?php # Help Button
$helphref = 'build-campaigns.html';
$helptitle = 'Help with Building Your Marketing Campaigns';
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
			<p>Your campaign changes were successfully saved!</p>
			</div>
		</div>
	</div>
</div>';
	}
}

if (isset($_REQUEST['action'])) {
	if ($_REQUEST['action']=='pagechosen') {
		echo '
<div class="alert-holder">
	<div class="alert-box green">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>Congratulations! You have successfully chosen an action page for your new campaign.</p>
			</div>
		</div>
	</div>
</div>';
	}
}

// Evaluate Submission of Summary Form:
if (isset($_POST['submitted'])) {
	//Assign variables:
	$actionpage = $_POST['actionpages'];
	$form = $_POST['forms'];
	$id = $_GET['id'];
	$campaignupdate = $actionpageupdate = FALSE;
	
	$q = "UPDATE campaigns " . 
	"SET updateflag=1-updateflag, action_page='$actionpage', last_updated=NOW() " . 
	"WHERE id='$id' LIMIT 1";

	$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
	Error: " . mysqli_error ($dbc));

	if (mysqli_affected_rows ($dbc) == 1) {		
		$campaignupdate = TRUE;
		
		$q = "UPDATE action_pages " . 
		"SET updateflag=1-updateflag, form_id='$form' " . 
		"WHERE id='$actionpage' LIMIT 1";
		
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
		Error: " . mysqli_error ($dbc));
	}

	if (mysqli_affected_rows ($dbc) == 1) $actionpageupdate = TRUE;			
	
	if($campaignupdate && $actionpageupdate) {
				echo '
<div class="alert-holder">
	<div class="alert-box green">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>Your campaign settings have been saved successfully!</p>
			</div>
		</div>
	</div>
</div>';
	} else {
		// If it did not run okay
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>The campaign settings could not be registered due to a system error. We apologize for any inconvenience.</p>
			</div>
		</div>
	</div>
</div>';
	}
}

//Build query for action pages available to this account:
$q = "SELECT id, name 
FROM action_pages 
WHERE account_id='{$_SESSION['account_id']}' AND id>5"; 
	
$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

//Assign ids and names of action pages to array:
$actionpagesarray = array();
while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	$actionpagesarray[$row['id']] = $row['name'];
}

//Build query for forms available to this account:
$q = "SELECT form_id, name 
FROM forms 
WHERE account_id='{$_SESSION['account_id']}' AND id>5"; 
	
$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

//Assign ids and names of action pages to array:
$formsarray = array();
while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	$formsarray[$row['form_id']] = $row['name'];
}

//SUMMARY PAGE FOR ACCOUNTS: ###############################################
//Build query for campaigns:
$q = "SELECT campaigns.id, campaigns.name, campaigns.type, campaigns.action_page, action_pages.form_id, " . 
"campaigns.last_updated, campaigns.date_begins, campaigns.date_ends, campaigns.phone, campaigns.domain " . 
"FROM campaigns LEFT JOIN action_pages ON campaigns.action_page=action_pages.id " . 
"WHERE campaigns.account_id='{$_SESSION['account_id']}' " . 
"ORDER BY campaigns.name";
	
$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

if (mysqli_num_rows($r) > 0) {
	//Table header:
	echo '
<div class="main-block">
	
<div class="content-link top-link">
	<div class="link-holder">
		<div class="link-frame">
			<a class="btn" href="/build/campaigns/new/"><span>Create a New Campaign &gt;</span></a>
		</div>
	</div>
</div>

<br/>

	
	<div class="heading">
	<!-- <h2>Active &amp; Current Marketing Campaigns</h2>
	<a href="/help/manage-active-campaigns.html" class="btn-help" title="Help" onclick="Modalbox.show(this.href, {title: this.title, width: 600, evalScript: true}); return false;"><span>Help?</span></a>  -->
	<h2>Your Existing Marketing Campaigns</h2>
	</div>

<form action="#" id="archive_campaigns" name="archivecampaignsform" class="main-form">
<fieldset>	

<div class="main-box">

<div class="main-table">
	<table class="sortable">
		<thead>
			<tr>
				<!-- <th class="nosort"></th> -->
				<th class="sortcol sortfirstasc"><span>Campaign</span></th>
				<th class="sortcol"><span>Type</span></th>
				<th class="sortcol"><span>Starts</span></th>
				<th class="sortcol"><span>Ends</span></th>
				<th class="nosort"><span>Call(s) to Action</span></th>
				<th class="nosort"><span>Preview/Test</span></th>
			</tr>
		</thead>
		<tbody>
'; 
	//Fetch records, build table:
	while ($row = mysqli_fetch_array($r,MYSQLI_ASSOC)) {
		echo '
		<tr><!-- <td class="check-row"><input type="checkbox" class="check" /></td> -->
		<td>
		<a href="/build/campaigns/edit/?id=' . $row['id'] . '">' . $row['name'] . '</a>
		</td>
		<td>
';
		switch($row['type']) {
			case '1':
				echo 'Print';break;
			case '2':
				echo 'Radio';break;
			case '3':
				echo 'Television';break;
			case '4':
				echo 'Banner';break;
			case '5':
				echo 'Email Marketing';break;
			case '6':
				echo 'Pay-Per-Click';break;
			default:
				echo 'Unknown';
		}
		echo '
		</td><td sorttable_customkey="' . $row['date_begins'] . '">' . 
		date('n/j/y',strtotime($row['date_begins']));
		if ($row['date_ends']=='0000-00-00 00:00:00') {
			echo '</td><td>Open';
		} else {
			echo '</td><td>' . date('n/j/y',strtotime($row['date_ends'])); 		
		}
		echo '</td>';
		if($row['phone']=='nophone' AND $row['domain']=='nodomain') {
		 	$calls = '<td></td>';
		} elseif($row['phone']!='nophone' AND $row['domain']=='nodomain') {
			$calls = '<td>' . $row['phone'] . '</td>';
		} elseif($row['phone']=='nophone' AND $row['domain']!='nodomain') {
			$calls = '<td>' . $row['domain'] . '</td>';
		} else {
			$calls = '<td>' . $row['phone'] . ' | ' . $row['domain'] . '</td>';
		}
		echo $calls;
		if($row['domain']!='nodomain') {
			echo '<td><a target="_blank" href="http://' . $_SESSION['mp_subdomain'] . 
			'.mp41.com/land?camp=' . $row['id'] . '">Test Link</a></td></tr>';
		} else {
			echo '<td>N/A</td></tr>';
		}
		
		
		
	}
	echo '
		</tbody>
	</table>
</div>

<!--
	<div class="content-link">
		<div class="link-holder">
			<div class="link-frame">
				<a href="#" class="btn" onclick="document.getElementById(\'archive_campaigns\').submit()" /><span>Archive Selected Campaigns &gt;</span></a>
			</div>
		</div>
	</div>
-->
	
	';
} else {
	echo '
<div class="main-block main-block-add">

<div class="content-link top-link">
	<div class="link-holder">
		<div class="link-frame">
			<a class="btn" href="/build/campaigns/new/"><span>Create a New Campaign &gt;</span></a>
		</div>
	</div>
</div>

<br/>

<div class="heading">
<h2>Your Existing Marketing Campaigns</h2>
</div>

<p>Unfortunately you don\'t have any existing Marketing Campaigns. There\'s a really easy fix though. Just click the "Create a New Campaign" button right up there and you\'ll be on your way.</p>

</div>
';
}

?>
	
</div>

</fieldset>
</form>

</div>


<!-- / Main Content -->

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
