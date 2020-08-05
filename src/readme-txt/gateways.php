<?php
/**
 * Readme gateways.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2020 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

// Providers.
$data = file_get_contents( __DIR__ . '/../providers.json' );

// Check if file could be read.
if ( false === $data ) {
	return;
}

$data = json_decode( $data );

$providers = array();

foreach ( $data as $provider ) {
	$providers[ $provider->slug ] = $provider;
}

// Gateways.
$data = file_get_contents( __DIR__ . '/../gateways.json' );

// Check if file could be read.
if ( false === $data ) {
	return;
}

$gateways = json_decode( $data );

foreach ( $gateways as $gateway ) {
	if ( ! isset( $providers[ $gateway->provider ] ) ) {
		continue;
	}

	$provider = $providers[ $gateway->provider ];

	if ( ! isset( $provider->gateways ) ) {
		$provider->gateways = array();
	}

	$provider->gateways[ $gateway->slug ] = $gateway;
}

// Print gateways.
foreach ( $providers as $provider ) {
	foreach ( $provider->gateways as $gateway ) {
		$note = '';

		if ( \property_exists( $gateway, 'license' ) ) {
			$note = \sprintf(
	 			' (requires [%s license](https://www.pronamic.eu/plugins/pronamic-pay/))',
	 			$gateway->license
			);
		}

		printf( "*	%s%s\n", $gateway->name, $note );
	}
}
