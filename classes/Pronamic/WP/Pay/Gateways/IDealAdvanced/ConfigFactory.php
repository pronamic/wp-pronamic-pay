<?php

class Pronamic_WP_Pay_Gateways_IDealAdvanced_ConfigFactory {
	public static function get_config( $post_id ) {
		$config = new Pronamic_Gateways_IDealAdvanced_Config();

		$config->merchant_id = get_post_meta( $post_id, '_pronamic_gateway_ideal_merchant_id', true );
		$config->sub_id      = get_post_meta( $post_id, '_pronamic_gateway_ideal_sub_id', true );

		$config->private_key          = get_post_meta( $post_id, '_pronamic_gateway_ideal_private_key', true );
		$config->private_key_password = get_post_meta( $post_id, '_pronamic_gateway_ideal_private_key_password', true );
		$config->private_certificate  = get_post_meta( $post_id, '_pronamic_gateway_ideal_private_certificate', true );

		$gateway_id = get_post_meta( $post_id, '_pronamic_gateway_id', true );
		$mode       = get_post_meta( $post_id, '_pronamic_gateway_mode', true );

		global $pronamic_pay_gateways;

		$gateway  = $pronamic_pay_gateways[ $gateway_id ];
		$settings = $gateway[ $mode ];

		$url = $settings['payment_server_url'];

		$config->directory_request_url   = $url;
		$config->transaction_request_url = $url;
		$config->status_request_url      = $url;

		if ( isset( $settings['directory_request_url'] ) ) {
			$client->directory_request_url = $settings['directory_request_url'];
		}
		if ( isset( $settings['transaction_request_url'] ) ) {
			$client->transaction_request_url = $settings['transaction_request_url'];
		}
		if ( isset( $settings['status_request_url'] ) ) {
			$client->status_request_url = $settings['status_request_url'];
		}

		$config->certificates = array();
		foreach ( $gateway['certificates'] as $certificate ) {
			$config->certificates[] = $certificate;
		}

		return $config;
	}
}
