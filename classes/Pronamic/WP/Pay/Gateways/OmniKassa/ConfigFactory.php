<?php

class Pronamic_WP_Pay_Gateways_OmniKassa_ConfigFactory extends Pronamic_WP_Pay_GatewayConfigFactory {
	public function get_config( $post_id ) {
		$config = new Pronamic_Gateways_OmniKassa_Config();

		$config->merchant_id = get_post_meta( $post_id, '_pronamic_gateway_omnikassa_merchant_id', true );
		$config->secret_key  = get_post_meta( $post_id, '_pronamic_gateway_omnikassa_secret_key', true );
		$config->key_version = get_post_meta( $post_id, '_pronamic_gateway_omnikassa_key_version', true );

		return $config;
	}
}
