<?php
/**
 * After autoload.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2020 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

if ( ! PRONAMIC_PAY_DEBUG ) {
	return;
}

foreach ( glob( __DIR__ . '/../repositories/*/*/composer.json' ) as $file ) {
	$content = file_get_contents( $file );

	$object = json_decode( $content );

	if ( ! isset( $object->autoload ) ) {
		continue;
	}

	foreach ( $object->autoload as $autoload_type => $classmap ) {
		if ( 'psr-4' !== $autoload_type ) {
			continue;
		}

		foreach ( $classmap as $prefix => $filepath ) {
			$loader->addPsr4( $prefix, dirname( $file ) . '/' . $filepath, true );
		}
	}
}
