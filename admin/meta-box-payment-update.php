<?php
/**
 * Meta Box Payment Update
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

use Pronamic\WordPress\Pay\Plugin;
use Pronamic\WordPress\Pay\Payments\PaymentPostType;

$states = PaymentPostType::get_payment_states();

// WordPress by default doesn't allow `post_author` values of `0`, that's why we use a dash (`-`).
// @see https://github.com/WordPress/WordPress/blob/4.9.5/wp-admin/includes/post.php#L56-L64.
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

	/**
	 * Check status button.
	 */
	$config_id = get_post_meta( $post->ID, '_pronamic_payment_config_id', true );

	$gateway = Plugin::get_gateway( $config_id );

	if ( $gateway && $gateway->supports( 'payment_status_request' ) ) {
		// Only show button if gateway exists and status check is supported.
		$check_status_nonce_url = wp_nonce_url(
			add_query_arg(
				array(
					'post'                      => $post->ID,
					'action'                    => 'edit',
					'pronamic_pay_check_status' => true,
				), admin_url( 'post.php' )
			),
			'pronamic_payment_check_status_' . $post->ID
		);

		printf(
			'<a class="button" href="%s">%s</a>',
			esc_url( $check_status_nonce_url ),
			esc_html__( 'Check status', 'pronamic_ideal' )
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
