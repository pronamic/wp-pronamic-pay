<?php
/*
Plugin Name: Pronamic iDEAL
Plugin URI: http://pronamic.eu/wordpress/ideal/
Description: Integrates iDEAL in to WordPress 
Version: 1.0
Requires at least: 3.0
Author: Pronamic
Author URI: http://pronamic.eu/
License: GPL
*/

function pronamic_ideal_autoload($name) {
	$name = str_replace('\\', DIRECTORY_SEPARATOR, $name);
	$name = str_replace('_', DIRECTORY_SEPARATOR, $name);

	$file = __DIR__ . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $name . '.php';

	if(is_file($file)) {
		require_once $file;
	}
}

spl_autoload_register('pronamic_ideal_autoload');

Pronamic_WordPress_IDeal_Plugin::bootstrap(__FILE__);
