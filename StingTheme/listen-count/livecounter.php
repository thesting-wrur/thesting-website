<?php
/*
 * Copied from current sting site. To Be Simplified.
 * Screen Scraper
 */
function getNumberOfListeners() {
        $ur=$_SERVER['REQUEST_URI'];

        $scraped = curl("wrur.ur.rochester.edu:8000");
        //echo $scraped;
        $DOM = new DOMDocument;
        $DOM ->loadHTML($scraped);
                $items = $DOM ->getElementsByTagName('td');
				$stuff = 0;
        for($i = 0; $i<$items->length; $i++) {
                if($items->item($i)->nodeValue == "Current Listeners:") {
						$stuff += $items->item($i+1)->nodeValue;
                }
        }

	$numb = $stuff;
	//echo '<marquee width="25%" behavior="alternate">';
	if($numb=='1') {
		echo "There is <b>1</b> listener";
	} else if ($numb == '0') {
		echo "There are <b>no</b> listeners";
	} else {
		echo "There are <b>".$numb."</b> listeners";
	}
	//echo '</marquee>';
  echo "<br>";
  
}

function getNumberOfLiveListeners() {
        $ur=$_SERVER['REQUEST_URI'];

        $scraped = curl("wrur.ur.rochester.edu:8000");
        //echo $scraped;
        $DOM = new DOMDocument;
        $DOM ->loadHTML($scraped);
                $items = $DOM ->getElementsByTagName('td');
				$stuff = 0;
        for($i = 0; $i<$items->length; $i++) {
                if($items->item($i)->nodeValue == "Current Listeners:") {
                        $stuff= $items->item($i+1)->nodeValue;
                }
        }
  $numb = $stuff;
  if($numb=='1')
 	echo "<center>There is 1 listener</center>";
  else 
    echo "<center>There are ".$numb." listeners</center>";
  //echo "<br>";
  
}


function curl($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,TRUE);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
 }
getNumberOfListeners();
?>
