<?php
//require_once 'listen-count/livecounter.php'; Hopefully we can remove this
add_action('wp_dashboard_setup', 'sting_add_listener_count_widget');

function sting_add_listener_count_widget() {
	wp_add_dashboard_widget(
		'sting_listener_count_widget',
		'Listener Count',
		'sting_setup_listener_count_widget'
	);
}

function sting_setup_listener_count_widget() {
	echo '<span id="sting-listen-count">&nbsp;</span>';
}

function sting_listen_count_admin_scripts($hook) {
	if ($hook == 'index.php') {
		wp_register_script('sting-listen-count-script', get_template_directory_uri().'/admin/dashboard/listen-count/livecounter.js');
		// Disabled until the problem can be fixed
		wp_enqueue_script('sting-listen-count-script');
	}
}
add_action('admin_enqueue_scripts', 'sting_listen_count_admin_scripts');

//setup ajax for live-updating the current show
add_action( 'wp_ajax_listener_count', 'send_listener_count_data' );
function send_listener_count_data() {
	//getNumberOfListeners();
	
	//$icecastStats = file_get_contents("http://wrur.ur.rochester.edu:8000/status-json.xsl");
	
	$url = "http://wrur.ur.rochester.edu:8000/status-json.xsl";
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$icecastStats = curl_exec($ch);
	if (curl_errno($ch)) {
		error_log(curl_error($ch));
	} else {
		curl_close($ch);
	}
	//error_log($icecastStats);
	$icecastJson = json_decode($icecastStats);
	$listeners = 0;
	
	
	/*foreach ($icecastJson -> icestats -> source as $source) {
		$listeners += $source -> listeners;
	}*/
	$count = count($icecastJson -> icestats -> source);
	if ($count > 1) {
		foreach ($icecastJson -> icestats -> source as $source) {
			$listeners += $source -> listeners;
		}
	} else if ($count == 1) {
		$listeners = $icecastJson -> icestats -> source -> listeners;
	}
	$toReturn = '';
	if($listeners == 1) {
		$toReturn .= "There is <strong>1</strong> listener";
	} else if ($listeners == 0) {
		$toReturn .= "There are <strong>no</strong> listeners";
	} else {
		$toReturn .= "There are <strong>".$listeners."</strong> listeners";
	}
	echo $toReturn;
	wp_die();
}
?>
