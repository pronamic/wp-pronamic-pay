<?php
/**
 * Page Tools
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

use Pronamic\WordPress\Pay\Plugin;

$tabs = array(
	'system_status' => __( 'System Status', 'pronamic_ideal' ),
	'gateways'      => __( 'Payment Gateways', 'pronamic_ideal' ),
	'extensions'    => __( 'Extensions', 'pronamic_ideal' ),
	'documentation' => __( 'Documentation', 'pronamic_ideal' ),
);

$current_tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
$current_tab = empty( $current_tab ) ? key( $tabs ) : $current_tab;

?>

<div class="wrap">
	<h1 class="nav-tab-wrapper">
		<?php

		foreach ( $tabs as $tab => $title ) {
			$classes = array( 'nav-tab' );

			if ( $current_tab === $tab ) {
				$classes[] = 'nav-tab-active';
			}

			$url = add_query_arg(
				array(
					'page' => 'pronamic_pay_tools',
					'tab'  => $tab,
				),
				admin_url( 'admin.php' )
			);

			printf(
				'<a class="nav-tab %s" href="%s">%s</a>',
				esc_attr( implode( ' ', $classes ) ),
				esc_attr( $url ),
				esc_html( $title )
			);
		}

		?>
	</h1>

	<hr class="wp-header-end">

	<?php

	$file = plugin_dir_path( Plugin::$file ) . 'admin/tab-' . $current_tab . '.php';

	if ( is_readable( $file ) ) {
		include $file;
	}

	?>

	<?php require 'pronamic.php'; ?>
</div>
