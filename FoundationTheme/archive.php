<?php get_header() ?>
<!--archive.php -->
<div class="row">
<?php
if (is_category()) {
	echo single_cat_title('Posts in: ');
} else if (is_tag()) {
	echo single_cat_title('Posts in: ');
} else if (is_author()) {
	$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
	echo 'Posts by '.$curauth -> display_name;
} else if (is_date()) {
	echo 'Posts from';
	single_month_title(' ');
}
?>
</div>
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