<?php

/**
 * Title: Event Espresso iDEAL data proxy
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_EventEspresso_IDeal_IDealDataProxy extends Pronamic_WordPress_IDeal_IDealDataProxy {
	/**
	 * Data
	 * 
	 * @var array
	 */
	private $data;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an Event Espresso iDEAL data proxy
	 * 
	 * @param array $data
	 */
	public function __construct($data) {
		$data = apply_filters('filter_hook_espresso_prepare_payment_data_for_gateways', $data);
		$data = apply_filters('filter_hook_espresso_get_total_cost', $data);
echo '<pre>';
var_dump($data);
echo '</pre>';
		$this->data = $data;
	}

	//////////////////////////////////////////////////

	/**
	 * Get source indicator
	 * 
	 * @see Pronamic_IDeal_IDealDataProxy::getSource()
	 * @return string
	 */
	public function getSource() {
		return 'event-espresso';
	}

	//////////////////////////////////////////////////

	/**
	 * Get description
	 * 
	 * @see Pronamic_IDeal_IDealDataProxy::getDescription()
	 * @return string
	 */
	public function getDescription() {
		return sprintf(__('Attendee %s', 'pronamic_ideal'), $this->data['attendee_id']);
	}

	/**
	 * Get order ID
	 * 
	 * @see Pronamic_IDeal_IDealDataProxy::getOrderId()
	 * @return string
	 */
	public function getOrderId() {
		return $this->data['attendee_id'];
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
		$item->setNumber($this->data['attendee_id']);
		$item->setDescription(sprintf(__('Attendee %s', 'pronamic_ideal'), $this->data['attendee_id']));
		$item->setPrice($this->data['total_cost']);
		$item->setQuantity(1);

		$items->addItem($item);

		return $items;
	}

	//////////////////////////////////////////////////
	// Currency
	//////////////////////////////////////////////////

	/**
	 * Get currency
	 * 
	 * @see Pronamic_IDeal_IDealDataProxy::getCurrencyAlphabeticCode()
	 * @return string
	 */
	public function getCurrencyAlphabeticCode() {
		return 'EUR';
	}

	//////////////////////////////////////////////////
	// Customer
	//////////////////////////////////////////////////

	public function getEMailAddress() {
		return $this->data['attendee_email'];
	}

	public function getCustomerName() {
		return $this->data['fname'] . ' ' . $this->data['lname'];
	}

	public function getOwnerAddress() {
		return $this->data['address'];
	}

	public function getOwnerCity() {
		return $this->data['city'];
	}

	public function getOwnerZip() {
		return $this->data['zip'];
	}

	//////////////////////////////////////////////////
	// URL's
	//////////////////////////////////////////////////

	private function getNotifyUrl() {
		global $org_options;

		return add_query_arg(
			array(
				'id' => $this->data['attendee_id'] ,
				'registration_id' => $this->data['registration_id'] , 
				'event_id' => $this->data['event_id']
			) , 
			get_permalink($org_options['notify_url'])
		);
	}

	private function getReturnUrl() {
		global $org_options;

		return add_query_arg(
			array(
				'id' => $this->data['attendee_id'] ,
				'registration_id' => $this->data['registration_id'] , 
				'event_id' => $this->data['event_id']
			) , 
			get_permalink($org_options['return_url'])
		);
	}

	private function getCancelReturn() {
		global $org_options;

		return get_permalink($org_options['cancel_return']);
	}

	//////////////////////////////////////////////////

	public function getNormalReturnUrl() {
		return $this->getReturnUrl();
	}
	
	public function getCancelUrl() {
		return $this->getCancelReturn();
	}
	
	public function getSuccessUrl() {
		return $this->getReturnUrl();
	}
	
	public function getErrorUrl() {
		return $this->getReturnUrl();
	}
}
