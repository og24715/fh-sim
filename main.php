<?php
require 'function.php';

$armor = new Armor;
$weapon = new Weapon;

$json_update = (string)filter_input(INPUT_GET, 'json_update');
$request = (string)filter_input(INPUT_GET, 'r');

if ($json_update === 'y') {
	$armor->update_json();
	$weapon->update_json();
} else {
	switch ( $request ) {
		case 'armor':
			echo $armor->get_new_json();
			break;
		case 'weapon':
			echo $weapon->get_new_json();
			break;
		default:
			echo "Invalid request";
			break;
	}
}