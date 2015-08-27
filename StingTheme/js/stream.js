//handle remembering if the stream is or is not playing
function saveState(state) {
	$.cookie('sting-stream-status', state, {path: '/'});
}
function readState() {
	return $.cookie('sting-stream-status');
}

//handle playing/pausing the stream
//var stream1 = document.getElementById("stream-player");
var streamMedia;
var stream = new MediaElementPlayer('#stream-player', {success: function(media, node, player) {
streamMedia = media;
}
});
function toggleStream() {
	if (streamMedia != null) {
		if (streamMedia.paused) {
				$("#mobile-play-pause-inner").attr('class', 'dashicons dashicons-controls-pause').attr('style', "");
				$("#desktop-play-pause-inner").attr('class', 'dashicons dashicons-controls-pause').attr('style', "");
			stream.play();
			saveState(true);
		} else {
				$('#mobile-play-pause-inner').attr('class', 'dashicons dashicons-controls-play');
				$('#desktop-play-pause-inner').attr('class', 'dashicons dashicons-controls-play');
			stream.pause();
			saveState(false);
		}
	} else {
		alert('Sorry, the live stream of TheSting only works in Chrome on mobile devices.');
	}
}

//handle auto-start if it was playing
$(document).ready(function() {
	var state = readState();
	if (state == 'true') {
		toggleStream();
	}
});
//show and hide stream panel on mobile
function showMobileStream() {
	var panelClass = $('#mobile-listen-live-panel').attr('class');
	var idx = panelClass.lastIndexOf(' ');
	panelClass = panelClass.substr(0, idx);
	panelClass += ' show';
	$('#mobile-listen-live-panel').attr('class', panelClass);
	
	var buttonClass = $('#mobile-listen-live').attr('class');
	var idx = buttonClass.lastIndexOf(' ');
	buttonClass = buttonClass.substr(0, idx);
	buttonClass += ' hide';
	$('#mobile-listen-live').attr('class',buttonClass);
}
function hideMobileStream(event) {
	if (event.target.id != "mobile-play-pause-inner" && event.target.id != "mobile-play-pause-outer") {
		var panelClass = $('#mobile-listen-live-panel').attr('class');
		var idx = panelClass.lastIndexOf(' ');
		panelClass = panelClass.substr(0, idx);
		panelClass += ' hide';
		$('#mobile-listen-live-panel').attr('class', panelClass);
		
		var buttonClass = $('#mobile-listen-live').attr('class');
		var idx = buttonClass.lastIndexOf(' ');
		buttonClass = buttonClass.substr(0, idx);
		buttonClass += ' show';
		$('#mobile-listen-live').attr('class',buttonClass);
	}
}