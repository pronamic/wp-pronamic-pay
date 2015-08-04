<?php

$statuses = array(
	null,
	Pronamic_WP_Pay_Gateways_IDeal_Statuses::SUCCESS,
	Pronamic_WP_Pay_Gateways_IDeal_Statuses::CANCELLED,
	Pronamic_WP_Pay_Gateways_IDeal_Statuses::EXPIRED,
	Pronamic_WP_Pay_Gateways_IDeal_Statuses::FAILURE,
	Pronamic_WP_Pay_Gateways_IDeal_Statuses::OPEN,
);

?>
<div class="pronamic-pay-inner">
	<p>
		<label for="pronamic-payment-status">Status:</span>&nbsp;
		<select id="pronamic-payment-status" name="pronamic_payment_status" class="medium-text">
			<?php

			$current = get_post_meta( $post->ID, '_pronamic_payment_status', true );

			foreach ( $statuses as $status ) {
				printf(
					'<option value="%s" %s>%s</option>',
					esc_attr( $status ),
					selected( $status, $current, false ),
					esc_html( Pronamic_WP_Pay_Plugin::translate_status( $status ) )
				);
			}

			?>
		</select>
	</p>
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
