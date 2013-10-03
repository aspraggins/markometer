<?php # Track Campaigns
$metatitle = 'Track Your Campaigns';
$returnurl = '/login/?returnpage=track/campaigns/';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Heading and Help Button -->

<div class="heading">
<h1>Track Your Marketing Campaigns
<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/current-account.php'); ?>
</h1>
<?php # Help Button
$helphref = 'track-campaigns.html';
$helptitle = 'Help with Tracking Campaigns';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
?>
</div>

<!-- Main Content -->

<div class="main-block main-block-add">

<?php
//MYSQL Setup:
require_once(MYSQL);

// Build Page:
// Build query for active and pending campaigns:
$q = "SELECT id, name, type, uniques, cost, actual_cost " .   
"FROM campaigns " . 
"WHERE account_id={$_SESSION['account_id']} " .
"AND archived='N' AND imported!='P'" . 
"ORDER BY name";
	
$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

if (mysqli_num_rows($r) > 0) {
	// Note above table:
echo '
<!-- Campaigns Table -->
	<div class="heading">
	<h2>Your Campaigns</h2>
	</div>
';

	// Table header:
echo '
<div class="main-box">
<div class="main-table">
<table id="leads_sortable" class="sortable">
<thead>
<tr>
<th class="long sortcol sortfirstasc"><span>Campaign Name</span></th>
<th class="sortcol"><span>Type</span></th>
<th class="sortcol"><span>Visitors</span></th>
<th class="sortcol"><span>Leads</span></th>
<th class="sortcol"><span>Quoted</span></th>
<th class="sortcol"><span>Sold</span></th>
<th class="sortcol"><span>ROI</span></th>
</tr>
</thead>
<tbody>
';
		
	//Fetch records, build table:
	while ($row = mysqli_fetch_array($r,MYSQLI_ASSOC)) {
		//Query to find leads and lead data for this campaign:
		$campaignid = $row['id'];
		$q2 = "SELECT COUNT(*) " . 
		"FROM leads " . 
		"WHERE campaign_id='$campaignid' AND status!='P'";

		$r2 = mysqli_query($dbc,$q2) or trigger_error("Query: $q2\n<br />MySQL Error: " . 
		mysqli_error($dbc));
		
		$row2 = mysqli_fetch_array($r2,MYSQLI_ASSOC);
		$campaignleads = $row2['COUNT(*)'];

		//Query to find quote and sales amounts for campaign:
		$q2 = "SELECT COUNT(*), SUM(quotes.amount) AS quotesum, SUM(quotes.soldamount) AS soldsum " . 
		"FROM quotes INNER JOIN leads " . 
		"ON quotes.lead_id = leads.idlead " . 
		"WHERE leads.campaign_id='$campaignid' AND leads.status!='P'";

		$r2 = mysqli_query($dbc,$q2) or trigger_error("Query: $q2\n<br />MySQL Error: " . 
		mysqli_error($dbc));
		
		$row2 = mysqli_fetch_array($r2,MYSQLI_ASSOC);
		
		//Determine text for the type of campaign in this record:
		switch($row['type']) {
			case 1:
				$campaigntype = 'Print';
				break;
			case 2:
				$campaigntype = 'Radio';
				break;
			case 3:
				$campaigntype = 'TV';
				break;
			case 4:
				$campaigntype = 'Banner Ad';
				break;
			case 5:
				$campaigntype = 'Email';
				break;
			case 6:
				$campaigntype = 'PPC';
				break;
			default:
				$campaigntype = 'Unknown';
		}
		$quotesum = 0; $soldsum = 0;
		if($row2['quotesum']>0) $quotesum = $row2['quotesum'];
		if($row2['soldsum']>0) $soldsum = $row2['soldsum'];

		//Adding in queries to get number of quotes and sales for this lead (as opposed to dollar amounts found above):
		$q3 = "SELECT COUNT(*) " . 
		"FROM quotes INNER JOIN leads " . 
		"ON quotes.lead_id = leads.idlead " . 
		"WHERE leads.campaign_id='$campaignid' AND quotes.amount>0 AND leads.status!='P'";
	
		$r3 = mysqli_query($dbc,$q3) or trigger_error("Query: $q3\n<br />MySQL Error: " . 
		mysqli_error($dbc));
		
		$row3 = mysqli_fetch_array($r3,MYSQLI_ASSOC);
		$numquotes = $row3['COUNT(*)'];

		$q3 = "SELECT COUNT(*) " . 
		"FROM quotes INNER JOIN leads " . 
		"ON quotes.lead_id = leads.idlead " . 
		"WHERE leads.campaign_id='$campaignid' AND quotes.soldamount>0 AND leads.status!='P'";

		$r3 = mysqli_query($dbc,$q3) or trigger_error("Query: $q3\n<br />MySQL Error: " . 
		mysqli_error($dbc));
		
		$row3 = mysqli_fetch_array($r3,MYSQLI_ASSOC);
		$numsales = $row3['COUNT(*)'];
		
		//Generate table:
echo '
<tr>
<td class="long"><a href="/track/campaigns/id/?id=' . $row['id'] . '">' . $row['name'] . '</a></td>
<td>' . $campaigntype . '</td>
<td>' . $row['uniques'] . '</td>
<td>' . $campaignleads . '</td>
<td>' . $numquotes . '</td>
<td>' . $numsales . '</td>
<td>
';

$cost = $row['cost'];
if($row['actual_cost']>0) $cost = $row['actual_cost'];
if($soldsum>0) {
	$roi = (($soldsum-$cost)/$cost)*100;
	echo number_format($roi,1) . '%';
}else{
	echo 'N/A';
}

echo '
</td></tr>
';
	}

echo '
</tbody>
</table>
</div>
</div>
';
} else {
echo '
<!-- Campaigns Table -->
	<div class="heading">
	<h2>You don\'t currently have any campaigns in the system.</h2>
	</div>
	<p>The easiest way to fix that is to <a href="/create/">Create</a> some!</p>
';
}

?>

</div>

<!-- / Main Content -->

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
