var blog = "";
jQuery(document).ready(function($) {
	/*$('#upload_main_background_button').click(function() {
		tb_show('Upload an Image', 'media-upload.php?referer=ROA_Options&type=image&TB_iframe=true&post_id=0', false);
		blog = "blog";
		return false;
	});*/
});

window.send_to_editor = function(html) {
	var image_url = jQuery('img',html).attr('src');
	jQuery('#main_image_preview').attr('src', image_url);
	jQuery('#url_box_main').val(image_url);
	tb_remove();
}
//https://www.gavick.com/blog/use-wordpress-media-manager-plugintheme
var sting_media_init = function(box_selector, button_selector) {
	var clicked_button = false;
	wp.media.frames.sting_frame = wp.media({
				title: 'Select Image',
				multiple: false,
				library: {type:'image'},
				button: {text:'Use as Default Header Image'}
	});
	
	jQuery(button_selector).click(function (event) {
		event.preventDefault();
		var selected_img;
		
		clicked_button = jQuery(this);
		if (wp.media.frames.sting_frame) {
			wp.media.frames.sting_frame.open();
		}
	});
	var sting_media_set_image = function() {
		var selection = wp.media.frames.sting_frame.state().get('selection');
		if (!selection) {
			return;
		}
		box_selector.value = attachment.attributes.url;
	};
	wp.media.frames.sting_frame.on('select', sting_media_set_image);
}
jQuery(document).ready(function($) {
	sting_media_init('.url_box_main', 'upload_main_background_button');
});