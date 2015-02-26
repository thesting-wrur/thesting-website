<?php
get_header();
//banner put in in the header.php file
?>
<!-- Loop stuff -->
<hr style="border-color: transparent;">
<?php get_template_part('loop','');?>
	</div>
		<div class="row">
			<div class="large-4 medium-6 small-12 columns"><?php previous_posts_link('Preivous Page') ?></div>
			<div class="large-4 medium-6 small-12 columns"><?php next_posts_link('Next Page'); ?></div>
		</div>
</div>
<!-- End loop stuff -->
<div class="space-filler"></div>
<?php get_footer() ?>