<h3>
	<?php _e( 'Retrieve Issuers Lists', 'pronamic_ideal' ); ?>
</h3>

<?php

/*
 * 
 * openssl genrsa -aes128 -out test.txt 2048
 * 
 * openssl genrsa -aes128 -out priv.pem -passout pass:testabcd 2048
 * openssl req -x509 -new -key priv.pem -passin pass:testabcd -days 3650 -out cert.cer
 * 
 */

include dirname( __FILE__ ) . '/../includes/xmlseclibs/xmlseclibs.php';

$data = new Pronamic_WordPress_IDeal_IDealTestDataProxy( wp_get_current_user(), 10 );

$gateway = new Pronamic_Gateways_IDealAdvancedV3_Gateway( $configuration, $data );

echo $gateway->get_html_fields();

$gateway->start();

$gateway->redirect();
