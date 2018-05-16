<?php
/**
 * Abstract Payment Data
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Payments;

use Pronamic\WordPress\Money\Money;
use Pronamic\WordPress\Pay\CreditCard;
use Pronamic\WordPress\Pay\Core\PaymentMethods;

/**
 * Abstract payment data class
 *
 * @author Remco Tolsma
 * @since 1.4.0
 */
abstract class AbstractPaymentData implements PaymentDataInterface {
	/**
	 * Entrance code.
	 *
	 * @todo Is this used?
	 * @var string
	 */
	private $entrance_code;

	/**
	 * Recurring.
	 *
	 * @todo Is this used?
	 * @var TODO
	 */
	protected $recurring;

	/**
	 * Construct and initialize abstract payment data object.
	 */
	public function __construct() {
		$this->entrance_code = uniqid();
	}

	/**
	 * Get user ID.
	 *
	 * @return string
	 */
	public function get_user_id() {
		return get_current_user_id();
	}

	/**
	 * Get source.
	 *
	 * @return string
	 */
	abstract public function get_source();

	/**
	 * Get source ID.
	 *
	 * @return string
	 */
	public function get_source_id() {
		return $this->get_order_id();
	}

	/**
	 * Get title.
	 *
	 * @return string
	 */
	public function get_title() {
		return $this->get_description();
	}

	/**
	 * Get description.
	 *
	 * @return string
	 */
	abstract public function get_description();

	/**
	 * Get order ID.
	 *
	 * @return string
	 */
	abstract public function get_order_id();

	/**
	 * Get items.
	 *
	 * @return array
	 */
	abstract public function get_items();

	/**
	 * Get amount.
	 *
	 * @return Money
	 */
	public function get_amount() {
		return new Money(
			$this->get_items()->get_amount()->get_amount(),
			$this->get_currency_alphabetic_code()
		);
	}

	/**
	 * Get email.
	 *
	 * @return null
	 */
	public function get_email() {
		return null;
	}

	/**
	 * Get customer name.
	 *
	 * @deprecated deprecated since version 4.0.1, use get_customer_name() instead.
	 */
	public function getCustomerName() {
		return $this->get_customer_name();
	}

	/**
	 * Get customer name.
	 *
	 * @return null
	 */
	public function get_customer_name() {
		return null;
	}

	/**
	 * Get owner address.
	 *
	 * @deprecated deprecated since version 4.0.1, use get_address() instead.
	 */
	public function getOwnerAddress() {
		return $this->get_address();
	}

	/**
	 * Get address.
	 *
	 * @return null
	 */
	public function get_address() {
		return null;
	}

	/**
	 * Get owner city.
	 *
	 * @deprecated deprecated since version 4.0.1, use get_city() instead.
	 */
	public function getOwnerCity() {
		return $this->get_city();
	}

	/**
	 * Get city.
	 *
	 * @return null
	 */
	public function get_city() {
		return null;
	}

	/**
	 * Get owner zip.
	 *
	 * @deprecated deprecated since version 4.0.1, use get_zip() instead.
	 */
	public function getOwnerZip() {
		return $this->get_zip();
	}

	/**
	 * Get ZIP.
	 *
	 * @return null
	 */
	public function get_zip() {
		return null;
	}

	/**
	 * Get country.
	 *
	 * @return null
	 */
	public function get_country() {
		return null;
	}

	/**
	 * Get telephone number.
	 *
	 * @return null
	 */
	public function get_telephone_number() {
		return null;
	}

	/**
	 * Get the curreny alphabetic code.
	 *
	 * @return string
	 */
	abstract public function get_currency_alphabetic_code();

	/**
	 * Get currency numeric code.
	 *
	 * @return string|null
	 */
	public function get_currency_numeric_code() {
		return $this->get_amount();
	}

	/**
	 * Helper function to get the curreny alphabetic code.
	 *
	 * @return string
	 */
	public function get_currency() {
		return $this->get_amount()->get_currency()->get_alphabetic_code();
	}

	/**
	 * Get the language code (ISO639).
	 *
	 * @see http://www.w3.org/WAI/ER/IG/ert/iso639.htm
	 *
	 * @return string
	 */
	abstract public function get_language();

	/**
	 * Get the language (ISO639) and country (ISO3166) code.
	 *
	 * @see http://www.w3.org/WAI/ER/IG/ert/iso639.htm
	 * @see http://www.iso.org/iso/home/standards/country_codes.htm
	 *
	 * @return string
	 */
	abstract public function get_language_and_country();

	/**
	 * Get entrance code.
	 *
	 * @return string
	 */
	public function get_entrance_code() {
		return $this->entrance_code;
	}

	/**
	 * Get issuer of the specified payment method.
	 *
	 * @todo Constant?
	 * @param string $payment_method Payment method identifier.
	 * @return string
	 */
	public function get_issuer( $payment_method = null ) {
		if ( PaymentMethods::CREDIT_CARD === $payment_method ) {
			return $this->get_credit_card_issuer_id();
		}

		return $this->get_issuer_id();
	}

	/**
	 * Get issuer ID.
	 *
	 * @return string
	 */
	public function get_issuer_id() {
		return filter_input( INPUT_POST, 'pronamic_ideal_issuer_id', FILTER_SANITIZE_STRING );
	}

	/**
	 * Get credit card issuer ID.
	 *
	 * @return string
	 */
	public function get_credit_card_issuer_id() {
		return filter_input( INPUT_POST, 'pronamic_credit_card_issuer_id', FILTER_SANITIZE_STRING );
	}

	/**
	 * Get credit card object.
	 *
	 * @return CreditCard
	 */
	public function get_credit_card() {
		return null;
	}

	/**
	 * Subscription.
	 *
	 * @return false|\Pronamic\WordPress\Pay\Subscriptions\Subscription
	 */
	public function get_subscription() {
		return false;
	}

	/**
	 * Subscription ID.
	 *
	 * @return int
	 */
	abstract public function get_subscription_id();

	/**
	 * Is this a recurring (not first) payment?
	 *
	 * @return boolean
	 */
	public function get_recurring() {
		return $this->recurring;
	}

	/**
	 * Set recurring.
	 *
	 * @param boolean $recurring Boolean flag which indicates recurring.
	 */
	public function set_recurring( $recurring ) {
		$this->recurring = $recurring;
	}
}
