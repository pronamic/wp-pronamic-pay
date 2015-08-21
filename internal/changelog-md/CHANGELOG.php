<?php

$data      = file_get_contents( __DIR__ . '/../changelog.json' );
$changelog = json_decode( $data );

function render_changes( $changes, $level = 0 ) {
	$indent = $level * 2;

	if ( is_array( $changes ) ) {
		foreach ( $changes as $change ) {
			if ( is_object( $change ) ) {
				if ( isset( $change->name ) ) {
					// Changes are grouped
					echo '### ', $change->name, "\r\n";
				}

				if ( isset( $change->description ) ) {
					render_changes( $change->description );
				}

				if ( isset( $change->changes ) && is_array( $change->changes ) ) {
					render_changes( $change->changes, $level + 1 );
				}
			} else {
				render_changes( $change );
			}
		}
	} elseif ( is_string( $changes ) ) {
		echo str_repeat( ' ', $indent ), '- ', $changes, "\r\n";
	}
}

?>
# Change Log

All notable changes to this project will be documented in this file.

This projects adheres to [Semantic Versioning](http://semver.org/) and [Keep a CHANGELOG](http://keepachangelog.com/).

<?php

foreach ( $changelog as $log ) {
	echo '## [', $log->version, '] - ', $log->date, "\r\n";

	render_changes( $log->changes );

	echo "\r\n";
}
