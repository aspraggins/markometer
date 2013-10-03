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
$formID = mysqli_real_escape_string($dbc, trim($_GET['formID']));

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

//Check validation of everything:
if ($formname) {
	//Initialize variables:
	$forminsert = FALSE;
	
	//Add the form association to the forms table:
	$q = "UPDATE forms " .
	"SET name='$formname', date_created=NOW(), account_id='{$_SESSION['account_id']}', " .
	"date_modified=NOW(), fontsize='$fontsize', fonttype='$fonttype', " .
	"fontcolor='$formfontcolor', fontbackground='$formbackgrnd', " .
	"buttonlabel='$buttonlabel', fieldorder='$fieldorder', fieldtype='$fieldtype', " . 
	"fieldrequired='$fieldrequired', fieldsize='$fieldsize', fieldmaxsz='$fieldmaxsz', " . 
	"fieldrows='$fieldrows', fieldtext='$fieldtext', fieldoptions='$fieldoptions' " . 
	"WHERE id='$formID'";
	
	$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
	. mysqli_error($dbc));

	//Check success of user table insert:
	if (mysqli_affected_rows($dbc) == 1) $forminsert = TRUE;
		
	if ($forminsert) {
		$url = '/build/forms/?saved=yes';		
		header("Location: $url");
	} else {
		//If it did not run okay
		echo '<p class="error">This form could not be saved due to a system
		error. We apologize for any inconvenience.</p>';
	}
} else {
	echo 'You have reached this page in error.<br />';
	echo '<a href="javascript: history.go(-1)">Back</a>';
} 
exit();
?>

