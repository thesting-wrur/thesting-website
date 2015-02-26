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
	if ($sting_query -> have_posts()) {
		while($sting_query -> have_posts()) {
		$sting_query -> the_post();//iterate to the next post (this is kindof a foreach loop except using an iterator)
		
		get_template_part('postContent','');
		
		$col_num = $col_num + 1;
		
		if ($col_num === 3) {
			?>
			</div>
			<div class="row">
			<?php
			$col_num = 0;
		}
		}//end while
	}//endif
?>