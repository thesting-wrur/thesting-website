<?php
require 'foundation-press/menu-walker.php';
//enqueue styles
function sting_enqueue_foundation_styles() {
	wp_register_style('sting-foundation', get_template_directory_uri().'/foundation/css/foundation.css');
	wp_enqueue_style('sting-foundation');
}
add_action('wp_enqueue_scripts', 'sting_enqueue_foundation_styles');

//enqueue scripts
function sting_enqueue_foundation_script() {
	wp_register_script('sting-foundation-js', get_template_directory_uri().'/foundation/js/foundation/foundation.js');
	wp_register_script('sting-foundation-topbar-js', get_template_directory_uri().'/foundation/js/foundation/foundation.topbar.js');
	wp_register_script('sting-fastclick', get_template_directory_uri().'/foundation/js/vendor/fastclick.js');
	wp_enqueue_script('jquery');
	wp_enqueue_script('sting-foundation-js');
	wp_enqueue_script('sting-foundation-topbar-js');
	wp_enqueue_script('sting-fastclick');
}
add_action('wp_enqueue_scripts', 'sting_enqueue_foundation_script');

//register menu - enable a menu in appearance --> menu
function sting_register_menu() {
	register_nav_menu('primary', 'Primary Navigation');
}
add_action('init', 'sting_register_menu');


?>