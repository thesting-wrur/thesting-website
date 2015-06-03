<?php
add_action('admin_init', 'sting_setup_calendar_auth');
$calendar_auth_page_name = 'calendar_auth';
function sting_setup_calendar_auth() {
	global $calendar_auth_page_name;
	register_setting('sting_calendar_auth_group', 'sting_calendar_auth', 'sting_calendar_auth_process_input');

	$calendar_second_auth_section = 'second_auth_settings';
	add_settings_section($calendar_second_auth_section, 'Calendar Authentication', 'setup_second_auth_section', $calendar_auth_page_name);
	add_settings_field('access_token', 'Access Token:', 'access_token_input_box', $calendar_auth_page_name, $calendar_second_auth_section);
	add_settings_field('refresh_token', 'Refresh Token:', 'refresh_token_input_box', $calendar_auth_page_name, $calendar_second_auth_section);
}

function create_calendar_auth_page() {
	global $calendar_auth_page_name;
	global $sting_admin_page_name;
	add_submenu_page($sting_admin_page_name, 'Calendar Auth II', 'Calendar Auth II', 'edit_theme_options', $calendar_auth_page_name, 'create_calendar_second_auth_page');
}

function setup_gcal_auth_2_scripts($hook) {
	if ($hook === 'sting_page_calendar_auth') {//need to do this properly (without the reregistration of javascript and putting it in the footer.)
		wp_deregister_script('jquery');
		wp_enqueue_script('jquery', get_template_directory_uri().'/js/lib/jquery.min.js','','',false);
		wp_enqueue_script('sting_google_auth', get_template_directory_uri().'/admin/settings/js/google_auth2.js','','',false);
	}
}
add_action ('admin_enqueue_scripts', 'setup_gcal_auth_2_scripts', 10, 1);

$calendar_auth_options = get_option('sting_calendar_auth');

function access_token_input_box() {
	global $calendar_auth_options;
	echo 'The content in this field is hidden from view as it is put there by javascript.';
	echo '<br>';
	echo "<input name='sting_calendar_auth[access_token_input_box]' type='text' id='access_token' value='{$calendar_auth_options['access_token_input_box']}' />";
}

function refresh_token_input_box() {
	global $calendar_auth_options;
	echo 'The content in this field is hidden from view as it is put there by javascript.';
	echo '<br>';
	echo "<input name='sting_calendar_auth[refresh_token_input_box]' type='text' id='refresh_token' value='{$calendar_auth_options['refresh_token_input_box']}' />";
}
function setup_second_auth_section() {}
function create_calendar_second_auth_page() {
	global $calendar_auth_page_name;
	?>
	<div class="wrap">
		<h2>Authentication Options</h2>
		The data on this page should NOT be input manually. It is generated from javascript due to the google authentication process.
		If you have questions, speak with the webmaster.
		<h3 style="display:none;" id="submitting">Submitting...</h3>
		<form method="post" id="auth_form" action="options.php" enctype="multipart/form-data">
			<?php
				settings_errors();
				settings_fields('sting_calendar_auth_group');
				do_settings_sections($calendar_auth_page_name);
				submit_button();
			?>
		</form>
	</div>
<?php
}

function sting_calendar_auth_process_input($input) {
	$client = setup_gcal_client();
	$authCode = $input['access_token_input_box'];
	
	if (json_decode($authCode) != null) {//key and token exchange has already taken place.
		return $input;
	}
	
	error_log($authCode);
	$accessToken = $client->authenticate($authCode);
	$parsedToken = json_decode($accessToken);
	error_log(var_export($parsedToken, true));
	$refreshToken = $parsedToken -> refresh_token;
	error_log($refreshToken);
	
	$input['access_token_input_box'] = $accessToken;
	$input['refresh_token_input_box'] = $refreshToken;
	return $input;
}

?>