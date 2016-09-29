<?php
/*
 *Template Name: Temporary Query Page
 */
global $show_type;
?>
<?php
$posts_to_get = get_option('sting_admin_options')['num_shows_input_box'];
$args = array(
	'posts_per_page'	=> $posts_to_get,
	'post_type'			=> $show_type,
	'post_status'		=> 'publish',
	'meta_key'			=> 'show_on_air',
	'meta_value'		=> true,
);
$child_pages = get_posts( $args ); 
usort($child_pages, "sting_compare_shows_by_date_time");
//var_dump($child_pages);
$days = array('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
$now = new DateTime('now', new DateTimeZone('America/New_York'));
$today = $days[intval($now->format("w"))];

$shows = array();
foreach($child_pages as $page) {
	$day = get_field('day', $page -> ID);
	if ($day != $_GET['day']) {
		continue;
	}
	$title = $page -> post_title;
	echo $title;
	echo '<br>';
	$start_time = get_field('start_time', $page -> ID, false);
	echo date('H-i', strtotime($start_time));
	echo '<br>';
	$end_time = get_field('end_time', $page -> ID, false);
	echo date('H-i', strtotime($end_time));
	echo '<br>';
}
?>
