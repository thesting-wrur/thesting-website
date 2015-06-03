<?php get_header();
/*
Template Name: Show Page
*/
?>
<!--start single-sting_show.php -->
<div class="row show-description">
<?php
	if ($post -> post_content != '') {
		echo apply_filters('the_content',$post -> post_content);
		//line between description and show's feed
		echo '<hr />';
	}
?>
</div>
<?php //show's news feed
$max_col = 0;//Show so only have 1 column
//Allows for both authors
$coauthors = get_coauthors($post -> ID);
$author_id = '';//'author=';
foreach($coauthors as $author) {
	$author_id .= $author -> ID;
	$author_id .= ',';
}
$author_id = substr($author_id, 0, strlen($author_id) - 1);

/*//First check if the author has multiple shows
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
*/
$categories = get_the_category()[0] -> term_id;
$categories .= ', -'.get_option('sting_admin_options')['homepage_cat_input_box'];
$paged = (get_query_var('pg')) ? get_query_var('pg') : 1;
error_log($paged);
//error_log($categories);
//var_dump($categories);
$show_id = get_queried_object_id();
$sting_query = new WP_Query(array('author' => $author_id, 'cat' => $categories, 'paged' => $paged));
get_template_part('loop');

?>
<div class="row">
<!-- The posts are being done with a special query so we need to do more work to get them to work. See sting_modify_query_show_page in functions.php-->
		<div class="large-4 medium-6 small-12 columns"><?php if ($paged != 1) {echo '<a href="'.get_permalink($show_id).'?pg='.($paged - 1).'">Previous Page</a>';}?></div>
		<div class="large-4 medium-6 small-12 columns"><?php if ($paged < $sting_query -> max_num_pages) {echo '<a href="'.get_permalink($show_id).'?pg='.($paged + 1).'">Next Page</a>';} ?></div>
</div>
<!--end single-sting_show.php -->
<?php get_footer() ?>