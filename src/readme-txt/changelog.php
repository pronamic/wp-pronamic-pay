<?php
/**
 * Readme changelog.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2022 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

require __DIR__ . '/../../vendor/autoload.php';

$changelog = new \Pronamic\Deployer\Changelog( __DIR__ . '/../../CHANGELOG.md' );

$entry = $changelog->get_entry( $pkg->version );

if ( null !== $entry ) {
	echo '= ', $pkg->version, ' =', "\n";
	echo implode( "\n", $entry->get_lines() );
	echo "\n";
}

echo "\n";
echo '[See changelog for all versions.](https://www.pronamic.eu/plugins/pronamic-pay/changelog/)';
