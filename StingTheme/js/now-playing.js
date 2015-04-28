var currentSongVisible = false;
function getCurrentShow() {
	var data = {
		'action': 'current_show',
	};
	$.post(adminAjaxUrl, data, function(response) {
		//alert('Got this from the server: ' + response);
		//console.log(response);
		var parsed = JSON.parse(response);
		//console.log(parsed);
		var currentShowTags = $(".current-show");
		var len = currentShowTags.length;
		for (var i = 0; i < len; i++) {
			currentShowTags[i].innerHTML = parsed.showTitle;
		}
		
		var toDisplay = "";
		//if (response.show-title != "The Sting") { //only display current song data if the show is actually playing. "The Sting" means there is no show. Should be done with wp_localize_script
			 if (parsed.title != "") {
				 toDisplay += parsed.title;
				 
				 if (parsed.artist != "") {
					 toDisplay += " by ";
					 toDisplay += parsed.artist;
				 }
				 if (!currentSongVisible) {
					$(".now-playing-container").fadeIn("fast");
					currentSongVisible = !currentSongVisible;
				 }
			 } else {
				 if (currentSongVisible) {
					$(".now-playing-container").fadeOut("fast");
					currentSongVisible = !currentSongVisible;
				 }
			 }
		//}
		var nowPlayingTags = $(".now-playing-content");
		for (var i = 0; i < nowPlayingTags.length; i++) {
			nowPlayingTags[i].innerHTML = toDisplay;
		}
	});
}
$(document).ready(function() {
	getCurrentShow();
	window.setInterval("getCurrentShow();", 20000);
});