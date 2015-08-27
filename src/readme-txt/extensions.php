<?php

$data       = file_get_contents( __DIR__ . '/../extensions.json' );
$extensions = json_decode( $data );

foreach ( $extensions as $extension ) {
	printf( "*	[%s](%s)\n", $extension->name, $extension->url );

	if ( isset( $extension->author, $extension->author_url ) ) {
		printf( "	*	**Author:** [%s](%s)\n", $extension->author, $extension->author_url );
	}

	if ( isset( $extension->wp_org_url ) ) {
		printf( "	*	**WordPress.org:** [%s](%s)\n", $extension->wp_org_url, $extension->wp_org_url );
	}

	if ( isset( $extension->github_url ) ) {
		printf( "	*	**GitHub:** [%s](%s)\n", $extension->github_url, $extension->github_url );
	}

	if ( isset( $extension->requires_at_least ) ) {
		printf( "	*	**Requires at least:** %s\n", $extension->requires_at_least );
	}

	if ( isset( $extension->tested_up_to ) ) {
		printf( "	*	**Tested up to:** %s\n", $extension->tested_up_to );
	}
}
