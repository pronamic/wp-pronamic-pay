<?php 

$data = new Pronamic_WordPress_IDeal_IDealTestDataProxy( wp_get_current_user(), 10 );

$gateway = new Pronamic_Gateways_IDealAdvancedV3_Gateway( $configuration, $data );

?>

<h3>
	<?php _e( 'Mandatory Tests', 'pronamic_ideal' ); ?>
</h3>

<form method="post" action="" target="_blank">
	<?php 
	
	wp_nonce_field( 'test_ideal_advanced_v3', 'pronamic_ideal_nonce' );

	echo $gateway->get_html_fields();
	
	foreach ( array( 1, 2, 3, 4, 5, 7 ) as $test_case ) {
		$name = sprintf( __( 'Test Case %s', 'pronamic_ideal' ), $test_case );
		
		submit_button( $name, 'secondary', 'test_ideal_advanced_v3[' . $test_case . ']', false );
	}
	
	?>
</form>