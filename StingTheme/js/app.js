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
