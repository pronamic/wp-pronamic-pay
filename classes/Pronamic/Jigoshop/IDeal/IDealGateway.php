<?php

/**
 * Title: Jigoshop iDEAL gateway
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Jigoshop_IDeal_IDealGateway extends jigoshop_payment_gateway {
	/**
	 * The unique ID of this payment gateway
	 * 
	 * @var string
	 */
	const ID = 'pronamic_ideal';

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize an iDEAL gateway
	 */
    public function __construct() { 
		$this->id = self::ID;
		$this->method_title = __('Pronamic iDEAL', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN);
		$this->icon = plugins_url('images/icon-24x24.png', Pronamic_WordPress_IDeal_Plugin::$file);
		$this->has_fields = false;
    } 

	//////////////////////////////////////////////////
    
	/**
	 * Admin Panel Options 
	 * - Options for bits like 'title' and availability on a country-by-country basis
	 *
	 * @since 1.0.0
	 */
	public function admin_options() {
    	?>
    	<thead>
    		<tr>
    			<th scope="col" width="200px">
    				<?php _e('Pronamic iDEAL', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
    			</th>
    			<th scope="col" class="desc">
    			
    			</th>
    		</tr>
    	</thead>
		<tr>
			<td class="titledesc"><?php _e('Enable iDEAL', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN) ?>:</td>
			<td class="forminp">
				<select name="jigoshop_paypal_enabled" id="jigoshop_paypal_enabled" style="min-width:100px;">
					<option value="yes" <?php if (get_option('jigoshop_paypal_enabled') == 'yes') echo 'selected="selected"'; ?>><?php _e('Yes', 'jigoshop'); ?></option>
					<option value="no" <?php if (get_option('jigoshop_paypal_enabled') == 'no') echo 'selected="selected"'; ?>><?php _e('No', 'jigoshop'); ?></option>
				</select>
	        </td>
	    </tr>
    	<?php
    }

	//////////////////////////////////////////////////

    /**
	 * There are no payment fields for bacs, but we want to show the description if set.
	 */
	function payment_fields() {
		
	}
}
