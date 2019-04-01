<?php
/**
 * Readme extensions.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

$data       = file_get_contents( __DIR__ . '/../extensions.json' );
$extensions = json_decode( $data );

foreach ( $extensions as $extension ) {
	printf(
		"*	[%s](%s)\n",
		$extension->name,
		$extension->url
	);

	// Author.
	if ( isset( $extension->author, $extension->author_url ) ) {
		printf(
			"	*	**Author:** [%s](%s)\n",
			$extension->author,
			$extension->author_url
		);
	}

	// WordPress.org URL.
	if ( isset( $extension->wp_org_url ) ) {
		printf(
			"	*	**WordPress.org:** [%s](%s)\n",
			$extension->wp_org_url,
			$extension->wp_org_url
		);
	}

	// Github URL.
	if ( isset( $extension->github_url ) ) {
		printf(
			"	*	**GitHub:** [%s](%s)\n",
			$extension->github_url,
			$extension->github_url
		);
	}

	// Requires at least.
	if ( isset( $extension->requires_at_least ) ) {
		printf(
			"	*	**Requires at least:** %s\n",
			$extension->requires_at_least
		);
	}

	// Tested up to.
	if ( isset( $extension->tested_up_to ) ) {
		printf(
			"	*	**Tested up to:** %s\n",
			$extension->tested_up_to
		);
	}
}
