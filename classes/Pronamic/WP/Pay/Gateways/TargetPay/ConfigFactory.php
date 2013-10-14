<?php

class Pronamic_WP_Pay_Gateways_TargetPay_ConfigFactory {
	public static function get_config( $post_id ) {
		$config = new Pronamic_Gateways_TargetPay_Config();

		$config->mode       = get_post_meta( $post_id, '_pronamic_gateway_mode', true );

		$config->layoutcode = get_post_meta( $post_id, '_pronamic_gateway_targetpay_layoutcode', true );
		
		return $config;
	}
}
