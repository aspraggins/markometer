// Manage & Create Action Pages JavaScript:

/*
// Ajax Initialize:
window.onload = init;

function init() {
	var ajax = getXMLHttpRequestObject();
}

// Ajax code to hit MySQL datebase to look for availability of campaign name:
function checkActionPageName() {
	var ajax = getXMLHttpRequestObject();
	var cname = document.getElementById('actionname-results').value;
	var requestURL = '/inc/check-action-page-name.php?name='+cname;
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
			var results = document.getElementById('actionname-results');
			results.innerHTML = ajax.responseText;
			results.style.display = 'block';
		} else {
			document.getElementById('autoform').submit();
		}
	}
}
*/

// Use Dom to show Thank you editor and hide redirect text field:
function showThankyou() {
	document.getElementById('thankyoudiv').style.display = 'block'; 
	document.getElementById('redirectdiv').style.display = 'none'; 	
}

function showRedirect() {
	document.getElementById('thankyoudiv').style.display = 'none'; 
	document.getElementById('redirectdiv').style.display = 'block'; 	
}

