<?php

/**
 * Title: Gravity Forms iDEAL data proxy
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WP_Pay_GravityForms_PaymentData extends Pronamic_WP_Pay_PaymentData {
	/**
	 * Gravity Forms form object
	 * 
	 * @see http://www.gravityhelp.com/documentation/page/Form_Object
	 * @var array
	 */
	private $form;

	/**
	 * Gravity Forms entry object
	 * 
	 * @see http://www.gravityhelp.com/documentation/page/Entry_Object
	 * @var array
	 */
	private $lead;

	/**
	 * Pronamic iDEAL feed object
	 * 
	 * @var Pronamic_GravityForms_IDeal_Feed
	 */
	private $feed;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize an Gravity Forms iDEAL data proxy
	 * 
	 * @param array $form
	 * @param array $lead
	 * @param Pronamic_GravityForms_IDeal_Feed $feed
	 */
	public function __construct( $form, $lead, $feed ) {
		parent::__construct();

		$this->form = $form;
		$this->lead = $lead;
		$this->feed = $feed;
	}

	//////////////////////////////////////////////////

	private function get_field_value( $field_name ) {
		$value = null;

		if ( isset( $this->feed->fields[$field_name] ) ) {
			$field_id = $this->feed->fields[$field_name];
			
			if ( isset( $this->lead[$field_id] ) ) {
				$value = $this->lead[$field_id];
			}
		}
		
		return $value;
	}

	//////////////////////////////////////////////////

	/**
	 * Get source indicator
	 * 
	 * @see Pronamic_Pay_PaymentDataInterface::getSource()
	 * @return string
	 */
	public function getSource() {
		return 'gravityformsideal';
	}

	//////////////////////////////////////////////////

	/**
	 * Get description
	 * 
	 * @see Pronamic_Pay_PaymentDataInterface::get_description()
	 * @return string
	 */
	public function get_description() {
		$description = GFCommon::replace_variables( $this->feed->transaction_description, $this->form, $this->lead );

		return $description;
	}

	/**
	 * Get order ID
	 * 
	 * @see Pronamic_Pay_PaymentDataInterface::get_order_id()
	 * @return string
	 */
	public function get_order_id() {
		// @see http://www.gravityhelp.com/documentation/page/Entry_Object#Standard
		return $this->lead['id'];
	}

	/**
	 * Get items
	 * 
	 * @see Pronamic_Pay_PaymentDataInterface::getItems()
	 * @return Pronamic_IDeal_Items
	 */
	public function getItems() {
		$items = new Pronamic_IDeal_Items();

		$number = 0;

		// Products
        $products = GFCommon::get_product_fields( $this->form, $this->lead );

        foreach ( $products['products'] as $product ) {
        	$description = $product['name'];
        	$price = GFCommon::to_number( $product['price'] );
        	$quantity = $product['quantity'];

        	$item = new Pronamic_IDeal_Item();
        	$item->setNumber( $number++ );
        	$item->setDescription( $description );
        	$item->setPrice( $price );
        	$item->setQuantity( $quantity );

        	$items->addItem( $item );

			if ( isset( $product['options']) && is_array( $product['options'] ) ) {
				foreach( $product['options'] as $option ) {
					$description = $option['option_label'];
					$price = GFCommon::to_number( $option['price'] );

		        	$item = new Pronamic_IDeal_Item();
		        	$item->setNumber( $number++ );
		        	$item->setDescription( $description );
		        	$item->setPrice( $price );
		        	$item->setQuantity( $quantity ); // Product quantity

        			$items->addItem( $item );
				}
            }
        }

        // Shipping
        if ( isset( $products['shipping'] ) ) {
        	$shipping = $products['shipping'];

        	if ( isset( $shipping['price'] ) && ! empty( $shipping['price'] ) ) {
        		$description = $shipping['name'];
				$price = GFCommon::to_number( $shipping['price'] );
				$quantity = 1;

				$item = new Pronamic_IDeal_Item();
		        $item->setNumber( $number++ );
		        $item->setDescription( $description );
		        $item->setPrice( $price );
	        	$item->setQuantity( $quantity );

        		$items->addItem( $item );
        	}
        }
        
        // Donations
        $donation_fields = GFCommon::get_fields_by_type( $this->form, array( 'donation' ) );

		foreach ( $donation_fields as $i => $field ) {
			$value = RGFormsModel::get_lead_field_value( $this->lead, $field );

			if ( ! empty( $value ) ) {
				$description = '';
				if ( isset( $field['adminLabel'] ) && ! empty( $field['adminLabel'] ) ) {
					$description = $field['adminLabel'];
				} elseif ( isset( $field['label'] ) ) {
					$description = $field['label'];
				}
	
				$separator_position = strpos( $value, '|' );
				if ( $separator_position !== false ) {
					$label = substr( $value, 0, $separator_position );
					$value = substr( $value, $separator_position + 1 );
					
					$description .= ' - ' . $label;
				}
				
				$price = GFCommon::to_number( $value );
				$quantity = 1;
	
				$item = new Pronamic_IDeal_Item();
				$item->setNumber( $i );
				$item->setDescription( $description );
				$item->setQuantity( $quantity );
				$item->setPrice( $price );
	
				$items->addItem( $item );
			}
		}
		
		return $items;
	}

	//////////////////////////////////////////////////
	// Currency
	//////////////////////////////////////////////////

	/**
	 * Get currency alphabetic code
	 * 
	 * @see Pronamic_Pay_PaymentDataInterface::getCurrencyAlphabeticCode()
	 * @return string
	 */
	public function getCurrencyAlphabeticCode() {
		return GFCommon::get_currency();
	}

	//////////////////////////////////////////////////
	// Customer
	//////////////////////////////////////////////////

	public function get_email() {
		return $this->get_field_value( 'email' );
	}

	public function getCustomerName() {
		return $this->get_field_value( 'first_name' ) . ' ' . $this->get_field_value( 'last_name' );
	}

	public function getOwnerAddress() {
		return $this->get_field_value( 'address1' ) . ' ' . $this->get_field_value( 'address2' );
	}

	public function getOwnerCity() {
		return $this->get_field_value( 'city' );
	}

	public function getOwnerZip() {
		return $this->get_field_value( 'zip' );
	}

	//////////////////////////////////////////////////
	// URL's
	//////////////////////////////////////////////////
	
	public function get_normal_return_url() {
		$url = $this->feed->get_url( Pronamic_GravityForms_IDeal_Feed::LINK_OPEN );

		if ( empty( $url ) ) {
        	$url = parent::get_normal_return_url();
        }
        
        return $url;
	}
	
	public function get_cancel_url() {
		$url = $this->feed->get_url( Pronamic_GravityForms_IDeal_Feed::LINK_CANCEL );

        if ( empty( $url ) ) {
        	$url = parent::get_cancel_url();
        }
        
        return $url;
	}
	
	public function get_success_url() {
		$url = $this->feed->get_url( Pronamic_GravityForms_IDeal_Feed::LINK_SUCCESS );

		if ( empty( $url ) ) {
        	$url = parent::get_success_url();
        }
        
        return $url;
	}

	public function get_error_url() {
		$url = $this->feed->get_url( Pronamic_GravityForms_IDeal_Feed::LINK_ERROR );

		if ( empty( $url ) ) {
			$url = parent::get_error_url();
        }
        
        return $url;
	}

	//////////////////////////////////////////////////
	// Issuer
	//////////////////////////////////////////////////

	public function get_issuer_id() {
		$issuer_id = null;

		$issuer_fields = GFCommon::get_fields_by_type( $this->form, array( Pronamic_GravityForms_IDeal_IssuerDropDown::TYPE ) );
		$issuer_field = array_shift( $issuer_fields );

		if ( $issuer_field != null ) {
			$issuer_id =  RGFormsModel::get_field_value( $issuer_field );
		}
		
		return $issuer_id;
	}

	//////////////////////////////////////////////////
	// Creditcard
	//////////////////////////////////////////////////

	public function get_credit_card() {
		$credit_card = null;

		$credit_card_fields = GFCommon::get_fields_by_type( $this->form, array( 'creditcard' ) );
		
		$credit_card_field = array_shift( $credit_card_fields );
		
		if ( $credit_card_field ) {
			$credit_card = new Pronamic_Pay_CreditCard();

			// Number
			$variable_name = sprintf( 'input_%s_1', $credit_card_field['id'] );
			$number = filter_input( INPUT_POST, $variable_name, FILTER_SANITIZE_STRING );
			
			$credit_card->set_number( $number );

			// Expiration date
			$variable_name = sprintf( 'input_%s_2', $credit_card_field['id'] );
			$expiration_date = filter_input( INPUT_POST, $variable_name, FILTER_VALIDATE_INT, FILTER_FORCE_ARRAY );

			$month = array_shift( $expiration_date );
			$year  = array_shift( $expiration_date );
			
			$credit_card->set_expiration_month( $month );
			$credit_card->set_expiration_year( $year );
			
			// Security code
			$variable_name = sprintf( 'input_%s_3', $credit_card_field['id'] );
			$security_code = filter_input( INPUT_POST, $variable_name, FILTER_SANITIZE_STRING );
			
			$credit_card->set_security_code( $security_code );
			
			// Name
			$variable_name = sprintf( 'input_%s_5', $credit_card_field['id'] );
			$name = filter_input( INPUT_POST, $variable_name, FILTER_SANITIZE_STRING );
			
			$credit_card->set_name( $name );
		}
		
		return $credit_card;
	}
}
