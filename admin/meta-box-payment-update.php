<?php
/**
 * Meta Box Payment Update
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

use Pronamic\WordPress\Pay\Core\PaymentMethods;
use Pronamic\WordPress\Pay\Payments\PaymentPostType;
use Pronamic\WordPress\Pay\Plugin;

$states = PaymentPostType::get_payment_states();

$payment = get_pronamic_payment( get_the_ID() );

// WordPress by default doesn't allow `post_author` values of `0`, that's why we use a dash (`-`).
// @link https://github.com/WordPress/WordPress/blob/4.9.5/wp-admin/includes/post.php#L56-L64.
$post_author = get_post_field( 'post_author' );
$post_author = empty( $post_author ) ? '-' : $post_author;

?>
<input type="hidden" name="post_author_override" value="<?php echo esc_attr( $post_author ); ?>" />

<div class="pronamic-pay-inner">
	<p>
		<label for="pronamic-payment-status">Status:&nbsp;</label>
		<select id="pronamic-payment-status" name="post_status" class="medium-text">
			<?php

			foreach ( $states as $status => $label ) {
				printf(
					'<option value="%s" %s>%s</option>',
					esc_attr( $status ),
					selected( $status, $post->post_status, false ),
					esc_html( $label )
				);
			}

			?>
		</select>
	</p>

	<?php

	$config_id = get_post_meta( $post->ID, '_pronamic_payment_config_id', true );

	$gateway = Plugin::get_gateway( $config_id );

	/**
	 * Check status button.
	 */
	if ( $gateway && $gateway->supports( 'payment_status_request' ) ) {
		// Only show button if gateway exists and status check is supported.
		$action_url = wp_nonce_url(
			add_query_arg(
				array(
					'post'                      => $post->ID,
					'action'                    => 'edit',
					'pronamic_pay_check_status' => true,
				),
				admin_url( 'post.php' )
			),
			'pronamic_payment_check_status_' . $post->ID
		);

		printf(
			'<p><a class="button" href="%s">%s</a></p>',
			esc_url( $action_url ),
			esc_html__( 'Check status', 'pronamic_ideal' )
		);
	}

	/**
	 * Create invoice for reserved payment.
	 */
	if ( $gateway && $gateway->supports( 'reservation_payments' ) && 'payment_reserved' === get_post_status( $post->ID ) ) {
		// Only show button if gateway exists and reservation payments are supported.
		$action_url = wp_nonce_url(
			add_query_arg(
				array(
					'post'                        => $post->ID,
					'action'                      => 'edit',
					'pronamic_pay_create_invoice' => true,
				),
				admin_url( 'post.php' )
			),
			'pronamic_payment_create_invoice_' . $post->ID
		);

		$link_text = sprintf(
			/* translators: %s: payment method name */
			__( 'Create %1$s invoice', 'pronamic_ideal' ),
			PaymentMethods::get_name( $payment->get_method() )
		);

		printf(
			'<p><a class="button" href="%s">%s</a></p>',
			esc_url( $action_url ),
			esc_html( $link_text )
		);
	}

	/**
	 * Cancel payment reservations.
	 */
	if ( $gateway && $gateway->supports( 'reservation_payments' ) && 'payment_reserved' === get_post_status( $post->ID ) ) {
		// Only show button if gateway exists and reservation payments are supported.
		$action_url = wp_nonce_url(
			add_query_arg(
				array(
					'post'                            => $post->ID,
					'action'                          => 'edit',
					'pronamic_pay_cancel_reservation' => true,
				),
				admin_url( 'post.php' )
			),
			'pronamic_payment_cancel_reservation_' . $post->ID
		);

		$link_text = sprintf(
			/* translators: %s: payment method name */
			__( 'Cancel %1$s reservation', 'pronamic_ideal' ),
			PaymentMethods::get_name( $payment->get_method() )
		);

		printf(
			'<p><a class="button" href="%s">%s</a></p>',
			esc_url( $action_url ),
			esc_html( $link_text )
		);
	}

	/**
	 * Send to Google Analytics button.
	 */
	$can_track = pronamic_pay_plugin()->google_analytics_ecommerce->valid_payment( $payment );

	if ( $can_track ) {
		// Only show button for payments that can be tracked.
		$action_url = wp_nonce_url(
			add_query_arg(
				array(
					'post'                  => $post->ID,
					'action'                => 'edit',
					'pronamic_pay_ga_track' => true,
				),
				admin_url( 'post.php' )
			),
			'pronamic_payment_ga_track_' . $post->ID
		);

		printf(
			'<p><a class="button" href="%s">%s</a></p>',
			esc_url( $action_url ),
			esc_html__( 'Send to Google Analytics', 'pronamic_ideal' )
		);
	}

	?>
</div>

<div class="pronamic-pay-major-actions">
	<div class="pronamic-pay-action">
		<?php

		wp_nonce_field( 'pronamic_payment_update', 'pronamic_payment_nonce' );

		submit_button(
			__( 'Update', 'pronamic_ideal' ),
			'primary',
			'pronamic_payment_update',
			false
		);

		?>
	</div>

	<div class="clear"></div>
</div>
