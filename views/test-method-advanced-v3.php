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

$request_dir_message = new Pronamic_Gateways_IDealAdvancedV3_XML_DirectoryRequestMessage();
$merchant = $request_dir_message->get_merchant();
$merchant->id = $configuration->getMerchantId();
$merchant->sub_id = $configuration->getSubId();

$document = $request_dir_message->get_document();

?>

<pre><?php echo htmlspecialchars( $document->saveXML() ); ?></pre>

<?php
/*
$document = new DOMDocument();
$document->preserveWhiteSpace = false;
$document->formatOutput = true;
$document->loadXML('<?xml version="1.0" encoding="UTF-8"?>
<DirectoryReq xmlns="http://www.idealdesk.com/ideal/messages/mer-acq/3.3.1" version="3.3.1">
<createDateTimestamp>2012-11-09T20:43:48.000Z</createDateTimestamp>
<Merchant>
<merchantID>005081723</merchantID>
<subID>0</subID>
</Merchant>
</DirectoryReq>');
*/
$objDSig = new XMLSecurityDSig();
$objDSig->setCanonicalMethod( XMLSecurityDSig::EXC_C14N );
$objDSig->addReference( $document, XMLSecurityDSig::SHA256, array( 'http://www.w3.org/2000/09/xmldsig#enveloped-signature' ), array( 'force_uri' => true ) );

$objKey = new XMLSecurityKey( XMLSecurityKey::RSA_SHA256, array( 'type' => 'private' ) );

$objKey->passphrase = $configuration->privateKeyPassword;
$objKey->loadKey( $configuration->privateKey );

$objDSig->sign($objKey);

$fingerprint = Pronamic_Gateways_IDealAdvanced_Security::getShaFingerprint( $configuration->privateCertificate );

$objDSig->addKeyInfoAndName( $fingerprint );
$objDSig->appendSignature( $document->documentElement);

$str = $document->saveXML();

$response = wp_remote_post( 'https://idealtest.secure-ing.com/ideal/iDEALv3', array(
	'method' => 'POST',
	'headers' => array(
		'Content-Type' => 'text/xml'
	),
	'body' => $str
) );

$body = wp_remote_retrieve_body( $response );

?>
<pre><?php echo htmlspecialchars( $str ); ?></pre>
<pre><?php echo htmlspecialchars( $body ); ?></pre>