<?php get_header() ?>
<!--single.php -->
<?php
	if (have_posts()) {
		while(have_posts()) {
		the_post();
		?>
			<div class="row">
				<!--Post content. See banner.php for title-->
				<?php //edit_post_link( $link, $before, $after, $id ); ?> 
				<div class="post-meta">Posted on: <?php the_time(get_option('date_format'));?> by <?php the_author_posts_link();?></div>
				<?php the_content(); ?>
				<hr class="post-after-content">
				<div><?php the_tags(' | ');?></div>
				<!--<div class="left"><php previous_post_link(); ?></div>
				<div class="right"><php next_post_link(); ?></div>-->
				<div>
					<?php
						/*global $post;
						if (post_type_supports(get_post_type($post), 'comments')) {
						echo 'Comments ';
						comments_template();
						}*/
					?>
				</div>
			</div>
<?php
		}//end while
	}//endif
?>
<!--end single.php stuff -->
<?php get_footer() ?>