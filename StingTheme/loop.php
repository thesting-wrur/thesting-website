<?php
//$col_num = 0;
global $wp_query;
global $sting_query;
global $max_col;
if (!isset($sting_query)) {//if we are using this loop for a special query like the one on the show archive page, it will have been set before including this template
	$sting_query = $wp_query;
}
?>
<div class="row">
<?php
	$post_list = array();
	if (!isset($max_col)) {
		$max_col = 1;
	}
	$current_col = 0;
	echo '<div class="row">';
	if ($sting_query -> have_posts()) {//alternate form of the loop
		while($sting_query -> have_posts()) {
			$sting_query -> the_post();//iterate to the next post (this is kindof a foreach loop except using an iterator)
			if ($current_col > $max_col) {
				echo '</div>';
				echo '<div class="row">';
				$current_col = 0;
			}
			
			if ($max_col > 0) {//we actually have columns
			?>
				<article id="post-<?php the_ID();?>" <?php post_class('large-6 medium-6 small-12 columns');?>>
			<?php
			} else {
				?>
				<article id="post-<?php the_ID();?>" <?php post_class();?>>
				<?php
			}
			?>
				<!-- Post image (if it exists) we aren't using featured images right now-->
				<?php
					/*if (has_post_thumbnail(get_the_ID())) {
						?><a href="<?php the_permalink();?>" title="<?php the_title_attribute();?>"><?php
						echo get_the_post_thumbnail( get_the_ID(), 'small' );
						?></a><?php
					}*/
				?>
				<!--Post content and title-->
				<h2><a href="<?php the_permalink();?>" title="<?php the_title_attribute();?>"> <?php the_title()?></a><?php if ($max_col == 0) {echo '<span class="post-date right">'.get_the_date().'</span>';}?></h2>
				<?php
					// as we aren't using featured images right now if (!has_post_thumbnail(get_the_ID())) {
						//error_log(get_the_content());
						if (has_excerpt()) {
							the_excerpt('<br>Continue Reading...');
						} else {
							the_content('<br>Continue Reading...'); 
						}
					//}
				?>
				</article>
				<?php
			$current_col++;
		}//end while
	}//endif
	echo '</div>';
	echo '<div id="post-content" class="row"></div>'
?>
<!--</div>-->
