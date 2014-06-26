<?php

class Pronamic_WP_Pay_Gateways_MultiSafepay_ConfigFactory extends Pronamic_WP_Pay_GatewayConfigFactory {
	public function get_config( $post_id ) {
		$config = new Pronamic_Pay_Gateways_MultiSafepay_Config();

		$config->mode       = get_post_meta( $post_id, '_pronamic_gateway_mode', true );
		$config->account_id = get_post_meta( $post_id, '_pronamic_gateway_multisafepay_account_id', true );
		$config->site_id    = get_post_meta( $post_id, '_pronamic_gateway_multisafepay_site_id', true );
		$config->site_code  = get_post_meta( $post_id, '_pronamic_gateway_multisafepay_site_code', true );

		if ( $config->mode == Pronamic_IDeal_IDeal::MODE_TEST ) {
			$config->api_url = Pronamic_Pay_Gateways_MultiSafepay_MultiSafepay::API_TEST_URL;
		} else {
			$config->api_url = Pronamic_Pay_Gateways_MultiSafepay_MultiSafepay::API_PRODUCTION_URL;
		}

		return $config;
	}
}
