<?php

/**
 * Title: ClassiPress iDEAL data proxy
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_ClassiPress_IDeal_IDealDataProxy extends Pronamic_WordPress_IDeal_IDealDataProxy {
	/**
	 * Order values
	 * 
	 * @var array
	 */
	private $orderValues;

	//////////////////////////////////////////////////

	/**
	 * Construct and intializes an ClassiPress iDEAL data proxy
	 * 
	 * @param array $orderValues
	 */
	public function __construct($orderValues) {
		$this->orderValues = $orderValues;
	}

	//////////////////////////////////////////////////

	/**
	 * Get source indicatir
	 * 
	 * @see Pronamic_IDeal_IDealDataProxy::getSource()
	 * @return string
	 */
	public function getSource() {
		return 'classipress';
	}

	//////////////////////////////////////////////////

	/**
	 * Get description
	 * 
	 * @see Pronamic_IDeal_IDealDataProxy::getDescription()
	 * @return string
	 */
	public function getDescription() {
		return sprintf(__('Advertisement %s', 'pronamic_ideal'), $this->getOrderId());
	}

	/**
	 * Get order ID
	 * 
	 * @see Pronamic_IDeal_IDealDataProxy::getOrderId()
	 * @return string
	 */
	public function getOrderId() {
		return $this->orderValues['post_id'];
	}

	/**
	 * Get items
	 * 
	 * @see Pronamic_IDeal_IDealDataProxy::getItems()
	 * @return Pronamic_IDeal_Items
	 */
	public function getItems() {
		// Items
		$items = new Pronamic_IDeal_Items();

		// Item
		// We only add one total item, because iDEAL cant work with negative price items (discount)
		$item = new Pronamic_IDeal_Item();
		$item->setNumber($this->orderValues['item_number']);
		$item->setDescription($this->orderValues['item_name']);
		$item->setPrice($this->orderValues['item_amount']);
		$item->setQuantity(1);

		$items->addItem($item);

		return $items;
	}

	//////////////////////////////////////////////////
	// Currency
	//////////////////////////////////////////////////

	public function getCurrencyAlphabeticCode() {
		return get_option('cp_curr_pay_type');
	}

	//////////////////////////////////////////////////
	// Customer
	//////////////////////////////////////////////////

	public function getEMailAddress() {
		$userId = $this->orderValues['user_id'];
		
		return get_the_author_meta('user_email', $userId);
	}

	public function getCustomerName() {
		$userId = $this->orderValues['user_id'];
		
		return get_the_author_meta('first_name', $userId) . ' ' . get_the_author_meta('last_name', $userId);
	}

	public function getOwnerAddress() {
		return $this->orderValues['cp_street'];
	}

	public function getOwnerCity() {
		return $this->orderValues['cp_city'];
	}

	public function getOwnerZip() {
		return $this->orderValues['cp_zipcode'];
	}

	//////////////////////////////////////////////////
	// URL's
	//////////////////////////////////////////////////
	
	public function getNormalReturnUrl() {
		return $this->orderValues['return_url'];
	}
	
	public function getCancelUrl() {
		return $this->orderValues['notify_url'];
	}
	
	public function getSuccessUrl() {
		return $this->orderValues['return_url'];
	}

	public function getErrorUrl() {
		return $this->orderValues['notify_url'];
	}
}
