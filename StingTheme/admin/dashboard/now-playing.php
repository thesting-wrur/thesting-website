<?php
add_action('wp_dashboard_setup', 'sting_add_now_playing_widget');

$sting_widget_id = 'sting_now_playing_widget';
$sting_artist_field_name= 'artist';
$sting_title_field_name = 'title';
$sting_show_title = 'showTitle';
$sting_show_page_url = 'showURL';
$action = 'Sting-Update-Now-Playing-Data';
function sting_add_now_playing_widget() {
	global $sting_widget_id;
	wp_add_dashboard_widget(
		$sting_widget_id,
		'Now Playing',
		'sting_setup_now_playing_widget'
	);
}
//setup script for processing and the client end of ajax
function sting_setup_now_playing_script($hook) {
	if ($hook == 'index.php') {
		wp_register_script('sting_now_playing_script', get_template_directory_uri().'/admin/dashboard/now-playing.js');
		wp_enqueue_script('sting_now_playing_script');
	}
}
add_action('admin_enqueue_scripts', 'sting_setup_now_playing_script');

//setup ajax reciever
add_action( 'wp_ajax_now_playing', 'sting_receive_now_playing_data' );
function sting_receive_now_playing_data() {
	global $action;
	//check_ajax_referer($action, '_wpnonce', true);//Checks the wp nonce for validity
	
	global $sting_widget_id;
	$post_data = $_POST['data'];
	//error_log(var_export($post_data, true));
	//echo var_export($post_data, true);
	//error_log($post_data[0][1]);
	//echo $post_data[0][1];
	$data = Array();
	foreach ($post_data as $datum) {
		$data[$datum[0]] = wp_strip_all_tags($datum[1]);
	}
	//error_log($data['title']);
	
	update_dashboard_widget_options( $sting_widget_id, $data);///////////WE NEED TO SANITIZE THE DATA BEFORE SENDING IT STRAIGHT TO THE DATABASE!!!!
	
	$data['success'] = 'true';
	echo json_encode($data);
	//echo 'hi';
	wp_die();
}

function sting_setup_now_playing_widget() {
	global $sting_artist_field_name;
	global $sting_title_field_name;
	global $action;
	?>
	Current Show: <span class="current-show"></span>
	
	<?php
	if (sting_check_homepage_push_permissions()) {//start check permissions on whether or not the current user can push the currently playing song to the homepage
	?>
	<form enctype="multipart/form-data">
	<?php
		wp_nonce_field($action, '_wpnonce', true);
	?>
		<table>
			<tr>
				<th>Title:</th>
				<td><input id="title" onkeypress="checkSubmitForm(event);" class="now-playing-form" type="text" name="<?php echo $sting_title_field_name; ?>"></td>
			</tr>
			<tr>
				<th>Artist:</th>
				<td><input id="artist" onkeypress="checkSubmitForm(event);" class="now-playing-form" type="text" name="<?php echo $sting_artist_field_name; ?>"></td>
			</tr>
		</table>
		<div>
			<input type="button" onclick="submitForm();" class="button button-primary" value="Update Now Playing"></input>
		</div>
	</form>
	<?php
	} else {//end check permissions on whether or not the current user can push the currently playing song to the homepage
		echo '<br>';//add line if not able to push to homepage
	}
	//$nowPlaying = get_dashboard_widget_options('sting_now_playing_widget');
	echo 'Currently Playing: <span id="title-field"> by </span><span id="artist-field"></span>';
}


function sting_check_homepage_push_permissions() {
	global $show_type;
	//var_dump(time());
	$user_id = get_current_user_id();
	
	if (current_user_can('edit_dashboard')) {//allows admins to push the current song to the homepage
		return true;
	}
	$posts_to_get = get_option('sting_admin_options')['num_shows_input_box'];
	$args = array(	'post_type' => $show_type,
					'posts_per_page'	=> $posts_to_get,
					'meta_key'			=> 'show_on_air',
					'meta_value'		=> true,
			);
	$shows = get_posts($args);
	$current_user_shows = array();
	foreach ($shows as $show) {
		$coauthors = get_coauthors($show -> ID);
		//error_log(var_export($coauthors, true));
		foreach($coauthors as $author) {
			//error_log('Author: '.$author->ID.' Show:'.$show->post_title);
			if ($author->ID == $user_id) {
				array_push($current_user_shows, $show);
			}
		}
	}
	//error_log(var_export($current_user_shows, true));
	foreach ($current_user_shows as $show) {
		$starttime = strtotime(get_field('start_time', $show -> ID, true));
		$endtime = strtotime(get_field('end_time', $show -> ID, true));
		$now = new DateTime('now', new DateTimeZone('America/New_York'));
		$nowtime = strtotime($now->format('G:i'));
		error_log($show->post_title);
		//var_dump('start time: '.$starttime.' end time: '.$endtime.' now: '.$nowtime);
		error_log('start time < now? '.(($starttime < $nowtime)? 'true' : 'false'));
		error_log('end time > now? '.(($endtime > $nowtime)? 'true' : 'false'));
		
		$day = get_field('day', $show->ID);
		$today = $now->format('l');
		//var_dump('show day '.$day.' today '.$today);
		
		if (strtolower($day) == strtolower($today)) {
			if ($starttime < $nowtime ) {
				error_log('start time < now? '.(($starttime < $nowtime)? 'true' : 'false'));
				if ($endtime > $nowtime) {
					error_log('end time > now? '.(($endtime > $nowtime)? 'true' : 'false'));
					error_log('returning true');
					//var_dump(time());
					return true;
				}
			}
		}
	}
	//var_dump(time());
	return false;
}
/**
 * Gets one specific option for the specified widget.
 * Taken from http://codex.wordpress.org/Dashboard_Widgets_API#Widget_Options
 * @param $widget_id
 * @param $option
 * @param null $default
 *
 * @return string
 */
function get_dashboard_widget_option( $widget_id, $option, $default=NULL ) {

    $opts = get_dashboard_widget_options($widget_id);

    //If widget opts dont exist, return false
    if ( ! $opts )
        return false;

    //Otherwise fetch the option or use default
    if ( isset( $opts[$option] ) && ! empty($opts[$option]) )
        return $opts[$option];
    else
        return ( isset($default) ) ? $default : false;

}

/**
 * Gets all widget options, or only options for a specified widget if a widget id is provided.
 *
 * @param string $widget_id Optional. If provided, will only get options for that widget.
 * @return array An associative array
 */
function get_dashboard_widget_options( $widget_id='' )
{
    //Fetch ALL dashboard widget options from the db...
    $opts = get_option( 'dashboard_widget_options' );

    //If no widget is specified, return everything
    if ( empty( $widget_id ) )
        return $opts;

    //If we request a widget and it exists, return it
    if ( isset( $opts[$widget_id] ) )
        return $opts[$widget_id];

    //Something went wrong...
    return false;
}

/**
 * Saves an array of options for a single dashboard widget to the database.
 * Can also be used to define default values for a widget.
 *
 * @param string $widget_id The name of the widget being updated
 * @param array $args An associative array of options being saved.
 * @param bool $add_only Set to true if you don't want to override any existing options.
 */
function update_dashboard_widget_options( $widget_id , $args=array(), $add_only=false )
{
    //Fetch ALL dashboard widget options from the db...
    $opts = get_option( 'dashboard_widget_options' );

    //Get just our widget's options, or set empty array
    $w_opts = ( isset( $opts[$widget_id] ) ) ? $opts[$widget_id] : array();

    if ( $add_only ) {
        //Flesh out any missing options (existing ones overwrite new ones)
        $opts[$widget_id] = array_merge($args,$w_opts);
    }
    else {
        //Merge new options with existing ones, and add it back to the widgets array
        $opts[$widget_id] = array_merge($w_opts,$args);
    }

    //Save the entire widgets array back to the db
    return update_option('dashboard_widget_options', $opts);
}
?>