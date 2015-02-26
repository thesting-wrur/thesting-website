<!--start banner.php-->
<?php $page = get_post(get_queried_object_id());?>
<div class="page-banner">
	<a class="article-link" href="#">
		<img src="<?php echo get_template_directory_uri();?>/images/studio.jpg">
		<div class="row">
			<div class="large-12 columns">
				<h1><?php echo $page -> post_title; ?></h1>
			</div>
		</div>
	</a>
</div>
<!--end banner.php-->