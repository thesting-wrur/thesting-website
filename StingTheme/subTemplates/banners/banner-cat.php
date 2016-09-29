<!--start banner-show.php-->
	<?php
		$schedule = sting_format_show_schedule().' ';
		if (stripos($schedule, 'not on the air')) {
			$schedule = '';
		}
	?>
	<div class="show-banner">
        <a class="article-link">
		  <img src="<?php global $admin_options; echo $admin_options['sting_music_blog_header_image_input_box'];?> ">
          <div class="hide-for-small-only">
            <div class="row">
                <div class="large-12 medium-12 small-12 columns">
					<h1><?php single_cat_title();?></h1>
                </div>
            </div>
          </div>
        </a>
      </div>

      <div class="show-banner-small show-for-small-only">
          <div class="row">
            <div class="large-12 columns">
              <h1><?php single_cat_title();?></h1>
            </div>
          </div>
      </div>
<!--end banner-show.php-->
