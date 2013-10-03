<?php #ajax code to go check database for leads for this user:

//Initialize a session if one not already in use:
if (session_id()=="") session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.inc.php");

//database connection:
require_once (MYSQL);

//parse incoming search variable:
$s = '%' . $_POST['q'] . '%';

//query leads table:
$q = "SELECT idlead, contact_first, contact_last, company " . 
"FROM leads " . 
"WHERE (contact_last LIKE '$s' OR contact_first LIKE '$s' OR company LIKE '$s') " . 
"AND account_id={$_SESSION['account_id']} " . 
"ORDER BY contact_last";

$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
Error: " . mysqli_error($dbc));

$response = '';
//if there are results, create first name and last name string to put into $matches to return to calling page:
if (mysqli_num_rows($r) > 0) {
	$response .= '<div class="entry">';
	while ($row = mysqli_fetch_array($r,MYSQLI_ASSOC)) {
		$response .= '<li id="' . $row['idlead'] . '">' . $row['contact_first'] . ' ' . $row['contact_last'] . ', ' . $row['company'] . '</li>';
	}
	$response .= '</div>';
}
	echo $response;
	exit;
?>