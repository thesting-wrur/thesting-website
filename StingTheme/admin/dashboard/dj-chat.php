<?php
add_action('wp_dashboard_setup', 'sting_add_chat_widget');
//error_log('hi');
function sting_add_chat_widget() {
	wp_add_dashboard_widget(
		'sting_dj_chat_widget',
		'Chat With your Listeners',
		'sting_setup_dj_chat_widget'
	);
}

function sting_setup_dj_chat_widget() {
	echo do_shortcode('[quick-chat height="200" userlist="0" smilies="0"]');// room="dj-chat"]');
}


?>