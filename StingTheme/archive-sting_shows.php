<?php get_header();?>
<!--archive-sting_shows.php -->
<div class="row show-archive">

<?php
/*
Template Name: Show Archive Page
*/
/**
 * Used for the page that has all of the shows on it (both on air and off air)
 * Doesn't show draft shows
 */
global $show_type;

	$break_end_date = $admin_options['break_schedule_end_input_box'];
	$break_end_datetime = new DateTime($break_end_date, new DateTimeZone('America/New_York'));
	$now = new DateTime('now', new DateTimeZone('America/New_York'));
	//$interval = $now->diff($break_end_datetime);
	error_log('End Date: '.$break_end_datetime->format('m-d-y H:i:s').' Now: '.$now->format('m-d-y H:i:s'));
	error_log(var_export($now > $break_end_datetime, true));
	if ($now < $break_end_datetime)://if the break ended before today, don't show the break schedule message
?>
<div class="large-12 medium-12 small-12 row" style="width: 100%; margin-left: 12px; margin-right: 12px; padding-left: 0px;">
	Note: The Sting is currently on a break schedule. The show times listed below may not accurate until the end of the break. The break ends on <?php echo $break_end_datetime->format('F jS, Y');?>.
</div>
<?php endif; ?>
<?php
$posts_to_get = get_option('sting_admin_options')['num_shows_input_box'];//how many shows to request at a time
$args = array(
	'posts_per_page'	=> $posts_to_get,
	'post_type'			=> $show_type,
	'post_status'		=> 'publish',
);
//	'post_parent'      => $post -> ID,
$shows = get_posts( $args ); //get them
$num_pages = count($shows);
//var_dump($shows);
?>

<?php
	//echo 'Pre-Sort:<br />';
	//print_shows($shows);
	usort($shows, "sting_compare_title");//sort the shows alphabetically, sting_compare_title is defined in functions
	//echo 'Post-Sort<br />';
	$index = 0;
	?>
	<!--<ul class="tabs" data-tab>
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
	<php
	//temporarily hijack the global post object
	global $post;
	$temp_post = $post;	
	foreach ($shows as $show) {
		$post = $show;
		?>
			<tr>
				<td rowspan="2"><img class="show-archive-header-img" src="<php echo sting_get_header_image();?> "></td>
				<td><php echo '<a href="'.get_permalink($show).'">'.$show -> post_title.'</a>'; ?></td>
				<td rowspan="2"><php
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
				<td><php echo coauthors(', ', ' and ', '', '', false); ?></td>
			</tr>
		<php
	}
	$post = $temp_post;
	echo '</table></div>';
?>-->
<div class="content active row" id="grid">
	<div class="row" data-equalizer>
		<?php
		//temporarily take over the global post object. this lets us use template tags with it
		global $post;
		$temp_post = $post;
		$max_col = 12;
		$current_col = 0;
		foreach($shows as $show) {
			$post = $show;
			//echo $current_col;
			if ($current_col >= $max_col) {
				echo '</div><div class="row" data-equalizer>';
				$current_col = 0;
			}
			$current_col += 3;?>
		<div class="large-3 medium-6 columns">
			<div class="archive-block" data-equalizer-watch>
			<?php echo '<a href="'.get_permalink($show).'">'?>
			<img style="width: 200px; height: 100px;" class="show-archive-header-img" src="<?php echo sting_get_header_image(get_the_ID());?> ">
			<br>
			<?php echo $show -> post_title.'</a>'; ?>
			<br>
			<?php echo 'With '.coauthors(', ', ' and ', '', '', false);?>
			<br> <?php echo sting_format_show_schedule();?>
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
<!--</div><--tabs-content-->
</div>
<!--end archive-sting_shows.php stuff -->
<?php get_footer() ?>