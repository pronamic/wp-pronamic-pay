<?php 

$id = get_post_meta( get_the_ID(), '_pronamic_gateway_id', true );

global $pronamic_pay_gateways;

if ( isset( $pronamic_pay_gateways[$id] ) ) {
	$gateway      = $pronamic_pay_gateways[$id];
	$gateway_slug = $gateway['gateway'];

	switch ( $gateway_slug ) {
		case 'easy':
			include 'test-method-easy.php';
			break;
		case 'basic':
			include 'test-method-basic.php';
			break;
		case 'internetkassa':
			include 'test-method-internetkassa.php';
			break;
		case 'omnikassa':
			include 'test-method-omnikassa.php';
			break;
		case 'advanced':
			include 'test-method-advanced.php';
			break;
		case 'advanced_v3':
			include 'test-method-advanced-v3.php';
			break;
		case 'mollie':
			include 'test-method-mollie.php';
			break;  
		case 'buckaroo':
			include 'test-method-buckaroo.php';
			break;
		case 'sisow':
			include 'test-method-sisow.php';
			break;
		case 'targetpay':
			include 'test-method-targetpay.php';
			break;
		case 'qantani':
			include 'test-method-qantani.php';
			break;
		case 'icepay':
			include 'test-method-icepay.php';
			break;
	}
}
