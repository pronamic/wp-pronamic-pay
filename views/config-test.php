<?php

global $pronamic_ideal_errors;

$gateway = Pronamic_WordPress_IDeal_IDeal::get_gateway( get_the_ID() );

if ( $gateway ) {
	wp_nonce_field( 'test_pay_gateway', 'pronamic_pay_test_nonce' );

	echo $gateway->get_input_html();

	if ( $gateway->has_error() ) {
		$pronamic_ideal_errors[] = $gateway->get_error();
	}

	include 'errors.php';

	?>

	<p>
		<label for="test_amount">&euro;</label>
		<input name="test_amount" id="test_amount" value="" class="small-text" type="text" />

		<?php
	
		$name = sprintf( __( 'Test', 'pronamic_ideal' ) );
	
		submit_button( $name, 'secondary', 'test_pay_gateway', false ); 
	
		?>
	</p>

	<?php 
	
	$is_ideal  = false;
	$is_ideal |= $gateway instanceof Pronamic_Gateways_IDealBasic_Gateway;
	$is_ideal |= $gateway instanceof Pronamic_Gateways_IDealAdvanced_Gateway;
	$is_ideal |= $gateway instanceof Pronamic_Gateways_IDealAdvancedV3_Gateway;
	
	if ( $is_ideal ) {
		include 'ideal-test-cases.php';
	}
}
