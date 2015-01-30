<!--postContent.php-->
<?php
global $max_col_count;
global $current_col_count;
global $num_cols;
		if ($current_col_count === 12) {//if a row is full, make a new one
			$current_col_count = 0;
			echo '</div>';//end row
			echo '<div class="row">';//start new row
			echo '<article class="large-'.$num_cols.' columns">';
		} else {
			if ($current_col_count + $num_cols > 12) {//if a row isn't full, but we would overfill one, just be as big as we can
				$num_cols = 12 - $current_col_count;
			}
			//echo '<article id="'.the_ID().'" '.post_class('large-'.$num_cols.' columns' ).'>';
			$additional_class = 'large-'.$num_cols.' columns';
			?>
			<article id="post-<?php the_ID();?>" <?php post_class($additional_class);?>>
			<?php
		}
		$current_col_count += $num_cols;
		echo '<!--num_cols '.$num_cols.' current_col_count '.$current_col_count.' -->';
?>
		
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
<!--end postContent.php -->