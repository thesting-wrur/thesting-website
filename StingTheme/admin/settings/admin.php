<?php
require_once 'services-admin.php';
require_once 'calendar-admin.php';
require_once 'calendar-auth.php';
require_once 'calendar-push.php';
//setup the parent page, then the child pages
$sting_admin_page_name = 'sting_admin';
function register_sting_admin_page() {
	global $sting_admin_page_name;
	add_menu_page( 'Sting Admin', 'Sting', 'edit_theme_options', $sting_admin_page_name, 'sting_admin_page', 'dashicons-admin-generic', '81' );
	add_submenu_page($sting_admin_page_name, 'Sting Admin', 'Admin', 'edit_theme_options', $sting_admin_page_name);
}
add_action( 'admin_menu', 'register_sting_admin_page' );
add_action('admin_menu', 'create_sting_services_options_page');
add_action('admin_menu', 'create_calendar_admin_page');
add_action('admin_menu', 'create_calendar_auth_page');
//setup the parent page, then the child pages
function sting_setup_admin_options() {
	global $sting_admin_page_name;
	register_setting('sting_admin_options_group', 'sting_admin_options', 'sting_admin_process_input');
	add_settings_section('admin_site_settings', 'Site Settings', 'setup_admin_content_section', $sting_admin_page_name);
	add_settings_field('homepage_cat_input', 'Category of Posts to show on the Homepage:', 'homepage_cat_input_box', $sting_admin_page_name, 'admin_site_settings');
	add_settings_field('num_shows', 'Number of shows to request at a time (make this large):', 'num_shows_input_box', $sting_admin_page_name, 'admin_site_settings');
	add_settings_field('homepage_slider_id', 'Slider to show on the homepage:', 'homepage_slider_id_input_box', $sting_admin_page_name, 'admin_site_settings');
	add_settings_field('homepage_mobile_slider_id', 'Slider to show on the mobile homepage:', 'homepage_mobile_slider_id_input_box', $sting_admin_page_name, 'admin_site_settings');
	add_settings_field('sting_header_image', 'Default Header Image:', 'sting_header_image_input_box', $sting_admin_page_name, 'admin_site_settings');
	add_settings_field('sting_admin_message', 'Admin Message (to Public):', 'sting_admin_message_input_box', $sting_admin_page_name, 'admin_site_settings');
	add_settings_field('sting_admin_message_start', 'Admin Message Event Start:', 'sting_admin_message_start_input_box', $sting_admin_page_name, 'admin_site_settings');
	add_settings_field('sting_admin_message_end', 'Admin Message End:', 'sting_admin_message_end_input_box', $sting_admin_page_name, 'admin_site_settings');
}
add_action('admin_init', 'sting_setup_admin_options');
//Start setup for media uploader
function sting_setup_media_scripts($page) {
		global $sting_admin_page_name;
		$temp_page_name = 'toplevel_page_'.$sting_admin_page_name;
		if ($page == $temp_page_name) {
			wp_register_script('sting_media_upload', get_template_directory_uri().'/admin/settings/js/media_upload.js', array('jquery', 'media-upload', 'thickbox'));
			wp_enqueue_script('jquery');
			wp_enqueue_script('thickbox');
			wp_enqueue_script('media-upload');
			wp_enqueue_script('sting_media_upload');
			wp_enqueue_style('thickbox');
			wp_enqueue_script('sting-datetimepicker', get_template_directory_uri().'/admin/settings/datetimepicker-master/jquery.datetimepicker.js');
			wp_enqueue_style('jquery-ui', get_template_directory_uri().'/admin/settings/datetimepicker-master/jquery.datetimepicker.css');
			wp_enqueue_script('sting_datetime', get_template_directory_uri().'/admin/settings/js/datetime.js');
			wp_enqueue_media();
		}
}
add_action('admin_enqueue_scripts', 'sting_setup_media_scripts');
function sting_setup_thickbox() {
	global $pagenow;
	if ('media-upload.php' == $pagenow || 'async-upload.php' == $pagenow) {
		// Now we'll replace the 'Insert into Post Button' inside Thickbox
        add_filter( 'gettext', 'sting_rename_thickbox_button'  , 1, 3 );
	}
}
add_action('admin_init', 'sting_setup_thickbox');
function sting_modify_thickbox($form_fields, $post) {
	$form_fields['buttons'] = "<input type='submit' class='button' value='Select Image' />";
	return $form_fields;
}
add_filter('attachment_files_to_edit', 'sting_modify_thickbox');
function sting_rename_thickbox_button($translated_text, $text, $domain) {
	if ('Insert into Post' == $text) {
		$referer = strpos(wp_get_referer(), $sting_page_name);
		if ($referer != '') {
			return __('Upload Header image');
		}
	}
	return $translated_text;
}
//end setup for media uploader
//input boxes
$admin_options = get_option('sting_admin_options');
function homepage_cat_input_box() {
	global $admin_options;
	$args = array( 
			'name' => 'sting_admin_options[homepage_cat_input_box]', 
			'option_none_value' => '0', 
			'selected' => $admin_options['homepage_cat_input_box'],
			'orderby' => 'ID',
			'order' => 'ASC'
		);
	wp_dropdown_categories($args);
}
function num_shows_input_box() {
	global $admin_options;
	echo "<input name='sting_admin_options[num_shows_input_box]' type='text' value='{$admin_options['num_shows_input_box']}' />";
}
function homepage_slider_id_input_box() {
	generate_slider_box('homepage_slider_id_input_box');
}
function homepage_mobile_slider_id_input_box() {
	generate_slider_box('homepage_mobile_slider_id_input_box');
}
function generate_slider_box($box_name) {
	global $admin_options;
	$sliders = get_posts(array('posts_per_page' => 100, 'post_type' => 'easingslider'));
	echo '<select id="sting_admin_options['.$box_name.']" name="sting_admin_options['.$box_name.']">';
	$selected;
	foreach ($sliders as $slider) {
		if ($slider -> ID == intval($admin_options[$box_name])) {
			$selected = true;
		} else {
			$selected = false;
		}
		echo '<option value='.$slider-> ID .($selected == true ? ' selected="selected"' : '').'>';
		echo $slider -> post_title;
		echo '</option>';
	}
	echo '</select>';
}
function sting_header_image_input_box() {
	image_input_box('sting_header_image_input_box', 'main');
}
function image_input_box($box, $id) {
	global $admin_options;
	echo "Preview of Current Image:";
	echo "<img src='".$admin_options[$box]."' id='".$id."_image_preview' width='240' style='vertical-align: middle;'/>";
	echo "<br /><br />";
	echo "Select a new image:";
	echo "<input name='sting_admin_options[".$box."]' id='url_box_".$id."' type='text' value='{$admin_options[$box]}' />";
	echo "<input id='upload_".$id."_background_button' type='button' class='button' value='Select or Upload Image' />";
}

function sting_admin_message_input_box() {
	global $admin_options;
	echo "<input name='sting_admin_options[sting_admin_message_input_box]' type='text' value='{$admin_options['sting_admin_message_input_box']}' />";
}
function sting_admin_message_start_input_box() {
	global $admin_options;
	echo "<input name='sting_admin_options[sting_admin_message_start_input_box]' class='sting-datetime' type='text' value='{$admin_options['sting_admin_message_start_input_box']}' />";
}
function sting_admin_message_end_input_box() {
	global $admin_options;
	echo "<input name='sting_admin_options[sting_admin_message_end_input_box]' class='sting-datetime' type='text' value='{$admin_options['sting_admin_message_end_input_box']}' />";
}

function setup_admin_content_section() {
}
function sting_admin_page() {
	global $sting_admin_page_name;
	?>
	<div class="wrap">
		<h2>Sting Admin Page</h2>
		<form method="post" action="options.php" enctype="multipart/form-data">
			<?php
				settings_errors();
				settings_fields('sting_admin_options_group');
				do_settings_sections($sting_admin_page_name);
				submit_button();
			?>
		</form>
	</div>
<?php
}
function sting_admin_process_input($input) {
	$complete_success = true;
	foreach( $input as $key => $value) {
		$processed = $value;
		//error_log('Key: '.$key.' Value: '.$value);
		if (stripos($key, 'um_shows') != false || stripos($key, 'cat') != false || stripos($key, 'id') != false) {//num_shows_input_box ==> search for um_shows because php is interpreting 0 as false (num is the beginning of the key)
			error_log('Key: '.$key.' Value: '.$value);
			if (intval($value) <= 0) {
				$processed = 100;
				add_settings_error($key, 'type-error', $key.' must be a positive integer value greater than 0.');
			}
		} else if (stripos($key, 'admin_message_start') != false || stripos($key, 'admin_message_end') != false) {
			if (strtotime($value) == FALSE) {
				$processed = '';
				add_settings_error($key, 'type-error', $key.' must be a valid date.');
			}
		} else if (stripos($key, 'admin_message_input') != false) {
			$processed = wp_strip_all_tags($value);
			if (stripos($value, 'scheduled') == false) {
				add_settings_error($key, 'type-error', 'Is the maintenance scheduled?');
			}
		}
		$result[$key] = $processed;
	}
	return $result;
}
?>