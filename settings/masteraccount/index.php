<?php # Settings - Master Account Page
$metatitle = 'Edit Master Account';
$returnurl = '/login/?returnpage=settings/masteraccount';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Heading and Help Button -->

<div class="heading">
<h1>Edit Master Account Details</h1>
<?php # Help Button
$helphref = 'settings-edit-master-account.html';
$helptitle = 'Help with Editing Your Master Account Information';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
?>
</div>

<!-- Main Content -->

<?php

if (isset($_REQUEST['action']))
{
	if ($_REQUEST['action']=='ccupdated')
	{
		echo '
<div class="alert-holder">
	<div class="alert-box green">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>Your credit card information has been successfully updated.</p>
			</div>
		</div>
	</div>
</div>';
	}
}

if (isset($_POST['submitted']))
{			
	//set error message boolean so no greens and reds simultaneously:
	$errormsgshowing = FALSE;
	
    require_once (MYSQL);
 
    // Assume invalid values:
    $co = $mpdomain = $annual = $avgprofit = $avgvalue = $siteaddr = FALSE;
    
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
		$errormsgshowing = TRUE;
    }
	
    // Check for a annual sales:
	if(preg_match('/^[0-9,\$]{0,12}$/i', trim($_POST['annualsales']))) {
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
		$errormsgshowing = TRUE;	
	}

    // Check for an average value of sales:
	if(preg_match('/^[0-9,\$]{0,12}$/i', trim($_POST['avgvalue_ofsale']))) {
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
		$errormsgshowing = TRUE;
	}
	
    // Check for an average profit margin:
	if(preg_match('/^[0-9,\$]{0,12}$/i', trim($_POST['avg_profit']))) {
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
		$errormsgshowing = TRUE;
	}
	
    if ($co)
    {   // Everything validated...
		//Set variables to False for optional information:
		$q1 = $q2 = $q3 = $qindustry = $qcustomer = FALSE;
		
		$q1 = ", annual_sales='$annual' ";
		$q2 = ", avg_value_sale='$avgvalue' ";
		$q3 = ", avg_profit_margin='$avgprofit' ";
		if (isset($_POST['industry'])) $qindustry = ", industry='{$_POST['industry']}' ";
		if (isset($_POST['customer'])) $qcustomer = ", customer='{$_POST['customer']}' ";
		
		//Logic to decode "don't measure my traffic" checkbox:
		if (isset($_POST['ignoretraffic'])) {
			$ignoretraffic = $_POST['ignoretraffic'];
		} else {
			$ignoretraffic = 'N';
		}
		$qignoretraffic = ", ignore_my_traffic='$ignoretraffic' ";
		
		//Set check variable to confirm that the update happens:
		$updateaccounts = $updateroles = FALSE;
		
		//Check for private label status, possible upload of logo file:
		$uploaddir = $_SERVER['DOCUMENT_ROOT'] . '/images/logos/';
		if($_POST['privatelabel']=='Y') {
			// If they've selected to be private labeled:
			// Test to see if a logo file was uploaded:
			if(strlen($_FILES['logofile']['name'])>0) {
				//filter out by file extension:
			  	$allowedExtensions = array("gif","png","jpg");
				$temp = explode(".",$_FILES['logofile']['name']);
				$extension = end($temp);
				// test for correct file extension:
				if(!in_array($extension, $allowedExtensions)) {
					echo '
					<div class="alert-holder">
						<div class="alert-box red">
							<div class="holder">
								<div class="frame">
									<a class="close" href="#">X</a>
									<p>Unfortunately you tried to upload the wrong type of file. We only take GIF, JPG and PNG files. Please try again with the right file type.</p>
								</div>
							</div>
						</div>
					</div>';
						$errormsgshowing = TRUE;
				} else {
					//go ahead with file upload
					$uploadfile = $uploaddir . str_pad($_SESSION['master_account'],6,"0",STR_PAD_LEFT) . "_" . time() . "." . $extension;
					//delete any existing logo images for this account: 
					FOREACH (GLOB($uploaddir . str_pad($_SESSION['master_account'],6,"0",STR_PAD_LEFT) . "*") AS $filename) {
					   //ECHO "$filename size " . FILESIZE($filename) . "\n";
					   UNLINK($filename);
					}
					//copy & rename uploaded logo:
					if (move_uploaded_file($_FILES['logofile']['tmp_name'], $uploadfile)) {
						chmod($uploadfile, 0777);
						$updateprivupload = TRUE;
					} else {
						echo '
						<div class="alert-holder">
							<div class="alert-box red">
								<div class="holder">
									<div class="frame">
										<a class="close" href="#">X</a>
										<p>Upload Failed. Please make sure you select a file to upload first. Please try again.</p>
									</div>
								</div>
							</div>
						</div>';
						$errormsgshowing = TRUE;
					} //end of copy & rename uploaded logo if/then:
				}//end of testing for file extension (jpg,etc)
			} //end of testing for logo uploaded
	
			//handle tab and tab font colors:
			$tabcolor = $_POST['tabcolor'];
			$tabfontcolor = $_POST['tabfontcolor'];
	
			//record private labeled status enabled to accounts table:
			$q = "UPDATE accounts " 
			. "SET updateflag=1-updateflag, privatelabel='Y', "
			. "tabcolor='$tabcolor', tabfontcolor='$tabfontcolor' "
			. "WHERE id={$_SESSION['master_account']} LIMIT 1";

			$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
			Error: " . mysqli_error ($dbc));

			if (mysqli_affected_rows ($dbc) != 1) {
				echo '
				<div class="alert-holder">
					<div class="alert-box red">
						<div class="holder">
							<div class="frame">
								<a class="close" href="#">X</a>
								<p>We could not change your private label status. Please try again.</p>
							</div>
						</div>
					</div>
				</div>';
				$errormsgshowing = TRUE;
			}

			//set session variables for tab change:
			$_SESSION['privatelabel'] = 'Y';
			$_SESSION['tabcolor'] = $tabcolor; $_SESSION['tabfontcolor'] = $tabfontcolor;
			//reload style statements for tabs to reflect real time change:
			echo '<style type="text/css" media="screen">
			/* Tab Font Color */
			#nav li a{
				color:#' . $tabfontcolor . ';
			}
			/* Tab Color */
			#nav li a span{
				background:#' . $tabcolor . ';
			}
			/* Settings Tab Font Color */
			#header .add-nav li a{
				color:#' . $tabfontcolor . ';
			}
			/* Settings Tab Color */
			#header .add-nav li a span{
				background:#' . $tabcolor . ';
			}
			</style>';
		} else {
			//they've decided not to be private labeled:		
			//delete any existing logo images for this account: 
			FOREACH (GLOB($uploaddir . str_pad($_SESSION['master_account'],6,"0",STR_PAD_LEFT) . "*") AS $filename) {
			   //ECHO "$filename size " . FILESIZE($filename) . "\n";
			   UNLINK($filename);
			}			
			
			//record private labeled status enabled to accounts table:
			$q = "UPDATE accounts " 
			. "SET updateflag=1-updateflag, privatelabel='N'"
			. "WHERE id={$_SESSION['master_account']} LIMIT 1";

			$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
			Error: " . mysqli_error ($dbc));
			
			if (mysqli_affected_rows ($dbc) != 1) {
				echo '
				<div class="alert-holder">
					<div class="alert-box red">
						<div class="holder">
							<div class="frame">
								<a class="close" href="#">X</a>
								<p>We could not change your private label status. Please try again.</p>
							</div>
						</div>
					</div>
				</div>';
				$errormsgshowing = TRUE;
			} 			
			//set session variable for change:
			$_SESSION['privatelabel'] = 'N';
			//reload style statements for tabs to reflect real time change:
			echo '<style type="text/css" media="screen">
			/* Tab Font Color */
			#nav li a{
				color:#fff;
			}
			/* Tab Color */
			#nav li a span{
				background:#f68a1e;
			}
			/* Settings Tab Font Color */
			#header .add-nav li a{
				color:#fff;
			}
			/* Settings Tab Color */
			#header .add-nav li a span{
				background:#f68a1e;
			}
			</style>';
			
			//refresh top logo image:
			$logo = '/images/default-logo.png';
			echo '<script type="text/javascript"><!--
			document.getElementById("logoimg").src="' . $logo . '";
			//--></script>';
		}
	
		//Update the master account for this admin
		$q = "UPDATE accounts 
		  SET updateflag=1-updateflag, company='$co'$q1$q2$q3$qindustry$qcustomer$qignoretraffic 
		  WHERE id={$_SESSION['master_account']} LIMIT 1";
		  
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
		Error: " . mysqli_error ($dbc));

		if (mysqli_affected_rows ($dbc) == 1) $updateaccounts = TRUE;

		//Update $_SESSION['accounts'] array, (if used), to reflect change in company name:
		//Re-populate Session accounts array to include new name for account:
		$accountsarray = array();
		$masteraccountno = $_SESSION['master_account'];
		if ($_SESSION['multiaccounts']) {
			$accountsarray = $_SESSION['accounts'];
			unset($_SESSION['accounts']);
			$accountsarray[$masteraccountno] = $co;
			$_SESSION['accounts'] = $accountsarray;				
		}

		//Change master account name session variable:
		$_SESSION['master_account_name'] = $co;
		//If this account is the current one selected by the user, update the session variable:
		if ($_SESSION['account_id']==$masteraccountno) $_SESSION['accountname'] = $co;
		
		//Adding in code to interpret and write out output from role radio buttons:				
		//Put assignsleads values in array:
		$assignsleadsarray = array();
		if (isset($_POST["assigns_leads"])) $assignsleadsarray = $_POST["assigns_leads"];
		
		if (isset($_POST['role']))
		{
			//loop through users and update roles and assignsleads values to account_lookup:
			foreach ($_POST['role'] as $key => $roleoutput)
			{
				$assignsleadsvalue = 'N';
				if (in_array($key, $assignsleadsarray)) $assignsleadsvalue = 'Y';
				
				if ($roleoutput!='N')
				{
					$q = "SELECT * 
					FROM account_lookup
					WHERE user_id='$key' AND account_id={$_SESSION['master_account']}";
				
					$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
					. mysqli_error($dbc));
					
					if (mysqli_num_rows($r) == 1)
					{
						$q = "UPDATE account_lookup 
						SET role='$roleoutput', assigns_leads='$assignsleadsvalue' 
						WHERE user_id='$key' AND account_id={$_SESSION['master_account']} LIMIT 1";
						
						$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
						. mysqli_error($dbc));
						
						//Update Successful?:
						if (mysqli_affected_rows ($dbc) == 1) $updateroles = TRUE;
					}
					else
					{
						$q = "INSERT INTO account_lookup (user_id, account_id, role, assigns_leads) 
						VALUES('$key', {$_SESSION['master_account']}, '$roleoutput', '$assignsleadsvalue')";
					
						$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
						. mysqli_error($dbc));
						
						//Insert Successful?:
						if (mysqli_affected_rows ($dbc) == 1) $updateroles = TRUE;
					}
				}
				else
				{
					$q = "SELECT * 
					FROM account_lookup
					WHERE user_id='$key' AND account_id={$_SESSION['master_account']}";
			
					$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
					. mysqli_error($dbc));
					
					if (mysqli_num_rows($r) == 1)
					{
						$q = "DELETE FROM account_lookup 
						WHERE user_id='$key' AND account_id={$_SESSION['master_account']} LIMIT 1";
						
						$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
						. mysqli_error($dbc));

						//Delete Successful?:
						if (mysqli_affected_rows ($dbc) == 1) $updateroles = TRUE;
					}
				}
			}
		}
		
		
		if ($updateaccounts || $updateroles) {
			if(!$errormsgshowing) {
				//Update Session Variable:
				//$_SESSION['master_account_subdomain'] = $siteaddr;
			
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
            }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 			
		} else {
			//If it did not run okay
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
    }
}   //End of the main Submit

?>  

<?php
//Code to run when page loads, to load in correct master account parameters:
require_once (MYSQL);

//Query for master account info to fill out form: 
$q = "SELECT company, DATE_FORMAT(date_created,'%M %e, %Y') as date_created, cctype,
ccexp_month, ccexp_year, ignore_my_traffic, annual_sales, avg_value_sale, 
avg_profit_margin, industry, customer, privatelabel, tabcolor, tabfontcolor 
FROM accounts 
WHERE id={$_SESSION['master_account']}";

$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
. mysqli_error($dbc));

//Check success of user query:
if (@mysqli_num_rows($r) == 1) {
	$row = mysqli_fetch_array($r,MYSQLI_ASSOC);
	$company = $row['company'];
	$membersince = $row['date_created'];
	//$mp_subdomain = $row['mp_subdomain'];
	$cctype = $row['cctype'];
	$ccexp_month = $row['ccexp_month'];
	$ccexp_year = $row['ccexp_year'];
	$ignore_my_traffic = $row['ignore_my_traffic'];
	$annualsales = $row['annual_sales'];
	$avgvalue_ofsale = $row['avg_value_sale'];
	$avg_profit = $row['avg_profit_margin'];
	$industry = $row['industry'];
	$customer = $row['customer'];
	//tab color logic, if there is an entry, use it:
	$tabcolor = 'F5891D';
	if(strlen($row['tabcolor']) > 1) $tabcolor = $row['tabcolor'];
	$tabfontcolor = 'fff';
	if(strlen($row['tabfontcolor']) > 1) $tabfontcolor = $row['tabfontcolor'];	
} else {
	echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>An error has occurred. Please contact the site administrator.</p>
			</div>
		</div>
	</div>
</div>';
}

//Get active and archived campaigns for the master account:
$q2 = "SELECT COUNT(*) as actives FROM campaigns WHERE account_id={$_SESSION['master_account']} AND archived='N'";

$r2 = mysqli_query($dbc, $q2) or trigger_error("Query: $q2\n<br />MySQL Error: " 
. mysqli_error($dbc));

//Get number of active campaigns:
$row2 = mysqli_fetch_array($r2,MYSQLI_ASSOC);
$activecampaigns = $row2['actives'];

$q2 = "SELECT COUNT(*) as actives FROM campaigns WHERE account_id={$_SESSION['master_account']} AND archived='Y'";

$r2 = mysqli_query($dbc, $q2) or trigger_error("Query: $q2\n<br />MySQL Error: " 
. mysqli_error($dbc));

//Get number of active campaigns:
$row2 = mysqli_fetch_array($r2,MYSQLI_ASSOC);
$archivedcampaigns = $row2['actives'];
?>

<!-- Master Account Information -->

<div class="buy-container">

<div class="head">
	<h2>Master Account: <?php echo '' . $_SESSION['master_account_name'] . ''; ?></h2>
</div>

<form id="edit_masteraccount" action="/settings/masteraccount/" method="post" class="buy-form" enctype="multipart/form-data">
<fieldset>

<div class="col">
<div class="row">
	<label class="title">Company Name:</label>
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
</div>

<!-- / Master Account Information -->

<!-- Private Label Information -->

<div class="col">
	<div class="title-holder">
		<strong>Private Label As Your Own Application?</strong>
		<input id="privatelabelyes" name="privatelabel" type="radio" class="radio" onclick="showhide('privlabel','open');" value="Y" checked />
		<label for="privatelabelyes">Yes</label>
		<input id="privatelabelno" name="privatelabel" type="radio" class="radio" onclick="showhide('privlabel','close');" value="N" />
		<label for="privatelabelno">No</label>
		<span class="holder-caption">Select Yes to remove all Markometer branding and use your own instead. All communications will be rebranded as "<?php echo '' . $_SESSION['master_account_name'] . ''; ?>".</span>
	</div>

	<?php
	$importlogostring = '/settings/masteraccount/importlogo/?id=' . $_SESSION['master_account'];
	?>
<div class="text-holder" id="privlabel">
		<div class="row">
			<label class="title-add">Your Logo:</label>
<?php 
// Run showhide function to decide if private label info opens or not, based on what's in the data table:
if($row['privatelabel']=='N') {
	echo '<script language="javascript"> 
	showhide(\'privlabel\',\'close\');
	
	document.getElementById("privatelabelno").checked=true;
	</script>';
}

//File Import:
$file_open = 0;
echo '<input name="logofile" type="file" class="text small" />';
?>
			<span class="caption">Size: Must be exactly 400 pixels wide by 100 pixels tall. GIF, JPG, PNG only.<br/>Or <a href="mailto:#">email us</a> and we can do it for you. 
			<?php
			//Determine if there is a custom logo to display:
			$tree = $_SERVER["DOCUMENT_ROOT"];
			$search = $tree . '/images/logos/' . str_pad($_SESSION['master_account'],6,"0",STR_PAD_LEFT) . "*";
			$filepresent = glob($search);
			if(count($filepresent) > 0) {
				foreach($filepresent as $present) {
					$logo = '/images/logos/' . basename($present);	
					echo ' <strong>Your current logo:</strong><br/><img src="' . $logo . '" width="400" height="100" alt="' . $_SESSION['master_account_name'] . '" />';
					//Refresh top logo image:
					echo '<script type="text/javascript"><!--
					document.getElementById("logoimg").src="' . $logo . '";
					//--></script>';
				}
			}
			
			?>
			</span>
		</div>
		<div class="row">
			<label class="title-add">Tab Color:</label>
			<input type="text" class="text" name="tabcolor" id="tabcolor" value="<?php echo $tabcolor; ?>" />
			<!-- <a href="#" class="btn" id="colorbox"><span>Choose Color &gt;</span></a> -->
			<span class="caption">This will be the default color for the Build, Track, Leads &amp; Settings tabs.</span>
		</div>
		<div class="row">
			<label class="title-add">Tab Font Color:</label>
			<select name="tabfontcolor" id="select-2">
				<?php
				$selected = '';
				if($tabfontcolor=='000') $selected = ' selected="selected"';
				?>
				<option value="fff">White</option>
				<option value="000" <?php echo $selected; ?>>Black</option>
			</select>
			<span class="caption">If you chose a dark tab color, choose White. If you chose a light tab color, chose Black.</span>
		</div>
	
	</div>
</div>

<!-- / Private Label Information -->								
								
<!-- Billing Information -->								
<div class="col">
	<div class="title-holder">
		<div class="heading heading-add">
			<h2>Billing:</h2>
		</div>
	</div>
	<div class="text-holder">
		<p>Credit Card on File: 
		<?php 
		if (isset($cctype)) 
		{
			echo $cctype . ' expires '; 
			echo $ccexp_month . '/' . $ccexp_year;
		}
		?>
		(<a href="/settings/changecard/">Change Card</a>)</p>
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
$helptitle = 'Help with Master Account Additional Information';
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
		<input type="checkbox" id="trafficmeasure" class="check" name="ignoretraffic" value="Y"	<?php if ($ignore_my_traffic=="Y") echo 'checked="yes" '; ?> />
		<label for="trafficmeasure">Don't Measure My Visits <span>(Current IP Address: <?php echo $_SERVER['REMOTE_ADDR']; ?>)</span></label>
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
	WHERE user_id=" . $row['id'] . " AND account_id={$_SESSION['master_account']}";

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
			<a href="#" class="btn" onclick="document.getElementById('edit_masteraccount').submit()" /><span>Update Master Account &gt;</span></a>
			<em>or</em>
			<a href="/settings/">Cancel</a>
			</div>
		</div>
	</div>

<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
<input type="hidden" name="option" value="yes" />
<input type="hidden" name="submitted" value="TRUE" />

</div>
</fieldset>
</form>

<script>
  new Control.ColorPicker("tabcolor", { IMAGE_BASE : "/images/colorpicker/" } );
</script>

<!-- / Main Content -->

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
