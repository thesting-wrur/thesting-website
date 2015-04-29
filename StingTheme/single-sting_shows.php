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

//Allows for both authors
$coauthors = get_coauthors($post -> ID);
$author_id = '';//'author=';
foreach($coauthors as $author) {
	$author_id .= $author -> ID;
	$author_id .= ',';
}
$author_id = substr($author_id, 0, strlen($author_id) - 1);

//First check if the author has multiple shows
$categories = "";
$title = get_the_title(get_the_ID());
//error_log($title);
foreach($coauthors as $author) {
	$category_query = new WP_Query(array('author' => $author -> ID, 'post_type' => $show_type));
	if ($category_query -> have_posts()) {
		while ($category_query -> have_posts()) {
			$category_query -> the_post();
			$cats = get_categories();
			foreach ($cats as $cat) {
				if ($cat -> cat_name == $title) {
					if (stripos($categories, $cat -> cat_ID) === false) {
						$categories .= $cat -> cat_ID . ',';
					}
					//error_log($cat -> cat_name .' ' . $cat -> cat_ID .$author -> user_nicename);
				}
			}
		}
	}
}
wp_reset_postdata();
//error_log(var_export($categories, true));
error_log($categories);
$sting_query = new WP_Query(array('author' => $author_id, 'cat' => $categories));
get_template_part('loop');
?>
<div class="row">
		<div class="large-4 medium-6 small-12 columns"><?php previous_posts_link('Preivous Page') ?></div>
		<div class="large-4 medium-6 small-12 columns"><?php next_posts_link('Next Page'); ?></div>
	</div>
<!--end single-sting_show.php -->
<?php get_footer() ?>