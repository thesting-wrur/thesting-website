<?php get_header();
/*
Template Name: Show Page
*/
?>
<!--start single-sting_show.php -->
Show Template
<?php
$coauthors = get_coauthors($post -> ID);
$author_id = 'author=';
foreach($coauthors as $author) {
	$author_id .= $author -> ID;
	$author_id .= ',';
}
$author_id = substr($author_id, 0, strlen($author_id) - 1);
$sting_query = new WP_Query($author_id);
get_template_part('loop');
?>
<!--end single-sting_show.php -->
<?php get_footer() ?>