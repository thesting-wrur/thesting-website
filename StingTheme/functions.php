<?php
require 'foundation-press/menu-walker.php';
//enqueue styles
function sting_enqueue_foundation_styles() {
	wp_register_style('sting-foundation-app', get_template_directory_uri().'/css/app.css');
	wp_register_style('sting-foundations-icons', get_template_directory_uri().'/css/foundation-icons.css');
	wp_enqueue_style('sting-foundation-app');
	wp_enqueue_style('sting-foundation-icons');
}
add_action('wp_enqueue_scripts', 'sting_enqueue_foundation_styles');

//enqueue scripts
function sting_enqueue_foundation_script() {
	//wp_enqueue_script('jquery');the default jquery installed with wordpress doesn't allow for using $
	wp_deregister_script('jquery');
	wp_enqueue_script('jquery', get_template_directory_uri().'/js/jquery.min.js','','',true);
	wp_enqueue_script('sting-foundation-js', get_template_directory_uri().'/js/foundation.js','','',true);
	//wp_enqueue_script('sting-foundation-topbar-js', get_template_directory_uri().'/js/foundation.topbar.js','','',true);
	//wp_enqueue_script('sting-fastclick', get_template_directory_uri().'/js/fastclick.js');
	wp_enqueue_script('sting-modernizr', get_template_directory_uri().'/js/modernizr.js');
	wp_enqueue_script('sting-code', get_template_directory_uri().'/js/app.js','','',true);
	//wp_enqueue_script('sting-code','','','',true);
}
add_action('wp_enqueue_scripts', 'sting_enqueue_foundation_script');

function sting_add_theme_support() {
	//allow wordpress generated stuff to use html5
	add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption'));
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'sting_add_theme_support' );

//register menu - enable a menu in appearance --> menu
function sting_register_menu() {
	register_nav_menu('primary', 'Primary Navigation');
}
add_action('init', 'sting_register_menu');
function sting_display_mobile_menu() {
	//Code from the codex: http://codex.wordpress.org/Function_Reference/wp_get_nav_menu_items
	$menu_name = 'primary';
    if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
		$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
		$menu_items = wp_get_nav_menu_items($menu->term_id);
		$menu_list = '<div id="menu" class="show-for-small-only">';
		$menu_item_classes = '"button dark-button menu-item"';
		foreach ( (array) $menu_items as $key => $menu_item ) {
			if ($menu_item -> post_parent == 0) {
				$title = $menu_item->title;
				$url = $menu_item->url;
				$menu_list .= '<a class='.$menu_item_classes.' href="' . $url . '">' . $title . '</a>';
			}
		}
		$menu_list .= '</div>';
    }
	echo $menu_list;
}

function sting_homepage_category( $query ) {
    if ( $query->is_home() && $query->is_main_query() ) {
        $query->set( 'cat', '3' );//make this the correct id
    }
}
add_action( 'pre_get_posts', 'sting_homepage_category' );
?>