<?php

function pronamic_ideal_wpsc_merchant_form() {
	return Pronamic_WPeCommerce_IDeal_IDealMerchant::adminConfigurationForm();
}

function pronamic_ideal_wpsc_merchant_submit_function() {
	return Pronamic_WPeCommerce_IDeal_IDealMerchant::adminConfigurationSubmit();
}

function pronamic_wpsc_transaction_result_content($purchase_id) {
	global $message_html, $cart;

	echo '<pre>';
	var_dump($purchase_id);
	echo '</pre>';
	echo '<pre>';
	var_dump($cart);
	echo '</pre>';
}

add_filter('wpsc_confirm_checkout', 'pronamic_wpsc_transaction_result_content', 10);
