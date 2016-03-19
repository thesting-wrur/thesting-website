<?php get_header();
/**
 * Old normal page template. All the content has been moved into page.php due to shows now
 * being their own post type and as a result, page.php no longer has to distinguish between
 * children of the schedule page and normal pages.
 *
 */
?>
<!--normalPage.php -->
<?php
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
<!--end normalPage.php stuff -->
<?php get_footer() ?>