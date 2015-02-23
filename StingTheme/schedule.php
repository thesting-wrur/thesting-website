<?php get_header();
/*
Template Name: Schedule Page
*/
?>
<!--schedule.php -->
<div class="row">
<h2>Schedule</h2>
<?php
$args = array(
	'posts_per_page'   => 100,
	'post_type'        => 'page',
	'post_parent'      => $post -> ID,
	'post_status'      => 'publish',
);
$child_pages = get_posts( $args ); 
?>

<?php
	usort($child_pages, "sting_compare_shows_by_date_time");
	//var_dump($child_pages);
	$index = 0;
?>
<div class="large-12 columns">
	<ul class="tabs vertical" data-tab role="tablist">
		<li class="tab-title"><a href="#sunday">Sunday</a></li>
		<li class="tab-title"><a href="#monday">Monday</a></li>
		<li class="tab-title"><a href="#tuesday">Tuesday</a></li>
		<li class="tab-title"><a href="#wednesday">Wednesday</a></li>
		<li class="tab-title"><a href="#thursday">Thursday</a></li>
		<li class="tab-title"><a href="#friday">Friday</a></li>
		<li class="tab-title active"><a href="#saturday">Saturday</a></li>
	</ul>
	<div class="tabs-content">
		<div class="content" id="sunday">
			<div class="medium-9 large-9 columns">
				<h3>Sunday</h3>
				<!-- LOOP THROUGH SHOWS IN A GIVEN DAY -->
				<?php
				$current_day = 'sunday';
				$index = sting_display_show_schedule($current_day, $index, $child_pages);
				?>
                <!-- END LOOP -->
			</div>
		</div>
		<div class="content" id="monday">
			<div class="medium-9 large-9 columns">
				<h3>Monday</h3>
				<!-- LOOP THROUGH SHOWS IN A GIVEN DAY -->
				<?php
				$current_day = 'monday';
				$index = sting_display_show_schedule($current_day, $index, $child_pages);
				?>
                <!-- END LOOP -->
			</div>
		</div>
		<div class="content" id="tuesday">
			<div class="medium-9 large-9 columns">
				<h3>Tuesday</h3>
				<!-- LOOP THROUGH SHOWS IN A GIVEN DAY -->
				<?php
				$current_day = 'tuesday';
				$index = sting_display_show_schedule($current_day, $index, $child_pages);
				?>
                <!-- END LOOP -->
			</div>
		</div>
		<div class="content" id="wednesday">
			<div class="medium-9 large-9 columns">
				<h3>Wednesday</h3>
				<!-- LOOP THROUGH SHOWS IN A GIVEN DAY -->
				<?php
				$current_day = 'wednesday';
				$index = sting_display_show_schedule($current_day, $index, $child_pages);
				?>
                <!-- END LOOP -->
			</div>
		</div>
		<div class="content" id="thursday">
			<div class="medium-9 large-9 columns">
				<h3>Thursday</h3>
				<!-- LOOP THROUGH SHOWS IN A GIVEN DAY -->
				<?php
				$current_day = 'thursday';
				$index = sting_display_show_schedule($current_day, $index, $child_pages);
				?>
                <!-- END LOOP -->
			</div>
		</div>
		<div class="content" id="friday">
			<div class="medium-9 large-9 columns">
				<h3>Friday</h3>
				<!-- LOOP THROUGH SHOWS IN A GIVEN DAY -->
				<?php
				$current_day = 'friday';
				$index = sting_display_show_schedule($current_day, $index, $child_pages);
				?>
                <!-- END LOOP -->
			</div>
		</div>
		<div class="content active" id="saturday">
			<div class="medium-9 large-9 columns">
				<h3>Saturday</h3>
				<!-- LOOP THROUGH SHOWS IN A GIVEN DAY -->
				<?php
				$current_day = 'saturday';
				$index = sting_display_show_schedule($current_day, $index, $child_pages);
				?>
                <!-- END LOOP -->
			</div>
		</div>
	</div>
</div>

</div>
<!--end schedule.php stuff -->
<?php get_footer() ?>