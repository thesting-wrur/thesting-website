<?php get_header();
/*
Template Name: Schedule Page
*/
?>
<!--schedule.php -->
<div class="row">
<h2>Schedule</h2>
<?php
$args = array(
	'posts_per_page'   => 100,
	'post_type'        => 'page',
	'post_parent'      => $post -> ID,
	'post_status'      => 'publish',
);
$child_pages = get_posts( $args ); 
?>

<?php
	usort($child_pages, "sting_compare_shows_by_date_time");
	var_dump($child_pages);
?>
</div>
<!--end schedule.php stuff -->
<?php get_footer() ?>