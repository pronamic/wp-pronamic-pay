<?php 
/**
 * Title: Shopp iDEAL Add-On
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Shopp_IDeal_AddOn {
	/**
	 * Slug
	 */
	const SLUG = 'shopp';

	//////////////////////////////////////////////////
	
	/**
	 * Bootstrap
	 */
	public static function bootstrap() {
		// Actions
		add_action('shopp_init', array(__CLASS__, 'intialize'));
	}

	//////////////////////////////////////////////////

	/**
	 * Initialize the Shopp Add-On
	 */
	public static function intialize() {
		self::addGateway();

		// Actions
		add_action('pronamic_ideal_status_update', array(__CLASS__, 'updateStatus'), 10, 2);
		
		// Filters
		add_filter('pronamic_ideal_source_column_shopp', array(__CLASS__, 'sourceColumn'), 10, 2);

		add_filter('shopp_checkout_form', array(__CLASS__, 'showMessage'));
		add_filter('shopp_cart_template', array(__CLASS__, 'showMessage'));
		add_filter('shopp_catalog', array(__CLASS__, 'showMessage'));
	}

	//////////////////////////////////////////////////

	/**
	 * Checks if Shopp is supported
	 * 
	 * @return true if Shopp is supported, false otherwise
	 */
	public static function isShoppSupported() {
		return defined('SHOPP_VERSION');
	}

	//////////////////////////////////////////////////

	/**
	 * Add the Shopp gateway
	 */
	public static function addGateway() {
		global $Shopp;
		
		$path = dirname(__FILE__);
		$file = '/GatewayModule.php';
		
		$module = new ModuleFile($path, $file);
		if($module->addon){
			$Shopp->Gateways->modules[$module->subpackage] = $module;
		} else {
			$Shopp->Gateways->legacy[] = md5_file($path . $file);
		}

		if(isset($Shopp->Settings)) {
			$activeGateways = $Shopp->Settings->get('active_gateways');

			if(strpos($activeGateways, 'Pronamic_Shopp_IDeal_GatewayModule') !== false) {
				$Shopp->Gateways->activated[] = 'Pronamic_Shopp_IDeal_GatewayModule';
			}
		}
	}
	
	//////////////////////////////////////////////////
	
	/**
	 * Update lead status of the specified advanced payment
	 * 
	 * @param Pronamic_WordPress_IDeal_Payment $payment
	 */
	public static function updateStatus(Pronamic_WordPress_IDeal_Payment $payment, $canRedirect = false) {
		if($payment->getSource() == self::SLUG && self::isShoppSupported()){
			global $Shopp, $wpdb;
			
			$transaction = $payment->transaction;			
			if($order->status !== 'completed'){								
				$url = null;
				$setstatus = null;
				$status = $transaction->getStatus();
				switch($status){
					case Pronamic_IDeal_Transaction::STATUS_CANCELLED:
						$setstatus = 'CANCELLED';
						$url = shoppurl(array('messagetype' => 'cancelled'), 'catalog');
						$Shopp->resession();
						break;
					case Pronamic_IDeal_Transaction::STATUS_EXPIRED:
						$setstatus = 'EXPIRED';
						$url = shoppurl(array('messagetype' => 'expired'), 'checkout');
						break;
					case Pronamic_IDeal_Transaction::STATUS_FAILURE:
						$setstatus = 'FAILURE';
						$url = shoppurl(array('messagetype' => 'failure'), 'checkout');
						break;
					case Pronamic_IDeal_Transaction::STATUS_SUCCESS:
						$setstatus = 'CHARGED';
						$url = $url = shoppurl(false, 'thanks');
						$Shopp->resession();
						break;
					case Pronamic_IDeal_Transaction::STATUS_OPEN:
						$setstatus = 'OPEN';
						$url = shoppurl(array('messagetype' => 'open'), 'checkout');
						break;
				}
				
				if($setstatus){
					$wpdb->update($wpdb->prefix.SHOPP_DBPREFIX.'purchase', array('txnstatus' => $setstatus), array('id' => $payment->getSourceId()));
				}

				if($url && $canRedirect) {
					wp_redirect($url, 303);

					exit;
				}
			}
		}
	}
	
	//////////////////////////////////////////////////
	
	/**
	 * Gets the $_GET['mesagetype'] from the url and returns
	 * the queued page with the message to Shopp.
	 */
	public static function showMessage($output){
		$message = '';
		// Pick something to display
		switch(strtolower($_GET['messagetype'])){
			case 'cancelled':
				$message .= __('Payment of the order has been cancelled.', 'pronamic_ideal');
				break;
			case 'error':
				$message .= __('An unexpected error occured during transaction.', 'pronamic_ideal');
				break;
			case 'expired':
				$message .= __('The maximum transaction time expired.', 'pronamic_ideal');
				break;
			case 'failure':
				$message .= __('The transaction failed for an unknown reason.', 'pronamic_ideal');
				break;
			case 'open':
				$message .= __('Transaction was not finished and was left open.', 'pronamic_ideal');
				break;
		}
		
		// Display an error message when message has a value
		$messageoutput = '';
		$error = new ShoppError($message);
		if(!empty($message)){
			$messageoutput .= '<div id="errors" class="shopp">
				<h3>'.__('Errors','Shopp').'</h3>
				<p>
					'.$error->message().'
				</p>
			</div>';
		}
		
		return $messageoutput.$output;
	}
	
	//////////////////////////////////////////////////
	
	/**
	 * Source column
	 */
	public static function sourceColumn($text, $payment) {
		$text  = '';
		$text .= __('Shopp', 'pronamic_ideal') . '<br />';
		$text .= sprintf('<a href="%s">', add_query_arg(array('page' => 'shopp-orders', 'id' => $payment->getSourceId()), admin_url('admin.php')));
		$text .= sprintf(__('Order #%s', 'pronamic_ideal'), $payment->getSourceId());
		$text .= '</a>';

		return $text;
	}
}
