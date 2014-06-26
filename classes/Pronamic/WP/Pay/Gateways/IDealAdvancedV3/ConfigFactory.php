<?php

class Pronamic_WP_Pay_Gateways_IDealAdvancedV3_ConfigFactory extends Pronamic_WP_Pay_GatewayConfigFactory {
	public function get_config( $post_id ) {
		$config = new Pronamic_Gateways_IDealAdvancedV3_Config();

		$config->merchant_id = get_post_meta( $post_id, '_pronamic_gateway_ideal_merchant_id', true );
		$config->sub_id      = get_post_meta( $post_id, '_pronamic_gateway_ideal_sub_id', true );

		$config->private_key          = get_post_meta( $post_id, '_pronamic_gateway_ideal_private_key', true );
		$config->private_key_password = get_post_meta( $post_id, '_pronamic_gateway_ideal_private_key_password', true );
		$config->private_certificate  = get_post_meta( $post_id, '_pronamic_gateway_ideal_private_certificate', true );

		$gateway_id = get_post_meta( $post_id, '_pronamic_gateway_id', true );
		$mode       = get_post_meta( $post_id, '_pronamic_gateway_mode', true );

		global $pronamic_pay_gateways;

		$gateway  = $pronamic_pay_gateways[ $gateway_id ];
		$settings = $gateway[ $mode ];

		$url = $settings['payment_server_url'];

		$config->url = $url;

		$config->certificates = array();
		foreach ( $gateway['certificates'] as $certificate ) {
			$config->certificates[] = $certificate;
		}

		return $config;
	}
}
