<?php get_header();
/*
Template Name: Show Page
*/
?>
<!--start single-sting_show.php -->
<div class="row show-description">
<?php
	if ($post -> post_content != '') {//if there is content (otherwise we don't want the share this nor the horizontal line)
		echo apply_filters('the_content',$post -> post_content);
		
		?><div class="sting-social-buttons-post row">
					<a href="https://twitter.com/share" class="twitter-share-button" data-via="WRURtheSting" data-count="none" data-dnt="true">Tweet</a>
					|<!-- makes it look better -->
					<div class="fb-share-button" data-href="<?php the_permalink();?>" data-layout="button"></div>
		</div><?php
		
		//line between description and show's feed
		echo '<hr />';
	}
?>
</div>
<?php //show's news feed
$max_col = 0;//Show so only have 1 column
//Allows for both authors
$coauthors = get_coauthors($post -> ID);
/*$author_id = '';//'author=';
foreach($coauthors as $author) {
	$author_id .= $author -> ID;
	$author_id .= ',';
}
$author_id = substr($author_id, 0, strlen($author_id) - 1);*/
$categories = get_the_category()[0] -> term_id;
$categories .= ', -'.get_option('sting_admin_options')['homepage_cat_input_box'];
$paged = (get_query_var('pg')) ? get_query_var('pg') : 1;
$show_id = get_queried_object_id();
	
$authors = array();
foreach($coauthors as $author) {
	array_push($authors, $author -> user_login);
}
$author_info = array(
			array(
				'taxonomy' 	=> 	'author',
				'field'		=>	'name',
				'terms'		=>	$authors,
				)
		);
global $show_type;
$query_args = array(
	'post_type'	=> 'post',
	//'author' => $author_id,
	'cat' => $categories,
	'paged' => $paged,
	'tax_query' => $author_info,
	);		
$sting_query = new WP_Query($query_args);
//error_log(var_export($query_args, true));

get_template_part('loop');

?>
<div class="row">
<!-- The posts are being done with a special query so we need to do more work to get them to work. See sting_modify_query_show_page in functions.php-->
		<div class="large-4 medium-6 small-12 columns"><?php if ($paged != 1) {echo '<a href="'.get_permalink($show_id).'?pg='.($paged - 1).'">Previous Page</a>';}?></div>
		<div class="large-4 medium-6 small-12 columns"><?php if ($paged < $sting_query -> max_num_pages) {echo '<a href="'.get_permalink($show_id).'?pg='.($paged + 1).'">Next Page</a>';} ?></div>
</div>
<!--end single-sting_show.php -->
<?php get_footer() ?>