var blog = "";
jQuery(document).ready(function($) {
	$('#upload_main_background_button').click(function() {
		tb_show('Upload an Image', 'media-upload.php?referer=ROA_Options&type=image&TB_iframe=true&post_id=0', false);
		blog = "blog";
		return false;
	});
});

window.send_to_editor = function(html) {
	var image_url = jQuery('img',html).attr('src');
	jQuery('#main_image_preview').attr('src', image_url);
	jQuery('#url_box_main').val(image_url);
	tb_remove();
}