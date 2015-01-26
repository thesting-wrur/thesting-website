<?php
//enqueue styles
function sting_enqueue_foundation_styles() {
	wp_register_style('sting-foundation', get_template_directory_uri().'/foundation/css/foundation.css');
	wp_enqueue_style('sting-foundation');
}
add_action('wp_enqueue_scripts', 'sting_enqueue_foundation_styles');

//register menu - enable a menu in appearance --> menu
function sting_register_menu() {
	register_nav_menu('primary', 'Primary Navigation');
}
add_action('init', 'sting_register_menu');

?>