<?php
$col_num = 0;
?>
<div class="row">
<?php
	if (have_posts()) {
		while(have_posts()) {
		the_post();//iterate to the next post (this is kindof a foreach loop except using an iterator)
		
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