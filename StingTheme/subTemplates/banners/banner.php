<!--start banner.php-->
<?php $page = get_post(get_queried_object_id());
	  $title = $page -> post_title;

if (is_search()) {
	$title = 'Search Results';
} else if (is_404()) {
	$title = 'Page not found';
}
?>
<div class="page-banner">
	<a class="article-link">
		<img src="<?php echo get_template_directory_uri();?>/images/studio.jpg">
		<div class="row">
			<div class="large-12 columns">
				<h1><?php echo $title; ?></h1>
			</div>
		</div>
	</a>
</div>
<!--end banner.php-->