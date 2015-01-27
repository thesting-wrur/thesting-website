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
		<nav class="top-bar" data-topbar>
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
		</div>
		</nav>
		</div>
		<!--<div class="row">
	<nav class="top-bar" data-topbar>
		<section class="top-bar-section">
			<ul id="menu-the-sting" class="menu left">
				<li id="menu-item-2083" class="has-dropdown"><a href="http://localhost/bl_site/about-us/">About us</a>
				<ul class="dropdown">
					<li id="menu-item-2084" class=""><a href="http://localhost/bl_site/about-us/about-4326/">About 4326</a></li>
					<li id="menu-item-2085" class=""><a href="http://localhost/bl_site/about-us/about-7890/">About 7890</a></li>
					<li id="menu-item-2086" class=""><a href="http://localhost/bl_site/about-us/basement-media/">Basement Media</a></li>
					<li id="menu-item-2087" class="has-dropdown"><a href="http://localhost/bl_site/about-us/competitions-and-games/">Competitions and Games</a>
						<ul class="dropdown">
							<li id="menu-item-2088" class=""><a href="http://localhost/bl_site/about-us/competitions-and-games/2013-2014-ftc-game-ftc-block-party/">2013-2014 FTC Game: FTC Block Party</a></li>
							<li id="menu-item-2089" class=""><a href="http://localhost/bl_site/about-us/competitions-and-games/2014-2015-game-released/">2014-2015 Game Released!</a></li>
							<li id="menu-item-2090" class=""><a href="http://localhost/bl_site/about-us/competitions-and-games/egg-scramble/">Egg Scramble</a></li>
						</ul>
					</li>
					<li id="menu-item-2091" class=""><a href="http://localhost/bl_site/about-us/contact-us/">Contact Us!</a></li>
					<li id="menu-item-2092" class="has-dropdown"><a href="http://localhost/bl_site/about-us/team-bios/">Team Bios</a>
						<ul class="dropdown">
							<li id="menu-item-2093" class=""><a href="http://localhost/bl_site/about-us/team-bios/4326-team-bios/">4326 Team Bios</a></li>
							<li id="menu-item-2094" class=""><a href="http://localhost/bl_site/about-us/team-bios/7890-team-bios/">7890 Team Bios</a></li>
						</ul>
					</li>
				</ul>
			</li>
			<li id="menu-item-2095" class="has-dropdown"><a href="http://localhost/bl_site/events/">Events</a>
				<ul class="dropdown">
					<li id="menu-item-2096" class=""><a href="http://localhost/bl_site/events/book-day/">Book Day</a></li>
					<li id="menu-item-2097" class=""><a href="http://localhost/bl_site/events/team-calendar/">Calendar</a></li>
					<li id="menu-item-2098" class=""><a href="http://localhost/bl_site/events/cs-ed-week/">Computer Science Education Week</a></li>
				</ul>
			</li>
			<li id="menu-item-2099" class=""><a href="http://localhost/bl_site/resources/">Resources</a></li>
			</ul>
		</section>
	</nav>
	<!--<nav class="top-bar" data-topbar role="navigation"> <ul class="title-area"> <li class="name"> <h1><a href="#">My Site</a></h1> </li> <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li> </ul> <section class="top-bar-section"><ul class="right"> <li class="active"><a href="#">Right Button Active</a></li> <li class="has-dropdown"> <a href="#">Right Button Dropdown</a> <ul class="dropdown"> <li><a href="#">First link in dropdown</a></li> <li class="active"><a href="#">Active link in dropdown</a></li> </ul> </li> </ul><ul class="left"> <li><a href="#">Left Nav Button</a></li> </ul> </section> </nav>-->
	</div>
	<div class="row">
		<div class="full-12 columns">
			WRUR The Sting Header
		</div>
	</div>