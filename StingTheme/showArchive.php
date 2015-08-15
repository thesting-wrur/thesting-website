<?php get_header();
/*
Template Name: Show Archive Page
*/
global $show_type;
?>
<!--showArchive.php -->
<div class="row show-archive">
<?php
$posts_to_get = get_option('sting_admin_options')['num_shows_input_box'];
$args = array(
	'posts_per_page'	=> $posts_to_get,
	'post_type'			=> $show_type,
	'post_status'		=> 'publish',
);
//	'post_parent'      => $post -> ID,
$shows = get_posts( $args ); 
$num_pages = count($shows);
//var_dump($shows);
?>

<?php
	//echo 'Pre-Sort:<br />';
	//print_shows($shows);
	usort($shows, "sting_compare_title");
	//echo 'Post-Sort<br />';
	$index = 0;
	?>
	<ul class="tabs" data-tab>
		<li class="tab-title"><a href="#table">Table Layout</a></li>
		<li class="tab-title active"><a href="#grid">Grid Layout by Title</a></li>
	</ul>
	<div class="tabs-content">
	<div class="content" id="table">
	<table width="100%">
		<colgroup>
			<col width="25%" />
			<col width="50%" />
			<col width="25%" />
		</colgroup>
		<thead>
			<tr>
				<th></th>
				<th><h4>Show</h4></th>
				<th><h4>Air Time</h4></th>
			</tr>
		</thead>
	<?php
	//temporarily hijack the global post object
	global $post;
	$temp_post = $post;	
	foreach ($shows as $show) {
		$post = $show;
		?>
			<tr>
				<td rowspan="2"><img class="show-archive-header-img" src="<?php echo sting_get_header_image();?> "></td>
				<td><?php echo '<a href="'.get_permalink($show).'">'.$show -> post_title.'</a>'; ?></td>
				<td rowspan="2"><?php
						if (get_field('show_on_air', $show -> ID)) {
							echo 'Airs on:<br>';
							echo ucfirst(get_field('day', $show -> ID));
							echo ' at ';
							echo get_field('start_time', $show -> ID);
						} else {
							echo 'Currently not on the air';
						}
					?></td>
			</tr>
			<tr>
				<td><?php echo coauthors(', ', ' and ', '', '', false); ?></td>
			</tr>
		<?php
	}
	$post = $temp_post;
	echo '</table></div>';
?>
<div class="content active row" id="grid">
	<div class="row">
		<?php
		//temporarily hijack the global post object
		global $post;
		$temp_post = $post;
		$max_col = 12;
		$current_col = 0;
		foreach($shows as $show) {
			$post = $show;
			//echo $current_col;
			if ($current_col >= $max_col) {
				echo '</div><div class="row">';
				$current_col = 0;
			}
			$current_col += 3;?>
		<div class="large-3 medium-6 columns">
			<div class="archive-block">
			<?php echo '<a href="'.get_permalink($show).'">'?>
			<img style="width: 200px; height: 100px;" class="show-archive-header-img" src="<?php echo sting_get_header_image();?> ">
			<br>
			<?php echo $show -> post_title.'</a>'; ?>
			<br>
			<?php echo 'With '.coauthors(', ', ' and ', '', '', false);?>
			<br>
			<?php
						if (get_field('show_on_air', $show -> ID)) {
							echo 'Airs ';
							echo ucfirst(get_field('day', $show -> ID));
							echo ' at ';
							echo get_field('start_time', $show -> ID);
						} else {
							echo 'This show is not on the air';
						}
					?>
					<br><br>
			</div>
		</div>
		<?php
		}
		if ($current_col != $max_col) {
			echo '</div>';
		}
		//return global post object
		$post = $temp_post;?>
	</div>
</div><!--tabs-content-->
</div>
<!--end showArchive.php stuff -->
<?php get_footer() ?>