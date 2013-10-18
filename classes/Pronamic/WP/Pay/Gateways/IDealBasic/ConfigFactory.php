<?php

class Pronamic_WP_Pay_Gateways_IDealBasic_ConfigFactory {
	public static function get_config( $post_id ) {
		$config = new Pronamic_Gateways_IDealBasic_Config();

		$config->merchant_id = get_post_meta( $post_id, '_pronamic_gateway_ideal_merchant_id', true );
		$config->sub_id      = get_post_meta( $post_id, '_pronamic_gateway_ideal_sub_id', true );
		$config->hash_key    = get_post_meta( $post_id, '_pronamic_gateway_ideal_hash_key', true );

		$gateway_id = get_post_meta( $post_id, '_pronamic_gateway_id', true );
		$mode       = get_post_meta( $post_id, '_pronamic_gateway_mode', true );
		
		global $pronamic_pay_gateways;

		if ( isset( $pronamic_pay_gateways[$gateway_id] ) ) {
			$gateway  = $pronamic_pay_gateways[$gateway_id];
			
			if ( isset( $gateway[$mode] ) ) {
				$settings = $gateway[$mode];
		
				$url = $settings['payment_server_url'];

				$config->url = $url;
			}
		}
		
		return $config;
	}
}
