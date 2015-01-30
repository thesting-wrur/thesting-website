<!DOCTYPE html>

<html <?php language_attributes(); ?>><!--closed in footer.php -->

	<head>
		<meta charset="<?php bloginfo('charset'); ?>" />
		<title><?php wp_title();?></title><!-- Change how the title looks in a filter: http://codex.wordpress.org/Function_Reference/wp_title -->
		<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" /><!--maybe media="screen"-->
		<link rel="pingback" href="<?php bloginfo('pingback_url');?>" />
		<link rel="alternate" type="application/rss+xml" title="Subscribe to The Sting" href="<?php bloginfo('rss2_url'); ?>" />
		<!--start wp_head() section -->
		<?php wp_head(); ?>
		<!--end wp_head() section -->
	</head>
	<body>
	<div class="row">
		<nav class="top-bar" data-topbar>
			<ul class="title-area">
				<li class="name">
				</li>
				<!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
				<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
				</ul>
			<section class="top-bar-section">
		<?php 
		$parameters = array(
		'theme_location'  => 'primary',
		'menu'            => '',
		'container'       => 'false',
		'container_class' => '',
		'container_id'    => '',
		'menu_class'      => 'menu left',
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
	</div>
	<div class="row">
		<div class="full-12 columns">
			WRUR The Sting Header
		</div>
	</div>