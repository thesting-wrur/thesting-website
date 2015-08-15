<?php
require_once 'php-lib/foundation-press/menu-walker.php';
require_once 'php-lib/google-api-php-client/src/Google/autoload.php';
require_once 'admin/admin.php';
$admin_options = get_option('sting_admin_options');
//enqueue styles
function sting_enqueue_foundation_styles() {
	wp_register_style('sting-foundation-app', get_template_directory_uri().'/css/app.css');
	wp_register_style('sting-foundations-icons', get_template_directory_uri().'/css/foundation-icons.css');
	wp_register_style('sting-font-raleway', get_template_directory_uri().'/css/raleway.css');
	wp_enqueue_style('sting-foundation-icons');
	wp_enqueue_style('dashicons');
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
	wp_enqueue_script('sting-foundation-tab-js', get_template_directory_uri().'/js/lib/foundation.tab.js','','',true);
	wp_enqueue_script('sting-foundation-accordion-js', get_template_directory_uri().'/js/lib/foundation.accordion.js','','',true);
	wp_enqueue_script('sting-modernizr', get_template_directory_uri().'/js/lib/modernizr.js');
	wp_enqueue_script('mediaelement');
	wp_enqueue_script('sting-code', get_template_directory_uri().'/js/app.js','','',true);
	wp_enqueue_script('sting-homepage-layout', get_template_directory_uri().'/js/post.js','','',true);
	wp_enqueue_script('sting-stream', get_template_directory_uri().'/js/stream.js','','',true);
	wp_enqueue_script('sting-now-playing', get_template_directory_uri().'/js/now-playing.js','','',true);
	wp_localize_script('sting-now-playing', 'ajaxurl', admin_url( 'admin-ajax.php' , 'http'));
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
			//if ($menu_item -> post_parent == 0) {
				$title = $menu_item->title;
				$url = $menu_item->url;
				$menu_list .= '<a class='.$menu_item_classes.' href="' . $url . '">' . $title . '</a>';
			//}
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
		$hrs1 = date('H', $stime1);
		$hrs2 = date('H', $stime2);
		
		$min1 = date('i', $stime1);
		$min2 = date('i', $stime2);
		
		if ($hrs1 < $hrs2) {
			return -1;
		} else if ($hrs1 > $hrs2) {
			return 1;
		} else {
			if ($min1 < $min2) {
				return -1;
			} else if ($min1 > $min2) {
				return 1;
			} else {
				return 0;
			}
		}
		
		/*
		$itime1 = intval($stime1);
		$itime2 = intval($stime2);
		if ($itime1 < $itime2) {
			return -1;
		} else if ($itime1 > $itime2) {
			return 1;
		} else {
			return 0;
		}
		*/
	}
	return 0;
}
function sting_display_show_schedule($day_of_week, $index, $child_pages, $num_pages, $isMobile = false) {
	global $post;//Will be modified later on, then reverted to what it was. If we don't do this, we will break wordpress
	//Post refers to the current post. We temporarily use it to refer to another post, and then revert it.
	$initial_index = $index;
		for (;$index < $num_pages; $index++) {
			$current_page = $child_pages[$index];
			$day = get_field('day', $current_page -> ID);
			if (strcmp($day, $day_of_week) != 0) {
				if ($index == $initial_index) {
					echo '<div class="post row">';
					echo '<div class="large-4 medium-4 columns">';
					echo 'No shows on '.ucfirst($day_of_week);
					echo '</div></div>';
				}
				break;
			}
			echo '<div class="post row">';
			if ($isMobile) {
				displayShowTitle($current_page, $isMobile);
				displayShowTime($current_page);
			} else {
				displayShowTime($current_page);
				displayShowTitle($current_page, $isMobile);
			}
			echo '<div class="large-4 medium-4 columns show-host">';
			$wp_post_var = $post;//$post is a global variable in wordpress. We need to reset it back to what it was or other parts of wordpress WILL BREAK
			$post = $current_page;//Setting it to the current page
			echo coauthors(', ', ' and ', '', '', false);
			echo '</div>';
			$post = $wp_post_var;//Resetting it
			echo '</div>';
		}
	return $index;
}

function displayShowTime($current_page) {
	echo '<div class="large-4 medium-4 columns show-time">';
	echo get_field('start_time', $current_page -> ID).' - '.get_field('end_time', $current_page -> ID);
	echo '</div>';
}
function displayShowTitle($current_page, $isMobile) {
	echo '<div class="large-4 medium-4 columns show-title">';
	echo $isMobile == true ? '<h3>' : '';
	echo '<a href="'.get_permalink($current_page -> ID).'">'.$current_page -> post_title;
	echo '</a>';
	echo $isMobile == true ? '</h3>' : '';
	echo '</div>';
}

function print_shows($shows) {
	foreach($shows as $show) {
		$to_print = 'Show '.$show -> ID .' '. $show-> post_title .' '. $show->post_date.' '.get_field('day', $show -> ID).' '.get_field('start_time', $show -> ID, true).' '.get_field('end_time', $show -> ID, true);
		error_log($to_print.'\r\n');
		echo $to_print.'<br />';
	}
}

$show_type = 'sting_shows';
function sting_create_show_type() {
	global $show_type;
	$args = array(
		'description'         => 'Sting Shows',
		'labels'              => array('name' => 'Shows', 'singular_name' => 'Show', 'add_new_item' => 'Add New Show', 'edit_item' => 'Edit Show', 'new_item' => 'New Show', 'view_item' => 'View Show Page', 'search_items' => 'Search Shows', 'not_found' => 'No shows found', 'not_found_in_trash' => 'No shows found in the Trash'),
		'supports'            => array('title', 'editor', 'author', 'excerpt', 'custom-fields', 'revisions'), // removed - I (Teddy) don't think we want to have/ are going to have comments on shows
		'taxonomies'          => array( 'category', 'post_tag' ),
		'public'              => true,
		'menu_position'       => 5,
		//'has_archive'         => true,///////DO WE WANT THIS????  NO
		'capability_type'     => 'show',//'page',
		'map_meta_cap'		  => true,
		'menu_icon'			  => 'dashicons-microphone',
		'rewrite'			  => array('slug' => 'shows', 'pages' => true),
	);
	register_post_type( $show_type, $args );
//'capabilities'		  => array('edit_post' => 'edit_show', 'read_post' => 'read_show', 'delete_post' => 'delete_show', 'edit_others_posts' => 'edit_others_shows', 'publish_posts' => 'publish_shows', 'read_private_posts' => 'read_private_shows', 'read' => 'read', 'delete_posts' => 'delete_shows', 'delete_private_posts' => 'delete_private_shows', 'delete_published_posts' => 'delete_published_shows', 'edit_private_posts' => 'edit_private_shows', 'edit_published_posts' => 'edit_published_shows', 'delete_others_posts' => 'delete_others_shows'),	
}
add_action( 'init', 'sting_create_show_type' );

function sting_get_header_image() {
	global $admin_options;
	if (get_field('header-image', $id) != '') {
		return get_field('header-image', $id);
	} else {
		return $admin_options['sting_header_image_input_box'];
	}
	//echo get_template_directory_uri().'/images/studio.jpg';
}
$was_on_air = false;
function check_if_show_is_on_the_air($post_id, $post_obj, $updated) {
	global $was_on_air;
	$on_air_field_val = get_field('show_on_air', $post_id);
	$was_on_air = $on_air_field_val;
	error_log('test_acf '.var_export($on_air_field_val, true).' priority=0');
}
add_action('save_post', 'check_if_show_is_on_the_air', 0, 3);
function sting_push_show_time_to_gcal($post_id, $post_obj, $updated) {
	global $show_type;
	global $was_on_air;
	$current_on_air_field_val = get_field('show_on_air', $post_id);
	
	$create_event = !$was_on_air && get_field('show_on_air', $post_id);
	$end_event = $was_on_air && !get_field('show_on_air', $post_id);
	$do_nothing = $was_on_air == get_field('show_on_air', $post_id);
	$created = $post_obj -> post_date;
	$modified = $post_obj -> post_modified;
	error_log('post created '.$created);
	error_log('post modified '.$modified);
	$created_idx = strrpos($created, ':');
	$modified_idx = strrpos($modified, ':');
	$same_time = substr($created, 0, $created_idx) == substr($modified, 0, $modified_idx);
	error_log('same time: '.var_export($same_time, true));
	
	if ($same_time) {
		$create_event = true;
		$do_nothing = false;
	}
	error_log('create event '.var_export($create_event, true));
	error_log('end_event '.var_export($end_event, true));
	error_log('do nothing '.var_export($do_nothing, true));
	//error_log ($post_obj -> post_type);
	//error_log ($show_type);
	$gcal_id = get_post_meta($post_id, 'gcal_event_id', true);
	//error_log($gcal_id);
	$post_status = get_post_status($post_id);
	if (($post_obj -> post_type) === $show_type && ($post_status == 'publish' || $post_status == 'future')) {//stops drafts from getting pushed to the calendar
		if ($do_nothing) {
			return;
		}
		sting_setup_gcal();
		if ($create_event || $gcal_id == '') {
			sting_push_show_to_gcal($post_id, $post_obj);
			//error_log('var1 = '.$var1.' var2 = '.$var2 -> post_title);
		} else if ($end_event) {
			sting_cancel_show_event_gcal($post_id, $post_obj);
		}
	}
}
add_action('save_post', 'sting_push_show_time_to_gcal', 11, 3);

/*function test_acf2($post_id, $post_obj, $updated) {
	global $was_on_air;
	error_log('test_acf2 '.var_export(get_field('show_on_air', $post_id), true));
	$to_log = $was_on_air == true? 'true' : 'false';
	error_log('test_acf2 priority=11 was false previously? '.$to_log);
	error_log('create_event? '.var_export(!$was_on_air && !get_field('show_on_air', $post_id), true));
	error_log('end_event? '.var_export($was_on_air && get_field('show_on_air', $post_id), true));
	error_log('do_nothing '.var_export($was_on_air != get_field('show_on_air', $post_id), true));
}
//add_action('save_post', 'test_acf2', 11, 3);*/

function setup_gcal_client() {
	$calendar_options = get_option('sting_calendar_options');
	$cal_auth_info = get_option('sting_calendar_auth');
	$client = new Google_Client();
	// OAuth2 client ID and secret can be found in the Google Developers Console.
	$client->setClientId($calendar_options['client_id_input_box']);
	$client->setClientSecret($calendar_options['client_secret_input_box']);
	//$client->setRedirectUri(site_url().'/wp-admin/admin.php?page=calendar_auth');
	$client->setRedirectUri('https://thesting.wdev.wrur.org/wp-admin/admin.php?page=calendar_auth');
	$client->addScope('https://www.googleapis.com/auth/calendar');
	$client->setAccessType('offline');
	$client->setApprovalPrompt('force');
	
	$accessToken = $cal_auth_info['access_token_input_box'];
	//$accessToken = '{"access_token":"ya29.QgHX48Ip4YrIGhADMxZPRGblfHRrMjjOooap7pIN7mhpTXhQWc7r0MxWMk2dhjYPRB9xpSfwf14v6w","token_type":"Bearer","expires_in":3600,"refresh_token":"1\/vxjHnXZmqNXy0AgD25UZYc57tzuqUn6cufn_ZfKOL44","created":1427393945}';
	
	$refreshToken = $cal_auth_info['refresh_token_input_box'];
	//$refreshToken = '1/vxjHnXZmqNXy0AgD25UZYc57tzuqUn6cufn_ZfKOL44';
	
	$client->setAccessToken($accessToken);
	if ($client->isAccessTokenExpired()) {
		$client->refreshToken($refreshToken);
		$client->setAccessToken($client->getAccessToken());
	}
	return $client;
}

//setup ajax for live-updating the current show
add_action( 'wp_ajax_current_show', 'send_now_playing_data' );
add_action( 'wp_ajax_nopriv_current_show', 'send_now_playing_data' );

function send_now_playing_data() {
	global $gcal_service;//defined in /admin/settings/calendar-push.php
	global $show_type;//defined above
	global $gcal_event_id_key;//defined in /admin/settings/calendar-push.php
	global $sting_artist_field_name;//defined in /admin/dashboard/now-playing.php
	global $sting_title_field_name;//defined in /admin/dashboard/now-playing.php
	global $sting_widget_id;//defined in /admin/dashboard/now-playing.php
	global $sting_show_title;//defined in /admin/dashboard/now-playing.php
	global $sting_show_page_url;//defined in /admin/dashboard/now-playing.php
	////Pull the current show from google calendar
	$calendar_options = get_option('sting_calendar_options');
	sting_setup_gcal();
	$now = new DateTime('now', new DateTimeZone('America/New_York'));
	$timeMin = $now->format(DateTime::ISO8601);
	$date_interval = new DateInterval('PT1M');
	$now->add($date_interval);
	$timeMax = $now->format(DateTime::ISO8601);
	$shows = $gcal_service->events->listEvents($calendar_options['calendar_id_input_box'],array('showDeleted' => 'false', 'showHiddenInvitations' => false, 'singleEvents' => false, 'timeMax' => $timeMax, 'timeMin' => $timeMin));
	$show_list = array();
	foreach($shows as $show) {//filter out deleted shows. not sure why showDeleted doesn't work
		if ($show->getStatus() == 'confirmed') {
			array_push($show_list, $show);
		}
	}
	$current_show = $show_list[0];
	
	if ($current_show != null) {
	$id = $current_show->getId();
	
	$args = array(
			'post_type' => $show_type,
			'meta_key' => $gcal_event_id_key,
			'meta_value' => $id,
		);
	$show_posts = get_posts($args);
	/*foreach($show_posts as $post) {
		error_log('Title: '.$post->post_title.' Type: '.$post->post_type.' GcalID '.get_post_meta($post_id, $gcal_event_id_key));
	}*/
	$url = get_permalink($show_posts[0] -> ID);
	} else {
		$url = '';
	}
	$title = $current_show == null ? '' : $current_show->getSummary();//stops fatal error due to there being no shows on the air right now.
	if ($title == '') {
		$title = 'The Sting';
	} else {
		$title = substr($title, 0,  strripos($title, '-') - 1);
	}
	$toSend = array(
		$sting_artist_field_name	=>	get_dashboard_widget_option($sting_widget_id, $sting_artist_field_name),
		$sting_title_field_name 	=>	get_dashboard_widget_option($sting_widget_id, $sting_title_field_name),
		$sting_show_title			=> $title,
		$sting_show_page_url		=> $url,
	);
	
	//error_log(var_export($toSend, true));
	//error_log(get_dashboard_widget_option($sting_widget_id, $sting_artist_field_name));
	//error_log(get_dashboard_widget_option($sting_widget_id, $sting_title_field_name));
	echo json_encode($toSend);
	wp_die();
}

function sting_get_admin_message () {
	global $admin_options;
	$starttime = strtotime($admin_options['sting_admin_message_start_input_box']);
	$endtime = strtotime($admin_options['sting_admin_message_end_input_box']);
	$now = new DateTime('now', new DateTimeZone('America/New_York'));
	$nowtime = strtotime($now->format('m/d/Y H:i'));
	//error_log('starttime: '.date('m/d/y h:i',$starttime));
	//error_log('nowtime: '.date('m/d/y h:i', $nowtime));
	//error_log('endtime: '.date('m/d/y h:i',$endtime));
	//error_log('start time < now? '.(($starttime < $nowtime)? 'true' : 'false'));
	//error_log('end time > now? '.(($endtime > $nowtime)? 'true' : 'false'));
	//if ($starttime < $nowtime) { //It should go up immediately once it is posted, not wait. However we still want the start time so we can display it
		if ($endtime > $nowtime) {
			$toReturn = $admin_options['sting_admin_message_input_box'].' from '.date('m/d/y h:i A',$starttime).' to '.date('m/d/y h:i A',$endtime);
		}
	//}
	//error_log($toReturn);
	return $toReturn;
}
/*
 * Code to allow us to have multiple pages of posts on show pages.
 * Because we use a custom query, wordpress will not allow us to use the built in page functionality
 * So, we have to write at least part of it ourselves.
 */
function sting_modify_query_show_page($vars) {
	array_push($vars, 'pg');
	//error_log(var_export($vars, true));
	return $vars;
}
add_filter( 'query_vars', 'sting_modify_query_show_page' , 10, 1 );
/*
 * Code for RSS feeds for the shows - these get the posts from both hosts (and deal with 1 DJ 2 Shows)
 */
function sting_add_show_rss_feed() {
	//error_log('adding_rss_feed');
	add_feed('show_feed', 'sting_generate_show_rss_feed');
}
add_action('init', 'sting_add_show_rss_feed');
function sting_generate_show_rss_feed() {
	error_log('generate_rss_feed');
	get_template_part('subTemplates/rss/rss', 'show');
}
function sting_add_feeds_to_header() {
	global $admin_options;
	if (get_post_type() === 'sting_shows') {
		echo '<link rel="alternate" type="application/rss+xml" title="'.get_the_title().'" href="'.get_bloginfo('url').'?feed=show_feed&show='.get_the_ID().'" />';
	}
	echo '<link rel="alternate" type="application/rss+xml" title="'.get_bloginfo('name').' - All Posts" href="'.get_bloginfo('rss2_url').'" />';
	$cat_id = intval($admin_options['homepage_cat_input_box']);
	echo '<link rel="alternate" type="application/rss+xml" title="'.get_bloginfo('name').' - News Feed" href="'.esc_url(home_url()).'?cat='.$cat_id.'&feed=rss2" />';
}
add_action('wp_head', 'sting_add_feeds_to_header');
remove_action( 'wp_head', 'feed_links_extra', 3 );

/*
 * Comparator for sorting shows by title alphabetically
 */
function sting_compare_title($show1, $show2) {
	$title1 = $show1 -> post_title;
	$title2 = $show2 -> post_title;
	
	return strcmp ($title1, $title2);
}
/*function sting_capitalize_title($title, $sep) {
	$upper_title = substr($title, 0, strpos($title, $sep));
	$subtitle = substr($title, strpos($title, $sep), strlen($title));
	$upper_title = strtoupper($upper_title);
	return $upper_title.$subtitle;
}
add_filter( 'wp_title', 'sting_capitalize_title', 10, 2 );*/
?>