<?php # Dashboard - Main Home Page
$metatitle = 'Your Dashboard';
$returnurl = '/login/?returnpage=';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Heading and Help Button -->

<div class="heading">
<h1>Dashboard
<?php 
if($_SESSION['multiaccounts']) {
	if (isset($_SESSION['accountname'])) echo ' (' . $_SESSION['accountname'] . ')'; 
}
?></h1>
<?php # Help Button
$helphref = 'main-dashboard.html';
$helptitle = 'Help with the Main Dashboard';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
?>
</div>

<!-- Main Content -->

<?php

// This doesn't do anything now..it verifies that someone has authenticated
//through our login process, and throws them out in the "else" if not
if(isset($_SESSION['first_name'])) {
	// Determine if new or old account:
	if($_SESSION['newaccount']!='N') {
	// Code to show introductory screen if account flagged as 'Y' in their "new" field:
	echo '
	<div class="content-items">
		<h2>Here\'s a Quick 4 Step Checklist to Get You Started:</h2>
		<ul class="content-list">
			<li class="warning">
				<span class="num"><em>1</em></span>
				<strong>Watch a 5 Minute Tutorial Video</strong>
				<em>Done!</em>
			</li>
			<li class="warning">
				<span class="num"><em>2</em></span>
				<strong>Learn the Lingo</strong>
				<em>Done!</em>
			</li>
			<li>
				<a href="/settings/masteraccount/" class="num"><em>3</em></a>
				<strong><a href="/settings/masteraccount/">Double Check Your Account Settings</a></strong>
				<em>Done!</em>
			</li>
			<li>
				<a href="/create/campaign/step1/" class="num"><em>4</em></a>
				<strong><a href="/create/campaign/step1/">Create Your First Campaign</a></strong>
				<em>Done!</em>
			</li>
		</ul>
		<div class="link">
			<a href="/intro/sampledash/">See What This Screen Will Look Like When You Have Campaigns &gt;</a>
		</div>
	</div>
	';
	} else {
		echo '
		<div class="content-boxes">

		<!-- Quick Links Side Box -->

		<div class="bar">
			<h3>Quick Links</h3>
			<form name="f" id="quickfind" onsubmit="g();" action="/leads/manage/id/" method="get" class="find-form">
				<fieldset>
				<div class="find">
					<label for="q">Find a Lead:</label>
					<input type="text" class="text" name="q" id="q" autocomplete="off" />
					<input type="hidden" id="id" name="id" />
					<div class="content-link">
						<div class="link-holder">
							<div class="link-frame">
								<a href="#" class="btn" onclick="document.getElementById(\'quickfind\').submit()"><span>Find &gt;</span></a>
							</div>
						</div>
					</div>
					<div id="ac" style="display:none;border:1px solid black;background-color:white;"></div>
				</div>
			<script type="text/javascript" language="javascript" charset="utf-8">
			// <![CDATA[
			var a_c = new Ajax.Autocompleter(\'q\',\'ac\',\'/inc/autocomplete.php\', {afterUpdateElement : getSelectedId});
			// ]]>

			function getSelectedId(text, li) {
				$(\'id\').value=li.id;
			}
			</script>
				</fieldset>
			</form>
			<h4>Attention Needed:</h4>
			<div class="row">
				<strong>5</strong>
				<a href="#"><span><em>New Leads</em></span></a>
			</div>
			<div class="row">
				<strong>2</strong>
				<a href="#"><span><em>Overdue Leads</em></span></a>
			</div>
			<div class="row">
				<strong>0</strong>
				<a href="#"><span><em>Leads Need Attention Today</em></span></a>
			</div>
			<div class="col">
				<div class="link"><a href="#">Do Something Here</a></div>
				<span>Explain it here with small text.</span>
			</div>
			<div class="col">
				<img src="/images/ico-01.gif" alt="image" width="33" height="33" />
				<div class="link"><a href="#">Do Something Here</a></div>
				<span>Explain it here with small text.</span>
			</div>
		</div>
		<!-- / Quick Links Side Box -->
		';
		
		//MYSQL Setup:
		require_once(MYSQL);

		$q = "SELECT id, name, type, cost, actual_cost " .   
		"FROM  campaigns " . 
		"WHERE account_id={$_SESSION['account_id']} " .
		"AND archived='N' AND imported!='P'" . 
		"ORDER BY id";

		$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 	mysqli_error($dbc));

		if (mysqli_num_rows($r) > 0) {
			$counter = 0;

			while ($row = mysqli_fetch_array($r,MYSQLI_ASSOC)) {	
				if($counter==0) {
					echo '
					<!-- Row of Campaign Boxes -->

					<div class="boxes-panel">
					<div class="boxes-holder">
					<ul class="boxes">
					';
				}

				$campaignid = $row['id'];
				echo '<li>';

				// Query to find quote and sales amounts for campaign:
				$q2 = "SELECT SUM(quotes.soldamount) AS soldsum " . 
				"FROM quotes INNER JOIN leads " . 
				"ON quotes.lead_id = leads.idlead " . 
				"WHERE leads.campaign_id='$campaignid'";

				$r2 = mysqli_query($dbc,$q2) or trigger_error("Query: $q2\n<br />MySQL Error: " . 
				mysqli_error($dbc));
			
				$row2 = mysqli_fetch_array($r2,MYSQLI_ASSOC);

				$cost = $row['cost'];
				if($row['actual_cost']>0) $cost = $row['actual_cost'];
				$soldsum = 0;
				if($row2['soldsum']>0) $soldsum = $row2['soldsum'];
				$roi = 0;
				if($soldsum>0) {
					$roi = (($soldsum-$cost)/$cost)*100;
				}
				if($roi>0) {
					/* Scenarios:
					Positive ROI & Needs Updates - <div class="box arrow-green">
					Positive ROI & No Updates - <div class="box arrow-green no-updates">
					Negative ROI & Needs Updates - <div class="box arrow-red">
					Negative ROI & No Updates - <div class="box arrow-red no-updates">
					NO ROI & Needs Updates - <div class="box arrow-none">
					NO ROI & No Info - <div class="box arrow-none no-info">
					NO ROI & No Updates - <div class="box no-info no-updates">*/
					echo '
					<div class="box arrow-green">
						<div class="holder">
							<div class="frame">
								<h3>' . $row['name'] . '</h3>
								<strong>' . number_format($roi,0) . '% ROI</strong>
					';
				} elseif($roi<0) {
					echo '
					<div class="box arrow-red">
						<div class="holder">
							<div class="frame">
								<h3>' . $row['name'] . '</h3>
								<strong>' . number_format($roi,0) . '% ROI</strong>
					';
				} else {
					echo '
					<div class="box arrow-none no-info">
						<div class="holder">
							<div class="frame">
								<h3>' . $row['name'] . '</h3>
								<strong>No ROI Data</strong>
					';
				}

				// Query to find number of leads for a campaign:
				$q2 = "SELECT COUNT(*) AS countleads FROM leads WHERE campaign_id='$campaignid'";

				$r2 = mysqli_query($dbc,$q2) or trigger_error("Query: $q2\n<br />MySQL Error: " . 
				mysqli_error($dbc));
			
				$row2 = mysqli_fetch_array($r2,MYSQLI_ASSOC);
				$numleads = $row2['countleads'];
				
				// Query to find number of sales for a campaign:
				$q2 = "SELECT COUNT(*) AS countsales FROM leads " .
				"WHERE campaign_id='$campaignid' AND stage='SD'";

				$r2 = mysqli_query($dbc,$q2) or trigger_error("Query: $q2\n<br />MySQL Error: " . 
				mysqli_error($dbc));
			
				$row2 = mysqli_fetch_array($r2,MYSQLI_ASSOC);
				$numsales = $row2['countsales'];
				
				// Query to find number of overdue leads (need attention) for a campaign:
				$q2 = "SELECT COUNT(*) AS countoverdueleads " . 
				"FROM leads " . 
				"INNER JOIN lead_followups " . 
				"ON leads.idlead=lead_followups.lead_id " . 
				"WHERE leads.campaign_id='$campaignid' " . 
				"AND lead_followups.user_id={$_SESSION['id']} " . 
				"AND lead_followups.due_date!='0000-00-00 00:00:00' " . 
				"AND DATE(lead_followups.due_date)<CURDATE()";
				
				$r2 = mysqli_query($dbc,$q2) or trigger_error("Query: $q2\n<br />MySQL Error: " . 
				mysqli_error($dbc));
			
				$row2 = mysqli_fetch_array($r2,MYSQLI_ASSOC);
				$numoverdueleads = $row2['countoverdueleads'];
				
				//display number of leads and sales in block:
				echo'<em>(' . $numleads . ' Leads | ' . $numsales . ' Sales)</em>
							<div class="btn">
								';
								
				//display "leads need updated" based on how many pop up:				
				if($numoverdueleads==0) {
					echo '<a href="#"><em><span>No Information Received';
				} elseif($numoverdueleads==1) {
					echo '<a href="/leads/manage/?filter=o&camp=' . $campaignid . '"><em><span>1 Lead Needs Updated';
				} else {
					echo '<a href="/leads/manage/?filter=o&camp=' . $campaignid . '"><em><span>' . $numoverdueleads . ' Need Updated';
				}				
								
				//rest of formatting:
				echo '</span></em></a>
							</div>
						</div>
					</div>
				</div>';

				//terminate block, increment, move on to next one:
				echo '</li>';
				$counter++;
				if($counter==2) {
					$counter = 0;
					echo '
					</ul>
					</div>
					</div>

					<!-- / Row of Campaign Boxes -->

					';
				}    
			}
			if($counter==1)
					echo '
					</ul>
					</div>
					</div>

					<!-- / Row of Campaign Boxes -->

					';
	 	}
	}

	echo
	'
	<div class="content-link link-add">
		<div class="link-holder">
			<div class="link-frame">
				<a class="btn" href="/track/campaigns/"><span>More Campaigns &gt;</span></a>
			</div>
		</div>
	</div>

	</div>
	';

	//}
} else {
	if ($_SESSION['url_subdomain']=='signup') {
		$url = "http://signup.mp41.com/register";
	} else {
		$url = LOGIN;
	}
	header("Location: $url");
}
?>

<!-- / Main Content -->

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); 

/*			<em>(15 Leads | 3 Sales)</em>
			<div class="btn">
				<a href="#"><em><span>8 Leads Need Updated &gt;</span></em></a>
			</div>
		</div>
	</div>
</div> 

			<em>(5 Leads | 1 Sale)</em>
			<div class="btn">
				<a href="#"><em><span>1 Lead Needs Updated &gt;</span></em></a>
			</div>
		</div>
	</div>
</div>

			<em>(0 Leads | 0 Sales)</em>
			<div class="btn">
				<a href="#"><em><span>No Information Received</span></em></a>
			</div>
		</div>
	</div>
</div>
*/


?>
