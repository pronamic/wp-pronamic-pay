<?php

class Pronamic_WP_Pay_Gateways_Ogone_DirectLink_ConfigFactory {
	public static function get_config( $post_id ) {
		$config = new Pronamic_Pay_Gateways_Ogone_DirectLink_Config();

		$config->mode = get_post_meta( $post_id, '_pronamic_gateway_mode', true );

		$config->psp_id = get_post_meta( $post_id, '_pronamic_gateway_ogone_psp_id', true );

		$config->hash_algorithm = get_post_meta( $post_id, '_pronamic_gateway_ogone_hash_algorithm', true );

		$config->user_id  = get_post_meta( $post_id, '_pronamic_gateway_ogone_user_id', true );
		$config->password = get_post_meta( $post_id, '_pronamic_gateway_ogone_password', true );

		$config->sha_in_pass_phrase = get_post_meta( $post_id, '_pronamic_gateway_ogone_directlink_sha_in_pass_phrase', true );

		if ( $config->mode == Pronamic_IDeal_IDeal::MODE_TEST ) {
			$config->api_url = Pronamic_Pay_Gateways_Ogone_DirectLink::API_TEST_URL;
		} else {
			$config->api_url = Pronamic_Pay_Gateways_Ogone_DirectLink::API_PRODUCTION_URL;
		}

		return $config;
	}
}
