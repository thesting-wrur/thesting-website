<?php
//setup the parent page, then the child pages
$sting_admin_slug = 'sting_admin';
function register_sting_admin_page() {
	global $sting_admin_slug;
	add_menu_page( 'Sting Admin', 'Sting', 'edit_theme_options', $sting_admin_slug, 'sting_admin_page', 'dashicons-admin-generic', '90' );
	add_submenu_page($sting_admin_slug, 'Sting Admin', 'Admin', 'edit_theme_options', $sting_admin_slug);
}
add_action( 'admin_menu', 'register_sting_admin_page' );
//setup the parent page, then the child pages
require_once 'services-admin.php';

function sting_admin_page() {
echo 'Hello - Welcome to my admin page';
}
?>