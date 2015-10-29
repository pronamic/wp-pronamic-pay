<?php
/**
 * Tab methods
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>
<h2><?php esc_html_e( 'Payment Methods', 'pronamic_ideal' ); ?></h2>

<?php

// @see http://www.icepay.com/downloads/pdf/documentation/ICEPAY_Supported_Parameters_Sheet.pdf
// @see http://www.one-stop-webshop.nl/webshop/betaalmethoden/

$issuers = array(
	'digiaccept' => array(
		'name' => __( 'DigiAccept', 'pronamic_ideal' ),
		'url'  => 'http://www.digiaccept.nl/',
	),
	'acceptgiro' => array(
		'name' => __( 'Acceptgiro', 'pronamic_ideal' ),
	),
	'yourgift' => array(
		'name' => __( 'YourGift', 'pronamic_ideal' ),
		'url'  => 'https://www.yourgift.nl/',
	),
	'americanexpress' => array(
		'name' => __( 'American Express', 'pronamic_ideal' ),
		'url'  => 'https://www.americanexpress.com/',
	),
	'mastercard' => array(
		'name' => __( 'MasterCard', 'pronamic_ideal' ),
		'url'  => 'http://www.mastercard.com/',
	),
);

$methods = array(
	'acceptgiro' => array(
		'name'    => __( 'Acceptgiro', 'pronamic_ideal' ),
		'url'     => 'http://www.acceptgiro.nl/',
	),
	'afterpay' => array(
		'name'    => __( 'AfterPay', 'pronamic_ideal' ),
		'url'     => 'http://www.afterpay.nl/',
		'issuers' => array(
			'digiaccept',
			'acceptgiro',
		),
	),
	'bitcoin' => array(
		'name'    => __( 'Bitcoin', 'pronamic_ideal' ),
		'issuers' => array( 'qantani' ),
	),
	'cash_on_delivery' => array( // Rembours
		'name'    => __( 'Cash on Delivery', 'pronamic_ideal' ),
		'issuers' => array(),
	),
	'giftcard' => array(
		'name'    => __( 'GiftCard', 'pronamic_ideal' ),
		'issuers' => array(
			'yourgift',
		),
	),
	'creditcard' => array(
		'name'    => __( 'Credit Card', 'pronamic_ideal' ),
		'issuers' => array(
			'americanexpress',
			'mastercard',
		),
	),
	'direct_debit' => array( // Incasso
		'name'    => __( 'Direct Debit', 'pronamic_ideal' ),
		'issuers' => array(),
	),
	'direkt_ebanking' => array(
		'name'    => __( 'Direct eBanking', 'pronamic_ideal' ),
		'issuers' => array(),
	),
	'giropay' => array(
		'name'    => __( 'Giropay', 'pronamic_ideal' ),
		'issuers' => array(),
	),
	'ideal' => array(
		'icon'    => plugins_url( 'images/icon-24x24.png', Pronamic_WP_Pay_Plugin::$file ),
		'name'    => __( 'iDEAL', 'pronamic_ideal' ),
		'issuers' => array(),
	),
	'minitix' => array(
		'name'    => __( 'MiniTix', 'pronamic_ideal' ),
		'url'     => 'http://www.minitix.nl/',
		'issuers' => array(),
	),
	'mister_cash' => array(
		'icon'    => plugins_url( 'images/mister-cash/icon-24x24.png', Pronamic_WP_Pay_Plugin::$file ),
		'name'    => __( 'Mister Cash', 'pronamic_ideal' ),
		'issuers' => array(),
	),
	'paysafecard' => array(
		'name'    => __( 'paysafecard', 'pronamic_ideal' ),
		'issuers' => array(),
	),
	'paypal' => array(
		'name'    => __( 'PayPal', 'pronamic_ideal' ),
		'issuers' => array(),
	),
	'wallie' => array(
		'name'    => __( 'Wallie', 'pronamic_ideal' ),
		'issuers' => array(),
	),
);

include 'methods-wp-admin.php';
