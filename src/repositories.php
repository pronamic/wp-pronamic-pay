<?php

$working_dir      = getcwd();
$project_dir      = dirname( __DIR__ );
$repositories_dir = $project_dir . '/repositories';

$organisations = array(
	'wp-pay'            => array(
		'core',
	),
	'wp-pay-gateways'   => array(
		'common',
		'abnamro-ideal-easy',
		'abnamro-ideal-only-kassa',
		'abnamro-ideal-zelfbouw-v3',
		'abnamro-internetkassa',
		'buckaroo',
		'deutschebank-ideal-expert-v3',
		'deutschebank-ideal-via-ogone',
		'easy-ideal',
		'ems-e-commerce',
		'fibonacciorange',
		'icepay',
		'ideal',
		'ideal-advanced-v3',
		'ideal-basic',
		'ideal-simulator-ideal-advanced-v3',
		'ideal-simulator-ideal-basic',
		'ing-ideal-advanced-v3',
		'ing-ideal-basic',
		'ing-kassa-compleet',
		'mollie',
		'mollie-ideal',
		'mollie-ideal-basic',
		'multisafepay',
		'ogone',
		'omnikassa',
		'omnikassa-2',
		'pay-nl',
		'paytor',
		'postcode-ideal',
		'qantani-mollie',
		'rabobank-ideal-professional-v3',
		'sisow',
		'sisow-ideal-basic',
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

		chdir( $working_dir );

		$target = $project_dir . '/repositories/' . $organisation . '/' . $repository;
		$link   = $project_dir . '/vendor/' . $organisation . '/' . $repository;

		$command = sprintf( 'rm -rf %s', escapeshellarg( $link ) );

		echo $command, PHP_EOL;

		echo shell_exec( $command ), PHP_EOL;

		$command = sprintf( 'ln -s %s %s', escapeshellarg( $target ), escapeshellarg( $link ) );

		echo $command, PHP_EOL;

		echo shell_exec( $command ), PHP_EOL;
	}
}
