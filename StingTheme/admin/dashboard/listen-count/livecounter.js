function loadListenerCount() {
	var data = {
		'action': 'listener_count',
	};
	jQuery.post(ajaxurl, data, function(response) {
		//alert('Got this from the server: ' + response);
		var listenCountTag = jQuery("#sting-listen-count");
		listenCountTag[0].innerHTML = response;//I don't know why the 0, but it is necessary
	});
}
jQuery(document).ready(function() {
	loadListenerCount();
	window.setInterval("loadListenerCount();", 10000);
});