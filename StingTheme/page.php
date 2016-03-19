<?php get_header() ?>
<!--page.php -->
<?php
/*
 * Default Page Template.
 * Used to include code for checking for children of the schedule page as shows, but with shows as their own post type,
 * that wasn't necessary so this is now just the code for any normal page (ie page without another template)
 */
	if (have_posts()) {//Loop for a page (there is only one query, but this makes stuff like the_content work)
		while(have_posts()) {
		the_post();
		//$page = get_post(get_queried_object_id());
		?>
		<div class="row">
			<article class="full-12 columns">
				<h2><?php //the_title();//echo $page -> post_title;?></h2>
				<?php the_content();//echo $page -> post_content; ?>
			</article>
		</div>
	<?php
		}
	}?>
<!--end page.php stuff -->
<?php get_footer() ?>