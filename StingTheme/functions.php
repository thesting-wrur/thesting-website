<?php
require_once 'foundation-press/menu-walker.php';
require_once 'admin/admin.php';
//enqueue styles
function sting_enqueue_foundation_styles() {
	wp_register_style('sting-foundation-app', get_template_directory_uri().'/css/app.css');
	wp_register_style('sting-foundations-icons', get_template_directory_uri().'/css/foundation-icons.css');
	wp_register_style('sting-google-font-railway', 'http://fonts.googleapis.com/css?family=Raleway:800');
	wp_enqueue_style('sting-foundation-app');
	wp_enqueue_style('sting-foundation-icons');
	wp_enqueue_style('sting-google-font-railway');
}
add_action('wp_enqueue_scripts', 'sting_enqueue_foundation_styles');

//enqueue scripts
function sting_enqueue_foundation_script() {
	wp_deregister_script('jquery');
	wp_enqueue_script('jquery', get_template_directory_uri().'/js/jquery.min.js','','',true);
	wp_enqueue_script('sting-foundation-js', get_template_directory_uri().'/js/foundation.js','','',true);
	wp_enqueue_script('sting-modernizr', get_template_directory_uri().'/js/modernizr.js');
	wp_enqueue_script('sting-code', get_template_directory_uri().'/js/app.js','','',true);
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
		$menu_item_classes = '"button button-dark menu-item"';
		$menu_list .= '<a class='.$menu_item_classes.' href="'.get_site_url().'">'.'Home'.'</a>';
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

function sting_compare_shows_by_date_time($show1, $show2) {
	$days = array('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday');
	$day1 = get_field('day', $show1 -> ID);
	$day2 = get_field('day', $show2 -> ID);
	$day1_index = array_search($day1, $days);
	$day2_index = array_search($day2, $days);
	if ($day1_index < $day2_index) {
		return -1;
	} else if ($day1_index > $day2_index) {
		return 1;
	} else {
		$stime1 = get_field('start_time', $show1 -> ID, false);
		$stime2 = get_field('start_time', $show2 -> ID, false);
		if ($stime1 < $stime2) {
			return 1;
		} else if ($stime1 > $stime2) {
			return -1;
		} else {
			return 0;
		}
	}
	return 0;
}
function sting_display_show_schedule($day_of_week, $index, $child_pages) {
	global $post;//Will be modified later on, then reverted to what it was. If we don't do this, we will break wordpress
	//Post refers to the current post. We temporarily use it to refer to another post, and then revert it.
	for (;$index < count($child_pages);$index++) {
		$current_page = $child_pages[$index];
		$day = get_field('day', $current_page -> ID);
		if (strcmp($day, $day_of_week) != 0) {
			break;
		}
		echo '<div class="post">';
		echo get_field('start_time', $current_page -> ID).' - '.get_field('end_time', $current_page -> ID);
		echo '<a href="'.get_permalink($current_page -> ID).'">'.$current_page -> post_title;
		echo '</a>';
		$wp_post_var = $post;//$post is a global variable in wordpress. We need to reset it back to what it was or other parts of wordpress WILL BREAK
		$post = $current_page;//Setting it to the current page
		echo coauthors(',', ' and ', ' - ', '', false);
		$post = $wp_post_var;//Resetting it
		echo '</div>';
	}
	return $index;
}
/*function sting_create_show_type() {
	$args = array(
		'description'         => 'Post Type Description',
		'labels'              => 
		'supports'            => array( ),
		'taxonomies'          => array( 'category', 'post_tag' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	register_post_type( 'show', $args ); 
}*/
/*function sting_capitalize_title($title, $sep) {
	$upper_title = substr($title, 0, strpos($title, $sep));
	$subtitle = substr($title, strpos($title, $sep), strlen($title));
	$upper_title = strtoupper($upper_title);
	return $upper_title.$subtitle;
}
add_filter( 'wp_title', 'sting_capitalize_title', 10, 2 );*/
?>