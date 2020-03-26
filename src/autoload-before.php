<?php

if ( ! PRONAMIC_PAY_DEBUG ) {
	return;
}

global $__composer_autoload_files;

if ( null === $__composer_autoload_files ) {
	$__composer_autoload_files = array();
}

foreach ( glob( __DIR__ . '/../repositories/*/*/vendor/composer/autoload_files.php' ) as $file ) {
	$files = require $file;

	foreach ( $files as $identifier => $filepath ) {
		if ( array_key_exists( $identifier, $__composer_autoload_files ) ) {
			continue;
		}

		require $filepath;

		$__composer_autoload_files[ $identifier ] = true;
	}
}
