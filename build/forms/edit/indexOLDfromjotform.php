<?
include_once "lib/db.php";
include_once "lib/sql_form.php";
 
//RMN: adding for session continuity, need to figure out making my header work to do this:
//Start output buffering:
ob_start();
if (session_id()=="") session_start(); 
 
 //foreach($_SERVER as $a => $b){ print "<li>$a => $b"; }
 // check if there is an existing cookie
//if($_GET['formID'] == "new" &&  ($_COOKIE['lastForm'] < 1 || $_SERVER['QUERY_STRING'] == "new")){
$randomidset = FALSE;
if($_GET['formID'] == "new"){
	$formID = random_id();
	setcookie("lastForm", $formID, time()+2592000, "/");
	$randomidset = TRUE;
}else if ($_GET['formID'] > 0){
	$formID = $_GET['formID'];
	if($_GET['action'] == "clone"){
		list($user) = split(":",$_COOKIE['user']);
		$r = clone_form($formID, $user);
		if($r>0)
			$formID = $r;
		else
			print "Error: $r";
	} 
	setcookie("lastForm", $formID, time()+2592000, "/");
}else{
	$formID = $_COOKIE['lastForm'];
}

//RMN:
//Store formID in session variable:
$_SESSION['formID'] = $formID;

//Store passed action page id in variable, if present:
$actionid = FALSE;
if(isset($_REQUEST['actionid'])) $actionid = $_REQUEST['actionid'];
?> 
<script type="text/javascript" src="/includes/ajax.js"></script>
<script type='text/javascript'>
//Ajax Initialize:
window.onload = init;

function init() {
	var ajax = getXMLHttpRequestObject();
}

//Ajax code to hit MySQL datebase to look for availability of form name:
function checkFormName() {
	var ajax = getXMLHttpRequestObject();
	var cname = document.getElementById('formname').value;
	var requestURL = 'validate_sql.php?name='+cname;
	ajax.open('get', requestURL);
	ajax.onreadystatechange = function() {
		handleResponse(ajax);
	}
	ajax.send(null);
	return;
}

//Handle output from Ajax request:
function handleResponse(ajax) {
	if (ajax.readyState==4) {
		if ((ajax.status==200) || (ajax.status==304)) {
			var results = document.getElementById('results');
			results.innerHTML = ajax.responseText;
			results.style.display = 'block';
		} else {
			document.getElementById('autoform').submit();
		}
	}
}
</script>

<!-- Prototype and Scriptaculous libraries for effects -->
<script src="/includes/prototype.js" type="text/javascript"></script>
<script src="/includes/effects.js" type="text/javascript"></script>
<!-- Modal Box library -->
<script src="/includes/modalbox.js" type="text/javascript"></script>
<link rel="stylesheet" href="/includes/modalbox.css" type="text/css" media="screen" />

<?php
$include_wysiwyg = 1;
include "lib/header.php";

//Title and naming of this form:
if($_SESSION['multiaccounts']) {
    if (isset($_SESSION['accountname'])) echo $_SESSION['accountname'] . ': '; 
}
?>
Create a New Form&nbsp;<a href="help.php" title="Help" onclick="Modalbox.show(this.href, 
{title: this.title, width: 600, evalScript: true}); return false;">Help</a></h1><br />

<?php
echo '<form name="formform" action="/create/form/new/saveform.php" method="post">';
echo '<p><b>Name This Form:</b><input type="text" name="formname" id="formname" size="40" maxlength="40"
value="" onblur="checkFormName()" /></p>
<div id="results"></div><br />';
//Save..this will execute jotform's formsavedialog() function, and 
//associate the new formID with a name that user creates, for later use in building
//Campaign==>Action Page==>Form associations:
/*echo '<input type="button" name="submitbutton" value="Save This New Form and Exit" 
onclick="javascript:formSaveDialog(); setTimeout(function(){document.formform.submit();}, 1000);" />
<input type="hidden" name="submitted" value="TRUE" />
</form>'; */


if ($_COOKIE['user'] != ""){
	include_once "lib/sql_user.php";
	check_session($_COOKIE['user']);
}

if($formID>0){
  if(-1 == check_owner($formID, $GLOBALS['USERNAME'])){
   print "Authorization Problem!<br>You are either logged out or logged on to a wrong account.<p>";
   print "<b>Go to My Forms >>  </b> <br>";
   print "<b>Go create a new form >>   </b>";
   setcookie ("lastForm", "", time() - 3600);
   ?>
    <script>location.replace("<?=$GLOBALS['HTTP_URL']?>user.php");</script>
   <?
  }
 }
?>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
 <tr>
  <td>
   <table width="867" border="0" align="center" cellpadding="0" cellspacing="0" >
    <tr>
     <td>
      <table width="867" border="0" align="center" cellpadding="0" cellspacing="0">
       <tr>
        <td width="22" height="5" class="win_up_left"></td>
        <td width="823" class="win_up"></td>
        <td width="22" class="win_up_right"></td>
       </tr>
      </table>
      <table width="867" border="0" align="center" cellpadding="0" cellspacing="0">
       <tr>
        <td width="11" height="20" class="win_title_left"></td>
        <td width="4" class="win_title2_left"></td>
        <td class="win_title title_text">
         <span id="title" class="title_text" style="width:auto">
          Form has not been loaded...
         </span>
         <span id="notice"></span>
        </td>
        <td width="4" class="win_title2_right"></td>
        <td width="11" class="win_title_right"></td>
       </tr>
      </table>
      <table width="867" border="0" cellpadding="0" cellspacing="0">
       <tr>
        <td width="11" class="win_mid_left"></td>
        <td width="845" height="50">
         <table width="845" border="0" cellpadding="0" cellspacing="0" >
          <tr>
           <td width="617" height="600" align="left" valign="top" bgcolor="#FFFFFF" >
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
             <tr>
              <td height="27" style="background:url(images/bg-upper1.gif)" id="toolbar">
               <table border="0" cellspacing="0" cellpadding="0">
                <tr valign="top">
                 <!-- toolbar -->
				   <td width="11">&nbsp;</td>
                  <!--<td class="menu_new" style="cursor:pointer" onclick="formNew();" ></td>
				   <td class="top_menu" style="cursor:pointer" onclick="formNew();" >New</td>
                   <td width="15"></td>
                  <td class="menu_open" style="cursor:pointer" onclick="formOpen()"></td>
				   <td class="top_menu" style="cursor:pointer" onclick="formOpen()">Open</td>
                   <td width="15"></td> 
                  <td class="menu_save" style="cursor:pointer" id="save" onclick="formSaveDialog()" ></td>
				   <td class="top_menu" style="cursor:pointer" id="save" onclick="formSaveDialog()" >Save</td>
                   <td width="15"></td> -->
                  <td class="menu_preview" style="cursor:pointer" onclick="previewForm('<?=$formID?>')"></td>
				   <td class="top_menu" style="cursor:pointer" onclick="previewForm('<?=$formID?>')">Preview</td>
                   <td width="15"></td>
                  <!-- <td class="menu_share" style="cursor:pointer" onclick="document.location='user.php?page=share&amp;formID=<?=$formID?>'"></td>
				   <td class="top_menu" style="cursor:pointer" onclick="document.location='user.php?page=share&amp;formID=<?=$formID?>'">Share</td>
                  <td width="15"></td>-->
                  <td class="menu_source" style="cursor:pointer" onclick="formSource()"></td>
				   <td class="top_menu" style="cursor:pointer" onclick="formSource()">Source</td>
            	  <td width="15"></td>
                  <td class="menu_properties" style="cursor:pointer" onclick="showFormProperties()"></td>
				   <td class="top_menu" style="cursor:pointer" onclick="showFormProperties()">Properties</td>
            	  <td width="15"></td>
                  <!-- <td class="menu_formwizard" style="cursor:pointer" onclick="displayWindow('newForm',true); "></td>
				   <td class="top_menu" style="cursor:pointer" onclick="displayWindow('newForm',true); ">Form Wizard</td>-->
                  <!-- End -->
                 </tr>
                </table>               </td>
              </tr>
              <tr>
               <td height="1" bgcolor="#9e9e9e"></td>
              </tr>
             </table>
             <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
               <td>
                <? 
                  if($_COOKIE['user'] && $_GET['mode'] == "source"){
                    include("lib/breadcrumb.php");
                  }
                ?>
               </td>
              </tr>
             </table>
             <table width="100%" border="0" cellpadding="0" cellspacing="0" id="allTable" style="height:100%;">
<!--              <tr>
               <td width="7" style="height:0px"></td>
               <td height="7" style="height:0px"></td>
               <td width="7" style="height:0px"></td>
              </tr>
              <tr>
               <td></td> -->
               <td valign="top" id="formTable">
                <table width="100%" border="0" align="center" style="height:100%; margin:0px" cellpadding="0" cellspacing="0" class="tbmain" id="tbmain">
                 <tr>
                  <td class="topleft" width="10" height="10">&nbsp;</td>
                  <td class="topmid">&nbsp;</td>
                  <td class="topright" width="10" height="10">&nbsp;</td>
                 </tr>
                 <tr>
                  <td class="midleft" width="10"></td>
                  <td valign="top" class="midmid">
                   <span id="thelist_message"></span>
                   <ul id="thelist"><li></li></ul>
                   <div id="noscript">Sorry!<br /><br />Jotform Form Builder Works on Only JavaScript Enabled Browsers.</div>
                   <script type="text/javascript">document.getElementById('noscript').innerHTML = "";</script>
                  </td>
                  <td class="midright" width="10"></td>
                 </tr>
                 <tr>
                  <td class="bottomleft" width="10" height="10">&nbsp;</td>
                  <td class="bottommid">&nbsp;</td>
                  <td class="bottomright" width="10" height="10">&nbsp;</td>
                 </tr>
                </table>
               </td>
               <td></td>
              </tr>
             </table>
             
             <script type="text/javascript">
			 //Adding code to get rid of tools that are already in use on a form
			 //Code provided by jotform guy:
			//var prop = {};
			//var submit_field = "";
            var formID = <?=$formID?>;
			//Event.observe(window, 'load', function() {
				<? build_questions($formID); ?>
				changed(0);
			//})
            </script>
			
			<?php
			// 3/09 Check to see if new, randomized formID needs to be assigned 
			//(if using prebuilt or existing form)
			if(!$randomidset) {
				$formID = random_id();
				setcookie("lastForm", $formID, time()+2592000, "/");
				//Store formID in session variable:
				$_SESSION['formID'] = $formID;
			}
			?>
			 
			<script type="text/javascript">
            var formID = <?=$formID?>;			
			</script>
			 <!-- insert jotform function to create new formID, set here after questions built as in line 274 -->

             <table cellpadding="0" align="left" cellspacing="0" bgcolor="#FFFFFF" id="sourceTable" style="display:none;">
              <tr>
               <td height="4" ></td>
              </tr>
              <tr>
               <td valign="top" >
               <div style="padding:4px;">
                <table width="100%" cellpadding="0" cellspacing="0">
                 <tr>
                  <td>
                   <span style="font-size:12px"><b>Option 1: Embed Form into Your Site</b></span><br />
                   It's recommended to paste this code in a table or width defined area in your site.
                  </td>
                  <td class="" width="50" valign="bottom">
                   <div class="icn_copy" style="cursor:pointer;" onclick="copy($('emb2').value,'emb')"></div>
                  </td>
                 </tr>
                </table>
               </div>
              </td>
             </tr>
             <tr>
              <td valign="top" style="padding:4px;">
               <div id='emb' class="innerembed" style="padding:2px;"></div>
               <textarea id="emb2" style="display:none" rows="" cols=""></textarea>
              </td>
             </tr>
             <tr>
              <td height="4" valign="top">
               <div style="padding:4px;">
                <table width="100%" cellpadding="0" cellspacing="0">
                 <tr>
                  <td>
                   <span style="font-size:12px"><b>Option 2: Copy Full Source Code of your Form</b></span><br />
                   It's recommended to paste this code in a table or width defined area in your site.
                  </td>
                  <td width="50" valign="bottom">
                   <div class="icn_copy" style="cursor:pointer;" onclick="copy($('src2').value,'code')"></div>
                  </td>
                 </tr>
                </table>
               </div>
               <textarea id="src2" style="display:none" rows="" cols=""></textarea>
              </td>
             </tr>
             <tr>
              <td valign="top" align="center" style="padding:4px;">
               <div id="code" class="code"></div>
              </td>
             </tr>
            </table>
           </td>
           <td width="1" bgcolor="#9e9e9e"></td>
           
		  

		  <td width="227" valign="top" bgcolor="#f5f5f5">

			 <div id="toolscontainer" style="position:relative; height:100px;">
			  <div id="toolsdiv" style="position:absolute;"><? include("tools.php"); ?></div>
			 </div>
           </td>
          
		  
		  
		  </tr>
         </table>
        </td>
        <td width="11" class="win_mid_right"></td>
       </tr>
      </table>
      <table width="867" border="0" align="center" cellpadding="0" cellspacing="0">
       <tr>
        <td width="22" height="22" class="win_down_left"></td>
        <td width="606" class="win_down"></td>
        <td width="1" class="win_down2"></td>
        <td width="216"  class="win_down1"></td>
        <td width="22" class="win_down_right"></td>
       </tr>
      </table>
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
	
 <script type="text/javascript" charset="utf-8">
  // <![CDATA[
  
  //Tools
   new Draggable('control_text',{revert:true});
   new Draggable('control_head',{revert:true});
   new Draggable('control_lastname',{revert:true});   
   new Draggable('control_firstname',{revert:true});      
   new Draggable('control_title',{revert:true});         
   new Draggable('control_company',{revert:true});   
   new Draggable('control_email',{revert:true});      
   new Draggable('control_address',{revert:true});  
   new Draggable('control_city',{revert:true});      
   new Draggable('control_state',{revert:true});      
   new Draggable('control_zip',{revert:true});      
   new Draggable('control_country',{revert:true});   
    new Draggable('control_phone',{revert:true});    
	new Draggable('control_inquiry',{revert:true});      
   
   /*new Draggable('control_customtext1',{revert:true});   	
   new Draggable('control_customtext2',{revert:true});   	
   new Draggable('control_customtext3',{revert:true});*/ 
   
/*	$$(".controls").each(function(control){
		new Draggable(control, { onEnd:function(drag, drop){
			//if(control!="control_textbox") { 
				drag.element.hide();
			//}
		}});
	}); */   
   
   //new Draggable('control_custompulldown1',{revert:true});         
   //new Draggable('control_custompulldown2',{revert:true});            
	
	//new Draggable('control_country',{revert:true});      
	//new Draggable('control_phone',{revert:true});      
   new Draggable('control_textbox',{revert:true});
   new Draggable('control_textarea',{revert:true});
   new Draggable('control_dropdown',{revert:true});
   new Draggable('control_checkbox',{revert:true});
   new Draggable('control_radio',{revert:true});
   //new Draggable('control_datetimepicker',{revert:true});
   //new Draggable('control_fileupload',{revert:true});
   //new Draggable('control_button',{revert:true});
   
  //Power Tools
   //new Draggable('control_captcha',{revert:true});
   //new Draggable('control_rating',{revert:true});
   //new Draggable('control_passwordbox',{revert:true});
   //new Draggable('control_collapse',{revert:true});
   //new Draggable('control_autocomp',{revert:true});
   //new Draggable('control_birthdate',{revert:true});
   //new Draggable('control_html',{revert:true});
   //new Draggable('control_image',{revert:true});
  
  //Payment Tools
   //new Draggable('control_paypal',{revert:true});
   //new Draggable('control_googleco',{revert:true});
   //new Draggable('control_worldpay',{revert:true});
   //new Draggable('control_clickbank',{revert:true});
   //new Draggable('control_2co',{revert:true});
   //new Draggable('control_onebip',{revert:true});
   
    
    Droppables.add('thelist', {
        accept: 'controls',
        hoverclass: 'hoverclass',
        onDrop: function(element, ethelist, e){
           addNewQuestion(element.id, undefined, Event.pointerY(e), undefined, true);
        }
    }
);
  // ]]>
 </script>
 <script type="text/javascript">
  Sortable.create('thelist');
  //new DragScrollable('scrolls'); //For Scrolling
 <?   
  if ($_GET['mode'] == "source"){
   print "formSource();\n";
   print "$('toolss').style.display = 'none'";
  }
 ?>
</script>

	
<!-- #Save Form Button, form submission: -->
<br /><br />
<a href="javascript: void(0);" onclick="javascript:formSaveDialog(); setTimeout(function(){document.formform.submit();}, 1000);" style="color:#09c;font-size:16px;text-decoration:none;font-weight:600;	font-family:verdana, arial, helvetica, sans-serif;">
Save Action Page</a>&nbsp;or&nbsp;<a href="/create/" style="color:#09c;font-size:16px;text-decoration:none;font-weight:600;	font-family:verdana, arial, helvetica, sans-serif;">Cancel</a>

<!-- <a href="javascript: void(0);" onclick="document.actionform.submit(); return false;">
Save Action Page</a>&nbsp;or&nbsp;<a href="/create/">Cancel</a>	-->
<?php 
//if there's a action page variable being passed, build hidden form element
//to transfer with form:
if($actionid) echo '<input type="hidden" name="actionid" value="' . $actionid . '" />';
echo '<input type="hidden" name="origname" id="origname" value="' . $name . '" />';
?>
<input type="hidden" name="submitted" value="TRUE" />
</form>

	 
<?php //Include the HTML footer
include ('/home/rneel/web/htdocs/includes/footer.html');
?>
