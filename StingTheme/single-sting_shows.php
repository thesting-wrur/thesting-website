<?php get_header();
/*
Template Name: Show Page
*/
?>
<!--start single-sting_show.php -->
<div class="row show-description">
<?php
	echo apply_filters('the_content',$post -> post_content);
?>
</div>
<?php //show's news feed
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
<div class="row">
		<div class="large-4 medium-6 small-12 columns"><?php previous_posts_link('Preivous Page') ?></div>
		<div class="large-4 medium-6 small-12 columns"><?php next_posts_link('Next Page'); ?></div>
	</div>
<!--end single-sting_show.php -->
<?php get_footer() ?>