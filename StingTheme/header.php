<!doctype html>
<html class="no-js" lang="en">
  <head>
	<!--Title inserted by wordpress. Can be modified by a filter -->
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!--<title>The Sting</title>-->
	<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" />
	<!--start wp_head() section -->
	<?php wp_head(); ?>
	<!--end wp_head() section -->
  </head>
  <body <?php body_class(); ?>>
  
      <div class="fixed">
      <nav class="top-bar hide-for-small-only" data-topbar role="navigation">
        <ul class="title-area center">
          <li class="name">
            <h1><a href="<?php echo get_site_url();?>"><?php echo strtoupper(get_bloginfo('name'));?></a></h1>
          </li>
        </ul>

        <section class="top-bar-section">
          <ul class="right">
            <li><a onclick="toggleStream();">Listen Live</a></li>
          </ul>

		<?php 
		$parameters = array(
		'theme_location'  => 'primary',
		'menu'            => '',
		'container'       => 'false',
		'container_class' => '',
		'container_id'    => '',
		'menu_class'      => 'left',
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'depth'           => 0,
		'walker'          => new top_bar_walker
		);
		wp_nav_menu($parameters); ?>
        </section>
      </nav>

      <nav class="tab-bar show-for-small-only">
        <section class="left">
          <a id="tab-bar-menu-button" class="menu-icon"><span></span></a>
        </section>

        <section class="left" style="padding-left: 10px;">
          <h1 class="title"><?php echo strtoupper(get_bloginfo('name'));?></h1>
        </section>
      </nav>
	  <?php sting_display_mobile_menu();?>
    </div>
	<div id="content">
	<?php
	 if (is_front_page()) {
		//Main big blog page header
		get_template_part('subTemplates/banners/banner', 'home');
	} else {
		get_template_part('subTemplates/banners/banner');
	}