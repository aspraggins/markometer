// Confirm Leaving Page and Losing Info:

var needToConfirm = true;

window.onbeforeunload = unloadPage;
function unloadPage() {
	if(needToConfirm) {
		return 'You may have unsaved changes to this page that will be lost.';
	}
}
