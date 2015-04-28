<!--start banner-home.php-->
	<div class="show-banner">
        <a class="article-link">
		  <img src="<?php echo sting_get_header_image();?> ">
          <div class="hide-for-small-only">
            <div class="row">
                <div class="large-12 columns">
					<h1><?php the_title();?></h1>
					<p><?php echo coauthors(',', ' and ', 'with ', '', false);?></p>
                </div>
            </div>
          </div>
        </a>
      </div>

      <div class="show-banner-small show-for-small-only">
          <div class="row">
            <div class="large-12 columns">
              <h1><?php the_title();?></h1>
              <p><?php echo coauthors(', ', ' and ', 'with ', '', false);?></p>
            </div>
          </div>
      </div>
<!--end banner-home.php-->
