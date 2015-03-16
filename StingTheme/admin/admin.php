<?php
require_once 'services-admin.php';
//setup the parent page, then the child pages
$sting_admin_page_name = 'sting_admin';
function register_sting_admin_page() {
	global $sting_admin_page_name;
	add_menu_page( 'Sting Admin', 'Sting', 'edit_theme_options', $sting_admin_page_name, 'sting_admin_page', 'dashicons-admin-generic', '90' );
	add_submenu_page($sting_admin_page_name, 'Sting Admin', 'Admin', 'edit_theme_options', $sting_admin_page_name);
}
add_action( 'admin_menu', 'register_sting_admin_page' );
add_action('admin_menu', 'create_services_options_page');
//setup the parent page, then the child pages
function sting_setup_admin_options() {
	global $sting_admin_page_name;
	register_setting('sting_admin_options_group', 'sting_admin_options', 'sting_admin_process_input');
	add_settings_section('admin_site_settings', 'Site Settings', 'setup_admin_content_section', $sting_admin_page_name);
	add_settings_field('homepage_cat_input', 'Category of Posts to show on the Homepage:', 'homepage_cat_input_box', $sting_admin_page_name, 'admin_site_settings');
	add_settings_field('schedule_num_shows', 'Number of shows for the schedule page to request:', 'schedule_num_shows_input_box', $sting_admin_page_name, 'admin_site_settings');
	add_settings_field('homepage_slider_id', 'Slider to show on the homepage:', 'homepage_slider_id_input_box', $sting_admin_page_name, 'admin_site_settings');
}
add_action('admin_init', 'sting_setup_admin_options');
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
function schedule_num_shows_input_box() {
	global $admin_options;
	echo "<input name='sting_admin_options[schedule_num_shows_input_box]' type='text' value='{$admin_options['schedule_num_shows_input_box']}' />";
}
function homepage_slider_id_input_box() {
	global $admin_options;
	$sliders = get_posts(array('posts_per_page' => 100, 'post_type' => 'easingslider'));
	echo '<select id="sting_admin_options[homepage_slider_id_input_box]" name="sting_admin_options[homepage_slider_id_input_box]">';
	$selected;
	foreach ($sliders as $slider) {
		if ($slider -> ID == intval($admin_options['homepage_slider_id_input_box'])) {
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
		$result = $value;
		if (stripos('num', $key) != 0 || stripos('cat', $key) != 0 || stripos('id', $key) != 0) {
			if (intval($value) <= 0) {
				$input[$key] = 100;
				add_settings_error($key, 'type-error', $key.' must be a positive integer value greater than 0.');
			}
		}
	}
	return $input;
}
?>