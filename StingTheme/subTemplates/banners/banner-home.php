<!--start banner-home.php-->
	<div class="home-banner">
        <a class="article-link">
          <!--<img alt="Image of a large Studio" src="<php echo get_template_directory_uri();?>/images/studio.jpg">-->
		  <div class="hide-for-small-only">
		  <?php
			$admin_options = get_option('sting_admin_options');
			if ( function_exists( "easingslider" ) ) {
				easingslider( intval($admin_options['homepage_slider_id_input_box']));
			} ?>
		</div>
		<div class="show-for-small-only">
			<?php
			$admin_options = get_option('sting_admin_options');
			if ( function_exists( "easingslider" ) ) {
				easingslider( intval($admin_options['homepage_mobile_slider_id_input_box']));
			}
			?>
		</div>
          <div class="hide-for-small-only">
            <div class="row">
                <div class="large-12 columns">
					<h1><?php echo bloginfo('name');?></h1>
					<p><?php bloginfo('description');?></p>
                </div>
            </div>
          </div>
        </a>
      </div>

      <div class="home-banner-small show-for-small-only">
        <a class="article-link" href="#">
          <div class="row">
            <div class="large-12 columns">
              <h1><?php echo bloginfo('name');?></h1>
              <p><?php bloginfo('description');?></p>
            </div>
          </div>
        </a>
      </div>
<!--end banner-home.php-->