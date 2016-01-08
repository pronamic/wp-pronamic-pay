<?php

global $pronamic_ideal_errors;

$gateway = Pronamic_WP_Pay_Plugin::get_gateway( get_the_ID() );

if ( $gateway ) {
	wp_nonce_field( 'test_pay_gateway', 'pronamic_pay_test_nonce' );

	echo $gateway->get_input_html(); //xss ok

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

	$is_ideal  = false;
	$is_ideal |= $gateway instanceof Pronamic_WP_Pay_Gateways_IDealBasic_Gateway;
	$is_ideal |= $gateway instanceof Pronamic_WP_Pay_Gateways_IDealAdvanced_Gateway;
	$is_ideal |= $gateway instanceof Pronamic_WP_Pay_Gateways_IDealAdvancedV3_Gateway;

	if ( $is_ideal ) {
		include Pronamic_WP_Pay_Plugin::$dirname . '/views/ideal-test-cases.php';
	}
} else {
	printf(
		'<em>%s</em>',
		__( 'Please save the entered account details of your payment provider, to make a test payment.', 'pronamic_ideal' )
	);
}
