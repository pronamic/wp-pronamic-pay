<?php

class Pronamic_WP_Pay_Gateways_Mollie_ConfigFactory {
	public static function get_config( $post_id ) {
		$config = new Pronamic_Gateways_Mollie_Config();

		$config->partner_id  = get_post_meta( $post_id, '_pronamic_gateway_mollie_partner_id', true );
		$config->profile_key = get_post_meta( $post_id, '_pronamic_gateway_mollie_profile_key', true );

		$config->mode        = get_post_meta( $post_id, '_pronamic_gateway_mode', true );

		return $config;
	}
}
