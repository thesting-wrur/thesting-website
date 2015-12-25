<!--start footer.php-->
    <div id="content-darken"></div><!--div to allow the screen to get darker when the mobile menu opens-->
	<div class="space-filler"></div>
    <!--<div id="mobile-listen-live" class="stream show-for-small-only show">
      <a class="button button-dark stream-button" onclick="showMobileStream();"><small>LISTEN LIVE</small></a>
    </div>-->
	<!-- Bottom portion of the screen on mobile devices. Has the current show information as well as the play button-->
	<div id="mobile-listen-live-panel" class="stream show-for-small-only button button-dark stream-button"><!--onclick="hideMobileStream(event);" - class hide removed-->
		<span id="mobile-play-pause-outer" onclick="toggleStream();">Listen Now<span id="mobile-play-pause-inner" class="dashicons dashicons-controls-play" style="display:none;"></span></span>
		<br>
		Current Show: <span class="current-show">The Sting</span>
		<br>
		<span class="now-playing-container" style="display: none;">Now Playing: <span class="now-playing-content"></span></span>
	</div>
	<!--Audio Player for the sting-->
	<audio id="stream-player" preload="none">
		<source src="http://wrur.ur.rochester.edu:8000/thestinghi" type="audio/mpeg">
	</audio>
	
	</div><!--end content section-->
	<!--Start wp_footer() stuff -->
	<?php wp_footer(); ?>
	<!--End wp_footer() stuff -->
  </body>
</html>
