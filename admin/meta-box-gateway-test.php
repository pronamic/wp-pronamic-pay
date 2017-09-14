<?php

global $pronamic_ideal_errors;

$gateway = Pronamic_WP_Pay_Plugin::get_gateway( get_the_ID() );

if ( $gateway ) {
	wp_nonce_field( 'test_pay_gateway', 'pronamic_pay_test_nonce' );

	$is_ideal  = false;
	$is_ideal |= $gateway instanceof Pronamic_WP_Pay_Gateways_IDealBasic_Gateway;
	$is_ideal |= $gateway instanceof Pronamic_WP_Pay_Gateways_IDealAdvanced_Gateway;
	$is_ideal |= $gateway instanceof Pronamic_WP_Pay_Gateways_IDealAdvancedV3_Gateway;

	// Payment method selector
	$payment_methods = $gateway->get_payment_method_field( true );

	$inputs = array();

	foreach ( $payment_methods['choices'][0]['options'] as $payment_method => $method_name ) {
		$gateway->set_payment_method( $payment_method );

		// Payment method input HTML
		$html = $gateway->get_input_html();

		if ( ! empty( $html ) ) {
			$inputs[ $payment_method ] = array(
				'label' => $method_name,
				'html'  => $html,
			);
		}
	}

	if ( $gateway->has_error() ) {
		$pronamic_ideal_errors[] = $gateway->get_error();
	}

	include Pronamic_WP_Pay_Plugin::$dirname . '/views/errors.php';

	?>
	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="pronamic-pay-test-payment-methods">
					<?php esc_html_e( 'Payment Method', 'pronamic_ideal' ); ?>
				</label>
			</th>
			<td>
				<select id="pronamic-pay-test-payment-methods" name="pronamic_pay_test_payment_method">
					<?php

					foreach ( $payment_methods['choices'][0]['options'] as $payment_method => $method_name ) {
						printf(
							'<option value="%s">%s</option>',
							esc_attr( $payment_method ),
							esc_html( $method_name )
						);
					}

					?>
				</select>
			</td>
		</tr>

		<?php foreach ( $inputs as $method => $input ) : ?>

			<tr class="pronamic-pay-cloack pronamic-pay-test-payment-method <?php echo esc_attr( $method ); ?>">
				<th scope="row">
					<?php echo esc_html( $input['label'] ); ?>
				</th>
				<td>
					<?php

					echo $input['html']; // WPCS: XSS ok.

					?>
				</td>
			</tr>

		<?php endforeach; ?>

		<tr>
			<th scope="row">
				<?php esc_html_e( 'Amount', 'pronamic_ideal' ); ?>
			</th>
			<td>
				<label for="test_amount">€</label>

				<input name="test_amount" id="test_amount" value="" type="text" size="6" />		
			</td>
		</tr>
		<tr>
			<td>

			</td>
			<td>
				<?php submit_button( __( 'Test', 'pronamic_ideal' ), 'secondary', 'test_pay_gateway', false ); ?>
			</td>
		</tr>
	</table>

	<?php

	if ( $is_ideal ) {
		include Pronamic_WP_Pay_Plugin::$dirname . '/views/ideal-test-cases.php';
	}
} else {
	printf(
		'<em>%s</em>',
		esc_html( __( 'Please save the entered account details of your payment provider, to make a test payment.', 'pronamic_ideal' ) )
	);
}
