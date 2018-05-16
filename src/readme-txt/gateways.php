<?php

$data = file_get_contents( __DIR__ . '/../providers.json' );
$data = json_decode( $data );

$providers = array();
foreach ( $data as $provider ) {
	$providers[ $provider->slug ] = $provider;
}

$data     = file_get_contents( __DIR__ . '/../gateways.json' );
$gateways = json_decode( $data );

foreach ( $gateways as $gateway ) {
	if ( isset( $providers[ $gateway->provider ] ) ) {
		$provider = $providers[ $gateway->provider ];

		if ( ! isset( $provider->gateways ) ) {
			$provider->gateways = array();
		}

		$provider->gateways[ $gateway->slug ] = $gateway;
	}
}

foreach ( $providers as $provider ) {
	if ( isset( $provider->url ) ) {
		printf( "*	[%s](%s)\n", $provider->name, $provider->url );
	} else {
		printf( "*	%s\n", $provider->name );
	}

	foreach ( $provider->gateways as $gateway ) {
		printf( "	*	%s\n", $gateway->name );
	}
}
