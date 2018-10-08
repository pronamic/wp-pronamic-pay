<?php
/**
 * Meta Box Payment Info
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

use Pronamic\WordPress\Money\Money;
use Pronamic\WordPress\Pay\Core\PaymentMethods;

$post_id = get_the_ID();

if ( empty( $post_id ) ) {
	return;
}

$post_type = 'pronamic_payment';

$payment = get_pronamic_payment( $post_id );

$purchase_id = get_post_meta( $post_id, '_pronamic_payment_purchase_id', true );

?>
<table class="form-table">
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Date', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php echo esc_html( $payment->date->format_i18n() ); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'ID', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php echo esc_html( $post_id ); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Order ID', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php echo esc_html( get_post_meta( $post_id, '_pronamic_payment_order_id', true ) ); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Description', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php echo esc_html( get_post_meta( $post_id, '_pronamic_payment_description', true ) ); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Amount', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			echo esc_html( $payment->get_amount()->format_i18n() );

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Transaction ID', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php do_action( 'manage_' . $post_type . '_posts_custom_column', 'pronamic_payment_transaction', $post_id ); ?>
		</td>
	</tr>

	<?php if ( $purchase_id ) : ?>

		<tr>
			<th scope="row">
				<?php esc_html_e( 'Purchase ID', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php echo esc_html( $purchase_id ); ?>
			</td>
		</tr>

	<?php endif; ?>

	<tr>
		<th scope="row">
			<?php esc_html_e( 'Gateway', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php edit_post_link( get_the_title( $payment->config_id ), '', '', $payment->config_id ); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Payment Method', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			$method = $payment->get_meta( 'method' );

			$name = PaymentMethods::get_name( $method );

			echo esc_html( $name );

			$issuer = $payment->get_meta( 'issuer' );

			if ( $issuer ) {
				echo esc_html( sprintf( ' (`%s`)', $issuer ) );
			}

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Action URL', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			$url = get_post_meta( $post_id, '_pronamic_payment_action_url', true );

			printf(
				'<a href="%s" target="_blank">%s</a>',
				esc_attr( $url ),
				esc_html( $url )
			);

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Return URL', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			$url = $payment->get_return_url();

			printf(
				'<a href="%s">%s</a>',
				esc_attr( $url ),
				esc_html( $url )
			);

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Redirect URL', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			$url = $payment->get_return_redirect_url();

			printf(
				'<a href="%s">%s</a>',
				esc_attr( $url ),
				esc_html( $url )
			);

			?>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php esc_html_e( 'Status', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			$status_object = get_post_status_object( get_post_status( $post_id ) );

			if ( isset( $status_object, $status_object->label ) ) {
				echo esc_html( $status_object->label );
			} else {
				echo '—';
			}

			?>
		</td>
	</tr>

	<?php if ( null !== $payment->get_customer() ) : ?>

		<tr>
			<th scope="row">
				<?php esc_html_e( 'Customer', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php

				if ( null !== $payment->get_customer()->get_name() ) {
					echo esc_html( $payment->get_customer()->get_name() );
				}

				?>
			</td>
		</tr>

	<?php endif; ?>

	<?php if ( null !== $payment->get_billing_address() ) : ?>

		<tr>
			<th scope="row">
				<?php esc_html_e( 'Billing Address', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php

				echo wp_kses(
					nl2br( $payment->get_billing_address() ),
					array(
						'br' => array(),
					)
				);

				?>
			</td>
		</tr>

	<?php endif; ?>

	<?php if ( null !== $payment->get_shipping_address() ) : ?>

		<tr>
			<th scope="row">
				<?php esc_html_e( 'Shipping Address', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php

				echo wp_kses(
					nl2br( $payment->get_shipping_address() ),
					array(
						'br' => array(),
					)
				);

				?>
			</td>
		</tr>

	<?php endif; ?>

	<?php

	$account_holder = get_post_meta( $post_id, '_pronamic_payment_consumer_name', true );

	if ( ! empty( $account_holder ) ) : ?>

		<tr>
			<th scope="row">
				<?php esc_html_e( 'Account Holder', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php echo esc_html( $account_holder ); ?>
			</td>
		</tr>

	<?php endif; ?>

	<?php

	$account_holder_city = get_post_meta( $post_id, '_pronamic_payment_consumer_city', true );

	if ( ! empty( $account_holder_city ) ) : ?>

		<tr>
			<th scope="row">
				<?php esc_html_e( 'Account Holder City', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php echo esc_html( $account_holder_city ); ?>
			</td>
		</tr>

	<?php endif; ?>

	<?php

	$iban = get_post_meta( $post_id, '_pronamic_payment_consumer_iban', true );

	if ( ! empty( $iban ) ) : ?>

		<tr>
			<th scope="row">
				<?php

				printf(
					'<abbr title="%s">%s</abbr>',
					esc_attr( _x( 'International Bank Account Number', 'IBAN abbreviation title', 'pronamic_ideal' ) ),
					esc_html__( 'IBAN', 'pronamic_ideal' )
				);

				?>
			</th>
			<td>
				<?php echo esc_html( $iban ); ?>
			</td>
		</tr>

	<?php endif; ?>

	<?php

	$bic = get_post_meta( $post_id, '_pronamic_payment_consumer_bic', true );

	if ( ! empty( $bic ) ) : ?>

		<tr>
			<th scope="row">
				<?php

				printf(
					'<abbr title="%s">%s</abbr>',
					esc_attr( _x( 'Bank Identifier Code', 'BIC abbreviation title', 'pronamic_ideal' ) ),
					esc_html__( 'BIC', 'pronamic_ideal' )
				);

				?>
			</th>
			<td>
				<?php echo esc_html( $bic ); ?>
			</td>
		</tr>

	<?php endif; ?>

	<tr>
		<th scope="row">
			<?php esc_html_e( 'Source', 'pronamic_ideal' ); ?>
		</th>
		<td>
			<?php

			echo $payment->get_source_text(); // WPCS: XSS ok.

			?>
		</td>
	</tr>

	<?php if ( 's2member' === $payment->get_source() ) : ?>

		<tr>
			<th scope="row">
				<?php esc_html_e( 'Period', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php echo esc_html( get_post_meta( $payment->get_id(), '_pronamic_payment_s2member_period', true ) ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php esc_html_e( 'Level', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php echo esc_html( get_post_meta( $payment->get_id(), '_pronamic_payment_s2member_level', true ) ); ?>
			</td>
		</tr>

	<?php endif; ?>

	<?php if ( 'wp-e-commerce' === $payment->get_source() ) : ?>

		<tr>
			<th scope="row">
				<?php esc_html_e( 'Purchase ID', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php echo esc_html( get_post_meta( $payment->get_id(), '_pronamic_payment_wpsc_purchase_id', true ) ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php esc_html_e( 'Session ID', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php echo esc_html( get_post_meta( $payment->get_id(), '_pronamic_payment_wpsc_session_id', true ) ); ?>
			</td>
		</tr>

	<?php endif; ?>

	<?php if ( 'membership' === $payment->get_source() ) : ?>

		<tr>
			<th scope="row">
				<?php esc_html_e( 'User ID', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php echo esc_html( get_post_meta( $payment->get_id(), '_pronamic_payment_membership_user_id', true ) ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php esc_html_e( 'Subscription ID', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php echo esc_html( get_post_meta( $payment->get_id(), '_pronamic_payment_membership_subscription_id', true ) ); ?>
			</td>
		</tr>

	<?php endif; ?>

	<?php if ( PRONAMIC_PAY_DEBUG ) : ?>

		<tr>
			<th scope="row">
				<?php esc_html_e( 'User Agent', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php echo esc_html( $payment->user_agent ); ?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php esc_html_e( 'IP Address', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<?php echo esc_html( $payment->user_ip ); ?>
			</td>
		</tr>

	<?php endif; ?>
</table>
