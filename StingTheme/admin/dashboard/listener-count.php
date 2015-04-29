<?php
require_once 'listen-count/livecounter.php';
add_action('wp_dashboard_setup', 'sting_add_listener_count_widget');

function sting_add_listener_count_widget() {
	wp_add_dashboard_widget(
		'sting_listener_count_widget',
		'Listener Count',
		'sting_setup_listener_count_widget'
	);
}

function sting_setup_listener_count_widget() {
	echo '<span id="sting-listen-count">&nbsp;</span>';
}

function sting_listen_count_admin_scripts($hook) {
	if ($hook == 'index.php') {
		wp_register_script('sting-listen-count-script', get_template_directory_uri().'/admin/dashboard/listen-count/livecounter.js');
		wp_enqueue_script('sting-listen-count-script');
	}
}
add_action('admin_enqueue_scripts', 'sting_listen_count_admin_scripts');

//setup ajax for live-updating the current show
add_action( 'wp_ajax_listener_count', 'send_listener_count_data' );
function send_listener_count_data() {
	getNumberOfListeners();
}
?>
