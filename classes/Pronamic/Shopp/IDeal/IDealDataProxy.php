<?php

/**
 * Title: Shopp iDEAL data proxy
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Shopp_IDeal_IDealDataProxy extends Pronamic_WordPress_IDeal_IDealDataProxy {
	/**
	 * Purchase
	 * 
	 * @see /shopp/core/model/Purchase.php
	 * @var Purchase
	 */
	private $purchase;

	/**
	 * Gateway
	 * 
	 * @see /shopp/core/model/Gateway.php
	 * @var GatewayFramework
	 */
	private $gateway;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize an Shopp iDEAL data proxy
	 * 
	 * @param Purchase $purchase
	 * @param GatewayFramework $gateway
	 */
	public function __construct( $purchase, $gateway ) {
		$this->purchase = $purchase;
		$this->gateway = $gateway;
	}

	//////////////////////////////////////////////////

	/**
	 * Get source indicator
	 * 
	 * @see Pronamic_IDeal_IDealDataProxy::getSource()
	 * @return string
	 */
	public function getSource() {
		return 'shopp';
	}

	//////////////////////////////////////////////////

	/**
	 * Get description
	 * 
	 * @see Pronamic_IDeal_IDealDataProxy::getDescription()
	 * @return string
	 */
	public function getDescription() {
		return sprintf( __( 'Order %s', 'pronamic_ideal' ), $this->purchase->id );
	}

	/**
	 * Get order ID
	 * 
	 * @see Pronamic_IDeal_IDealDataProxy::getOrderId()
	 * @return string
	 */
	public function getOrderId() {
		return $this->purchase->id;
	}

	/**
	 * Get items
	 * 
	 * @see Pronamic_IDeal_IDealDataProxy::getItems()
	 * @return Pronamic_IDeal_Items
	 */
	public function getItems() {
		$items = new Pronamic_IDeal_Items();

		// Item
		// We only add one total item, because iDEAL cant work with negative price items (discount)
		$item = new Pronamic_IDeal_Item();
		$item->setNumber( $this->purchase->id );
		$item->setDescription( sprintf( __( 'Order %s', 'pronamic_ideal' ), $this->purchase->id ) );
		$item->setPrice( $this->purchase->total );
		$item->setQuantity( 1 );

		$items->addItem( $item );

		return $items;
	}

	//////////////////////////////////////////////////
	// Currency
	//////////////////////////////////////////////////

	public function getCurrencyAlphabeticCode() {
		// @see /shopp/core/model/Lookup.php#L58
		// @see /shopp/core/model/Gateway.php
		return $this->gateway->baseop['currency']['code'];
	}

	//////////////////////////////////////////////////
	// Customer
	//////////////////////////////////////////////////

	public function getEMailAddress() {
		// @see /shopp/core/model/Purchase.php
		return $this->purchase->email;
	}

	public function getCustomerName() {
		// @see /shopp/core/model/Purchase.php
		return $this->purchase->firstname . ' ' . $purchase->lastname;
	}

	public function getOwnerAddress() {
		// @see /shopp/core/model/Purchase.php
		return $this->purchase->address;
	}

	public function getOwnerCity() {
		// @see /shopp/core/model/Purchase.php
		return $this->purchase->city;
	}

	public function getOwnerZip() {
		// @see /shopp/core/model/Purchase.php
		return $this->purchase->postcode;
	}

	//////////////////////////////////////////////////
	// URL's
	//
	// shoppurl default pages: 
	// catalog, account, cart, checkout, confirm, thanks
	//////////////////////////////////////////////////
	
	public function getNormalReturnUrl() {
		// @see /shopp/core/functions.php#L1873
		// @see /shopp/core/flow/Storefront.php#L1364
		return shoppurl( array( 'messagetype' => 'open' ), 'thanks' );
	}
	
	public function getCancelUrl() {
		// @see /shopp/core/functions.php#L1873
		// @see /shopp/core/flow/Storefront.php#L1364
		return shoppurl( array( 'messagetype' => 'cancelled' ), 'thanks' );
	}
	
	public function getSuccessUrl() {
		// @see /shopp/core/functions.php#L1873
		// @see /shopp/core/flow/Storefront.php#L1364
		return shoppurl( false, 'thanks' );
	}
	
	public function getErrorUrl() {
		// @see /shopp/core/functions.php#L1873
		// @see /shopp/core/flow/Storefront.php#L1364
		return shoppurl( array( 'messagetype' => 'error' ), 'thanks' );
	}
}
