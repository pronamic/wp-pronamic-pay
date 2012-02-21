<?php

class Pronamic_GravityForms_IDeal_IDealDataProxy extends Pronamic_WordPress_IDeal_IDealDataProxy {
	private $form;
	
	private $feed;

	private $lead;

	//////////////////////////////////////////////////

	public function __construct($form, $feed, $lead) {
		$this->form = $form;
		$this->feed = $feed;
		$this->lead = $lead;
	}

	//////////////////////////////////////////////////

	public function getSource() {
		return 'gravityformsideal';
	}

	//////////////////////////////////////////////////

	public function getDescription() {
		return GFCommon::replace_variables($this->feed->transactionDescription, $this->form, $this->lead);
	}

	public function getOrderId() {
		return $this->lead['id'];
	}

	public function getItems() {
		$items = new Pronamic_IDeal_Items();

		$number = 0;

		// Products
        $products = GFCommon::get_product_fields($this->form, $this->lead);

        foreach($products['products'] as $product) {
        	$description = $product['name'];
        	$price = GFCommon::to_number($product['price']);
        	$quantity = $product['quantity'];

        	$item = new Pronamic_IDeal_Item();
        	$item->setNumber($number++);
        	$item->setDescription($description);
        	$item->setPrice($price);
        	$item->setQuantity($quantity);

        	$items->addItem($item);

			if(isset($product['options']) && is_array($product['options'])) {
				foreach($product['options'] as $option) {
					$description = $option['option_label'];
					$price = GFCommon::to_number($option['price']);

		        	$item = new Pronamic_IDeal_Item();
		        	$item->setNumber($number++);
		        	$item->setDescription($description);
		        	$item->setPrice($price);
		        	$item->setQuantity($quantity); // Product quantity

        			$items->addItem($item);
				}
            }
        }

        // Shipping
        if(isset($products['shipping'])) {
        	$shipping = $products['shipping'];

        	if(isset($shipping['price']) && !empty($shipping['price'])) {
        		$description = $shipping['name'];
				$price = GFCommon::to_number($shipping['price']);
				$quantity = 1;

				$item = new Pronamic_IDeal_Item();
		        $item->setNumber($number++);
		        $item->setDescription($description);
		        $item->setPrice($price);
	        	$item->setQuantity($quantity);

        		$items->addItem($item);
        	}
        }
        
        // Donations
        $donationFields = GFCommon::get_fields_by_type($this->form, array('donation'));

		foreach($donationFields as $i => $field) {
			$value = RGFormsModel::get_lead_field_value($this->lead, $field);

			if(!empty($value)) {
				$description = '';
				if(isset($field['adminLabel']) && !empty($field['adminLabel'])) {
					$description = $field['adminLabel'];
				} elseif(isset($field['label'])) {
					$description = $field['label'];
				}
	
				$separatorPosition = strpos($value, '|');
				if($separatorPosition !== false) {
					$label = substr($value, 0, $separatorPosition);
					$value = substr($value, $separatorPosition + 1);
					
					$description .= ' - ' . $label;
				}
				
				$price = GFCommon::to_number($value);
				$quantity = 1;
	
				$item = new Pronamic_IDeal_Item();
				$item->setNumber($i);
				$item->setDescription($description);
				$item->setQuantity($quantity);
				$item->setPrice($price);
	
				$items->addItem($item);
			}
		}
		
		return $items;
	}

	//////////////////////////////////////////////////
	// Currency
	//////////////////////////////////////////////////

	public function getCurrencyAlphabeticCode() {
		return GFCommon::get_currency();
	}

	//////////////////////////////////////////////////
	// Customer
	//////////////////////////////////////////////////

	public function getEMailAddress() {
		
	}

	public function getCustomerName() {
		
	}

	public function getOwnerAddress() {
		
	}

	public function getOwnerCity() {
		
	}

	public function getOwnerZip() {
		
	}

	//////////////////////////////////////////////////
	// URL's
	//////////////////////////////////////////////////
	
	public function getNormalReturnUrl() {
		$url = $this->feed->getUrl(Pronamic_GravityForms_IDeal_Feed::LINK_SUCCESS);

        if($url != null) {
        	$url = add_query_arg('transaction', $this->getOrderId(), $url);
        	$url = add_query_arg('status', 'success', $url);
        }
        
        return $url;
	}
	
	public function getCancelUrl() {
		$url = $this->feed->getUrl(Pronamic_GravityForms_IDeal_Feed::LINK_CANCEL);

        if($url != null) {
        	$url = add_query_arg('transaction', $this->getOrderId(), $url);
        	$url = add_query_arg('status', 'cancel', $url);
        }
        
        return $url;
	}
	
	public function getSuccessUrl() {
		$url = $this->feed->getUrl(Pronamic_GravityForms_IDeal_Feed::LINK_CANCEL);

        if($url != null) {
        	$url = add_query_arg('transaction', $this->getOrderId(), $url);
        	$url = add_query_arg('status', 'success', $url);
        }
        
        return $url;
	}

	public function getErrorUrl() {
		$url = $this->feed->getUrl(Pronamic_GravityForms_IDeal_Feed::LINK_CANCEL);

        if($url != null) {
        	$url = add_query_arg('transaction', $this->getOrderId(), $url);
        	$url = add_query_arg('status', 'error', $url);
        }
        
        return $url;
	}
}
