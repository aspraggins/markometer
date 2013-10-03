<?php # Settings - Change Credit Card Page
$metatitle = 'Change Your Credit Card Information';
$returnurl = '/login/?returnpage=settings/changecard';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Heading and Help Button -->

<div class="heading">
<h1>Change Your Credit Card on File</h1>
<?php # Help Button
$helphref = 'settings-changecard.html';
$helptitle = 'Help with Changing Your Credit Card Info';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
?>
</div>

<!-- Main Content -->

<?php 

if (isset($_POST['submitted']))
{
    //Handle the form
    require_once(MYSQL);
  
    //Trim all of the incoming data:
    $trimmed = array_map('trim', $_POST);
  
    //Assume invalid values:
    $ccnum = $ccmonth = $ccyear = $cctype = $bz = FALSE;

    //Check for a valid Credit Card Number, discover type of card from number:
    if( preg_match('/^[0-9 ]{10,25}$/i', $trimmed['creditcardnumber']))
	{
		$tempstring = substr($trimmed['creditcardnumber'],0,1);
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
		$ccnum = mysqli_real_escape_string($dbc, $trimmed['creditcardnumber']);		
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
    if(preg_match('/^[0-9 -]{5,10}$/i', $trimmed['billingzipcode']))
    {
		$bz = mysqli_real_escape_string($dbc, $trimmed['billingzipcode']);
    }
    else
    {
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

    $ccmonth = mysqli_real_escape_string($dbc, $trimmed['ccmonth']);  
    $ccyear = mysqli_real_escape_string($dbc, $trimmed['ccyear']);  
  
    if ($ccmonth && $ccyear && $bz && $cctype && $ccnum) 
    {
		//Update the card information to the account table:
		$q = "UPDATE accounts 
		SET ccnumber='$ccnum', cctype='$cctype', ccexp_month='$ccmonth',
		ccexp_year='$ccyear', billing_zipcode='$bz' 
		WHERE id={$_SESSION['master_account']} LIMIT 1";
		
		$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
		. mysqli_error($dbc));
	  
		//Check success of account table insert:
		if (mysqli_affected_rows($dbc) == 1) 
		{
			mysqli_close ($dbc);//Close the database
			$url = '/settings/masteraccount/?action=ccupdated'; //Define the URL
			ob_end_clean(); //Delete the buffer
			header("Location: $url");
			exit(); //Quit the script
		}
		else
		{
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>There was a problem processing your billing information. Please contact the site administrator if you think an error has occurred.</p>
			</div>
		</div>
	</div>
</div>';	
		}
	}
	else
	{
	    //If one of the data tests failed
		echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Your card information appears to be incorrect. Please try again!</p>
			</div>
		</div>
	</div>
</div>';	
    }
}
?>

<form id="change_creditcard" action="/settings/changecard/" method="post" class="buy-form">
<fieldset>
	
<!-- Change Credit Card Information -->

<div class="buy-container">

<!-- Billing Information -->								
<div class="row">
	<div class="text-holder">
		<p>Enter Your New Billing Information (SECURE): <span>You <em>won't be billed</em> until you create a campaign or enter leads.</span></p>
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
			<input type="text" class="text" name="billingzipcode" maxlength="20" value="<?php if (isset($trimmed['billingzipcode'])) echo $trimmed['billingzipcode']; ?>" />
		</div>
	</div>
</div>
<!-- / Billing Information -->	

	<div class="content-link">
		<div class="link-holder">
			<div class="link-frame">
			<a href="#" class="btn" onclick="document.getElementById('change_creditcard').submit()" /><span>Update Credit Card Info &gt;</span></a>
			<em>or</em>
			<a href="javascript:history.back();">Cancel</a>
			</div>
		</div>
	</div>

<input type="hidden" name="submitted" value="TRUE" />

</div>
</fieldset>
</form>

<!-- / Main Content -->

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
