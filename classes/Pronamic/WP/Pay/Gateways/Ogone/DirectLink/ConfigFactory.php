<?php

class Pronamic_WP_Pay_Gateways_Ogone_DirectLink_ConfigFactory {
	public static function get_config( $post_id ) {
		$config = new Pronamic_Pay_Gateways_Ogone_DirectLink_Config();

		$config->psp_id   = get_post_meta( $post_id, '_pronamic_gateway_ogone_psp_id', true );
		$config->user_id  = get_post_meta( $post_id, '_pronamic_gateway_ogone_user_id', true );
		$config->password = get_post_meta( $post_id, '_pronamic_gateway_ogone_password', true );

		$config->sha_in_pass_phrase = get_post_meta( $post_id, '_pronamic_gateway_ogone_directlink_sha_in_pass_phrase', true );

		return $config;
	}
}
