<?php

$data      = file_get_contents( __DIR__ . '/../changelog.json' );
$changelog = json_decode( $data );

/**
 * Render changes.
 *
 * @param string|array|object $changes Changes.
 * @param int                 $level   Indentation level.
 */
function render_changes( $changes, $level = 0 ) {
	$indent = $level * 1;

	if ( is_string( $changes ) ) {
		echo str_repeat( "\t", $indent ), '*', "\t", $changes, "\r\n";
	} elseif ( is_array( $changes ) ) {
		foreach ( $changes as $change ) {
			render_changes( $change, $level );
		}
	} elseif ( is_object( $changes ) ) {
		if ( isset( $changes->name ) ) {
			// Changes group.
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

foreach ( $changelog as $log ) {
	if ( 'Unreleased' === $log->version ) {
		continue;
	}

	echo '= ', $log->version, ' - ', $log->date, ' =', "\r\n";

	render_changes( $log->changes );

	echo "\r\n";
}
