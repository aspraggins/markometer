<?php # archive function, called from individual campaign pages

//Programmer: Ray Neel 4/2009

require_once('/home/rneel/web/htdocs/includes/config.inc.php');
include('/home/rneel/web/htdocs/includes/header.html');

//Check for logged in user:
if (!isset($_SESSION['id'])) {
	header("Location: /");
	exit();
}

//MySQL setup:
require_once(MYSQL);

if( isset($_GET['id']) && isset($_GET['action']) ) {
	$id = mysqli_real_escape_string($dbc, trim($_GET['id']));
	$action = mysqli_real_escape_string($dbc, trim($_GET['action']));

	if($action=='arch') {
		$q = "UPDATE campaigns " . 
		"SET updateflag=1-updateflag, archived='Y', archive_date=NOW() " . 
		"WHERE id='$id' LIMIT 1";
		
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
		Error: " . mysqli_error ($dbc));

		if (mysqli_affected_rows ($dbc) == 1) {		
			//Move to manage campaigns overview:
			$url = '/manage/campaigns/?indid=' . $id;
			header("Location: $url");
		}else{
			echo 'There was an error processing your request. Please try again.';
		}
	}else if($action=='unarch') {
		$q = "UPDATE campaigns " . 
		"SET updateflag=1-updateflag, archived='N' " . 
		"WHERE id='$id' LIMIT 1";
		
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL
		Error: " . mysqli_error ($dbc));

		if (mysqli_affected_rows ($dbc) == 1) {		
			//Move to manage campaigns overview:
			$url = '/manage/campaigns/?indid=' . $id;
			header("Location: $url");
		}else{
			echo 'There was an error processing your request. Please try again.';
		}
	}
	exit();
}
?>

