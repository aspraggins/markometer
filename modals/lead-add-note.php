<?php # record a note from within a modal box
//Programmer: Ray Neel 1/2009 revised 8/2009 for leads/manage use
//Initialize a session if one not already in use:
if (session_id()=="") session_start();
$_SESSION['idlead'] = $_GET['idlead'];
?>
<div style="text-align:center;">
<form action="/modals/lead-add-note-done.php" method="get" id="noteform" onsubmit="return false;">

<label for="body"><strong>Your Note</strong></label><br />
<textarea name="body" id="body" cols="60" rows="7"></textarea><br /><br />
<strong>When Did It Happen? (Today is the default)</strong><br/>
<?php 
$t = getdate(time());  

$months = array(1 =>'January', 'February', 'March', 'April', 'May', 'June', 'July',  
'August', 'September', 'October', 'November', 'December');  

echo '<select name="month" id="month">';  
foreach( $months as $key => $value ) {  
    if ($months[$t['mon']]==$value) { 
       $sel=" selected=\"selected\""; 
    } else { 
       $sel=""; 
    } 
    echo "<option value = \"".$key."\"".$sel.">".$value."</option>";  
}  
echo "</select> "; 

echo ' <select name="day">';
foreach(range(1,31) as $days) {
    if ($t['mday']==$days) { 
       $sel=" selected=\"selected\""; 
    } else { 
       $sel=""; 
    } 
	echo '<option value=' . $days . $sel . '>' . $days . '</option>';
}
echo '</select> ';
$years = array(2000,2001,2002,2003,2004,2005,2006,2007,2008,2009,2010,2011,2012,2013,2014,2015,2016,2017,2018,2019,2020);
echo ' <select name="year">';
foreach($years as $year) {
    if ($t['year']==$year) { 
       $sel=" selected=\"selected\""; 
    } else { 
       $sel=""; 
    } 
	echo '<option value=' . $year . $sel . '>' . $year . '</option>';
}
echo '</select>'; 
?>

<br /><br />
<div class="modal-link">
	<div class="modal-link-holder">
		<div class="modal-link-frame">
			<a href="#" class="btn" onclick="Modalbox.show('/modals/lead-add-note-done.php', {title: 'New Note Status', afterHide:function() { location.reload(true) }, width: 500, params:Form.serialize('noteform') }); document.getElementById('noteform').submit();" /><span>Add Note &gt;</span></a>
			<em>or</em>
			<a href="#" onclick="Modalbox.hide()">Cancel</a>
		</div>
	</div>
</div>
<input type="hidden" name="submitted" value="TRUE" />
</form>

</div>
