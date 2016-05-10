<?php
add_action('wp_dashboard_setup', 'sting_add_monitor_chat_widget');
//error_log('hi');
function sting_add_monitor_chat_widget() {
	wp_add_dashboard_widget(
		'sting_monitor_chat_widget',
		'Clear out chat spam',
		'sting_setup_monitor_chat_widget'
	);
}

function sting_setup_monitor_chat_widget() {
	echo do_shortcode('[quick-chat height="200" userlist="0" smilies="0"]');
}


?>