<?php # Edit Existing Campaigns Page
$metatitle = 'Manage Campaigns';
$returnurl = '/login/?returnpage=manage/campaigns/';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Heading and Help Button -->

<div class="heading">
<h1>Manage a Marketing Campaign
<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/current-account.php'); ?>
</h1>
<a href="help/manage-marketing-campaign.html" class="btn-help" title="Help" onclick="Modalbox.show(this.href, {title: this.title, width: 600, evalScript: true}); return false;"><span>Help?</span></a>
</div>

<!-- Main Content -->

<?php
//MYSQL Setup:
require_once(MYSQL);

//Evaluate submission of INDIVIDUAL CAMPAIGN form ############################
if (isset($_POST['indsubmitted'])) {
	$id = $_GET['indid'];
	
    //Assume invalid values:
    $goalcheck = $cname = $datecheck = $namecheck = $subjectcheck = FALSE;

    //Trim all of the incoming data:
    $trimmed = array_map('trim', $_POST);

    //Check for a valid campaign name:
    if(strlen($trimmed['campaignname'])>2 && strlen($trimmed['campaignname'])<200) {
		$cname = mysqli_real_escape_string($dbc, $trimmed['campaignname']);
		
		//SQL hit to recheck availability of name:
		$q = "SELECT * FROM campaigns WHERE name='$cname' AND account_id={$_SESSION['account_id']}";

		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
		Error: " . mysqli_error($dbc));

		$num = mysqli_num_rows($r);

		if($num!=0 && $_POST['begincampaignname']!=$cname) {
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
	if(strlen($trimmed['endDate'])>0) {
		if(strtotime($trimmed['beginDate'])-strtotime($trimmed['endDate'])>0) {
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
		} else {
			$datecheck = TRUE;
		}
	} else {
		$datecheck = TRUE;
	}
	
	if($namecheck && $datecheck && $goalcheck && $subjectcheck && $costcheck) {
		//Set query flags to False:
		$campaignupdate = FALSE; $actionpageupdate = FALSE;

		//fields validate, update campaign record:
		$actionpage = $_POST['actionpages'];
		$subject = $_POST['subject']; $cost = $_POST['cost']; $actualcost = $_POST['actualcost'];
		$beginDate = date("Y-m-d", strtotime($trimmed['beginDate']));

		if($trimmed['endDate']!='') {
			$endDate = date("Y-m-d", strtotime($trimmed['endDate']));
		}else{
			$endDate = '';
		}
		$goal = $_POST['goal'];
		
		$q = "UPDATE campaigns " . 
		"SET updateflag=1-updateflag, action_page='$actionpage', name='$cname', " . 
		"subject='$subject', cost='$cost', actual_cost='$actualcost', date_begins='$beginDate', " . 
		"date_ends='$endDate', goal='$goal', last_updated=NOW() " . 
		"WHERE id='$id' LIMIT 1";
		
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
		Error: " . mysqli_error ($dbc)); 

		if (mysqli_affected_rows ($dbc) == 1) $campaignupdate = TRUE;

		$q = "UPDATE action_pages " . 
		"SET updateflag=1-updateflag, last_updated=NOW() " . 
		"WHERE id='$actionpage' LIMIT 1";
		
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
		Error: " . mysqli_error ($dbc)); 

		if (mysqli_affected_rows ($dbc) == 1) $actionpageupdate = TRUE;

		//check to see if both updates happened:
		if($campaignupdate && $actionpageupdate) {
		//Move to manage campaigns overview:
			$url = '/manage/campaigns/edit?indid=' . $id . '&saved=yes';
			header("Location: $url");
		}else{
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>There was an error processing your request. Please try again.</p>
			</div>
		</div>
	</div>
</div>';
		}
	}else{
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>Doesn\'t Pass Validation</p>
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

//INDIVIDUAL ACCOUNT PAGE: ################################################
//Evaluate if campaign id number is being passed. If so, display summary of that
//campaign. If not, show table of campaigns for this account. 
if(isset($_GET['indid']) && !isset($_POST['indsubmitted'])) {
	$campaign_id = $_GET['indid'];

	if(isset($_GET['saved'])) {
		if($_GET['saved']=='yes') {
		echo '
<div class="alert-holder">
	<div class="alert-box green">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>Campaign settings have been saved successfully!</p>
			</div>
		</div>
	</div>
</div>';
		}elseif($_GET['saved']=='one') {
		echo '
<div class="alert-holder">
	<div class="alert-box green">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>Lead successfully added.</p>
			</div>
		</div>
	</div>
</div>';
		}
	}
	
	$q = "SELECT campaigns.id, campaigns.name, campaigns.type, campaigns.action_page, action_pages.form_id, " . 
	"campaigns.date_begins, campaigns.date_ends, campaigns.phone, campaigns.domain, " . 
	"campaigns.goal, campaigns.subject, campaigns.domain, campaigns.rate, campaigns.cost, " . 
	"campaigns.archive_date, campaigns.archived, campaigns.actual_cost " . 
	"FROM campaigns LEFT JOIN action_pages ON campaigns.action_page=action_pages.id " . 
	"WHERE campaigns.id='$campaign_id'";
	
	$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
	Error: " . mysqli_error ($dbc));

	$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
	//Store session variables in case user "Changes Settings":
	$_SESSION['campaign_name'] = $row['name'];
	$_SESSION['campaign_domain'] = $domain = $row['domain']; 
	$_SESSION['campaign_phone'] = $phone = $row['phone'];
	$_SESSION['campaign_begin'] = date("m/d/y", strtotime($row['date_begins']))	;
	$_SESSION['campaign_end'] = $row['date_ends']	;	
	
	if($domain=='0') $_SESSION['campaign_domain'] = $domain = 'N/A';
	if($phone=='0') $_SESSION['campaign_phone'] = $phone = 'N/A';
	
	//Look up actual charged rate for user's requested level of service:
	$_SESSION['campaign_level'] = $ratelookup = $row['rate'];
	//$q = "SELECT * FROM rates WHERE level='$ratelookup'";
	//$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
	//. mysqli_error($dbc));

	//$row2 = mysqli_fetch_array($r, MYSQLI_ASSOC);
	//$_SESSION['campaign_rate'] = $rate = $row2['rate'];	
	$_SESSION['campaign_rate'] = $rate = '199';
	
	//Handle open-ended campaigns, which will have a '0000-00-00....' type value in the database:
	$campaignend = strtotime($row['date_ends']);
	if(strtotime($row['date_ends'])<1000000000) $campaignend = 3000000000;
	
	//Initialize status variable for later use:
	if(time()<strtotime($row['date_begins'])) {
		$status = 'Pending (starts ' . date("m/d/y", strtotime($row['date_begins'])) . ')';
	}elseif(time()>strtotime($row['date_begins']) && time()<$campaignend) {
		$status = 'Active (started ' . date("m/d/y", strtotime($row['date_begins'])) . ')';
	}elseif(time()>$campaignend) {
		$status = 'Complete (ended ' . date("m/d/y", $campaignend) . ')';
	}
	$archivebuttontext = 'Archive Campaign';
	$archiveclickstring = "'/manage/campaigns/archive.php?id=" . $row['id'] . 
	"&action=arch'\"";
	
	if($row['archived']=='Y') {
		$status = 'Archived (on ' . $row['archive_date'] . ')';
		$archivebuttontext = 'Unarchive Campaign';
		$archiveclickstring = "'/manage/campaigns/archive.php?id=" . $row['id'] . 
		"&action=unarch'\"";
	}
		
	if (mysqli_affected_rows ($dbc) == 1) {
		//Table with monthly cost, domain name, phone:
		echo '
<div class="main-block">

<form action="" method="post" id="edit_campaign" name="campform" class="main-form">
<fieldset>

<div class="section-container">
	<div class="head">
		<h2>' . $row['name'] . '</h2>
		<a href="#">(Show/Edit More Info)</a>
	</div>
	
<div class="section-table">
	<table>
		<tr>
			<td class="title"><strong>Campaign Status:</strong></td>
			<td><span>' . $status . ' (Starts 11/30/09, Ends 1/15/2010)</span></td>
		</tr>
		<tr class="add">
			<td class="title"><strong>Campaign Type:</strong></td>
			<td><span>
';
		if ($row['type']==1) echo 'Print';
		if ($row['type']==2) echo 'Radio';		
		if ($row['type']==3) echo 'Television';
		if ($row['type']==4) echo 'Banner';
		if ($row['type']==5) echo 'Email Marketing';
		if ($row['type']==6) echo 'Pay-Per-Click';
			
echo '
			</span></td>
		</tr>
		<tr>
			<td class="title"><strong>Domain Name:</strong></td>
			<td><span>' . $domain . '</span><!-- <a href="#" class="btn-copy"><span>Copy</span></a> --></td>
		</tr>
		<tr class="add">
			<td class="title"><strong>Phone Number:</strong></td>
			<td><span>' . $phone . '</span><!-- <a href="#" class="btn-copy"><span>Copy</span></a> --></td>
		</tr>
		<tr>
			<td class="title"><strong>Monthly Cost:</strong></td>
			<td><span>$' . $rate . '/Month (for XXX months once the campaign starts)</span></td>
		</tr>
		<tr class="add">
			<td class="title"><strong>Action Page:</strong></td>
			<td>
				<select name="actionpages">
				<option value="Thing Stuff Thing No. 1">Thing Stuff Thing No. 1</option>
';
				if ($row['action_page']>0) {
					echo '<option value="' . $row['action_page'] . '" selected="selected">' . 
					$actionpagesarray[$row['action_page']] . '</option>';
				} else {
					echo '<option value="0" selected="selected">No Page Selected</option>';
				}	
				foreach($actionpagesarray as $key => $apages) {
					echo '<option value="' . $key . '">' . $apages . '</option>';
				}
echo '
				</select>
			</td>
		</tr>
	</table>
</div>

<div class="section-holder">
<div>

<div class="row">
	<div class="heading">
		<h3>What is Your Goal? <span>(Begin with the end in mind, i.e. 2 new sales)</span></h3>
		<a href="#" class="btn-help"><span>Help?</span></a>
	</div>
	<input type="text" class="text" name="goal" size="75" maxlength="200" value="' . $row['goal'] . '" onblur="checkGoal(document.campform.goal.value)" />
	<div id="goalresults"></div>
</div>
<div class="row">
	<div class="heading">
		<h3>Campaign Name <span>(i.e. Business Week Ad or CES Trade Show)</span></h3>
		<a href="#" class="btn-help"><span>Help?</span></a>
	</div>
	<input type="text" class="text" name="campaignname" size="75" maxlength="200" value="' . $row['name'] . '" onchange="checkCampaignName(document.campform.campaignname.value)" />
	<div id="nameresults"></div>
</div>
<div class="row">
	<div class="heading">
		<h3>Campaign Subject <span>(i.e. iPod, Corolla)</span></h3>
		<a href="#" class="btn-help"><span>Help?</span></a>
	</div>
	<input type="text" name="subject" size="75" maxlength="75" value="' . 
		$row['subject'] . '" onblur="checkSubject(document.campform.subject.value)" class="text" />
	<div id="subjectresults"></div>
</div>
<div class="row">
	<div class="heading">
		<h3>Budgeted Campaign Cost <span>(i.e. 5775.25)</span></h3>
		<a href="#" class="btn-help"><span>Help?</span></a>
	</div>
	<input type="text" class="text" name="cost" size="35" maxlength="9" value="' . $row['cost'] . '" onchange="checkCost(document.campform.cost.value)" />
	<div id="costresults"></div>
</div>
<div class="row">
	<div class="heading">
		<h3>Actual Campaign Cost <span>(i.e. 5775.25)</span></h3>
		<a href="#" class="btn-help"><span>Help?</span></a>
	</div>
	<input type="text" class="text" name="actualcost" size="35" maxlength="9" value="' . $row['actual_cost'] . '" onchange="checkActualCost(document.campform.actualcost.value)" />
	<div id="actualcostresults"></div>
</div>
<div class="row">
	<div class="heading">
		<h3>Campaign Duration <span>(XX/XX/XXXX)</span></h3>
		<a href="#" class="btn-help"><span>Help?</span></a>
		<div style="display: inline;" id="campaignduration"></div>
	</div>
	<div class="calendar-box">
		<div class="calendar">
			<input type="text" class="text" name="beginDate" id="beginDate"' . 'value="' . date('m/d/Y',strtotime($row['date_begins'])) . '" onchange="checkDuration()"/>
			<script language="JavaScript">
			new tcal ({
			// form name
			\'formname\': \'campform\',
			// input name
			\'controlname\': \'beginDate\'
			});
			</script>
		</div>
';
		//Evaluate date_ends to see if an open-ended campaign:
		$dateendstring = '';
		if($row['date_ends']!='0000-00-00 00:00:00') $dateendstring = date('m/d/Y',strtotime($row['date_ends']));
echo '		
		<div class="calendar">
			<input type="text" class="text" name="endDate" id="endDate"' . 'value="' . $dateendstring . '" onchange="checkDuration()"/>
			<script language="JavaScript">
			new tcal ({
			// form name
			\'formname\': \'campform\',
			// input name
			\'controlname\': \'endDate\'
			});
			</script>
		</div>
	</div>
</div>

</div>
</div>
';

// Store beginning value of campaign name as POST variable for comparison during validation:

echo '
<input type="hidden" name="begincampaignname" value="' . $row['name'] . '" />
<input type="hidden" name="indsubmitted" value="TRUE" />

<div class="slide-content-link">
	<div class="link-holder">
		<div class="link-frame">
			<a href="#" class="btn" onclick="document.getElementById(\'edit_campaign\').submit(); return validateAll()" /><span>Update Campaign Info &gt;</span></a>
			<em>or</em>
			<a href="#" class="cancel">Cancel</a>
		</div>
	</div>
</div>
';

$importfilestring = "'/manage/campaigns/import/file/?id=" . $campaign_id . "'";
$importonestring = "'/manage/campaigns/import/one/?id=" . $campaign_id . "'";

echo '
<div class="bar">
	<strong>Import Leads:</strong>
	<a href="#" class="btn-help"><span>Help?</span></a>
	<a href="#" onclick="window.location.href=' . $importfilestring . '" class="btn"><span>From File &gt;</span></a>
	<a href="#" onclick="window.location.href=' . $importonestring . '" class="btn"><span>One-at-a-Time &gt;</span></a>
</div>

<div class="content-link">
	<div class="link-holder">
		<div class="link-frame">
			<a class="btn" href="/create/campaign/step1/"><span>Create a New Campaign &gt;</span></a>
		</div>
	</div>
</div>

</div>

</fieldset>
</form>

</div>

<script language="JavaScript">checkDuration();</script>
';
	}else{
		echo 'An error has occurred. Please try again!';
	}

}
?>

<!-- / Main Content -->

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
