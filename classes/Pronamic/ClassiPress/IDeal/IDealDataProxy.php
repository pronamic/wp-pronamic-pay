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
		return sprintf(__('Order %s', 'pronamic_ideal'), '???');
	}

	/**
	 * Get order ID
	 * 
	 * @see Pronamic_IDeal_IDealDataProxy::getOrderId()
	 * @return string
	 */
	public function getOrderId() {
		return '???';
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
		$item->setNumber('???');
		$item->setDescription(sprintf(__('Order %s', 'pronamic_ideal'), '???'));
		$item->setPrice($this->orderValues['item_amount']);
		$item->setQuantity(1);

		$items->addItem($item);

		return $items;
	}

	//////////////////////////////////////////////////
	// Currency
	//////////////////////////////////////////////////

	public function getCurrencyAlphabeticCode() {
		return 'EUR';
	}

	//////////////////////////////////////////////////
	// Customer
	//////////////////////////////////////////////////

	public function getEMailAddress() {
		
	}

	public function getCustomerName() {
		
	}

	public function getOwnerAddress() {
		
	}

	public function getOwnerCity() {
		
	}

	public function getOwnerZip() {
		
	}

	//////////////////////////////////////////////////
	// URL's
	//////////////////////////////////////////////////
	
	public function getNormalReturnUrl() {
		return $this->orderValues['return_url'];
	}
	
	public function getCancelUrl() {
		return $this->orderValues['return_url'];
	}
	
	public function getSuccessUrl() {
		return $this->orderValues['return_url'];
	}

	public function getErrorUrl() {
		return $this->orderValues['return_url'];
	}
}
