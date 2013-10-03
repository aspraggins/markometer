<?php # Create Campaign CSV Upload Mapping Page
$metatitle = 'Create Campaign - Step 2 of 3 - Import Contact From File';
$returnurl = '/login/?returnpage=create/campaign/prestep1.php';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/header.php');
?>

<!-- Heading and Help Button -->

<div class="heading">
<h1>Import Contacts From File</h1>
<?php # Help Button
$helphref = 'create-campaign-upload-file-mappings.html';
$helptitle = 'Help with File Mappings';
include ($_SERVER['DOCUMENT_ROOT'] . '/inc/help.php');
?>
</div>

<!-- Main Content -->

<?php

if($_POST['option'] == "yes") {
		$uploaddir = './uploads/';
		$uploadfile = $uploaddir . $_FILES['csvfile']['name'];
		if (move_uploaded_file($_FILES['csvfile']['tmp_name'], $uploadfile)) {
/*
echo '
<div class="alert-holder">
	<div class="alert-box green">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>File successfully uploaded!</p>
			</div>
		</div>
	</div>
</div>';
*/
			$notice = 1;
			chmod($uploadfile, 0777);
		} else {
echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Upload Failed. Please make sure you select a file to upload first. Press the Previous Step button below and try again.</p>
			</div>
		</div>
	</div>
</div>
<br/><br/>

<div class="content-link">
	<div class="link-holder">
		<div class="link-frame">
			<a class="btn btn-left" href="/create/campaign/step2/"><span>&lt; Previous Step</span></a>
			<em>or</em>
			<a href="/create/campaign/cancel.php">Cancel</a>
		</div>
	</div>
</div>
';
			$notice = 0;
			exit();
		} 
		$notice = 1;
		if ($notice == 1) {
			// echo "\n Showing Data...<br />";
			flush();
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
		
			// break data out of csv file and count number of columns:
			$data = fgetcsv($fp,0, ",");
			$num = count($data); 		
			if($num>120) {
echo '
<div class="alert-holder">
	<div class="alert-box red">
		<div class="holder">
			<div class="frame">
				<a class="close" href="#">X</a>
				<p>Too many fields to import! ' . $num; '</p>
			</div>
		</div>
	</div>
</div>';
				exit();
			}
			// end brackets for this stuff continued after this html:
?>

<div class="main-block main-block-add">
		
<div class="heading">
	<h2><?php echo '' . $num . ' Columns Imported &amp; Ready to Be Mapped'; ?></h2>
</div>

<p>Please choose the lead criteria in the dropdown boxes that correspond to each column:</p>

<?php 
$nexturl = '/create/campaign/step2/next.php';
echo '<form name="formChooseFields" action="' . $nexturl  . '" method="post" id="create_campaign_upload_choose_fields">';
?>
<div class="csv-style">
<table class="csv-import">
<thead>
<tr>
	
<?php
$i = 0;
while($i<=$num-1) {
	echo '<th class="csvcolumn"><select name="field_' . $i .
	'" id="field_' . $i . '"><option value="---">Select Field</option>' . 
	'<option value="IGNORE" selected="selected">-- IGNORE --</option><option value="fname">First Name</option>' . 
	'<option value="lname">Last Name</option><option value="email">Email</option>' . 
	'<option value="phone">Phone</option><option value="company">Company</option>' . 
	'<option value="address">Address</option><option value="city">City</option>' . 
	'<option value="state">State</option><option value="zip">Zip/Postal Code</option>' .      
	'<option value="country">Country</option></select></th>';
	$i++;	
}
?>

</tr>
</thead>
<tbody>
		
<?php	
		// Create table of CSV values on form, begin session array of csv values:
		$_SESSION['csvimport'] = array();
		$row = 0;
		echo '<tr class="csvrow">';		
		for ($c=0; $c < $num; $c++) {
			echo '<td>' . $data[$c] . '</td>';
			$_SESSION['csvimport'][$row][$c] = $data[$c];			
		}
		// continue adding csv values to session array:
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

echo'

</tr>
</tbody>
</table>
</div>

<br/><br/>

<div class="csv">
	<input type="checkbox" id="check-1" class="check-2" name="firstline" value="Y" /> <label for="check-1">Include the First Row of Your File (Shown Above)?</label>
</div>

<br/><br/>

<div class="content-link">
	<div class="link-holder">
		<div class="link-frame">
			<a class="btn btn-left" href="/create/campaign/step2/"><span>&lt; Previous Step</span></a>
			<a href="#" class="btn" onclick="document.getElementById(\'create_campaign_upload_choose_fields\').submit();" /><span>Finalize Lead Import &amp; Go To Next Step &gt;</span></a>
			<em>or</em>
			<a href="/create/campaign/cancel.php">Cancel</a>
		</div>
	</div>
</div>

<input type="hidden" name="columns" value="' . $num . '" />


</fieldset>
</form>
</div>
';
		if ($fileopen ==1) {
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
?>

<div class="alert-holder">
	<div class="alert-box yellow">
		<div class="holder">
			<div class="frame">
				<p>Don't worry - you'll have a chance to review your campaign before you finalize it.</p>
			</div>
		</div>
	</div>
</div>

<br/><br/>

<!-- / Main Content -->

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/inc/footer.php'); ?>
