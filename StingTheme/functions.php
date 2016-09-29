<?php
/**
 * Collection of helper functions that apply to the entire theme
 *
 */
require_once 'php-lib/foundation-press/menu-walker.php';//loads code necessary to generate the menu
require_once 'php-lib/google-api-php-client/src/Google/autoload.php';//loads code necessary for any google related operation
require_once 'admin/admin.php';//loads the admin secton of the theme
$admin_options = get_option('sting_admin_options');//gets a copy of the options from the admin options page
/**
 * Registers and enqueues styles to be added to the webpage header.
 *
 * Styles Registered:
 *		app.css - main style of the theme
 *		foundation-icons.css - foundation's icons
 *		raleway.css - the website's font
 *
 * Other styles enqueued:
 *		mediaelement - for the html5 player (with a backup flash player)
 *		dashicons - for the play/pause icons
 *
 * Action Hook: wp_enqueue_scripts
 */
function sting_enqueue_foundation_styles() {//enqueue styles
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

/**
 * Regesters and enqueues scripts to be added to the webpage. 
 *
 * Deregisters jQuery so that we can use our own. We actually should just go back to using the no-conflict version of jQuery as opposed to bringig in one that might have problems
 *
 * Enqueues:
 *		jQuery
 *		jQuery Cookies (to facilitate the creation of cookies)
 *		Foundation, Foundation-tab (schedule page), Foundation-accordion (schedule page), Foundation-equalizer (shows page)
 *		Modernizr (make new stuff work in old browsers)
 *		Mediaelement (sting player)
 *		
 *		app.js, post.js, stream.js, now-playing.js - code written by us (see the individual file descriptions)
 *
 *	Adds the url for admin-ajax to the page (localize script)
 *
 *	Action Hook: wp_enqueue_scripts
 */
function sting_enqueue_foundation_script() {//enqueue scripts
	wp_deregister_script('jquery');
	wp_enqueue_script('jquery', get_template_directory_uri().'/js/lib/jquery.min.js','','',true);
	wp_enqueue_script('jquery-cookies', get_template_directory_uri().'/js/lib/jquery.cookie.js','','',true);
	wp_enqueue_script('sting-foundation-js', get_template_directory_uri().'/js/lib/foundation.js','','',true);
	wp_enqueue_script('sting-foundation-tab-js', get_template_directory_uri().'/js/lib/foundation.tab.js','','',true);
	wp_enqueue_script('sting-foundation-accordion-js', get_template_directory_uri().'/js/lib/foundation.accordion.js','','',true);
	wp_enqueue_script('sting-foundation-equalizer-js', get_template_directory_uri().'/js/lib/foundation.equalizer.js','','',true);
	wp_enqueue_script('sting-modernizr', get_template_directory_uri().'/js/lib/modernizr.js');
	wp_enqueue_script('mediaelement');
	wp_enqueue_script('sting-code', get_template_directory_uri().'/js/app.js','','',true);
	wp_enqueue_script('sting-homepage-layout', get_template_directory_uri().'/js/post.js','','',true);
	wp_enqueue_script('sting-stream', get_template_directory_uri().'/js/stream.js','','',true);
	wp_enqueue_script('sting-now-playing', get_template_directory_uri().'/js/now-playing.js','','',true);
	wp_localize_script('sting-now-playing', 'ajaxurl', admin_url( 'admin-ajax.php' , 'http'));
}
add_action('wp_enqueue_scripts', 'sting_enqueue_foundation_script');

/**
 * Meta tag for Teddy to verify ownership of thesting.wrur.org for Google Webmaster Tools.
 *
 * Action Hook: wp_head
 */
function sting_add_webmaster_tools_meta_head() {
	echo '<meta name="google-site-verification" content="YcHsMSIoweqqISBMkWniYMfQsM3Kc_hWTB1RnNM7Y8I" />';
}
add_action('wp_head', 'sting_add_webmaster_tools_meta_head');
/**
 * 
 * Add facebook meta tags and javascript to header
 *
 * Code based on what's found on facebook developers.
 *
 * We should find a good way to do a description of the posts
 *
 * Action Hook:  wp_head
 */
function sting_add_fb_head() {
	global $post;
	echo '<!-- Begin facebook crawler properties -->';
	if (is_single()) {
	?>
		<meta property="og:url"           content="<?php echo get_permalink();?>" />
		<meta property="og:title"         content="<?php echo get_the_title(); ?>" />
		<!--<meta property="og:description"   content="<php echo apply_filters('get_the_excerpt', $post->post_excerpt); ?>" />-->
		<?php
	} else if (is_home() && is_front_page()) {
		?>
		<meta property="og:url"				content="<?php echo get_bloginfo('url');?>" />
		<meta property="og:title"			content="<?php echo get_bloginfo('name') ?>" />
		<meta property="og:description"		content="<?php echo get_bloginfo('description') ?>" />
		<?php
	} else if (is_page_template('schedule.php')) {
	?>
		<meta property="og:title"			content="<?php echo get_bloginfo('name') ?> - Schedule" />
		<meta property="og:url"           content="<?php echo get_permalink();?>" />
	<?php
	}
	echo '<meta property="og:image"			content="'.get_template_directory_uri().'/img/screenshot.jpg" />';
	echo '<!-- End facebook crawler properties -->';
}
add_action('wp_head', 'sting_add_fb_head');

/**
 * Adds code that makes the share on facebook buttons work
 *
 * Action Hook: wp_had
 */
function sting_add_fb_foot() {
	?>
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4";
	fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	<?php
}
add_action('wp_head', 'sting_add_fb_foot');
/* End facebook section */
/* Start twitter section */
/**
 * Adds code so that the share on twitter button works
 *
 * Action Hook: wp_footer
 */
function sting_add_twitter_foot() {
	?>
	<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
	<?php
}
add_action('wp_footer', 'sting_add_twitter_foot');

/**
 *
 * Tells wordpress to use HTML5 for comments, searching, galleries and captions, as well as support post-thumbnails (featured images) and wordpress generated title tags
 *
 * Action Hook: after_setup_theme
 */
function sting_add_theme_support() {
	//allow wordpress generated stuff to use html5
	add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption'));
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'sting_add_theme_support' );

//register menu - enable a menu in appearance --> menu
/**
 * Tells wordpress that we have a menu and makes it our primary navigation menu
 *
 * Action Hook: init
 */
function sting_register_menu() {
	register_nav_menu('primary', 'Primary Navigation');
}
add_action('init', 'sting_register_menu');

/**
 * Generates the menu structure for mobile devices
 * 
 * Probably called from header.php
 */
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
/**
 * Makes it such that only the category designated for homepage use appears on the homepage
 *
 * Action Hook: pre_get_posts
 * @param $query the query we are modifying
 */
function sting_homepage_category( $query ) {
    if ( $query->is_home() && $query->is_main_query() && !is_date()) {
		$admin_options = get_option('sting_admin_options');
		$id = intval($admin_options['homepage_cat_input_box']);
        $query->set( 'cat', $id);//make this the correct id
    }
}
add_action( 'pre_get_posts', 'sting_homepage_category' );

/**
 * Custom comparator to sort shows by date and time
 *
 * Used by the schedule page
 * @param $show1 - the first show
 * @param $show2 - the second show
 */
function sting_compare_shows_by_date_time($show1, $show2) {
	//error_log($show1 -> post_title . "          " . $show2 -> post_title);
	//echo $show1 -> post_title . "          " . $show2 -> post_title.'<br>';
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
		//error_log($stime1."          ".$stime2);
		//echo $stime1."          ".$stime2;
		$s_unix_time1 = strtotime($stime1);
		$s_unix_time2 = strtotime($stime2);
		$hrs1 = date('H', $s_unix_time1);
		$hrs2 = date('H', $s_unix_time2);
		
		$min1 = date('i', $s_unix_time1);
		$min2 = date('i', $s_unix_time2);
		//error_log($hrs1.":".$min1.'          '.$hrs2.":".$min2);
		//echo $hrs1.":".$min1.'          '.$hrs2.":".$min2;
		if ($hrs1 < $hrs2) {
			//echo $show1 -> post_title.'<'.$show2 -> post_title;
			//error_log($show1 -> post_title.'<'.$show2 -> post_title);
			//echo "<br><br>";
			return -1;
		} else if ($hrs1 > $hrs2) {
			//echo $show1 -> post_title.'>'.$show2 -> post_title;
			//error_log($show1 -> post_title.'>'.$show2 -> post_title);
			//echo "<br><br>";
			return 1;
		} else {
			if ($min1 < $min2) {
				//echo $show1 -> post_title.'<'.$show2 -> post_title;
				//error_log($show1 -> post_title.'<'.$show2 -> post_title);
				//echo "<br><br>";
				return -1;
			} else if ($min1 > $min2) {
				//echo $show1 -> post_title.'>'.$show2 -> post_title;
				//error_log($show1 -> post_title.'>'.$show2 -> post_title);
				//echo "<br><br>";
				return 1;
			} else {
				//echo $show1 -> post_title.'='.$show2 -> post_title;
				//error_log($show1 -> post_title.'='.$show2 -> post_title);
				//echo "<br><br>";
				return 0;
			}
		}
	}
	return 0;
}

/**
 * Actually outputs the show schedule
 * Called once per day of programming (so 7 times per load * 2 for non-mobile vs. mobile)
 * @param $day_of_week (which day it is)
 * @param $index where we are in the list of shows
 * @param $child_pages array of shows (used to be children of the schedule page before becoming their own post type)
 * @param $num_pages size of the array
 * @param $isMobile whether to display the mobile layout or the desktop one
 */
function sting_display_show_schedule($day_of_week, $index, $child_pages, $num_pages, $isMobile = false) {
	global $post;//Will be modified later on, then reverted to what it was. If we don't do this, we will break wordpress
	//Post refers to the current post. We temporarily use it to refer to another post, and then revert it.
	if ($num_pages == 0) {
		echo '<div class="post row">';
		echo '<div class="large-4 medium-4 columns">';
		echo 'No shows on '.ucfirst($day_of_week);
		echo '</div></div>';
	}
	
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
/**
 *
 * Displays the date and time for the show
 * @param $current_page the page to get the information from (WP_Post object)
 */
function displayShowTime($current_page) {
	echo '<div class="large-4 medium-4 columns show-time">';
	echo get_field('start_time', $current_page -> ID).' - '.get_field('end_time', $current_page -> ID);
	echo '</div>';
}
/**
 * Displays the show title, putting it in an h3 if it is on mobile devices
 * @param $current_page the page to get the information from (WP_Post object)
 * @param $isMobile whether or not we are on a mobile device
 */
function displayShowTitle($current_page, $isMobile) {
	echo '<div class="large-4 medium-4 columns show-title">';
	echo $isMobile == true ? '<h3>' : '';
	echo '<a href="'.get_permalink($current_page -> ID).'">'.$current_page -> post_title;
	echo '</a>';
	echo $isMobile == true ? '</h3>' : '';
	echo '</div>';
}
/**
 * Prints the show info (used for debugging)
 * @param $shows the list of shows to print
 */
function print_shows($shows) {
	foreach($shows as $show) {
		$to_print = 'Show '.$show -> ID .' '. $show-> post_title .' '. $show->post_date.' '.get_field('day', $show -> ID).' '.get_field('start_time', $show -> ID, true).' '.get_field('end_time', $show -> ID, true);
		error_log($to_print.'\r\n');
		echo $to_print.'<br />';
	}
}

$show_type = 'sting_shows';
/**
 * Registers the custom post type for shows
 *
 * Action Hook: init
 */
function sting_create_show_type() {
	//global variable to keep track of the type
	global $show_type;
	$args = array(
		'description'         => 'Sting Shows',//human readable description
		//human readable labels
		'labels'              => array('name' => 'Shows', 'singular_name' => 'Show', 'add_new_item' => 'Add New Show', 'edit_item' => 'Edit Show', 'new_item' => 'New Show', 'view_item' => 'View Show Page', 'search_items' => 'Search Shows', 'not_found' => 'No shows found', 'not_found_in_trash' => 'No shows found in the Trash'),
		//supports a title, the standard post editor, authors, excerpts, custom fields and revisions
		'supports'            => array('title', 'editor', 'author', 'excerpt', 'custom-fields', 'revisions'), // removed - I (Teddy) don't think we want to have/ are going to have comments on shows
		//can be put in categories and given tags
		'taxonomies'          => array( 'category', 'post_tag' ),
		//see docs, needs to be true to be findable in the backend
		'public'              => true,
		//Position in admin menu
		'menu_position'       => 5,
		//allows it to have an archive page (archive-sting_shows.php)
		'has_archive'         => true,
		//'has_archive'         => true,///////DO WE WANT THIS????  NO @Teddy - Change as of before launch we do - this will allow people to see shows that are no longer on the air @ Teddy, implemented Dec. 2015
		'capability_type'     => 'show',//'page', - lets us define special permissions for shows
		'map_meta_cap'		  => true,
		//menu icon
		'menu_icon'			  => 'dashicons-microphone',
		//makes sure the urls have /shows/, not /sting_shows
		'rewrite'			  => array('slug' => 'shows', 'pages' => true),
	);
	register_post_type( $show_type, $args );
//'capabilities'		  => array('edit_post' => 'edit_show', 'read_post' => 'read_show', 'delete_post' => 'delete_show', 'edit_others_posts' => 'edit_others_shows', 'publish_posts' => 'publish_shows', 'read_private_posts' => 'read_private_shows', 'read' => 'read', 'delete_posts' => 'delete_shows', 'delete_private_posts' => 'delete_private_shows', 'delete_published_posts' => 'delete_published_shows', 'edit_private_posts' => 'edit_private_shows', 'edit_published_posts' => 'edit_published_shows', 'delete_others_posts' => 'delete_others_shows'),	
}
add_action( 'init', 'sting_create_show_type' );

/**
 * Checks if there is a header image defined for the post, otherwise uses the default
 *
 */
function sting_get_header_image($specified_id = null) {
	global $admin_options;
	
	$id = ($specified_id == null) ? $id : $specified_id;
	
	if ($id != 0 && get_field('header-image', $id) != '') {
		return get_field('header-image', $id);
	} else {
		return $admin_options['sting_header_image_input_box'];
	}
	//echo get_template_directory_uri().'/images/studio.jpg';
}

$was_on_air = false;
/**
 * Logic to determine if a show was on the air before the update
 *
 * Used to determine if we need to update google calendar.
 * Occurs before sting_push_show_time_to_gcal, and sets variable that is used by that method
 *
 * Action Hook: save_post
 * @param $post_id the id of the current post
 * @param $post_obj the current post object
 * @param updated unused
 */
function check_if_show_is_on_the_air($post_id, $post_obj, $updated) {
	global $was_on_air;
	$on_air_field_val = get_field('show_on_air', $post_id);
	$was_on_air = $on_air_field_val;
	error_log('test_acf '.var_export($on_air_field_val, true).' priority=0');
}
add_action('save_post', 'check_if_show_is_on_the_air', 0, 3);

/**
 * Pushes show information to google calendar if necessary
 * Contians logic to determine whether or not to push the information
 * @param $post_id the id of the current post
 * @param $post_obj the current post object
 * @param updated unused
 */
function sting_push_show_time_to_gcal($post_id, $post_obj, $updated) {
	global $show_type;
	global $was_on_air;
	$current_on_air_field_val = get_field('show_on_air', $post_id);
	
	$create_event = !$was_on_air && get_field('show_on_air', $post_id);
	$end_event = $was_on_air && !get_field('show_on_air', $post_id);
	$do_nothing = $was_on_air == get_field('show_on_air', $post_id);
	$created = $post_obj -> post_date;
	$modified = $post_obj -> post_modified;
	//error_log('post created '.$created);
	//error_log('post modified '.$modified);
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
/**
 * Part of the google calendar initialization process (the rest can be found in /admin/settings/calendar-push.php)
 * Based on google developer documentation
 */
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
/**
 * Sends the current show data to browsers when requested by ajax
 * USes google calendar to determine which show is on now
 */
function send_now_playing_data() {
	//var_dump('send_now_playing_data');
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
	$current_show = $show_list[0];//we only should have one show at a time anyway
	
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
	$artist = get_dashboard_widget_option($sting_widget_id, $sting_artist_field_name);
	$songTitle = get_dashboard_widget_option($sting_widget_id, $sting_title_field_name);
	
	error_log(strpos($artist, '\\'));
	error_log(strpos($artist, "\'"));
	error_log(strpos($artist, "'"));
	
	while(stripos($artist, '\\')) {
		$indexSlash = stripos($artist, '\\');
		$firstPart = substr($artist, 0, $indexSlash);
		$lastPart = substr($artist, $indexSlash + 1, strlen($artist));
		error_log($firstPart);
		error_log($lastPart);
		$artist = $firstPart.$lastPart;
	}
	$toSend = array(
		$sting_artist_field_name	=>	$artist,
		$sting_title_field_name 	=>	$songTitle,
		$sting_show_title			=> $title,
		$sting_show_page_url		=> $url,
	);
	//error_log(var_export($toSend, true));
	//error_log(get_dashboard_widget_option($sting_widget_id, $sting_artist_field_name));
	//error_log(get_dashboard_widget_option($sting_widget_id, $sting_title_field_name));
	echo json_encode($toSend);
	wp_die();//ajax so we call wp_die when it is done
}
/**
 *
 * Determines whether or not to show the admin message (the sting will be down for updates from wxy to abc)
 *
 */
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
	$toReturn = "";
		if ($endtime > $nowtime) {
			$toReturn = $admin_options['sting_admin_message_input_box'].' from '.date('m/d/y h:i A',$starttime).' until '.date('m/d/y h:i A',$endtime);
		}
	//}
	//error_log($toReturn);
	return $toReturn;
}
/**
 * Modifies the show query to allow for our own page variable
 *
 * Code to allow us to have multiple pages of posts on show pages.
 * Because we use a custom query, wordpress will not allow us to use the built in page functionality
 * So, we have to write at least part of it ourselves.
 *
 * Filter: query_vars
 * @param $vars the current query variables. We add pg to them
 */
function sting_modify_query_show_page($vars) {
	array_push($vars, 'pg');
	//error_log(var_export($vars, true));
	return $vars;
}
add_filter( 'query_vars', 'sting_modify_query_show_page' , 10, 1 );
/**
 * Code for RSS feeds for the shows - these get the posts from both hosts (and deal with 1 DJ 2 Shows)
 */
function sting_add_show_rss_feed() {
	//error_log('adding_rss_feed');
	add_feed('show_feed', 'sting_generate_show_rss_feed');
}
add_action('init', 'sting_add_show_rss_feed');

/**
 * Uses custom template for the rss feed for shows
 */
function sting_generate_show_rss_feed() {
	//error_log('generate_rss_feed');
	get_template_part('subTemplates/rss/rss', 'show');
}
/**
 * Adds relevent rss feeds to <head>
 *
 * Action Hook: wp_head
 * Removes feed_links_extra as we need to do it ourselves
 */
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

/**
 * Comparator for sorting shows by title alphabetically
 * Used for the shows page
 * @param show1 first show
 * @param show2 second show
 */
function sting_compare_title($show1, $show2) {
	$title1 = $show1 -> post_title;
	$title2 = $show2 -> post_title;
	
	return strcmp ($title1, $title2);
}

/**
 * Used on the individual show pages to show when they are playing
 */
function sting_format_show_schedule() {
		$show_schedule = '';
	if (get_field('show_on_air', $show -> ID)) {
		$show_schedule .= ucfirst(get_field('day', $show -> ID)).'s';
		$show_schedule .= ' at ';
		$show_schedule .= get_field('start_time', $show -> ID);
	} else {
		$show_schedule .= 'This show is not on the air';
	}
	return $show_schedule;
}
/*function sting_capitalize_title($title, $sep) {
	$upper_title = substr($title, 0, strpos($title, $sep));
	$subtitle = substr($title, strpos($title, $sep), strlen($title));
	$upper_title = strtoupper($upper_title);
	return $upper_title.$subtitle;
}
add_filter( 'wp_title', 'sting_capitalize_title', 10, 2 );*/
?>