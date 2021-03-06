<?php
//sting
add_action('admin_init', 'sting_setup_services_options');
$sting_services_page_name = 'services_options';
function sting_setup_services_options() {
	global $sting_services_page_name;
	register_setting('sting_services_options_group', 'sting_services_options', 'sting_services_process_input');
	add_settings_section('content_settings', 'Page Content', 'setup_content_section', $sting_services_page_name);
	add_settings_field('s1_title', 'Service 1 Title:', 's1_title_box', $sting_services_page_name, 'content_settings');
	add_settings_field('s1_input', 'Service 1 Description:', 's1_input_box', $sting_services_page_name, 'content_settings');
	add_settings_field('s2_title', 'Service 2 Title:', 's2_title_box', $sting_services_page_name, 'content_settings');
	add_settings_field('s2_input', 'Service 2 Description:', 's2_input_box', $sting_services_page_name, 'content_settings');
	add_settings_field('s3_title', 'Service 3 Title:', 's3_title_box', $sting_services_page_name, 'content_settings');
	add_settings_field('s3_input', 'Service 3 Description:', 's3_input_box', $sting_services_page_name, 'content_settings');
}
function create_sting_services_options_page() {
	global $sting_services_page_name;
	global $sting_admin_page_name;
	add_submenu_page($sting_admin_page_name, 'Services', 'Services', 'sting_edit_services_page', $sting_services_page_name, 'create_services_options_page');
}
/* Image Sizes */
add_filter( 'image_size_names_choose', 'my_custom_sizes' );
function my_custom_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'sting-services-img' <= __( 'Services Image' ),
    ) );
}
function sting_setup_img_size() {
	add_image_size('sting-services-img', 300, 200, false);
}
add_action('after_setup_theme', 'sting_setup_img_size');
//input boxes
$services_options = get_option('sting_services_options');
function s1_title_box() {
	global $services_options;
	echo "<input name='sting_services_options[s1_title_box]' type='text' value='{$services_options['s1_title_box']}' />";
}
function s1_input_box() {
	generate_editor('s1');
}
function s2_title_box() {
	global $services_options;
	echo "<input name='sting_services_options[s2_title_box]' type='text' value='{$services_options['s2_title_box']}' />";
}
function s2_input_box() {
	generate_editor('s2');
}
function s3_title_box() {
	global $services_options;
	echo "<input name='sting_services_options[s3_title_box]' type='text' value='{$services_options['s3_title_box']}' />";
}
function s3_input_box() {
	generate_editor('s3');
}
function generate_editor($slug) {
	global $services_options;
	$content = $services_options[$slug.'_input_box'];
	$editor_id = $slug.'_editor';
	$args = array(
		'textarea_name' => 'sting_services_options['.$slug.'_input_box]',
		'textarea_rows' => '5',
	);
	wp_editor( $content, $editor_id, $args);
}
//end input boxes
function setup_content_section() {
	$query = new WP_Query('pagename=services');
	$posts_retrieved = $query -> posts;
	echo 'View the <a href="'.get_permalink($posts_retrieved[0] -> ID).'">Services Page</a>';
	echo '<br><br>If you add an image to the description, make sure to select the sting-services-img size for it';
}
function create_services_options_page() {
	global $sting_services_page_name;
	?>
	<div class="wrap">
		<h2>Services Page</h2>
		<form method="post" action="options.php" enctype="multipart/form-data">
			<?php
				settings_errors();
				settings_fields('sting_services_options_group');
				do_settings_sections($sting_services_page_name);
				submit_button();
			?>
		</form>
	</div>
<?php
}
/*Validate the input*/
function sting_services_process_input($input) {
	$output = array();
	$complete_success = true;
	foreach( $input as $key => $value) {
		$result = $value;
		$validated = wp_kses_post($result);
		$output[$key] = $validated;
		if ($validated != $result) {
			add_settings_error($key, 'type-error', $key.' cannot contain dangerous html tags');
		}
	}
	return $output;
}
/*old Validate the input*/
function old_sting_services_process_input($input) {
	$output = array();
	$comlete_success = true;
	foreach ($input as $key => $value) {
		$result = $value;
		if (stripos($key, 'size')) {
			$final_size = validate_size($key, $value);
			if ($final_size == false) {
				$complete_success = false;
				$result = '';
			} else {
				$result = $final_size;
			}
		} else if ((stripos($key, 'Color') || stripos($key, 'rgba')) && stripos($key, 'stop') == false) {
			$final_color = validate_color($key, $value);
			if ($final_color == false) {
				$complete_success = false;
				$result = '';
			} else {
				$result = $final_color;
			}
		} else if (stripos($key, 'stop')) {
			$final_value = validate_color_stop($key, $value);
			if ($final_value == false) {
				$complete_success = false;
				$result = '';
			} else {
				$result = $final_value;
			}
		}
		$output[$key] = $result;
	}
	if ($complete_success == true) {
		//show that it worked.
		//don't have to do anything, wordpress takes care of it for us (if we don't report any errors.
	}
	return $output;
}
function validate_size($key, $value) {
	if (is_numeric($value) == true) {
		return $value.'pt';
	} else if (stripos($value, 'pt') != false) {
		$pos_pt = stripos($value, 'pt');
		$unitless_size = substr($value, 0, $pos_pt);
		if (is_numeric($unitless_size)) {
			return $unitless_size. 'pt';
		}
	}
	add_settings_error($key, 'type-error', $key.' value must be valid font size');
	return false;
}
function validate_color_stop($key, $value) {
	if ($value == '') {
		return '';
	}
	if (is_numeric($value) == true) {
		$val_as_int = intval($value);
		if ($val_as_int < 0 || $val_as_int > 100) {
			add_settings_error($key, 'type-error', $key.' value must be valid pestingent');
			return false;
		}
		return $value.'%';
	} else if (stripos($value, '%') != false) {
		$pos_pt = stripos($value, '%');
		$unitless_size = substr($value, 0, $pos_pt);
		if (is_numeric($unitless_size)) {
		$val_as_int = intval($value);
			if ($val_as_int < 0 || $val_as_int > 100) {
				add_settings_error($key, 'type-error', $key.' value must be valid pestingent');
				return false;
			}
			return $unitless_size. '%';
		}
	}
	add_settings_error($key, 'type-error', $key.' value must be valid pestingent');
	return false;
}
function validate_color($key, $value) {
	if ($value == '') {
		return '';
	}

	$pos_open = strpos($value, '(') + 1;
	$type = substr($value, 0, $pos_open);
	if ($type != 'rgba(') {
		add_settings_error($key, 'type-error', $key.' value must be valid rgba color code debug = 0');
		return false;
	}
	$end_red = strpos($value, ',');
	if ($end_red == false) {
		add_settings_error($key, 'type-error', $key.' value must be valid rgba color code debug = 1');
		return false;
	}
	$red = substr($value, $pos_open, $end_red - $pos_open);
	if (is_numeric($red) == false || intval($red) < 0 || intval($red) > 255 || intval($red) != $red) {
		add_settings_error($key, 'type-error', $key.' value must be valid rgba color code debug = 1a');
		return false;
	}
	
	
	$end_green = strpos($value, ',', $end_red + 1);
	if ($end_green == false) {
		add_settings_error($key, 'type-error', $key.' value must be valid rgba color code debug = 2');
		return false;
	}
	$green = substr($value, $end_red + 2, $end_green - $end_red - 2);
	if (is_numeric($green) == false || intval($green) < 0 || intval($green) > 255 || intval($green) != $green) {
		add_settings_error($key, 'type-error', $key.' value must be valid rgba color code debug = 2a');
		return false;
	}
	
	
	$end_blue = strpos($value, ',', $end_green + 1);
	if ($end_blue == false) {
		add_settings_error($key, 'type-error', $key.' value must be valid rgba color code debug = 3');
		return false;
	}
	$blue = substr($value, $end_green + 2, $end_blue - $end_green - 2);
	if (is_numeric($blue) == false || intval($blue) < 0 || intval($blue) > 255 || intval($blue) != $blue) {
		add_settings_error($key, 'type-error', $key.' value must be valid rgba color code debug = 3a');
		return false;
	}
	
	
	$end_alpha = strpos($value, ')');
	if ($end_alpha == false) {
		add_settings_error($key, 'type-error', $key.' value must be valid rgba color code debug = 4');
		return false;
	}
	$alpha = substr($value, $end_blue + 2, $end_alpha - $end_blue - 2);
	if (is_numeric($alpha) == false || floatval($alpha) < 0 || floatval($alpha) > 1) {
		add_settings_error($key, 'type-error', $key.' value must be valid rgba color code debug = 4a');
		return false;
	}
	//return $type . 'r='. $red . 'g='.$green. 'b='.$blue. ' alpha = '.$alpha.'end'; debug only
	return $type . $red .', '. $green .', '. $blue .', '. $alpha . ')';
}