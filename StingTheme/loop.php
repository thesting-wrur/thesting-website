<?php
$col_num = 0;
global $wp_query;
global $sting_query;
if (!isset($sting_query)) {
	$sting_query = $wp_query;
}
?>
<div class="row">
<?php
	$post_list = array();
	if ($sting_query -> have_posts()) {
		while($sting_query -> have_posts()) {
			$sting_query -> the_post();//iterate to the next post (this is kindof a foreach loop except using an iterator)
			$id = get_the_ID();
			$title = the_title('','',false);
			$link = get_permalink();
			$datetime = get_the_time(get_option('date_format'));
			$content = get_the_excerpt();
			$image = get_the_post_thumbnail( get_the_ID(), 'thumbnail' );
			$author = get_the_author();
			$priority = get_field('post_priority');
			$post_information = array(
				'id'			=>		$id,
				'title'			=>		$title,
				'image'			=>		$image,
				'content'		=>		$content,
				'author'		=>		$author,
				'link'			=>		$link,
				'datetime'		=>		$datetime,
				'post_priority'	=>		$priority,
			);
			array_push($post_list, $post_information);
		}//end while
	}//endif
	echo '<div id="page-content">';
	echo json_encode($post_list);
	echo '</div>';
?>
</div>