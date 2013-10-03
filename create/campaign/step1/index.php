<?php # Create New Campaign Step 1
$metatitle = 'Create a New Marketing Campaign - Step 1 of 3';
$returnurl = '/login/?returnpage=create/campaign/prestep1.php';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Heading and Help Button -->

<div class="heading">
<h1>Create a New Marketing Campaign - Step 1/3
<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/current-account.php'); ?>
</h1>
</div>

<!-- Main Content -->

<?php
if (isset($_POST['submitted']))
{
	//Handle the form
    require_once(MYSQL);
  
    //Trim all of the incoming data:
    $trimmed = array_map('trim', $_POST);
  
    //Assume invalid values:
    $goalcheck = $cname = $datecheck = $namecheck = $subjectcheck = FALSE;
  
	//Set session variables for this campaign startup:
	$_SESSION['campaign_goal'] = $trimmed['goal'];	
	$_SESSION['campaign_name'] = $trimmed['campaignname'];
	$_SESSION['campaign_type'] = $trimmed['type'];
	$_SESSION['campaign_cost'] = $trimmed['cost'];
	$_SESSION['campaign_subject'] = $trimmed['subject'];	
	if($trimmed['beginDate']=='Today') {
		$_SESSION['campaign_begin'] = date("Y-m-d");
	} else {
		$_SESSION['campaign_begin'] = date("Y-m-d", strtotime($trimmed['beginDate']));
	}
	if(substr($trimmed['endDate'],0,1)!="E") {
		$_SESSION['campaign_end'] = date("Y-m-d", strtotime($trimmed['endDate']));
	} else {
		$_SESSION['campaign_end'] = "Open";
	}
	$_SESSION['leadimport'] = 'N';
	if(isset($trimmed['import'])) $_SESSION['leadimport'] = 'Y';

    //Check for a valid campaign name:
    if(strlen($trimmed['campaignname'])>2 && strlen($trimmed['campaignname'])<200) {
		$cname = mysqli_real_escape_string($dbc, $trimmed['campaignname']);
		
		//SQL hit to recheck availability of name:
		$q = "SELECT * FROM campaigns WHERE name='$cname' AND account_id={$_SESSION['account_id']}";

		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
		Error: " . mysqli_error($dbc));

		$num = mysqli_num_rows($r);

		if($num!=0) {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>The campaign name <strong>' . $cname . '</strong> is taken, please try another.</p>
			</div>
		</div>
	</div>
</div>';
		} else {
			$namecheck = TRUE;
		}
	} else {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Please enter a valid campaign name (it can\'t be blank)!</p>
			</div>
		</div>
	</div>
</div>';
    }
	
	//Check for a campaign goal:
    if(strlen($trimmed['goal'])>2 && strlen($trimmed['goal'])<200) {
		$goalcheck = TRUE;
	} else {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Please enter a valid campaign goal (it can\'t be blank)!</p>
			</div>
		</div>
	</div>
</div>';
	}
		
	//Check for a campaign subject:
    if(strlen($trimmed['subject'])>2 && strlen($trimmed['subject'])<200) {
		$subjectcheck = TRUE;
	} else {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Please enter a valid campaign subject (it can\'t be blank)!</p>
			</div>
		</div>
	</div>
</div>';
	}

	//Check for a campaign cost:
    if(strlen($trimmed['cost'])>1 && is_numeric($trimmed['cost'])) {
		$costcheck = TRUE;
	} else {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Please enter a valid campaign cost (it can\'t be blank)!</p>
			</div>
		</div>
	</div>
</div>';
	}

	//Check for reasonable dates in campaign start/end:
	if(substr($trimmed['beginDate'],0,5)!='Start') $datecheck = TRUE;
	if(substr($trimmed['endDate'],0,3)!='End') {
		if(strtotime($trimmed['beginDate'])-strtotime($trimmed['endDate'])>0) $datecheck = FALSE;
	}
	if(!$datecheck)
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Please enter valid begin and end dates.</p>
			</div>
		</div>
	</div>
</div>';
		
	if($namecheck && $datecheck && $goalcheck && $subjectcheck && $costcheck) {
		// Move to next page in campaign startup:
		$url = '/create/campaign/step2/';
		header("Location: $url");
	}
}

?>


<div class="content-items content-steps">
<form action="#" method="post" id="create_campaign_step1" name="campform" class="steps-form">
<fieldset>

<div class="row">
	<span class="num"><em>1</em></span>
	<div class="steps-holder">
		<div class="heading">
			<h2>What is Your Goal? <span>(ie. 2 new sales)</span></h2>
			<?php # Help Button
			$helphref = 'create-campaign-step1-goal.html';
			$helptitle = 'Help with Campaign Goals';
			include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
			?>
		</div>
		<input tabindex="1" type="text"  class="text" name="goal" id="goal" maxlength="200" value="<?php if (isset($_SESSION['campaign_goal'])) 
echo $_SESSION['campaign_goal']; ?>" onblur="checkGoal(document.campform.goal.value)" />
		<div id="goalresults"></div>
	</div>
</div>

<div class="row">
	<span class="num"><em>2</em></span>
	<div class="steps-holder">
		<div class="heading">
			<h2>Campaign Name <span>(ie. Business Week ad or CES trade show)</span></h2>
			<?php # Help Button
			$helphref = 'create-campaign-step1-name.html';
			$helptitle = 'Help with Campaign Names';
			include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
			?>
		</div>
		<input tabindex="2" type="text" class="text" name="campaignname" id="campaignname" maxlength="200" value="<?php if (isset($_SESSION['campaign_name'])) 
echo $_SESSION['campaign_name']; ?>" onblur="checkCampaignName(document.campform.campaignname.value)" />
		<div id="nameresults"></div>
	</div>
</div>

<div class="row">
	<span class="num"><em>3</em></span>
	<div class="steps-holder">
		<div class="heading">
			<h2>Campaign Type</h2>
			<?php # Help Button
			$helphref = 'create-campaign-step1-type.html';
			$helptitle = 'Help with Campaign Types';
			include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
			?>
		</div>
	 	<select tabindex="3" id="type" name="type"> 
		    <option value="1" 
			<?php if (isset($_SESSION['campaign_type'])) {
				if ($_SESSION['campaign_type']==1) echo ' selected'; }?> >Print Advertisement</option>    
		    <option value="2"
			<?php if (isset($_SESSION['campaign_type'])) {
				if ($_SESSION['campaign_type']==2) echo ' selected'; }?> >Radio</option>    
		    <option value="3"
			<?php if (isset($_SESSION['campaign_type'])) {
				if ($_SESSION['campaign_type']==3) echo ' selected'; }?> >Television</option>    
		    <option value="4"
			<?php if (isset($_SESSION['campaign_type'])) {
				if ($_SESSION['campaign_type']==4) echo ' selected'; }?> >Banner</option>    
		    <option value="5"
			<?php if (isset($_SESSION['campaign_type'])) {
				if ($_SESSION['campaign_type']==5) echo ' selected'; }?> >Email Marketing</option>    
		    <option value="6"
			<?php if (isset($_SESSION['campaign_type'])) {
				if ($_SESSION['campaign_type']==6) echo ' selected'; }?> >Pay-Per-Click</option>    
			<?php if(!isset($_SESSION['campaign_type'])) echo '<option value="1" selected>Print</option>'; ?>
		</select> 
	</div>
</div>

<div class="row">
	<span class="num"><em>4</em></span>
	<div class="steps-holder">
		<div class="heading">
			<h2>Campaign Subject <span>(ie. iPod, Corolla)</span></h2>
			<?php # Help Button
			$helphref = 'create-campaign-step1-subject.html';
			$helptitle = 'Help with Campaign Subjects';
			include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
			?>
		</div>
		<input tabindex="4" type="text" class="text" name="subject" id="subject" maxlength="75" value="<?php if (isset($_SESSION['campaign_subject'])) 
echo $_SESSION['campaign_subject']; ?>" onblur="checkSubject(document.campform.subject.value)"  /><br />
		<div id="subjectresults"></div>
	</div>
</div>

<div class="row">
	<span class="num"><em>5</em></span>
	<div class="steps-holder">
		<div class="heading">
			<h2>Budgeted Campaign Cost <span>(i.e. 5755.25)</span></h2>
			<?php # Help Button
			$helphref = 'create-campaign-step1-cost.html';
			$helptitle = 'Help with Campaign Costs';
			include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
			?>
		</div>
		<input tabindex="5" type="text" class="text" name="cost" id="cost" maxlength="20" value="<?php if (isset($_SESSION['campaign_cost'])) 
echo $_SESSION['campaign_cost']; ?>" onblur="checkCost(document.campform.cost.value)" /><br />
<div id="costresults"></div>
	</div>
</div>

<div class="row">
	<span class="num"><em>6</em></span>
	<div class="steps-holder">
		<div class="heading">
			<h2>Campaign Duration <span>(MM/DD/YYYY)</span></h2>
			<?php # Help Button
			$helphref = 'create-campaign-step1-duration.html';
			$helptitle = 'Help with Campaign Durations';
			include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
			?>
			<div style="display: inline;" id="campaignduration"></div>
		</div>
		<div class="calendar-box">
			<div class="calendar">
				<input tabindex="6" type="text" class="text" name="beginDate" id="beginDate" 
				<?php 
					echo 'value="Start (Required)                          ' . date('m/d/Y') . '"';
				?> onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" onchange="checkDuration()">
				<script language="JavaScript">
				new tcal ({
					// form name
					'formname': 'campform',
					// input name
					'controlname': 'beginDate'
				});
				</script>
			</div>
			<div class="calendar">
				<input tabindex="7" type="text" class="text" name="endDate" id="endDate" 
				<?php 
					echo ' value="End (Not Required)                          ' . date('m/d/Y') . '"';
				?> onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" onchange="checkDuration()">
				<script language="JavaScript">
				new tcal ({
					// form name
					'formname': 'campform',
					// input name
					'controlname': 'endDate'
				});
				</script>
			</div>
		</div>
	</div>
</div>

<div class="coll">
	<div class="heading">
		<input tabindex="8" type="checkbox" name="import" id="import" class="check" />
		<label tabindex="9" for="import">One-Time Lead Import <span>(for past campaigns)</span></label>
			<?php # Help Button
			$helphref = 'create-campaign-step1-import.html';
			$helptitle = 'Help with One-Time Lead Imports';
			include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
			?>
	</div>
</div>

<br/><br/>

<div class="content-link">
	<div class="link-holder">
		<div class="link-frame">
			<a href="#" class="btn" onclick="document.getElementById('create_campaign_step1').submit(); return validateAll()" /><span>Save &amp; Go To Next Step &gt;</span></a>
			<em>or</em>
			<a href="/create/campaign/cancel.php">Cancel</a>
		</div>
	</div>
</div>

<input type="hidden" name="submitted" value="TRUE" /> 

</fieldset>
</form>
</div>

<div class="alert-holder">
	<div class="alert-box yellow">
		<div class="holder">
			<div class="frame">
				<p>Don't worry - you'll have a chance to review your campaign before you finalize it.</p>
			</div>
		</div>
	</div>
</div>

<br/><br/>

<!-- / Main Content -->

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
