<?php

header( 'Content-Type: text/plain' );

$data      = file_get_contents( __DIR__ . '/../changelog.json' );
$changelog = json_decode( $data );

function render_changes( $changes, $level = 0 ) {
	$indent = $level * 2;

	if ( is_string( $changes ) ) {
		echo str_repeat( ' ', $indent ), '- ', $changes, "\r\n";
	} elseif ( is_array( $changes ) ) {
		foreach ( $changes as $change ) {
			render_changes( $change, $level );
		}
	} elseif ( is_object( $changes ) ) {
		if ( isset( $changes->name ) ) {
			// Changes group
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
			echo '[unreleased]: https://github.com/pronamic/wp-pronamic-ideal/compare/', $prev->version, '...', 'HEAD', "\r\n";     
		} else {
    		echo '[', $log->version, ']: https://github.com/pronamic/wp-pronamic-ideal/compare/', $prev->version, '...', $log->version, "\r\n";     
    	}
     }
}
