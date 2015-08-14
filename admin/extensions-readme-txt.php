<?php

foreach ( $extensions as $extension ) {
	printf( "*	[%s](%s)\n", esc_html( $extension['name'] ), esc_html( $extension['url'] ) );

	if ( isset( $extension['author'], $extension['author_url'] ) ) {
		printf( "	*	**Author:** [%s](%s)\n", esc_html( $extension['author'] ), esc_html( $extension['author_url'] ) );
	}

	if ( isset( $extension['wp_org_url'] ) ) {
		printf( "	*	**WordPress.org:** [%s](%s)\n", esc_html( $extension['wp_org_url'] ), esc_html( $extension['wp_org_url'] ) );
	}

	if ( isset( $extension['github_url'] ) ) {
		printf( "	*	**GitHub:** [%s](%s)\n", esc_html( $extension['github_url'] ), esc_html( $extension['github_url'] ) );
	}

	if ( isset( $extension['requires_at_least'] ) ) {
		printf( "	*	**Requires at least:** %s\n", esc_html( $extension['requires_at_least'] ) );
	}

	if ( isset( $extension['tested_up_to'] ) ) {
		printf( "	*	**Tested up to:** %s\n", esc_html( $extension['tested_up_to'] ) );
	}
}
