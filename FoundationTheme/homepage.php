<?php
/*
 * Template Name: Home Media Feed
 */
 get_header();
$args = array('category' => '61');
//$args = array('category' => '3');
$wanted_posts = get_posts($args);
?>
<!-- Loop stuff -->
<?php
$max_col_count = 12;
$current_col_count = 0;
echo '<div class="row">';//start new row
	foreach($wanted_posts as $post):
		setup_postdata($post);//iterate to the next post (this is kindof a foreach loop except using an iterator)	
		$num_cols = 4;//theoretically will be read from post meta probably
	$tags = wp_get_post_tags(get_the_ID());
	//echo the_ID();
	foreach ($tags as $tag) {
		if ($tag -> name === 'full') {
			$num_cols = 12;
			break;
		} else if ($tag -> name === 'large'){
			$num_cols = 6;
			break;
		} else if ($tag -> name === 'medium') {
			$num_cols = 4;
			break;
		} else if ($tag -> name === 'small') {
			$num_cols = 3;
			break;
		}
	}		
			get_template_part('postContent',''); ?>
			
		<?php endforeach;
	if ($current_col_count != $max_col_count) {
		echo '</div>';//end row
	}
?>
</div>
<div class="row">
			<div style="float:left;" class="medium-2 columns"><?php previous_posts_link('? Preivous Page') ?></div>
			<div style="float:right;" class="medium-2 columns"><?php next_posts_link('Next Page ?'); ?></div>
</div>
<!-- End loop stuff -->

<?php get_footer() ?>
 ?>