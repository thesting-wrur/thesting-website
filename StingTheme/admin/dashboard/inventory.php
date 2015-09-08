<?php
require_once 'listen-count/livecounter.php';
add_action('wp_dashboard_setup', 'sting_add_inventory_widget');

function sting_add_inventory_widget() {
	wp_add_dashboard_widget(
		'sting_inventory_widget',
		'Inventory System',
		'sting_setup_inventory_widget'
	);
}

function sting_setup_inventory_widget() {
	?>
	To access the inventory system, you will need these credentials:<br>
	Username: wrur<br>
	Password: wrur885<br>
	<br>
	<a href="http://128.151.44.36/mcat/">Music Catalog</a><br>
	<a href="http://128.151.44.36/invent/add.php">Add to the Inventory</a><br>
	<a href="http://128.151.44.36/invent/checkout.php">Check out an item</a><br>
	<a href="http://128.151.44.36/invent/checkin.php">Check in an item</a><br>
	<br>
	If you have any problems or questions, please contact <a href="mailto:treiss@u.rochester.edu?subject=WRUR-inventory">Teddy</a>, or another engineer.
	<?php
}
?>
