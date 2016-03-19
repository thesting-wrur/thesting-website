<!--start banner.php-->
<?php
	$page = get_post(get_queried_object_id());
	$title = $page -> post_title;
	
	global $show_type;//defined in another file
	if (is_search()) {
		$title = 'Search Results';
	} else if (is_404()) {
		$title = 'Page not found';
	} else if (is_category()) {
		$title = single_cat_title('', false).' Posts';
	} else if (is_tag()) {
		$title = single_cat_title('Posts in the tag ', false);
	} else if (is_author()) {
		$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
		$title = 'Posts by '.$curauth -> display_name;
	} else if (is_date()) {
		$title = 'Posts from ';
		
		$m = get_query_var('monthnum');//function came from wp_title's code
		$month = sting_num_to_month($m);
		$month = empty($month)? '' : $month;
		
		$year = get_query_var('year');
		
		$day = get_query_var('day');
		$day = empty($day)? '' : $day;
		
		if ($day != '') {
			$title .= $month.' '.$day.', '.$year;
		} else {
			$title .= $month.' '.$day.' '.$year;
		}
	} else if (is_post_type_archive($show_type)) {
		$title = "Shows";
	}
function sting_num_to_month($monthNum) {
	global $wp_locale;
	error_log(var_export($wp_locale, true)." Locale");
	return $wp_locale->get_month($monthNum);
}
?>
<div class="page-banner">
	<a class="article-link">
		<img src="<?php echo sting_get_header_image();?> ">
		<div class="row">
			<div class="large-12 columns">
				<h1><?php echo $title; ?></h1>
			</div>
		</div>
	</a>
</div>
<!--end banner.php-->