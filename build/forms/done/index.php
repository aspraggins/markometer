<?php # Done page for form creation

require_once('/home/rneel/web/htdocs/includes/config.inc.php');
include('/home/rneel/web/htdocs/includes/header.html');

//Check for logged in user:
if (!isset($_SESSION['id'])) {
	header("Location: /");
	exit();
}

//See if "?action=formchosen" passed to this page, to see if part of a campaign creation sequence:
$campaignseq = FALSE;
if(isset($_REQUEST['action'])) {
	if($_REQUEST['action']=='formchosen') {
		$campaignseq = 'formchosen';
	} else if($_REQUEST['action']=='pagechosen') {
		$campaignseq = 'pagechosen';
	}
}
?>

<!-- Text to page, appropriate to a new form created, or an form chosen for a campaign sequence -->
<h1>
<?php 
if($_SESSION['multiaccounts']) {
    if (isset($_SESSION['accountname'])) echo $_SESSION['accountname'] . ': '; 
}
if($campaignseq=='formchosen') {
	echo 'Form Selected</h1>'; 
	echo '<b>Congratulations! You Have Successfully Chosen Your Form.</b><br />';
} else if($campaignseq=='pagechosen') {
	echo 'Action Page and Form Selected</h1>'; 
	echo '<b>Congratulations! You Have Successfully Chosen Your Action Page and its Form.</b><br />';
} else {
	echo 'New Form Created</h1>';
	echo '<b>Congratulations! You Have Successfully Created a New Form.</b><br />';
}

//show links for reviewing campaign:
echo '<table border="0" width="70%" style="display:inline">' . 
'<tr><td><br /><br />Next Steps: <a href="/manage/campaigns/">Review Your Campaigns</a>' . 
'</td></tr></table>';
?>

<?php //Include the HTML footer
include ('/home/rneel/web/htdocs/includes/footer.html');
?>
