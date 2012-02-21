<?php

class Pronamic_Jigoshop_IDeal_IDealDataProxy extends Pronamic_WordPress_IDeal_IDealDataProxy {
	private $order;

	//////////////////////////////////////////////////

	public function __construct($order) {
		$this->order = $order;
	}

	//////////////////////////////////////////////////

	public function getSource() {
		return 'jigoshop';
	}

	//////////////////////////////////////////////////

	public function getDescription() {
		return sprintf(__('Order %s', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN), $this->order->id);
	}

	public function getOrderId() {
		return $this->order->id;
	}

	public function getItems() {
		// Items
		$items = new Pronamic_IDeal_Items();

		// Item
		// We only add one total item, because iDEAL cant work with negative price items (discount)
		$item = new Pronamic_IDeal_Item();
		$item->setNumber($this->order->id);
		$item->setDescription(sprintf(__('Order %s', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN), $this->order->id));
		$item->setPrice($this->order->order_total);
		$item->setQuantity(1);

		$items->addItem($item);

		return $items;
	}

	//////////////////////////////////////////////////
	// Currency
	//////////////////////////////////////////////////

	public function getCurrencyAlphabeticCode() {
		return get_option('jigoshop_currency');
	}

	//////////////////////////////////////////////////
	// Customer
	//////////////////////////////////////////////////

	public function getEMailAddress() {
		return $this->order->billing_email;
	}

	public function getCustomerName() {
		return $this->order->billing_first_name . ' ' . $this->order->billing_last_name;
	}

	public function getOwnerAddress() {
		return $this->order->billing_address_1;
	}

	public function getOwnerCity() {
		return $this->order->billing_city;
	}

	public function getOwnerZip() {
		return $this->order->billing_postcode;
	}

	//////////////////////////////////////////////////
	// URL's
	//////////////////////////////////////////////////
	
	public function getNormalReturnUrl() {
		return add_query_arg(
			array(
				'key' => $this->order->order_key ,
				'order' => $this->order->id
			) , 
			get_permalink(get_option('jigoshop_thanks_page_id'))
		);
	}
	
	public function getCancelUrl() {
		return $this->order->get_cancel_order_url();
	}
	
	public function getSuccessUrl() {
		return add_query_arg(
			array(
				'key' => $this->order->order_key ,
				'order' => $this->order->id
			) , 
			get_permalink(get_option('jigoshop_thanks_page_id'))
		);
	}

	public function getErrorUrl() {
		return $thisorder->get_checkout_payment_url();
	}
}
