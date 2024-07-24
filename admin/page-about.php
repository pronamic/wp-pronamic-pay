<?php
/**
 * Page about
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2024 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 * @wordpress
 * Version: 5.8.0
 */

use Pronamic\WordPress\Pay\Plugin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
<div class="wrap about-wrap">
	<h1><?php esc_html_e( 'Welcome to Pronamic Pay', 'pronamic-ideal' ); ?></h1>

	<div class="about-text">
		<?php

		printf(
			/* translators: %s: Plugin version number */
			esc_html__( 'Thanks for installing Pronamic Pay. Version %s is more powerful, stable and secure than ever before. We hope you enjoy using it.', 'pronamic-ideal' ),
			esc_html( $this->plugin->get_version() )
		);

		?>
	</div>

	<div class="wp-badge pronamic-pay-badge">
		<?php

		printf(
			/* translators: %s: Plugin version number */
			esc_html__( 'Version: %s', 'pronamic-ideal' ),
			esc_html( $this->plugin->get_version() )
		);

		?>
	</div>

	<h2 class="nav-tab-wrapper">
		<?php

		$nav_tabs = [
			'new'             => __( 'What is new?', 'pronamic-ideal' ),
			'getting-started' => __( 'Getting started', 'pronamic-ideal' ),
		];

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce is not necessary because this parameter does not trigger an action.
		$current_tab = \array_key_exists( 'tab', $_GET ) ? \sanitize_title( \wp_unslash( $_GET['tab'] ) ) : 'new';

		foreach ( $nav_tabs as $tab_id => $tab_title ) {
			$classes = [ 'nav-tab' ];

			if ( $current_tab === $tab_id ) {
				$classes[] = 'nav-tab-active';
			}

			$url = add_query_arg( 'tab', $tab_id );

			printf(
				'<a class="nav-tab %s" href="%s">%s</a>',
				esc_attr( implode( ' ', $classes ) ),
				esc_attr( $url ),
				esc_html( $tab_title )
			);
		}

		?>
	</h2>

	<?php

	$file = __DIR__ . '/tab-' . $current_tab . '.php';

	if ( is_readable( $file ) ) {
		include $file;
	}

	?>
</div>
