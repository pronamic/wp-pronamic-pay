<?php

class Pronamic_WP_Pay_Gateways_Qantani_ConfigFactory {
	public static function get_config( $post_id ) {
		$config = new Pronamic_Gateways_Qantani_Config();

		$config->merchant_id     = get_post_meta( $post_id, '_pronamic_gateway_qantani_merchant_id', true );
		$config->merchant_secret = get_post_meta( $post_id, '_pronamic_gateway_qantani_merchant_secret', true );
		$config->merchant_key    = get_post_meta( $post_id, '_pronamic_gateway_qantani_merchant_key', true );

		return $config;
	}
}
