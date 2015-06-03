<?php
add_action('admin_init', 'sting_setup_calendar_options');
$calendar_options_page_name = 'calendar_options';
function sting_setup_calendar_options() {
	global $calendar_options_page_name;
	register_setting('sting_calendar_options_group', 'sting_calendar_options', 'sting_calendar_process_input');

	$calendar_settings_section = 'calendar_settings';
	add_settings_section($calendar_settings_section, 'Calendar Settings', 'setup_cal_section', $calendar_options_page_name);
	add_settings_field('current_show_end_date', 'Default Show End Date:', 'current_show_end_date_input_box', $calendar_options_page_name, $calendar_settings_section);
	add_settings_field('calendar_id', 'Google Calendar ID:', 'calendar_id_input_box', $calendar_options_page_name, $calendar_settings_section);	
	
	add_settings_section('auth_settings', 'Calendar Authentication', 'setup_auth_section', $calendar_options_page_name);
	add_settings_field('client_id', 'Client ID:', 'client_id_input_box', $calendar_options_page_name, 'auth_settings');
	add_settings_field('client_secret', 'Client Secret:', 'client_secret_input_box', $calendar_options_page_name, 'auth_settings');
}

function create_calendar_admin_page() {
	global $calendar_options_page_name;
	global $sting_admin_page_name;
	add_submenu_page($sting_admin_page_name, 'Calendar Options', 'Calendar Options', 'edit_theme_options', $calendar_options_page_name, 'create_calendar_options_page');
}

$calendar_options = get_option('sting_calendar_options');

function client_id_input_box() {
	global $calendar_options;
	echo "<input name='sting_calendar_options[client_id_input_box]' type='text' value='{$calendar_options['client_id_input_box']}' />";
}

function client_secret_input_box() {
	global $calendar_options;
	echo "<input name='sting_calendar_options[client_secret_input_box]' type='text' value='{$calendar_options['client_secret_input_box']}' />";
	echo '<br>';
	setup_gcal_window();
}

function setup_gcal_scripts($hook) {
	//error_log($hook);
	if ($hook === 'sting_page_calendar_options') {
		wp_enqueue_script('sting_google_auth', get_template_directory_uri().'/admin/settings/js/google_auth.js');
	}
}
add_action ('admin_enqueue_scripts', 'setup_gcal_scripts', 10, 1);

function setup_gcal_window() {
	$client = setup_gcal_client();
	
	$url = $client->createAuthUrl();
	$url = '\''.$url.'\'';
	echo '<a href="javascript:openGoogleAuth('.$url.');" class="button button-primary" style="margin-top: 10px;">Authenticate</a>';
}

function current_show_end_date_input_box() {
	global $calendar_options;
	echo "<input name='sting_calendar_options[current_show_end_date_input_box]' type='text' value='{$calendar_options['current_show_end_date_input_box']}' />";
}
function calendar_id_input_box() {
	global $calendar_options;
	echo "<input name='sting_calendar_options[calendar_id_input_box]' type='text' value='{$calendar_options['calendar_id_input_box']}' />";
}

function setup_auth_section() {}
function setup_cal_section() {}
function create_calendar_options_page() {
	global $calendar_options_page_name;
	?>
	<div class="wrap">
		<h2>Calendar Options</h2>
		<form method="post" action="options.php" enctype="multipart/form-data">
			<?php
				settings_errors();
				settings_fields('sting_calendar_options_group');
				do_settings_sections($calendar_options_page_name);
				submit_button();
			?>
		</form>
	</div>
<?php
}

function sting_calendar_process_input($input) {
	return $input;
}