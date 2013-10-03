<?php # saveform script to take chocoform & parse out to data table
//Programmer: Ray Neel 1/2011

require_once('/home/rneel/web/htdocs/includes/config.inc.php');
include('/home/rneel/web/htdocs/includes/header.html');

//MySQL setup:
require_once(MYSQL);

//Check for a form name:
if (isset($_GET['name'])) {
	if(preg_match('/^[A-Z0-9 \',_.-]{2,60}$/i', trim($_GET['name']))) {
		$formname = mysqli_real_escape_string($dbc, trim($_GET['name']));
	} else {
		echo '<table border="1" bgcolor="red"><tr><td>Please enter a valid form name!</td></tr></table>';	
		exit();	
	} 
} else {
	echo '<table border="1" bgcolor="red"><tr><td>Please go back and enter a form name!</td></tr></table>';
	exit();
}

$fontsize = mysqli_real_escape_string($dbc, trim($_GET['fs']));
$fonttype = mysqli_real_escape_string($dbc, trim($_GET['fF']));
$formfontcolor = mysqli_real_escape_string($dbc, trim($_GET['fc']));
$formbackgrnd = mysqli_real_escape_string($dbc, trim($_GET['fb']));
$buttonlabel = mysqli_real_escape_string($dbc, trim($_GET['button']));
$fieldorder = mysqli_real_escape_string($dbc, trim($_GET['order']));
$fieldtype = mysqli_real_escape_string($dbc, trim($_GET['type']));
$fieldrequired = mysqli_real_escape_string($dbc, trim($_GET['req']));
$fieldsize = mysqli_real_escape_string($dbc, trim($_GET['sz']));
$fieldmaxsz = mysqli_real_escape_string($dbc, trim($_GET['maxsz']));
$fieldrows = mysqli_real_escape_string($dbc, trim($_GET['rows']));
$fieldtext = mysqli_real_escape_string($dbc, trim($_GET['txt']));
$fieldoptions = mysqli_real_escape_string($dbc, trim($_GET['opt']));


echo $formname . '<br />fontsize:' . $fontsize . '<br />fonttype:' . $fonttype . 
'<br />fontcolor:' . $formfontcolor . '<br />background:' . $formbackgrnd;
echo '<br />' . $fieldorder . '<br />';
echo $fieldtype . '<br />';
echo $fieldrequired . '<br />';
echo $fieldsize . '<br />';
echo $fieldmaxsz . '<br />';
echo $fieldrows . '<br />';
echo $fieldtext . '<br />';
echo $fieldoptions . '<br />';

//Set up variableS:
//$name = $formID = NULL;

//SQL hit to recheck availability of name:
$q = "SELECT * FROM forms WHERE name='$formname' AND account_id='{$_SESSION['account_id']}'";

$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
Error: " . mysqli_error($dbc));

$num = mysqli_num_rows($r);

if($num!=0) {
	echo '<table border="1" bgcolor="red"><tr><td>The form name ' . $formname . '</b> is taken,
	please try another.</td></tr></table>';	
	$namecheck = FALSE;
	exit();
} else {
	$namecheck = TRUE;
}

//Check for a formID session variable:
/*if (isset($_SESSION['formID'])) {
	$formID = $_SESSION['formID'];
}*/

//Check validation of everything:
if ($formname && $namecheck) {
	//Initialize variables:
	$forminsert = FALSE;
	
	//Add the form association to the forms table:
/*	$q = "INSERT INTO forms (name, form_id, date_created, account_id, date_modified) 
	VALUES('$name', '$formID', NOW(), '{$_SESSION['account_id']}', NOW())";*/

	$q = "INSERT INTO forms (name, date_created, account_id, date_modified, fontsize, fonttype, fontcolor, fontbackground, buttonlabel, fieldorder, fieldtype, fieldrequired, fieldsize, fieldmaxsz, fieldrows, fieldtext, fieldoptions) VALUES('$formname', NOW(), '{$_SESSION['account_id']}', NOW(), '$fontsize', '$fonttype', '$formfontcolor', '$formbackgrnd', '$buttonlabel', '$fieldorder', '$fieldtype', '$fieldrequired', '$fieldsize', '$fieldmaxsz', '$fieldrows', '$fieldtext', '$fieldoptions')";
	
	$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
	. mysqli_error($dbc));

	//Check success of user table insert:
	if (mysqli_affected_rows($dbc) == 1) $forminsert = TRUE;
	
	//Check to see if this is part of a sequence to register a 
	//action page==>form:
	/*if(isset($_POST['actionid'])) {
		if($_POST['actionid']>0) {
			//Update the formID to the campaigns table:
			$q = "UPDATE action_pages " . 
			"SET updateflag=1-updateflag, form_id='$formID' " . 
			"WHERE id='{$_REQUEST['actionid']}' " . 
			"LIMIT 1";
			
			$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
			. mysqli_error($dbc));

			//Check success of user table insert:
			if (mysqli_affected_rows($dbc) == 1) $actioninsert = TRUE;
		} else {
			$actioninsert = TRUE;
		}
	} else {
		$actioninsert = TRUE;
	}*/
		
	if ($forminsert) {
		//Success message for user, clear session variables, exit:
		//If part of a campaign==>action page==>form sequence:
		/*if($_SESSION['campaignid']>0) {		
			echo 'Campaign, Action Page, and Form successfully created!<br /><br />';
			echo 'Public link for this campaign:<br />http://www.mp41.com/land?camp=0000000000' . 
			intval($_SESSION['campaignid']) . '<br /><br /><br />';
		} else {
			echo 'Form successfully created!<br /<br />';
		}
		echo '<a href="/">Go Home</a><br />';		
		$_SESSION['campaignid'] = ''; $_SESSION['formID'] = ''; */

		//$url = '/create/form/done/?action=formchosen';
		$url = '/manage/forms/?new=added';		
		header("Location: $url");
	} else {
		//If it did not run okay
		echo '<p class="error">This form could not be registered due to a system
		error. We apologize for any inconvenience.</p>';
	}
} else {
	echo 'You have reached this page in error.<br />';
	echo '<a href="javascript: history.go(-1)">Back</a>';
} 
exit();
?>

