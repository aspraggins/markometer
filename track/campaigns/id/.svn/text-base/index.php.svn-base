<?php # Track Individual Campaigns
$metatitle = 'Track A Campaign';
$returnurl = '/login/?returnpage=track/campaigns/';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Main Content -->

<?php
//MYSQL Setup:
require_once(MYSQL);

//get campaignid from passed variable:
$campaignid = $_GET['id'];

//Build Page:
//Build query for active and pending campaigns:
$q = "SELECT name, type, uniques, cost, actual_cost " .   
"FROM  campaigns " . 
"WHERE account_id={$_SESSION['account_id']} AND id='$campaignid'";
	
$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

if (mysqli_num_rows($r)==1) {
	//Fetch record, build table:
	$row = mysqli_fetch_array($r,MYSQLI_ASSOC);

	//Query to find leads and lead data for this campaign:
	//$campaignid = $row['id'];
	$q2 = "SELECT COUNT(*) " . 
	"FROM leads " . 
	"WHERE campaign_id='$campaignid'";

	$r2 = mysqli_query($dbc,$q2) or trigger_error("Query: $q2\n<br />MySQL Error: " . 
	mysqli_error($dbc));
	
	$row2 = mysqli_fetch_array($r2,MYSQLI_ASSOC);
	$campaignleads = $row2['COUNT(*)'];

	//Query to find quote and sales amounts for campaign:
	$q2 = "SELECT COUNT(*), SUM(quotes.amount) AS quotesum, SUM(quotes.soldamount) AS soldsum " . 
	"FROM quotes INNER JOIN leads " . 
	"ON quotes.lead_id = leads.idlead " . 
	"WHERE leads.campaign_id='$campaignid'";

	$r2 = mysqli_query($dbc,$q2) or trigger_error("Query: $q2\n<br />MySQL Error: " . 
	mysqli_error($dbc));
	
	$row2 = mysqli_fetch_array($r2,MYSQLI_ASSOC);

	$quotesum = 0; $soldsum = 0;
	if($row2['quotesum']>0) $quotesum = $row2['quotesum'];
	if($row2['soldsum']>0) $soldsum = $row2['soldsum'];

	//session variables for cost for use in "edit costs" modal box:
	$_SESSION['budgeted_cost'] = $row['cost'];
	$_SESSION['actual_cost'] = $row['actual_cost'];
	
	//use budgeted cost for calculations if no actual cost in place:
	if($row['actual_cost']==0) {
		$calccost = $row['cost'];
	}else{
		$calccost = $row['actual_cost'];
	}

	if($soldsum>0) {
		$roi = (($soldsum-$calccost)/$calccost)*100;
		$roiformatted = number_format($roi,1) . '%';
	}else{
		$roiformatted = 'N/A';
	}

// Begin Top Table:
echo '
<!-- Heading and Help Button -->

<div class="heading">
<h1>Campaign Results: ' . $row['name'] . ' ';

include ($_SERVER['DOCUMENT_ROOT'] . '/inc/current-account.php');

echo '
</h1>
';
# Help Button
$helphref = 'track-individual-campaign.html';
$helptitle = 'Help with Tracking an Individual Campaign';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
echo '
</div>


<!--  Campaign ROI & Revenue Box -->
<div class="main-block">
<div class="main-item-box">
	<div class="holder">
		<div class="frame">
			<div class="col-1">
				<strong>' . $roiformatted . '</strong>
				<span>Campaign Return on Investment (ROI)</span>
			</div>
			<div class="col-2">
				<strong>$' . number_format(round($soldsum)) . '</strong>
				<span>Total Sales Revenue Generated</span>
			</div>
		</div>
	</div>
</div>
</div>
<!--  / Campaign ROI & Revenue Box -->
<br/>
';

// Begin Deep Analysis Content Boxes:
echo '
<!-- Campaign Deep Analysis Content Boxes -->
<div class="main-item-holder main-table-row">
<div class="main-item-frame">
';

// Begin Left Column:
echo '
<!-- Left Column -->
<div class="collumn">
';
	
// Begin Campaign Cost Analysis Table:
$variance = (($row['cost'] - $row['actual_cost'])/$row['cost']) * 100;
$variance = number_format($variance,0) . '%';
echo '
<!-- Campaign Cost Analysis Box -->
<div class="box">
	<div class="head">
		<h3>Campaign Cost Analysis</h3>
		<a href="/modals/track-individual-campaign-edit-cost.php?id=' . $_REQUEST['id'] . '" title="Update Your Campaign Costs" onclick="Modalbox.show(this.href, 
	{title: this.title, width: 600, evalScript: true}); return false;">(Edit Costs)</a>
	</div>
	<div class="main-table">
	<table>
		<tr>
			<td class="left">Actual Cost</td>
			<td>$' . number_format(round($row['actual_cost'])) . '</td>
		</tr>
		<tr class="add">
			<td class="left">Budgeted Cost</td>
			<td>$' . number_format(round($row['cost'])) . '</td>
		</tr>
		<tr>
			<td class="left">Difference</td>
			<td>$' . number_format(round($row['actual_cost'] - $row['cost'])) . '</td>
		</tr>
		<tr class="add">
			<td class="left">Variance</td>
			<td>' . $variance . '</td>
		</tr>
	</table>
	</div>
</div>
';

// Begin Quote versus Close Analysis table:
//query to get number of quotes within these leads:
/*$q3 = "SELECT COUNT(*) " . 
"FROM leads " . 
"WHERE campaign_id='$campaignid' " . 
"AND quoted_value>0";*/

	//Pre-emptively get $numsales variable for use in calculations:
	$q3 = "SELECT COUNT(*) " . 
	"FROM quotes INNER JOIN leads " . 
	"ON quotes.lead_id = leads.idlead " . 
	"WHERE leads.campaign_id='$campaignid' AND quotes.soldamount>0";


	$r3 = mysqli_query($dbc,$q3) or trigger_error("Query: $q3\n<br />MySQL Error: " . 
	mysqli_error($dbc));

	$row3 = mysqli_fetch_array($r3,MYSQLI_ASSOC);
	$numsales = $row3['COUNT(*)'];
		
	//query for quotes:	
	$q3 = "SELECT COUNT(*) " . 
	"FROM quotes INNER JOIN leads " . 
	"ON quotes.lead_id = leads.idlead " . 
	"WHERE leads.campaign_id='$campaignid' AND quotes.amount>0";

	$r3 = mysqli_query($dbc,$q3) or trigger_error("Query: $q3\n<br />MySQL Error: " . 
	mysqli_error($dbc));
	
	$row3 = mysqli_fetch_array($r3,MYSQLI_ASSOC);
	$numquotes = $row3['COUNT(*)'];
	
	$quotesuma = 'N/A'; $soldsuma = 'N/A';
	if($quotesum>0) $quotesuma = '$' . number_format(round($quotesum));
	if($soldsum>0) $soldsuma = '$' . number_format(round($soldsum));
	//figure out quote and close rates:
	$quoteratea = 'N/A'; $closeratea = 'N/A';
	if($campaignleads>0) {
		$quoterate = ($numquotes/$campaignleads)*100;
		$quoteratea = number_format($quoterate,0) . '%';
		$closerate = ($numsales/$campaignleads)*100;
		$closeratea = number_format($closerate,0) . '%';		
	}	
echo '
<!-- Quote vs. Close Analysis Box -->
<div class="box">
	<div class="head">
	<h3>Quote vs. Close Analysis</h3>
	</div>
	<div class="main-table">
	<table>
		<tr>
			<td class="left">Dollars Quoted</td>
			<td>' . $quotesuma . '</td>
		</tr>
		<tr class="add">
			<td class="left">Dollars Booked</td>
			<td>' . $soldsuma . '</td>
		</tr>
		<tr>
			<td class="left">Quote Rate</td>
			<td>' . $quoteratea . '</td>
		</tr>
		<tr class="add">
			<td class="left">Close Rate</td>
			<td>' . $closeratea . '</td>
		</tr>
	</table>
	</div>
</div>
';

// Begin Lead Category Analysis:
//clumsy, but don't know a better way at the moment, 
//separate queries for finding different sorts of lead classes:
	$q3 = "SELECT COUNT(*) " . 
	"FROM leads " . 
	"WHERE campaign_id='$campaignid' " . 
	"AND lead_class='H'";
		
	$r3 = mysqli_query($dbc,$q3) or trigger_error("Query: $q3\n<br />MySQL Error: " . 
	mysqli_error($dbc));
	
	$row3 = mysqli_fetch_array($r3,MYSQLI_ASSOC);
	$numHot = $row3['COUNT(*)'];
	
	$q3 = "SELECT COUNT(*) " . 
	"FROM leads " . 
	"WHERE campaign_id='$campaignid' " . 
	"AND lead_class='W'";
		
	$r3 = mysqli_query($dbc,$q3) or trigger_error("Query: $q3\n<br />MySQL Error: " . 
	mysqli_error($dbc));
	
	$row3 = mysqli_fetch_array($r3,MYSQLI_ASSOC);
	$numWarm = $row3['COUNT(*)'];
	
	$q3 = "SELECT COUNT(*) " . 
	"FROM leads " . 
	"WHERE campaign_id='$campaignid' " . 
	"AND lead_class='C'";
		
	$r3 = mysqli_query($dbc,$q3) or trigger_error("Query: $q3\n<br />MySQL Error: " . 
	mysqli_error($dbc));
	
	$row3 = mysqli_fetch_array($r3,MYSQLI_ASSOC);
	$numCool = $row3['COUNT(*)'];

	$q3 = "SELECT COUNT(*) " . 
	"FROM leads " . 
	"WHERE campaign_id='$campaignid' " . 
	"AND lead_class='Cd'";
		
	$r3 = mysqli_query($dbc,$q3) or trigger_error("Query: $q3\n<br />MySQL Error: " . 
	mysqli_error($dbc));
	
	$row3 = mysqli_fetch_array($r3,MYSQLI_ASSOC);
	$numCold = $row3['COUNT(*)'];
	
	echo '
<!-- Lead Category Analysis Box -->
<div class="box">
	<div class="head">
	<h3>Lead Category Analysis</h3>
	<a href="/modals/help/track-individual-campaign-lead-category.html" title="What Are Lead Categories?" onclick="Modalbox.show(this.href, 
	{title: this.title, width: 600, evalScript: true}); return false;">(Say What?)</a>
	</div>
	<div class="main-table">
	<table>
		<tr>
			<td class="left">Hot Leads</td>
			<td>' . $numHot . '</td>
		</tr>
		<tr class="add">
			<td class="left">Warm Leads</td>
			<td>' . $numWarm . '</td>
		</tr>
		<tr>
			<td class="left">Cool Leads</td>
			<td>' . $numCool . '</td>
		</tr>
		<tr class="add">
			<td class="left">Cold Leads</td>
			<td>' . $numCold . '</td>
		</tr>
	</table>
	</div>
</div>
';

// End Left Column:
echo '
</div>
<!-- / Left Column -->
';

// Start Right Column:
echo '
<!-- Right Column -->
<div class="collumn">
';

// Begin Customer Cost Analysis table:
//query to get number of sales within these leads:
/*$q3 = "SELECT COUNT(*) " . 
"FROM leads " . 
"WHERE campaign_id='$campaignid' " . 
"AND sold_value>0";*/
		
	/*$q3 = "SELECT COUNT(*) " . 
	"FROM quotes INNER JOIN leads " . 
	"ON quotes.lead_id = leads.idlead " . 
	"WHERE leads.campaign_id='$campaignid' AND quotes.soldamount>0";


	$r3 = mysqli_query($dbc,$q3) or trigger_error("Query: $q3\n<br />MySQL Error: " . 
	mysqli_error($dbc));
	
	$row3 = mysqli_fetch_array($r3,MYSQLI_ASSOC);
	$numsales = $row3['COUNT(*)'];*/
	//Calculations for Customer Cost Analysis:
	$costperimpression = 0; $costperlead = 0; $costpersale = 0; $avgsale = 0;
	if($row['uniques']>0) $costperimpression = round($calccost/$row['uniques']);
	if($costperimpression<1) $costperimpression = 'N/A';
	if($costperimpression!='N/A') $costperimpression = '$' . number_format(round($costperimpression));
	
	if($campaignleads>0) $costperlead = number_format(round($calccost/$campaignleads));
	if($costperlead<1) $costperlead = 'N/A';
	if($costperlead!='N/A') $costperlead = '$' . $costperlead;	
	
	if($numsales>0) $costpersale = number_format(round($calccost/$numsales));
	if($costpersale<1) $costpersale = 'N/A';
	if($costpersale!='N/A') $costpersale = '$' . $costpersale;
	
	if($numsales>0) $avgsale = number_format(round($soldsum/$numsales));
	if($avgsale<1) $avgsale = 'N/A';
	if($avgsale!='N/A') $avgsale = '$' . $avgsale;
	
echo '
<!-- Customer Cost Analysis Box -->
<div class="box">
	<div class="head">
	<h3>Customer Cost Analysis</h3>
	</div>
	<div class="main-table">
	<table>
		<tr>
			<td class="left">Cost Per Visitor</td>
			<td>' . $costperimpression . '</td>
		</tr>
		<tr class="add">
			<td class="left">Cost Per Lead</td>
			<td>' . $costperlead . '</td>
		</tr>
		<tr>
			<td class="left">Cost Per Sale</td>
			<td>' . $costpersale . '</td>
		</tr>
		<tr class="add">
			<td class="left">Average Sale Per Customer</td>
			<td>' . $avgsale . '</td>
		</tr>
	</table>
	</div>
</div>
';
	
// Begin Sales Funnel Analysis table:
echo '
<!-- Sales Funnel Analysis Box -->
<div class="box">
	<div class="head">
	<h3>Sales Funnel Analysis</h3>
	</div>
	<div class="main-table">
	<table>
		<tr>
			<td class="left">Total Visitors</td>
			<td>' . $row['uniques'] . '</td>
		</tr>
		<tr class="add">
			<td class="left">Total Leads</td>
			<td>' . $campaignleads . '</td>
		</tr>
		<tr>
			<td class="left">Total Quotes</td>
			<td>' . $numquotes . '</td>
		</tr>
		<tr class="add">
			<td class="left">Total Sales</td>
			<td>' . $numsales . '</td>
		</tr>
	</table>
	</div>
</div>
';

// Begin Lead Status Analysis table:
	$q3 = "SELECT COUNT(*) " . 
	"FROM leads " . 
	"WHERE campaign_id='$campaignid' " . 
	"AND new='Y'";
		
	$r3 = mysqli_query($dbc,$q3) or trigger_error("Query: $q3\n<br />MySQL Error: " . 
	mysqli_error($dbc));
	
	$row3 = mysqli_fetch_array($r3,MYSQLI_ASSOC);
	$numNew = $row3['COUNT(*)'];
	
	$q3 = "SELECT COUNT(*) " . 
	"FROM leads " . 
	"WHERE campaign_id='$campaignid' " . 
	"AND status='A'";
		
	$r3 = mysqli_query($dbc,$q3) or trigger_error("Query: $q3\n<br />MySQL Error: " . 
	mysqli_error($dbc));
	
	$row3 = mysqli_fetch_array($r3,MYSQLI_ASSOC);
	$numActive = $row3['COUNT(*)'];
	
	$q3 = "SELECT COUNT(*) " . 
	"FROM leads " . 
	"WHERE campaign_id='$campaignid' " . 
	"AND status='D'";
		
	$r3 = mysqli_query($dbc,$q3) or trigger_error("Query: $q3\n<br />MySQL Error: " . 
	mysqli_error($dbc));
	
	$row3 = mysqli_fetch_array($r3,MYSQLI_ASSOC);
	$numDead = $row3['COUNT(*)'];
	
echo '
<!-- Lead Status Analysis Box -->
<div class="box">
	<div class="head">
	<h3>Lead Status Analysis</h3>
	</div>
	<div class="main-table">
	<table>
		<tr>
			<td class="left">New Leads</td>
			<td>' . $numNew . '</td>
		</tr>
		<tr class="add">
			<td class="left">Active Leads</td>
			<td>' . $numActive . '</td>
		</tr>
		<tr>
			<td class="left">Sold Leads</td>
			<td>' . $numsales . '</td>
		</tr>
		<tr class="add">
			<td class="left">Dead Leads</td>
			<td>' . $numDead . '</td>
		</tr>
	</table>
	</div>
</div>
';

// End Right Column
echo '
</div>
<!-- / Right Column -->
';

// End Deep Analysis Content Boxes:
echo '
</div>
</div>
<!-- / Campaign Deep Analysis Content Boxes -->
<br/><br/>
';

}else {
	echo '
<!-- Heading and Help Button -->

<div class="heading">
<h1>Campaign Not Found ';
if($_SESSION['multiaccounts']) {
if (isset($_SESSION['accountname'])) echo ' (' . $_SESSION['accountname'] . ')'; 
}
echo '
</h1>
';
# Help Button
$helphref = 'track-individual-campaign.html';
$helptitle = 'Help with Tracking an Individual Campaign';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
echo '
</div>

<div class="main-block main-block-add">

<div class="heading">
<h2>Unfortunately the campaign you\'re looking for can\'t be found. Some reasons for this might be:</h2>
</div>
<ul>
<li>The campaign doesn\'t exist</li>
<li>The campaign might be for another account (which means you don\'t have access to it)</li>
</ul>
<p>If you followed a link and got this message you might double check with the person who sent you the link.</p>

</div>

';
}

?>

<!-- / Main Content -->

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
