<?php

$working_dir      = getcwd();
$project_dir      = dirname( __DIR__ );
$repositories_dir = $project_dir . '/repositories';

$organisations = array(
	'pronamic'          => array(
		'wp-datetime',
		'wp-money',
	),
	'wp-pay'            => array(
		'core',
	),
	'wp-pay-gateways'   => array(
		'common',
		'buckaroo',
		'ems-e-commerce',
		'icepay',
		'ideal',
		'ideal-advanced-v3',
		'ideal-basic',
		'ing-kassa-compleet',
		'mollie',
		'mollie-ideal',
		'multisafepay',
		'nocks',
		'ogone',
		'omnikassa',
		'omnikassa-2',
		'pay-nl',
		'sisow',
		'targetpay',
	),
	'wp-pay-extensions' => array(
		'appthemes',
		'charitable',
		'classipress',
		'easy-digital-downloads',
		'event-espresso',
		'event-espresso-legacy',
		'formidable-forms',
		'give',
		'gravityforms',
		'ithemes-exchange',
		'jigoshop',
		'memberpress',
		'membership',
		'restrict-content-pro',
		's2member',
		'shopp',
		'woocommerce',
		'wp-e-commerce',
	),
);

foreach ( $organisations as $organisation => $repositories ) {
	echo '# ', $organisation, PHP_EOL;

	foreach ( $repositories as $repository ) {
		echo '- ', $repository, PHP_EOL;

		$git_url = sprintf(
			'https://github.com/%s/%s.git',
			$organisation,
			$repository
		);

		$git_dir = $repositories_dir . '/' . $organisation . '/' . $repository;

		if ( ! is_dir( $git_dir ) ) {
			`git clone $git_url $git_dir`;
		}

		// Git flow
		chdir( $git_dir );

		if ( isset( $argv[1] ) && 'develop' === $argv[1] ) {
			`git checkout develop`;
		}

		if ( isset( $argv[1] ) && 'pull' === $argv[1] ) {
			$command = 'git pull';

			echo $command, PHP_EOL;

			echo shell_exec( $command ), PHP_EOL;
		}

		if ( isset( $argv[1], $argv[2] ) && in_array( $argv[1], array( 'git', 'composer', 'yarn' ) ) ) {
			$command = sprintf( '%s %s', $argv[1], $argv[2] );
		}

		if ( null !== $command ) {
			echo $command, PHP_EOL;

			echo shell_exec( $command ), PHP_EOL;
		}
	}
}
