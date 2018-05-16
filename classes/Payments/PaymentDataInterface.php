<?php
/**
 * Payment Data Interfase
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Payments;

use Pronamic\WordPress\Pay\CreditCard;
use Pronamic\WordPress\Pay\Subscriptions\Subscription;

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
	 * Get the title of the payment.
	 *
	 * @return string
	 */
	public function get_title();

	/**
	 * Get credit card object.
	 *
	 * @return CreditCard
	 */
	public function get_credit_card();

	/**
	 * Get normal return URL.
	 *
	 * @return string
	 */
	public function get_normal_return_url();

	/**
	 * Get cancel URL.
	 *
	 * @return string
	 */
	public function get_cancel_url();

	/**
	 * Get success URL.
	 *
	 * @return string
	 */
	public function get_success_url();

	/**
	 * Get error URL.
	 *
	 * @return string
	 */
	public function get_error_url();

	/**
	 * Get subscription.
	 *
	 * @return Subscription
	 */
	public function get_subscription();
}
