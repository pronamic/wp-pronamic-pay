<?php
/**
 * Readme changelog.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2023 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

require __DIR__ . '/../../vendor/autoload.php';

$changelog = new \Pronamic\CLI\Changelog( __DIR__ . '/../../CHANGELOG.md' );

$entries = array_slice( $changelog->get_entries(), 0, 5 );

foreach ( $entries as $entry ) {
	echo '= ', $entry->version, ' =', "\n";

	echo implode( "\n", $entry->get_lines() );

	echo str_repeat( "\n", 2 );
}

echo '[See changelog for all versions.](https://github.com/pronamic/wp-pronamic-pay/blob/main/CHANGELOG.md)';
