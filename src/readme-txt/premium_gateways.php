<?php
/**
 * Readme gateways.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

// Providers.
$data = file_get_contents( __DIR__ . '/../providers.json' );
$data = json_decode( $data );

$providers = array();

foreach ( $data as $provider ) {
	if ( ! isset( $provider->gateways ) ) {
		$provider->gateways = array();
	}

	$providers[ $provider->slug ] = $provider;
}

// Gateways.
$data     = file_get_contents( __DIR__ . '/../gateways.json' );
$gateways = json_decode( $data );

foreach ( $gateways as $gateway ) {
	if ( ! isset( $gateway->premium ) || true !== $gateway->premium ) {
		continue;
	}

	if ( ! isset( $providers[ $gateway->provider ] ) ) {
		continue;
	}

	$provider = $providers[ $gateway->provider ];

	$provider->gateways[ $gateway->slug ] = $gateway;
}

// Print gateways.
foreach ( $providers as $provider ) {
	foreach ( $provider->gateways as $gateway ) {
		printf( "*	%s\n", $gateway->name );
	}
}
