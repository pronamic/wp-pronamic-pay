<?php
/**
 * Extensions.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2021 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

$data = file_get_contents( __DIR__ . '/../extensions.json' );

// Check if file could be read.
if ( false === $data ) {
	return;
}

$extensions = json_decode( $data );

?>
| Name | Author | WordPress.org | GitHub | Requires at least | Tested up to |
| ---- | ------ | ------------- | ------ | ----------------- | ------------ |
<?php foreach ( $extensions as $extension ) : ?>
| <?php

printf( '[%s](%s)', $extension->name, $extension->url );

echo ' | ';

if ( isset( $extension->author, $extension->author_url ) ) {
	printf( '[%s](%s)', $extension->author, $extension->author_url );
}

echo ' | ';

if ( isset( $extension->wp_org_url ) ) {
	printf( '[%s](%s)', 'WordPress.org', $extension->wp_org_url );
}

echo ' | ';

if ( isset( $extension->github_url ) ) {
	printf( '[%s](%s)', 'GitHub', $extension->github_url );
}

echo ' | ';

if ( isset( $extension->requires_at_least ) ) {
	printf( '`%s`', $extension->requires_at_least );
}

echo ' | ';

printf( '`%s`', $extension->tested_up_to );

?> |
<?php endforeach; ?>
