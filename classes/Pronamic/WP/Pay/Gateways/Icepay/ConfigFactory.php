<?php

class Pronamic_WP_Pay_Gateways_Icepay_ConfigFactory {
	public static function get_config( $post_id ) {
		$config = new Pronamic_Gateways_Icepay_Config();

		$config->merchant_id = get_post_meta( $post_id, '_pronamic_gateway_icepay_merchant_id', true );
		$config->secret_code = get_post_meta( $post_id, '_pronamic_gateway_icepay_secret_code', true );

		return $config;
	}
}
