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
	if (streamMedia.paused) {
		stream.play();
		saveState(true);
	} else {
		stream.pause();
		saveState(false);
	}
}

//handle auto-start if it was playing
$(document).ready(function() {
	var state = readState();
	if (state == 'true') {
		stream.play();
	}
});