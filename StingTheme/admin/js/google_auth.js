var authWindow = null;
function openGoogleAuth(url) {
	authWindow = window.open(url, 'google_auth', 'menubar=yes,location=yes,resizable=yes,scrollbars=yes,status=yes');
}