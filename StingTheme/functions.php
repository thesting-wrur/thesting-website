<?php
require_once 'php-lib/foundation-press/menu-walker.php';
require_once 'php-lib/google-api-php-client/src/Google/autoload.php';
require_once 'admin/admin.php';
require_once 'admin/calendar-push.php';

//enqueue styles
function sting_enqueue_foundation_styles() {
	wp_register_style('sting-foundation-app', get_template_directory_uri().'/css/app.css');
	wp_register_style('sting-foundations-icons', get_template_directory_uri().'/css/foundation-icons.css');
	wp_register_style('sting-font-raleway', get_template_directory_uri().'/css/raleway.css');
	wp_enqueue_style('sting-foundation-icons');
	wp_enqueue_style('sting-font-raleway');
	wp_enqueue_style('mediaelement');
	wp_enqueue_style('sting-foundation-app');
}
add_action('wp_enqueue_scripts', 'sting_enqueue_foundation_styles');

//enqueue scripts
function sting_enqueue_foundation_script() {
	wp_deregister_script('jquery');
	wp_enqueue_script('jquery', get_template_directory_uri().'/js/lib/jquery.min.js','','',true);
	wp_enqueue_script('jquery-cookies', get_template_directory_uri().'/js/lib/jquery.cookie.js','','',true);
	wp_enqueue_script('sting-foundation-js', get_template_directory_uri().'/js/lib/foundation.js','','',true);
	wp_enqueue_script('sting-modernizr', get_template_directory_uri().'/js/lib/modernizr.js');
	wp_enqueue_script('mediaelement');
	wp_enqueue_script('sting-code', get_template_directory_uri().'/js/app.js','','',true);
	wp_enqueue_script('sting-homepage-layout', get_template_directory_uri().'/js/post.js','','',true);
	wp_enqueue_script('sting-stream', get_template_directory_uri().'/js/stream.js','','',true);
	
	//enqueue listener count script only on the DJ on-air view page
	if (stripos(get_page_template(), 'onAir') != 0) {
		wp_register_script('listener-count', get_template_directory_uri().'/listen-count/livecounter.js');
		wp_enqueue_script('listener-count');
		$templateDir = get_template_directory_uri();
		wp_localize_script('listener-count', 'stingTemplateDir', $templateDir);
	}
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
		$admin_options = get_option('sting_admin_options');
		$id = intval($admin_options['homepage_cat_input_box']);
        $query->set( 'cat', $id);//make this the correct id
    }
}
add_action( 'pre_get_posts', 'sting_homepage_category' );

function sting_compare_shows_by_date_time($show1, $show2) {
	$days = array('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
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
			return -1;
		} else if ($stime1 > $stime2) {
			return 1;
		} else {
			return 0;
		}
	}
	return 0;
}
function sting_display_show_schedule($day_of_week, $index, $child_pages, $num_pages) {
	global $post;//Will be modified later on, then reverted to what it was. If we don't do this, we will break wordpress
	//Post refers to the current post. We temporarily use it to refer to another post, and then revert it.
	for (;$index < $num_pages;$index++) {
		$current_page = $child_pages[$index];
		$day = get_field('day', $current_page -> ID);
		if (strcmp($day, $day_of_week) != 0) {
			break;
		}
		echo '<div class="post">';
		echo get_field('start_time', $current_page -> ID, false).' - '.get_field('end_time', $current_page -> ID);
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
$show_type = 'sting_shows';
function sting_create_show_type() {
	global $show_type;
	$args = array(
		'description'         => 'Sting Shows',
		'labels'              => array('name' => 'Shows', 'singular_name' => 'Show', 'add_new_item' => 'Add New Show', 'edit_item' => 'Edit Show', 'new_item' => 'New Show', 'view_item' => 'View Show Page', 'search_items' => 'Search Shows', 'not_found' => 'No shows found', 'not_found_in_trash' => 'No shows found in the Trash'),
		'supports'            => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields', 'revisions', 'bage-attributes'),
		'taxonomies'          => array( 'category', 'post_tag' ),
		'public'              => true,
		'menu_position'       => 5,
		'has_archive'         => true,
		'capability_type'     => 'page',
		'menu_icon'			  => 'dashicons-microphone',
		'rewrite'			  => array('slug' => 'shows'),
	);
	register_post_type( $show_type, $args );
//'capabilities'		  => array('edit_post' => 'edit_show', 'read_post' => 'read_show', 'delete_post' => 'delete_show', 'edit_others_posts' => 'edit_others_shows', 'publish_posts' => 'publish_shows', 'read_private_posts' => 'read_private_shows', 'read' => 'read', 'delete_posts' => 'delete_shows', 'delete_private_posts' => 'delete_private_shows', 'delete_published_posts' => 'delete_published_shows', 'edit_private_posts' => 'edit_private_shows', 'edit_published_posts' => 'edit_published_shows', 'delete_others_posts' => 'delete_others_shows'),	
}
add_action( 'init', 'sting_create_show_type' );

function sting_get_header_image() {
	$admin_options = get_option('sting_admin_options');
	if (get_field('header-image', $id) != '') {
		return get_field('header-image', $id);
	} else {
		return $admin_options['sting_header_image_input_box'];
	}
	//echo get_template_directory_uri().'/images/studio.jpg';
}

function sting_push_show_time_to_gcal($post_id, $post_obj, $updated) {
	global $show_type;
	//error_log ($post_obj -> post_type);
	//error_log ($show_type);
	if (($post_obj -> post_type) === $show_type) {
		sting_setup_gcal();
		sting_push_post_to_gcal($post_id, $post_obj);
		//error_log('var1 = '.$var1.' var2 = '.$var2 -> post_title);
	}
}
add_action('save_post', 'sting_push_show_time_to_gcal', 11, 3);

/*function sting_capitalize_title($title, $sep) {
	$upper_title = substr($title, 0, strpos($title, $sep));
	$subtitle = substr($title, strpos($title, $sep), strlen($title));
	$upper_title = strtoupper($upper_title);
	return $upper_title.$subtitle;
}
add_filter( 'wp_title', 'sting_capitalize_title', 10, 2 );*/
?>