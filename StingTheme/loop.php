<?php
$col_num = 0;
global $wp_query;
global $sting_query;
if (!isset($sting_query)) {
	$sting_query = $wp_query;
}
?>
<div class="row">
<?php
	$post_list = array();
	$max_col = 2;
	$current_col = 0;
	echo '<div class="row">';
	if ($sting_query -> have_posts()) {
		while($sting_query -> have_posts()) {
			$sting_query -> the_post();//iterate to the next post (this is kindof a foreach loop except using an iterator)
			if ($current_col > $max_col) {
				echo '</div>';
				echo '<div class="row">';
				$current_col = 0;
			}
			?>
				<article id="post-<?php the_ID();?>" <?php post_class('large-4 medium-6 small-12 columns');?>>
				<!-- Post image (if it exists) -->
				<?php
					if (has_post_thumbnail(get_the_ID())) {
						?><a href="<?php the_permalink();?>" title="<?php the_title_attribute();?>"><?php
						echo get_the_post_thumbnail( get_the_ID(), 'small' );
						?></a><?php
					}
				?>
				<!--Post content and title-->
				<h2><a href="<?php the_permalink();?>" title="<?php the_title_attribute();?>"> <?php the_title()?></a></h2>
				<?php
					if (!has_post_thumbnail(get_the_ID())) {
						if (has_excerpt()) {
							the_excerpt('<br>Continue Reading...');
						} else {
							the_content('<br>Continue Reading...'); 
						}
					}
				?>
				</article>
				<?php
			$current_col++;

			$id = get_the_ID();
			$title = the_title('','',false);
			$link = get_permalink();
			$datetime = get_the_time(get_option('date_format'));
			$content = get_the_excerpt();
			$image = get_the_post_thumbnail( get_the_ID(), 'thumbnail' );
			$author = get_the_author();
			$priority = get_field('post_priority');
			$post_information = array(
				'id'			=>		$id,
				'title'			=>		$title,
				'image'			=>		$image,
				'content'		=>		$content,
				'author'		=>		$author,
				'link'			=>		$link,
				'datetime'		=>		$datetime,
				'post_priority'	=>		$priority,
			);
			array_push($post_list, $post_information);
		}//end while
	}//endif
	echo '<div id="post-json" style="display:none">';
	echo json_encode($post_list);
	echo '</div>';
	echo '<div id="post-content" class="row"></div>'
?>
</div>
