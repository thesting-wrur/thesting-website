<!--postContent.php-->
<?php
?>
<div class="row">
	<article id="post-<?php the_ID();?>" <?php post_class($additional_class);?>>
		<!--Post content and title-->
		<h2><a href="<?php the_permalink();?>" title="<?php the_title_attribute();?>"> <?php the_title()?></a></h2>
		<?php
		if (has_excerpt()) {
			the_excerpt('Continue Reading');
		} else {
			the_content('Continue Reading'); 
		}
		?>
	</article>
</div>
<!--end postContent.php -->