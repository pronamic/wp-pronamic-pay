<?php

class Pronamic_WPeCommerce_IDeal_IDealDataProxy extends Pronamic_WordPress_IDeal_IDealDataProxy {
	private $merchant;

	//////////////////////////////////////////////////

	public function __construct($merchant) {
		$this->merchant = $merchant;
	}

	//////////////////////////////////////////////////

	public function getSource() {
		return 'wp-e-commerce';
	}

	//////////////////////////////////////////////////

	public function getDescription() {
		return sprintf(__('Order %s', 'pronamic_ideal'), $this->merchant->purchase_id);
	}

	public function getOrderId() {
		return $this->merchant->purchase_id;
	}

	public function getItems() {
		// Items
		$items = new Pronamic_IDeal_Items();

		// Item
		// We only add one total item, because iDEAL cant work with negative price items (discount)
		$item = new Pronamic_IDeal_Item();
		$item->setNumber($this->merchant->purchase_id);
		$item->setDescription(sprintf(__('Order %s', 'pronamic_ideal'), $this->merchant->purchase_id));
		$item->setPrice($this->merchant->cart_data['total_price']);
		$item->setQuantity(1);

		$items->addItem($item);

		return $items;
	}

	//////////////////////////////////////////////////
	// Currency
	//////////////////////////////////////////////////

	public function getCurrencyAlphabeticCode() {
		return $this->merchant->cart_data['store_currency'];
	}

	//////////////////////////////////////////////////
	// Customer
	//////////////////////////////////////////////////

	public function getEMailAddress() {
		return $this->merchant->cart_data['email_address'];
	}

	public function getCustomerName() {
		return $this->merchant->cart_data['billing_address']['first_name'] . ' ' . $this->cart_data['billing_address']['last_name'];
	}

	public function getOwnerAddress() {
		return $this->merchant->cart_data['billing_address']['address'];
	}

	public function getOwnerCity() {
		return $this->merchant->cart_data['billing_address']['city'];
	}

	public function getOwnerZip() {
		return $this->merchant->cart_data['billing_address']['post_code'];
	}

	//////////////////////////////////////////////////
	// URL's
	//////////////////////////////////////////////////
	
	public function getNormalReturnUrl() {
		return add_query_arg(
			array(
				'sessionid' => $this->merchant->cart_data['session_id'], 
				'gateway' => 'iDeal'
			) , 
			get_option('transact_url')
		);
	}
	
	public function getCancelUrl() {
		return add_query_arg(
			array(
				'sessionid' => $this->merchant->cart_data['session_id'], 
				'return' => 'cancel'
			) , 
			get_option('shopping_cart_url')
		);
	}
	
	public function getSuccessUrl() {
		return add_query_arg(
			array(
				'sessionid' => $this->merchant->cart_data['session_id'], 
				'gateway' => 'iDeal'
			) , 
			get_option('transact_url')
		);
	}
	
	public function getErrorUrl() {
		return add_query_arg(
			array(
				'sessionid' => $this->merchant->cart_data['session_id'], 
				'return' => 'error'
			) , 
			get_option('shopping_cart_url')
		);
	}
}
