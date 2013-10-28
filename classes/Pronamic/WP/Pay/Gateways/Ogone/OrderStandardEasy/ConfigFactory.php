<?php

class Pronamic_WP_Pay_Gateways_Ogone_OrderStandardEasy_ConfigFactory {
	public static function get_config( $post_id ) {
		$config = new Pronamic_Pay_Gateways_Ogone_OrderStandardEasy_Config();

		$config->psp_id = get_post_meta( $post_id, '_pronamic_gateway_ogone_psp_id', true );

		$config->mode = get_post_meta( $post_id, '_pronamic_gateway_mode', true );

		$gateway_id = get_post_meta( $post_id, '_pronamic_gateway_id', true );
		$mode       = get_post_meta( $post_id, '_pronamic_gateway_mode', true );
		
		global $pronamic_pay_gateways;

		$gateway  = $pronamic_pay_gateways[$gateway_id];
		$settings = $gateway[$mode];
		
		$url = $settings['payment_server_url'];
		
		$config->url = $url;

		return $config;
	}
}
