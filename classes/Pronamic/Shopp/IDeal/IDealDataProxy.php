<?php

class Pronamic_Shopp_IDeal_IDealDataProxy extends Pronamic_WordPress_IDeal_IDealDataProxy {
	private $purchase;

	private $gateway;

	//////////////////////////////////////////////////

	public function __construct($purchase, $gateway) {
		$this->purchase = $purchase;
		$this->gateway = $gateway;
	}

	//////////////////////////////////////////////////

	public function getSource() {
		return 'shopp';
	}

	//////////////////////////////////////////////////

	public function getDescription() {
		return sprintf(__('Order %s', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN), $this->purchase->id);
	}

	public function getOrderId() {
		return $this->purchase->id;
	}

	public function getItems() {
		$items = new Pronamic_IDeal_Items();

		// Purchased
		foreach($this->purchase->purchased as $p) {
			// Item
			$item = new Pronamic_IDeal_Item();
			$item->setNumber($p->id);
			$item->setDescription($p->name);
			$item->setQuantity($p->quantity);
			$item->setPrice($p->unitprice);

			$items->addItem($item);
		}
		
		// Freight
		if(!empty($this->purchase->freight)) {
			$item = new Pronamic_IDeal_Item();
			$item->setNumber('freight');
			$item->setDescription(__('Shipping', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN));
			$item->setQuantity(1);
			$item->setPrice($this->purchase->freight);

			$items->addItem($item);
		}
		
		// Tax
		if(!empty($this->purchase->tax)) {
			$item = new Pronamic_IDeal_Item();
			$item->setNumber('tax');
			$item->setDescription(__('Tax', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN));
			$item->setQuantity(1);
			$item->setPrice($this->purchase->tax);

			$items->addItem($item);
		}

		return $items;
	}

	//////////////////////////////////////////////////
	// Currency
	//////////////////////////////////////////////////

	public function getCurrencyAlphabeticCode() {
		return $this->gateway->baseop['currency']['code'];
	}

	//////////////////////////////////////////////////
	// Customer
	//////////////////////////////////////////////////

	public function getEMailAddress() {
		return $this->purchase->email;
	}

	public function getCustomerName() {
		return $this->purchase->firstname . ' ' . $purchase->lastname;
	}

	public function getOwnerAddress() {
		return $this->purchase->address;
	}

	public function getOwnerCity() {
		return $this->purchase->city;
	}

	public function getOwnerZip() {
		return $this->purchase->postcode;
	}

	//////////////////////////////////////////////////
	// URL's
	//////////////////////////////////////////////////
	
	public function getNormalReturnUrl() {
		return shoppurl(false, 'thanks');
	}
	
	public function getCancelUrl() {
		return shoppurl(array('messagetype' => 'cancelled'), 'receipt');
	}
	
	public function getSuccessUrl() {
		return shoppurl(false, 'thanks');
	}
	
	public function getErrorUrl() {
		shoppurl(array('messagetype' => 'error'), 'receipt');
	}
}
