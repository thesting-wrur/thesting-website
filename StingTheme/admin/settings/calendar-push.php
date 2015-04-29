<?php
$gcal_service;
$cal_options = get_option('sting_calendar_options');
$gcal_event_id_key = 'gcal_event_id';
function sting_setup_gcal() {
	global $gcal_service;
	global $cal_options;
	$client = setup_gcal_client();
	$gcal_service = new Google_Service_Calendar($client);
	//$authCode = '4/J03L5qcos2eJJvnhroH5AU6USHr5Td9aTUZ0fHF-J60.gmKOvBtELyUXgtL038sCVnvbPOl1mAI';
	// Exchange authorization code for access token
	//$accessToken = $client->authenticate($authCode);
	//$accessToken = '{"access_token":"ya29.PQGaxC1nNWwj4srKNM_YwLyhRndgL-1ZRwffmJbZYTth5V7R5CaMlazqbQnT3zD55MgqG329o3LzTg","token_type":"Bearer","expires_in":3600,"refresh_token":"1\/HcrZ1wZeJTgfIBJ8-t1AupJt7KNzvbjNRiyVEPLur3Q","created":1426918291}';
	//$refreshToken = '1/HcrZ1wZeJTgfIBJ8-t1AupJt7KNzvbjNRiyVEPLur3Q';
	//echo '<br>';
	//error_log($accessToken);
	//var_dump($accessToken);
	//echo '<br>';echo '<br>';echo '<br>';
	//error_log($refreshToken);
	//var_dump($refreshToken);
	//echo '<br>';
	/* $client->setAccessToken($accessToken);
	if ($client->isAccessTokenExpired()) {
		$client->refreshToken($refreshToken);
		$client->setAccessToken($client->getAccessToken());
	} */
	//echo '<br>';
	//error_log($accessToken);
	//var_dump($accessToken);
	//echo '<br>';
}
function sting_push_post_to_gcal($post_id, $post) {
	global $gcal_service;
	global $cal_options;
	global $gcal_event_id_key;
	$event = new Google_Service_Calendar_Event();
	
	$title = ($post -> post_title).' '.coauthors(',', ' and ', ' - ', '', false);
	$event->setSummary($title);
	$event->setLocation('WRUR');
	$event->setDescription(wp_strip_all_tags($post -> post_content), false);
	$ical_time = date('Ymd\THis', strtotime($cal_options['current_show_end_date_input_box'])).'Z';
	//var_dump($ical_time);
	error_log($ical_time.' icaltime');
	$event->setRecurrence(array('RRULE:FREQ=WEEKLY;UNTIL='.$ical_time));
	
	$days = array('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
	$current_day_of_week = intval(date('w'));
	$day_of_show = array_search(get_field('day', $post_id), $days);
	
	if ($day_of_show < $current_day_of_week) {
		$days_from_now = $day_of_show + 7 - $current_day_of_week;
	} else {
		$days_from_now = $day_of_show - $current_day_of_week;
	}
	//error_log($days_from_now.' days from now. day of show: '.$day_of_show.' today '.$current_day_of_week);
	$date_interval = new DateInterval('P'.$days_from_now.'D');
	//error_log(var_export($date_interval, true));
	//var_dump($date_interval);
	
	$start = new Google_Service_Calendar_EventDateTime();
	
	$starttime = get_field('start_time', $post_id, false);
	$formatted_start = sting_format_datetime($starttime, $date_interval);
	//var_dump($starttime);
	//error_log($starttime);
	//echo '<br>';
	//var_dump($formatted_start);
	//error_log($formatted_start);
	$start->setDateTime($formatted_start);
	$start->setTimeZone('America/New_York');
	$event->setStart($start);
	$end = new Google_Service_Calendar_EventDateTime();
	
	$endtime = get_field('end_time', $post_id, false);
	$formatted_end = sting_format_datetime($endtime, $date_interval);
	//echo '<br>';
	//var_dump($endtime);
	//error_log($endtime);
	//echo '<br>';
	//var_dump($formatted_end);
	//error_log($formatted_end);
	//$end->setDateTime('2015-03-21T10:00:00.000-04:00');
	$end->setDateTime($formatted_end);
	$end->setTimeZone('America/New_York');
	$event->setEnd($end);
	$calendarID = $cal_options['calendar_id_input_box'];
	//var_dump($cal_options);
	$createdEvent = $gcal_service->events->insert($calendarID, $event);
	
	update_post_meta($post_id, $gcal_event_id_key, $createdEvent->getId());
	error_log($createdEvent->getId());
}
function sting_format_datetime($timestamp, $date_interval) {
	$interval = DateInterval::createFromDateString('monday');
	//error_log(var_export($interval, true));
	$date = new DateTime('now', new DateTimeZone('America/New_York'));
	$date->setTimestamp($timestamp - $date->getOffset());
	$date->add($date_interval);
	//needed to change timezone properly	
	return $date->format(DateTime::ISO8601);
}
?>