<?php

namespace Pronamic\WordPress\Pay\Payments;

use Pronamic\WordPress\Pay\CreditCard;

/**
 * Title: Payment data interface
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 4.4.2
 * @since 1.4.0
 */
interface PaymentDataInterface {
	/**
	 * Get the title of the payment
	 *
	 * @return string
	 */
	public function get_title();

	/**
	 * Get credit card object
	 *
	 * @return CreditCard
	 */
	public function get_credit_card();

	//////////////////////////////////////////////////
	// URL's
	//////////////////////////////////////////////////

	public function get_normal_return_url();

	public function get_cancel_url();

	public function get_success_url();

	public function get_error_url();

	//////////////////////////////////////////////////
	// Subscription
	//////////////////////////////////////////////////

	public function get_subscription();
}
