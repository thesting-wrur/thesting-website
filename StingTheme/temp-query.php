<?php
/*
 *Template Name: Temporary Query Page
 */
 
 $posts_to_get = get_option('sting_admin_options')['num_shows_input_box'];
	$args = array(	'post_type' => $show_type,
					'posts_per_page'	=> $posts_to_get,
					'meta_key'			=> 'show_on_air',
					'meta_value'		=> true,
			);
	$shows = get_posts($args);
	
	var_dump($shows);
	echo '<hr>';
	
	$current_user_shows = array();
	foreach ($shows as $show) {
		$coauthors = get_coauthors($show -> ID);
		//var_dump(var_export($coauthors, true));
		foreach($coauthors as $author) {
			//var_dump('Author: '.$author->ID.' Show:'.$show->post_title);
			if ($author->ID == $user_id) {
				array_push($current_user_shows, $show);
			}
		}
	}
	var_dump($current_user_shows);
	foreach ($current_user_shows as $show) {
		$starttime = strtotime(get_field('start_time', $show -> ID, true));
		$endtime = strtotime(get_field('end_time', $show -> ID, true));
		$now = new DateTime('now', new DateTimeZone('America/New_York'));
		$nowtime = strtotime($now->format('G:i'));
		var_dump($show->post_title);
		//var_dump('start time: '.$starttime.' end time: '.$endtime.' now: '.$nowtime);
		//var_dump('start time < now? '.(($starttime < $nowtime)? 'true' : 'false'));
		//var_dump('end time > now? '.(($endtime > $nowtime)? 'true' : 'false'));
		
		$day = get_field('day', $show->ID);
		$today = $now->format('l');
		//var_dump('show day '.$day.' today '.$today);
		
		if (strtolower($day) == strtolower($today)) {
			if ($starttime < $nowtime ) {
				var_dump('start time < now? '.(($starttime < $nowtime)? 'true' : 'false'));
				if ($endtime > $nowtime) {
					var_dump('end time > now? '.(($endtime > $nowtime)? 'true' : 'false'));
					var_dump('returning true');
					//var_dump(time());
					//return true;
					var_dump(true);
				}
			}
		}
	}
	var_dump(false);
	//var_dump(time());
	//return false;
	
	echo '<hr>';
	echo '<hr>';
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
	var_dump($shows);
	echo '<hr>';
	var_dump($shows_list);
	echo '<hr>';
	if ($current_show != null) {
	$id = $current_show->getId();
	
	$args = array(
			'post_type' => $show_type,
			'meta_key' => $gcal_event_id_key,
			'meta_value' => $id,
		);
	$show_posts = get_posts($args);
	echo '<hr>';
	var_dump($show_posts);
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
	echo '<hr>';
	var_dump($toSend);
	
	?>