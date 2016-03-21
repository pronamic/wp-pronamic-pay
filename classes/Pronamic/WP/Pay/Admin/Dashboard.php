<?php

/**
 * Title: WordPress admin dashboard
 * Description:
 * Copyright: Copyright (c) 2005 - 2016
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 3.7.0
 * @since 3.7.0
 */
class Pronamic_WP_Pay_Admin_Dashboard {
	private $admin;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes admin dashboard object
	 *
	 * @see https://github.com/WordImpress/Give/blob/1.1/includes/admin/dashboard-widgets.php
	 * @see https://github.com/woothemes/woocommerce/blob/2.3.13/includes/admin/class-wc-admin.php
	 * @see https://github.com/woothemes/woocommerce/blob/2.3.13/includes/admin/class-wc-admin-dashboard.php
	 */
	public function __construct( $admin ) {
		$this->admin = $admin;

		// Actions
		add_action( 'wp_dashboard_setup', array( $this, 'setup' ) );
	}

	//////////////////////////////////////////////////

	public function setup() {
		if ( current_user_can( 'manage_options' ) ) {
			wp_add_dashboard_widget(
				'pronamic_pay_dashboard_status',
				__( 'Pronamic iDEAL Status', 'pronamic_ideal' ),
				array ( $this, 'status_widget' )
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

		$url = add_query_arg( array(
			'post_type' => 'pronamic_payment',
		), admin_url( 'edit.php' ) );

		?>
		<ul class="pronamic-pay-status-list">

			<?php foreach ( $states as $status => $label ) : ?>

				<li class="<?php echo esc_attr( 'payment_status-' . $status ); ?>">
					<a href="<?php echo esc_attr( add_query_arg( 'post_status', $status, $url ) ); ?>">
						<?php

						$count = isset( $counts->$status ) ? $counts->$status : 0;

						printf( //xss ok
							$label,
							'<strong>' . sprintf(
								esc_html( _n( '%s payment', '%s payments', $count, 'pronamic_ideal' ) ),
								esc_html( number_format_i18n( $count ) )
							) . '</strong>'
						);

						?>
					</a>
				</li>

			<?php endforeach; ?>

		</ul>
		<?php
	}
}
