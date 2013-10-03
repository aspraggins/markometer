<?php # Edit Existing Campaigns Page
$metatitle = 'Manage Campaigns';
$returnurl = '/login/?returnpage=build/campaigns';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Heading and Help Button -->

<div class="heading">
<h1>Manage a Marketing Campaign
<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/current-account.php'); ?>
</h1>
<?php # Help Button
$helphref = 'build-campaigns-edit.html';
$helptitle = 'Help with Campaign Names';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
?>
</div>

<!-- Main Content -->

<?php
//MYSQL Setup:
require_once(MYSQL);

//Evaluate submission of INDIVIDUAL CAMPAIGN form ############################
if (isset($_POST['indsubmitted'])) {
	$id = $_GET['id'];
	
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
		$actionpage = 0;
		if (isset($_POST['actionpages'])) $actionpage = $_POST['actionpages'];
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

		if (mysqli_affected_rows ($dbc) == 1 || $actionpage==0) $actionpageupdate = TRUE;

		//check to see if both updates happened:
		if($campaignupdate && $actionpageupdate) {
		//Move to manage campaigns overview:
			$url = '/build/campaigns/edit/?id=' . $id . '&saved=yes';
			header("Location: $url");
		}else{
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>Please select an action page from the dropdown box!</p>
			</div>
		</div>
	</div>
</div>';
		}
	}else{
		/*echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>Doesn\'t Pass Validation</p>
			</div>
		</div>
	</div>
</div>';*/
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
//if(isset($_GET['id']) && !isset($_POST['indsubmitted'])) {
if(isset($_GET['id'])) {	
	
	$campaign_id = $_GET['id'];

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
			<p>Your lead was successfully added. <a href="/leads/manage/?filter=last">View it now</a>.</p>
			</div>
		</div>
	</div>
</div>';
		}elseif($_GET['saved']=='file') {
		echo '
<div class="alert-holder">
	<div class="alert-box green">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>Your leads were successfully added. <a href="/leads/manage/?filter=last">View them now</a>.</p>
			</div>
		</div>
	</div>
</div>';
		}
	}
	
	$q = "SELECT campaigns.id, campaigns.name, campaigns.type, campaigns.action_page, action_pages.form_id, " . 
	"campaigns.date_begins, campaigns.date_ends, campaigns.phone, campaigns.domain, " . 
	"campaigns.goal, campaigns.subject, campaigns.domain, campaigns.rate, campaigns.cost, " . 
	"campaigns.archive_date, campaigns.archived, campaigns.actual_cost, campaigns.imported " . 
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
	
	if($domain=='0') $_SESSION['campaign_domain'] = $domain = 'No Domain Name Chosen';
	if($domain=='nodomain') $_SESSION['campaign_domain'] = $domain = 'No Domain Name Chosen';
	if($phone=='0') $_SESSION['campaign_phone'] = $phone = 'No Phone Number Chosen';
	if($phone=='nophone') $_SESSION['campaign_phone'] = $phone = 'No Phone Number Chosen';
	
	//Handle PPC instance:
	if ($row['type']==6) {
		//create dashed name for this campaign name:
		$name = $row['name'];
		$special_chars = array("?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}", chr(0));
		$name = str_replace($special_chars, '', $name);
		$name = preg_replace('/[\s-]+/', '-', $name);
		$name = trim($name, '.-_');
		
		$domain = 'Display URL (to show in ad):' . $_SESSION['mp_subdomain'] . 
			'.mp41.com | Destination URL:' . 
			$_SESSION['mp_subdomain'] . '.mp41.com/' . $name;
	}
	
	//Look up actual charged rate for user's requested level of service:
	$_SESSION['campaign_level'] = $ratelookup = $row['rate'];
	//$q = "SELECT * FROM rates WHERE level='$ratelookup'";
	//$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
	//. mysqli_error($dbc));

	//$row2 = mysqli_fetch_array($r, MYSQLI_ASSOC);
	//$_SESSION['campaign_rate'] = $rate = $row2['rate'];	
	$_SESSION['campaign_rate'] = $rate = '199';
	
	$campaignend = strtotime($row['date_ends']);

	//Initialize status variable for later use:
	if(time()<strtotime($row['date_begins'])) {
		//campaign hasn't started yet
		if(strtotime($row['date_ends'])<1000000000) {
			//open ended:
			$status = 'Pending (Starts ' . date("m/d/y", strtotime($row['date_begins'])) . ', Open Ended)';
		} else {
			//set end date:
			$status = 'Pending (Starts ' . date("m/d/y", strtotime($row['date_begins'])) . ', Ends ' . date("m/d/y", $campaignend) . ')';			
		}
	}elseif(time()>strtotime($row['date_begins'])) {
		//Campaign Begin date has passed
		//open ended?
		if(strtotime($row['date_ends'])<1000000000) {
			$status = 'Active (Started ' . date("m/d/y", strtotime($row['date_begins'])) . ', Open Ended)';
		} else {
			if(time()<$campaignend) {
				//campaign is currently active
				$status = 'Active (Started ' . date("m/d/y", strtotime($row['date_begins'])) . ', Ends ' . date("m/d/y", $campaignend) . ')';
			} else {
				//campaign is over
				$status = 'Complete (Started ' . date("m/d/y", strtotime($row['date_begins'])) . ', Ended ' . date("m/d/y", $campaignend) . ')';				
			}
		}
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
			<td><span>' . $status . '</span></td>
		</tr>
		<tr class="add">
			<td class="title"><strong>Campaign Type:</strong></td>
			<td><span>
';
		if ($row['type']==1) echo 'Print Advertisement';
		if ($row['type']==2) echo 'Radio';		
		if ($row['type']==3) echo 'Television';
		if ($row['type']==4) echo 'Banner';
		if ($row['type']==5) echo 'Email Marketing';
		if ($row['type']==6) echo 'Pay-Per-Click';
			
echo '
			</span></td>
		</tr>';

		//show domain and phone info if this is not an imported campaign:
		//change text if this is a PPC campaign:
		$domainlabel = 'Domain Name:';
		if ($row['type']==6) $domainlabel = 'Links:';
		if ($row['imported']=='N') {
			echo '<tr>
			<td class="title"><strong>' . $domainlabel . '</strong></td>
			<td><span>' . $domain . '</span><!-- <a href="#" class="btn-copy"><span>Copy</span></a> --></td>
		</tr>
		<tr class="add">
			<td class="title"><strong>Phone Number:</strong></td>
			<td><span>' . $phone . '</span><!-- <a href="#" class="btn-copy"><span>Copy</span></a> --></td>
		</tr>';
		}
		
		//calculate campaign duration in months, generate text for that or open-ended as needed:
		if(strtotime($row['date_ends'])<1000000000) {		
			//open ended campaign:
			$months = '(Until you end the campaign)';
		} else {
			//fixed end date calculation:
			$diff = abs(strtotime($row['date_ends']) - strtotime($row['date_begins']));
			$months = '(for ' . ceil($diff / (30*60*60*24)) . ' month(s) once the campaign starts)';
		}
		
		echo '<tr>
			<td class="title"><strong>Monthly Cost:</strong></td>
			<td><span>$' . $rate . '/Month ' . $months . '</span></td>
		</tr>';
		
		//give action page drop down if this is a domain or PPC campaign:
		if ($row['domain']!='nodomain' || $row['type']==6) {
			echo '
		<tr class="add">
			<td class="title"><strong>Action Page:</strong></td>
			<td>
				<select name="actionpages">';
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
		</tr>';
		}
		
		echo '</table>
</div>

<div class="section-holder">
<div>

<div class="row">
	<div class="heading">
		<h3>What is Your Goal? <span>(Begin with the end in mind, i.e. 2 new sales)</span></h3>
';
			# Help Button
			$helphref = 'build-campaigns-goal.html';
			$helptitle = 'Help with Campaign Goals';
			include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
echo '
	</div>
	<input type="text" class="text" name="goal" size="75" maxlength="200" value="' . $row['goal'] . '" />
</div>
<div class="row">
	<div class="heading">
		<h3>Campaign Name <span>(i.e. Business Week Ad or CES Trade Show)</span></h3>
';
			# Help Button
			$helphref = 'build-campaigns-name.html';
			$helptitle = 'Help with Campaign Names';
			include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
echo '
	</div>
	<input type="text" class="text" name="campaignname" size="75" maxlength="200" value="' . $row['name'] . '" />
</div>
<div class="row">
	<div class="heading">
		<h3>Campaign Subject <span>(i.e. iPod, Corolla)</span></h3>
';
			# Help Button
			$helphref = 'build-campaigns-subject.html';
			$helptitle = 'Help with Campaign Subjects';
			include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
echo '
	</div>
	<input type="text" name="subject" size="75" maxlength="75" value="' . $row['subject'] . '" class="text" />
</div>
<div class="row">
	<div class="heading">
		<h3>Budgeted Campaign Cost <span>(i.e. 5775.25)</span></h3>
';
			# Help Button
			$helphref = 'build-campaigns-cost.html';
			$helptitle = 'Help with Campaign Costs';
			include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
echo '
	</div>
	<input type="text" class="text" name="cost" size="35" maxlength="9" value="' . $row['cost'] . '" />
</div>
<div class="row">
	<div class="heading">
		<h3>Actual Campaign Cost <span>(i.e. 5775.25)</span></h3>
';
			# Help Button
			$helphref = 'build-campaigns-cost.html';
			$helptitle = 'Help with Campaign Costs';
			include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
echo '
	</div>
	<input type="text" class="text" name="actualcost" size="35" maxlength="9" value="' . $row['actual_cost'] . '" />
</div>
<div class="row">
	<div class="heading">
		<h3>Campaign Duration <span>(XX/XX/XXXX)</span></h3>
';
			# Help Button
			$helphref = 'build-campaigns-duration.html';
			$helptitle = 'Help with Campaign Durations';
			include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
echo '
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
			<a href="/build/campaigns/" class="cancel">Cancel</a>
		</div>
	</div>
</div>
';

$importfilestring = "'/build/campaigns/edit/import/file/select/?id=" . $campaign_id . "'";
$importonestring = "'/build/campaigns/edit/import/one/?id=" . $campaign_id . "'";

echo '
<div class="bar">
	<strong>Import Leads:</strong>
';
			# Help Button
			$helphref = 'build-campaigns-import-leads.html';
			$helptitle = 'Help with Importing Leads';
			include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
echo '
	<a href="#" onclick="window.location.href=' . $importfilestring . '" class="btn"><span>From File &gt;</span></a>
	<a href="#" onclick="window.location.href=' . $importonestring . '" class="btn"><span>One-at-a-Time &gt;</span></a>
</div>

<div class="content-link">
	<div class="link-holder">
		<div class="link-frame">
			<a class="btn" href="/build/campaigns/new/"><span>Create a New Campaign &gt;</span></a>
		</div>
	</div>
</div>

</div>

</fieldset>
</form>

</div>

';
	}else{
		echo 'An error has occurred. Please try again!';
	}

}
?>

<!-- / Main Content -->

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
