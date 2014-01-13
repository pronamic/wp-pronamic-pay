<?php

/**
 * Title: MutliSafepay Connect transaction
 * Description: 
 * Copyright: Copyright (c) 2005 - 2013
 * Company: Pronamic
 * @author Remco Tolsma
 * @since 1.4.0
 */
class Pronamic_Pay_Gateways_MultiSafepay_Connect_Transaction {
	public $id;
	
	public $currency;
	
	public $amount;
	
	public $description;
	
	public $var1;
	
	public $var2;
	
	public $var3;
	
	public $items;
	
	public $manual;
	
	public $gateway;
	
	public $days_active;

	/////////////////////////////////////////////////

	public $payment_url;

	/////////////////////////////////////////////////

	/**
	 * Constructs and initialize an MultiSafepay Connect transaction object
	 */
	public function __construct() {
		
	}
}
