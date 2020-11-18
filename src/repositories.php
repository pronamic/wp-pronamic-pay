<?php
/**
 * Repositories.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2020 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

$working_dir      = getcwd();
$project_dir      = dirname( __DIR__ );
$repositories_dir = $project_dir . '/repositories';

$organisations = array(
	'pronamic'          => array(
		'wp-datetime' => 'DateTime',
		'wp-money'    => 'Money',
	),
	'wp-pay'            => array(
		'core' => 'core',
	),
	'wp-pay-gateways'   => array(
		'adyen'              => 'Adyen',
		'buckaroo'           => 'Buckaroo',
		'ems-e-commerce'     => 'EMS e-Commerce;',
		'icepay'             => 'ICEPAY',
		'ideal'              => 'iDEAL',
		'ideal-advanced-v3'  => 'iDEAL Advanced v3',
		'ideal-basic'        => 'iDEAL Basic',
		'ing-kassa-compleet' => 'ING Kassa Compleet',
		'mollie'             => 'Mollie',
		'mollie-ideal'       => 'Mollie iDEAL',
		'multisafepay'       => 'MultiSafepay',
		'nocks'              => 'Nocks',
		'ogone'              => 'Ingenico',
		'omnikassa'          => 'OmniKassa',
		'omnikassa-2'        => 'OmniKassa 2.0',
		'pay-nl'             => 'Pay.nl',
		'payvision'          => 'Payvision',
		'sisow'              => 'Sisow',
		'targetpay'          => 'TargetPay',
	),
	'wp-pay-extensions' => array(
		'charitable'             => 'Charitable',
		'contact-form-7'         => 'Contact Form 7',
		'easy-digital-downloads' => 'Easy Digital Downloads',
		'event-espresso'         => 'Event Espresso',
		'event-espresso-legacy'  => 'Event Espresso (legacy)',
		'formidable-forms'       => 'Formidable Forms',
		'give'                   => 'Give',
		'gravityforms'           => 'Gravity Forms',
		'memberpress'            => 'MemberPress',
		'ninjaforms'             => 'Ninja Forms',
		'restrict-content-pro'   => 'Restrict Content Pro',
		's2member'               => 's2Member',
		'woocommerce'            => 'WooCommerce',
		'wp-e-commerce'          => 'WP eCommerce',
	),
);

/**
 * Version update `awk` actions.
 *
 * @return string
 */
function version_update_awk_actions() {
	global $argv;

	$version_update = isset( $argv[2] ) ? $argv[2] : 'patch';

	switch ( $version_update ) {
		case 'major':
			return 'NR==1{printf "%s.0.0", ++$NR};';

		case 'minor':
			return 'NF==2{print ++$NF}; NF>0{$(NF-1)++; $NF=0; print};';

		case 'patch':
		default:
			return 'NF==1{print ++$NF}; NF>1{if(length($NF+1)>length($NF))$(NF-1)++; $NF=sprintf("%0*d", length($NF), ($NF+1)%(10^length($NF))); print};';
	}
}

if ( isset( $argv[1] ) && 'changelog-plugin' === $argv[1] ) {
	$changelog_release = fopen( __DIR__ . '/changelog-release.json', 'w+' );

	if ( false !== $changelog_release ) {
		fwrite( $changelog_release, '[null' );
	}
}

foreach ( $organisations as $organisation => $repositories ) {
	echo '# ', $organisation, PHP_EOL;

	foreach ( $repositories as $repository => $name ) {
		echo '- ', $repository, PHP_EOL;

		$git_url = sprintf(
			'https://github.com/%s/%s.git',
			$organisation,
			$repository
		);

		$git_dir = $repositories_dir . '/' . $organisation . '/' . $repository;

		if ( ! is_dir( $git_dir ) ) {
			echo shell_exec( 'git clone ' . $git_url . ' ' . $git_dir );
		}

		// Git flow.
		chdir( $git_dir );

		$command = null;

		if ( isset( $argv[1] ) && 'develop' === $argv[1] ) {
			$command = 'git checkout develop';
		}

		if ( isset( $argv[1] ) && 'master' === $argv[1] ) {
			$command = 'git checkout master';
		}

		if ( isset( $argv[1] ) && 'pull' === $argv[1] ) {
			$command = 'git pull';
		}

		if ( isset( $argv[1] ) && 'log' === $argv[1] ) {
			$command = 'git --no-pager log $(git describe --tags --abbrev=0)..HEAD --oneline';
		}

		if ( isset( $argv[1] ) && 'changelog' === $argv[1] ) {
			$command = '
				CURRENT_TAG=$(git describe --tags --abbrev=0);
				LOG=$(git --no-pager log ${CURRENT_TAG}..HEAD --oneline);

				# Exit if there are no changes in Git repository.
				if [ $(echo "$LOG" | wc -l) -eq 1 ]; then
					echo "Version: ${CURRENT_TAG}";
					exit;
				fi;

				# Set version numbers.
				NEW_VERSION=$(echo "$CURRENT_TAG" | awk -F. -v OFS=. \'' . version_update_awk_actions() . '\');
				echo "Version: ${CURRENT_TAG} --> ${NEW_VERSION}";

				# Add title and log.
				TITLE_LINENR=$(grep -n "## \[" CHANGELOG.md | head -2 | tail -1 | cut -d: -f1);
				LOG=$(echo "$LOG" | sed \'s/^[a-z0-9]\{7\}/-/\' | sed \'s/^- Merge tag.*//\'; echo "";);
				TITLE="## [${NEW_VERSION}] - $(date \'+%Y-%m-%d\')' . \PHP_EOL . '";
				ex -s -c "${TITLE_LINENR}i|${TITLE}${LOG}' . str_repeat( \PHP_EOL, 2 ) . '" -c x CHANGELOG.md;

				# Update footer links.
				LINK_LINENR=$(grep -n "\[unreleased\]" CHANGELOG.md | tail -1 | cut -d: -f1);
				LINK="[${NEW_VERSION}]: https://github.com/' . $organisation . '/' . $repository . '/compare/${CURRENT_TAG}...${NEW_VERSION}";
				CHANGELOG=$(cat CHANGELOG.MD | sed "${LINK_LINENR}s/${CURRENT_TAG}...HEAD/${NEW_VERSION}...HEAD/" | tee CHANGELOG.md);
				ex -s -c "$(( ${LINK_LINENR} + 1 ))i|${LINK}" -c x CHANGELOG.md';
		}

		if ( isset( $argv[1] ) && 'changelog-plugin' === $argv[1] ) {
			$command = '
				CURRENT_TAG=$(git describe --tags --abbrev=0);
				NEW_VERSION=$(cat package.json | jq --raw-output \'.version\' );

				# Exit if there are no changes in Git repository.
				if [[ "$CURRENT_TAG" == "$NEW_VERSION" ]]; then
					echo "Version: ${CURRENT_TAG}";
					exit;
				fi;

				# Echo new version number.
				echo "Version: ${CURRENT_TAG} --> ${NEW_VERSION}";

				# Write temporary changelog JSON.
				FROM=$(( $(grep -n "## \[" CHANGELOG.md | head -2 | tail -1 | cut -d: -f1) + 1 ));
				TO=$(( $(grep -n "## \[" CHANGELOG.md | head -3 | tail -1 | cut -d: -f1) - 2 ));
				LOG=$(cat CHANGELOG.md | head -n $TO | tail -n +$FROM );
				echo "${LOG}"
				echo ",{\"description\":\"Updated WordPress ' . ( 'pronamic' === $organisation ? '' : 'pay ' ) . $name . ' library to version ${NEW_VERSION}.\",\"changes\":$(echo "${LOG}" | sed \'s/^- //\' | jq --raw-input --raw-output --slurp \'split("\\n") | .[0:-1]\')}" >> ../../../src/changelog-release.json';
		}

		if ( isset( $argv[1] ) && 'update-package-version' === $argv[1] ) {
			$command = '
				CURRENT_TAG=$(git describe --tags --abbrev=0);
				LOG=$(git --no-pager log ${CURRENT_TAG}..HEAD --oneline);

				# Exit if there are no changes in Git repository.
				if [ $(echo "$LOG" | wc -l) -eq 1 ]; then
					echo "Version: ${CURRENT_TAG}";
					exit;
				fi;

				# Set version numbers.
				NEW_VERSION=$(echo "$CURRENT_TAG" | awk -F. -v OFS=. \'' . version_update_awk_actions() . '\');
				echo "Version: ${CURRENT_TAG} --> ${NEW_VERSION}";

				# Update version number.
				VERSION_LINENR=$(grep -n "\"version\":" package.json | tail -1 | cut -d: -f1);
				PACKAGE_JSON=$(cat package.json | sed "${VERSION_LINENR}s/\"${CURRENT_TAG}\"/\"${NEW_VERSION}\"/" | tee package.json);';
		}

		if ( isset( $argv[1] ) && in_array( $argv[1], array( 'git', 'grunt', 'composer', 'npm', 'ncu' ), true ) ) {
			if ( isset( $argv[2] ) ) {
				$command = sprintf( '%s %s', $argv[1], $argv[2] );
			} else {
				$command = sprintf( '%s', $argv[1] );
			}
		}

		if ( null !== $command ) {
			if ( ! isset( $argv[1] ) || ( isset( $argv[1] ) && ! in_array( $argv[1], array( 'changelog', 'changelog-plugin', 'update-package-version' ), true ) ) ) {
				echo $command, PHP_EOL;
			}

			echo shell_exec( $command ), PHP_EOL;
		}
	}
}

if ( isset( $argv[1] ) && 'changelog-plugin' === $argv[1] ) {
	// Get release changelog items from temporary file.
	$changelog_plugin = file_get_contents( __DIR__ . '/changelog-release.json' ) . ']';
	$changelog_plugin = str_replace( '\\', '\\\\', $changelog_plugin );
	$changelog_plugin = str_replace( '\\\\"', '\\"', $changelog_plugin );

	unlink( __DIR__ . '/changelog-release.json' );

	$updates = json_decode( $changelog_plugin );

	array_shift( $updates );

	// Release item.
	$package = file_get_contents( __DIR__ . '/../package.json' );

	// Check if package file could be read.
	if ( false === $package ) {
		return;
	}

	$package = json_decode( $package );

	$release = array(
		array(
			'version' => $package->version,
			'date'    => gmdate( 'Y-m-d' ),
			'changes' => array(
				'name'    => 'Changed',
				'changes' => $updates,
			),
		),
	);

	// Insert in changelog after 'Unreleased' item.
	$changelog = file_get_contents( __DIR__ . '/changelog.json' );

	// Check if changelog file could be read.
	if ( false === $changelog ) {
		return;
	}

	$changelog = json_decode( $changelog );

	array_splice( $changelog, 1, 0, $release );

	// Use tabs for indentation.
	// phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
	$changelog_json = json_encode( $changelog, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

	// Check if changelog could be JSON encoded.
	if ( false === $changelog_json ) {
		return;
	}

	$json = preg_replace_callback(
		'/^ +/m',
		/**
		 * Repeated tabs based on indentation length.
		 *
		 * @param array<int, string> $indentation Indentation spaces.
		 * @return string
		 */
		function ( $indentation ) {
			return str_repeat( '	', (int) ceil( strlen( $indentation[0] ) / 4 ) );
		},
		$changelog_json
	);

	// Write updated changelog.
	$handle = fopen( __DIR__ . '/changelog.json', 'w+' );

	if ( false !== $handle ) {
		fwrite( $handle, $json . PHP_EOL );
	}
}
