var currentSongVisible = false;
function getCurrentShow() {
	var data = {
		'action': 'current_show',
	};
	$.post(ajaxurl, data, function(response) {
		//alert('Got this from the server: ' + response);
		//console.log(response);
		var parsed = JSON.parse(response);
		//console.log(parsed);
		var currentShowTags = $(".current-show");
		var len = currentShowTags.length;
		for (var i = 0; i < len; i++) {
			if (parsed.showTitle != "The Sting") {
				currentShowTags[i].innerHTML = "<a href="+parsed.showURL+">" + parsed.showTitle +" </a>";
			} else {
				currentShowTags[i].innerHTML = parsed.showTitle;
			}
		}
		
		var toDisplay = "";
		if (parsed.showTitle != "The Sting") { //only display current song data if the show is actually playing. "The Sting" means there is no show. Should be done with wp_localize_script
			 if (parsed.title != "") {
				title = parsed.title;
				while (title.indexOf("\\") != -1) {//loops through and removes all instances of backslashes in the title
					title = title.substr(0, title.indexOf("\\")) + title.substr(title.indexOf("\\") + 1, title.length);
				}
				 
				 toDisplay += title;
				 
				 if (parsed.artist != "") {
					 artist = parsed.artist;
					while (artist.indexOf("\\") != -1) {//loops through and removes all instances of backslashes in the title
						artist = artist.substr(0, artist.indexOf("\\")) + artist.substr(artist.indexOf("\\") + 1, artist.length);
					}
					 toDisplay += " by ";
					 toDisplay += artist;
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
		} else {
			toDisplay = '';
			$(".now-playing-container").fadeOut("fast");
			currentSongVisible == false;
		}
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