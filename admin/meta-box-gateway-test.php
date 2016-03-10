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

	$input_html = array();

	?>

		<label for="pronamic-pay-test-payment-methods">
			<?php echo esc_html( $payment_methods['label'] ); ?>
		</label>

		<select id="pronamic-pay-test-payment-methods" name="pronamic_pay_test_payment_method">

		<?php

		foreach ( $payment_methods['choices'][0]['options'] as $payment_method => $method_name ) {
			$gateway->set_payment_method( $payment_method );

			printf(
				'<option value="%s">%s</option>',
				esc_attr( $payment_method ),
				esc_html( $method_name )
			);

			// Payment method input HTML
			$input_html[] = sprintf(
				'<div class="pronamic-pay-test-payment-method %s">%s</div>',
				esc_attr( $payment_method ),
				$gateway->get_input_html()
			);
		}

		?>

		</select>

	<?php

	echo implode( '', $input_html ); //xss ok

	if ( $gateway->has_error() ) {
		$pronamic_ideal_errors[] = $gateway->get_error();
	}

	include Pronamic_WP_Pay_Plugin::$dirname . '/views/errors.php';

	?>

	<p>
		<label for="test_amount">&euro;</label>
		<input name="test_amount" id="test_amount" value="" type="text" size="6" />

		<?php

		submit_button( __( 'Test', 'pronamic_ideal' ), 'secondary', 'test_pay_gateway', false );

		?>
	</p>

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
