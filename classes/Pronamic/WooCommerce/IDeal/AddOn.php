<?php 

/**
 * Title: WooCommerce iDEAL Add-On
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WooCommerce_IDeal_AddOn {
	/**
	 * Slug
	 * 
	 * @var string
	 */
	const SLUG = 'woocommerce';

	//////////////////////////////////////////////////

	/**
	 * Bootstrap
	 */
	public static function bootstrap() {
		add_filter('woocommerce_payment_gateways', array(__CLASS__, 'addGateway'));
		
		add_action('pronamic_ideal_status_update', array(__CLASS__, 'updateStatus'), 10, 2);
		
		add_filter('pronamic_ideal_source_column_woocommerce', array(__CLASS__, 'sourceColumn'), 10, 2);
	}

	//////////////////////////////////////////////////

	/**
	 * Checks if Shopp is supported
	 * 
	 * @return true if Shopp is supported, false otherwise
	 */
	public static function isWooCommerceSupported() {
		return defined('WOOCOMMERCE_VERSION');
	}

	//////////////////////////////////////////////////

	/**
	 * Add the gateway to WooCommerce
	 */
	function addGateway($methods) {
		$methods[] = 'Pronamic_WooCommerce_IDeal_IDealGateway';

		return $methods;
	}

	//////////////////////////////////////////////////
	
	/**
	 * Update lead status of the specified payment
	 * 
	 * @param string $payment
	 */
	public static function updateStatus(Pronamic_WordPress_IDeal_Payment $payment, $return = false) {
		if($payment->getSource() == self::SLUG && self::isWooCommerceSupported()) {
			$id = $payment->getSourceId();
			$transaction = $payment->transaction;

			$order = new woocommerce_order((int) $id);

			if ($order->status !== 'completed') {				
				$url = null;

				$status = $transaction->getStatus();

				switch($status) {
					case Pronamic_IDeal_Transaction::STATUS_CANCELLED:
						$order->update_status('cancelled', __('iDEAL payment cancelled.', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN));
						break;
					case Pronamic_IDeal_Transaction::STATUS_EXPIRED:
						$order->update_status('expired', __('iDEAL payment expired.', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN));
						break;
					case Pronamic_IDeal_Transaction::STATUS_FAILURE:
						$order->update_status('expired', __('iDEAL payment expired.', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN));
						break;
					case Pronamic_IDeal_Transaction::STATUS_SUCCESS:
		            	// Payment completed
		                $order->add_order_note(__('iDEAL payment completed.', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN));
		                $order->payment_complete();
		                
		                $url = add_query_arg('key', $order->order_key, add_query_arg('order', $order->id, get_permalink(get_option('woocommerce_thanks_page_id'))));

						break;
					case Pronamic_IDeal_Transaction::STATUS_OPEN:
						$order->update_status('open', __('iDEAL payment open.', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN));
						break;
					default:
						$order->update_status('unknown', __('iDEAL payment unknown.', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN));
						break;
				}
				
				if($url && $return) {
					wp_redirect($url, 303);

					exit;
				}
			}
		}
	}

	//////////////////////////////////////////////////
	
	/**
	 * Source column
	 */
	public static function sourceColumn($text, $payment) {
		$text  = '';
		$text .= __('WooCommerce', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) . '<br />';
		$text .= sprintf('<a href="%s">', get_edit_post_link($payment->getSourceId()));
		$text .= sprintf(__('Order #%s', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN), $payment->getSourceId());
		$text .= '</a>';

		return $text;
	}
}
