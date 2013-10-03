<?php # Create New Campaign Step 3 (Review and Confirm)
$metatitle = 'Create a New Marketing Campaign - Step 3 of 3';
$returnurl = '/login/?returnpage=build/campaigns/new';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Heading and Help Button -->

<div class="heading">
<h1>Create a New Marketing Campaign - Step 3/3
<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/current-account.php'); ?>
</h1>
</div>

<!-- Main Content -->

<?php
//Database:
require_once(MYSQL);

if (isset($_POST['submitted'])) {
	if(isset($_POST['finalize'])) {
		//Check to see if this is a lead import (if is, campaign created in last step)
		if($_SESSION['leadimport']=='N') { // standard, non-imported campaign:
			// Write off all information to new campaign record in database:
			$q = "INSERT INTO campaigns (name, date_created, account_id, type, 
			subject, date_begins, date_ends, domain, phone, goal, cost, last_updated, imported) VALUES('{$_SESSION['campaign_name']}', NOW(), {$_SESSION['account_id']}, 
			'{$_SESSION['campaign_type']}','{$_SESSION['campaign_subject']}', 
			'{$_SESSION['campaign_begin']}', '{$_SESSION['campaign_end']}',
			'{$_SESSION['campaign_domain']}', '{$_SESSION['campaign_pnumber']}', 
			'{$_SESSION['campaign_goal']}', '{$_SESSION['campaign_cost']}', NOW(), 'N')";

			$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
			. mysqli_error($dbc));
  
			//Check success of campaign table insert:
			if (mysqli_affected_rows($dbc) == 1) {
				//Retrieve record number for insert, put into session variable:
				$_SESSION['campaignid'] = mysqli_insert_id($dbc);
				//Unset session variables that stored campaign information:
				$_SESSION['campaign_goal'] = NULL; unset($_SESSION['campaign_goal']);
				$_SESSION['campaign_type'] = NULL; unset($_SESSION['campaign_type']);
				$_SESSION['campaign_cost'] = NULL; unset($_SESSION['campaign_cost']);
				$_SESSION['campaign_subject'] = NULL; unset($_SESSION['campaign_subject']);
				$_SESSION['leadimport'] = NULL; unset($_SESSION['leadimport']);
		
				//update current account record to show that a campaign has been created, account no longer new:
				$q = "UPDATE accounts " .
				"SET updateflag=1-updateflag, new='N' " . 
				"WHERE id='{$_SESSION['account_id']}' LIMIT 1"; 
		
				$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
				. mysqli_error($dbc));

				//Update session variable indicating that this is no longer a "new" account:
				$_SESSION['newaccount'] = 'N';

				//Move to next page in campaign startup:
				$url = '/build/campaigns/new/done/';
				header("Location: $url");
				exit();
			} else {
				//If it did not run okay
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Your campaign could not be registered due to a system error. We apologize for any inconvenience.</p>
			</div>
		</div>
	</div>
</div>';
			}
		} else { //imported campaign:
			//set imported field to Yes in record for this new campaign,
			//so know that it is active and can be charged:
			$q = "UPDATE campaigns " . 
			"SET imported='A'" . 
			"WHERE id='{$_SESSION['campaignid']}' LIMIT 1";

			$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
			. mysqli_error($dbc));	
			
			//update leads to be New instead of Pending:
			$q = "UPDATE leads " . 
			"SET status='N'" . 
			"WHERE campaign_id='{$_SESSION['campaignid']}'";

			$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
			. mysqli_error($dbc));	
			
			//Unset session variables that stored campaign information:
			$_SESSION['campaign_goal'] = NULL; unset($_SESSION['campaign_goal']);
			$_SESSION['campaign_type'] = NULL; unset($_SESSION['campaign_type']);
			$_SESSION['campaign_cost'] = NULL; unset($_SESSION['campaign_cost']);
			$_SESSION['campaign_subject'] = NULL; unset($_SESSION['campaign_subject']);

			//Move to next page in campaign startup:
			$url = '/leads/?imported=good';
			header("Location: $url");
			exit();
		}
	} else {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>You must check the box agreeing to your campaign terms before we can finalize your campaign.</p>
			</div>
		</div>
	</div>
</div>';
	}	
} 
//Figure out campaign type from number for use in table:
switch($_SESSION['campaign_type']) {
	case '1':
		$type = 'Print Ad'; break;
	case '2':
		$type = 'Radio Ad'; break;
	case '3':
		$type = 'Television Ad'; break;
	case '4':
		$type = 'Banner Ad'; break;
	case '5':
		$type = 'Email Marketing Campaign'; break;
	case '6':
		$type = 'Pay-Per-Click Ad'; break;
	default:
		$type = 'Unknown';
}
?>

<div class="step-container">
	<div class="heading">
<?php
// Variable string for above table text, depending upon whether or not it's an import:
$abovetext = '<h2>Review &amp; Approve Your Campaign <span>(it\'s not active until you press the "Finalize Campaign" button)</span></h2>';
$importedtextabove = '<h2>Review &amp; Approve Your Campaign <span>(it\'s not active and your leads won\'t show up until you press the "Finalize Campaign" button)</span></h2>';

if($_SESSION['leadimport']=='Y') $abovetext = $importedtextabove;

echo $abovetext;
?>
		<?php # Help Button
		$helphref = 'build-campaigns-step-3.html';
		$helptitle = 'Help with Finalizing Your Marketing Campaigns';
		include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
		?>
	</div>
	
<br/>
	
<!-- Table summarizing campaign selections by user: -->

	<div class="container">
		<h3 class="title-head"><?php echo $_SESSION['campaign_name']; ?></h3>

		<div class="content-table">
			<table>
				<tr>
					<td class="title"><strong>Campaign Type:</strong></td>
					<td><?php echo $type; ?></td>
				</tr>
				<tr class="add">
					<td class="title"><strong>Campaign Subject:</strong></td>
					<td><?php echo $_SESSION['campaign_subject']; ?></td>
				</tr>
				<tr>
					<td class="title"><strong>Budgeted Cost:</strong></td>
					<td>$<?php echo $_SESSION['campaign_cost']; ?></td>
				</tr>
				<tr class="add">
					<td class="title"><strong>Campaign Starts:</strong></td>
					<td><?php echo date('F j, Y', strtotime($_SESSION['campaign_begin'])); ?></td>
				</tr>
				<tr>
					<td class="title"><strong>Campaign Ends:</strong></td>
					<td>
					<?php 
					if($_SESSION['campaign_end']!='Open') {
						echo date('F j, Y', strtotime($_SESSION['campaign_end'])); 
					} else {
						echo 'Open-Ended';
					}
					?>
					</td>
				</tr>
				<tr class="add">
					<td class="title"><strong>Campaign Duration:</strong></td>
					<td>
					<?php 
					if($_SESSION['campaign_end']!='Open') {
						$duration = round((strtotime($_SESSION['campaign_end']) - strtotime($_SESSION['campaign_begin']))/86400);
						echo $duration . ' Days';
					}else{
						echo 'Open';
					}
					?>	
					</td>
				</tr>
				<tr>
					<td class="title"><strong>Campaign Goal:</strong></td>
					<td><?php echo $_SESSION['campaign_goal']; ?></td>
				</tr>
<?php
// if not importing contacts, then echo domain and phone:
if($_SESSION['leadimport']!='Y') {
	
	if($_SESSION['campaign_type']!=6) { //(NOT PPC)
echo '
				<tr class="add">
					<td class="title"><strong>Campaign Domain:</strong></td>
					<td>
';
		if(isset($_SESSION['campaign_domain'])) {
//			if ($_SESSION['campaign_domain']=='actionpagelink') {
//				echo $_SESSION['mp_subdomain'] . '.mp41.com/' . $_SESSION['campaign_name_dashes'];
//			} else {
				echo $_SESSION['campaign_domain'];
//			}
		} else { echo 'N/A'; }
	} else { //PPC Campaign:
		echo '
						<tr class="add">
							<td class="title"><strong>Links:</strong></td>
							<td>
		';
		echo 'Display URL(to show in ad):' . $_SESSION['mp_subdomain'] . '.mp41.com/<br />';
		echo 'Destination URL(to link to from ad):' . $_SESSION['mp_subdomain'] . '.mp41.com/' . $_SESSION['campaign_name_dashes'];
	}


echo '
					</td>
				</tr>
				<tr>
					<td class="title"><strong>Campaign Phone:</strong></td>
					<td>
';
	if(isset($_SESSION['campaign_pnumber'])) { 
		if($_SESSION['campaign_pnumber']!='nophone') {
			echo $_SESSION['campaign_pnumber']; 
		} else {
			echo 'N/A';
		}
	} else { echo 'N/A'; }
echo '
					</td>
				</tr>
';
}
?>
			</table>
		</div>
<h3 class="title-head">
<?php
// If it's a one-time lead import do this, otherwise use the default text
if($_SESSION['leadimport']=='Y') {
echo '
One-Time Lead Import Charges: $199
';
}else{
echo '
Campaign Monthly Charges: $199/month
';
}
?>
</h3>
	</div>
	
	<form action="" method="post" name="campform" id="new_campaign_step3" class="steps-form">
		<fieldset>
			<div class="row add">
				<input type="hidden" name="submitted" value="TRUE" />
				<input type="checkbox" name="finalize" value="Y" id="agree_to_terms" class="check-2" />
				<div class="text-holder">
					<label for="agree_to_terms">
<?php
// Variable string for below table text, depending upon whether or not it's an import:
if($_SESSION['campaign_end']!='Open') {
$belowtext = '<strong>Yes, I agree</strong> that when I click the "Finalize Campaign" button I will be billed <strong>$199 per month</strong> from the time this campaign <strong>starts on ' . date('F j, Y', strtotime($_SESSION['campaign_begin'])) . '</strong> until it <strong>ends on ' . date('F j, Y', strtotime($_SESSION['campaign_end'])) . '</strong>.';
}else{
$belowtext = '<strong>Yes, I agree</strong> that when I click the "Finalize Campaign" button I will be billed <strong>$199 per month starting on ' . date('F j, Y', strtotime($_SESSION['campaign_begin'])) . '</strong> and that I will be charged $199 monthly until I end this campaign.';
} 

$importedtextbelow = '<strong>Yes, I agree</strong> that when I click the "Finalize Campaign" button I will be billed <strong>a one-time charge of $199 for setting up this campaign</strong>.';

if($_SESSION['leadimport']=='Y') $belowtext = $importedtextbelow;

echo $belowtext;
?>
					</label>
				</div>
			</div>
		</fieldset>
	</form>

	<div class="content-link">
		<div class="link-holder">
			<div class="link-frame">
				<!-- Need to Update href on Previous Step to Point to Appropriate Step 2 Screen -->
				<a class="btn btn-left" href="/build/campaigns/new/step2/"><span>&lt; Previous Step</span></a>
				<a href="#" class="btn" onclick="document.getElementById('new_campaign_step3').submit();" /><span>Finalize Campaign &gt;</span></a>
				<em>or</em>
				<a href="/build/campaigns/new/cancel/">Cancel</a>
			</div>
		</div>
	</div>
</div>

<div class="alert-holder">
	<div class="alert-box yellow">
		<div class="holder">
			<div class="frame">
				<p>Note: Your campaign is not active until you press the "Finalize Campaign" button.</p>
			</div>
		</div>
	</div>
</div>

<br/><br/>

<!-- / Main Content -->

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
