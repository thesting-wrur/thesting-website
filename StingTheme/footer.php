    <div id="content-darken"></div>

    <div class="stream show-for-small-only">
      <a class="button button-dark stream-button" onclick="toggleStream();"><small>LISTEN LIVE</small></a>
    </div>
	<audio id="stream-player" preload="none">
		<source src="http://wrur.ur.rochester.edu:8000/thestinghi" type="audio/mpeg">
	</audio>
	<!--Start wp_footer() stuff -->
	<?php wp_footer(); ?>
	<!--End wp_footer() stuff -->
  </body>
</html>
