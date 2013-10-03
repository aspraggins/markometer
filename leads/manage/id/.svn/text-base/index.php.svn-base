<?php # Individual Lead Screen
$metatitle = 'Manage a Lead';
$returnurl = '/login/?returnpage=leads/';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Show/Hide Div Script -->
<script language="javascript"> 
var state = 'none';

function showhide(layer_ref) {
	if (state == 'block') { 
		state = 'none'; 
	} 
	else { 
		state = 'block'; 
	} 
	if (document.all) { //IS IE 4 or 5 (or 6 beta) 
		eval( "document.all." + layer_ref + ".style.display = state"); 
	} 
	if (document.layers) { //IS NETSCAPE 4 or below 
		document.layers[layer_ref].display = state; 
	} 
	if (document.getElementById &&!document.all) { 
		hza = document.getElementById(layer_ref); 
		hza.style.display = state; 
	} 
}
</script>
<!-- Heading and Help Button -->

<div class="heading">
<h1>Manage/Edit a Lead
<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/current-account.php'); ?>
</h1>
<?php # Help Button
$helphref = 'leads-manage-individual.html';
$helptitle = 'Help with Managing an Individual Lead';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
?>
</div>

<!-- Main Content -->

<?php

// Check incoming passed id variable:
// Check for valid ID being passed:
$idlead = FALSE;
if ( (isset($_REQUEST['id'])) && (is_numeric($_REQUEST['id'])) ) {
	//MySQL setup:
	require_once(MYSQL);
	//query to see if this lead is available to this account:
	$q = "SELECT * FROM leads WHERE account_id='{$_SESSION['account_id']}' " . 
	"AND idlead='{$_REQUEST['id']}'";

	$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
	. mysqli_error($dbc));

	if (mysqli_num_rows($r) == 1) $idlead = $_REQUEST['id'];
} else {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>This page has been reached in error - you need a valid lead ID first. Choose one from the <a href="/leads/">Leads Dashboard</a></p>
			</div>
		</div>
	</div>
</div>';
	exit();
}

//lead is not a valid number for this account:
if(!$idlead) {
			echo '
	<div class="alert-holder">
		<div class="alert-box red">
			<div class="holder">
				<div class="frame">
					<a class="close" href="#">X</a>
					<p>This lead is not in your account. Choose one from the <a href="/leads/">Leads Dashboard</a></p>
				</div>
			</div>
		</div>
	</div>';
		exit();	
}

// More Info Form Submission
if (isset($_POST['moreinfosubmitted'])) {
/*	//MySQL setup:
	require_once(MYSQL); */

	//test to see if user is deleting lead:
	if(isset($_POST['deletelead'])) {
		//SQL string to delete this lead:
		$q = "DELETE FROM leads " . 
		"WHERE idlead='$idlead' LIMIT 1";
	
		$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
		. mysqli_error($dbc));
		
		if (mysqli_affected_rows($dbc)!=1) {
			//If it did not run okay
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>The lead details could not be deleted due to a system error. We apologize for any inconvenience.</p>
			</div>
		</div>
	</div>
</div>';
			exit();
		}
		//delete lead followups that go with this lead:
		$q = "DELETE FROM lead_followups WHERE lead_id='$idlead'";
	
		$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
		. mysqli_error($dbc));
		
		//redirect user to main leads dashboard:
		$url = '/leads/?deleted=deleted';
		header("Location: $url");
		exit();
	}else{
		//Assume invalid values:
		$fn = $ln = $e = $co = $phone = $address = $city = $title = $zip = $saleschannel = $agentname = $country = $state = " ";
	  
		//Check for a first name:
		if(strlen($_POST['first_name'])>0) {
			$fn = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
		}else{
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Please enter a valid first name!</p>
			</div>
		</div>
	</div>
</div>';		

		}

		//Check for a last name:
		if(strlen($_POST['last_name'])>0) {
			$ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
		}else{
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Please enter a valid last name!</p>
			</div>
		</div>
	</div>
</div>';		

		}
		
		//Check for an email address:
		if(preg_match('/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/', trim($_POST['email']))) {
			$e = strtolower(mysqli_real_escape_string($dbc, trim($_POST['email'])));
		} else {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Please enter a valid email address!</p>
			</div>
		</div>
	</div>
</div>';	

		}

		//Check for a company:
		if(strlen($_POST['company'])>0) {
			$co = mysqli_real_escape_string($dbc, trim($_POST['company']));
		} 

		if (strlen($_POST['title'])>0) {
			$title = mysqli_real_escape_string($dbc, trim($_POST['title']));
		}

		if (strlen(trim($_POST['phone']))>0) {
			$phone = mysqli_real_escape_string($dbc, trim($_POST['phone']));
		}

		if (strlen(trim($_POST['address']))>0) {
			$address = mysqli_real_escape_string($dbc, trim($_POST['address']));
		}

		if (strlen(trim($_POST['city']))>0) {
			$city = mysqli_real_escape_string($dbc, trim($_POST['city']));
		}

		if (strlen(trim($_POST['state']))>0) {
			$state = $_POST['state'];
		}
		
		if (strlen(trim($_POST['zip']))>0) {
			$zip = mysqli_real_escape_string($dbc, trim($_POST['zip']));
		}

		if (strlen(trim($_POST['country']))>0) {
			$country = $_POST['country'];
		}

		if (strlen(trim($_POST['channel']))>0) {
			$saleschannel = mysqli_real_escape_string($dbc, trim($_POST['channel']));
		}

		if(isset($_POST['agentname'])) {
			if(strlen(trim($_POST['agentname']))>0) {
				$agentname = mysqli_real_escape_string($dbc, trim($_POST['agentname']));
			}
		}
		
		if ($fn && $ln) 
		{
			//if required fields validate
			//Update all lead info: 
			$q = "UPDATE leads " .
			"SET contact_first='$fn', contact_last='$ln', email='$e', company='$co', " . 
			"telephone='$phone', address='$address', city='$city', state='$state', " . 
			"country='$country', title='$title', zip='$zip', saleschannel='$saleschannel', " . 
			"agentname='$agentname', updateflag=1-updateflag " .  
			"WHERE idlead=$idlead LIMIT 1";
			
			$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
			. mysqli_error($dbc));
			
			//edit 11/20/10:
			//check for proper insertion of data:
			if (mysqli_affected_rows($dbc) == 1) $moreleadinsert = TRUE;
			
			//edit by RMN 11/20/10 trying to sort out lead status and classification 
			//stuff inside of web markup style stuff:
			//Assume invalid values:
			$stat = $clas = $stag = " ";

			if (strlen(trim($_POST['status']))>0) {
				$stat = $_POST['status'];
			}

			if (strlen(trim($_POST['leadclass']))>0) {
				$clas = $_POST['leadclass'];
			}

			//Update lead info: 
			$q = "UPDATE leads " .
			"SET status='$stat',lead_class='$clas', updateflag=1-updateflag, new='N' " .  
			"WHERE idlead=$idlead LIMIT 1";

			$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
			. mysqli_error($dbc));

			if (mysqli_affected_rows($dbc) == 1) $classstatusinsert = TRUE;

			if ($moreleadinsert && $classstatusinsert) {
				echo '
		<div class="alert-holder">
			<div class="alert-box green">
				<div class="holder">
					<div class="frame">
					<a class="close" href="#">X</a>
					<p>Lead information has been saved successfully.</p>
					</div>
				</div>
			</div>
		</div>';
			} else {
				//If it did not run okay
				echo '
		<div class="alert-holder">
			<div class="alert-box red">
				<div class="holder">
					<div class="frame">
						<a class="close" href="#">X</a>
						<p>The lead information could not be updated due to a system error. We apologize for any inconvenience.</p>
					</div>
				</div>
			</div>
		</div>';
				exit();
			}	
			
			if (mysqli_affected_rows($dbc) == 1) {
				//update emailSend session variable in case he decides to send mail to this person right now:
				$_SESSION['emailSend'] = $e;
		/*echo '
<div class="alert-holder">
	<div class="alert-box green">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>Lead details have been saved successfully.</p>
			</div>
		</div>
	</div>
</div>';*/			
			} else {
				//If it did not run okay
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>The lead details could not be updated due to a system error. We apologize for any inconvenience.</p>
			</div>
		</div>
	</div>
</div>';
				exit();
			}
		}
	} //End of main Submit conditional
}

//Follow Up Form Submission
if (isset($_POST['followupsubmit'])) {
    //MySQL setup:
    require_once(MYSQL);

	//See if a date was selected by calendar widget:
	if(strlen($_POST['followupDate']>1)) { //Calendar Date Chosen
		//User has entered a specific date, format:
		$formatteddate = date("Y-m-d", strtotime($_POST['followupDate']));
		$formattedtime = date("H:i:s");
		$postpone = $formatteddate . ' ' . $formattedtime;	

		//change 03/18/10 to use new lead_followups table:
		//With new table, have to determine if a followup exists for this lead/user:
		$q = "SELECT * " . 
		"FROM lead_followups " . 
		"WHERE lead_id='$idlead' AND user_id='{$_SESSION['id']}'";			

		$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
		mysqli_error($dbc));

		if (mysqli_num_rows($r) > 0) {
		    //If followup already exists:
		    $q = "UPDATE lead_followups " . 
		    "SET updateflag=1-updateflag, due_date='$postpone' " . 
		    "WHERE lead_id='$idlead' AND user_id='{$_SESSION['id']}' LIMIT 1";

		    $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
		    Error: " . mysqli_error ($dbc));

		    if (mysqli_affected_rows ($dbc) != 1) {			
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>An error has occurred. Please try your request again. We apologize for the inconvenience.</p>
			</div>
		</div>
	</div>
</div>';
			    exit();
		    }
		} else {
		    //No followup set for this lead/user yet, so add one:
		    $q = "INSERT INTO lead_followups (lead_id, user_id, account_id, due_date) " . 
		    "VALUES('$idlead', '{$_SESSION['id']}', '{$_SESSION['account_id']}', '$postpone')";

		    $r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
		    . mysqli_error($dbc));
		  
		    if (mysqli_affected_rows ($dbc) != 1) {			
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>An error has occurred. Please try your request again. We apologize for the inconvenience.</p>
			</div>
		</div>
	</div>
</div>';
			    exit();
		    }
		}
	}else{
		//Look for actions that are reminders (numeric values):
		if (is_numeric($_POST['followup'])) {
			//Number of days to postpone due_date in variable:
			$postpone = $_POST['followup'];

			//With new table, have to determine if a followup exists for this lead/user:
			$q = "SELECT * " . 
			"FROM lead_followups " . 
			"WHERE lead_id='$idlead' AND user_id='{$_SESSION['id']}'";			

			$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
			mysqli_error($dbc));

			if (mysqli_num_rows($r) > 0) {
			    //If followup already exists:
			    $q = "UPDATE lead_followups " . 
			    "SET updateflag=1-updateflag, due_date=DATE_ADD(CURDATE(), INTERVAL $postpone DAY) " . 
			    "WHERE lead_id='$idlead' AND user_id='{$_SESSION['id']}' LIMIT 1";

			    $r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
			    Error: " . mysqli_error ($dbc));

			    if (mysqli_affected_rows ($dbc) != 1) {			
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>An error has occurred. Please try your request again. We apologize for the inconvenience.</p>
			</div>
		</div>
	</div>
</div>';
				    exit();
			    }
			} else {
			    //No followup set for this lead/user yet, so add one:
			    $q = "INSERT INTO lead_followups (lead_id, user_id, account_id, due_date) " . 
			    "VALUES('$idlead', '{$_SESSION['id']}', '{$_SESSION['account_id']}', DATE_ADD(CURDATE(), INTERVAL $postpone DAY))";

			    $r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
			    . mysqli_error($dbc));
			  
			    if (mysqli_affected_rows ($dbc) != 1) {			
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>An error has occurred. Please try your request again. We apologize for the inconvenience.</p>
			</div>
		</div>
	</div>
</div>';
				    exit();
			    }
			}



			/*$q = "UPDATE leads " . 
			"SET updateflag=1-updateflag, due_date=DATE_ADD(CURDATE(), INTERVAL $postpone DAY) " .
			"WHERE idlead='$idlead' LIMIT 1";

			$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
			Error: " . mysqli_error ($dbc));

			if (mysqli_affected_rows ($dbc) != 1) {			
				echo 'An error has occurred. Please try your request again. We apologize
				for the inconvenience.';
				exit();
			}*/
		}
	}
}

//More Info Form Submission
if (isset($_POST['updateleadsubmitted'])) {
	//MySQL setup:
	require_once(MYSQL);

	//Assume invalid values:
	$stat = $clas = $stag = " ";
	
	if (strlen(trim($_POST['status']))>0) {
		$stat = $_POST['status'];
	}

	if (strlen(trim($_POST['leadclass']))>0) {
		$clas = $_POST['leadclass'];
	}


	//Update lead info: 
	$q = "UPDATE leads " .
	"SET status='$stat',lead_class='$clas', updateflag=1-updateflag, new='N' " .  
	"WHERE idlead=$idlead LIMIT 1";

	/*$q = "UPDATE leads " .
	"SET status='$stat',lead_class='$clas',stage='$stag',updateflag=1-updateflag " .  
	"WHERE idlead=$idlead LIMIT 1";*/

	$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
	. mysqli_error($dbc));
	
	if (mysqli_affected_rows($dbc) == 1) {
		echo '
<div class="alert-holder">
	<div class="alert-box green">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>Lead information has been saved successfully.</p>
			</div>
		</div>
	</div>
</div>';
	} else {
		//If it did not run okay
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>The lead information could not be updated due to a system error. We apologize for any inconvenience.</p>
			</div>
		</div>
	</div>
</div>';
		exit();
	}
}	
	
require_once (MYSQL);

//Query for lead info to fill out form:
$q = "SELECT * " . 
"FROM leads " . 
"WHERE idlead=$idlead";

$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
. mysqli_error($dbc));

//Check success of user query:
if (@mysqli_num_rows($r) == 1) {
	//pull in values from SQL hit to user database:
	$row = mysqli_fetch_array($r,MYSQLI_ASSOC);
	$first = stripslashes($row['contact_first']);
	$last = stripslashes($row['contact_last']);
	$_SESSION['emailSend'] = $email = $row['email'];
	$title = stripslashes($row['title']);
	$company = stripslashes($row['company']);
	$phone = $row['telephone'];
	$address = stripslashes($row['address']);
	$city = $row['city'];
	$state = $row['state'];
	$zip = $row['zip'];
	$country = $row['country'];	
	//$duedate = $row['due_date'];
	$datecreated = $row['date_created'];
	$inquiry = $row['inquiry'];
	$saleschannel = $row['saleschannel'];
	$agentname = $row['agentname'];
	if ($row['lead_class']!="") {
		$leadclass = $row['lead_class'];
	} else {
		$leadclass = 'H';
	}
	$status = $row['status'];
	$quotevalue = $row['quoted_value'];
	$soldvalue = $row['sold_value'];
	$campid = $row['campaign_id'];
	$stage = $row['stage'];
}

//Update query to set this lead to "Active" if currently set to "New":
//also check to see if the user has updated the status back to "New" with the
//dropdown, and is now rebuilding the page..
if(!isset($stat)) $stat = ' ';
    
if($status=='N' && $stat<>'N') {
    $q = "UPDATE leads SET status='A', updateflag=1-updateflag WHERE idlead=$idlead LIMIT 1";

    $r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
    . mysqli_error($dbc));

    $status = 'A';
}

//Query for campaign id:
$q = "SELECT name, type " . 
"FROM campaigns " . 
"WHERE id=$campid";

$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
. mysqli_error($dbc));

//Check success of user query:
if (@mysqli_num_rows($r) == 1) {
	$row2 = mysqli_fetch_array($r,MYSQLI_ASSOC);
	$campname = $row2['name'];
	$camptypenum = $row2['type'];
}
//decipher campaign type from number stored in campaign table:
switch($camptypenum) {
	case '1':
		$camptype = "Print";
		break;
	case '2':
		$camptype = "Radio";
		break;
	case '3':
		$camptype = "Television";
		break;
	case '4':
		$camptype = "Banner";
		break;
	case '5':
		$camptype = "Email Marketing";
		break;
	case '6':
		$camptype = "Pay-Per-Click";
		break;
	default:
		$camptype = "Unknown";
		break;
}

//Hit leads_followup table to find followup due_date for this lead:
$q3 = "SELECT due_date " . 
"FROM lead_followups " . 
"WHERE user_id='{$_SESSION['id']}' AND lead_id='$idlead'";
	
$r3 = mysqli_query($dbc,$q3) or trigger_error("Query: $q3\n<br />MySQL Error: " . 
mysqli_error($dbc));

$duedate = FALSE;
if (mysqli_num_rows($r3) > 0) {
    $row3 = mysqli_fetch_array($r3,MYSQLI_ASSOC);    
    $duedate = $row3['due_date'];
}

?>

<div class="contact-container">

<!-- DIV Holder for Entire Boxed In Area -->
<div class="contact-box">
	
<!-- Update Lead Information Area (this is the first form) -->

<form action="" method="post" id="update_leadinfo" name="moreinfoform" class="contact-form">
	<fieldset>
		
<!-- Visible Area -->
<div class="head">
	<h2><?php // Name in fieldset:
	$name = $first . ' ' . $last;
	if(strlen($name)>3) {echo $name . '';}else{echo 'Unknown Name';} 
	?></h2>
	<a href="#" class="opener">(Show/Edit More Info)</a>
</div>
<div class="panel">
	<h3><?php //Title in fieldset:
	if(strlen($title)>2) echo $title . ' at ';
	if(strlen($company)>2) echo $company;
	echo '<br/>';
	if(strlen($phone)>2) echo $phone;
	echo '</h3>';
		//Add Note link:
	$addnotetitle = 'Add a Note for ' . $name;
	echo '<a href="/modals/lead-add-note.php?idlead=' . $idlead . '" title="' . $addnotetitle . '" onclick="Modalbox.show(this.href, {title: this.title, width: 600, evalScript: true}); return false;" class="btn"><span>Add Note &gt;</span></a>';
	//Send Email link:
	if(strlen($email)>3) {
	$sendemailtitle = 'Send Email to ' . $name . ' (' . $email . ')';
	echo '<a href="/modals/lead-send-email.php?email=' . $email . '&idlead=' . $idlead . '" title="' . $sendemailtitle . '" onclick="Modalbox.show(this.href, {title: this.title, width: 600, evalScript: true}); return false;" class="btn"><span>Send Email &gt;</span></a>';
	}
	?>
</div>
<div class="select-row">
	<label>Lead Status:</label>
	<select name="status" class="status">
		<?php
		// Status Pulldown:
		echo '<option value="A"';
		if($status=='A') echo ' selected="selected"';
		echo '>Active</option><option value="I"';
		if($status=='I') echo ' selected="selected"';
		echo '>Inactive</option><option value="D"';
		if($status=='D') echo ' selected="selected"';
		echo '>Dead</option><option value="J"';
		if($status=='J') echo ' selected="selected"';
		echo '>Junk</option><option value="INT"';
		if($status=='INT') echo ' selected="selected"';
		echo '>Internal</option><option value="N"';
		if($status=='N') echo ' selected="selected"';
		echo '>New</option>';
		?>
	</select>
	<label>Lead Classification:</label>
	<select name="leadclass" class="leadclass">
		<?php
		// Lead Classification Pulldown:
		echo '<option value="H"';
		if($leadclass=='H'||$leadclass=='') echo ' selected="selected"';
		echo '>Hot</option><option value="W"';
		if($leadclass=='W') echo ' selected="selected"';
		echo '>Warm</option><option value="C"';
		if($leadclass=='C') echo ' selected="selected"';
		echo '>Cool</option><option value="Cd"';
		if($leadclass=='Cd') echo ' selected="selected"';
		echo '>Cold</option>';
		?>
	</select>
</div>
<!-- / Visible Area -->

<!-- Slider Area -->
<div class="slider">
	<div>
		<ul class="contact-list exspanded-list">
			<li class="add">
				<strong>Date Received:</strong>
				<span><?php 
				// Build string for how long ago creation date occurred:
				if(date("m/d/y",strtotime($datecreated))==date("m/d/y")) { // Is today the date it was created?
//					$datereceived = 'Today';
					$datereceived =  date("F j, Y",strtotime($datecreated)) . ' (Today)';
				}else{
					$datediff = round((strtotime(date('m/d/y')) - strtotime($datecreated)) /86400);
					if($datediff<31) {
						if($datediff==1) {$datereceived = date("F j, Y",strtotime($datecreated)) . ' (' . $datediff . ' Day Ago)';}else{$datereceived = date("F j, Y",strtotime($datecreated)) . ' (' . $datediff . ' Days Ago)';}
					}else{
						if(round($datediff/30)==1) {$datereceived = date("F j,Y",strtotime($datecreated)) . ' (' . round($datediff/30) . ' Month Ago)';}else{$datereceived = date("F j, Y",strtotime($datecreated)) . ' (' . round($datediff/30) . ' Months Ago)';}
					}
				}
				// Date received:
				echo $datereceived;
				?></span>
			</li>
			<li>
				<strong>Original Inquiry:</strong>
				<span><?php
				echo stripslashes(nl2br($inquiry));
				?></span>
			</li>
			<li class="add">
				<strong>Lead Source:</strong>
				<span><?php
				echo $campname . '  (' . $camptype . ')';
				?></span>
			</li>
		</ul>
		<div class="col">
			<?php 
			//sales channel and agent/distributor name stuff:
			echo '<label>Sales Channel:</label><select name="channel" id="channel" onchange="enableagentname();"><option value="direct"';
			if($saleschannel=='direct') echo ' selected="selected"';
			echo '>Direct</option><option value="agent"';
			if($saleschannel=='agent') echo ' selected="selected"';
			echo '>Agent</option><option value="dist"';
			if($saleschannel=='dist') echo ' selected="selected"';
			echo '>Distributor</option></select>';
			echo '<label>Distributor/Agent Name:</label><input type="text" name="agentname" class="text" maxlength="80" value="';
			if(strlen($agentname)>0) echo $agentname;
			echo '" />';
			?>
		</div>
		<script language="javascript"> 
		enableagentname();
		</script>
		<div class="row">
			<label class="title">First Name:</label>
			<input type="text" class="text middle add" name="first_name" maxlength="80" value="<?php if(isset($first)) echo $first; ?>" />
			<label>Last Name:</label>
			<input type="text" class="text middle-edit" name="last_name" maxlength="80" value="<?php if(isset($last)) echo $last; ?>" />
		</div>
		<div class="row">
			<label class="title">Title:</label>
			<input type="text" class="text" name="title" maxlength="80" value="<?php if(isset($title)) echo $title; ?>" />
		</div>
		<div class="row">
			<label class="title">Company:</label>
			<input type="text" class="text" name="company" maxlength="80" value="<?php if(isset($company)) echo $company; ?>" />
		</div>
		<div class="row">
			<label class="title">Phone:</label>
			<input type="text" class="text" name="phone" maxlength="80" value="<?php if(isset($phone)) echo $phone; ?>" />
		</div>
		<div class="row">
			<label class="title">Email:</label>
			<input type="text" class="text" name="email" maxlength="80" value="<?php if(isset($email)) echo $email; ?>" />
		</div>
		<div class="row">
			<label class="title">Address:</label>
			<textarea rows="10" cols="30" id="area" name="address"><?php if(isset($address)) echo $address; ?></textarea>
		</div>
		<div class="row">
			<label class="title">City:</label>
			<input type="text" class="text edit" name="city" maxlength="80" value="<?php if(isset($city)) echo $city; ?>" />
			<label>State:</label>
			<input type="text" class="text short" name="state" maxlength="80" value="<?php if(isset($state)) echo $state; ?>" />  
			<label>Zip/Postal Code:</label>
			<input type="text" class="text small" name="zip" maxlength="80" value="<?php if(isset($zip)) echo $zip; ?>" />
		</div>
		<div class="row">
			<label class="title">Country:</label>
			<input type="text" class="text" name="country" maxlength="80" value="<?php if(isset($country)) echo $country; ?>" />
		</div>
		<div class="check-row">
			<input type="checkbox" id="check-1" class="check" name="deletelead" value="TRUE" onclick="confirmdelete();" />
			<label for="check-1">Delete This Lead (This Cannot Be Undone)</label>
		</div>
	</div>
</div>
<!-- / Slider Area -->

<!-- Update Lead Button -->
<div class="slide-content-link">
	<div class="link-holder">
		<div class="link-frame">
			<input type="hidden" name="id" value="<?php echo $idlead; ?>" />
			<input type="hidden" name="moreinfosubmitted" value="TRUE" />
			<a href="#" class="btn" onclick="document.getElementById('update_leadinfo').submit()" /><span>Update Lead Info &gt;</span></a>
			<em>or</em>
			<a href="#" class="cancel">Cancel</a>
		</div>
	</div>
</div>
<!-- / Update Lead Button -->

	</fieldset>
</form>

<!-- / Update Lead Information Area (this is the first form) -->


<!-- Follow Up Area (this is the second form) -->
<div class="bar">
	<div class="head">
<?php
// Follow Up:
if(!$duedate) { //No due date has been set
	$followup = '<h4>No Follow Up Set For ' . $name . '</h4> <ul class="links"><li><a href="#" onclick="showhide(\'showfollowup\');">Add One</a></li></ul>';
	
}else{
	if(date("m/d/y",strtotime($duedate))==date("m/d/y")) { // Is today the due date?
		$followup = '<h4>Follow Up Due Today</h4>';
	}else{
		if(strtotime($duedate)>strtotime(date('m/d/y'))) { // Due date is in the future
			$datediff = round((strtotime($duedate)- strtotime(date('m/d/y')))/86400);
			if($datediff<31) {
				if($datediff==1) {$followup = '<h4>Follow Up Set for ' . date("F j, Y",strtotime($duedate)) .  ' (In ' . $datediff . ' Day)</h4>';}else{$followup = '<h4>Follow Up Set for ' . date("F j, Y",strtotime($duedate)) . ' (In ' . $datediff . ' Days)</h4>';}
			}else{
				if(round($datediff/30)==1) {$followup = '<h4>Follow Set for ' . date("F j, Y",strtotime($duedate)) . ' (In ' . round($datediff/30) . ' Month)</h4>';}else{$followup = '<h4>Follow Up Set for ' . date("F j, Y",strtotime($duedate)) . ' (In ' . round($datediff/30) . ' Months)</h4>';}
			}
		}else{ // Due date is in the past:
			$datediff = round((strtotime(date('m/d/y')) - strtotime($duedate)) /86400);
			if($datediff<31) {
				if($datediff==1) {$followup = '<h4>Follow Up Set for ' . date("F j, Y",strtotime($duedate)) . ' (' . $datediff . ' Day Ago)</h4>';}else{$followup = '<h4>Follow Up Set for ' . date("F j, Y",strtotime($duedate)) . ' (' . $datediff . ' Days Ago)</h4>';}
			}else{
				if(round($datediff/30)==1) {$followup = '<h4>Follow Up Set for ' . date("F j, Y",strtotime($duedate)) . ' (' . round($datediff/30) . ' Month Ago)</h4>';}else{$followup = '<h4>Follow Up Set for ' . date("F j, Y",strtotime($duedate)) . ' (' . round($datediff/30) . ' Months Ago)</h4>';}
			}
		}
	}
	$followup .= ' <ul class="links"><li><a href="/inc/lead-followup.php?id=' . $idlead . '&note=yes">Complete</a></li>';
	$followup .= '<li><a href="#" onclick="showhide(\'showfollowup\');">Edit</a></li>';
	$followup .= '<li><a href="/inc/lead-followup.php?id=' . $idlead . '&note=no">Delete</a></li></ul>';
}
echo $followup;
?>
	</div>

		<form action="" method="post" name="followupform" id="followupform">
			<fieldset>
<br />
<div id="showfollowup" class="showfollowup" style="display:none;">
<br/>
Follow Up In: 
<select name="followup" class="followup">
<option value="1">1 Day</option>
<option value="2">2 Days</option>
<option value="7">1 Week</option>
<option value="14">2 Weeks</option>
<option value="30">1 Month</option>
</select>
<br/>or<br/>
Specific Day (MM/DD/YYYY):
<input type="text" name="followupDate" id="addfollowupDate"> 

<script language="JavaScript">
new tcal ({
	'formname': 'followupform',
	'controlname': 'addfollowupDate'
})
</script>
<br/><br/>

<!-- Add Follow Up Button -->
<div class="content-link">
	<div class="link-holder">
		<div class="link-frame">
			<input type="hidden" name="id" value="<?php echo $idlead; ?>" />
			<input type="hidden" name="followupsubmit" value="TRUE" />
			<a href="#" class="btn" onclick="document.getElementById('followupform').submit()" /><span>Add Follow Up &gt;</span></a>
			<em>or</em>
			<a href="#" onclick="showhide('showfollowup');" class="cancel">Cancel</a>
		</div>
	</div>
</div>
<!-- / Add Follow Up Button -->

</div>
			</fieldset>
		</form>

</div>
<!-- / Follow Up Area (this is the second form) -->

<!-- Quote Area -->

<!-- Show Quotes, Sold and Lost Sales Boxes -->
<div class="boxes">

<!-- Quote Color Code: <div class="box yellow"> Sold: <div class="box green"> Lost Sale: <div class="box red">  -->

<?php
// Query for quotes that go with this lead:
$q = "SELECT name, id, amount, number " . 
"FROM quotes " . 
"WHERE lead_id='$idlead' AND sold='N'";

$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
. mysqli_error($dbc));

// Adjust to use length of name and company to fill out leadname variable:
if(strlen(trim($name))>0) {
	$leadname = ' for ' . $name;
}else{
	$leadname = '';
}
if(strlen(trim($name))>0 && strlen(trim($company))>0) {
	$leadname = ' for ' . $name . ' of ' . $company;
}
$managequotetitle = 'Manage Quote' . $leadname;
$soldtitle = 'Record a Sale' . $leadname;
$editsoldtitle = 'Update Sale Info' . $leadname;
$lostsaletitle = 'Record a Lost Sale' . $leadname;
$editlostsaletitle = 'Update Lost Sale Info' . $leadname;

// Check success of quotes query:
if (mysqli_num_rows($r) > 0) {
	// Fetch and display records:
	while ($qrow = mysqli_fetch_array($r,MYSQLI_ASSOC)) {
		//code to see if there's a quote number, exclude "( )" if not there:
		$paren00 = ' ('; $paren01 = ')</a>';
		if(strlen($qrow['number']) == 0) {
			$paren00 = ''; $paren01 = '</a>';
		}
		echo '
<div class="box yellow">
	<div class="holder">
		<div class="frame">
			<strong>Quoted:</strong>
			<div class="link">
				<a href="/modals/lead-edit-quote.php?id=' . $qrow['id'] . '" title="' . $managequotetitle . '" onclick="Modalbox.show(this.href, {title: this.title, width: 600}); return false;">' . $qrow['name'] . $paren00 . $qrow['number'] . $paren01 . '
			</div>
			<em>$' . $qrow['amount'] . '</em>
			<div class="user-links">
				<span>Mark as:</span>
				<ul>
					<li><a href="/modals/lead-add-sale.php?id=' . $qrow['id'] . '" title="' . $soldtitle . '" onclick="Modalbox.show(this.href, {title: this.title, width: 600}); return false;">Sold</a></li>
					<li><a href="/modals/lead-add-lost-sale.php?id=' . $qrow['id'] . '" title="' . $lostsaletitle . '" onclick="Modalbox.show(this.href, {title: this.title, width: 600}); return false;">Lost</a></li>
				</ul>
			</div>
		</div>
	</div>
</div>
		';
	}
}

// Query for solds that go with this lead:
$q = "SELECT name, id, number, soldamount " . 
"FROM quotes " . 
"WHERE lead_id='$idlead' AND sold='Y'";

$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
. mysqli_error($dbc));

// Check success of sold query:
if (mysqli_num_rows($r) > 0) {
	//Fetch and display records:
	while ($qrow = mysqli_fetch_array($r,MYSQLI_ASSOC)) {
		//code to see if there's a quote number, exclude "( )" if not there:
		$paren00 = '&nbsp;('; $paren01 = ')</a>';
		if(strlen($qrow['number']) == 0) {
			$paren00 = '&nbsp;'; $paren01 = '</a>';
		}		
		echo '
<div class="box green">
	<div class="holder">
		<div class="frame">
			<strong>Sold:</strong>
			<div class="link">
				<a href="/modals/lead-edit-quote.php?id=' . $qrow['id'] . '" title="' . $managequotetitle . '" onclick="Modalbox.show(this.href, {title: this.title, width: 600}); return false;">' . $qrow['name'] . $paren00 . $qrow['number'] . $paren01 . '
			</div>
			<em>$' . $qrow['soldamount'] . '</em>
			<div class="user-links">
				<a href="/modals/lead-edit-sale.php?id=' . $qrow['id'] . '" title="' . $editsoldtitle . '" onclick="Modalbox.show(this.href, {title: this.title, width: 600}); return false;">Edit Sold Info</a>
			</div>
		</div>
	</div>
</div>
';
	}
}

// Query for lost sales that go with this lead:
$q = "SELECT name, id, number, amount " . 
"FROM quotes " . 
"WHERE lead_id='$idlead' AND sold='L'";

$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
. mysqli_error($dbc));

// Check success of sold query:
if (mysqli_num_rows($r) > 0) {
	//Fetch and display records:
	while ($qrow = mysqli_fetch_array($r,MYSQLI_ASSOC)) {
		//code to see if there's a quote number, exclude "( )" if not there:
		$paren00 = '&nbsp;('; $paren01 = ')</a>';
		if(strlen($qrow['number']) == 0) {
			$paren00 = '&nbsp;'; $paren01 = '</a>';
		}		
		echo '
<div class="box red">
	<div class="holder">
		<div class="frame">
			<strong>Lost:</strong>
			<div class="link">
				<a href="/modals/lead-edit-quote.php?id=' . $qrow['id'] . '" title="' . $managequotetitle . '" onclick="Modalbox.show(this.href, {title: this.title, width: 600}); return false;">' . $qrow['name'] . $paren00 . $qrow['number'] . $paren01 . '
			</div>
			<em>$' . $qrow['amount'] . '</em>
			<div class="user-links">
				<a href="/modals/lead-edit-lost-sale.php?id=' . $qrow['id'] . '" title="' . $editsoldtitle . '" onclick="Modalbox.show(this.href, {title: this.title, width: 600}); return false;">Edit Lost Sale Info</a>
			</div>
		</div>
	</div>
</div>
';
	}
}



?>

<!-- Lost Sale Box:
<div class="box red">
	<div class="holder">
		<div class="frame">
			<strong>Lost:</strong>
			<div class="link">
				<a href="/modals/lead-edit-quote.php?id=' . $qrow['id'] . '" title="' . $managequotetitle . '" onclick="Modalbox.show(this.href, {title: this.title, width: 600}); return false;">' . $qrow['name'] . '&nbsp;(' . $qrow['number'] . ')</a>' . '
			</div>
			<em>Stuff Stuff Stuff</em>
			<div class="user-links">
				<a href="/modals/lead-edit-lost-sale.php?id=' . $qrow['id'] . '" title="' . $editlostsaletitle . '" onclick="Modalbox.show(this.href, {title: this.title, width: 600}); return false;">Edit Sold Info</a>
			</div>
		</div>
	</div>
</div>
-->

</div>
<!-- / Show Quotes, Sold and Lost Sales Boxes -->

<!-- Add Quote Button -->
<div class="content-link">
	<div class="link-holder">
		<div class="link-frame">
			<?php
			// Add a Quote button:
			$addquotetitle = 'Add a New Quote for ' . $name;
			if(strlen($company)>1) $addquotetitle .= ' of ' . $company;
			echo '<a class="btn" href="/modals/lead-add-quote.php?idlead=' . $idlead . '" title="' . $addquotetitle . '" onclick="Modalbox.show(this.href, {title: this.title, width: 600, evalScript: true}); return false;"><span>Add a Quote &gt;</span></a>'; 
			?>
		</div>
	</div>
</div>
<!-- / Add Quote Button -->

<!-- / Quote Area -->


</div>
<!-- / DIV Holder for Entire Boxed In Area -->

<!-- Note/Note History Area -->
<div class="block">
<div class="head">
	<h2>Note History</h2>
	<?php
	echo '<a class="btn" href="/modals/lead-add-note.php?idlead=' . $idlead . '" title="' . $addnotetitle . '" onclick="Modalbox.show(this.href, {title: this.title, width: 600, evalScript: true}); return false;"><span>Add Note</span></a>'; 
	?>
</div>
<ul class="contact-list">

<!-- For notes: <li class="add"> for dark background, <li> for no background -->

<?php
// Query for Notes for this lead:
$q = "SELECT * " . 
"FROM notes " . 
"WHERE lead_id=$idlead " . 
"ORDER BY date_created DESC";

$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
. mysqli_error($dbc));

// Check success of notes query:
if (mysqli_num_rows($r) > 0)
{
	//Set up incrementer to alternate colors of notes:
	$altercounter = 0;
	// Fetch and display records:
	while ($row = mysqli_fetch_array($r,MYSQLI_ASSOC)) {
		//if odd number, make row dark:
		$altercounter++; $listart = '<li>';
		if($odd = $altercounter%2) $listart = '<li class="add">';
		echo $listart;
		if($row['type']=="E") {
			echo '<strong>' . date('F j, Y',strtotime($row['date_created'])) . '</strong><span><strong>Email Sent:</strong><br /><br />';
		} else {
			echo '<strong>' . date('F j, Y',strtotime($row['date_created'])) . '</strong><span>';
		}
		echo stripslashes(nl2br($row['note'])) . '</span>';
		if($row['type']=="N") {
			echo '<span class="caption">By: ' . $row['username'] . ' (<a href="/modals/lead-edit-note.php?id=' . $row['id'] . '" title="Edit Note" onclick="Modalbox.show(this.href, 
			{title: this.title, width: 600, evalScript: true}); return false;">Edit Note</a>)</span></li>';
		} else {
		echo '<span class="caption">By: ' . $row['username'] . '</span></li>';
		}
	}
}
?>

</ul>

<div class="link">
<?php
//Parse incoming filter to decide what to show:
$filter = '';
if(isset($_GET['filter'])) $filter = $_GET['filter'];
$label = '';
switch($filter) {
	case 'n':
		$label = 'New';
		break;
	case 'o':
		$label = 'Overdue';
		break;
	case 't':
		$label = 'Today\'s';
		break;
	case 'q':
		$label = 'Quoted';
		break;
	case 's':
		$label = 'Sold';
		break;
	case 'j':
		$label = 'Junk';
		break;
	case 'd':
		$label = 'Dead';
		break;
	case 'a':
		$label = 'Active';
		break;
	case 'i':
		$label = 'Inactive';
		break;
	case 'all':
		$label = 'All';
		break;
	case 'w':
		$label = 'This Week\'s';
		break;
	case '30':
		$label = '30 Day Old';
		break;
	case '90':
		$label = '90 Day Old';
		break;
	case '30Touch':
		$label = '';
		break;
	case '90Touch':
		$label = '';
		break;
	case 'h':
		$label = 'Hot';
		break;
	case 'wm':
		$label = 'Warm';
		break;
	case 'c':
		$label = 'Cool';
		break;
	case 'cd':
		$label = 'Cold';
		break;
	default:
		$label = 'Dashboard for';
		break;
}
if($label<>'Dashboard for') {
    echo '<a href="/leads/manage/?filter=' . $filter . '">&lt; Back to ' . $label . ' Leads</a>';
} else {
    echo '<a href="/leads/">&lt; Back to Leads Dashboard</a>';
}
?>
</div>
</div>
<!-- / Note/Note History Area -->



</div>

<!-- / Main Content -->

<?php
$_SESSION['idlead'] = $idlead;
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
