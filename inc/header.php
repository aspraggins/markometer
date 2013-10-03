<?php # header.php
// HTML header for the site

//Start output buffering:
ob_start();

//Initialize a session if one not already in use:
if (session_id()=="") session_start();

//Include the configuration file:
require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.inc.php");

//If not logged in, or not an admin for this account, then redirect the user:
if ( (!isset($_SESSION['first_name']))) {
	ob_end_clean(); //Delete the buffer
	header("Location: $returnurl");
	exit(); //Quit the script
}

// Grab the URL path, chop it up, put bits in array and remove first element (it's blank)
$navurl = $_SERVER['REQUEST_URI'];
$navchop = parse_url($navurl);
$navdirs = explode("/", $navchop['path']);
array_shift($navdirs);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title><?php
if(isset($metatitle)) {
		echo $metatitle;
}
if(isset($_SESSION['master_account_name']) && $_SESSION['privatelabel']=='Y') {
	echo " - {$_SESSION['master_account_name']}";
} else {
	echo ' - Markometer';
}
?></title>

<link rel="stylesheet" href="/css/all.css" media="screen" type="text/css" />
<link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico">

<!--[if lt IE 8]><link rel="stylesheet" type="text/css" href="/css/ie.css" media="screen" /><![endif]-->

<?php
//handle custom tab and tab font colors, when private label enabled:
$tabcolor = '#f68a1e'; $tabfontcolor = '#fff';
if($_SESSION['privatelabel']=='Y') {
	if(isset($_SESSION['tabcolor'])) $tabcolor = '#' . $_SESSION['tabcolor'];
	if(isset($_SESSION['tabfontcolor'])) $tabfontcolor = '#' . $_SESSION['tabfontcolor'];
}
?>


<!-- Custom Styles -->
<style type="text/css" media="screen">
/* Tab Font Color */
#nav li a{
	color:<?php echo $tabfontcolor; ?>;
}
/* Tab Color */
#nav li a span{
	background:<?php echo $tabcolor; ?>;
}
/* Settings Tab Font Color */
#header .add-nav li a{
	color:<?php echo $tabfontcolor; ?>;
}
/* Settings Tab Color */
#header .add-nav li a span{
	background:<?php echo $tabcolor; ?>;
}
</style>
<!-- / Custom Styles -->

<!--
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/prototype/1.6.1.0/prototype.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/scriptaculous/1.8.3/scriptaculous.js"></script>
-->

<script type="text/javascript" src="/js/prototype-1.6.1.js"></script>
<script type="text/javascript" src="/js/effects.js"></script>
<script type="text/javascript" src="/js/prototype.main.js"></script>
<script type="text/javascript" src="/js/fastinit.js"></script>
<script type="text/javascript" src="/js/tablesort.js"></script>
<script type="text/javascript" src="/js/scriptaculous.js"></script>
<script type="text/javascript" src="/includes/calendar/calendar_us.js"></script>
<script type="text/javascript" src="/js/common.js"></script>

<?php
	// If it's the /build/campaigns/new/step1 or /build/campaigns/edit page include the campaigns JS
	if (($navdirs[0]=="build") && ($navdirs[1]=="campaigns") && ($navdirs[2]=="new") && ($navdirs[3]=="step1")):
	echo '<script type="text/javascript" src="/js/campaigns.js"></script>';
	elseif (($navdirs[0]=="build") && ($navdirs[1]=="campaigns") && ($navdirs[2]=="edit")):
	echo '<script type="text/javascript" src="/js/campaigns.js"></script>';
	else:
	endif;
?>

<?php
	// If it's the /build/actionpages/new/create or /build/actionpages/edit page include the action pages JS and confirm lose changes JS (new only)
	if (($navdirs[0]=="build") && ($navdirs[1]=="actionpages") && ($navdirs[2]=="new") && ($navdirs[3]=="create")):
	echo '<script type="text/javascript" src="/js/actionpages.js"></script>';
	echo '<script type="text/javascript" src="/js/confirmlose.js"></script>';
	elseif (($navdirs[0]=="build") && ($navdirs[1]=="actionpages") && ($navdirs[2]=="edit")):
	echo '<script type="text/javascript" src="/js/actionpages.js"></script>';
	else:
	endif;
?>

<?php
	// If it's the /settings/masteraccount page include the color picker JS
	if (($navdirs[0]=="settings") && ($navdirs[1]=="masteraccount")):
	echo '<script type="text/javascript" src="/js/yahoo.color.js"></script>
<script type="text/javascript" src="/js/colorPicker.js"></script>';
	else:
	endif;
?>

<?php
	// If it's the /build/campaigns/new/step2/map page include the JS alert
	if (($navdirs[0]=="build") && ($navdirs[1]=="campaigns") && ($navdirs[2]=="new") && ($navdirs[3]=="step2") && ($navdirs[4]=="map")):
	echo '<script type="text/javascript" src="/js/csv-upload-alert.js"></script>';
	else:
	endif;
?>

</head>
	
<?php
	// If it's the /build/campaigns/new/step1 page focus on the campaign goal box first and if it's /build/campaigns/new/step2 page focus on domain name text
	if (($navdirs[0]=="build") && ($navdirs[1]=="campaigns") && ($navdirs[2]=="new") && ($navdirs[3]=="step1")):
	echo '<body onload="javascript:document.getElementById(\'goal\').focus();">';
	elseif (($navdirs[0]=="build") && ($navdirs[1]=="campaigns") && ($navdirs[2]=="new") && ($navdirs[3]=="step2")):
	echo '<body onload="javascript:document.getElementById(\'domainnametext\').focus();">';
	else:
	echo '<body>';
	endif;
?>

<noscript>
<h1>You need to change a setting in your web browser</h1>
<p>This application requires a browser feature called <strong>JavaScript</strong>. All modern browsers support JavaScript. You probably just need to change a setting in order to turn it on.</p>
<p>Please see: <a href="http://www.google.com/support/bin/answer.py?hl=en&answer=23852">How to enable JavaScript in your browser</a>.</p>
<p>If you use ad-blocking software, it may require you to allow JavaScript from mp41.com.</p>
<p>Once you've enabled JavaScript you can <a href="/">try loading this site again</a>.</p>
<p>Thank you.</p>
</noscript>

<?php
	// If it's the main index dashboard page put the class of "inner" - otherwise don't
	if (($navdirs[0]=="build") || ($navdirs[0]=="create") || ($navdirs[0]=="manage") || ($navdirs[0]=="track") || ($navdirs[0]=="leads") || ($navdirs[0]=="settings")):
	echo '<div id="wrapper">';
	else:
	echo '<div id="wrapper" class="inner">';
	endif;
?>

<div id="header">
<div class="header-holder">

<!-- Top Header Area -->
<div class="bar">

<!-- Logo -->
<?php
//Discover if there is a custom logo to replace default Markometer image:
$logo = '/images/default-logo.png';
//determine formatting of logo file to look for:
$tree = $_SERVER["DOCUMENT_ROOT"];
//Search Wildcard string and implementation:
$search = $tree . '/images/logos/' . str_pad($_SESSION['master_account'],6,"0",STR_PAD_LEFT) . "*";
$filepresent = glob($search);
if(count($filepresent) > 0) {
	foreach($filepresent as $present) {
		//set to custom logo is privatelabel session variable is "Y":
		if($_SESSION['privatelabel']=='Y') $logo = '/images/logos/' . basename($present);		
	}
}
?>
<strong class="logo"><a href="/" title="Back to Dashboard"><img id="logoimg" src="<?php echo $logo; ?>" alt="Back to Dashboard" width="400" height="100" /></a></strong>
<!-- / Logo -->

<?php
//Code to figure out incoming subdomain, confirm in database:
//gets the http request string
$serverhost = explode('.',$_SERVER["HTTP_HOST"]);
//get the subdomain
$subdomain = $serverhost[0];

if (isset($_SESSION['url_subdomain']))
{
	if ($_SESSION['url_subdomain']!=$subdomain)
	{
		//Database hit to confirm this subdomain:
		require_once (MYSQL);
		//Make sure the selected subdomain is a real one:
		$q = "SELECT id FROM accounts WHERE mp_subdomain='$subdomain' AND agency='Y'";
		$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
		mysqli_error($dbc));
		
		if (mysqli_num_rows($r) == 1 || $subdomain=='be') {
			$_SESSION['url_subdomain'] = $subdomain;
		} else {
			echo '<ul class="header-links"><li>' . $subdomain . ' is not a valid account in our system!</li></ul>';
			exit();
		}
	}
}
else
{
	//Database hit to confirm this subdomain:
	require_once (MYSQL);
	//Make sure the selected subdomain is a real one:
	$q = "SELECT id FROM accounts WHERE mp_subdomain='$subdomain' AND agency='Y'";
	$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
	mysqli_error($dbc));
	
	if (mysqli_num_rows($r) == 1 || $subdomain=='be' || $subdomain=='signup') 
	{
		$_SESSION['url_subdomain'] = $subdomain;
		//Add in code to look up proper subdomain for this user:
		if (isset($_SESSION['id']))
		{
			if ($subdomain=='be'){
				$url = 'http://be.mp41.com/be';
				header("Location: $url");
			} elseif ($subdomain=='signup'){
				$url = 'http://signup.mp41.com/register';
				header("Location: $url");
			} else {
				$url = 'http://' . $_SESSION['master_account_subdomain'] . '.mp41.com/';
				//see if a returnpage is set (user tried to nav to a page logged out)
				if(isset($_GET['returnpage'])) {
					
					$url .= $_GET['returnpage'];
				}
				header("Location: $url");
			}
		}
	}
	else
	{
		echo '<ul class="header-links"><li>' . $subdomain . ' is not a valid account in our system!</li></ul>';
		exit();
	}
}

?>

<!-- Header Links -->
<ul class="header-links">
	<?php echo '<li>' . $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] . '</li>'; ?>
	<li><a href="/" title="Dashboard">Dashboard</a></li>
	<li><a href="#" title="Get Help">Help</a></li>
	<li><a href="/logout/" title="Logout">Logout</a></li>
</ul>
<!-- / Top Menu -->

<!-- Account Dropdown Chooser -->

<?php
// If Multiple accounts for this user, show pulldown menu
if (isset($_SESSION['multiaccounts']) && !isset($_GET['indid']) 
&& substr($_SERVER['PHP_SELF'],0,21)!='/build/campaigns/edit' 
&& $_SERVER['PHP_SELF']!='/build/actionpages/edit')	{
	if ($_SESSION['multiaccounts'])	{
		echo '<form action="/inc/account-select-dropdown.php" method="post" class="header-dropdown"><fieldset><label>Current Account:</label><select name="AccountID" id="select-1" onChange="javascript:form.submit();">';
		
		// Loop through accounts to build pulldown menu:
		foreach ($_SESSION['accounts'] as $key => $accountname)
		{
		echo '<option value="' . $key . '"';
		if ($_SESSION['account_id'] == $key) echo ' selected';
		echo '>' . $accountname . '</option>';
		}
	    echo '</select></fieldset></form>';
	}
}
?>

<!-- / Account Dropdown Chooser -->

</div>
<!-- / Top Header Area -->

<!-- Navigation -->

<div class="panel">

<?php

	// Start The Navgation
	echo '<ul id="nav">';
	
	// Build Tab
	if ($navdirs[0]=="build"):
	echo '<li class="active"><a href="/build/" title="Create and Edit Your Stuff"><span>Build</span></a></li>';
	else:
	echo '<li><a href="/build/" title="Create and Edit Your Stuff"><span>Build</span></a></li>';
	endif;

	// Create Tab
	if ($navdirs[0]=="create"):
	echo '<li class="active"><a href="/create/" title="Create Something New"><span>Create</span></a></li>';
	else:
	echo '<li><a href="/create/" title="Create Something New"><span>Create</span></a></li>';
	endif;

	// Manage Tab
	if ($navdirs[0]=="manage"):
	echo '<li class="active"><a href="/manage/" title="Manage Your Stuff"><span>Manage</span></a></li>';
	else:
	echo '<li><a href="/manage/" title="Manage Your Stuff"><span>Manage</span></a></li>';
	endif;

	// Track Tab
	if ($navdirs[0]=="track"):
	echo '<li class="active"><a href="/track/campaigns/" title="Track Your Marketing Campaigns"><span>Track</span></a></li>';
	else:
	echo '<li><a href="/track/campaigns/" title="Track Your Marketing Campaigns"><span>Track</span></a></li>';
	endif;

	// Leads Tab
	if ($navdirs[0]=="leads"):
	echo '<li class="active"><a href="/leads/" title="Manage Your Leads"><span>Leads</span></a></li>';
	else:
	echo '<li><a href="/leads/" title="Manage Your Leads"><span>Leads</span></a></li>';
	endif;
			
	echo '</ul>';

	echo '<ul class="add-nav">';

	// Settings Tab
	if ($navdirs[0]=="settings"):
	echo '<li class="active"><a href="/settings/" title="Manage Your Settings"><span>Settings</span></a></li>';
	else:
	echo '<li><a href="/settings/" title="Manage Your Settings"><span>Settings</span></a></li>';
	endif;

	echo '</ul>';

?>

</div>

<!-- / Navigation -->

		
<!-- Subnavigation -->

<?php

	// Start The Subnavigation
	if (($navdirs[0]=="build") || ($navdirs[0]=="create") || ($navdirs[0]=="manage") || ($navdirs[0]=="track") || ($navdirs[0]=="leads") || ($navdirs[0]=="settings")):
	echo '<ul class="sub-nav">';
	else:
	endif;
	
	// Build Subnav
		
	// Build Campaigns
	if (($navdirs[0]=="build") && ($navdirs[1]=="campaigns")):
	echo '<li class="active"><a href="/build/campaigns/" title="Create and Edit Campaigns">Campaigns</a></li>';
	elseif ($navdirs[0]=="build"):
	echo '<li><a href="/build/campaigns/" title="Create and Edit Campaigns">Campaigns</a></li>';
	else:
	endif;

	// Build Action Pages
	if (($navdirs[0]=="build") && ($navdirs[1]=="actionpages")):
	echo '<li class="active"><a href="/build/actionpages/" title="Create and Edit Action Pages">Action Pages</a></li>';
	elseif ($navdirs[0]=="build"):
	echo '<li><a href="/build/actionpages/" title="Create and Edit Action Pages">Action Pages</a></li>';
	else:
	endif;

	// Build Forms
	if (($navdirs[0]=="build") && ($navdirs[1]=="forms")):
	echo '<li class="active"><a href="/build/forms/" title="Create and Edit Forms">Forms</a></li>';
	elseif ($navdirs[0]=="build"):
	echo '<li><a href="/build/forms/" title="Create and Edit Forms">Forms</a></li>';
	else:
	endif;

	// End Build Subnav
		
	// Create Subnav

	// Create Campaign
	if (($navdirs[0]=="create") && ($navdirs[1]=="campaign")):
	echo '<li class="active"><a href="/create/campaign/prestep1.php" title="New Campaign">Campaign</a></li>';
	elseif ($navdirs[0]=="create"):
	echo '<li><a href="/create/campaign/prestep1.php" title="New Campaign">Campaign</a></li>';
	else:
	endif;

	// Create Action Page
	if (($navdirs[0]=="create") && ($navdirs[1]=="actionpage")):
	echo '<li class="active"><a href="/create/actionpage/choose/" title="New Action Page">Action Page</a></li>';
	elseif ($navdirs[0]=="create"):
	echo '<li><a href="/create/actionpage/choose/" title="New Action Page">Action Page</a></li>';
	else:
	endif;

	// Create Form
	if (($navdirs[0]=="create") && ($navdirs[1]=="form")):
	echo '<li class="active"><a href="/create/form/choose/" title="New Form">Form</a></li>';
	elseif ($navdirs[0]=="create"):
	echo '<li><a href="/create/form/choose/" title="New Form">Form</a></li>';
	else:
	endif;

	// End Create Subnav

	// Manage Subnav
		
	// Manage Campaigns
	if (($navdirs[0]=="manage") && ($navdirs[1]=="campaigns")):
	echo '<li class="active"><a href="/manage/campaigns/" title="Manage Campaigns">Campaigns</a></li>';
	elseif ($navdirs[0]=="manage"):
	echo '<li><a href="/manage/campaigns/" title="Manage Campaigns">Campaigns</a></li>';
	else:
	endif;

	// Manage Action Pages
	if (($navdirs[0]=="manage") && ($navdirs[1]=="actionpages")):
	echo '<li class="active"><a href="/manage/actionpages/" title="Manage Action Pages">Action Pages</a></li>';
	elseif ($navdirs[0]=="manage"):
	echo '<li><a href="/manage/actionpages/" title="Manage Action Pages">Action Pages</a></li>';
	else:
	endif;

	// Manage Forms
	if (($navdirs[0]=="manage") && ($navdirs[1]=="forms")):
	echo '<li class="active"><a href="/manage/forms/" title="Manage Forms">Forms</a></li>';
	elseif ($navdirs[0]=="manage"):
	echo '<li><a href="/manage/forms/" title="Manage Forms">Forms</a></li>';
	else:
	endif;

	// End Manage Subnav

	// Track Subnav
	
	// Track Campaigns
	if (($navdirs[0]=="track") && ($navdirs[1]=="campaigns")):
	echo '<li class="active"><a href="/track/campaigns/" title="Track Campaigns">Campaigns</a></li>';
	elseif ($navdirs[0]=="track"):
	echo '<li><a href="/track/campaigns/" title="Track Campaigns">Campaigns</a></li>';
	else:
	endif;

	// End Track Subnav

	// Leads Subnav
	
	// All Leads
	if (($navdirs[0]=="leads") && ($navdirs[1]=="manage")):
	echo '<li class="active"><a href="/leads/manage/?filter=all" title="Manage All Your Leads">All Leads</a></li>';
	elseif ($navdirs[0]=="leads"):
	echo '<li><a href="/leads/manage/?filter=all" title="Manage All Your Leads">All Leads</a></li>';
	else:
	endif;
	
	// End Leads Subnav

	// Settings Subnav
	
	// Settings My Info
	if (($navdirs[0]=="settings") && ($navdirs[1]=="myinfo")):
	echo '<li class="active"><a href="/settings/myinfo/" title="My Info">My Info</a></li>';
	elseif ($navdirs[0]=="settings"):
	echo '<li><a href="/settings/myinfo/" title="My Info">My Info</a></li>';
	else:
	endif;

	// Settings People
	if (($navdirs[0]=="settings") && ($navdirs[1]=="people")):
	echo '<li class="active"><a href="/settings/people/" title="People">People</a></li>';
	elseif ($navdirs[0]=="settings"):
	echo '<li><a href="/settings/people/" title="People">People</a></li>';
	else:
	endif;

if ($_SESSION['role']=='O') 
{
	// Settings Master Account
	if (($navdirs[0]=="settings") && ($navdirs[1]=="masteraccount")):
	echo '<li class="active"><a href="/settings/masteraccount/" title="Master Account">Master Account</a></li>';
	elseif ($navdirs[0]=="settings"):
	echo '<li><a href="/settings/masteraccount/" title="Master Account">Master Account</a></li>';
	else:
	endif;

	// Settings Add Sub Account
	if (($navdirs[0]=="settings") && ($navdirs[1]=="subaccount") && ($navdirs[2]=="add")):
	echo '<li class="active"><a href="/settings/subaccount/add/" title="Add Sub Account">Add Sub Account</a></li>';
	elseif ($navdirs[0]=="settings"):
	echo '<li><a href="/settings/subaccount/add/" title="Add Sub Account">Add Sub Account</a></li>';
	else:
	endif;
}

	// End Settings Subnav

	// Close The Subnavigation
	if (($navdirs[0]=="create") || ($navdirs[0]=="manage") || ($navdirs[0]=="track") || ($navdirs[0]=="leads") || ($navdirs[0]=="settings")):
	echo '</ul>';
	else:
	endif;

?>

<!-- / Subnavigation -->

</div>
</div>

<!-- Content -->

<div id="content">
<div class="content-holder">
<div class="content-frame">

<?php
// Check to see if all action pages in this account have a form:
require_once (MYSQL);

$q = "SELECT id FROM action_pages WHERE form_id=0 AND account_id={$_SESSION['account_id']}";

$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

if (mysqli_num_rows($r) > 0) {
			echo '
	<div class="alert-holder">
		<div class="alert-box red">
			<div class="holder">
				<div class="frame">
				<a class="close" href="#">X</a>
				<p>Oh no! At least one of your action pages doesn\'t have a lead form associated with it. This means you can\'t get leads from that action page. Please <a href="/build/actionpages/">review your action pages</a> (look for the "form" column) and update this to make this error go away.</p>
				</div>
			</div>
		</div>
	</div>';
}

?>
