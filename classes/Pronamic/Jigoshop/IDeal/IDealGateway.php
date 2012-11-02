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
    	// Give this gateway an unique ID so Jigoshop can identiy this gateway
		$this->id              = self::ID;

		// The method title that Jigoshop will display in the admin
		$this->method_title    = __( 'Pronamic iDEAL', 'pronamic_ideal' );
		
		// The icon that Jigoshop will display on the payment methods radio list
		$this->icon            = plugins_url( 'images/icon-24x24.png', Pronamic_WordPress_IDeal_Plugin::$file );

		// Let Jigoshop know that this gateway has field
		// Technically only iDEAL advanced variants has fields
		$this->has_fields      = true;

		// Set default Jigoshop variables, load them form the WordPress options
		$this->enabled         = get_option( 'jigoshop_pronamic_ideal_enabled' );
		$this->title           = get_option( 'jigoshop_pronamic_ideal_title' );
		$this->description     = get_option( 'jigoshop_pronamic_ideal_description' );
		
		// Set own variables, load them form the WordPress options
		$this->configurationId = get_option( 'jigoshop_pronamic_ideal_configuration_id' );

		// Actions
		add_action( 'jigoshop_update_options', array( &$this, 'update_options' ) );
		
		add_action( 'receipt_' . self::ID, array( &$this, 'receipt_page' ) );

		// Add options
		add_option( 'jigoshop_pronamic_ideal_enabled', 'yes' );
		add_option( 'jigoshop_pronamic_ideal_title', __( 'iDEAL', 'pronamic_ideal' ) );
		add_option( 'jigoshop_pronamic_ideal_description', __( 'With iDEAL you can easily pay online in the secure environment of your own bank.', 'pronamic_ideal' ) );
		add_option( 'jigoshop_pronamic_ideal_configuration_id', '' );
    } 

	//////////////////////////////////////////////////
    
	/**
	 * Admin Panel Options 
	 * - Options for bits like 'title' and availability on a country-by-country basis
	 *
	 * @since 1.0.0
	 */
	public function admin_options() {
    	$configurations = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurations();
    	$configuration_options = array( '' => __( '&mdash; Select configuration &mdash;', 'pronamic_ideal' ) );
    	foreach($configurations as $configuration) {
    		$configuration_options[$configuration->getId()] = $configuration->getName();
    	}

    	?>
    	<thead>
    		<tr>
    			<th scope="col" colspan="2">
    				<h3 class="title">
    					<?php _e( 'Pronamic iDEAL', 'pronamic_ideal' ); ?>
    				</h3>
    				
    				<p>
    					<?php _e( 'Allow iDEAL payments.', 'pronamic_ideal' ); ?>
    				</p>
    			</th>
    		</tr>
    	</thead>
		<tr>
			<td class="titledesc">
				<?php _e( 'Enable iDEAL', 'pronamic_ideal' ); ?>:
			</td>
			<td class="forminp">
				<select name="jigoshop_pronamic_ideal_enabled" id="jigoshop_pronamic_ideal_enabled" style="min-width:100px;">
					<option value="yes" <?php selected( $this->enabled, 'yes' ); ?>><?php _e( 'Yes', 'pronamic_ideal' ); ?></option>
					<option value="no" <?php selected( $this->enabled, 'no' ); ?>><?php _e( 'No', 'pronamic_ideal' ); ?></option>
				</select>
	        </td>
	    </tr>
	    <tr>
	        <td class="titledesc"><a href="#" tip="<?php _e( 'This controls the title which the user sees during checkout.', 'pronamic_ideal' ) ?>" class="tips" tabindex="99"></a><?php _e( 'Title', 'pronamic_ideal' ); ?>:</td>
	        <td class="forminp">
		        <input class="input-text" type="text" name="jigoshop_pronamic_ideal_title" id="jigoshop_pronamic_ideal_title" style="min-width:50px;" value="<?php if ( $value = get_option( 'jigoshop_pronamic_ideal_title' ) ) echo $value; else _e( 'iDEAL', 'pronamic_ideal' ); ?>" />
	        </td>
	    </tr>
	    <tr>
	        <td class="titledesc"><a href="#" tip="<?php _e( 'This controls the description which the user sees during checkout.', 'pronamic_ideal' ) ?>" class="tips" tabindex="99"></a><?php _e( 'Description', 'pronamic_ideal' ) ?>:</td>
	        <td class="forminp">
		        <input class="input-text wide-input" type="text" name="jigoshop_pronamic_ideal_description" id="jigoshop_pronamic_ideal_description" style="min-width:50px;" value="<?php if ( $value = get_option( 'jigoshop_pronamic_ideal_description' ) ) echo $value; ?>" />
	        </td>
	    </tr>
		<tr>
			<td class="titledesc">
				<?php _e( 'Configuration', 'pronamic_ideal' ); ?>:
			</td>
			<td class="forminp">
				<select name="jigoshop_pronamic_ideal_configuration_id" id="jigoshop_pronamic_ideal_configuration_id" style="min-width:100px;">
					<?php foreach ( $configuration_options as $id => $name ): ?>
						<option value="<?php echo $id; ?>" <?php selected( $this->configurationId, $id ); ?>><?php echo $name; ?></option>
					<?php endforeach; ?>
				</select>
	        </td>
	    </tr>
    	<?php
    }

	//////////////////////////////////////////////////

    /**
     * Process admin options
     */
    public function update_options() {
    	if ( isset( $_POST['jigoshop_pronamic_ideal_enabled'] ) )          update_option( 'jigoshop_pronamic_ideal_enabled', jigowatt_clean( $_POST['jigoshop_pronamic_ideal_enabled'] ) ); else @delete_option( 'jigoshop_pronamic_ideal_enabled' );
   		if ( isset( $_POST['jigoshop_pronamic_ideal_title'] ) )            update_option( 'jigoshop_pronamic_ideal_title', jigowatt_clean( $_POST['jigoshop_pronamic_ideal_title'] ) ); else @delete_option( 'jigoshop_pronamic_ideal_title' );
   		if ( isset( $_POST['jigoshop_pronamic_ideal_description'] ) )      update_option( 'jigoshop_pronamic_ideal_description', jigowatt_clean( $_POST['jigoshop_pronamic_ideal_description'] ) ); else @delete_option( 'jigoshop_pronamic_ideal_description' );
    	if ( isset( $_POST['jigoshop_pronamic_ideal_configuration_id'] ) ) update_option( 'jigoshop_pronamic_ideal_configuration_id', jigowatt_clean( $_POST['jigoshop_pronamic_ideal_configuration_id'] ) ); else @delete_option( 'jigoshop_pronamic_ideal_configuration_id' );
    }

	//////////////////////////////////////////////////

    /**
	 * Payment fields
	 */
	function payment_fields() {
		if ( ! empty( $this->description ) ) {
			echo wpautop( wptexturize( $this->description ) );
		}

		$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $this->configurationId );
		if ( $configuration !== null ) {
			$variant = $configuration->getVariant();

			if ( $variant !== null && $variant->getMethod() == Pronamic_IDeal_IDeal::METHOD_ADVANCED ) {
				$lists = Pronamic_WordPress_IDeal_IDeal::getTransientIssuersLists( $configuration );
				
				if ( $lists ) {
					?>
					<p class="pronamic_ideal_issuer">
						<label for="pronamic_ideal_issuer_id">
							<?php _e( 'Choose your bank', 'pronamic_ideal' ); ?>
						</label>
						
						<?php echo Pronamic_IDeal_HTML_Helper::issuersSelect( 'pronamic_ideal_issuer_id', $lists ); ?>
					</p>
					<?php 
				} elseif ( $error = Pronamic_WordPress_IDeal_IDeal::getError() ) {
					?>
					<div class="jigoshop_error">
						<?php echo $error->getConsumerMessage(); ?>
					</div>
					<?php
				} else {
					?>
					<div class="jigoshop_error">
						<?php echo __( 'Paying with iDEAL is not possible. Please try again later or pay another way.', 'pronamic_ideal' ); ?>
					</div>
					<?php 
				}
			}
		}
	}

	//////////////////////////////////////////////////
	
	/**
	 * Receipt page
	 */
	function receipt_page( $order_id ) {
		$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $this->configurationId );
		
		$order = &new jigoshop_order( $order_id );
		
		$data_proxy = new Pronamic_Jigoshop_IDeal_IDealDataProxy( $order );

		$html = Pronamic_WordPress_IDeal_IDeal::getHtmlForm( $data_proxy, $configuration );
		
		echo $html;
	}

	//////////////////////////////////////////////////
	
	/**
	 * Process the payment and return the result
	 */
	function process_payment( $order_id ) {
		$order = &new jigoshop_order( $order_id );

		// Mark as on-hold (we're awaiting the payment)
		$order->update_status( 'pending', __( 'Pending iDEAL payment.', 'pronamic_ideal' ) );

		// Empty cart
		jigoshop_cart::empty_cart();

		// Do specifiek iDEAL variant processing
		$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $this->configurationId );
		if ( $configuration !== null ) {
			$variant = $configuration->getVariant();
	
			if ( $variant !== null ) {
				switch ( $variant->getMethod() ) {
					case Pronamic_IDeal_IDeal::METHOD_ADVANCED:
						return $this->process_ideal_advanced_payment( $order, $configuration, $variant );
					default: 
						return $this->process_ideal_payment( $order, $configuration, $variant );
				}
			}
		}
    }

	private function process_ideal_payment( $order, $configuration, $variant ) {
		// Return pay page redirect
		return array(
			'result' 	=> 'success',
			'redirect'	=> add_query_arg(
				array(
					'order' => $order->id, 
					'key'   => $order->order_key
				), 
				get_permalink( jigoshop_get_page_id('pay') ) 
			)
		);
	}

    private function process_ideal_advanced_payment( $order, $configuration, $variant ) {
		$data_proxy = new Pronamic_Jigoshop_IDeal_IDealDataProxy( $order );

		$issuer_id = filter_input( INPUT_POST, 'pronamic_ideal_issuer_id', FILTER_SANITIZE_STRING );

		$url = Pronamic_WordPress_IDeal_IDeal::process_ideal_advanced( $configuration, $data_proxy, $issuer_id );

		return array(
			'result' 	=> 'success',
			'redirect'	=> $url
		);
    }
}
