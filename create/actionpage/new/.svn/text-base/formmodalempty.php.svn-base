<html>
<head>

<!-- <script type="text/javascript" 
	        src="/js/prototype-1.6.1.js"></script>
<script type="text/javascript"
	        src="/js/scriptaculous.js"></script> -->

<title>Draggables Elements</title>
</head>
<body>
<p><b>Drag Fields Over to Build Your Form!</b></p>	
<table border = "0"><tr><td>Name of Form:</td><td><input type="text" width="250px" id="formname"></td><td>
<input type="button" value="Save Form" onclick="itemize();" /></td></tr></table>
<table width="800px" border="1" cellpadding="0" cellspacing="0" id="allTable" style="height:90%;">
<tr><td valign="top" width="60%" id="formTable">
<ul id="thelist" style="padding: 10px; margin-left: 0;"><br /></ul>
<p><div align="center"><input type="button" value="submit" id="submitbutton">
&nbsp;<input type="radio" name="fieldprops" value="0" valign="top" onclick="selectedfield='0';showfieldprops('0');" />
	
</div></p>
</td>

<td width="16%" valign="top" align="center">
<p><b>Common Fields</b></p>
<div style="font-size:0.8em">
<div id="user_text" style="position: relative;" class="selections">
Text
</div>	
	
<div id="first_name" style="position: relative;" class="selections">
First Name
</div>

<div id="last_name" style="position: relative;" class="selections">
Last Name
</div>

<div id="email" style="position: relative;" class="selections">
Email
</div>

<div id="address" style="position: relative;" class="selections">
Address
</div>

<div id="city" style="position: relative;" class="selections">
City
</div>

<div id="state" style="position: relative;" class="selections">
State
</div>

<div id="zip" style="position: relative;" class="selections">
Zip
</div>

<div id="country" style="position: relative;" class="selections">
Country
</div>

<div id="title" style="position: relative;" class="selections">
Title
</div>

<div id="company" style="position: relative;" class="selections">
Company
</div>

<div id="telephone" style="position: relative;" class="selections">
Telephone
</div>

<div id="inquiry" style="position: relative;" class="selections">
Inquiry
</div>
<br /></div>

<p><b>Custom Fields</b></p>
<div style="font-size:0.8em">
<div id="custom_textbox" style="position: relative;" class="selections">
Custom TextBox
</div>	

<div id="custom_textarea" style="position: relative;" class="selections">
Custom Text Area
</div>	

<div id="custom_dropdown" style="position: relative;" class="selections">
Custom Dropdown
</div>	

<div id="custom_checkbox" style="position: relative;" class="selections">
Custom CheckBox
</div>	

<div id="custom_radiobutton" style="position: relative;" class="selections">
Custom Radio Button
</div>	

</div>
</td>
<td width="24%" valign="top" align="center">
<b>Form Attributes</b><br />
<div style="font-size:0.7em">
Font Size:<br />
<select name="fontsize" id="fontsize" onchange="changefontsize();">
<option>16px</option>
<option>18px</option>
<option>20px</option>
<option>22px</option>
<option>24px</option>
</select><br /><br />
Font:<br />
<select name="fontFamily" id="fontFamily" onchange="javascript: var d = document.getElementById('fontFamily'); var styles = {fontFamily: d.value}; var x = document.getElementById('thelist'); x.setStyle(styles);">
<option>Times New Roman</option>
<option>Arial</option>
<option>Verdana</option>
<option>Sans Serif</option>
</select><br /><br />
Font Color:<br />
<select name="color" id="color" onchange="changefontcolor();">
<option>Black</option>
<option>Blue</option>
<option>Red</option>
<option>Brown</option>
<option>White</option>
</select><br /><br />
Bkgrnd Color:<br />
<select name="bckcolor" id="bckcolor" onchange="javascript: var d = document.getElementById('bckcolor'); var styles = {backgroundColor: d.value}; var x = document.getElementById('thelist'); x.setStyle(styles);">
<option>#EFEFEF</option>
<option>Black</option>
<option>Blue</option>
<option>Red</option>
<option>Brown</option>
<option>White</option>
</select></div>

<hr>
<div id="fieldattributes" style="display:none;">
<b>Field Attributes</b><br />
<div style="font-size:0.7em">
<!-- Label -->	
Label:<br />	
<input type="text" name="labeltext" size="15" id="labeltext" onkeydown="if(event.keyCode==13) labelchanged(value,selectedfield);" />
<br /><br />

<!-- Field Required Selector: -->
Required:<br />
<select name="required" id="required" onchange="requiredchanged(value,selectedfield);">
<option>No</option>
<option>Yes</option>
</select><br /><br />

<!-- Field Size Selector: -->
Size:<br />
<select name="size" id="size" onchange="sizechanged(value,selectedfield);">
<option>50</option>	
<option>100</option>
<option selected="selected">150</option>
<option>200</option>
</select><br /><br />

<!-- Field Maximum Size Selector: -->
Max Size:<br />
<select name="maxsize" id="maxsize" onchange="maxsizechanged(value,selectedfield);">
<option>10</option>	
<option selected="selected">30</option>
<option>50</option>
<option>70</option>
<option>90</option>
</select><br /><br />

<!-- Textarea Number of Rows Selector: -->
Height:<br />
<select name="numrows" id="numrows" onchange="numrowschanged(value,selectedfield);">
<option>40</option>	
<option selected="selected">60</option>
<option>80</option>
</select><br /><br />

<!-- Options -->	
Options (sep. by comma):<br />	
<input type="text" name="useroptions" size="15" id="useroptions" onkeydown="if(event.keyCode==13) optionschanged(value,selectedfield);" />

</div></div>
</td>	
</tr>
</table>
      
<script type="text/javascript">
	var fieldrequired = new Array();
	var fieldtext = new Array();
	var fieldsize = new Array();
	var fieldmaxsize = new Array();
	var fieldrows = new Array();
	var fieldtype = new Array();
	var fieldoptions = new Array();
	
	//Set value for submit button label:
	fieldtext[0] = "submit";
	
	//functions to detect when user has selected different field options:
	labelchanged = function(value,selectedfd) {
		fieldtext[selectedfd] = value;
		if(selectedfd!=0) {
			document.getElementById('utext_'+selectedfd).innerHTML = value;
		}else{
			document.getElementById('submitbutton').value = value;
		}	
	}
	
	requiredchanged = function(value,selectedfd) {
		fieldrequired[selectedfd] = value;
	}

	sizechanged = function(value,selectedfd) {
		fieldsize[selectedfd] = value;
		var styles = {width: value}; 
		if(fieldtype[selectedfd].substr(0,4) == 'TEXT') {
			tempstring = 'text_';
		}else{
			tempstring = 'customtext_';
		}
		var x = document.getElementById(tempstring+selectedfd); 
		x.setStyle(styles);		
	}

	maxsizechanged = function(value,selectedfd) {
		fieldmaxsize[selectedfd] = value;
	}
	
	numrowschanged = function(value,selectedfd) {
		fieldrows[selectedfd] = value;
		var styles = {height: value}; 
		var x = document.getElementById('text_'+selectedfd); 
		x.setStyle(styles);		
	}
	
	optionschanged = function(value,selectedfd) {
		fieldoptions[selectedfd] = value;
		//parse out value in options field by commas, insert as options in pulldown, checkbox, radio:
		var optionsarry = fieldoptions[selectedfd].split(',');		
		if(fieldtype[selectedfd] == "CUSTOMDROPDOWN") {
			var obj = document.getElementById('cdropdown_'+selectedfd);
			obj.options.length = 0;
			for ( var i in optionsarry ) {
				obj.options[i] = new Option(optionsarry[i], optionsarry[i]);
			}	
		}else if(fieldtype[selectedfd] == "CUSTOMCHECKBOX") {
			checkboxcode = '';
			for ( i=0; i<=optionsarry.length-1; i++ ) {
				if(i>0) checkboxcode += '<br />';
				checkboxcode += '<input type="checkbox" name="ccheckbox_' + selectedfd + '" id="ccheckbox_' + selectedfd + '" value=' + optionsarry[i] + '" />' + optionsarry[i]; 
			}	
			document.getElementById('checkboxdiv_'+selectedfd).innerHTML = checkboxcode;
		}else if(fieldtype[selectedfd] == "CUSTOMRADIO") {
			radiocode = '';
			for (i=0; i<=optionsarry.length-1; i++) {
				if(i>0) radiocode += '<br />';
				radiocode += '<input type="radio" name="cradio_' + selectedfd + '" id="cradio_' + selectedfd + '" value=' + optionsarry[i] + '" />' + optionsarry[i]; 				
			}
		document.getElementById('radiodiv_'+selectedfd).innerHTML = radiocode;	
		}
	}

	var itemnum = 1;
	var selectedfield = 0;

	new Draggable('user_text',{revert:true});
	new Draggable('first_name',{revert:true});
	new Draggable('last_name',{revert:true});
	new Draggable('email',{revert:true});
	new Draggable('address',{revert:true});
	new Draggable('city',{revert:true});
	new Draggable('state',{revert:true});
	new Draggable('zip',{revert:true});
	new Draggable('country',{revert:true});
	new Draggable('title',{revert:true});
	new Draggable('company',{revert:true})
	new Draggable('telephone',{revert:true});
	new Draggable('inquiry',{revert:true});
	new Draggable('custom_textbox',{revert:true});
	new Draggable('custom_textarea',{revert:true});
	new Draggable('custom_dropdown',{revert:true});	
	new Draggable('custom_checkbox',{revert:true});	
	new Draggable('custom_radiobutton',{revert:true});		

Droppables.add(
 'thelist',
 {
    hoverclass: 'hoverActive',
	accept: 'selections',
    onDrop: moveItem
 }
);

function moveItem(){
	alert("stuff");

}
</script>
</body></html>