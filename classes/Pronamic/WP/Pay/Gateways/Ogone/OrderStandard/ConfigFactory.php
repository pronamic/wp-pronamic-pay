<?php

class Pronamic_WP_Pay_Gateways_Ogone_OrderStandard_ConfigFactory {
	public static function get_config( $post_id ) {
		$config = new Pronamic_Pay_Gateways_Ogone_OrderStandard_Config();

		$config->mode = get_post_meta( $post_id, '_pronamic_gateway_mode', true );

		$config->psp_id = get_post_meta( $post_id, '_pronamic_gateway_ogone_psp_id', true );

		$config->hash_algorithm = get_post_meta( $post_id, '_pronamic_gateway_ogone_hash_algorithm', true );

		$config->sha_in_pass_phrase = get_post_meta( $post_id, '_pronamic_gateway_ogone_sha_in_pass_phrase', true );
		$config->sha_out_pass_phrase = get_post_meta( $post_id, '_pronamic_gateway_ogone_sha_out_pass_phrase', true );

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
