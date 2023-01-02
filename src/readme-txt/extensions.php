<?php
/**
 * Readme extensions.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2023 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

$data = file_get_contents( __DIR__ . '/../extensions.json' );

// Check if file could be read.
if ( false === $data ) {
	return;
}

$extensions = json_decode( $data );

foreach ( $extensions as $extension ) {
	$note = '';

	if ( \property_exists( $extension, 'license' ) ) {
		$note = \sprintf(
			' (requires [%s license](https://www.pronamicpay.com/))',
			$extension->license
		);
	}

	printf(
		"*	[%s](%s)%s\n",
		$extension->name,
		$extension->url,
		$note
	);
}
