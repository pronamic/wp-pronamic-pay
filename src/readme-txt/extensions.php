<?php
/**
 * Readme extensions.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

$data       = file_get_contents( __DIR__ . '/../extensions.json' );
$extensions = json_decode( $data );

foreach ( $extensions as $extension ) {
	printf(
		"*	[%s](%s)\n",
		$extension->name,
		$extension->url
	);
}
