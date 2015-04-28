//Onload, get the url, parse off the auth code and put it in the textbox
//then submit the form 
jQuery(document).ready(function() {
	var loc = document.location.search;
	var key = loc.substr(loc.indexOf("&code=") + 6, loc.length);//6 removes the code= part
	
	if (loc.indexOf("settings-updated") == -1 && key.indexOf("calendar_auth") == -1) {//if we haven't submitted it and there is a url from google
		jQuery("#access_token").val(key);
		jQuery("#submitting").attr("style", "");
		document.getElementById("auth_form").submit.click();//clicks the submit button...should be a better way
	}
});