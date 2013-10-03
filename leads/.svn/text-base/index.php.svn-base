<?php # Leads Dashboard
$metatitle = 'Leads Dashboard';
$returnurl = '/login/?returnpage=leads/';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Heading and Help Button -->

<div class="heading">
<h1>Leads Dashboard
<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/current-account.php'); ?>
</h1>
<?php # Help Button
$helphref = 'leads-dashboard.html';
$helptitle = 'Help with Your Leads Dashboard';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
?>
</div>

<!-- Main Content -->

<?php
//MYSQL Setup:
require_once(MYSQL);

if (isset($_REQUEST['imported'])) {
	if ($_REQUEST['imported']=='good') {
		echo '
<div class="alert-holder">
	<div class="alert-box green">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>Your leads were imported successfully into your new campaign! They\'re waiting for you under "Leads Recently Imported" in the "For Review" box below.</p>
			</div>
		</div>
	</div>
</div>';
	}
}

if (isset($_REQUEST['deleted'])) {
	if ($_REQUEST['deleted']=='deleted') {
		echo '
<div class="alert-holder">
	<div class="alert-box green">
		<div class="holder">
			<div class="frame">
			<a class="close" href="#">X</a>
			<p>The lead was deleted from your campaign.</p>
			</div>
		</div>
	</div>
</div>';
	}
}
?>

<div class="search-container">

<!-- Quick Find Box -->

	<form name="f" id="quickfind" onsubmit="g();" action="/leads/manage/id/" method="get" class="search-form">
		<fieldset>
			<div class="row">
				<div class="find">
					<label for="q">Find a Lead:</label>
					<input type="text" class="text" name="q" id="q" size="35" autocomplete="off" />
					<input type="hidden" id="id" name="id" />
					<a href="#" class="btn" onclick="document.getElementById('quickfind').submit()"><span>Find &gt;</span></a>
					<div id="ac" style="display:none;border:1px solid black;background-color:white;"></div>
				</div>
			</div>
		</fieldset>
	</form>
	<script type="text/javascript" language="javascript" charset="utf-8">
	// <![CDATA[
	var a_c = new Ajax.Autocompleter('q','ac','/inc/autocomplete.php', {afterUpdateElement : getSelectedId});
	// ]]>

	function getSelectedId(text, li) {
	$('id').value=li.id;
	}
	</script>
			
<!-- / Quick Find Box -->

<?php
//MYSQL Setup:
require_once(MYSQL);

//Handle Submission of form:
$filter = '';
if (isset($_POST['submitted'])) {
	switch($_POST['leadfilter']) {
		case 'A':
			$filter = 'a';
			break;
		case 'I':
			$filter = 'i';
			break;
		case 'LAST':
			$filter = 'last';
			break;
		case 'ALL':
			$filter = 'all';
			break;
		case 'W':
			$filter = 'w';
			break;
		case '30':
			$filter = '30';
			break;
		case '90':
			$filter = '90';
			break;
		case '30Touch':
			$filter = '30Touch';
			break;
		case '60Touch':
			$filter = '60Touch';
			break;
		case '90Touch':
			$filter = '90Touch';
			break;
		case 'H':
			$filter = 'h';
			break;
		case 'WM':
			$filter = 'wm';
			break;
		case 'C':
			$filter = 'c';
			break;
		case 'CD':
			$filter = 'cd';
			break;
	}
	$url = '/leads/manage/?filter=' . $filter;
	header("Location: $url");
	exit();
}

//Build query for counting new leads on page:
$q = "SELECT COUNT(*) AS newcount " .   
"FROM leads " . 
"WHERE account_id={$_SESSION['account_id']} " .
"AND status='N'" . $filter;
	
$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

$row = mysqli_fetch_array($r,MYSQLI_ASSOC);

$newleads = $row['newcount'];

//Build query for counting overdue leads on page:
$q = "SELECT COUNT(*) AS newcount " .   
"FROM lead_followups " . 
"WHERE user_id={$_SESSION['id']} AND account_id={$_SESSION['account_id']} " .
"AND due_date!='0000-00-00 00:00:00' AND DATE(due_date)<CURDATE()" . $filter;
	
$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

$row = mysqli_fetch_array($r,MYSQLI_ASSOC);

$overdueleads = $row['newcount'];

//Build query for counting today leads on page:
$q = "SELECT COUNT(*) AS newcount " .   
"FROM lead_followups " . 
"WHERE user_id={$_SESSION['id']} AND account_id={$_SESSION['account_id']} " .
"AND DATE(due_date)=CURDATE()" . $filter;

$showme = $q;

$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

$row = mysqli_fetch_array($r,MYSQLI_ASSOC);

$todayleads = $row['newcount'];

//Build query for active leads on page:
$q = "SELECT COUNT(*) AS newcount " .   
"FROM leads " . 
"WHERE account_id={$_SESSION['account_id']} " .
"AND status='A'" . $filter;
	
$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

$row = mysqli_fetch_array($r,MYSQLI_ASSOC);

$activeleads = $row['newcount'];

//Build query for counting quoted leads on page:
$q = "SELECT COUNT(*) AS newcount " .   
"FROM leads " . 
"WHERE account_id={$_SESSION['account_id']} " .
"AND (stage='QT' OR stage='RP')" . $filter;
	
$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

$row = mysqli_fetch_array($r,MYSQLI_ASSOC);

$quotedleads = $row['newcount'];

//Build query for counting sold leads on page:
$q = "SELECT COUNT(*) AS newcount " .   
"FROM leads " . 
"WHERE account_id={$_SESSION['account_id']} " .
"AND stage='SD'" . $filter;
	
$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

$row = mysqli_fetch_array($r,MYSQLI_ASSOC);

$soldleads = $row['newcount'];

//Query for counting recently imported leads on page:
$q = "SELECT COUNT(*) AS newcount " .   
"FROM leads " . 
"WHERE account_id={$_SESSION['account_id']} " .
"AND last_imported='Y'" . $filter;
	
$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

$row = mysqli_fetch_array($r,MYSQLI_ASSOC);

$recentleads = $row['newcount'];

//Build query for counting junk leads on page:
$q = "SELECT COUNT(*) AS newcount " .   
"FROM leads " . 
"WHERE account_id={$_SESSION['account_id']} " .
"AND status='J'" . $filter;
	
$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

$row = mysqli_fetch_array($r,MYSQLI_ASSOC);

$junkleads = $row['newcount'];

//Build query for counting dead leads on page:
$q = "SELECT COUNT(*) AS newcount " .   
"FROM leads " . 
"WHERE account_id={$_SESSION['account_id']} " .
"AND status='D'" . $filter;
	
$r = mysqli_query($dbc,$q) or trigger_error("Query: $q\n<br />MySQL Error: " . 
mysqli_error($dbc));

$row = mysqli_fetch_array($r,MYSQLI_ASSOC);

$deadleads = $row['newcount'];


//Filter leads dropdown menu:
echo '
	<!-- Drop Down Box -->

		<form action="/leads/" class="search-form" method="post">
			<fieldset>
				<div class="filter">
';

//Set up pulldown menu for tasks to perform:
$selectedstr = ' selected="selected"';
echo '<label for="leadfilter">Filter Leads:</label>
<select name="leadfilter" id="select-2" onchange=\'this.form.submit()\'>';

echo '<option value=""'; 
if(isset($_POST['leadfilter'])) {if($_POST['leadfilter']=='ALL') echo $selectedstr;}
echo '>Select Filter</option>';

echo '<option value="A"'; 
if(isset($_POST['leadfilter'])) {if($_POST['leadfilter']=='A') echo $selectedstr;}
echo '>Show Active Leads</option>';

echo '<option value="I"'; 
if(isset($_POST['leadfilter'])) {if($_POST['leadfilter']=='I') echo $selectedstr;}
echo '>Show Inactive Leads</option>';

echo '<option value="LAST"'; 
if(isset($_POST['leadfilter'])) {if($_POST['leadfilter']=='LAST') echo $selectedstr;}
echo '>Show Last Imported Leads</option>';

echo '<option value="ALL"'; 
if(isset($_POST['leadfilter'])) {if($_POST['leadfilter']=='ALL') echo $selectedstr;}
echo '>Show All Leads</option>';

echo '<option value="W"'; 
if(isset($_POST['leadfilter'])) {if($_POST['leadfilter']=='W') echo $selectedstr;}
echo '>Show Leads Due This Week</option>';

echo '<option value="30"'; 
if(isset($_POST['leadfilter'])) {if($_POST['leadfilter']=='30') echo $selectedstr;}
echo '>Show Leads Older Than 30 Days</option>';

echo '<option value="90"'; 
if(isset($_POST['leadfilter'])) {if($_POST['leadfilter']=='90') echo $selectedstr;}
echo '>Show Leads Older Than 90 Days</option>';

echo '<option value="30Touch"'; 
if(isset($_POST['leadfilter'])) {if($_POST['leadfilter']=='30Touch') echo $selectedstr;}
echo '>Show Leads I Haven\'t Touched in 30 Days</option>';

echo '<option value="60Touch"'; 
if(isset($_POST['leadfilter'])) {if($_POST['leadfilter']=='60Touch') echo $selectedstr;}
echo '>Show Leads I Haven\'t Touched in 60 Days</option>';

echo '<option value="90Touch"'; 
if(isset($_POST['leadfilter'])) {if($_POST['leadfilter']=='90Touch') echo $selectedstr;}
echo '>Show Leads I Haven\'t Touched in 90 Days</option>';

echo '<option value="H"';
if(isset($_POST['leadfilter'])) {if($_POST['leadfilter']=='H') echo $selectedstr;}
echo '>Show Hot Leads</option>';

echo '<option value="WM"';
if(isset($_POST['leadfilter'])) {if($_POST['leadfilter']=='WM') echo $selectedstr;}
echo '>Show Warm Leads</option>';

echo '<option value="C"';
if(isset($_POST['leadfilter'])) {if($_POST['leadfilter']=='C') echo $selectedstr;}
echo '>Show Cool Leads</option>';

echo '<option value="CD"';
if(isset($_POST['leadfilter'])) {if($_POST['leadfilter']=='CD') echo $selectedstr;}
echo '>Show Cold Leads</option>';

echo '</select>';

//Hidden and Button inputs:
echo '
<input type="hidden" name="submitted" value="TRUE" />
		</div>
	</fieldset>
</form>

<!-- / Drop Down Box -->
';

echo '
	<!-- Leads Boxes -->

	<div class="boxes">
	<div class="boxes-holder">
	<div class="boxes-frame">
';

// Red Box:

echo '
		<!-- Red Box -->

		<div class="box">
			<h3>Attention Needed</h3>
			<ul class="list">
';

echo '
	<li>
		<strong>' . $newleads . '</strong>';
	if($newleads) {
		echo '
			<a href="/leads/manage/?filter=n"><span><em>New Leads</em></span></a>
		</li>
	';
	}else{
	echo '
			<a href="/leads/manage/?filter=n"><span><em>New Leads</em></span></a>
		</li>
	';
	}

echo '
	<li>
		<strong>' . $overdueleads . '</strong>'; 
	if($overdueleads) {
		echo '
			<a href="/leads/manage/?filter=o"><span><em>Overdue Leads</em></span></a>
		</li>
	';
	}else{
	echo '
			<a href="/leads/manage/?filter=o"><span><em>Overdue Leads</em></span></a>
		</li>
	';
	}
echo '
	<li>
		<strong>' . $todayleads . '</strong>';
	if($todayleads) {
		echo '
			<a href="/leads/manage/?filter=t"><span><em>Leads Need Attention Today</em></span></a>
		</li>
	';
	}else{
	echo '
			<a href="/leads/manage/?filter=t"><span><em>Leads Need Attention Today</em></span></a>
		</li>
	';
	}


echo '
		</ul>
	</div>
';

// Yellow Box:

echo '
	<!-- Yellow Box -->

	<div class="box">
		<h3>For Review</h3>
		<ul class="list">
';

echo '
	<li>
		<strong>' . $recentleads . '</strong>
			<a href="/leads/manage/?filter=last"><span><em>Leads Recently Imported</em></span></a>
		</li>
	<li>
		<strong>' . $junkleads . '</strong>';
	if($junkleads) {
		echo '
			<a href="/leads/manage/?filter=j"><span><em>Junk Leads</em></span></a>
		</li>
	';
	}else{
		echo '
			<a href="/leads/manage/?filter=j"><span><em>Junk Leads</em></span></a>
		</li>
	';
	}

echo '
	<li>
		<strong>' . $deadleads . '</strong>';
	if($deadleads) {
		echo '
			<a href="/leads/manage/?filter=d"><span><em>Dead Leads</em></span></a>
		</li>
	';
	}else{
		echo '
			<a href="/leads/manage/?filter=d"><span><em>Dead Leads</em></span></a>
		</li>
	';
	}

echo '
		</ul>
	</div>
';

// Green Box:

echo '
	<!-- Green Box -->
	
	<div class="box">
		<h3>Works in Progress</h3>
		<ul class="list">
';

echo '
	<li>
		<strong>' . $activeleads . '</strong>';
	if($quotedleads) {
		echo '
			<a href="/leads/manage/?filter=a"><span><em>Active Leads</em></span></a>
		</li>
	';
	}else{
		echo '
			<a href="/leads/manage/?filter=a"><span><em>Active Leads</em></span></a>
		</li>
	';
	}

echo '
	<li>
		<strong>' . $quotedleads . '</strong>';
	if($quotedleads) {
		echo '
			<a href="/leads/manage/?filter=q"><span><em>Quoted Leads</em></span></a>
		</li>
	';
	}else{
		echo '
			<a href="/leads/manage/?filter=q"><span><em>Quoted Leads</em></span></a>
		</li>
	';
	}

echo '
	<li>
		<strong>' . $soldleads . '</strong>';
	if($soldleads) {
		echo '
			<a href="/leads/manage/?filter=s"><span><em>Sold Leads</em></span></a>
		</li>
	';
	}else{
		echo '
			<a href="/leads/manage/?filter=s"><span><em>Sold Leads</em></span></a>
		</li>
	';
	}

echo '
		</ul>
	</div>
';

echo '
	</div>
	</div>
	</div>

	<!-- / Leads Boxes -->
';

?>

</div>

<!-- / Main Content -->

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
