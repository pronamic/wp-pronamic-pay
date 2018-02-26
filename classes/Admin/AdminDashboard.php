<?php
/**
 * Admin Dashboard
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Admin
 */

namespace Pronamic\WordPress\Pay\Admin;

use Pronamic\WordPress\Pay\Plugin;

/**
 * Title: WordPress admin dashboard
 *
 * @author Remco Tolsma
 * @version 3.7.0
 * @since 3.7.0
 */
class AdminDashboard {
	private $plugin;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes admin dashboard object
	 *
	 * @see https://github.com/WordImpress/Give/blob/1.1/includes/admin/dashboard-widgets.php
	 * @see https://github.com/woothemes/woocommerce/blob/2.3.13/includes/admin/class-wc-admin.php
	 * @see https://github.com/woothemes/woocommerce/blob/2.3.13/includes/admin/class-wc-admin-dashboard.php
	 *
	 * @param Plugin $plugin
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		// Actions
		add_action( 'wp_dashboard_setup', array( $this, 'setup' ) );
	}

	//////////////////////////////////////////////////

	public function setup() {
		if ( current_user_can( 'manage_options' ) ) {
			wp_add_dashboard_widget(
				'pronamic_pay_dashboard_status',
				__( 'Pronamic Pay Status', 'pronamic_ideal' ),
				array( $this, 'status_widget' )
			);
		}
	}

	public function status_widget() {
		$counts = wp_count_posts( 'pronamic_payment' );

		$states = array(
			'payment_completed' => __( '%s completed', 'pronamic_ideal' ),
			'payment_pending'   => __( '%s pending', 'pronamic_ideal' ),
			'payment_cancelled' => __( '%s cancelled', 'pronamic_ideal' ),
			'payment_failed'    => __( '%s failed', 'pronamic_ideal' ),
			'payment_expired'   => __( '%s expired', 'pronamic_ideal' ),
		);

		$url = add_query_arg(
			array(
				'post_type' => 'pronamic_payment',
			), admin_url( 'edit.php' )
		);

		include \Pronamic\WordPress\Pay\Plugin::$dirname . '/admin/widget-payments-status-list.php';
	}
}
