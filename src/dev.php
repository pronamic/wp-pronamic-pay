<?php

$branch = 'main';

if ( \array_key_exists( 1, $argv ) ) {
	$branch = $argv[1];
}

$packages = [
	'pronamic/wp-datetime',
	'pronamic/wp-html',
	'pronamic/wp-http',
	'pronamic/wp-mollie',
	'pronamic/wp-money',
	'pronamic/wp-number',
	'pronamic/wp-pay-logos',
	'pronamic/wp-gravityforms-nl',
	'pronamic/wp-pronamic-pay-forms',
	// Core.
	'wp-pay/core',
	'wp-pay/fundraising',
	// Gateways.
	'wp-pay-gateways/adyen',
	'wp-pay-gateways/buckaroo',
	'wp-pay-gateways/ems-e-commerce',
	'wp-pay-gateways/ideal',
	'wp-pay-gateways/ideal-advanced-v3',
	'wp-pay-gateways/ideal-basic',
	'wp-pay-gateways/mollie',
	'wp-pay-gateways/multisafepay',
	'wp-pay-gateways/ogone',
	'wp-pay-gateways/omnikassa-2',
	'wp-pay-gateways/pay-nl',
	'wp-pay-gateways/paypal',
	// Extensions.
	'wp-pay-extensions/charitable',
	'wp-pay-extensions/contact-form-7',
	'wp-pay-extensions/easy-digital-downloads',
	'wp-pay-extensions/event-espresso',
	'wp-pay-extensions/formidable-forms',
	'wp-pay-extensions/give',
	'wp-pay-extensions/gravityforms',
	'wp-pay-extensions/memberpress',
	'wp-pay-extensions/ninjaforms',
	'wp-pay-extensions/restrict-content-pro',
	'wp-pay-extensions/woocommerce',
];

$json = file_get_contents( __DIR__ . '/../composer.lock' );

if ( false === $json ) {
	echo 'File `composer.lock` could not be read.';

	exit;
}

$composer_lock = json_decode( $json );

$requirements = [];

if ( is_object( $composer_lock ) && isset( $composer_lock->packages ) ) {
	foreach ( $composer_lock->packages as $package ) {
		if ( ! in_array( $package->name, $packages ) ) {
			continue;
		}

		$requirement = $package->name;

		if ( '' !== $branch ) {
			if ( str_starts_with( $package->version, 'dev-' ) ) {
				continue;
			}

			$requirement .= ':dev-' . $branch . ' as ' . $package->version;
		}

		$requirements[] = $requirement;
	}
}

if ( count( $requirements ) > 0 ) {
	$arguments = implode( ' ', array_map( 'escapeshellarg', $requirements ) );

	$command = "composer require $arguments --prefer-source";

	echo $command, PHP_EOL;

	passthru( $command );
}
