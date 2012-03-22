<?php

function pronamic_ideal_wpsc_merchant_form() {
	return Pronamic_WPeCommerce_IDeal_IDealMerchant::adminConfigurationForm();
}

function pronamic_ideal_wpsc_merchant_submit_function() {
	return Pronamic_WPeCommerce_IDeal_IDealMerchant::adminConfigurationSubmit();
}
