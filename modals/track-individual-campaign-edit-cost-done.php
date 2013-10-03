<?php # process changes to costs by user
//Programmer: Ray Neel 7/2009

require_once($_SERVER["DOCUMENT_ROOT"]."/inc/config.inc.php");

//Initialize a session if one not already in use:
if (session_id()=="") session_start();

echo '<div style="text-align:center">';

if (isset($_GET['submitted'])) {
	//test to see if the values entered are the same old values:
	if($_GET['actual']==$_GET['prioractual'] && $_GET['budgeted']==$_GET['priorbudgeted']) {
		echo 'Your cost values were not changed.';
	} else {
		//Test for something in budgeted cost box:
		if(strlen($_GET['budgeted'])>0) {
			//Write to campaigns table:
			//MySQL setup:
			require_once(MYSQL);
			$budgeted = $_GET['budgeted'];
			$actual = $_GET['actual'];
			//Pull in id of campaign to be edited from passed variable:
			$id = $_GET['cid'];
		
			$q = "UPDATE campaigns " . 
			"SET cost='$budgeted', actual_cost='$actual' " . 
			"WHERE id='$id' LIMIT 1";
		
			$r = mysqli_query($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " 
			. mysqli_error($dbc));
		
			if (mysqli_affected_rows($dbc) == 1) {
				echo 'Costs for your campaign have been updated!';			
			} else {
				echo $q . '<br />';
				echo 'There was a problem updating these costs. We are sorry for the inconvenience.';
			}
		} else {
			echo 'Please enter a budgeted cost for this campaign, and try again!';
		}
	}
	echo '
<br /><br />
<div class="modal-link">
	<div class="modal-link-holder">
		<div class="modal-link-frame">
			<a class="btn" href="#" onclick="Modalbox.hide()"><span>Close &gt;</span></a>
		</div>
	</div>
</div>

</div>
';
	exit();
}
?>
