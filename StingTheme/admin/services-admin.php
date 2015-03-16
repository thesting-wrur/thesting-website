<?php
//sting
add_action('admin_init', 'sting_setup_services_options');
$sting_services_page_name = 'services_options';
function sting_setup_services_options() {
	global $sting_services_page_name;
	register_setting('sting_services_options_group', 'sting_services_options', 'sting_services_process_input');
	add_settings_section('content_settings', 'Page Content', 'setup_content_section', $sting_services_page_name);
	add_settings_field('mlp_input', 'Meat Locker Productions Description:', 'mlp_input_box', $sting_services_page_name, 'content_settings');
	add_settings_field('live_events_input', 'Live Events Description:', 'live_events_input_box', $sting_services_page_name, 'content_settings');
	add_settings_field('show_dj_input', 'DJing a show Description:', 'show_dj_input_box', $sting_services_page_name, 'content_settings');
}
function create_services_options_page() {
	global $sting_services_page_name;
	global $sting_admin_page_name;
	add_submenu_page($sting_admin_page_name, 'Services', 'Services', 'edit_theme_options', $sting_services_page_name, 'create_sting_options_page');
}
//add_action('admin_menu', 'create_services_options_page');

//input boxes
$services_options = get_option('sting_services_options');
function mlp_input_box() {
	generate_editor('mlp');
}
function show_dj_input_box() {
	generate_editor('show_dj');
}
function live_events_input_box() {
	generate_editor('live_events');
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
}
function create_sting_options_page() {
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