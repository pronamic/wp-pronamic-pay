<?php
/**
 * Extensions.
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

$data       = file_get_contents( __DIR__ . '/../extensions.json' );
$extensions = json_decode( $data );

?>
| Name | Author | WordPress.org | GitHub | Requires at least | Tested up to |
| ---- | ------ | ------------- | ------ | ----------------- | ------------ |
<?php foreach ( $extensions as $extension ) : ?>
| <?php

printf( '[%s](%s)', esc_html( $extension->name ), esc_html( $extension->url ) );

echo ' | ';

if ( isset( $extension->author, $extension->author_url ) ) {
	printf( '[%s](%s)', esc_html( $extension->author ), esc_html( $extension->author_url ) );
}

echo ' | ';

if ( isset( $extension->wp_org_url ) ) {
	printf( '[%s](%s)', 'WordPress.org', esc_html( $extension->wp_org_url ) );
}

echo ' | ';

if ( isset( $extension->github_url ) ) {
	printf( '[%s](%s)', 'GitHub', esc_html( $extension->github_url ) );
}

echo ' | ';

if ( isset( $extension->requires_at_least ) ) {
	printf( '`%s`', esc_html( $extension->requires_at_least ) );
}

echo ' | ';

printf( '`%s`', esc_html( $extension->tested_up_to ) );

?> |
<?php endforeach; ?>
