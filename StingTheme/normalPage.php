<?php get_header() ?>
<!--normalPage.php -->
<?php
	if (have_posts()) {
		while(have_posts()) {
		the_post();
		//$page = get_post(get_queried_object_id());
		?>
		<div class="row">
			<article class="full-12 columns">
				<h2><?php the_title();//echo $page -> post_title;?></h2>
				<?php the_content();//echo $page -> post_content; ?>
			</article>
		</div>
	<?php
		}
	}?>
<!--end normalPage.php stuff -->
<?php get_footer() ?>