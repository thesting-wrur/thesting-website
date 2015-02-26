<?php
/*
 * Default Page Template.
 * Note, all pages who are children of the "Schedule" page will be rendered with the "show" template.
 * All other pages will be rendered with the "normalPage" template.
 * This is so that when we add shows, the person who is adding the show only has to set the parent - they don't have to set both the parent and the template.
 */
$id = get_queried_object_id();
$page = get_post($id);
if ($page -> post_parent == 2239 || $page -> post_parent == 2145) {
	get_template_part('single-show', '');
} else {
	get_template_part('normalPage','');
}
?>