<?php # step 2 page for changing an existing campaign

require_once('/home/rneel/web/htdocs/includes/config.inc.php');
include('/home/rneel/web/htdocs/includes/header.html');

//Check for logged in user:
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
	
<script type='text/javascript'>
function selectdomain(field) {
	document.getElementById('domainnametext').value=field;
	return;
}
function selectnumber(field) {
	document.getElementById('phonenumbertext').value=field;
	return;
}
</script>

<h1>
<?php 
if($_SESSION['multiaccounts']) {
    if (isset($_SESSION['accountname'])) echo $_SESSION['accountname'] . ': '; 
}
?>
Change a Marketing Campaign Settings</h1><br />
	
<?php
if (isset($_POST['submitted']))
{
    //Database:
    require_once(MYSQL);

    //Trim all of the incoming data:
    $trimmed = array_map('trim', $_POST);
  
    //Assume invalid values:
    $domainname = $phonenumber = FALSE;

	//Need to verify that there's a domain?:
	if(($_SESSION['campaign_level']>=30 && $_SESSION['campaign_level']<40)
		|| $_SESSION['campaign_level']>=50)	{
		//Check for a valid domain name:
		if(strlen($trimmed['domainnametext'])>2) {
			$_SESSION['campaign_domain'] = mysqli_real_escape_string($dbc, $trimmed['domainnametext']);
			$domainname = TRUE;
			//Code here will have to use API with domain partner to confirm availability...
			##################################
		} else {
			echo '<p class="error">Please enter or select a valid domain name.</p>';
		}
	} else {
		$domainname = TRUE;
		$_SESSION['campaign_domain'] = '0';
	}

	//Need to verify that there's a phone number?:
	if($_SESSION['campaign_level']>=40) {
		//Check for phone number:
		if(strlen($trimmed['phonenumbertext'])>2 && $trimmed['phonenumbertext']!='N/A') {
			$_SESSION['campaign_phone'] = mysqli_real_escape_string($dbc, $trimmed['phonenumbertext']);
			$phonenumber = TRUE;
			//Code here will have to use phone number API to confirm availability...

		} else {
			echo '<p class="error">Please select one of the provided phone numbers.</p>';
		}
	} else {
		$phonenumber = TRUE;
		$_SESSION['campaign_phone'] = '0';		
	}
		
	if($domainname && $phonenumber) { //validation for domain and phone fields
		$url = '/manage/campaigns/step3/?id=' . $_GET['id'];
		header("Location: $url");
	}
}


//Table with monthly cost, domain name, phone:
echo '<br /><table width="50%" border="1">' . 
'<th colspan="3" border="0">' . $_SESSION['campaign_name'] . '</th>' . 
'<tr border="0"><td align="left" width="30%">New Monthly Cost<br />Current Domain Name<br />' . 
'Current Phone Number</td><td align="center" width="40%">$' . 
$_SESSION['campaign_rate'] . '/month<br />' . $_SESSION['campaign_domain'] . '<br />' . $_SESSION['campaign_phone'] . 
'</td></tr>' . 
'</table><br /><br />';
?>

<b><a href="/manage/campaigns/step1/?id=<?php echo $_GET['id'];?>">Step 1 </a>==> <font color="lime">Step 2 </font>==> Step 3</b><br /><br />

<form action="" method="post" name="campform">
<?php 
//evaluate what to show based on campaign's selected level:
$domaindisplay = 'inline'; $phonedisplay = 'inline';
//echo $_SESSION['campaign_level'] . '<br />';
if($_SESSION['campaign_level']>=40 && $_SESSION['campaign_level']<50) {
	$domaindisplay = 'none';
} else if($_SESSION['campaign_level']>=30 && $_SESSION['campaign_level']<40) {
	$phonedisplay = 'none';
}
echo '<div style="display:' . $domaindisplay . '">';
?>

<b>2)</b>Create a Domain Name:&nbsp;<a href="help.php" title="Help" onclick="Modalbox.show(this.href, 
{title: this.title, width: 600, evalScript: true}); return false;">Help</a>&nbsp;(ie. marketingsolutions.com or 
productbenefit.com)<br />
<input type="text" maxlength="75" size="75" id="domainnametext" name="domainnametext" value="<?php if(isset($_SESSION['campaign_domain'])) echo $_SESSION['campaign_domain'];?>"/>

<br /><br /><b>Or we can suggest a few (click to choose):</b>
<div id="domainresults">

<table border ="0" cellspacing="5" width="50%">
<tr><td><div onclick="selectdomain('domainname1.com')"><span style="color:blue">domainname1.com</span></div></td>
<td><div onclick="selectdomain('domainname2.com')"><span style="color:blue">domainname2.com</span></div></td></tr>
<tr><td><div onclick="selectdomain('domainname3.com')"><span style="color:blue">domainname3.com</span></div></td>
<td><div onclick="selectdomain('domainname4.com')"><span style="color:blue">domainname4.com</span></div></td></tr>
</table>
</div>
<input type="button" id="domainbutton" value="Not these, suggest some more"  />
<br /><br /><br /><br /></div>

<?php echo '<div style="display:' . $phonedisplay . '">'; ?>

<b><?php if($domaindisplay=='none') {echo '2';}else{echo '3';}?>)</b>Get a Phone Number:&nbsp;<a href="help.php" title="Help" onclick="Modalbox.show(this.href, 
{title: this.title, width: 600, evalScript: true}); return false;">Help</a>&nbsp;(Click to Choose)<br />
<input type="text" size="75" value="<?php if(isset($_SESSION['campaign_phone'])) echo $_SESSION['campaign_phone'];?>" readonly name="phonenumbertext" 
id="phonenumbertext">

<div id="phoneresults">
<table border ="0" cellspacing="5" width="50%">
	<tr><td><b>Local:</b></td><td><b>Toll-Free:</b></td></tr>
	<tr><td><div onclick="selectnumber('704-555-3243')"><span style="color:blue">704-555-3243</span></div></td>
	<td><div onclick="selectnumber('1-800-555-3266')"><span style="color:blue">1-800-555-3266</span></div></td></tr>
	<tr><td><div onclick="selectnumber('704-555-3244')"><span style="color:blue">704-555-3244</span></div></td>
	<td><div onclick="selectnumber('1-800-555-3269')"><span style="color:blue">1-800-555-3269</span></div></td></tr>
</table>
</div>
<input type="button" id="phonebutton" value="Not these, suggest some more"  />
</div>
<br /><input type="hidden" name="submitted" value="TRUE" /> 

<br /><br />

<a href="/create/campaign/step2/">Previous Step</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" value="Save & Go to Next Step (Review & Finalize)" />
&nbsp;or&nbsp;<a href="/manage/cancel.php">Cancel</a>	
</form>

<br/><br />(You will have a chance to review your campaign before you finalize it)
<img src="/images/white.jpg" width="1" height="1" 
onload="javascript:document.getElementById('domainnametext').focus();"> 

<?php //Include the HTML footer
include ('/home/rneel/web/htdocs/includes/footer.html');
?>
