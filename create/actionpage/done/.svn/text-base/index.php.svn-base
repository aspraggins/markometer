<?php # Done page for action page creation

require_once('/home/rneel/web/htdocs/includes/config.inc.php');
include('/home/rneel/web/htdocs/includes/header.html');

//Check for logged in user:
if (!isset($_SESSION['id'])) {
	header("Location: /");
	exit();
}

//See if "?action=pagechosen" passed to this page, to see if part of a campaign creation sequence:
$campaignseq = FALSE;
if(isset($_REQUEST['action'])) {
	if($_REQUEST['action']=='pagechosen') {
		$campaignseq = TRUE;
	}
}
?>

<!-- Text to page, appropriate to a new action page created, or an action page chosen for a campaign sequence -->
<h1>
<?php 
if($_SESSION['multiaccounts']) {
    if (isset($_SESSION['accountname'])) echo $_SESSION['accountname'] . ': '; 
}
if($campaignseq) {
	echo 'Action Page Selected</h1>'; 
	echo '<b>Congratulations! You Have Successfully Chosen Your Action Page.</b><br />';
} else {
	echo 'New Action Page Created</h1>';
	echo '<b>Congratulations! You Have Successfully Created a New Action Page.</b><br />';
}

//Database:
require_once(MYSQL);

//Handle Submission if choosing an existing form:
if (isset($_POST['submitted'])) {
	if(strlen(trim($_POST['chooseform']))>0) {
		$chooseform = trim($_POST['chooseform']);
		 
		$action_id = $_REQUEST['actionid'];
		
		//update campaign record to include this chosen form:
		/*$q = "UPDATE campaigns " .
		"SET updateflag=1-updateflag, form_id='$chooseform' " . 
		"WHERE id='{$_SESSION['campaignid']}' LIMIT 1"; */

		//update action page record to include this chosen form:
		$q = "UPDATE action_pages " .
		"SET updateflag=1-updateflag, form_id='$chooseform' " . 
		"WHERE id='$action_id' LIMIT 1"; 
	
		$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
		. mysqli_error($dbc));

		//Check success of campaigns table update:
		if (mysqli_affected_rows($dbc) == 1) {
			//Success! Move to form done page, with form chosen message:
			$url = '/create/form/done/?action=formchosen';
			header("Location: $url");
		} else {
			//If it did not run okay
			echo '<p class="error">This form could not be chosen due to a system
			error. We apologize for any inconvenience.</p>';
		}
	}
}

//show links for choosing or creating a form:
echo '<table border="0" width="70%" style="display:inline">' . 
'<tr><td><b><br />Next Steps: You need to choose or create a form to use with this action page:</b>' . 
'</td></tr></table>';

echo '<table height="300" border="0"><tr><td>' . 
'<div style="display:inline"><table border="1" width="250" bgcolor="LightGrey">' . 
'<tr><td height="75" align="center"><b>Choose an Existing Form To Use With This Action Page:</b>' . 
'</td></tr><tr><td>' . 
	'<div id="scrolldiv">';

//query for available forms for this account:
$q = "SELECT form_id as id, name " . 
"FROM forms " . 
"WHERE account_id='{$_SESSION['account_id']}' AND id>5 ORDER BY name";
	
$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

//if forms available, display in scrolling table:
if (mysqli_num_rows($r) > 0) {
	echo '<form action="" method="post">';
	echo '<select name="chooseform" size="8">';
	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		echo '<option value="' . 
		$row['id'] . '">' . $row['name'] . '</option>';
	}
	echo '</select>';
}else{
	echo 'No Forms Found';
}

echo '</div></td></tr>';
if (mysqli_num_rows($r) > 0) {
	echo '<tr><td height="40" align="center">' . 
	'<input type="hidden" name="submitted" value="TRUE" />' .
	'<input type="submit" value="Choose This Form"></form></td></tr>';
}
echo '</table></div></td><td>';

//show "OR":
echo '<table border="0"><div style="display:inline">' . 
'<tr><td><b>OR</b></td></tr></div></table></td><td>';

//show new Action Page link
echo '<div style="display:inline"><table border="1" width="250" bgcolor="LightGrey">' .
'<tr><td height="90" align="center"><b>Create A New Form For This Action Page</b></td></tr>' . 
'<tr><td width="270" height="200" align="center"><a href="/create/form/choose/?actionid=' . $_REQUEST['actionid'];
//if($campaignseq) echo '&camp=' . $_SESSION['campaignid']; 
echo '">Create a New Form</a></td>' . 
'</tr></table></div></td></tr></table>';
?>

<?php //Include the HTML footer
include ('/home/rneel/web/htdocs/includes/footer.html');
?>
