<?php # Manage Leads
$metatitle = 'Manage Leads';
$returnurl = '/login/?returnpage=leads/';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<?php
//Parse incoming filter to decide what to show:
$filter = '';
if(isset($_GET['filter'])) $filter = $_GET['filter'];
$label = '';
switch($filter) {
	case 'n':
		$label = 'New Leads';
		$filterstring = " AND status='N' ";
		break;
	case 'o':
		$label = 'Overdue Leads';
		$filterstring = " AND lead_followups.due_date!='0000-00-00 00:00:00' AND DATE(lead_followups.due_date)<CURDATE() ";
		break;
	case 't':
		$label = 'Leads Due Today';
		$filterstring = " AND DATE(lead_followups.due_date)=CURDATE() ";
		break;
	case 'q':
		$label = 'Quoted Leads';
		$filterstring = " AND (stage='QT' OR stage='RP') ";
		break;
	case 's':
		$label = 'Sold Leads';
		$filterstring = " AND stage='SD' ";
		break;
	case 'j':
		$label = 'Junk Leads';
		$filterstring = " AND status='J' ";
		break;
	case 'd':
		$label = 'Dead Leads';
		$filterstring = " AND status='D' ";
		break;

	case 'a':
		$label = 'Active Leads';
		$filterstring = " AND status='A' ";
		break;
	case 'i':
		$label = 'Inactive Leads';
		$filterstring = " AND status='I' ";
		break;
	case 'last':
		$label = 'Last Imported Leads';
		$filterstring = " AND last_imported='Y' ";
		break;
	case 'all':
		$label = 'All Leads';
		$filterstring = '';
		break;
	case 'w':
		$label = 'Leads Due This Week';
		$filterstring = " AND YEARWEEK(lead_followups.due_date)=YEARWEEK(current_date) ";
		break;
	case '30':
		$label = 'Leads Older Than 30 Days';
		$filterstring = " AND date_created<DATE_SUB(NOW(),interval 30 day) ";
		break;
	case '90':
		$label = 'Leads Older Than 90 Days';
		$filterstring = " AND date_created<DATE_SUB(NOW(),interval 90 day) ";
		break;
	case '30Touch':
		$label = 'Leads Untouched in the Last 30 Days';
		$filterstring = " AND last_updated<DATE_SUB(NOW(),interval 30 day) ";
		break;
	case '60Touch':
		$label = 'Leads Untouched in the Last 60 Days';
		$filterstring = " AND last_updated<DATE_SUB(NOW(),interval 60 day) ";
		break;
	case '90Touch':
		$label = 'Leads Untouched in the Last 90 Days';
		$filterstring = " AND last_updated<DATE_SUB(NOW(),interval 90 day) ";
		break;
	case 'h':
		$label = 'Hot Leads';
		$filterstring = " AND lead_class='H' ";
		break;
	case 'wm':
		$label = 'Warm Leads';
		$filterstring = " AND lead_class='W' ";
		break;
	case 'c':
		$label = 'Cool Leads';
		$filterstring = " AND lead_class='C' ";
		break;
	case 'cd':
		$label = 'Cold Leads';
		$filterstring = " AND lead_class='Cd' ";
		break;
}

echo '
<!-- Heading and Help Button -->

<div class="heading">
<h1>Manage ' . $label .  '';

include ($_SERVER['DOCUMENT_ROOT'] . '/inc/current-account.php');

echo '
</h1>
';
# Help Button
$helphref = 'leads-manage-bulk.html';
$helptitle = 'Help with Managing Leads';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
echo '
</div>';
?>

<!-- Main Content -->

<div class="main-block">
<!-- Form -->
<form action="#" method="post" name="editleadform" class="main-form" id="editleads">
<fieldset>


<?php
//MYSQL Setup:
require_once(MYSQL);

//variable to check to see if confirmation message needs to be displayed:
$leadsedited = FALSE;

//Handle Submission of form:
if (isset($_POST['submitted'])) {
	//Check for change in lead classification (hot, warm, cool, cold)
	if ($_POST['TableAction']=='H' || $_POST['TableAction']=='W' 
		|| $_POST['TableAction']=='C' || $_POST['TableAction']=='Cd') {	
		//Store which value for status in variable:
		$classchange = $_POST['TableAction'];

		//Put lead id values in array:
		$selectleadarray = array();
		if (isset($_POST["selectlead"])) $selectleadarray = $_POST["selectlead"];
		
		foreach ($selectleadarray as $lead) {
			$q = "UPDATE leads 
			SET updateflag=1-updateflag, lead_class='$classchange' 
			WHERE idlead='$lead' LIMIT 1";

			$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
			Error: " . mysqli_error ($dbc));

			if (mysqli_affected_rows ($dbc) != 1) {			
				echo 'An error has occurred. Please try your request again. We apologize for the inconvenience.';
				exit();
			}
		}
		$leadsedited = 'Classification changed for selected leads!';
	}
	//Check for action being done:
	if ($_POST['TableAction']=='D' || $_POST['TableAction']=='A' || $_POST['TableAction']=='N' 
		|| $_POST['TableAction']=='I' || $_POST['TableAction']=='J' || $_POST['TableAction']=='INT') {	
		//Store which value for status in variable:
		$statuschange = $_POST['TableAction'];

		//Put lead id values in array:
		$selectleadarray = array();
		if (isset($_POST["selectlead"])) {
			$selectleadarray = $_POST["selectlead"];
		}
		foreach ($selectleadarray as $lead) {
			$q = "UPDATE leads " . 
			"SET updateflag=1-updateflag, status='$statuschange' " . 
			"WHERE idlead='$lead' LIMIT 1";

			$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
			Error: " . mysqli_error ($dbc));

			if (mysqli_affected_rows ($dbc) != 1) {			
				echo 'An error has occurred. Please try your request again. We apologize for the inconvenience.';
				exit();
			}
		}
		$leadsedited = 'Status changed for selected leads!';
	}
	
	//Look for actions that are reminders (numeric values):
	if (is_numeric($_POST['TableAction'])) {
		//Number of days to postpone due_date in variable:
		$postpone = $_POST['TableAction'];

		//Put lead id values in array:
		$selectleadarray = array();
		if (isset($_POST["selectlead"])) {
			$selectleadarray = $_POST["selectlead"];
		}
		foreach ($selectleadarray as $lead) {
			// have to determine if a followup exists for this lead/user:
			$q = "SELECT * " . 
			"FROM lead_followups " . 
			"WHERE lead_id='$lead' AND user_id='{$_SESSION['id']}'";			
	
			$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
			mysqli_error($dbc));

			if (mysqli_num_rows($r) > 0) {
			    // If followup already exists:
			    $q = "UPDATE lead_followups " . 
			    "SET updateflag=1-updateflag, due_date=DATE_ADD(CURDATE(), INTERVAL $postpone DAY) " . 
			    "WHERE lead_id='$lead' AND user_id='{$_SESSION['id']}' LIMIT 1";

			    $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
			    Error: " . mysqli_error ($dbc));

			    if (mysqli_affected_rows ($dbc) != 1) {			
				    echo 'An error has occurred. Please try your request again. We apologize for the inconvenience.';
				    exit();
			    }
			} else {
			    // No followup set for this lead/user yet, so add one:
			    $q = "INSERT INTO lead_followups (lead_id, user_id, due_date, account_id) " . 
			    "VALUES('$lead', '{$_SESSION['id']}', DATE_ADD(CURDATE(), INTERVAL $postpone DAY), '{$_SESSION['account_id']}')";

			    $r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
			    . mysqli_error($dbc));
			  
			    if (mysqli_affected_rows ($dbc) != 1) {			
				    echo 'An error has occurred. Please try your request again. We apologize for the inconvenience.';
				    exit();
			    }
			}
		}
		$leadsedited = 'Follow up edited for selected leads!';
	}
	if ($_POST['TableAction']=='Calendar') {
		// User has entered a specific date
		$formatteddate = date("Y-m-d", strtotime($_POST['followupDate']));
		$formattedtime = date("H:i:s");
		$postpone = $formatteddate . ' ' . $formattedtime;	

		//Put lead id values in array:
		$selectleadarray = array();
		if (isset($_POST["selectlead"])) {
			$selectleadarray = $_POST["selectlead"];
		}
		foreach ($selectleadarray as $lead) {
			// have to determine if a followup exists for this lead/user:
			$q = "SELECT * " . 
			"FROM lead_followups " . 
			"WHERE lead_id='$lead' AND user_id='{$_SESSION['id']}'";			
	
			$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
			mysqli_error($dbc));

			if (mysqli_num_rows($r) > 0) {
			    // If followup already exists:
			    $q = "UPDATE lead_followups " . 
			    "SET updateflag=1-updateflag, due_date='$postpone' " . 
			    "WHERE lead_id='$lead' AND user_id='{$_SESSION['id']}' LIMIT 1";

			    $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
			    Error: " . mysqli_error ($dbc));

			    if (mysqli_affected_rows ($dbc) != 1) {			
				    echo 'An error has occurred. Please try your request again. We apologize
				    for the inconvenience.';
				    exit();
			    }
			} else {
			    // No followup set for this lead/user yet, so add one:
			    $q = "INSERT INTO lead_followups (lead_id, user_id, due_date, account_id) " . 
			    "VALUES('$lead', '{$_SESSION['id']}', '$postpone', '{$_SESSION['account_id']}')";

			    $r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
			    . mysqli_error($dbc));
			  
			    if (mysqli_affected_rows ($dbc) != 1) {			
				    echo 'An error has occurred. Please try your request again. We apologize
				    for the inconvenience.';
				    exit();
			    }
			}
		}
		$leadsedited = 'Follow up edited for selected leads!';
	}
	//Look for action that is deleting followups for leads:
	if ($_POST['TableAction']=='Del') {
		//Put lead id values in array:
		$selectleadarray = array();
		if (isset($_POST["selectlead"])) {
			$selectleadarray = $_POST["selectlead"];
		}
		foreach ($selectleadarray as $lead) {
			// have to determine if a followup exists for this lead/user:
			$q = "SELECT * " . 
			"FROM lead_followups " . 
			"WHERE lead_id='$lead' AND user_id='{$_SESSION['id']}'";			
	
			$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
			mysqli_error($dbc));

			if (mysqli_num_rows($r) > 0) {
			    // Delete followup if exists for this lead:
				$q = "DELETE FROM lead_followups " . 
				"WHERE lead_id='$lead' AND user_id='{$_SESSION['id']}'";

			    $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
			    Error: " . mysqli_error ($dbc));

			    if (mysqli_affected_rows ($dbc) != 1) {			
				    echo 'An error has occurred. Please try your request again. We apologize for the inconvenience.';
				    exit();
			    }
			} 
		}
		$leadsedited = 'Follow up deleted for selected leads!';
	}
}

//Show confirmation message if change made to selected leads
if($leadsedited) {
	echo '
	<div class="alert-holder">
		<div class="alert-box green">
			<div class="holder">
				<div class="frame">
					<a class="close" href="#">X</a>
						<p>' . $leadsedited . '</p>
				</div>
			</div>
		</div>
	</div>';
}

//Build Page:
//parse incoming campaign number from dashboard, if exists:
$campaign = '';
if(isset($_GET['camp'])) $campaign = "AND leads.campaign_id='" . $_GET['camp'] . "' ";

//Build query for leads:
//added 5/2011, need choice between two queries, depending upon
//whether or not it's looking for what's due this week:
//most situations:
if($filter!='o' AND $filter!='w' AND $filter!='t') {
	$q = "SELECT idlead, contact_first, contact_last, company, email, " . 
	"status, date_created, last_updated " . 
	"FROM leads " . 
	"WHERE account_id='{$_SESSION['account_id']}' " . $filterstring . "AND status!='P'" . 
	"ORDER BY contact_last";
} else {	
	//looking for what's due this week:
	$q = "SELECT leads.idlead, leads.contact_first, leads.contact_last, " . 
	"leads.company, leads.email, " . 
	"leads. status, leads.date_created, leads.last_updated, lead_followups.due_date " . 
	"FROM leads " . 
	"INNER JOIN lead_followups " . 
	"ON leads.idlead=lead_followups.lead_id " . 
	"WHERE lead_followups.user_id='{$_SESSION['id']}' " . 
	"AND lead_followups.account_id='{$_SESSION['account_id']}' " . $filterstring . 
	" AND leads.status!='P' " . $campaign . 
	" ORDER BY leads.contact_last";
}

$showme = $q;

$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

if (mysqli_num_rows($r) > 0) {
	//Begin Form:
	echo '
<div class="make-edit">
	<div class="row">
		<div class="row-holder">
			<label>Edit Selected Leads:</label>
			<select name="TableAction" id="TableAction" onchange="CalendarCheck();">
			<option value="#" selected="selected">Select Action</option>	
			<option value="H">Change Class of Lead(s) to Hot</option>
			<option value="W">Change Class of Lead(s) to Warm</option>
			<option value="C">Change Class of Lead(s) to Cool</option>
			<option value="Cd">Change Class of Lead(s) to Cold</option>
			<option value="#">        ..          </option>	
			<option value="1">Add Follow Up for Lead(s) in 1 Day</option>
			<option value="2">Add Follow Up for Lead(s) in 2 Days</option>
			<option value="7">Add Follow Up for Lead(s) in 1 Week</option>
			<option value="14">Add Follow Up for Lead(s) in 2 Weeks</option>
			<option value="30">Add Follow Up for Lead(s) in 1 Month</option>
			<option value="Calendar">Add Follow Up for Lead(s) on a Specific Date</option>	
			<option value="Del">Delete Follow Up for Lead(s)</option>
			<option value="#">        ..          </option>	
			<option value="N">Change Status of Lead(s) to New</option>		
			<option value="A">Change Status of Lead(s) to Active</option>		
			<option value="I">Change Status of Lead(s) to Inactive</option>	
			<option value="D">Change Status of Lead(s) to Dead</option>	
			<option value="J">Change Status of Lead(s) to Junk</option>		
			<option value="INT">Change Status of Lead(s) to Internal</option>		
			</select>
			<input type="hidden" name="submitted" value="TRUE" />
			<a href="#" class="btn" onclick="document.getElementById(\'editleads\').submit()"><span>Make Edit &gt;</span></a>
			<br/>
			<div id="showCalendar" style="display:none; text-align:center;">
			Enter a Date (MM/DD/YYYY):
			<input type="text" name="followupDate" id="addfollowupDate" value="                                            ' . date('m/d/Y') . '" onfocus="if(this.value==this.defaultValue)this.value=\'\';" onblur="if(this.value==\'\')this.value=this.defaultValue;" />';
?>

	<script language="JavaScript">
	new tcal ({
		'formname': 'editleadform',
		'controlname': 'addfollowupDate'
	});
	</script>

<?php	
	echo '
			</div>
		</div>
	</div>
</div>

<div class="make-edit"><p>Click on a lead name OR select checkboxes and choose an action from the dropdown box above to edit.</p></div>

<div class="main-box">
<div class="main-table make-table">
<table id="leads_sortable" class="sortable">
<thead>
<tr>
<th scope="col" class="check-row nosort"><input type="checkbox" class="check" name="checkall" onclick="checkUncheckAll(this);" /></th>
<th class="long sortcol sortfirstasc"><span>Contact</span></th>
<th class="sortcol"><span>Company</span></th>
<th class="sortcol"><span>Status</span></th>
<th class="sortcol"><span>Received</span></th>
<th class="sortcol"><span>Updated</span></th>
<th class="sortcol"><span>Follow Up</span></th>
<th class="nosort"><span>Quick Act</span></th>
</tr>
</thead>

<tbody>
';

	// Fetch records, build table:
	while ($row = mysqli_fetch_array($r,MYSQLI_ASSOC)) {
		//Hit leads_followup table to find followup due_date for this lead:
		$leadid = $row['idlead'];

		$q2 = "SELECT due_date " . 
		"FROM lead_followups " . 
		"WHERE user_id='{$_SESSION['id']}' AND lead_id='$leadid'";
			
		$r2 = mysqli_query($dbc,$q2) or trigger_error("Query: $q2\n<br />MySQL Error: " . 
		mysqli_error($dbc));

		$due_date = FALSE;
		if (mysqli_num_rows($r2) > 0) {
		    $row2 = mysqli_fetch_array($r2,MYSQLI_ASSOC);    
		    $due_date = $row2['due_date'];
		}

		if($row['contact_first']!=NULL || $row['contact_last']!=NULL) {
			$contact_name = stripslashes($row['contact_first']) . ' ' . stripslashes($row['contact_last']);
		} else {
			if($row['email']!=NULL) {
				$contact_name = $row['email'];
			} else {
				$contact_name = "Unknown Name";
			}
		}

		echo '<tr>' . 
		'<td><input type="checkbox" id="myform' . $row['idlead'] . '" name="selectlead[]" value="' . $row['idlead'] . '"></td>' . 
		'<td><a href="/leads/manage/id/?id=' . $row['idlead'] . '&filter=' . $filter . 
		'" title="Edit Lead Details">' . $contact_name . '</a></td>' . 
		'<td>' . stripslashes($row['company']) . '</td>';
		//Status column:
		echo '<td';
		if ($row['status']=='A') {
			echo ' sorttable_customkey="Active">Active'; 
		} else if ($row['status']=="I") {
			echo ' sorttable_customkey="Inactive">Inactive';
		} else if ($row['status']=="D") {
			echo ' sorttable_customkey="Dead">Dead';
		} else if ($row['status']=="J") {
			echo ' sorttable_customkey="Junk">Junk';
		} else if ($row['status']=="INT") {
			echo ' sorttable_customkey="INT">Internal';
		} else if ($row['status']=="N") {
			echo ' sorttable_customkey="N">New';
		}
		echo '</td>';
		//calculate how many days ago since lead was created, format:
		if(strtotime(date('Y-m-d'))-strtotime($row['date_created'])>86400) {
			$datediff = round((strtotime(date('Y-m-d'))-strtotime($row['date_created']))/86400);
			if($datediff<31) {
				if($datediff==1) {$datecreated = $datediff . ' Day Ago';}else{$datecreated = $datediff . ' Days Ago';}
			}else{
				if(round($datediff/30)==1) {$datecreated = round($datediff/30) . ' Month Ago';}else{$datecreated = round($datediff/30) . ' Months Ago';}
			}
		} else {
			if(date("m/d/y",strtotime($row['date_created']))==date("m/d/y")) { //created today?
				$datediff = 0;
				$datecreated = 'Today';
			}else{
				$datecreated = 'Yesterday';
			}
		}		
		echo '<td>' . $datecreated . '</td>';
		//calculate how many days ago since lead was updated, format:
		if(strtotime(date('Y-m-d'))-strtotime($row['last_updated'])>86400) {
			$datediff = round((strtotime(date('Y-m-d'))-strtotime($row['last_updated']))/86400);
			if($datediff<31) {
				if($datediff==1) {$updated = $datediff . ' Day Ago';}else{$updated = $datediff . ' Days Ago';}
			}else{
				if(round($datediff/30)==1) {$updated = round($datediff/30) . ' Month Ago';}else{$updated = round($datediff/30) . ' Months Ago';}
			}
		} else {
			if(date("m/d/y",strtotime($row['last_updated']))==date("m/d/y")) { //Updated today?
				$datediff = 0;
				$updated = 'Today';
			}else{
				$updated = 'Yesterday';
			}
		}		
		echo '<td>' . $updated . '</td>';

		//if($row['due_date']=='0000-00-00 00:00:00') { //No due date has been set
		if(!$due_date) { //No due date has been set
			$followup = 'N/A';
		}else{
			if(date("m/d/y",strtotime($due_date))==date("m/d/y")) { //Is today the due date?
				$followup = 'Today';
			}else{
				//if(date("m/d/y",strtotime($row['due_date']))>date("m/d/y")) { //due date is in the future
				if(strtotime($due_date)>strtotime(date('m/d/y'))) {
					$datediff = round((strtotime($due_date)- strtotime(date('m/d/y')))/86400);
					if($datediff<31) {
						if($datediff==1) {$followup = 'In ' . $datediff . ' Day';}else{$followup = 'In ' . $datediff . ' Days';}
					}else{
						if(round($datediff/30)==1) {$followup = 'In ' . round($datediff/30) . ' Month';}else{$followup = 'In ' . round($datediff/30) . ' Months';}
					}
				}else{ //due date is in the past:
					$datediff = round((strtotime(date('m/d/y')) - strtotime($due_date)) /86400);
					if($datediff<31) {
						if($datediff==1) {$followup = $datediff . ' Day Ago';}else{$followup = $datediff . ' Days Ago';}
					}else{
						if(round($datediff/30)==1) {$followup = round($datediff/30) . ' Month Ago';}else{$followup = round($datediff/30) . ' Months Ago';}
					}
				}
			}
		}
		echo '<td>' . $followup . '</td>';
		echo '<td>';
		//Add email quick link if email addy available for this lead:
		$sendemailtitle = 'Send Email to ' . $row['contact_first'] . ' ' . $row['contact_last'] . 
		' (' . $row['email'] . ')';
		if(strlen($row['email'])>2) echo '<a href="/modals/lead-send-email.php?email=' .$row['email'] . 
		'&idlead=' . $row['idlead'] . '" title="' . 
		$sendemailtitle . '" onclick="Modalbox.show(this.href, 
{title: this.title, width: 600, evalScript: true}); return false;">Send Email</a><br />';
		$addnotetitle = 'Add a Note for ' . $row['contact_first'] . ' ' . $row['contact_last'];
		echo '<a href="/modals/lead-add-note.php?idlead=' . $row['idlead'] . '" title="' . 
		$addnotetitle . '" onclick="Modalbox.show(this.href, 
{title: this.title, width: 600, evalScript: true}); return false;">Add Note</a></td></tr>';

	}
	echo '
</tbody>
</table>';
} else {

echo '
<div class="main-block main-block-add">

<div class="heading">
<h2>Unfortunately no ' . $label .  ' were found. Some reasons for this might be:</h2>
</div>
<ul>
<li>You don\'t have any ' . $label .  ' (you may have other types of leads - check your <a href="/leads/">leads dashboard</a> to see if you do)</li>
<li>This could be a genuine error on our part. If you think you should be seeing leads here please press the "Support" link at the bottom of the page and tell us about it</li>
</ul>
<p>If you followed a link and got this message you might double check with the person who sent you the link.</p>

</div>
';

}

?>

</div>
</div>

</fieldset>
</form>
</div>

<!-- / Main Content -->

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
