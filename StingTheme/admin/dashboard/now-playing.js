function submitForm() {
	var formElements = jQuery(".now-playing-form");
	var data = new Array(formElements.length);
	for (var i = 0; i < formElements.length; i++) {
		if (formElements[i].type != "hidden") {
			var key = formElements[i].name;
			var value = formElements[i].value;
			data[i] = new Array(key, value);
		}
	}
	console.log(data);
	
	var dataForAjax = jQuery.extend({}, data);
	console.log(dataForAjax);
	
	var ajaxData = {
		'action': 'now_playing',
		'data': dataForAjax,
		'contentType': 'application/json',
		'_wpnonce' : jQuery("#_wpnonce")[0].value,
		'_wp_http_referer' : jQuery("[name = _wp_http_referer]")[0].value,
	};
	jQuery.post(ajaxurl, ajaxData, function(response) {
		//alert('Got this from the server: ' + response);
		var reply = JSON.parse(response);
		console.log(reply);
		if (reply.success) {
			for (var i = 0; i < formElements.length; i++) {
				formElements[i].value="";
			}
			jQuery("#title-field")[0].innerHTML = reply.title;
			if (reply.artist != "") {
				jQuery("#title-field")[0].innerHTML += " by ";
				jQuery("#artist-field")[0].innerHTML = reply.artist;
			} else {
				jQuery("#artist-field")[0].innerHTML = "";
			}
		}
	});
	return data;
}

jQuery(document).ready(function() {
	getCurrentShow();
	window.setInterval("getCurrentShow();", 10000);
});

var currentSongVisible = false;
function getCurrentShow() {
	var data = {
		'action': 'current_show',
	};
	jQuery.post(ajaxurl, data, function(response) {
		//alert('Got this from the server: ' + response);
		console.log(response);
		var parsed = JSON.parse(response);
		console.log(parsed);
		var currentShowTags = jQuery(".current-show");
		var len = currentShowTags.length;
		for (var i = 0; i < len; i++) {
			currentShowTags[i].innerHTML = parsed.showTitle;
		}
		
		//if (response.show-title != "The Sting") { //only display current song data if the show is actually playing. "The Sting" means there is no show. Should be done with wp_localize_script
			 if (parsed.title != "") {
				 jQuery("#title-field")[0].innerHTML = parsed.title;
				 
				 if (parsed.artist != "") {
					 jQuery("#artist-field")[0].innerHTML = " by " + parsed.artist;
				 }
			 } else {
				 jQuery("#title-field")[0].innerHTML = "Nothing Playing Right Now";
				 jQuery("#artist-field")[0].innerHTML = "";
			 }
		//}
	});
}

function checkSubmitForm(event) {
	if (event.key == "Enter") {
		submitForm();
	}
}