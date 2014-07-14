<?php

class Pronamic_WP_Pay_Gateways_Buckaroo_ConfigFactory extends Pronamic_WP_Pay_GatewayConfigFactory {
	public function get_config( $post_id ) {
		$config = new Pronamic_Gateways_Buckaroo_Config();

		$config->website_key = get_post_meta( $post_id, '_pronamic_gateway_buckaroo_website_key', true );
		$config->secret_key  = get_post_meta( $post_id, '_pronamic_gateway_buckaroo_secret_key', true );

		$config->mode        = get_post_meta( $post_id, '_pronamic_gateway_mode', true );

		return $config;
	}
}
