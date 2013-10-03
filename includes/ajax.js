//<!-- Javascript for Ajax object setup
function getXMLHttpRequestObject() {
  var ajax = false;
  if (window.XMLHttpRequest) {
    ajax = new XMLHttpRequest();
  } 
  else if (window.ActiveXObject) {
    try {
      ajax = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {  
	    ajax = new ActiveXObject("Microsoft.XMLHTTP");
	  } catch (e) {}
    }
  }
  return ajax;
}
