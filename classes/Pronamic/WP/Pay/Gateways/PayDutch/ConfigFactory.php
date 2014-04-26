<?php

class Pronamic_WP_Pay_Gateways_PayDutch_ConfigFactory {
	public static function get_config( $post_id ) {
		$config = new Pronamic_Gateways_PayDutch_Config();

		$config->username = get_post_meta( $post_id, '_pronamic_gateway_paydutch_username', true );
		$config->password = get_post_meta( $post_id, '_pronamic_gateway_paydutch_password', true );

		return $config;
	}
}
