<?php

class Pronamic_WP_Pay_Gateways_Ogone_OrderStandard_ConfigFactory {
	public static function get_config( $post_id ) {
		$config = new Pronamic_Pay_Gateways_Ogone_OrderStandard_Config();

		$config->psp_id   = get_post_meta( $post_id, '_pronamic_gateway_ogone_pspid', true );

		return $config;
	}
}
