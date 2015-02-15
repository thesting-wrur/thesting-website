<!--postContent.php-->
<?php

?>
        <!-- BEGIN LOOP -->
        <div class="large-4 medium-6 small-12 columns">
          <div class="article-image-box">
            <div class="article-image-content">
              <a class="article-link" href="<?php the_permalink()?>">
                <?php the_post_thumbnail(); ?>
              </a>
            </div>
          </div>
          <hr style="border: 0;">
          <h3 class="article-link"><a class="article-link" href="<?php the_permalink();?>" title="<?php the_title_attribute();?>"> <?php the_title()?></a></h3>
            <?php //the_excerpt('...'); ?>
            <span class="article-related">
              <a>CONCERTS</a> / <a>MUSIC</a> / <a>RADIO HAUS</a>
            </span>
        </div>
        <!-- END LOOP -->
<!--end postContent.php -->