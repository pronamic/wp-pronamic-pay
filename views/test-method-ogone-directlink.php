<?php

$url = 'https://secure.ogone.com/ncol/test/orderdirect.asp';

$kassa = new Pronamic_Gateways_IDealInternetKassa_IDealInternetKassa();
$kassa->setPspId( 'pronamic2' );
$kassa->setPassPhraseIn( '' );
$kassa->setOrderId( time() );
$kassa->set_field( 'USERID', 'pronamic_api' );
$kassa->set_field( 'PSWD', '' );
$kassa->setAmount( 50 );
$kassa->setCurrency( 'EUR' );
$kassa->set_field( 'CARDNO', '5555555555554444' );
$kassa->set_field( 'CARDNO', '4111111111111111' );
$kassa->set_field( 'ED', '01/15' );
//$kassa->setOrderDescription( 'Description' );
//$kassa->setCustomerName( 'Pronamic Test' );
//$kassa->setEMailAddress( 'test@pronamic.nl' );
$kassa->set_field( 'CVC', '000' );
$kassa->set_field( 'OPERATION', 'SAL' );

$data = $kassa->get_fields();
$data['SHASIGN'] = $kassa->getSignatureIn();

//var_dump($data);

$client = new Pronamic_Gateways_Ogone_DirectLink_Client();

$response = $client->order_direct( $data );
