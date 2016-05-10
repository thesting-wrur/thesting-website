var menuOpen = 0;

$(document).foundation();

$(document).ready(function() {
  $('#tab-bar-menu-button').add(".menu-item").add("#content-darken").click(function() {
    $("#content").stop();
    $("#menu").stop();
    if(menuOpen) {
      $("#content-darken").fadeOut(200);
      $("#menu").animate({left: "-200"}, 200);
      menuOpen = 0;
    } else {
      $("#content-darken").fadeIn(200);
      $("#menu").animate({left: "0"}, 200);
      menuOpen = 1;
    }
  });
});
var chatVisible = false;
function toggleChat() {
	if (chatVisible) {
		$("#quick-chat-dropdown").fadeOut(400);
		$("#chat-button a")[0].innerHTML = "Chat with the DJ";
	} else {
		$(".quick-chat-loading").attr("style", "display: none;");
		$("#quick-chat-dropdown").fadeIn(400, function() {
			$(".quick-chat-history-container").scrollTop($(".quick-chat-history-container").get()[0].scrollHeight);
		});
		$("#chat-button a")[0].innerHTML = "Hide Chat";
	}
	chatVisible = !chatVisible;
}

var hidingStarted = false;
var hidingTimeout = null;
function showNowPlaying() {
	console.log("Showing Now Playing");
	$("#nowPlaying").slideDown(400);
	$("#quick-chat-dropdown").animate({top: "120px"}, 400);//450ms
	clearTimeout(hidingTimeout);
	console.log("cleared timeout");
	hidingTimeout = null;
}
var startingToFade = false;
function hideNowPlaying() {
	console.log("Hiding Now Playing");
	startingToFade = true;
	$("#quick-chat-dropdown").animate({top: "60px"}, 400);//450ms
	$("#nowPlaying").slideUp(400, function() {
		startingToFade = false;
	});
	hidingStarted = false;
	hidingTimeout = null;
}
$(document).ready(function() {
	$("#menu-bar").mouseleave(function() {
		//hideNowPlaying();
		if (!hidingStarted) {
			hidingTimeout = setTimeout("hideNowPlaying()", 400);
			hidingStarted = true;
		}
	});
	$("#menu-bar").mouseenter(function() {
		clearTimeout(hidingTimeout);
		console.log("cleared timeout");
		hidingTimeout = null;
		hidingStarted = false;
		if (startingToFade) {
			$("#nowPlaying").slideDown(400);
			$("#quick-chat-dropdown").animate({top: "120px"});
		}
	})
});