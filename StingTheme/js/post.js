var page = 1;

// on document ready
$(document).ready(function() {
  // get the data from the div
  //data = JSON.parse($('#post-json').html().replace("\\/", "\\\\/"));
  // add the posts
  //addPostsFromJSON(data);
  // window scroll event
  $(window).scroll(function() {
    // if scroll to the bottom
    if($(window).scrollTop() >= $(document).height() - $(window).height()) {
      page++;
      $.get("page/"+page).done(function(data) {
        console.log('data ' + page);
      }).fail(function(data) {
        console.log('error');
      });
    }
  });
});

// add more posts to feed
function addPostsFromJSON(data) {
  var column;
  console.log(data);
  for(i=0; i<3; i++) {
    console.log('adding data?? '+data[0]);
    column = $('<div class="row column small-12 medium-12 large-6></div>');
    
  }
}
