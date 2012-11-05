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
	
	public function get_action_url() {
		
	}
	
	/////////////////////////////////////////////////

	public function get_html_fields() {
		
	}
	
	/////////////////////////////////////////////////

	public function get_redirect_url() {
		
	}
}
