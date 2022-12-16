<?php
/**
 * Repositories.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2022 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

$working_dir      = getcwd();
$project_dir      = dirname( __DIR__ );
$repositories_dir = $project_dir . '/repositories';

$organisations = [
	'pronamic' => [
		'wp-datetime'                            => 'DateTime',
		'wp-html'                                => 'HTML',
		'wp-http'                                => 'HTTP',
		'wp-money'                               => 'Money',
		'wp-number'                              => 'Number',
		'wp-pay-core'                            => 'core',
		'wp-pay-logos'                           => 'Logos',
		'wp-pronamic-pay-fundraising'            => 'Fundraising',
		'wp-gravityforms-nl'                     => 'Gravity Forms (nl)',
		// Gateways.
		'wp-pronamic-pay-adyen'                  => 'Adyen',
		'wp-pronamic-pay-buckaroo'               => 'Buckaroo',
		'wp-pronamic-pay-digiwallet'             => 'DigiWallet',
		'wp-pronamic-pay-ems-e-commerce'         => 'EMS e-Commerce;',
		'wp-pronamic-pay-icepay'                 => 'ICEPAY',
		'wp-pronamic-pay-ideal'                  => 'iDEAL',
		'wp-pronamic-pay-ideal-advanced-v3'      => 'iDEAL Advanced v3',
		'wp-pronamic-pay-ideal-basic'            => 'iDEAL Basic',
		'wp-pronamic-pay-mollie'                 => 'Mollie',
		'wp-mollie'                              => 'Mollie (library)',
		'wp-pronamic-pay-multisafepay'           => 'MultiSafepay',
		'wp-pronamic-pay-ingenico'               => 'Ingenico',
		'wp-pronamic-pay-omnikassa-2'            => 'OmniKassa 2.0',
		'wp-pronamic-pay-pay-nl'                 => 'Pay.nl',
		'wp-pronamic-pay-paypal'                 => 'PayPal',
		// Extensions.
		'wp-pronamic-pay-charitable'             => 'Charitable',
		'wp-pronamic-pay-contact-form-7'         => 'Contact Form 7',
		'wp-pronamic-pay-easy-digital-downloads' => 'Easy Digital Downloads',
		'wp-pronamic-pay-event-espresso'         => 'Event Espresso',
		'wp-pronamic-pay-formidable-forms'       => 'Formidable Forms',
		'wp-pronamic-pay-give'                   => 'Give',
		'wp-pronamic-pay-gravityforms'           => 'Gravity Forms',
		'wp-pronamic-pay-memberpress'            => 'MemberPress',
		'wp-pronamic-pay-ninjaforms'             => 'Ninja Forms',
		'wp-pronamic-pay-restrict-content-pro'   => 'Restrict Content Pro',
		'wp-pronamic-pay-woocommerce'            => 'WooCommerce',
	],
];

if ( isset( $argv[1] ) && 'release' === $argv[1] ) {
	$changelog_release = fopen( __DIR__ . '/changelog-release.json', 'w+' );

	if ( false !== $changelog_release ) {
		// phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_fwrite
		fwrite( $changelog_release, '[null' );
	}
}

foreach ( $organisations as $organisation => $repositories ) {
	foreach ( $repositories as $repository => $name ) {
		$header = sprintf( '#   %s/%s - %s   #', $organisation, $repository, $name );

		echo str_repeat( '#', strlen( $header ) ) . \PHP_EOL;
		echo '#' . str_repeat( ' ', strlen( $header ) - 2 ) . '#' . \PHP_EOL;
		echo $header . \PHP_EOL;
		echo '#' . str_repeat( ' ', strlen( $header ) - 2 ) . '#' . \PHP_EOL;
		echo str_repeat( '#', strlen( $header ) ) . \PHP_EOL;

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

		if ( isset( $argv[1] ) ) {
			$args = $argv;

			array_shift( $args );

			$command = implode( ' ', $args );
		}

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

		if ( isset( $argv[1] ) && 'release' === $argv[1] ) {
			$command = '
				bold=$(tput bold)
				normal=$(tput sgr0)

				CURRENT_TAG=$(git describe --tags --abbrev=0)

				echo "🌐 ${bold}GitHub URL${normal}"
				echo "    https://github.com/' . $organisation . '/' . $repository . '"
				echo ""
				echo "🔢 ${bold}Latest version${normal}"
				echo "    ${CURRENT_TAG}"
				echo ""

				if [ `git status --porcelain` ]; then
					echo "❗️ ${bold}Found uncommited changes, please commit/resolve before continuing${normal}"
					
					read -p "Press a key to continue..."
				fi

				LOG=$(git --no-pager log ${CURRENT_TAG}..HEAD --oneline| awk \'{a[i++]=$0} END {for (j=i-1; j>=0;) print a[j--] }\')

				if [ $(echo "$LOG" | wc -l) -eq 1 ]; then
					echo "❌ ${bold}No changes in Git since release of version ${CURRENT_TAG}${normal}"
					echo ""

					echo "📖 ${bold}Version ${CURRENT_TAG} in changelog:${normal}"
					echo ""

					CHANGELOG_RELEASE=$(grep -zoP "## \[${CURRENT_TAG}\](.|\n)*?(?=\n\n)" CHANGELOG.md)
					CHANGELOG_RELEASE_LOG=$(echo "$CHANGELOG_RELEASE" | tail -n +2)

					echo "$CHANGELOG_RELEASE"
					echo ""

					sleep .1

					select ACTION in "Include version ${bold}${CURRENT_TAG}${normal} in release" "Check coding standards" "Skip"; do
						case $ACTION in
							*release*)      $(echo ",{\"description\":\"Updated WordPress ' . ( false === strpos( $repository, '-pay-' ) ? '' : 'pay ' ) . $name . ' library to version ${CURRENT_TAG}.\",\"changes\":$(echo "${CHANGELOG_RELEASE_LOG}" | sed \'s/^- //\' | jq --raw-input --raw-output --slurp \'split("\\n") | .[0:-1]\')}" >> ../../../src/changelog-release.json)
											exit
											break;;
							*standards*)    break;;
							Skip*)          exit
											break;;
						esac
					done
				fi;

				if [ -f "composer.json" ]; then
					echo "🕵 ${bold}Checking coding standards...${normal}"

					if ! composer phpcs; then
						echo ""
						echo "🕵 ${bold}Fix or continue?${normal}"

						sleep .1

						select ACTION in "Fix with PHPCBF" "Retry" "Ignore and continue"; do
							case $ACTION in
								*PHPCBF*)   composer phpcbf
											read -p "Press a key to commit and continue..."
											git commit -a -m "Coding standards." --no-verify
											break;;
								Retry*)     composer phpcs
											read -p "Press a key to commit and continue..."
											git commit -a -m "Coding standards." --no-verify
											break;;
								Ignore*)    break;;
							esac
						done

						echo ""
					fi
				fi

				LOG=$(git --no-pager log ${CURRENT_TAG}..HEAD --oneline| awk \'{a[i++]=$0} END {for (j=i-1; j>=0;) print a[j--] }\')

				# Exit if there are no changes in Git repository.
				if [ $(echo "$LOG" | wc -l) -eq 1 ]; then
					echo "❌ No changes in Git since last release."

					exit
				fi;

				echo "📖 ${bold}Commits since latest release:${normal}"
				echo "$LOG" | while read line; do echo "https://github.com/' . $organisation . '/' . $repository . '/commit/$line"; done
				echo ""

				# Set version numbers.
				echo "🔢 ${bold}Select version number for release:${normal}"
				
				NEW_MAJOR_VERSION=$(echo "$CURRENT_TAG" | awk -F. -v OFS=. \'NR==1{printf "%s.0.0", ++$NR};\')
				NEW_MINOR_VERSION=$(echo "$CURRENT_TAG" | awk -F. -v OFS=. \'NF==2{print ++$NF}; NF>0{$(NF-1)++; $NF=0; print};\')
				NEW_PATCH_VERSION=$(echo "$CURRENT_TAG" | awk -F. -v OFS=. \'NF==1{print ++$NF}; NF>1{if(length($NF+1)>length($NF))$(NF-1)++; $NF=sprintf("%0*d", length($NF), ($NF+1)%(10^length($NF))); print};\')

				sleep .1

				select NEW_VERSION in "$NEW_MAJOR_VERSION" "$NEW_MINOR_VERSION" "$NEW_PATCH_VERSION" "Skip release"; do
					if [ "Skip release" == "$NEW_VERSION" ]; then
						exit
					fi

					break
				done

				echo ""
				echo "🆙 Updating ' . $name . ' library from version ${bold}${CURRENT_TAG}${normal} to ${bold}${NEW_VERSION}${normal}."
				echo ""

				echo "$LOG" | sed \'s/^[a-z0-9]\{7\} //\' | sed \'s/^Merge tag.*//\' | sed \'s/^Add /Added /\' | sed \'s/^Fix /Fixed /\' | sed \'s/^Update /Updated /\' | sed \'s/^Remove /Removed /\' | sed \'s/^Delete /Deleted /\' | awk \'NF\' - > "$TMPDIR/release-changelog.txt" 

				if [ -n "$VISUAL" ]; then
					echo "⏳ Waiting for changelog to be saved in external editor..."
					echo ""

					if [ "nova" == "$VISUAL" ]; then
						LASTMOD=$(date -r "$TMPDIR/release-changelog.txt")
 
						$VISUAL "$TMPDIR/release-changelog.txt" > /dev/null 2>&1 &

						while sleep 3; do
							MODIFIED=$(date -r "$TMPDIR/release-changelog.txt")

							if [ "$LASTMOD" != "$MODIFIED" ]; then
								break
							fi
						done
					else
						$VISUAL "$TMPDIR/release-changelog.txt"
					fi
				elif [ -n "$EDITOR" ]; then
					$EDITOR "$TMPDIR/release-changelog.txt"
				else
					nano "$TMPDIR/release-changelog.txt"
				fi

				LOG=$(cat "$TMPDIR/release-changelog.txt" | awk \'NF\' - | while read line; do echo "- $line"; done)

				rm "$TMPDIR/release-changelog.txt"

				echo "📃 ${bold}Changelog${normal}"
				echo "$LOG"
				echo ""

				# Start Gitflow release.
				git flow init -d
				git flow release start "${NEW_VERSION}"

				# Update package version number.
				sed -i "" -e "s/\"version\": \"${CURRENT_TAG}\"/\"version\": \"${NEW_VERSION}\"/g" package.json

				# Update changelog title and add log.
				TITLE_LINENR=$(grep -n "## \[" CHANGELOG.md | head -2 | tail -1 | cut -d: -f1)
				LOG=$(echo "$LOG" | sed \'s/^[a-z0-9]\{7\}/-/\' | sed \'s/^- Merge tag.*//\'; echo "";)
				TITLE="## [${NEW_VERSION}] - $(date \'+%Y-%m-%d\')' . \PHP_EOL . '"
				ex -s -c "${TITLE_LINENR}i|${TITLE}${LOG}' . str_repeat( \PHP_EOL, 2 ) . '" -c x CHANGELOG.md

				# Update changelog footer links.
				sed -i "" -e "s/\/${CURRENT_TAG}...HEAD/\/${NEW_VERSION}...HEAD/g" CHANGELOG.md
				LINK_LINENR=$(grep -n "\[unreleased\]" CHANGELOG.md | tail -1 | cut -d: -f1)
				LINK="[${NEW_VERSION}]: https://github.com/' . $organisation . '/' . $repository . '/compare/${CURRENT_TAG}...${NEW_VERSION}"
				ex -s -c "$(( ${LINK_LINENR} + 1 ))i|${LINK}" -c x CHANGELOG.md

				# Write temporary changelog JSON.
				FROM=$(( $(grep -n "## \[" CHANGELOG.md | head -2 | tail -1 | cut -d: -f1) + 1 ))
				TO=$(( $(grep -n "## \[" CHANGELOG.md | head -3 | tail -1 | cut -d: -f1) - 2 ))
				LOG=$(cat CHANGELOG.md | head -n $TO | tail -n +$FROM )
				echo "${LOG}"
				echo ",{\"description\":\"Updated WordPress ' . ( false === strpos( $repository, '-pay-' ) ? '' : 'pay ' ) . $name . ' library to version ${NEW_VERSION}.\",\"changes\":$(echo "${LOG}" | sed \'s/^- //\' | jq --raw-input --raw-output --slurp \'split("\\n") | .[0:-1]\')}" >> ../../../src/changelog-release.json

				# Git commit changes (without running pre-commit hooks).
				git commit -a -m "Getting ready for version ${NEW_VERSION}." --no-verify

				# Gitflow finish release.
				git rev-parse --verify --quiet master && git fetch origin master:master && echo ""
				git rev-parse --verify --quiet main && git fetch origin main:main && echo ""
				git flow release finish -m "${NEW_VERSION}" "${NEW_VERSION}"
				
				# Git push tag, master and develop.
				git push origin --tags && echo ""
				git rev-parse --verify --quiet master && git push origin master && echo ""
				git rev-parse --verify --quiet main && git push origin main && echo ""
				git push origin develop

				echo ""
				echo "✅ Tag https://github.com/' . $organisation . '/' . $repository . '/tree/${NEW_VERSION}"
				echo ""
				echo "🎉 Released ' . $name . ' version ${bold}${NEW_VERSION}${normal}."';
		}

		if ( null !== $command ) {
			if ( ! isset( $argv[1] ) || 'release' !== $argv[1] ) {
				echo $command, PHP_EOL;
			}

			echo passthru( $command ), PHP_EOL;
		}
	}
}

if ( isset( $argv[1] ) && 'release' === $argv[1] ) {
	// Get release changelog items from temporary file.
	$changelog_plugin = file_get_contents( __DIR__ . '/changelog-release.json' ) . ']';
	$changelog_plugin = str_replace( '\\', '\\\\', $changelog_plugin );
	$changelog_plugin = str_replace( '\\\\"', '\\"', $changelog_plugin );

	// phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_unlink
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

	$release = [
		[
			'version' => $package->version,
			'date'    => gmdate( 'Y-m-d' ),
			'changes' => [
				'name'    => 'Changed',
				'changes' => $updates,
			],
		],
	];

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

	// Replace issue references markdown.
	$json = preg_replace( '/ \[#([0-9]+)\]\(.*?\/(.*?)\/(.*?)\/issues\/.*?\)/m', ' (\\2/\\3#\\1)', (string) $json );

	$json = preg_replace( '/\(\[#([0-9]+)\]\(.*?\/(.*?)\/(.*?)\/issues\/.*?\)\)/m', '(\\2/\\3#\\1)', (string) $json );

	$json = preg_replace( '/\(\[(.*?)\/(.*?)#([0-9]+)\]\(.*?\/.*?\/.*?\/issues\/.*?\)\)/m', '(\\1/\\2#\\3)', (string) $json );

	// Remove all other issue reference markdown.
	$json = preg_replace( '/ \[(#[0-9]+)\]\(.*?\/issues\/.*?\)\./m', '.', (string) $json );

	// Write updated changelog.
	$handle = fopen( __DIR__ . '/changelog.json', 'w+' );

	if ( false !== $handle ) {
		// phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_fwrite
		fwrite( $handle, $json . PHP_EOL );
	}
}
