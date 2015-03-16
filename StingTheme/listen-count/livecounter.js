function loadListenerCount() {
	var request = new XMLHttpRequest();
	request.open("GET", stingTemplateDir + "/listen-count/livecounter.php", false);
	request.send();
	document.getElementById("listen-count").innerHTML = request.responseText;
}
$(document).ready(function() {
	window.setInterval("loadListenerCount();", 5000);
	loadListenerCount();
});