<?php
/**
 * Template Name: Custom RSS Template - Feedname
 */
global $show_type;
$posts_to_get = get_option('sting_admin_options')['num_shows_input_box'];
header('Content-Type: '.feed_content_type('rss-http').'; charset='.get_option('blog_charset'), true);
echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';
error_log('show_feed');
/*
 *	get requested show id so that we can query based on it
 */
$request = $_SERVER['REQUEST_URI'];
error_log($request);
$ID = substr($request, stripos($request, '&') + 6);
error_log($ID);
$show = get_post($ID);
if (id == '' || $show -> post_type != $show_type) {
	wp_die('Need valid show id in order to generate show rss feed', 'Bad ID');
}
?>
<rss version="2.0"
        xmlns:content="http://purl.org/rss/1.0/modules/content/"
        xmlns:dc="http://purl.org/dc/elements/1.1/"
        xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
        <?php do_action('rss2_ns'); ?>
	>
	<channel>
		<title><?php echo get_the_title($ID ); ?></title>
		<link><?php echo get_bloginfo('url').'?p='.$ID ?></link>
		<description><?php echo wp_strip_all_tags($show -> post_content); ?></description>
		<sy:updatePeriod><?php echo apply_filters( 'rss_update_period', 'weekly' ); ?></sy:updatePeriod>
		<sy:updateFrequency><?php echo apply_filters( 'rss_update_frequency', '2' ); ?></sy:updateFrequency>
		<?php do_action('rss2_head'); ?>
		
		<?php
		/*
		 * Query for the posts by the authors of the show in the category of the show
		 */
		$coauthors = get_coauthors($ID);
		$author_id = '';//'author=';
		foreach($coauthors as $author) {
			$author_id .= $author -> ID;
			$author_id .= ',';
		}
		$author_id = substr($author_id, 0, strlen($author_id) - 1);
		$categories = get_the_category($ID)[0] -> term_id;
		$posts = new WP_Query(array('author' => $author_id, 'cat' => $categories));
		$first_post = $posts -> posts[0];
		error_log(var_export($first_post, true));
		?>
		<lastBuildDate><?php echo mysql2date('D, d M Y H:i:s +0000', $first_post -> post_date, false); ?></lastBuildDate>
		
		<?php while($posts -> have_posts()) : $posts -> the_post(); ?>
                <item>
                        <title><?php the_title_rss(); ?></title>
                        <link><?php the_permalink_rss(); ?></link>
                        <pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false); ?></pubDate>
                        <dc:creator><?php echo coauthors(', ', ' and ', '', '', false); ?></dc:creator>
                        <description><![CDATA[<?php the_excerpt_rss() ?>]]></description>
                        <content:encoded><![CDATA[<?php the_excerpt_rss() ?>]]></content:encoded>
						<guid isPermaLink="true"><?php echo get_permalink(); ?></guid>
                        <?php rss_enclosure(); ?>
                        <?php do_action('rss2_item'); ?>
                </item>
        <?php endwhile; ?>
	</channel>
</rss>