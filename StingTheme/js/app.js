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
		$("#quick-chat-dropdown").fadeOut("slow");
		$("#chat-button a")[0].innerHTML = "Chat with the DJ";
	} else {
		$(".quick-chat-loading").attr("style", "display: none;");
		$("#quick-chat-dropdown").fadeIn("slow", function() {
			$(".quick-chat-history-container").scrollTop($(".quick-chat-history-container").get()[0].scrollHeight);
		});
		$("#chat-button a")[0].innerHTML = "Hide Chat";
	}
	chatVisible = !chatVisible;
}
function showNowPlaying() {
	console.log("Showing Now Playing");
	$("#nowPlaying").fadeIn("slow");
}
function hideNowPlaying() {
	console.log("Hiding Now Playing");
	$("#nowPlaying").fadeOut("slow");
}
$(document).ready(function() {
	$("#menu-bar").mouseleave(function() {
		hideNowPlaying();
	});
});