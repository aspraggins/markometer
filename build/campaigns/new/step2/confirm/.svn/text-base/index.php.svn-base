<?php # Post Leads Upload Screen (Not Shown to User)
$metatitle = 'Importing Files';
$returnurl = '/login/?returnpage=build/campaigns/new';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<?php

//Check that user is logged in:
if (!isset($_SESSION['id'])) {
	header("Location: /");
	exit();
}

//Initialize variables for use in determining if redundant fields selected:
$fname = FALSE; $lname = FALSE; $email = FALSE;
$phone = FALSE; $address = FALSE; $city = FALSE;
$state = FALSE; $zip = FALSE; $country = FALSE; 
$company = FALSE; $IGNORE = FALSE;

//MYSQL Setup:
require_once(MYSQL);

//Determine if all fields selected in previous page:
$columns = $_POST['columns'];
$somethingmapped = 0; //variable to assure that at least one field is mapped
for($j=0; $j<=$columns-1; $j++) {
	$field = 'field_' . $j;
	if($_POST[$field]=='---') {
echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>You need to select a criteria for all of the fields. If you don\'t want to map a field choose "IGNORE" in the dropdown. Press the Previous Step button below and try again.</p>
			</div>
		</div>
	</div>
</div>
<br/><br/>

<div class="content-link">
	<div class="link-holder">
		<div class="link-frame">
			<a class="btn" href="javascript:window.history.go(-1);"><span>&lt; Previous Step</span></a>
			<em>or</em>
			<a href="/build/campaigns/new/cancel/">Cancel</a>
		</div>
	</div>
</div>
';
		exit();
	}
	//echo $columns . '&nbsp;' . $field . '&nbsp;' . $_POST[$field] . '<br />';
	
	if($$_POST[$field] && $_POST[$field]!='IGNORE') {
echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Please select each field only once for each column. Press the Previous Step button below and try again.</p>
			</div>
		</div>
	</div>
</div>
<br/><br/>

<div class="content-link">
	<div class="link-holder">
		<div class="link-frame">
			<a class="btn" href="javascript:window.history.go(-1);"><span>&lt; Previous Step</span></a>
			<em>or</em>
			<a href="/build/campaigns/new/cancel/">Cancel</a>
		</div>
	</div>
</div>
';
		exit();
	}
	switch($_POST[$field]) {
		case 'fname':
			$fname = TRUE; $somethingmapped++; break;
		case 'lname':
			$lname = TRUE; $somethingmapped++; break;
		case 'email':
			$email = TRUE; $somethingmapped++; break;
		case 'phone':
			$phone = TRUE; $somethingmapped++; break;
		case 'address':
			$address = TRUE; $somethingmapped++; break;
		case 'city':
			$city = TRUE; $somethingmapped++; break;
		case 'state':
			$state = TRUE; $somethingmapped++; break;
		case 'zip':
			$zip = TRUE; $somethingmapped++; break;
		case 'country':
			$country = TRUE; $somethingmapped++; break;
		case 'company':
			$company = TRUE; $somethingmapped++; break;			
	}
}
 
//check to make sure that at least one field was mapped:
if ($somethingmapped==0) {
	echo '
	<div class="alert-holder">
		<div class="alert-box red">
			<div class="holder">
				<div class="frame">
					<a class="close" href="#">X</a>
					<p>Please map at least one field. Press the Previous Step button below and try again.</p>
				</div>
			</div>
		</div>
	</div>
	<br/><br/>

	<div class="content-link">
		<div class="link-holder">
			<div class="link-frame">
				<a class="btn" href="javascript:window.history.go(-1);"><span>&lt; Previous Step</span></a>
				<em>or</em>
				<a href="/build/campaigns/new/cancel/">Cancel</a>
			</div>
		</div>
	</div>
	';
			exit();
}

//Delete "pending" dbase entries for campaign and leads:
//campaign
$q = "DELETE FROM campaigns " . 
	"WHERE account_id='{$_SESSION['account_id']}' AND imported='P'";

$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
. mysqli_error($dbc));

//leads
$q = "DELETE FROM leads " . 
	"WHERE account_id='{$_SESSION['account_id']}' AND status='P'";

$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
. mysqli_error($dbc));

//Create campaign for this import:
//Write off all information to new campaign record in database:
$q = "INSERT INTO campaigns (name, date_created, account_id, type, 
subject, date_begins, date_ends, domain, phone, goal, cost, last_updated, imported) 
VALUES('{$_SESSION['campaign_name']}', NOW(), '{$_SESSION['account_id']}', 
'{$_SESSION['campaign_type']}','{$_SESSION['campaign_subject']}', 
'{$_SESSION['campaign_begin']}', '{$_SESSION['campaign_end']}',
'nodomain', 'nophone', 
'{$_SESSION['campaign_goal']}', '{$_SESSION['campaign_cost']}', NOW(), 'P')";

$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
. mysqli_error($dbc));

//Check success of campaign table insert:
if (mysqli_affected_rows($dbc) == 1) {
	//Retrieve record number for insert, put into session variable:
	$_SESSION['campaignid'] = mysqli_insert_id($dbc);
	//Unset session variables that stored campaign information:
	/*$_SESSION['campaign_goal'] = NULL; unset($_SESSION['campaign_goal']);
	$_SESSION['campaign_type'] = NULL; unset($_SESSION['campaign_type']);
	$_SESSION['campaign_cost'] = NULL; unset($_SESSION['campaign_cost']);
	$_SESSION['campaign_subject'] = NULL; unset($_SESSION['campaign_subject']);*/

	//update current account record to show that a campaign has been created, account no longer new:
	$q = "UPDATE accounts " .
	"SET updateflag=1-updateflag, new='N' " . 
	"WHERE id='{$_SESSION['account_id']}' LIMIT 1"; 

	$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
	. mysqli_error($dbc));

	//Update session variable indicating that this is no longer a "new" account:
	$_SESSION['newaccount'] = 'N';
} else {
	//If it did not run okay
echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Your campaign could not be registered due to a system error. We apologize for any inconvenience.</p>
			</div>
		</div>
	</div>
</div>';
}
//end of code that creates campaign

//retrieve import from session variable:
$importarray = array();
$importarray = $_SESSION['csvimport'];
$num = 1;
$numentries = count($importarray);
$campaign_id = $_SESSION['campaignid'];
//counter for number of inserts that successfully get written to table:
$numinserts = 0; $insertproblem = FALSE;

//check out firstline variable from previous page to see if need to import
//first row of csv file:
$firstline = 1;
if(isset($_POST['firstline'])) $firstline = 0;

for($row = $firstline; $row<=$numentries-1; $row++) {
	//echo '<br />';
	$i = 0;
	
	//Begin building insertion string:
	$qfields = "INSERT INTO leads (account_id, campaign_id,";
	$qvalues = "VALUES('{$_SESSION['account_id']}','$campaign_id',";
	
	//run through selected fields and values, appending to strings 
	//based on what they've chosen:
	foreach($importarray[$row] as $value) {
		$field = 'field_' . $i;

		//echo '<br />' . $_POST[$field];
		if($_POST[$field]!='IGNORE') {
			switch($_POST[$field]) {
				case 'fname':
					$chosenfield = 'contact_first'; break;
				case 'lname':
					$chosenfield = 'contact_last'; break;
				case 'email':
					$chosenfield = 'email'; break;
				case 'phone':
					$chosenfield = 'telephone'; break;
				case 'address':
					$chosenfield = 'address'; break;
				case 'city':
					$chosenfield = 'city'; break;
				case 'state':
					$chosenfield = 'state'; break;
				case 'zip':
					$chosenfield = 'zip'; break;
				case 'country':
					$chosenfield = 'country'; break;
				case 'company':
					$chosenfield = 'company'; break;
			}
		$qfields .= $chosenfield . ',';
		$qvalues .= "'" . mysqli_real_escape_string($dbc,trim($value)) . "',";
		}
		$i++;
	}
	//Add new (Y), status (A), date_created, and last_updated:
	$qfields .= 'new,status,date_created,last_updated) ';
	$qvalues .= "'Y','P',NOW(),NOW())";

	//and then puts them together to make the INSERT:
	$q = $qfields . $qvalues;
	//Insert into table:
	//echo $q . '<br />';
	
	$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
	. mysqli_error($dbc));
	
	//Success?:
	if (mysqli_affected_rows ($dbc) != 1) {
		$insertproblem = TRUE;
	}else{
		$numinserts++;
	}
}

//echo 'Entries: ' . $numentries . '<br />';
echo '
<div class="alert-holder">
	<div class="alert-box green">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>' . $numinserts; ' leads were added to this campaign.</p>
			</div>
		</div>
	</div>
</div>';
if($insertproblem) {
echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>There was at least one problem while adding these leads.</p>
			</div>
		</div>
	</div>
</div>';
}else{
	
	$url = '/build/campaigns/new/step3/';
	header("Location: $url");
}
?>

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
