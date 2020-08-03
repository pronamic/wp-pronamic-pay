<?php
/**
 * Before autoload.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2020 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

if ( ! PRONAMIC_PAY_DEBUG ) {
	return;
}

if ( ! isset( $GLOBALS['__composer_autoload_files'] ) ) {
	$GLOBALS['__composer_autoload_files'] = array();
}

$files = glob( __DIR__ . '/../repositories/*/*/vendor/composer/autoload_files.php' );

// Check for glob error.
if ( false === $files ) {
	return;
}

foreach ( $files as $file ) {
	$files = require $file;

	foreach ( $files as $identifier => $filepath ) {
		if ( array_key_exists( $identifier, $GLOBALS['__composer_autoload_files'] ) ) {
			continue;
		}

		// Make sure to load `wp-pay/core` files from development repository,
		// instead of from `vendor` directory of gateways and extensions.
		if ( false !== strpos( $filepath, '/vendor/wp-pay/core/' ) ) {
			continue;
		}

		require $filepath;

		$GLOBALS['__composer_autoload_files'][ $identifier ] = true;
	}
}
