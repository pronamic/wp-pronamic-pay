<?php
/**
 * Page About
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

use Pronamic\WordPress\Pay\Plugin;

/**
 * Page about
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
<div class="wrap about-wrap">
	<h1><?php esc_html_e( 'Welcome to Pronamic Pay', 'pronamic_ideal' ); ?></h1>

	<div class="about-text">
		<?php

		printf(
			esc_html__( 'Thanks for installing Pronamic Pay. Version %s is more powerful, stable and secure than ever before. We hope you enjoy using it.', 'pronamic_ideal' ),
			esc_html( $this->plugin->get_version() )
		);

		?>
	</div>

	<div class="wp-badge pronamic-pay-badge">
		<?php

		printf(
			esc_html__( 'Version: %s', 'pronamic_ideal' ),
			esc_html( $this->plugin->get_version() )
		);

		?>
	</div>

	<h2 class="nav-tab-wrapper">
		<?php

		$tabs = array(
			'new'             => __( 'What is new', 'pronamic_ideal' ),
			'getting-started' => __( 'Getting started', 'pronamic_ideal' ),
		);

		$current_tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
		$current_tab = empty( $current_tab ) ? key( $tabs ) : $current_tab;

		foreach ( $tabs as $tab => $title ) {
			$classes = array( 'nav-tab' );

			if ( $current_tab === $tab ) {
				$classes[] = 'nav-tab-active';
			}

			$url = add_query_arg( 'tab', $tab );

			printf(
				'<a class="nav-tab %s" href="%s">%s</a>',
				esc_attr( implode( ' ', $classes ) ),
				esc_attr( $url ),
				esc_html( $title )
			);
		}

		?>
	</h2>

	<?php

	$file = plugin_dir_path( Plugin::$file ) . 'admin/tab-' . $current_tab . '.php';

	if ( is_readable( $file ) ) {
		include $file;
	}

	?>
</div>
