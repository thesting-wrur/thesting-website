<?php get_header();
/*
Template Name: Schedule Page
*/
global $show_type;
?>
<!--schedule.php -->
<div class="row">
<?php
$admin_options = get_option('sting_admin_options');
$posts_to_get = $admin_options['num_shows_input_box'];
$args = array(
	'posts_per_page'	=> $posts_to_get,
	'post_type'			=> $show_type,
	'post_status'		=> 'publish',
	'meta_key'			=> 'show_on_air',
	'meta_value'		=> true,
);
//	'post_parent'      => $post -> ID,
$child_pages = get_posts( $args ); 
$num_pages = count($child_pages);
?>

<?php
	//echo 'Pre-Sort:<br />';
	//print_shows($child_pages);
	usort($child_pages, "sting_compare_shows_by_date_time");
	//echo 'Post-Sort<br />';
	//print_shows($child_pages);
	$days = array('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');
	$now = new DateTime('now', new DateTimeZone('America/New_York'));
	$today = $days[intval($now->format("w"))];
	$index = 0;
	
	$break_end_date = $admin_options['break_schedule_end_input_box'];
	$break_end_datetime = new DateTime($break_end_date, new DateTimeZone('America/New_York'));
	$now = new DateTime('now', new DateTimeZone('America/New_York'));
	//$interval = $now->diff($break_end_datetime);
	error_log('End Date: '.$break_end_datetime->format('m-d-y H:i:s').' Now: '.$now->format('m-d-y H:i:s'));
	error_log(var_export($now > $break_end_datetime, true));
	if ($now < $break_end_datetime)://if the break ended before today, don't show the break schedule message
?>
<div class="columns large-12 medium-12 small-12">
	Note: The Sting is currently on a break schedule. The shows listed may not be on the air until the end of the break. The break ends on <?php echo $break_end_datetime->format('F jS, Y');?>.
</div>
<?php endif; ?>
<!--big devices-->
<div class="large-12 medium-12 columns hide-for-small-only" id="schedule-container">
	<ul class="tabs horizontal" data-tab role="tablist">
		<?php
			$current_day = 'sunday';
		?>
		<li class="tab-title<?php echo $current_day == $today ? ' active' :''?>"><a href="#sunday">Sunday</a></li>
		<?php
			$current_day = 'monday';
		?>
		<li class="tab-title<?php echo $current_day == $today ? ' active' :''?>"><a href="#monday">Monday</a></li>
		<?php
			$current_day = 'tuesday';
		?>
		<li class="tab-title<?php echo $current_day == $today ? ' active' :''?>"><a href="#tuesday">Tuesday</a></li>
		<?php
			$current_day = 'wednesday';
		?>
		<li class="tab-title<?php echo $current_day == $today ? ' active' :''?>"><a href="#wednesday">Wednesday</a></li>
		<?php
			$current_day = 'thursday';
		?>
		<li class="tab-title<?php echo $current_day == $today ? ' active' :''?>"><a href="#thursday">Thursday</a></li>
		<?php
			$current_day = 'friday';
		?>
		<li class="tab-title<?php echo $current_day == $today ? ' active' :''?>"><a href="#friday">Friday</a></li>
		<?php
			$current_day = 'saturday';
		?>
		<li class="tab-title<?php echo $current_day == $today ? ' active' :''?>"><a href="#saturday">Saturday</a></li>
	</ul>
	<div class="tabs-content">
		<?php
			$current_day = 'sunday';
		?>
		<div class="content<?php echo $current_day == $today ? ' active' :''?>" id="sunday">
			<div class="medium-12 large-12 columns">
				<h3>Sunday</h3>
				<?php
				$index = sting_display_show_schedule($current_day, $index, $child_pages, $num_pages);
				?>
			</div>
		</div>
		<?php
			$current_day = 'monday';
		?>
		<div class="content<?php echo $current_day == $today ? ' active' :''?>" id="monday">
			<div class="medium-12 large-12 columns">
				<h3>Monday</h3>
				<?php
				$index = sting_display_show_schedule($current_day, $index, $child_pages, $num_pages);
				?>
			</div>
		</div>
		<?php
			$current_day = 'tuesday';
		?>
		<div class="content<?php echo $current_day == $today ? ' active' :''?>" id="tuesday">
			<div class="medium-12 large-12 columns">
				<h3>Tuesday</h3>
				<?php
				$index = sting_display_show_schedule($current_day, $index, $child_pages, $num_pages);
				?>
			</div>
		</div>
		<?php
			$current_day = 'wednesday';
		?>
		<div class="content<?php echo $current_day == $today ? ' active' :''?>" id="wednesday">
			<div class="medium-12 large-12 columns">
				<h3>Wednesday</h3>
				<?php
				$index = sting_display_show_schedule($current_day, $index, $child_pages, $num_pages);
				?>
			</div>
		</div>
		<?php
			$current_day = 'thursday';
		?>
		<div class="content<?php echo $current_day == $today ? ' active' :''?>" id="thursday">
			<div class="medium-12 large-12 columns">
				<h3>Thursday</h3>
				<?php
				$index = sting_display_show_schedule($current_day, $index, $child_pages, $num_pages);
				?>
			</div>
		</div>
		<?php
			$current_day = 'friday';
		?>
		<div class="content<?php echo $current_day == $today ? ' active' :''?>" id="friday">
			<div class="medium-12 large-12 columns">
				<h3>Friday</h3>
				<?php
				$index = sting_display_show_schedule($current_day, $index, $child_pages, $num_pages);
				?>
			</div>
		</div>
		<?php
			$current_day = 'saturday';
		?>
		<div class="content<?php echo $current_day == $today ? ' active' :''?>" id="saturday">
			<div class="medium-12 large-12 columns">
				<h3>Saturday</h3>
				<?php
				$current_day = 'saturday';
				$index = sting_display_show_schedule($current_day, $index, $child_pages, $num_pages);
				?>
			</div>
		</div>
	</div>
</div>
<!--Small Devices-->
<?php
	$index = 0;//reset counter so we can print out the schedule again for mobile devices
?>
<div class="small-12 columns show-for-small-only">
	<ul class="accordion" data-accordion>
		<?php
			$current_day = 'sunday';
		?>
		<li class="accordion-navigation<?php echo $current_day == $today ? ' active' :''?>">
			<a href="#sunday">Sunday</a>
			<div id="sunday" class="content<?php echo $current_day == $today ? ' active' :''?>">
				<?php
				$index = sting_display_show_schedule($current_day, $index, $child_pages, $num_pages, true);
				?>
			</div>
		</li>
		<?php
			$current_day = 'monday';
		?>
		<li class="accordion-navigation<?php echo $current_day == $today ? ' active' :''?>">
			<a href="#monday">Monday</a>
			<div id="monday" class="content<?php echo $current_day == $today ? ' active' :''?>">
				<?php
				$index = sting_display_show_schedule($current_day, $index, $child_pages, $num_pages, true);
				?>
			</div>
		</li>
		<?php
			$current_day = 'tuesday';
		?>
		<li class="accordion-navigation<?php echo $current_day == $today ? ' active' :''?>">
			<a href="#tuesday">Tuesday</a>
			<div id="tuesday" class="content<?php echo $current_day == $today ? ' active' :''?>">
				<?php
				$index = sting_display_show_schedule($current_day, $index, $child_pages, $num_pages, true);
				?>
			</div>
		</li>
		<?php
			$current_day = 'wednesday';
		?>
		<li class="accordion-navigation<?php echo $current_day == $today ? ' active' :''?>">
			<a href="#wednesday">Wednesday</a>
			<div id="wednesday" class="content<?php echo $current_day == $today ? ' active' :''?>">
				<?php
				$index = sting_display_show_schedule($current_day, $index, $child_pages, $num_pages, true);
				?>
			</div>
		</li>
		<?php
			$current_day = 'thursday';
		?>
		<li class="accordion-navigation<?php echo $current_day == $today ? ' active' :''?>">
			<a href="#thursday">Thursday</a>
			<div id="thursday" class="content<?php echo $current_day == $today ? ' active' :''?>">
				<?php
				$index = sting_display_show_schedule($current_day, $index, $child_pages, $num_pages, true);
				?>
			</div>
		</li>
		<?php
			$current_day = 'friday';
		?>
		<li class="accordion-navigation<?php echo $current_day == $today ? ' active' :''?>">
			<a href="#friday">Friday</a>
			<div id="friday" class="content<?php echo $current_day == $today ? ' active' :''?>">
				<?php
				$index = sting_display_show_schedule($current_day, $index, $child_pages, $num_pages, true);
				?>
			</div>
		</li>
		<?php
			$current_day = 'saturday';
		?>
		<li class="accordion-navigation<?php echo $current_day == $today ? ' active' :''?>">
			<a href="#saturday">Saturday</a>
			<div id="saturday" class="content<?php echo $current_day == $today ? ' active' :''?>">
				<?php
				$index = sting_display_show_schedule($current_day, $index, $child_pages, $num_pages, true);
				?>
			</div>
		</li>
	</ul>
</div>
</div>
<!--end schedule.php stuff -->
<?php get_footer() ?>