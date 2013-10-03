<?php # Settings - Add Sub Account Page
$metatitle = 'Add a New Sub Account';
$returnurl = '/login/?returnpage=settings/subaccount/add';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Heading and Help Button -->

<div class="heading">
<h1>Add a New Sub Account</h1>
<?php # Help Button
$helphref = 'settings-add-sub-account.html';
$helptitle = 'Help with Adding a New Sub Account';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
?>
</div>

<!-- Main Content -->

<?php

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
	
    //Check for a valid MP Site Address:
    if(preg_match('/^[A-Z0-9-]{3,40}$/i', trim($_POST['mp_subdomain']))) {
		$siteaddr = strtolower(mysqli_real_escape_string($dbc, trim($_POST['mp_subdomain'])));
    } else {
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Please enter a valid site address. It must contain letters and numbers only.</p>
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
	if ($_POST['billto']=='Separate')	
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
				<p>lease enter your billing zip code!</p>
			</div>
		</div>
	</div>
</div>';			
	    }

	    $ccmonth = mysqli_real_escape_string($dbc, trim($_POST['ccmonth']));  
	    $ccyear = mysqli_real_escape_string($dbc, trim($_POST['ccyear']));  
	}
	
    if ( ($_POST['billto']=='Master' && $co && $siteaddr) || ($_POST['billto']=='Separate' && $cctype && $ccnum && $ccmonth && $ccyear && $bz && $co && $siteaddr) )
    {   //Everything validated...
		//Confirm availability of site address:
		$q = "SELECT id FROM accounts WHERE mp_subdomain='$siteaddr'";
		
		$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
		mysqli_error($dbc));
		
		if (mysqli_num_rows($r) == 0)
		{

			//Set variables to False for optional information:
			$q1 = $q2 = $q3 = $qindustry = $qcustomer = FALSE;
			$qcctype = $qccnum = $qccmonth = $qccyear = $qbz = FALSE;
			
			if ($annual) $q1 = ", annual_sales='$annual' ";
			if ($avgvalue) $q2 = ", avg_value_sale='$avgvalue' ";
			if ($avgprofit) $q3 = ", avg_profit_margin='$avgprofit' ";
			if (isset($_POST['industry'])) $qindustry = ", industry='{$_POST['industry']}' ";
			if (isset($_POST['customer'])) $qcustomer = ", customer='{$_POST['customer']}' ";			
			
			//Build credit card info, if needed:
			if ($_POST['billto']=='Separate')
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

			$q = "INSERT INTO accounts 
			 SET created_by={$_SESSION['id']}, company='$co', mp_subdomain='$siteaddr', 
			 agency='N', active='Y', date_created=NOW()
			 $q1$q2$q3$qindustry$qcustomer$qignoretraffic$qbillagency$qcctype$qccnum$qccmonth$qccyear$qbz ";
			 
			$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
			Error: " . mysqli_error ($dbc));

			if (mysqli_affected_rows ($dbc) == 1) $updateaccounts = TRUE;
			
			//pull out account number for use in account_lookup:
			$newaccountid = mysqli_insert_id($dbc);
						
			//Set up link from this admin to this new account:
			$q = "INSERT INTO account_lookup (user_id, account_id, role) 
			VALUES({$_SESSION['id']}, $newaccountid, 'O')";
		
			$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
			. mysqli_error($dbc));
			
			//Insert Successful?:
			if (mysqli_affected_rows ($dbc) == 1) $updateroles = TRUE;
			
			//Re-populate Session accounts array to include new account:
			$accountsarray = array();
			if ($_SESSION['multiaccounts'])
			{
				$accountsarray = $_SESSION['accounts'];
				unset($_SESSION['accounts']);
				$accountsarray[$newaccountid] = $co;
				$_SESSION['accounts'] = $accountsarray;				
			}
			else
			{
				$_SESSION['multiaccounts'] = TRUE;
				
				$q = "SELECT id, company FROM accounts WHERE id={$_SESSION['master_account']}";

				$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
				. mysqli_error($dbc));
				
				$row = mysqli_fetch_array($r,MYSQLI_ASSOC);
				$accountsarray[$row['id']] = $row['company'];
				$accountsarray[$newaccountid] = $co;
				
				$_SESSION['accounts'] = $accountsarray;
			}
			

			//Adding in code to interpret and write out output from role radio buttons:				
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
						$q = "INSERT INTO account_lookup (user_id, account_id, role, assigns_leads) 
						VALUES('$key', '$newaccountid', '$roleoutput', '$assignsleadsvalue')";
					
						$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
						. mysqli_error($dbc));
						
						//Insert Successful?:
						if (mysqli_affected_rows ($dbc) == 1) $updateroles = TRUE;
					}

				}
			}
			if ($updateaccounts || $updateroles)					
			{
				//Update Session Variable:
				
				mysqli_close ($dbc);//Close the database
				$url = '/settings/?action=accountadded'; //Define the URL
				ob_end_clean(); //Delete the buffer
				header("Location: $url");
				exit(); //Quit the script
			}
			else
			{
				//If it did not run okay
				echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Your information was not changed. Contact the system administrator if you think an error occurred. <a href="javascript:history.back();">Back</a></p>
			</div>
		</div>
	</div>
</div>';
				exit();
			}
		}
		else
		{
			echo 'That Markometer Web Address is already in use! Please select another.';
			exit();
		}
    }
}   //End of the main Submit

?>  

<?php

//Code to run when page loads, to load in correct master account parameters:
require_once (MYSQL);

//Query for master account info to fill out form:
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
	$company = $row['company'];
	$cctype = $row['cctype'];
	$ccexp_month = $row['ccexp_month'];
	$ccexp_year = $row['ccexp_year'];
}
else
{
	echo 'An error has occurred. Please contact the site administrator.';
}
?>

<form id="add_subaccount" action="/settings/subaccount/add/" method="post" class="buy-form">
<fieldset>

<!-- Sub Account Information -->

<div class="buy-container">

<div class="row">
	<label class="title">Company:</label>
	<input type="text" class="text large" name="company" maxlength="50" />
</div>
<div class="row">
	<label class="title">Create Site Address: <span>http://</span></label>
	<input type="text" class="text large" name="mp_subdomain" maxlength="25" />
	<span class="text-caption">.mk41.com</span>
	<span class="caption caption-large">Every site has its own address - letters and numbers only.</span>
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
				<input type="radio" id="billtomaster" class="radio" name="billto" value="Master" checked /> 
				<label for="billtomaster"> &nbsp; Bill to Master Account (<?php if (isset($company)) echo '' . $company . ''; ?>) - Credit Card on File -
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
		<div class="radio-holder">
			<div class="holder">
				<input type="radio" id="billtoseparate" class="radio" name="billto" value="Separate" /> 
				<label for="billtoseparate"> &nbsp; Bill to Separate Credit Card</label>
			</div>
			<span>Choose this option for us to bill each Sub Account (standard rates) or if you want to use a different card than the Master Account.</span>
		</div>
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
			<option>Select Your Business Activity</option>
			<option value="Business to Business (B2B)">Business to Business (B2B)</option>
			<option value="Business to Consumer (B2C)">Business to Consumer (B2C)</option>
			<option value="Both B2B &amp; B2C">Both B2B &amp; B2C</option>
			<option value="Business to Government (B2G)">Business to Government (B2G)</option>
		</select>
	</div>
	<div class="row">
		<label class="title-large">Your Industry/Business Segment:</label>
		<select id="industry" name="industry" class="select">
			<option>Select Your Industry/Segment</option>
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
		<input type="text" class="text" name="annualsales" maxlength="50" />
		<span class="caption-add">Numbers only, no commas, round to the nearest dollar. Used to help you with forecasting.</span>
	</div>
	<div class="row">
		<label class="title-add">Average Value of Sale:</label>
		<em class="price">$</em>
		<input type="text" class="text" name="avgvalue_ofsale" maxlength="50" />
		<span class="caption-add">Numbers only, no commas, round to the nearest dollar. Used to help you with forecasting.</span>
	</div>
	<div class="row">
		<label class="title-add">Average Profit Margin:</label>
		<em class="price">&nbsp;</em>
		<input type="text" class="text" name="avg_profit" maxlength="10" />
		<em class="price">%</em>
		<span class="caption-add">Numbers only, give as percent value, round to nearest percent. Used to help you with forecasting.</span>
	</div>
	<div class="check-row">
		<input type="checkbox" id="trafficmeasure" class="check" name="ignoretraffic" value="Y" />
		<?php echo '
		<label for="trafficmeasure">Don\'t Measure My Visits <span>
		(Current IP Address: ' . $_SERVER['REMOTE_ADDR'] . ')</span></label>
		';
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
//Build table of users for all of admin's accounts:
	
$q = "SELECT id, first_name, last_name 
FROM users 
WHERE master_account={$_SESSION['master_account']} AND users.active!='D' 
ORDER BY last_name";

$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

// Build table header:
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

// Show Account Owner:
echo '
<tr>
<td class="long"><span>' . $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] . ' </span></td>
<td colspan="6" style="text-align:center;"><span>Account Owner has full permissions. These permissions cannot be modified.</span></td>
</tr>';

while ($row = mysqli_fetch_array($r,MYSQLI_ASSOC))
{
	if($row['id']!=$_SESSION['id']) {
	
	    // Add role account radio buttons to table, check with each entry
	    // to show current role assignment:

	echo '
	<tr>
	<td class="long"><span>' . $row['first_name'] . ' ' . $row['last_name'] . ' </span></td>
	<td><input type="radio" name="role[' . $row['id'] . ']" value="A" /></td>
	<td><input type="radio" name="role[' . $row['id'] . ']" value="M" /></td>
	<td><input type="radio" name="role[' . $row['id'] . ']" value="S" /></td>
	<td><input type="radio" name="role[' . $row['id'] . ']" value="V" /></td>
	<td><input type="checkbox" name="assigns_leads[]" value="' . $row['id'] . '" /></td>	
	<td><input type="radio" name="role[' . $row['id'] . ']" value="N" /></td>
	</tr>
	';
	}
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
			<a href="#" class="btn" onclick="document.getElementById('add_subaccount').submit()" /><span>Add New Sub Account &gt;</span></a>
			<em>or</em>
			<a href="/settings/">Cancel</a>
			</div>
		</div>
	</div>

<input type="hidden" name="submitted" value="TRUE" />

</div>
</fieldset>
</form>

<!-- / Main Content -->

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
