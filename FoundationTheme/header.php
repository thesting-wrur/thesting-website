<!DOCTYPE html>

<html <?php language_attributes(); ?>><!--closed in footer.php -->

	<head>
		<meta charset="<?php bloginfo('charset'); ?>" />
		<title><?php wp_title();?></title><!-- Change how the title looks in a filter: http://codex.wordpress.org/Function_Reference/wp_title -->
		<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" /><!--maybe media="screen"-->
		<link rel="pingback" href="<?php bloginfo('pingback_url');?>" />
		<!--start wp_head() section -->
		<?php wp_head(); ?>
		<!--end wp_head() section -->
	</head>
	<body>
	<div class="row">
		<section class="top-bar-section">
		<?php 
		$parameters = array(
		'theme_location'  => 'primary',
		'menu'            => '',
		'container'       => 'nav',
		'container_class' => 'top-bar',
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
		'walker'          => ''
		);
		wp_nav_menu($parameters); ?>
		</section>
	</div>
	<div class="row">
		<div class="full-12 columns">
			WRUR The Sting Header
		</div>
	</div>