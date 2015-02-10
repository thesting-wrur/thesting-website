<?php get_header() ?>
<!--page.php -->
<?php
$page = get_post(get_queried_object_id()); ?>
<div class="row">
	<article class="full-12 columns">
		<h2><?php echo $page -> post_title;?></h2>
		<?php echo $page -> post_content; ?>
	</article>
</div>
<!--end page.php stuff -->
<?php get_footer() ?>