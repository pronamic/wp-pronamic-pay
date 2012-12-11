<?php

abstract class Pronamic_Gateways_Gateway {
	const METHOD_HTML_FORM = 1;

	const METHOD_HTTP_REDIRECT = 2;

	/////////////////////////////////////////////////

	private $method;

	private $require_issue_select;

	private $amount_minimum;

	/////////////////////////////////////////////////

	/**
	 * Constructs and initializes an gateway
	 */
	public function __construct( ) {

	}

	/////////////////////////////////////////////////

	public function set_method( $method ) {
		$this->method = $method;
	}
	
	/////////////////////////////////////////////////

	public function set_amount_minimum( $amount ) {
		$this->amount_minimum = $amount;
	}
	
	/////////////////////////////////////////////////

	public function set_require_issue_select( $require ) {
		$this->require_issue_select = $require;
	}

	/////////////////////////////////////////////////
	
	public function get_issuers() {
		
	}
	
	/////////////////////////////////////////////////

	public function start( $data ) {
		
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
	
	
	public function get_fields() {
		return array();
	}

	public function get_html_fields() {
		$html = '';

		$fields = $this->get_fields();

		foreach( $fields as $field ) {
			if ( isset( $field['type'] ) ) {
				$type = $field['type'];

				switch ( $type ) {
					case 'select':
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
}
