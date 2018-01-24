<?php

namespace Pronamic\WordPress\Pay;

use DateTime;

/**
 * Title: Credit card class
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @since 1.4.0
 */
class CreditCard {
	/**
	 * Credit card number
	 *
	 * @var string
	 */
	private $number;

	/**
	 * Credit card expiration month
	 *
	 * @var string
	 */
	private $expiration_month;

	/**
	 * Credit card expiration year
	 *
	 * @var string
	 */
	private $expiration_year;

	/**
	 * Credit card security code
	 *
	 * @var string
	 */
	private $security_code;

	/**
	 * Credit card holder name
	 *
	 * @var string
	 */
	private $name;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initializes an credit card object
	 */
	public function __construct() {

	}

	//////////////////////////////////////////////////

	/**
	 * Get credit card number
	 *
	 * @return string
	 */
	public function get_number() {
		return $this->number;
	}

	/**
	 * Set credit card number
	 *
	 * @param string $number
	 */
	public function set_number( $number ) {
		$this->number = $number;
	}

	//////////////////////////////////////////////////

	/**
	 * Get expiration month
	 *
	 * @return int
	 */
	public function get_expiration_month() {
		return $this->expiration_month;
	}

	/**
	 * Set expiration month
	 *
	 * @param int $month
	 */
	public function set_expiration_month( $month ) {
		$this->expiration_month = $month;
	}

	//////////////////////////////////////////////////

	/**
	 * Get expiration year
	 *
	 * @return int
	 */
	public function get_expiration_year() {
		return $this->expiration_year;
	}

	/**
	 * Set expiration year
	 *
	 * @param int $year
	 */
	public function set_expiration_year( $year ) {
		$this->expiration_year = $year;
	}

	//////////////////////////////////////////////////

	/**
	 * Get expiration date
	 *
	 * @return DateTime
	 */
	public function get_expiration_date() {
		return new DateTime( '@' . gmmktime( 0, 0, 0, $this->expiration_month, 1, $this->expiration_year ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Get security code
	 *
	 * @return int
	 */
	public function get_security_code() {
		return $this->security_code;
	}

	/**
	 * Set security code
	 *
	 * @param int $security_code
	 */
	public function set_security_code( $security_code ) {
		$this->security_code = $security_code;
	}

	//////////////////////////////////////////////////

	/**
	 * Get credit card holder name
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Set credit card holder name
	 *
	 * @param string $name
	 */
	public function set_name( $name ) {
		$this->name = $name;
	}
}
