<?php

class Pronamic_WP_Pay_Gateways_Sisow_ConfigFactory {
	public static function get_config( $post_id ) {
		$config = new Pronamic_Gateways_Sisow_Config();

		$config->mode         = get_post_meta( $post_id, '_pronamic_gateway_mode', true );

		$config->merchant_id  = get_post_meta( $post_id, '_pronamic_gateway_sisow_merchant_id', true );
		$config->merchant_key = get_post_meta( $post_id, '_pronamic_gateway_sisow_merchant_key', true );
		
		return $config;
	}
}
