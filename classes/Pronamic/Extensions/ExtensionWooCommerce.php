<?php

class Pronamic_Extensions_ExtensionWooCommerce extends Pronamic_Extensions_ExtensionInfo {
	public function get_name() {
		return __( 'WooCommerce', 'pronamic_ideal' );
	}

	public function is_active() {
		return defined( 'WOOCOMMERCE_VERSION' );
	}

	public function get_version() {
		return WOOCOMMERCE_VERSION;
	}
}
