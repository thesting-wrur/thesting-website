<?php
/*
Template Name: Services Page
*/
/**
 *
 * Displays the services information that's set in the services-admin page
 */
$savedOptions = get_option('sting_services_options');
?>
<?php get_header() ?>
<!--services.php -->
<div class="row">
<div class="columns large-4 medium-6 small-12">
<h3><?php echo $savedOptions['s1_title_box'];?></h3>
<?php echo $savedOptions['s1_input_box'];?>
<!--Description of MLP
Meat Locker was...
We can do...
We might be expanding to the basement of Dewey...-->
</div>
<div class="columns large-4 medium-6 small-12">
<h3><?php echo $savedOptions['s2_title_box'];?></h3>
<?php echo $savedOptions['s2_input_box'];?>
<!--Ever wanted to DJ a show?
Students and everyone else welcome.
You start online on TheSting and eventually could have a show on FM.-->
</div>
<div class="columns large-4 medium-6 small-12">
<h3><?php echo $savedOptions['s3_title_box'];?></h3>
<?php echo $savedOptions['s3_input_box'];?>
<!--We provide DJ's, sound reinforcement and live streaming for events on campus and in the neighborhood.-->
</div>
</div>
<div class="row" style="text-align: center; margin-top: 40px;"><strong>
To learn more about any of these services, contact the General Manager, Kedar Shashidhar at <a href="mailto:kshashid@u.rochester.edu">kshashid@u.rochester.edu</a>
</strong></div>
<!--end services.php stuff -->
<?php get_footer() ?>