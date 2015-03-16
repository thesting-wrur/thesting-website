<?php get_header(); ?>
<!--search.php -->
<div class="row">
<?php get_search_form();
get_template_part('loop','');?>
</div>
<div class="row">
	<div class="large-4 medium-6 small-12 columns"><?php previous_posts_link('Preivous Page') ?></div>
	<div class="large-4 medium-6 small-12 columns"><?php next_posts_link('Next Page'); ?></div>
</div>
<!--end search.php -->
<?php get_footer(); ?>