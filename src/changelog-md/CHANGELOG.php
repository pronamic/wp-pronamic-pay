<?php
/**
 * Changelog.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

header( 'Content-Type: text/plain' );

$data      = file_get_contents( __DIR__ . '/../changelog.json' );
$changelog = json_decode( $data );

/**
 * Render changes.
 *
 * @param string|array|object $changes Changes.
 * @param int                 $level   Level.
 */
function render_changes( $changes, $level = 0 ) {
	$indent = $level * 2;

	// Changes string.
	if ( is_string( $changes ) ) {
		echo esc_html( str_repeat( ' ', $indent ) ), '- ', esc_html( $changes ), "\r\n";
	}

	// Changes array.
	if ( is_array( $changes ) ) {
		foreach ( $changes as $change ) {
			render_changes( $change, $level );
		}
	}

	// Changes object.
	if ( is_object( $changes ) ) {
		if ( isset( $changes->name ) ) {
			// Changes group.
			echo "\r\n";
			echo '### ', $changes->name, "\r\n";

			if ( isset( $changes->changes ) ) {
				render_changes( $changes->changes, $level );
			}
		} else {
			if ( isset( $changes->description ) ) {
				render_changes( $changes->description, $level );
			}

			if ( isset( $changes->changes ) ) {
				render_changes( $changes->changes, $level + 1 );
			}
		}
	}
}

?>
# Change Log

All notable changes to this project will be documented in this file.

This projects adheres to [Semantic Versioning](http://semver.org/) and [Keep a CHANGELOG](http://keepachangelog.com/).

<?php

foreach ( $changelog as $log ) {
	if ( 'Unreleased' === $log->version ) {
		echo '## [', $log->version, '][unreleased]', "\r\n";
	} else {
		echo '## [', $log->version, '] - ', $log->date, "\r\n";
	}

	render_changes( $log->changes );

	echo "\r\n";
}

$collection = new CachingIterator( new ArrayIterator( $changelog ), CachingIterator::TOSTRING_USE_CURRENT );

foreach ( $collection as $log ) {
	if ( $collection->hasNext() ) {
		$prev = $collection->getInnerIterator()->current();

		if ( 'Unreleased' === $log->version ) {
			printf(
				'[unreleased]: https://github.com/pronamic/wp-pronamic-ideal/compare/%s...HEAD',
				$prev->version
			);
		} else {
			printf(
				'[%1$s]: https://github.com/pronamic/wp-pronamic-ideal/compare/%2$s...%1$s',
				$log->version,
				$prev->version
			);
		}

		echo "\r\n";
	}
}
