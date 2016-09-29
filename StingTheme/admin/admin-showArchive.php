<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

function register_sting_admin_show_archives_page() {
	global $show_type;
	$archivePageName = 'Archives';
	add_submenu_page('edit.php?post_type='.$show_type, 'Archives', 'Archives', 'edit_shows', $archivePageName, 'sting_setup_archive_page');
}
add_action( 'admin_menu', 'register_sting_admin_show_archives_page' );

function sting_setup_archive_page() {
	global $current_user;
	get_currentuserinfo();
	//var_dump( $current_user);
	$admin_options = get_option('sting_admin_options');
	$posts_to_get = $admin_options['num_shows_input_box'];
	//if user can edit_others_shows, they can see all the archives
	$author_info = array();
	if (!current_user_can('edit_others_shows')) {
		$author_info = array(
				array(
					'taxonomy' 	=> 	'author',
					'field'		=>	'name',
					'terms'		=>	$current_user -> user_login,
					)
			);
	}
	global $show_type;
	$query_args = array(
		'posts_per_page'	=> $posts_to_get,
		'post_type'			=> $show_type,
		'tax_query'			=> $author_info,
	);		
	$show_query = new WP_Query($query_args);
	//loop through the show query and use the post_title field in each WP_Post object to find the titles of the shows
	//the script on the backend is going to be modified to require the author id in the url passed to file_get_contents
	//echo $show_query -> have_posts() == false ? 'true' : 'false';
	//var_dump($show_query);
		$show_array = array();
		$show_titles = array();
		while($show_query -> have_posts()){
			$show_query -> the_post();
			$title=get_the_title();
			$title=str_replace(' ','_',$title);
			array_push($show_titles, $title);
			//var_dump($title);//'Hi';
			//echo '<br>';
			//var_dump('http://128.151.44.36/archives/showArchive.php?title='.$title.'&list=list');
			
			
			//$shows = file_get_contents('http://128.151.44.36/archives/showArchive.php?title='.$title.'&list=list');
			
			$curl = curl_init('http://128.151.44.36/archives/showArchive.php?title='.$title.'&list=list');
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($curl, CURLOPT_USERPWD, "wrur:wrur885");
			curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
			$shows = curl_exec($curl);
			curl_close($curl);
			
			//echo '<br>';
			//var_dump($shows);
			//echo '<br>';
			$showData = json_decode($shows, true);
			//var_dump($showData);
			//echo '<br>';
			array_push($show_array, $showData);
			
		}
	//Sammy, if you have a moment to work on this before Sunday, loop through the array (show_array) and print out the available files (the array will have an array of strings in it. Print those strings).
	//don't actually display the file name. Instead, display the date in a nice form. Then link to the address with the required parameters
	?>
	<h1>Available Archives</h1>
	<table class="wp-list-table widefat fixed striped posts">
	<thead>
	<tr>
		<!--Won't work for us. I don't want to be zipping files on the fly - unless of cuorse there is a very easy way to
		<td id='cb' class='manage-column column-cb check-column'>
			<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
			<input id="cb-select-all-1" type="checkbox" />
		</td>-->
		<th scope="col" id='title' class='column-title column-primary'><span>Archives</span></th>
	</tr>
	</thead>
	<tbody id="the-list">
	<?php
	$index = 0;
	foreach($show_titles as $title) {
		$show = $show_array[$index][$title];
		$orig_title = $title;
		foreach($show as $current_show) {
			$current_show = substr($current_show, 0, strpos($current_show, '.'));
			$date = new DateTime($current_show, new DateTimeZone('America/New_York'));
			$link_to_show = admin_url().'admin-post.php?action=sting_download_archive&title='.$orig_title.'&date='.$current_show;
			//echo $link_to_show;
			$title=str_replace('_',' ',$title);
	?>
	<tr>
		<!--<th scope="row" class="check-column">
			<input id="cb-select-14692" type="checkbox" name="post[]" value="14692" />
		</th>-->
		<td class="title column-title has-row-actions" data-colname="Title">
			<strong>
				<a class="row-title" href="<?php echo $link_to_show ?>" target="_BLANK"><?php echo $title;?> &mdash; <span class='post-state'><?php echo $date -> format('F d, Y'); ?></span></a>
			</strong>
			<!--<div class="row-actions">
				<span class='edit'>
					<a href="" title="Edit this item">Edit</a> | </span>
				<span class='inline'>
					<a href="#" class="editinline" title="Edit this item inline">Quick&nbsp;Edit</a> | </span>
				<span class='trash'>
					<a class='submitdelete' title='Move this item to the Trash' href=''>Trash</a> | </span>
				<span class='view'>
					<a href="" title="View &#8220;test&#8221;" rel="permalink">View</a></span>
			</div>-->
		</td>
	</tr>
	<!--NEXT POST-->
	<?php
		}
		$index++;
	}//end foreach
	?>
	</tbody>
</table>
	<?php
}

/*function to donwload the archive from our internal server.
admin_post makes you be logged in to wordpress to use this.
The archive page will have a download link that looks like admin-post.php?title=Call_it_Classical&date=2016-03-03
This will send the file to the user

Need to double check that you can edit the show you are requesting. Skip the check if you can edit_others_shows
*/
add_action('admin_post_sting_download_archive', 'sting_download_archive');
function sting_download_archive() {
	//http://128.151.44.36/archives/showArchive.php?title=Call_it_Classical&amp;date=2016-03-03&amp;download&amp;authors
	$base = 'http://128.151.44.36/archives/showArchive.php?title=';//url base
	$title = esc_url($_GET['title']);//wordpress built in function for escaping things that should go in a url
	$title = substr($title, strpos($title, '//') + 2);//strip off the http:// that esc_url adds
	//check that the current user is requesting one of his/her shows
	error_log($title);
	$real_title = str_replace("_", " ", $title);
	$posts_to_get = get_option('sting_admin_options')['num_shows_input_box'];
	global $show_type;
	$args = array(
		'posts_per_page' => $posts_to_get,
		'post_type' 	 => $show_type
	);
	$posts = get_posts($args);
	if (!current_user_can('edit_others_shows')) {
		$success = false;
		$current_id = get_current_user_id();
		foreach($posts as $current_post) {
			if ($current_post -> post_title === $real_title && $current_post -> post_author == $current_id) {
				$success = true;
				break;
			}
		}
		if (!$success) {
			error_log('The request does not come from one of the authors of the requested shows');
			http_response_code(401);
			echo '<h1>You can only get archives of your own shows</h1>';
			wp_die();
		}
	}
	$date = $_GET['date'];
	//validate the date (no pun intended)
	$dateComponents = explode('-', $date);//split the date by day month year
	//print_r($dateComponents);
	if (checkdate($dateComponents[1], $dateComponents[2], $dateComponents[0]) == false) {//check if it is a valid date
		http_response_code(401);
		error_log('INVALID DATE');
		error_log($date);
		echo 'INVALID DATE ';
		echo $date;
		wp_die();
	}
	$fileLocation = $base.$title.'&date='.$date.'&download'.'&authors';
	//error_log($fileLocation);
	/************************************************************************************************
	 *		IT Security prevents us from forcing a download through their server.					*
	 *		At the present time, the best solution is to redirect the user to the download link,	*
	 *		even though they could theoretically download anybody's arhcive...						*
	 ************************************************************************************************/
	 header('Location: '.$fileLocation);
	
	
	/*
	//var_dump($fileLocation);
	//wp_die();
	//Ansewr 1 https://stackoverflow.com/questions/10991443/curl-get-remote-file-and-force-download-at-same-time
	//code for getting the size of the download from the url's http headers. Found online.
	$ch = curl_init($fileLocation);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, TRUE);
	curl_setopt($ch, CURLOPT_NOBODY, TRUE);
	$data = curl_exec($ch);
	$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if ($response_code == 400) {
		http_response_code(400);
		echo "<h1>400 Error. Invalid Parameters</h1>\n";
		curl_close($ch);
		wp_die();
	} else if ($response_code == 404) {
		http_response_code(404);
		echo "<h1>404 Error. File Not Found.</h1>\n";
		curl_close($ch);
		wp_die();
	}
	
	$size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
	//var_dump($size);
	curl_close($ch);
	
	header('Content-Disposition: attachment; filename="archive-'.$title.'_'.$date.'.mp3"');
	header('Content-Type: audio/mpeg;');
	header('Content-Description: File Transfer');
	header("Cache-Control: private, must-revalidate, no-cache"); 
	header('Expires: 0');
	header('Content-Length: '.$size);
	
	
	$ch = curl_init($fileLocation);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	$data = curl_exec($ch);
	curl_close($ch);
	echo $data;
	//readfile($fileLocation);
	//$string = file_get_contents($fileLocation);
	//echo $string;
	//phpinfo();
	wp_die();
	*/
}

/*function chunk($ch, $str) {
    print($str);
    return strlen($str);
}*/
?>
