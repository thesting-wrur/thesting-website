<?php
/*
 * Template Name: Home Media Feed
 */
 get_header();
//$args = array('category' => '61');
$args = array('category' => '3');
$wanted_posts = get_posts($args);
?>
<!-- Loop stuff -->
<?php
	foreach($wanted_posts as $post):
		setup_postdata($post);//iterate to the next post (this is kindof a foreach loop except using an iterator)	

		get_template_part('postContent','');
	endforeach;
?>
</div>
<div class="row">
			<div style="float:left;" class="medium-2 columns"><?php previous_posts_link('? Preivous Page') ?></div>
			<div style="float:right;" class="medium-2 columns"><?php next_posts_link('Next Page ?'); ?></div>
</div>
<!-- End loop stuff -->

<?php get_footer() ?>