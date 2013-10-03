<?php # 3rd (review)  page for changing existing campaign

require_once('/home/rneel/web/htdocs/includes/config.inc.php');
include('/home/rneel/web/htdocs/includes/header.html');

//Check for logged in user:
if (!isset($_SESSION['id'])) {
	header("Location: /");
	exit();
}
?>

<h1>
<?php 
if($_SESSION['multiaccounts']) {
    if (isset($_SESSION['accountname'])) echo $_SESSION['accountname'] . ': '; 
}
?>
Change a Marketing Campaign Settings</h1><br />
	
<?php
//Database:
require_once(MYSQL);

if (isset($_POST['submitted'])) {
	//get campaign record id number:
	$id = $_GET['id'];

	//update all information to existing campaign record:
	$q = "UPDATE campaigns " . 
	"SET updateflag=1-updateflag, rate='{$_SESSION['campaign_level']}', domain='{$_SESSION['campaign_domain']}',"  . 
	"phone='{$_SESSION['campaign_phone']}', last_updated=NOW() " . 
	"WHERE id='$id' LIMIT 1";

	$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
	Error: " . mysqli_error ($dbc));

	if (mysqli_affected_rows ($dbc) == 1) {		
		$_SESSION['campaign_domain'] = NULL; unset($_SESSION['campaign_domain']);
		$_SESSION['campaign_phone'] = NULL; unset($_SESSION['campaign_phone']);
		$_SESSION['campaign_rate'] = NULL; unset($_SESSION['campaign_rate']);
		$_SESSION['campaign_begin'] = NULL; unset($_SESSION['campaign_begin']);	
		$_SESSION['campaign_end'] = NULL; unset($_SESSION['campaign_end']);		
		$_SESSION['campaign_level'] = NULL; unset($_SESSION['campaign_level']);		
	
		//Move to next page in campaign startup:
		$url = '/manage/campaigns/?action=done';
		header("Location: $url");
		exit();
	} else {
		//If it did not run okay
		echo '<p class="error">Your campaign could not be changed due to a system
		error. We apologize for any inconvenience.</p>';
	}
}

//Table with monthly cost, domain name, phone:
if($_SESSION['campaign_domain']!='0') $domain = $_SESSION['campaign_domain']; else $domain = 'N/A';
if($_SESSION['campaign_phone']!='0') $phone = $_SESSION['campaign_phone']; else $phone = 'N/A';
echo '<br /><table width="50%" border="1">' . 
'<th colspan="3" border="0">' . $_SESSION['campaign_name'] . '</th>' . 
'<tr border="0"><td align="left" width="30%">New Monthly Cost<br />New Domain Name<br />' . 
'New Phone Number</td><td align="center" width="40%">$' . 
$_SESSION['campaign_rate'] . '/month<br />' . $domain . '<br />' . $phone . 
'</td></tr>' . 
'</table><br /><br />';
?>


<!-- Code for Step Links: -->
<b><a href="/manage/campaigns/step1/?id=<?php echo $_GET['id'];?>">Step 1 </a>==> 
<?php
if(isset($_SESSION['campaign_domain']) && isset($_SESSION['campaign_phone'])) {
	if(strlen($_SESSION['campaign_domain'])>2 || strlen($_SESSION['campaign_phone'])>2) {
		echo '<a href="/manage/campaigns/step2/?id=' . $_GET['id'] . '">Step 2 </a>';
	} else {
		echo '<b>Step 2</b>';
	}
}
?>	
==> <font color="lime">Step 3 </font></b><br /><br />

<table border="0" width="80%" bgcolor="chocolate"><tr><td>Note: Your campaign is not active until you press "Finalize Campaign"</td></tr></table><br />

<form action="" method="post" name="campform">

<br /><input type="hidden" name="submitted" value="TRUE" /> 
<br />

<?php
if ($_SESSION['campaign_end']=='0000-00-00 00:00:00') {
	$end = '.';
} else {
	$end = date('m/j/Y',strtotime($_SESSION['campaign_end']));
}
?>


<table border="0" width="80%"><tr><td>
<b>When you click the "Finalize Campaign" button you <u>will be billed $<?php echo $_SESSION['campaign_rate']; ?> per month</u> from the time the event <u>starts on <?php echo date('M j, Y', strtotime($_SESSION['campaign_begin'])); ?> </u> until it <u>ends <?php echo $end . '.'; ?></u></td></tr></table> 
<br /><br /><br />

<?php
if(isset($_SESSION['campaign_domain']) && isset($_SESSION['campaign_pnumber'])) {
	if(strlen($_SESSION['campaign_domain'])>2 || strlen($_SESSION['campaign_pnumber'])>2) {
		echo '<a href="/create/campaign/step3/">Previous Step</a>';
	} else {
		echo '<a href="/create/campaign/step2/">Previous Step</a>';
	}
}
?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" value="Finalize Changes" />
&nbsp;or&nbsp;<a href="/manage/cancel.php">Cancel</a>	
</form>

<br/><br />
<table border="0" width="80%" bgcolor="chocolate"><tr><td>Note: Your campaign is not active until you press "Finalize Campaign"</td></tr></table>

<img src="/images/white.jpg" width="1" height="1" 
onload="javascript:document.getElementById('domainnametext').focus();"> 

<?php //Include the HTML footer
include ('/home/rneel/web/htdocs/includes/footer.html');
?>
