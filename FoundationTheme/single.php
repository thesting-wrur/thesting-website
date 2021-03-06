﻿<?php get_header() ?>
<!--single.php -->
<?php
	if (have_posts()) {
		while(have_posts()) {
		the_post();
		?>
			<div class="row">
				<!--Post content and title-->
				<h1><?php the_title()?></h1><?php edit_post_link( $link, $before, $after, $id ); ?> 
				<div>Date and Time<?php the_time(get_option('date_format'))?></div>
				<div>Category <?php the_category(',');?></div>
				<div>Tags <?php the_tags();?></div>
				<div>Author <?php the_author_posts_link();?></div>
				<hr>
				<?php the_content('Continue Reading'); ?>
				<hr>
				<div><?php previous_post_link() ?></div>
				<div><?php next_post_link() ?></div>
				<hr>
				<div>Comments <?php comments_template(); ?> </div>
			</div>
<?php
		}//end while
	}//endif
?>
<!--end single.php stuff -->
<?php get_footer() ?>