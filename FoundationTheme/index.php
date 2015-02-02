<?php get_header() ?>

<!-- Loop stuff -->

<?php
//$max_col_count = 12;
//$current_col_count = 0;
//$num_cols = 4;//theoretically will be read from post meta probably
	if (have_posts()) {
		while(have_posts()) {
			the_post();//iterate to the next post (this is kindof a foreach loop except using an iterator)
			
			get_template_part('postContent',''); ?>
			
		<?php
		}//end while
	}//endif
?>
</div>
<div class="row">
			<div style="float:left;" class="medium-2 columns"><?php previous_posts_link('← Preivous Page') ?></div>
			<div style="float:right;" class="medium-2 columns"><?php next_posts_link('Next Page →'); ?></div>
</div>
<!-- End loop stuff -->

<?php get_footer() ?>