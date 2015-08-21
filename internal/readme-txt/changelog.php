<?php

$data      = file_get_contents( __DIR__ . '/../changelog.json' );
$changelog = json_decode( $data );

foreach ( $changelog as $log ) {
	echo '= ', $log->version, ' - ', $log->date, ' =', "\r\n";

	foreach ( $log->changes as $group ) {
		if ( is_string( $group ) ) {
			echo '*', "\t", $group, "\r\n";
		}

		if ( isset( $group->changes ) && is_array( $group->changes ) ) {
			foreach ( $group->changes as $change ) {
				if ( is_string( $change ) ) {
					echo '*', "\t", $change, "\r\n";
				}

				if ( is_object( $change ) && isset( $change->description ) ) {
					echo '*', "\t", $change->description, "\r\n";	

					if ( isset( $change->changes ) && is_array( $change->changes ) ) {
						foreach ( $change->changes as $change ) {
							echo "\t", '*', "\t", $change, "\r\n";
						}
					}
				}
			}
		}
	}

	echo "\r\n";
}
