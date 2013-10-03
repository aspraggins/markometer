<?php # Settings - Edit Sub Account Page (called from pulldown on settings page)
$metatitle = 'Edit Sub Account Details';
$returnurl = '/login/?returnpage=settings/subaccount/edit';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Heading and Help Button -->

<div class="heading">
<h1>Edit Sub Account Details</h1>
<?php # Help Button
$helphref = 'settings-edit-sub-account.html';
$helptitle = 'Help with Editing Sub Account Information';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
?>
</div>

<!-- Main Content -->

<?php

//Process requested AccountID, passed from settings page:
//Check for valid ID being passed:
if ( (isset($_REQUEST['editAccountID'])) && (is_numeric($_REQUEST['editAccountID'])) ) {
	$account_id = $_REQUEST['editAccountID'];
} elseif (isset($_POST['editAccountID'])) {
	$account_id = $_POST['editAccountID'];
} else {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>This page has been reached in error. Please click the settings button and select a sub account from the main settings page.</p>
			</div>
		</div>
	</div>
</div>';
	exit();
}

if (isset($_POST['submitted']))
{
    require_once (MYSQL);

    //Assume invalid values:
    $co = $siteaddr = $annual = $avgprofit = $avgvalue = $ccnum = $ccmonth = $ccyear = $bz = $cctype = FALSE;
    
    //Check for a company:
    if(preg_match('/^[A-Z0-9 \',_.-]{2,40}$/i', trim($_POST['company']))) {
		$co = mysqli_real_escape_string($dbc, trim($_POST['company']));
    } else {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Please enter a valid company name!</p>
			</div>
		</div>
	</div>
</div>';		
    }
	
    //Check for a annual sales:
    if (strlen(trim($_POST['annualsales']))>1)
	{
	    if(preg_match('/^[0-9,\$]{2,12}$/i', trim($_POST['annualsales']))) {
			$annual = mysqli_real_escape_string($dbc, trim($_POST['annualsales']));
	    } else {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Please enter annual sales in dollars!</p>
			</div>
		</div>
	</div>
</div>';	
	    }
	}
    //Check for an average value of sales:
    if (strlen(trim($_POST['avgvalue_ofsale']))>1)
	{
		if(preg_match('/^[0-9,\$]{2,12}$/i', trim($_POST['avgvalue_ofsale']))) {
			$avgvalue = mysqli_real_escape_string($dbc, trim($_POST['avgvalue_ofsale']));
	    } else {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Please enter your average value of a sale in dollars!</p>
			</div>
		</div>
	</div>
</div>';	
	    }
	}
	
    //Check for an average profit margin:
    if (strlen(trim($_POST['avg_profit']))>1)
	{
	    if(preg_match('/^[0-9,\$]{2,12}$/i', trim($_POST['avg_profit']))) {
			$avgprofit = mysqli_real_escape_string($dbc, trim($_POST['avg_profit']));
	    } else {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Please enter your average profit margin in dollars!</p>
			</div>
		</div>
	</div>
</div>';
	    }
	}

    //Check for a valid Credit Card Number, discover type of card from number:
	//Only do if bill to radio buttons are set to create separate credit card:
	if ($_POST['billto']=='Add')	
    {
		if( preg_match('/^[0-9 ]{10,25}$/i', trim($_POST['creditcardnumber'])))
		{
			$tempstring = substr(trim($_POST['creditcardnumber']),0,1);
			switch ($tempstring)
			{
				case '3':
					$cctype = 'AMEX';
					break;
				case '4':
					$cctype = 'VISA';
					break;
				case '5':
					$cctype = 'MC';
					break;
				default:
					echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Please enter a valid credit card number. We only accept Visa, Mastercard and American Express.</p>
			</div>
		</div>
	</div>
</div>';
					break;
			}
			$ccnum = mysqli_real_escape_string($dbc, trim($_POST['creditcardnumber']));		
	    }
	    else
	    {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Please enter a valid credit card number. We only accept Visa, Mastercard and American Express.</p>
			</div>
		</div>
	</div>
</div>';
	    }

	    //Check for a billing zip code:
	    if(preg_match('/^[0-9 -]{5,10}$/i', trim($_POST['billingzipcode']))) {
			$bz = mysqli_real_escape_string($dbc, trim($_POST['billingzipcode']));
	    } else {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Please enter your billing zip code!</p>
			</div>
		</div>
	</div>
</div>';
	    }

	    $ccmonth = mysqli_real_escape_string($dbc, trim($_POST['ccmonth']));  
	    $ccyear = mysqli_real_escape_string($dbc, trim($_POST['ccyear']));  
	}
	
    if ( ($_POST['billto']=='Master' && $co) || ($_POST['billto']=='Add' && $cctype && $ccnum && $ccmonth && $ccyear && $bz && $co) )

    {   //Everything validated...
		//$siteaddravailable = FALSE;
		
		/*if ($_SESSION['tempSubdomain']!=$siteaddr)
		{
			//Confirm availability of site address:
			$q = "SELECT id FROM accounts WHERE mp_subdomain='$siteaddr'";
			
			$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
			mysqli_error($dbc));
			if (mysqli_num_rows($r) == 0) $siteaddravailable = TRUE;
		}*/
		
		//if ($siteaddravailable || $_SESSION['tempSubdomain']==$siteaddr)
		//{
		//Set variables to False for optional information:
		$q1 = $q2 = $q3 = $qindustry = $qcustomer = FALSE;
		$qcctype = $qccnum = $qccmonth = $qccyear = $qbz = FALSE;
		
		if ($annual) $q1 = ", annual_sales='$annual' ";
		if ($avgvalue) $q2 = ", avg_value_sale='$avgvalue' ";
		if ($avgprofit) $q3 = ", avg_profit_margin='$avgprofit' ";
		if (isset($_POST['industry'])) $qindustry = ", industry='{$_POST['industry']}' ";
		if (isset($_POST['customer'])) $qcustomer = ", customer='{$_POST['customer']}' ";
		
		//Build credit card info, if needed:
		if ($_POST['billto']=='Add')
		{
			$qcctype = ", cctype='$cctype' ";
			$qccnum = ", ccnumber='$ccnum' ";
			$qccmonth = ", ccexp_month='$ccmonth' ";
			$qccyear = ", ccexp_year='$ccyear' ";
			$qbz = ", billing_zipcode='$bz' ";
		}
		
		//Logic to decode "don't measure my traffic" checkbox:
		if (isset($_POST['ignoretraffic'])) {
			$ignoretraffic = $_POST['ignoretraffic'];
		} else {
			$ignoretraffic = 'N';
		}
		$qignoretraffic = ", ignore_my_traffic='$ignoretraffic' ";
		
		//Make decision about bill_agency flag in table: (billing master account, or separate credit card)
		if ($_POST['billto']=='Master') {
			$qbillagency = ", bill_agency='Y' ";
		} else {
			$qbillagency = ", bill_agency='N' ";				
		}
		
		//Set check variable to confirm that the update happens:
		$updateaccounts = $updateroles = FALSE;

		$q = "UPDATE accounts 
		SET updateflag=1-updateflag, company='$co'$q1$q2$q3$qindustry$qcustomer$qignoretraffic$qbillagency
		$qcctype$qccnum$qccmonth$qccyear$qbz 
		WHERE id='$account_id' LIMIT 1";
	
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
		Error: " . mysqli_error ($dbc));

		if (mysqli_affected_rows ($dbc) == 1) $updateaccounts = TRUE;
		
		//Re-populate Session accounts array to include new account:
		$accountsarray = array();
		$accountsarray = $_SESSION['accounts'];
		unset($_SESSION['accounts']);
		$accountsarray[$account_id] = $co;
		$_SESSION['accounts'] = $accountsarray;				

		//If this account is the current one selected by the user, update the session variable:
		if ($_SESSION['account_id']==$account_id) $_SESSION['accountname'] = $co;
		
		//code to interpret and write out output from role radio buttons:				
		//Put salesadmin values in array:
		$assignsleadsarray = array();
		if (isset($_POST["assigns_leads"])) $assignsleadsarray = $_POST["assigns_leads"];
		
		if (isset($_POST['role']))
		{
			//loop through users and update roles and salesadmin values to account_lookup:
			foreach ($_POST['role'] as $key => $roleoutput)
			{
				$assignsleadsvalue = 'N';
				if (in_array($key, $assignsleadsarray)) $assignsleadsvalue = 'Y';
				
				if ($roleoutput!='N')
				{
					//query and see if record existed for this user in account_lookup for this account..
					$q = "SELECT * FROM account_lookup 
					WHERE account_id='$account_id' 
					AND user_id='$key'";
					
					$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
					. mysqli_error($dbc));
					
					//Record found...updating:
					if (mysqli_num_rows($r) == 1)
					{
						//if it did, than UPDATE, if it didn't, then INSERT
						$q = "UPDATE account_lookup 
						SET role='$roleoutput', assigns_leads='$assignsleadsvalue' 
						WHERE account_id='$account_id'
						AND user_id='$key'";
					
						$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
						. mysqli_error($dbc));
						
						//Update Successful?:
						if (mysqli_affected_rows ($dbc) == 1) $updateroles = TRUE;
					}
					else
					{   //Record not found...inserting:
						$q = "INSERT INTO account_lookup 
						SET user_id='$key', account_id='$account_id', role='$roleoutput',
						assigns_leads='$assignsleadsvalue'";

						$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
						. mysqli_error($dbc));

						//Insert Successful?:
						if (mysqli_affected_rows ($dbc) == 1) $updateroles = TRUE;
					}
				}
				else 
				{
					//Scenario where user was set for disabled, and will now be INSERTed into the account_lookup database:
					//Query for user and account in account_lookup, if exists, DELETE.
					//query and see if record existed for this user in account_lookup for this account..
					$q = "SELECT * FROM account_lookup 
					WHERE account_id='$account_id' 
					AND user_id='$key'";
					
					$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
					. mysqli_error($dbc));
					
					//Record found...delete:
					if (mysqli_num_rows($r) == 1)
					{
						$q = "DELETE FROM account_lookup 
						WHERE user_id='$key' AND account_id='$account_id'";
						
						$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
						. mysqli_error($dbc));
					}
				}

			}
		}
		if ($updateaccounts) {
			echo '
<div class="alert-holder">
	<div class="alert-box green">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>Your settings have been saved successfully</p>
			</div>
		</div>
	</div>
</div>';
		} else {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Your information was not changed. Contact the system administrator if you think an error has occurred.</p>
			</div>
		</div>
	</div>
</div>';			
		}
		//}
		/*else
		{
			echo '<table border="1" bgcolor="red"><tr><td>That Markometer Web Address is already in use!</td></tr></table>';				
			exit();
		}*/
    }
}   //End of the main Submit

?>  

<?php

//Code to run when page loads, to load in correct master account parameters:
require_once (MYSQL);

//Set up variables to check for success of account and master account reads:
$accountsuccess = $masteraccountsuccess = FALSE;

//Query for sub-account info to fill out form:
$q = "SELECT company, DATE_FORMAT(date_created,'%M %e, %Y') as date_created, cctype,
ccexp_month, ccexp_year, ignore_my_traffic, annual_sales, avg_value_sale, 
avg_profit_margin, industry, bill_agency, customer 
FROM accounts 
WHERE id=$account_id";

$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
. mysqli_error($dbc));

//Check success of user query:
if (@mysqli_num_rows($r) == 1) 
{
	$row = mysqli_fetch_array($r,MYSQLI_ASSOC);
	$company = $row['company'];
	$membersince = $row['date_created'];
	//$_SESSION['tempSubdomain'] = $mp_subdomain = $row['mp_subdomain'];
	$cctype = $row['cctype'];
	$ccexp_month = $row['ccexp_month'];
	$ccexp_year = $row['ccexp_year'];
	$ignore_my_traffic = $row['ignore_my_traffic'];
	$annualsales = $row['annual_sales'];
	$avgvalue_ofsale = $row['avg_value_sale'];
	$avg_profit = $row['avg_profit_margin'];
	$industry = $row['industry'];
	$bill_agency = $row['bill_agency'];
	$customer = $row['customer'];

	$accountsuccess = TRUE;
}
else
{
	echo 'An error has occurred. Please contact the site administrator.';
}

//Get active and archived campaigns for the master account:
$q2 = "SELECT COUNT(*) as actives FROM campaigns WHERE account_id=$account_id AND archived='N'";

$r2 = mysqli_query($dbc, $q2) or trigger_error("Query: $q2\n<br />MySQL Error: " 
. mysqli_error($dbc));

//Get number of active campaigns:
$row2 = mysqli_fetch_array($r2,MYSQLI_ASSOC);
$activecampaigns = $row2['actives'];

$q2 = "SELECT COUNT(*) as actives FROM campaigns WHERE account_id=$account_id AND archived='Y'";

$r2 = mysqli_query($dbc, $q2) or trigger_error("Query: $q2\n<br />MySQL Error: " 
. mysqli_error($dbc));

//Get number of active campaigns:
$row2 = mysqli_fetch_array($r2,MYSQLI_ASSOC);
$archivedcampaigns = $row2['actives'];


//Get Master Account info:

$q = "SELECT company, cctype,
ccexp_month, ccexp_year 
FROM accounts 
WHERE id={$_SESSION['master_account']}";

$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
. mysqli_error($dbc));

//Check success of user query:
if (@mysqli_num_rows($r) == 1) 
{
	$row = mysqli_fetch_array($r,MYSQLI_ASSOC);
	$mastercompany = $row['company'];
	if ($bill_agency=='Y')
	{
		$cctype = $row['cctype'];
		$ccexp_month = $row['ccexp_month'];
		$ccexp_year = $row['ccexp_year'];
	}	
	$masteraccountsuccess = TRUE;
}
else
{
	echo 'An error has occurred. Please contact the site administrator.';
}

if ( ($accountsuccess && $masteraccountsuccess) || ($accountsuccess && $bill_agency=='N') )
{ }
else
{
	echo 'An error has occurred. Please contact the site administrator.';
	exit();
}

?>

<?php
echo '
<form id="edit_subaccount" action="/settings/subaccount/edit/?editAccountID=' . $account_id . '" method="post" class="buy-form">
<fieldset>
'; 
?>

<!-- Sub Account Information -->

<div class="buy-container">

<div class="head">
	<h2>Sub Account: <?php echo '' . $company . ''; ?></h2>
</div>


<div class="row">
	<label class="title">Company:</label>
	<input type="text" class="text large" name="company" maxlength="50" value="<?php if (isset($company)) echo $company; ?>" />
</div>
<div class="row">
	<ul class="list">
		<li>
			<strong>Member Since:</strong>
			<span><?php if (isset($membersince)) echo $membersince; ?></span>
		</li>
		<li>
			<strong>Active Campaigns:</strong>
			<span><?php echo $activecampaigns; ?></span>
		</li>
		<!--
		<li>
			<strong>Archived Campaigns:</strong>
			<span><?php echo $archivedcampaigns; ?></span>
		</li>
		-->
	</ul>
</div>


<!-- Billing Information -->
<div class="col">
	<div class="title-holder">
		<div class="heading heading-add">
			<h2>Billing:</h2>
		</div>
	</div>
	<div class="text-holder">
		<div class="radio-holder">
			<div class="holder">
				<input type="radio" id="billtomaster" class="radio" name="billto" value="Master" <?php if ($bill_agency=='Y') echo 'checked'; ?> /> 
				<label for="billtomaster"> &nbsp; Bill to Master Account (<?php if (isset($mastercompany)) echo '' . $mastercompany . ''; ?>) - Credit Card on File -
				<?php 
				if (isset($cctype)) {
					echo $cctype . ' expires '; 
					echo $ccexp_month . '/' . $ccexp_year;
				}
				?>
				</label>
			</div>
			<span>Choose this option if you plan on private labeling the application and charging each Sub Account separately at your own rates.</span>
		</div>
		<?php if ($bill_agency=='N')
		echo '
		<div class="radio-holder">
			<div class="holder">
			<input type="radio" id="billtoseparate" name="billto" value="Separate" checked />
			<label for="billtoseparate"> &nbsp; Bill to Separate Credit Card (on file)</label>
			</div>
			<span>Choose this option for us to bill each Sub Account (standard rates) or if you want to use a different card than the Master Account.</span>
		</div>
		<div class="radio-holder">
			<div class="holder">
				<input type="radio" id="billtonew" name="billto" value="Add" />
				<label for="billtonew">&nbsp; Enter a New Credit Card</label>
			</div>
			<span>Choose this option to change your separate credit that is billed.</span>
		</div>
			';
			else
			echo '
		<div class="radio-holder">
			<div class="holder">
				<input type="radio" id="billtonew" name="billto" value="Add" />
				<label for="billtonew">&nbsp; Bill to Separate Credit Card</label>
			</div>
			<span>Choose this option for us to bill each Sub Account (standard rates) or if you want to use a different card than the Master Account.</span>
		</div>
		';
		?>
		<div class="hide-block">
			<div class="row">
				<label class="title-add">Credit Card Number:</label>
				<input type="text" class="text" name="creditcardnumber" maxlength="25" />
				<span class="buy-ico"><img src="/images/cc-logos.gif" alt="We accept Visa, Mastercard and American Express" width="150" height="33" /></span>
				<span class="caption">We only take credit cards.</span>
			</div>
			<div class="row">
				<label class="title-add">Expires On:</label>
				<select id="ccmonth" name="ccmonth">
				<option value="1">1 - January</option>
				<option value="2">2 - February</option>
				<option value="3">3 - March</option>
				<option value="4">4 - April</option>
				<option value="5" selected="selected">5 - May</option>
				<option value="6">6 - June</option>
				<option value="7">7 - July</option>
				<option value="8">8 - August</option>
				<option value="9">9 - September</option>
				<option value="10">10 - October</option>
				<option value="11">11 - November</option>
				<option value="12">12 - December</option>
				</select>
				<select id="ccyear" name="ccyear">
				<option value="2010" selected="selected">2010</option>
				<option value="2011">2011</option>
				<option value="2012">2012</option>
				<option value="2013">2013</option>
				<option value="2014">2014</option>
				<option value="2015">2015</option>
				<option value="2016">2016</option>
				<option value="2017">2017</option>
				<option value="2018">2018</option>
				<option value="2019">2019</option>
				<option value="2020">2020</option>
				<option value="2021">2021</option>
				<option value="2022">2022</option>
				<option value="2023">2023</option>
				<option value="2024">2024</option>
				<option value="2025">2025</option>
				</select>
			</div>
			<div class="row">
				<label class="title-add">Billing Zip/Postal Code:</label>
				<input type="text" class="text" name="billingzipcode" maxlength="20" />
			</div>
		</div>
	</div>
</div>
<!-- / Billing Information -->

<!-- Additional Information -->									
<div class="col">
	<div class="title-holder">
		<div class="heading heading-add">
			<h2>Additional Information:</h2>
<?php # Help Button
$helphref = 'settings-account-additional-info.html';
$helptitle = 'Help with Sub Account Additional Information';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
?>
		</div>
	</div>
	<div class="row">
		<label class="title-large">Your Primary Business Activity:</label>
		<select id="customer" name="customer" class="select">
			<?php 
			if (strlen($customer)>2)
			{
				echo '<option selected value="' . $customer . '">' . $customer . '</option>';
			}
			else
			{
				echo '<option>Select Your Business Activity</option>';
			}
			?>
			<option value="Business to Business (B2B)">Business to Business (B2B)</option>
			<option value="Business to Consumer (B2C)">Business to Consumer (B2C)</option>
			<option value="Both B2B &amp; B2C">Both B2B &amp; B2C</option>
			<option value="Business to Government (B2G)">Business to Government (B2G)</option>
		</select>
	</div>
	<div class="row">
		<label class="title-large">Your Industry/Business Segment:</label>
		<select id="industry" name="industry" class="select">
			<?php 
			if (strlen($industry)>2)
			{
				echo '<option selected value="' . $industry . '">' . 
					$industry . '</option>';
			}
			else
			{
				echo '<option>Select Your Industry/Segment</option>';
			}
			?>
			<option value="Accounting/Finance">Accounting/Finance</option>
			<option value="Advertising/Marketing/Public Relations">Advertising/Marketing/Public Relations</option>
			<option value="Automotive">Automotive</option>
			<option value="Banking/Mortgage">Banking/Mortgage</option>
			<option value="Communications">Communications</option>
			<option value="Consulting">Consulting</option>
			<option value="Consumer Goods">Consumer Goods</option>
			<option value="Contractor">Contractor</option>
			<option value="Creative/Design">Creative/Design</option>
			<option value="Education">Education</option>
			<option value="Energy">Energy</option>
			<option value="Engineering">Engineering</option>
			<option value="Food Services/Hospitality">Food Services/Hospitality</option>
			<option value="Government/Military">Government/Military</option>
			<option value="Healthcare">Healthcare</option>
			<option value="Insurance">Insurance</option>
			<option value="Internet/New Media">Internet/New Media</option>
			<option value="IT/Software">IT/Software</option>
			<option value="Legal">Legal</option>
			<option value="Manufacturing">Manufacturing</option>
			<option value="Media/Entertainment">Media/Entertainment</option>
			<option value="Non-Profit/Volunteer">Non-Profit/Volunteer</option>
			<option value="Pharmaceutical/Biotech">Pharmaceutical/Biotech</option>
			<option value="Publishing &amp; Printing">Publishing &amp; Printing</option>
			<option value="Real Estate">Real Estate</option>
			<option value="Retail/Wholesale">Retail/Wholesale</option>
			<option value="Transportation/Logistics">Transportation/Logistics</option>
			<option value="Travel/Leisure/Hospitality">Travel/Leisure/Hospitality</option>
			<option value="Utilities">Utilities</option>
			<option value="Other">Other</option>
		</select>
	</div>
	<div class="row">
		<label class="title-add">Annual Sales:</label>
		<em class="price">$</em>
		<input type="text" class="text" name="annualsales" maxlength="50" value="<?php if (isset($annualsales)) echo $annualsales; ?>" />
		<span class="caption-add">Numbers only, no commas, round to the nearest dollar. Used to help you with forecasting.</span>
	</div>
	<div class="row">
		<label class="title-add">Average Value of Sale:</label>
		<em class="price">$</em>
		<input type="text" class="text" name="avgvalue_ofsale" maxlength="50" value="<?php if (isset($avgvalue_ofsale)) echo $avgvalue_ofsale; ?>" />
		<span class="caption-add">Numbers only, no commas, round to the nearest dollar. Used to help you with forecasting.</span>
	</div>
	<div class="row">
		<label class="title-add">Average Profit Margin:</label>
		<em class="price">&nbsp;</em>
		<input type="text" class="text" name="avg_profit" maxlength="10" value="<?php if (isset($avg_profit)) echo $avg_profit; ?>" />
		<em class="price">%</em>
		<span class="caption-add">Numbers only, give as percent value, round to nearest percent. Used to help you with forecasting.</span>
	</div>
	<div class="check-row">
		<input type="checkbox" id="trafficmeasure" class="check" name="ignoretraffic" value="Y"
		<?php if ($ignore_my_traffic=="Y") 
		{
			echo ' checked'; 
		}
			echo '/> <label for="trafficmeasure">Don\'t Measure My Visits <span>
			(Current IP Address: ' . $_SERVER['REMOTE_ADDR'] . ')</span></label>';
		?>
	</div>
</div>
<!-- / Additional Information -->

<!-- User Role Area -->
<div class="col">
	<div class="title-holder">
		<div class="heading heading-add">
			<h2>Account Role:</h2>
<?php # Help Button
$helphref = 'settings-user-roles.html';
$helptitle = 'Help with Account Roles';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
?>
		</div>
	</div>
<?php
//Build table of users for this account with their roles:

//Two SQL passes done, one to list out users associated with this master account, then work through
//those, making a SQL pass to account_lookup to find role if it's there
//SQL hit to users table to find this admin's users:
	
$q = "SELECT id, first_name, last_name 
FROM users 
WHERE master_account={$_SESSION['master_account']} AND users.active!='D' 
ORDER BY last_name";

$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

//Build table header:
echo '
<!-- User Role Table -->
	<div class="main-box">
		<div class="main-table">
			<table class="sortable">
				<thead>
					<tr>
						<th class="long nosort"><span>User</span></th>
						<th class="nosort"><span>Admin</span></th>
						<th class="nosort"><span>Marketing</span></th>
						<th class="nosort"><span>Sales</span></th>
						<th class="nosort"><span>Executive</span></th>
						<th class="nosort"><span>Assign Leads</span></th>
						<th class="nosort"><span>Disable</span></th>
					</tr>
				</thead>
				<tbody>';

while ($row = mysqli_fetch_array($r,MYSQLI_ASSOC))
{
	//Execute 2nd level SQL hit to account_lookup to get role info:
	$q2 = "SELECT role, assigns_leads 
	FROM account_lookup 
	WHERE user_id=" . $row['id'] . " AND account_id=$account_id";

	$r2 = mysqli_query($dbc,$q2) or trigger_error("Query: $q2\n<br />MySQL Error: " . 
	mysqli_error($dbc));

	//First and Last name in table:
	echo '<tr>
	<td class="long"><span>' . $row['first_name'] . ' ' . $row['last_name'] . ' </span></td>';
	
	if (mysqli_num_rows($r2) == 1)//If = 1, this person has a role in this account..if not, then is disabled
	{
	$row2 = mysqli_fetch_array($r2,MYSQLI_ASSOC);
	if ($row2['role']=="O") //Is this the account owner?:
	{
		echo '<td colspan="6" style="text-align:center;"><span>Account Owner has full permissions. These permissions cannot be modified.</span></td>';
	}
	else //Test for which radio button to check, depending on role:
	{
		echo '
		<td><input type="radio" name="role[' . $row['id'] . ']" value="A"';	if ($row2['role']=="A") echo ' checked'; echo ' /></td>
		<td><input type="radio" name="role[' . $row['id'] . ']" value="M"';	if ($row2['role']=="M") echo ' checked'; echo ' /></td>
		<td><input type="radio" name="role[' . $row['id'] . ']" value="S"';	if ($row2['role']=="S") echo ' checked'; echo ' /></td>
		<td><input type="radio" name="role[' . $row['id'] . ']" value="V"';	if ($row2['role']=="V") echo ' checked'; echo ' /></td>
		<td><input type="checkbox" name="assigns_leads[]" value="' . $row['id'] . '"'; if ($row2['assigns_leads']=="Y") echo ' checked'; echo ' /></td>		
		<td><input type="radio" name="role[' . $row['id'] . ']" value="N"';	echo ' /></td>';
	}
}
	else //User is disabled for this account.  Assign role buttons, set disabled button to checked:
	{
		echo '
		<td><input type="radio" name="role[' . $row['id'] . ']" value="A"';	echo ' /></td>
		<td><input type="radio" name="role[' . $row['id'] . ']" value="M"';	echo ' /></td>
		<td><input type="radio" name="role[' . $row['id'] . ']" value="S"'; echo ' /></td>		
		<td><input type="radio" name="role[' . $row['id'] . ']" value="V"'; echo ' /></td>
		<td><input type="checkbox" name="assigns_leads[]" value="' . $row['id'] . '"';	echo ' /></td>
		<td><input type="radio" name="role[' . $row['id'] . ']" value="N" checked';	echo ' /></td>';
	}
	echo '</tr>';
}
   mysqli_close ($dbc); //Close the database connection
?>	

<!-- tr class="add" for alternating colors -->

				</tbody>
			</table>
		</div>
	</div>
<!-- / User Role Table -->
</div>
<!-- / User Role Area -->

	<div class="content-link">
		<div class="link-holder">
			<div class="link-frame">
			<a href="#" class="btn" onclick="document.getElementById('edit_subaccount').submit()" /><span>Update Sub Account &gt;</span></a>
			<em>or</em>
			<a href="/settings/">Cancel</a>
			</div>
		</div>
	</div>

<input type="hidden" name="submitted" value="TRUE" />
<input type="hidden" name="editAccountID" value="<?php if (isset($account_id)) echo $account_id; ?>" />	

</div>
</fieldset>
</form>

<!-- / Main Content -->

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
