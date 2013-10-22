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
	 * Pronamic_Pay_Config
	 * 
	 * @var int
	 */
	protected $config;

	/////////////////////////////////////////////////

	/**
	 * The slug of this gateway
	 * 
	 * @var string
	 */
	private $slug;

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
	 * The transaction ID
	 * 
	 * @var string
	 */
	private $transaction_id;

	/////////////////////////////////////////////////

	/**
	 * Action URL
	 * 
	 * @var string
	 */
	private $action_url;

	/////////////////////////////////////////////////

	/**
	 * Error
	 * 
	 * @var WP_Error
	 */
	public $error;

	/////////////////////////////////////////////////

	/**
	 * Constructs and initializes an gateway
	 * 
	 * @param Pronamic_Pay_Config $config
	 */
	public function __construct( Pronamic_Pay_Config $config ) {
		$this->config = $config;
	}

	/////////////////////////////////////////////////

	/**
	 * Get the slug of this gateway
	 * 
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Set the slug of this gateway
	 * 
	 * @param unknown_type $slug
	 */
	public function set_slug( $slug ) {
		$this->slug = $slug;
	}

	/////////////////////////////////////////////////

	/**
	 * Get the error
	 * 
	 * @return WP_Error or null
	 */
	public function get_error() {
		return $this->error;
	}

	/**
	 * Has error
	 * 
	 * @return boolean
	 */
	public function has_error() {
		return $this->error != null;
	}

	/**
	 * Set error
	 * 
	 * @param WP_Error $error
	 */
	public function set_error( WP_Error $error = null ) {
		$this->error = $error;
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
	public function get_transient_issuers() {
		$issuers = null;

		$transient = 'pronamic_ideal_issuers_' . spl_object_hash( $this->config );

		$result = get_transient( $transient );
		// $result = false;

		if ( is_wp_error( $result ) || $result === false ) {
			$issuers = $this->get_issuers();

			if ( $issuers ) {
				// 60 * 60 * 24 = 24 hours = 1 day
				set_transient( $transient, $issuers, 60 * 60 * 24 );
			}
		} elseif ( is_array( $result ) ) {
			$issuers = $result;
		}

		return $issuers;
	}

	/////////////////////////////////////////////////

	/**
	 * Start transaction/payment
	 * 
	 * @param Pronamic_Pay_PaymentDataInterface $data
	 */
	public function start( Pronamic_Pay_PaymentDataInterface $data ) {
		
	}
	
	/////////////////////////////////////////////////

	/**
	 * Handle payment
	 * 
	 * @param Pronamic_Pay_Payment $payment
	 */
	public function payment( Pronamic_Pay_Payment $payment ) {
		
	}
	
	/////////////////////////////////////////////////

	/**
	 * Redirect to the gateway action URL
	 */
	public function redirect( Pronamic_Pay_Payment $payment ) {
		switch ( $this->method ) {
			case self::METHOD_HTTP_REDIRECT:
				return $this->redirect_via_http( $payment );
			case self::METHOD_HTML_FORM:
				return $this->redirect_via_html( $payment);
			default:
				// No idea how to redirect to the gateway
		}
	}

	public function redirect_via_http( Pronamic_Pay_Payment $payment ) {
		if ( headers_sent() ) {
			$this->redirect_via_html( $payment );
		} else {
			// Redirect, See Other
			// http://en.wikipedia.org/wiki/HTTP_303
			wp_redirect( $payment->get_action_url(), 303 );
		}

		exit;
	}

	public function redirect_via_html( Pronamic_Pay_Payment $payment ) {
		if ( headers_sent() ) {
			echo $this->get_form_html( $payment, true );
		} else {
			include Pronamic_WordPress_IDeal_Plugin::$dirname . '/views/redirect-via-html.php';
		}

		exit;
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
	
	/**
	 * Get the input fields for this gateway
	 * 
	 * This function will automatically add the issuer field to the 
	 * input fields array of it's not empty
	 * 
	 * @return array
	 */
	public function get_input_fields() {
		$fields = array();
		
		$issuer_field = $this->get_issuer_field();

		if ( ! empty( $issuer_field ) ) {
			$fields[] = $this->get_issuer_field();
		}

		return $fields;
	}
	
	/////////////////////////////////////////////////

	/**
	 * Get the input HTML
	 * 
	 * This function will convert all input fields to an HTML notation
	 * 
	 * @return string
	 */
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
							esc_attr( $field['id'] ),
							$field['label']
						);

						$html .= sprintf(
							'<select id="%s" name="%s">%s</select>',
							esc_attr( $field['id'] ),
							esc_attr( $field['name'] ),
							Pronamic_WP_HTML_Helper::select_options_grouped( $field['choices'] )
						);
						
						break;
				}
			}
		}
		
		return $html;
	}

	/**
	 * Get form HTML
	 * 
	 * @return string
	 */
	public function get_form_html( Pronamic_Pay_Payment $payment, $auto_submit = false ) {
		$html = '';

		// Form
		$form_inner  = '';
		$form_inner .= $this->get_output_html();
		$form_inner .= 	sprintf(
			'<input class="btn btn-primary" type="submit" name="pay" value="%s" />',
			__( 'Pay', 'pronamic_ideal' )
		);
		
		$form = sprintf(
			'<form id="pronamic_ideal_form" name="pronamic_ideal_form" method="post" action="%s">%s</form>',
			esc_attr( $payment->get_action_url() ),
			$form_inner
		);
		
		// HTML
		$html .= $form;

		if ( $auto_submit ) {
			$html .= '<script type="text/javascript">document.pronamic_ideal_form.submit();</script>';
		}

		return $html;
	}

	/////////////////////////////////////////////////
	// Output fields
	/////////////////////////////////////////////////

	/**
	 * Get the output HTML
	 * 
	 * @return string
	 */
	public function get_output_html() {
		return '';
	}
}
