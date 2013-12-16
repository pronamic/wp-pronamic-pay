<?php

class Pronamic_WP_Pay_Gateways_Mollie_ConfigFactory {
	public static function get_config( $post_id ) {
		$config = new Pronamic_Gateways_Mollie_Config();

		$config->api_key  = get_post_meta( $post_id, '_pronamic_gateway_mollie_api_key', true );

		return $config;
	}
}
