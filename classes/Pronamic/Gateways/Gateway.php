<?php

/**
 * Title: Gateway
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
abstract class Pronamic_Gateways_Gateway {
	/**
	 * Method indicator for an gateway wich works through an HTML form
	 *
	 * @var int
	 */
	const METHOD_HTML_FORM = 1;

	/**
	 * Method indicator for an gateway wich works through an HTTP redirect
	 * 
	 * @var int
	 */
	const METHOD_HTTP_REDIRECT = 2;

	/////////////////////////////////////////////////

	/**
	 * Configuration
	 * 
	 * @var Pronamic_WordPress_IDeal_Configuration
	 */
	private $configuration;

	/////////////////////////////////////////////////

	/**
	 * The method of this gateway
	 * 
	 * @var int
	 */
	private $method;
	
	/**
	 * Indiactor if this gateway supports feedback 
	 * 
	 * @var boolean
	 */
	private $has_feedback;

	/**
	 * The mimimum amount this gateway can handle
	 * 
	 * @var float
	 */
	private $amount_minimum;

	/////////////////////////////////////////////////

	/**
	 * Constructs and initializes an gateway
	 * 
	 * @param Pronamic_WordPress_IDeal_Configuration $configuration
	 */
	public function __construct( Pronamic_WordPress_IDeal_Configuration $configuration ) {
		$this->configuration = $configuration;
	}

	/////////////////////////////////////////////////

	/**
	 * Set the method
	 * 
	 * @param int $method
	 */
	public function set_method( $method ) {
		$this->method = $method;
	}
	
	/////////////////////////////////////////////////

	/**
	 * Check if this gateway works trhough an HTTP redirect
	 * 
	 * @return boolean true if an HTTP redirect is required, false otherwise
	 */
	public function is_http_redirect() {
		return $this->method == self::METHOD_HTTP_REDIRECT;
	}

	/**
	 * Check if this gateway works through an HTML form
	 * 
	 * @return boolean true if an HTML form is required, false otherwise
	 */
	public function is_html_form() {
		return $this->method == self::METHOD_HTML_FORM;
	}

	/////////////////////////////////////////////////

	/**
	 * Check if this gateway supports feedback
	 * 
	 * @return boolean true if gateway supports feedback, false otherwise
	 */
	public function has_feedback() {
		return $this->has_feedback;
	}

	/**
	 * Set has feedback
	 * 
	 * @param boolean $has_feedback
	 */
	public function set_has_feedback( $has_feedback ) {
		$this->has_feedback = $has_feedback;
	}
	
	/////////////////////////////////////////////////

	/**
	 * Set the minimum amount required
	 * 
	 * @param float $amount
	 */
	public function set_amount_minimum( $amount ) {
		$this->amount_minimum = $amount;
	}

	/////////////////////////////////////////////////

	/**
	 * Get issuers
	 * 
	 * @return mixed an array or null
	 */
	public function get_issuers() {
		return null;
	}

	/**
	 * Get the issuers transient
	 */
	public static function get_transient_issuers() {
		$issuers = null;

		$transient = 'pronamic_ideal_issuers_' . $this->configuration->getId();

		$result = get_transient( $transient );

		if ( is_wp_error( $result ) ) {
			$this->error = $result;
		} elseif ( $result === false ) {
			$issuers = $this->get_issuers();
		
			if ( $issuers ) {
				// 60 * 60 * 24 = 24 hours = 1 day
				set_transient( $transient, $issuers, 60 * 60 * 24 );
			} elseif ( $this->error ) {
				// 60 * 30 = 30 minutes
				set_transient( $transient, $error, 60 * 30 * 1 );
			}
		} elseif ( is_array( $result ) ) {
			$issuers = $result;
		}

		return $issuers;
	}
	
	/////////////////////////////////////////////////

	public function start( $data ) {
		
	}
	
	/////////////////////////////////////////////////

	public function payment( $payment ) {
		
	}
	
	/////////////////////////////////////////////////

	public function redirect() {
		// Redirect, See Other
		// http://en.wikipedia.org/wiki/HTTP_303
		wp_redirect( $this->get_action_url(), 303 );

		exit;
	}
	
	/////////////////////////////////////////////////
	
	public function get_action_url() {
		return $this->action_url;
	}

	/////////////////////////////////////////////////
	// Input fields
	/////////////////////////////////////////////////
	
	/**
	 * Get an isser field
	 * 
	 * @return mixed an array or null
	 */
	public function get_issuer_field() {
		return null;
	}
	
	/////////////////////////////////////////////////
	
	public function get_input_fields() {
		return array();
	}

	public function get_input_html() {
		$html = '';

		$fields = $this->get_input_fields();

		foreach( $fields as $field ) {
			if ( isset( $field['type'] ) ) {
				$type = $field['type'];

				switch ( $type ) {
					case 'select':
						$html .= sprintf(
							'<label for="%s">%s</label> ',
							$field['id'],
							$field['label']
						);

						$html .= sprintf(
							'<select id="%s" name="%s">%s</select>',
							$field['id'],
							$field['name'],
							Pronamic_IDeal_HTML_Helper::select_options_grouped( $field['choices'] )
						);
						
						break;
				}
			}
		}
		
		return $html;
	}

	public function get_form_html() {
		$html  = '';

		$html .= sprintf('<form id="pronamic_ideal_form" name="pronamic_ideal_form" method="post" action="%s">', esc_attr( $this->get_action_url() ) );
		$html .= 	$this->get_output_html();
		$html .= 	sprintf('<input class="ideal-button" type="submit" name="ideal" value="%s" />', __('Pay with iDEAL', 'pronamic_ideal'));
		$html .= '</form>';

		return $html;
	}

	/////////////////////////////////////////////////
	// Output fields
	/////////////////////////////////////////////////

	public function get_output_html() {
		
	}
}
