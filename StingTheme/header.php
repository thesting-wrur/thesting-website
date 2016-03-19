<!doctype html>
<html class="no-js" lang="en">
	<head>
		<!--Title inserted by wordpress. Can be modified by a filter -->
		<meta charset="<?php bloginfo('charset'); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon.ico" />
		<!--<title>The Sting</title>-->
		<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" />
	<!--start wp_head() section -->
		<?php wp_head(); ?>
	<!--end wp_head() section -->
	</head>
	<body <?php body_class(); ?>>
  
	<div class="fixed" id="menu-bar">
		<nav class="top-bar hide-for-small-only" data-topbar role="navigation" >
			<ul class="title-area center">
				<li class="name">
					<h1>
					<?php
						//if (!is_front_page() || !is_home()) {
							echo '<a href="'.get_site_url().'">';
							echo strtoupper(get_bloginfo('name'));
							echo '</a>';
						/*} else {
							echo '<span class="home-name">';
							echo strtoupper(get_bloginfo('name'));
							echo '</span>';
						}*/
					?>
					</h1>
				</li>
			</ul>

			<section class="top-bar-section">
				<ul class="right">
					<li id="sting-admin-message"><?php echo sting_get_admin_message();?></li>
					<li id="sting-desktop-social-icons" class="sting-social-icons">
						<a href="https://twitter.com/WRURtheSting"><img class="sting-twitter" src="<?php echo get_template_directory_uri();?>/img/TwitterLogo_white.png"></a>
						<a href="https://www.facebook.com/wrur.thesting"><img class="sting-fb" src="<?php echo get_template_directory_uri();?>/img/FB-f-Logo__white_50.png"></a>
					</li>
					<li class="right"><a id="desktop-play-pause-outer" onclick="toggleStream();" onmouseover="showNowPlaying();">Listen Now
						<span id="desktop-play-pause-inner" class="dashicons dashicons-controls-play" style="display:none;"></span>
					</a></li>
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
					'walker'          => new top_bar_walker//class found in php-lib/foundationpress
					);
					wp_nav_menu($parameters);
				?>
			</section>
		</nav>
	<div class="top-bar" id="nowPlaying" data-topbar role="navigation" ><!--hide-for-small-only-->
			<ul class="title-area center">
				<li class="name"></li>
			</ul>
			<section class="top-bar-section now-playing">
				<ul class="right">
					<li id="chat-button"><a onclick="toggleChat();">Chat with the DJ</a></li>
				</ul>
				<ul class="left">
					<li class="current-show-label">Current Show:</li><li id="current-show-title" class="current-show">The Sting</li>
					<li class="now-playing-container" style="display: none;">Now Playing:<span class="now-playing-content"></span></li>
				</ul>
			</section>
	</div>
	<div id="quick-chat-dropdown">
		<?php echo do_shortcode('[quick-chat height="200" userlist="0" smilies="0"]');?>
	</div>
	<nav class="tab-bar show-for-small-only">
        <section class="left">
          <a id="tab-bar-menu-button" class="menu-icon"><span></span></a>
        </section>

        <section class="left" style="padding-left: 10px;">
          <h1 class="title" id="mobile-title">
		  <?php
					echo '<a href="'.get_site_url().'">';
					echo strtoupper(get_bloginfo('name'));
					echo '</a>';
			?>
		  </h1>
        </section>
		<section class="right">
			<ul class="sting-social-icons-mobile">
				<li id="sting-mobile-social-icons"  class="sting-social-icons">
					<a href="https://twitter.com/WRURtheSting"><img class="sting-twitter" src="<?php echo get_template_directory_uri();?>/img/TwitterLogo_white.png"></a>
					<a href="https://www.facebook.com/wrur.thesting"><img class="sting-fb" src="<?php echo get_template_directory_uri();?>/img/FB-f-Logo__white_50.png"></a>
				</li>
			</ul>
		</section>
      </nav>
	  <?php sting_display_mobile_menu();?>
    </div>
	<div id="content">
	<?php
	global $show_type;//defined in another file
	 if (is_front_page()) {
		//Main big blog page header
		get_template_part('subTemplates/banners/banner', 'home');
	} else if (get_post_type() === $show_type && !is_post_type_archive($show_type)) {//the shows page should use the standard banner
		//show page header
		get_template_part('subTemplates/banners/banner', 'show');
	} else {
		get_template_part('subTemplates/banners/banner');
	}