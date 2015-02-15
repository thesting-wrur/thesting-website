<?php
/*
Template Name: Services Page
*/
$savedOptions = get_option('sting_services_options');
?>
<?php get_header() ?>
<!--services.php -->
<div class="row">
<div class="columns large-4">
<h3>Meat Locker Productions</h3>
<?php echo $savedOptions['mlp_input_box'];?>
<!--Description of MLP
Meat Locker was...
We can do...
We might be expanding to the basement of Dewey...-->
</div>
<div class="columns large-4">
<h3>DJ Radio Shows</h3>
<?php echo $savedOptions['show_dj_input_box'];?>
<!--Ever wanted to DJ a show?
Students and everyone else welcome.
You start online on TheSting and eventually could have a show on FM.-->
</div>
<div class="columns large-4">
<h3>Live Events</h3>
<?php echo $savedOptions['live_events_input_box'];?>
<!--We provide DJ's, sound reinforcement and live streaming for events on campus and in the neighborhood.-->
</div>
</div>
<!--end services.php stuff -->
<?php get_footer() ?>