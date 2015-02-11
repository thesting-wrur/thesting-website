<?php
get_header();
?>
<div id="content">
<div class="home-banner">
        <!--<a class="article-link" href="#">-->
          <img class="home-banner-image" alt="Image of a large Studio" src="<?php echo get_template_directory_uri();?>/images/studio.jpg">
          <div class="hide-for-small-only">
            <div class="row">
              <div class="home-banner-title">
                <div class="large-12 columns">
                  <h1 class="home-banner-header">Station Renovations</h1>
                  <p class="home-banner-paragraph">
                    Check out the new furniture and equipment in the station!  New couches, sound boards, headphones, and more...
                  </p>
                </div>
              </div>
            </div>
          </div>
        <!--</a>-->
      </div>

      <div class="home-banner-small show-for-small-only">
        <!--<a class="article-link" href="#">-->
          <div class="row">
            <div class="large-12 columns">
              <h1 class="home-banner-header">Station Renovations</h1>
              <p class="home-banner-paragraph">
                Check out the new furniture and equipment in the station!  New couches, computers, headphones, and more...
              </p>
            </div>
          </div>
        <!--</a>-->
      </div>

<!-- Loop stuff -->
<hr style="border-color: transparent;">

<?php get_template_part('loop','');?>
	</div>
		<div class="row">
			<div class="large-4 medium-6 small-12 columns"><?php previous_posts_link('Preivous Page') ?></div>
			<div class="large-4 medium-6 small-12 columns"><?php next_posts_link('Next Page'); ?></div>
		</div>
</div>
<!-- End loop stuff -->
<div class="stream-filler"></div>
<?php get_footer() ?>