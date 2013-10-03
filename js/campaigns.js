// Create & Manage Campaigns JavaScript

/*

//Ajax code to hit MySQL datebase to look for availability of campaign name:
function checkCampaignName(name, field) {
	//Firstly check field to see if anything is there:
	if(name.length<2) { 
		document.getElementById('nameresults').innerHTML='<div class="error"><p>Please type in up to 200 characters for your campaign name!</p></div>';
		return false;
	}

	var ajax = getXMLHttpRequestObject();
	//var cname = document.getElementById('campaignname').value;
	var cname = name;
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
			var results = document.getElementById('nameresults');
			results.innerHTML = ajax.responseText;
			results.style.display = 'block';
		} else {
			//document.getElementById('autoform').submit();
		}
	}
}
//Validation functions for form:
function validateAll() {
	if(checkGoal(document.campform.goal.value) == false) return false;
	if(checkName(document.campform.campaignname.value) == false) return false;	
	if(checkSubject(document.campform.subject.value) == false) return false;
	if(checkCost(document.campform.cost.value) == false) return false;	
	if(checkActualCost(document.campform.actualcost.value) == false) return false;	

	return true;
}

function checkGoal(text) {
	if(text.length<2) { 
		document.getElementById('goalresults').innerHTML='<div class="error"><p>Please type in up to 200 characters to describe the goal of your campaign!</p></div>';
	} else {
		document.getElementById('goalresults').innerHTML='<div class="error"><p>Okay!</p></div>';		
	}
}
function checkSubject(text) {
	if(text.length<2) { 
		document.getElementById('subjectresults').innerHTML='<div class="error"><p>Please type in up to 75 characters to describe your campaign subject!</p></div>';
	} else {
		document.getElementById('subjectresults').innerHTML='<div class="error"><p>Okay!</p></div>';		
	}
}
function checkCost(text) {
	if(text.length<1) { 
		document.getElementById('costresults').innerHTML='<div class="error"><p>Please type in the budgeted cost of your campaign!</p></div>';
		return false;
	} else if(!isNumeric(text)) {
		document.getElementById('costresults').innerHTML='<div class="error"><p>Please type in only numbers!</p></div>';
		return false;
	} else {
		document.getElementById('costresults').innerHTML='<div class="error"><p>Okay!</p></div>';		
	}
}

// Only Used in Manage Campaign:
function checkActualCost(text) {
	if(text.length<1) { 
		document.getElementById('actualcostresults').innerHTML='<div class="error"><p>Please type in the budgeted cost of your campaign!</p></div>';
		return false;
	} else if(!isNumeric(text)) {
		document.getElementById('actualcostresults').innerHTML='<div class="error"><p>Please type in only numbers!</p></div>';
		return false;
	} else {
		document.getElementById('actualcostresults').innerHTML='<div class="error"><p>Okay!</p></div>';		
	}
}

function isNumeric(sText) {
	var ValidChars = "0123456789.";
	var IsNumber=true;
	var Char;

	for (i = 0; i < sText.length && IsNumber == true; i++) { 
		Char = sText.charAt(i); 
		if (ValidChars.indexOf(Char) == -1) {
			IsNumber = false;
		}
	}
	return IsNumber;
}

*/

//Figure out/display how many days in user's campaign:
function checkDuration() {
	//Get 1 day in milliseconds
	var one_day=1000*60*60*24;
	var begindate;
	//convert form contents to variable, calculate difference:
	if (document.getElementById('beginDate').value=='Today') {
		begindate = new Date();
	} else {
		begindate = new Date(document.getElementById('beginDate').value);
	}
	var enddate = new Date(document.getElementById('endDate').value);	
	var difference = Math.ceil((enddate.getTime()-begindate.getTime())/(one_day));

	//Dom object for showing date difference:
	var campaignduration = document.getElementById('campaignduration');
	
	if (difference>0) {
		difference = "<strong>(Total: " + difference;
		difference += " days)</strong>";
		//display
		campaignduration.innerHTML = difference;
	} else if (difference<1) {
		campaignduration.innerHTML = '<strong>Please enter valid begin and end dates!</strong>';
	} else {
		campaignduration.innerHTML = '<strong>Open-ended campaign</strong>';
	}
}
