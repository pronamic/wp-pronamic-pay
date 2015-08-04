<?php

/**
 * Title: WordPress admin dashboard
 * Description:
 * Copyright: Copyright (c) 2005 - 2015
 * Company: Pronamic
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
		wp_add_dashboard_widget(
			'pronamic_pay_dashboard_status',
			__( 'Pronamic iDEAL Status', 'pronamic_ideal' ),
			array( $this, 'status_widget' )
		);
	}

	private function get_count( $status ) {
		global $wpdb;

		$condition = empty( $status ) ? 'IS NULL' : '= %s';

		$query = "
			SELECT
				COUNT( post.ID ) AS value
			FROM
				$wpdb->posts AS post
					LEFT JOIN
				$wpdb->postmeta AS meta_status
						ON post.ID = meta_status.post_id AND meta_status.meta_key = '_pronamic_payment_status'
			WHERE
				post.post_type = 'pronamic_payment'
					AND
				meta_status.meta_value $condition
			GROUP BY
				post.ID
			;";

		if ( null !== $status ) {
			$query = $wpdb->prepare( $query, $status );
		}

		return intval( $wpdb->get_var( $query ) );
	}

	public function status_widget() {
		$processing_count = $this->get_count( null );
		$cancelled_count  = $this->get_count( 'Cancelled' );
		$failed_count     = $this->get_count( 'Failure' );
		$expired_count    = $this->get_count( 'Expired' );

		$stats = array(
			''          => __( '%s awaiting processing', 'pronamic_ideal' ),
			'Cancelled' => __( '%s cancelled', 'pronamic_ideal' ),
			'Failure'   => __( '%s failed', 'pronamic_ideal' ),
			'Expired'   => __( '%s expired', 'pronamic_ideal' ),
		);

		?>
		<ul class="pronamic-pay-status-list">

			<?php foreach ( $stats as $status => $label ) : ?>

				<li>
					<a href="<?php echo admin_url( 'edit.php?post_status=wc-processing&post_type=shop_order' ); ?>">
						<?php

						$count = $this->get_count( $status );

						printf(
							$label,
							'<strong>' . sprintf(
								_n( '%s payment', '%s payments', $count, 'pronamic_ideal' ),
								number_format_i18n( $count )
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
