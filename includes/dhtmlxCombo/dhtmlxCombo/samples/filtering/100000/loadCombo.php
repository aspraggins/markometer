<?php
//Initialize a session if one not already in use:
if (session_id()=="") session_start();

require_once('/home/rneel/web/htdocs/includes/config.inc.php');

//database connection:
require_once (MYSQL);

	header("Content-type:text/xml");
	ini_set('max_execution_time', 7000);
	print("<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>");

	if (!isset($_GET["pos"])) $_GET["pos"]=0;
	
	getDataFromDB($_GET["mask"]);
	//mysql_close($link);

	//print one level of the tree, based on parent_id
	function getDataFromDB($mask){
		$s = '%' . $mask . '%';
		$q = "SELECT DISTINCT idlead, contact_first, contact_last, company " . 
		"FROM leads " . 
		"WHERE (contact_last LIKE '$s' OR contact_first LIKE '$s' OR company LIKE '$s') " . 
		"AND account_id={$_SESSION['account_id']} " . 
		"ORDER BY contact_last";

		if ( $_GET["pos"]==0)
			print("<complete>");
		else
			print("<complete add='true'>");

		//$res = mysql_query ($sql);
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
		Error: " . mysqli_error($dbc));

		if($r){
			while($row=mysqli_fetch_array($r)){
				print("<option value=\"".$row["idlead"]."\">");
				print($row["contact_first"] . ' ' . $row["contact_last"] . ', ' . $row["company"]);
				print("</option>");
			}
		}else{
			//echo mysql_errno().": ".mysql_error()." at ".__LINE__." line in ".__FILE__." file<br>";
			//echo mysqli_errno().": ".mysql_error()." at ".__LINE__." line in ".__FILE__." file";			
		}
		print("</complete>");
	}
?>
