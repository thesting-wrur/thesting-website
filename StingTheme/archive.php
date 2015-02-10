<?php get_header() ?>
<!--archive.php -->
<div class="row">
<?php
if (is_category()) {
	echo single_cat_title('Posts in the category ');
} else if (is_tag()) {
	echo single_cat_title('Posts in the tag ');
} else if (is_author()) {
	$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
	echo 'Posts by '.$curauth -> display_name;
} else if (is_date()) {
	echo 'Posts from ';
	$m = get_query_var('monthnum');//function came from wp_title's code
	$month = $wp_locale->get_month($m);
	$year = get_query_var('year');
	$day = get_query_var('day');
	$day = empty($day)? '' : $day;
	echo $month.' '.$day.' '.$year;
}
?>
</div>
<?php get_template_part('loop','');?>
<!--end archive.php stuff -->
<?php get_footer() ?>