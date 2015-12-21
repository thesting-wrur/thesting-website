<!--start banner-show.php-->
	<?php
		$schedule = sting_format_show_schedule().' ';
		if (stripos($schedule, 'not on the air')) {
			$schedule = '';
		}
	?>
	<div class="show-banner">
        <a class="article-link">
		  <img src="<?php echo sting_get_header_image();?> ">
          <div class="hide-for-small-only">
            <div class="row">
                <div class="large-11 medium-11 small-11 columns">
					<h1><?php the_title();?></h1>
					<p><?php echo $schedule.coauthors(',', ' and ', 'with ', '', false);?></p>
                </div>
				<div class="large-1 medium-1 small-1 columns sting-social-media-banner">
					<img style="position: relative; vertical-align: baseline; width: 36px;" src="http://thesting.wrur.org/wp-content/themes/StingTheme/img/FB-f-Logo__white_50.png">	<br>
					<img src="http://thesting.wrur.org/wp-content/themes/StingTheme/img/TwitterLogo_white.png" style="position: relative; vertical-align: baseline; width: 36px;">	
				</div>
            </div>
          </div>
        </a>
      </div>

      <div class="show-banner-small show-for-small-only">
          <div class="row">
            <div class="large-12 columns">
              <h1><?php the_title();?></h1>
			  <p><?php echo $schedule.'<br>'.coauthors(',', ' and ', 'with ', '', false);?></p>
            </div>
          </div>
      </div>
<!--end banner-show.php-->
