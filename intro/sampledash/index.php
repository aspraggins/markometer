<?php #Sample dashboard to show user before they set up a campaign

//Include the configuration file:
require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.inc.php");
//$page_title = 'Marketing Payback';
$page_header = 'Dashboard';
include('/home/rneel/web/htdocs/includes/header.html');

/* $page_welcome_message = array(0 => 'Have you gained weight?','[sniff] What\'s that smell?', 
	'You got a perty mouth, boy..', 'Shake and Bake!');

srand(time());
$random = (rand()%4);	*/

//Welcome the user:
if(isset($_SESSION['first_name']))
{
	echo "<div align='center'><b>Start Screen (sample)</b></div><br />";
/*	echo "<div align='center'><b>Welcome Back, {$_SESSION['first_name']}.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . 
	$page_welcome_message[$random] . "</b></div><br />"; */

	echo "<div align='center'><b>Welcome Back, {$_SESSION['first_name']}</b></div><br />";

	echo '<table align="center" border="0">
	<tr><td>
		<img src="/images/campaigns/Campaign01.jpg" width="400" height="140">
	</td><td>
		<img src="/images/campaigns/Campaign02.jpg" width="400" height="140">
	</td></tr>
	<tr><td>
		<img src="/images/campaigns/Campaign03.jpg" width="400" height="140">
	</td></tr>
	</table>';
	
	
}
else
{
	if ($_SESSION['url_subdomain']=='signup'){
		$url = "http://signup.mp41.com/register";
	}else{
		$url = LOGIN;
	}
	header("Location: $url");
}
?>

<br /><br /><center><a href="/">Return</a></center>

<?php //Include the footer file:
include ('/home/rneel/web/htdocs/includes/footer.html');
?>