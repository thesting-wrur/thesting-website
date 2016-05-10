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
				<p class="post-meta">Posted on <?php the_time(get_option('date_format'));?> by <?php coauthors_posts_links(', ', ' and ', '', ''); ?></p>
				<?php the_content(); ?>
				<hr class="post-after-content">
				<div><?php the_tags(' | ');?></div>
				<!--<div class="left"><php previous_post_link(); ?></div>
				<div class="right"><php next_post_link(); ?></div>-->
				<?php
						/*global $post;
						if (post_type_supports(get_post_type($post), 'comments')) {
						echo 'Comments ';
						comments_template();
						}*/
					?>
				<div class="sting-social-buttons-post">
					<a href="https://twitter.com/share" class="twitter-share-button" data-via="WRURtheSting" data-count="none" data-dnt="true">Tweet</a>
					|<!-- makes it look better -->
					<div class="fb-share-button" data-href="<?php the_permalink();?>" data-layout="button"></div>
				</div>
			</div>
<?php
		}//end while
	}//endif
?>
<!--end single.php stuff -->
<?php get_footer() ?>