<?php
/*
Plugin Name: Pronamic iDEAL
Plugin URI: http://pronamic.eu/wp-plugins/ideal/
Description: Integrates iDEAL in to WordPress
 
Version: beta-0.9.8
Requires at least: 3.0

Author: Pronamic
Author URI: http://pronamic.eu/

Text Domain: pronamic_ideal
Domain Path: /languages/

License: GPL
*/

if(function_exists('spl_autoload_register')):

function pronamic_ideal_autoload($name) {
	$name = str_replace('\\', DIRECTORY_SEPARATOR, $name);
	$name = str_replace('_', DIRECTORY_SEPARATOR, $name);

	$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $name . '.php';

	if(is_file($file)) {
		require_once $file;
	}
}

spl_autoload_register('pronamic_ideal_autoload');

require_once 'functions/wp-e-commerce.php';

Pronamic_WordPress_IDeal_Plugin::bootstrap(__FILE__);

endif;
