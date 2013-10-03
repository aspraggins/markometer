<?php # Post Leads Upload Screen (Not Shown to User)
$metatitle = 'Importing Files';
//$returnurl = '/login/?returnpage=create/campaign/prestep1.php';
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
for($j=0; $j<=$columns-1; $j++) {
	$field = 'field_' . $j;
	if($_POST[$field]=='---') {
echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Please press the back button and fill out all of the fields!</p>
			</div>
		</div>
	</div>
</div>';
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
				<p>Please select each field only once for each column. Press the back button and try again!</p>
			</div>
		</div>
	</div>
</div>';
		exit();
	}
	switch($_POST[$field]) {
		case 'fname':
			$fname = TRUE; break;
		case 'lname':
			$lname = TRUE; break;
		case 'email':
			$email = TRUE; break;
		case 'phone':
			$phone = TRUE; break;
		case 'address':
			$address = TRUE; break;
		case 'city':
			$city = TRUE; break;
		case 'state':
			$state = TRUE; break;
		case 'zip':
			$zip = TRUE; break;
		case 'country':
			$country = TRUE; break;
		case 'company':
			$company = TRUE; break;			
	}
}
 
//retrieve import from session variable:
$importarray = array();
$importarray = $_SESSION['csvimport'];
$num = 1;
$numentries = count($importarray);
$campaign_id = $_GET['id'];

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
	$qvalues .= "'Y','N',NOW(),NOW())";

	//and then puts them together to make the INSERT:
	$q = $qfields . $qvalues;
	//Insert into table:
	
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
	$url = '/leads/';
	header("Location: $url");
}
?>

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
