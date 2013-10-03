<?php
require_once('/home/rneel/web/htdocs/includes/config.inc.php');
$page_title = 'Manage Campaigns';
include('/home/rneel/web/htdocs/includes/header.html');

//Check that user is logged in:
if (!isset($_SESSION['id'])) {
	header("Location: /");
	exit();
}
 
$php_self = $_SERVER['PHP_SELF'] . '?id=' . $_GET['id'];
$file_open = 0;

$page = "<center>
<div align='center' style='border: 1px solid #000000; background-color: #000000;padding: 5px; color: #071bfd; width: 550px; font-family: verdana, arial, sans-serif;' >
<table align='center' style='border: 1px solid #eff7c2; background-color: #e2feea;padding: 10px; color: #071bfd;' cellspacing=5><tbody>
<tr><td><span style=\"font-family: arial, sans-serif;\"><i><b>Instructions:</b></i><br />
Please make sure you are trying to upload a TXT or CSV file only with the extension .txt or .csv at the end of the file name. <br />
Do not exceed the maximum upload size of 2mb.<br />
You can create your files using MS-Excel or similar CSV creator.<br /><br /></td></tr>
<tr><td style='background-color: #000000;padding: 5px'>
<form enctype='multipart/form-data' action='' method='post'>
<input type='hidden' name='MAX_FILE_SIZE' value='2000000' />
<input type='hidden' name='option' value='yes' /> 
<font color='#ffffff' size='-1'>Locate  CSV File: <br /></font><input name='csvfile' type='file' size='70' />&nbsp;<br />
<center><input type='submit' value='Upload CSV File' />
</td></tr></tbody></table></div></center>";


if(!isset($_POST['option'])) { 
			echo $page;
} elseif($_POST['option'] == "yes") {
		$uploaddir = './uploads/';
		$uploadfile = $uploaddir . $_FILES['csvfile']['name'];
		if (move_uploaded_file($_FILES['csvfile']['tmp_name'], $uploadfile)) {
			//print "<font face='arial'>Successfully uploaded. ";
			$notice = 1;
			chmod($uploadfile, 0777);
		} else {
			print "<font face='arial'>ERROR: Upload Failed. Please Press the Back Button and Try Again!";
			$notice = 0;
			exit();
		} 
		$notice = 1;
		if ($notice == 1) {
		//echo "\n Showing Data...<br />";
		flush();
		echo "</font>";
		if (file_exists($uploadfile)) {
		$fp = fopen($uploadfile, 'r') or die (" Cannot open the file");
		$fileopen = 1;
		$length = calculate_length($uploadfile);
		}
		$replace = "REPLACE";
		$field_terminater = ",";
		$enclose_option = 1;
		$enclosed = '"';
		$escaped = '\\\\';
		$line_terminator = 1;
		$local_option = 1;
		
		//break data out of csv file and count number of columns:
		$data = fgetcsv($fp,0, ",");
		$num = count($data); 		
		if($num>120) {
			echo 'Too many fields to import! ' . $num;
			exit();
		}
?>		
		
<!-- inserting code from icontact: -->
<h2>Import Contacts from File</h2>
<?php echo '<h3>' . $num . ' Fields Imported</h3>'; ?>
<p>Please choose which subscriber field corresponds to each column.</p>

<?php 
$nexturl = '/manage/campaigns/import/file/next/?id=' . $_GET['id'];
echo '<form name="formChooseFields" action="' . $nexturl  . '" method="post">';
?>
<div style="border: 0; OVERFLOW: auto; width: 700px; height: auto;" id="tableField"><table cellspacing=0 cellpadding=0 class="tableDataGrid"><tr><thead><tr>
	
<?php
$i = 0;
while($i<=$num-1) {
	echo '<th class="thColumnTitle"><select name="field_' . $i .
	'" id="field_' . $i . '"><option value="---">Select Field</option>' . 
	'<option value="IGNORE" selected="selected">-- IGNORE --</option><option value="fname">FName</option>' . 
	'<option value="lname">LName</option><option value="email">Email</option>' . 
	'<option value="phone">Phone</option><option value="address">Address</option>' . 
	'<option value="city">City</option><option value="state">State</option>' . 
	'<option value="zip">Zip</option><option value="country">Country</option></select></th>';
	$i++;	
}
?>

</tr></thead>
</tr>
		
<?php	
		//Create table of CSV values on form, begin session array of csv values:
		$_SESSION['csvimport'] = array();
		$row = 0;
		echo '<tr class="trDataGridRow" id="trSample_0">';		
		for ($c=0; $c < $num; $c++) {
			echo '<td>' . $data[$c] . '</td>';
			$_SESSION['csvimport'][$row][$c] = $data[$c];			
		}
		//continue adding csv values to session array:
		while (($data = fgetcsv($fp, 0, ",")) !== FALSE) {
			if($data[0]!=null) {
				$row++;
				//echo $row . '&nbsp;' . count($data) . '<br />';
				for ($c=0; $c < $num; $c++) {
				//echo $row . '&nbsp;' . count($data) . '<br />';
				$_SESSION['csvimport'][$row][$c] = $data[$c];
				}
			}
		}
		echo '</td></tr></table></div>';
		
		//submit button:
		echo '<br /><br /><input type="submit" value="Next" />' . 
		'&nbsp;or&nbsp;<a href="/manage/campaigns">Cancel</a>';
		echo '<input type="hidden" name="columns" value="' . $num . '" />';
		echo '</form>';
		
		/*$sql_query     =  'LOAD DATA';
			if ($local_option == "1") {
				$sql_query     .= ' LOCAL';
			}
			$sql_query     .= ' INFILE \'' . $uploadfile . '\'';
			if (!empty($replace)) {
				$sql_query .= ' ' . $replace;
			}
			$sql_query     .= ' INTO TABLE ' . "`csv_test`";
			if (isset($field_terminater)) {
				$sql_query .= ' FIELDS TERMINATED BY \'' . $field_terminater . '\'';
			}
			if (isset($enclose_option) && strlen($enclose_option) > 0) {
				$sql_query .= ' OPTIONALLY';
			}
			if (strlen($enclosed) > 0) {
				$sql_query .= ' ENCLOSED BY \'' . $enclosed . '\'';
			}
			if (strlen($escaped) > 0) {
				$sql_query .= ' ESCAPED BY \'' . $escaped . '\'';
			}
			if (strlen($line_terminator) > 0){
				$sql_query .= ' LINES TERMINATED BY \'' . '\n' . '\'';
			}
		
		$result = mysql_query ($sql_query);
		echo mysql_error() ;
		
		if(mysql_affected_rows() > 1) {
				echo " <div align=left><b><font color='#071bfd' face='arial'>The CSV data has been added to your database.<br><br> <a href='javascript:history.back()'><<< Return to CSV Upload Script</a></font></div> ";
		}
		else {
			error_log(mysql_error());
			echo " <div align=left><b><font color='#e42127' face='arial' > ERROR, could not add the CSV data to your database.</font></div>";
		}*/
		
		if ($file_open ==1) {
		fclose($fp) or die("ERROR: Could not close the file");
		}
	}
}

function calculate_length($fp) {
   $length = 1000;
   $array = file($fp);
   for($i=0;$i<count($array);$i++)
   {
       if ($length < strlen($array[$i]))
       {
           $length = strlen($array[$i]);
       }
   }
   unset($array);
   return $length;
}
include ('/home/rneel/web/htdocs/includes/footer.html');
?>
<script language="Javascript">
 function Alert() {
var question = alert("Confirm this is a CSV file no more than 2mb in size!");
return question;
 }
 </script>

<!-- <form enctype='multipart/form-data' action='$php_self' method='post' onSubmit='Alert()'> -->