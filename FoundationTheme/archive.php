<?php get_header() ?>
<!--archive.php -->
<?php
if (is_category()) {
	echo 'Posts in category:';
}
?>
<div class="row">
<?php
$max_col_count = 12;
$current_col_count = 0;
	if (have_posts()) {
		while(have_posts()) {
		the_post();
		
		get_template_part('postContent','');?>
<?php
		}//end while
	}//endif
if ($current_col_count != $max_col_count) {
	echo '</div>';//end row
}
?>
</div>
<!--end single.php stuff -->
<?php get_footer() ?>