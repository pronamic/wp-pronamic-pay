<?php

function pronamic_ideal_wpsc_merchant_form() {
	return Pronamic_WPeCommerce_IDeal_IDealMerchant::admin_configuration_form();
}

function pronamic_ideal_wpsc_merchant_submit_function() {
	return Pronamic_WPeCommerce_IDeal_IDealMerchant::admin_configuration_submit();
}
