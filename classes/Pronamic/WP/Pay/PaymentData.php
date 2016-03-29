<?php

/**
 * Title: WordPress payment data
 * Description:
 * Copyright: Copyright (c) 2005 - 2016
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 1.0
 */
abstract class Pronamic_WP_Pay_PaymentData extends Pronamic_Pay_AbstractPaymentData {
	/**
	 * The current user
	 *
	 * @var WP_User
	 */
	private $user;

	//////////////////////////////////////////////////

	/**
	 * Constructs and intializes an WordPress iDEAL data proxy
	 */
	public function __construct() {
		parent::__construct();

		$this->user = wp_get_current_user();
	}

	//////////////////////////////////////////////////

	/**
	 * Get the ISO 639 language code
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_language()
	 * @return string
	 */
	public function get_language() {
		$locale = get_locale();

		return substr( $locale, 0, 2 );
	}

	/**
	 * Get the language ISO 639 and ISO 3166 country code
	 *
	 * @see Pronamic_Pay_PaymentDataInterface::get_language_and_country()
	 * @return string
	 */
	public function get_language_and_country() {
		return get_locale();
	}

	//////////////////////////////////////////////////
	// Customer
	//////////////////////////////////////////////////

	public function get_email() {
		$email = null;

		if ( is_user_logged_in() ) {
			$email = $this->user->user_email;
		}

		return $email;
	}

	public function get_customer_name() {
		$name = null;

		if ( is_user_logged_in() ) {
			$name = $this->user->user_firstname . ' ' . $this->user->user_lastname;
		}

		return $name;
	}

	//////////////////////////////////////////////////
	// URL's
	//////////////////////////////////////////////////

	private function get_url( $name ) {
		$url = home_url( '/' );

		$permalink = get_permalink( pronamic_pay_get_page_id( $name ) );

		if ( $permalink ) {
			$url = $permalink;
		}

		return $url;
	}

	//////////////////////////////////////////////////

	public function get_normal_return_url() {
		return $this->get_url( 'unknown' );
	}

	public function get_cancel_url() {
		return $this->get_url( 'cancel' );
	}

	public function get_success_url() {
		return $this->get_url( 'completed' );
	}

	public function get_error_url() {
		return $this->get_url( 'error' );
	}

	//////////////////////////////////////////////////
	// WordPres
	//////////////////////////////////////////////////

	public function get_blogname() {
		$blogname = get_option( 'blogname' );

		// @see https://github.com/WordPress/WordPress/blob/3.8.1/wp-includes/pluggable.php#L1085
		// The blogname option is escaped with esc_html on the way into the database in sanitize_option
		// we want to reverse this for the gateways.
		$blogname = wp_specialchars_decode( $blogname, ENT_QUOTES );

		return $blogname;
	}
}
