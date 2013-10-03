<?php # step 1 page for changing an existing campaign

require_once('/home/rneel/web/htdocs/includes/config.inc.php');
include('/home/rneel/web/htdocs/includes/header.html');

//Check that user is logged in:
if (!isset($_SESSION['id'])) {
	header("Location: /");
	exit();
}
?>

<!-- Prototype and Scriptaculous libraries for effects -->
<script src="/includes/prototype.js" type="text/javascript"></script>
<script src="/includes/effects.js" type="text/javascript"></script>
<!-- Modal Box library -->
<script src="/includes/modalbox.js" type="text/javascript"></script>
<link rel="stylesheet" href="/includes/modalbox.css" type="text/css" media="screen" />
<style type="text/css">
tr.d0 td {
		background-color: #FFEFD5; color: black;
}
tr.d1 td {
		background-color: #CFC9AF; color: black;
}
</style>

<h1>
<?php 
if($_SESSION['multiaccounts']) {
    if (isset($_SESSION['accountname'])) echo $_SESSION['accountname'] . ': '; 
}
?>
Change a Marketing Campaign Settings</h1><br />
		
<?php
//Database setup:
require_once(MYSQL);

if (isset($_POST['submitted'])) {
	//Set session variables for this campaign level:
	$rate = $_SESSION['campaign_level'] = $_POST['rate'];	
	
	//query for rates to write to session variable:
	$q = "SELECT rate FROM rates WHERE level='$rate'";
		
	$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
	mysqli_error($dbc));

	$row = mysqli_fetch_array($r, MYSQLI_ASSOC);	
	$_SESSION['campaign_rate'] = $row['rate'];
	
	//Move on to next step in campaign process based on his level:
	if($_POST['rate']>=30) {
		$url = '/manage/campaigns/step2/?id=' . $_GET['id'];
		header("Location: $url");
	} else {
		$_SESSION['campaign_domain'] = '0';
		$_SESSION['campaign_phone'] = '0';
		$url = '/manage/campaigns/step3/?id=' . $_GET['id'];
		header("Location: $url");
	}	
}


//Table with monthly cost, domain name, phone:
echo '<br /><table width="50%" border="1">' . 
'<th colspan="3" border="0">' . $_SESSION['campaign_name'] . '</th>' . 
'<tr border="0"><td align="left" width="30%">Current Monthly Cost<br />Current Domain Name<br />' . 
'Current Phone Number</td><td align="center" width="40%">$' . 
$_SESSION['campaign_rate'] . '/month<br />' . $_SESSION['campaign_domain'] . '<br />' . $_SESSION['campaign_phone'] . 
'</td></tr>' . 
'</table><br /><br />';
?>

<b><font color="lime">Step 1 </font>==> Step 2 ==> Step 3</b><br /><br />

<form action="" method="post" name="campform">
<b>1)</b>Select What Best Describes What You Need to Measure This Event&nbsp;<a href="/manage/help.php" title="Help" onclick="Modalbox.show(this.href, 
{title: this.title, width: 600, evalScript: true}); return false;">Help</a><br /></br /><br />

<table border="0" width="90%">

<?php 
//Build query for rates table to display:
$q = "SELECT * FROM rates ORDER BY rate DESC";
	
$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

//Initialize variables to handle alternating colors and default checked selection:
$tableclass = 'd0'; $checked = ' checked="checked"';
//Generate table with radio boxes for rate levels that user can choose from:
while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	echo '<tr class="' . $tableclass . '"><td><input type="radio" name="rate" value="' . 
	$row['level'] . '"';

	if($_SESSION['campaign_level']==$row['level']) echo ' checked="checked"';

	echo '></td><td>' . $row['description'] . '<td><b>$' . 
	$row['rate'] . '/month</b></td></tr>';
	if($tableclass=='d0') {
		$tableclass = 'd1';
	} else {
		$tableclass = 'd0';
	}
	$checked = '';
}
?>
</table>
<br /><input type="hidden" name="submitted" value="TRUE" /> 
<br /><br />

<input type="submit" value="Save & Go to Next Steps (Calls to Action)" />
<!-- <input type="button" value="Save & Go to Next Steps (Calls to Action)" onclick="alert('Not ready!');"/> -->
&nbsp;or&nbsp;<a href="/manage/cancel.php">Cancel</a>	
</form>

<br/><br />(You will have a chance to review your changes before you finalize it)

<?php //Include the HTML footer
include ('/home/rneel/web/htdocs/includes/footer.html');
?>
