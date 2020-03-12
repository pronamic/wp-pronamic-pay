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
		'wp-datetime',
		'wp-money',
	),
	'wp-pay'            => array(
		'core',
	),
	'wp-pay-gateways'   => array(
		'adyen',
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
		'charitable',
		'easy-digital-downloads',
		'event-espresso',
		'event-espresso-legacy',
		'formidable-forms',
		'give',
		'gravityforms',
		'memberpress',
		'ninjaforms',
		'restrict-content-pro',
		's2member',
		'woocommerce',
		'wp-e-commerce',
	),
);

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
			echo shell_exec( 'git clone ' . $git_url . ' ' . $git_dir );
		}

		// Git flow.
		chdir( $git_dir );

		$command = null;

		if ( isset( $argv[1] ) && 'develop' === $argv[1] ) {
			$command = 'git checkout develop';
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
			if ( ! isset( $argv[1] ) || ( isset( $argv[1] ) && ! in_array( $argv[1], array( 'changelog', 'update-package-version' ), true ) ) ) {
				echo $command, PHP_EOL;
			}

			echo shell_exec( $command ), PHP_EOL;
		}
	}
}
