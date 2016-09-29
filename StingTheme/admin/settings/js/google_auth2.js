//Onload, get the url, parse off the auth code and put it in the textbox
//then submit the form 
jQuery(document).ready(function () {
	submitKey();
});

function submitKey() {
	var loc = document.location.search;
	if (document.referrer.indexOf("wrur") != -1 || document.referrer.indexOf("128.151") != -1) {
		return;
	}
	
	var key = loc.substr(loc.indexOf("&code=") + 6, loc.length);//6 removes the code= part
	key = key.replace("%2F", "/");
	//Doesn't work: if (loc.indexOf("settings-updated") == -1 && key.indexOf("calendar_auth") == -1) {//if we haven't submitted it and there is a url from google
	var doubleSubmitTest = null;
	try {
		doubleSubmitTest = jQuery("#setting-error-settings_updated")[0];
	} catch (ex) {}
	if (doubleSubmitTest == null || !doubleSubmitTest.classList.contains("updated")) {
		jQuery("#access_token").val(key);
		jQuery("#submitting").attr("style", "");
		alert("Submitting the key " + key);
		document.getElementById("auth_form").submit.click();//clicks the submit button...should be a better way
	}
}