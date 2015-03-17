<?php get_header() ?>
<!--archive.php -->
<?php get_template_part('loop','');?>
<div class="row">
	<div class="large-4 medium-6 small-12 columns"><?php previous_posts_link('Preivous Page') ?></div>
	<div class="large-4 medium-6 small-12 columns"><?php next_posts_link('Next Page'); ?></div>
</div>
<!--end archive.php stuff -->
<?php get_footer() ?>