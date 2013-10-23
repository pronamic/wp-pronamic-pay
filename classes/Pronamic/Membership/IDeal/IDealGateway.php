<?php

/**
 * Title: Membership iDEAL gateway
 * Copyright: Pronamic (c) 2005 - 2013
 * Company: Pronamic
 * @see http://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/gateways/gateway.freesubscriptions.php
 * @author Leon Rowland <leon@rowland.nl>
 * @version 1.0
 */
class Pronamic_Membership_IDeal_IDealGateway extends M_Gateway {
	/**
	 * Gateway name/slug
	 * 
	 * @see http://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/classes/class.gateway.php#L10
	 * @var string
	 */
	public $gateway = 'ideal';

	/**
	 * Gateway title
	 * 
	 * @see http://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/classes/class.gateway.php#L11
	 * @var string
	 */
	public $title = 'iDEAL';
	
	//////////////////////////////////////////////////

	/**
	 * Constructs and initliaze an Membership iDEAL gateway
	 */
	public function __construct() {
		parent::M_Gateway();

		// @see http://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/gateways/gateway.freesubscriptions.php#L30
		// @see http://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/classes/class.gateway.php#L97
		if ( $this->is_active() ) {
			add_action( 'init', array( $this, 'handle_real_form' ) );
			add_action( 'init', array( $this, 'redirect_http' ) );
			
			// @see http://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/includes/payment.form.php#L78
			add_action( 'membership_purchase_button', array( $this, 'purchase_button' ), 1, 3 );
		}
	}
	
	//////////////////////////////////////////////////

	/**
	 * Purchase button
	 * 
	 * @see http://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/includes/payment.form.php#L78
	 * 
	 * @param M_Subscription $subscription
	 *     @see http://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/classes/class.subscription.php
	 *     
	 * @param array $pricing
	 *     @see http://plugins.trac.wordpress.org/browser/membership/tags/3.4.4.1/membershipincludes/classes/class.subscription.php#L110
	 *     
	 *     array(
	 *         array(
	 *             'period' => '1',
	 *             'amount' => '50.00',
	 *             'type'   => 'indefinite',
	 *             'unit'   => 'm'
	 *         )
	 *     )
	 *     
	 * @param int $user_id WordPress user/member ID
	 */
	public function purchase_button( $subscription, $pricing, $user_id ) {
		if ( Pronamic_Membership_Membership::is_pricing_free( $pricing ) ) {
			// @todo what todo?
		} else {
			$membership = new M_Membership( $user_id );

			$config_id = get_option( Pronamic_Membership_IDeal_AddOn::OPTION_CONFIG_ID );

			$data = new Pronamic_WP_Pay_Membership_PaymentData( $subscription, $membership );

			printf( '<form method="post" action="">' );
			
			echo Pronamic_IDeal_IDeal::htmlHiddenFields( array(
				'pronamic_pay' => 'true',
				'config_id'    => $config_id
			) );

			echo Pronamic_IDeal_IDeal::htmlHiddenFields( Pronamic_WordPress_IDeal_Plugin::payment_data_to_array( $data ));
					
			printf(
				'<input type="image" border="0" alt="%s" src="%s" name="pronamic_pay" />',
				esc_attr__( 'iDEAL - Online betalen via uw eigen bank', 'pronamic_ideal' ),
				esc_attr( plugins_url( 'images/ideal-logo-pay-off-2-lines.png', Pronamic_WordPress_IDeal_Plugin::$file ) )
			);
			printf( '</form>' );
		}
	}
	
	//////////////////////////////////////////////////

	public function build_subscribe_button( $subscription, $pricing, $user_id, $sublevel = 1 ) {
		
		if ( ! empty( $pricing ) ) {
			$free = true;
			foreach ( $pricing as $key => $price ) {
				if ( ! empty( $price['amount'] ) && $price['amount'] > 0 ) {
					$free = false;
				}
			}

			if ( ! $free ) {
				if ( count ( $pricing ) == 1 ) {
					if ( in_array( $pricing[0]['type'], array( 'indefinite', 'finite' ) ) ) {
						return $this->single_sub_button( $pricing, $subscription, $user_id, true );
					} else {
						return $this->single_sub_button( $pricing, $subscription, $user_id );
					}
				} else {
					//return $this->complex_sub_button( $pricing, $subscription, $user_id );
				}
			} else {
				return $this->single_free_button( $pricing, $subscription, $user_id, true );
			}
		}


	}

	public function display_subscribe_button( $subscription, $pricing, $user_id, $sublevel = 1 ) {
		echo $this->build_subscribe_button( $subscription, $pricing, $user_id );
	}

	public function single_sub_button( $pricing, $subscription, $user_id, $norepeat = false ) {
		global $M_options;

		if ( empty( $M_options['paymentcurrency'] ) )
			$M_options['paymentcurrency'] = 'EUR';

		$registration_page_id = $M_options['registration_page'];
		
		ob_start();

		?>
		<form action="<?php echo add_query_arg( array( 'pronamic_ideal_membership_checkout' => 'true' ), get_permalink( $registration_page_id ) ); ?>" method="POST">
			<input type="hidden" name="pronamic_ideal_membership_checkout" value="<?php echo Pronamic_Membership_IDeal_Addon::encrypt_data( $subscription->sub_id(), $pricing, $user_id ); ?>" />
			<input type="hidden" name="subscription_id" value="<?php echo $subscription->sub_id(); ?>" />
			<input type="hidden" name="pricing" value='<?php echo serialize( $pricing ); ?>' />
			<input type="hidden" name="user_id" value="<?php echo $user_id; ?>"/>
			<input type="submit" value="<?php _e( 'Pay with iDEAL', 'pronamic_ideal' ); ?>" />
		</form>
		<?php

		ob_get_flush();
	}

	public function handle_real_form() {
		if ( ! isset( $_GET['pronamic_ideal_membership_checkout' ] ) || ! isset( $_POST['pronamic_ideal_membership_checkout' ] ) )
			return;
		
		if ( isset( $_POST['pronamic_ideal_issuer_id'] ) )
			return;

		// Posted hash
		$hash = $_POST['pronamic_ideal_membership_checkout'];

		// Posted variables
		$subscription_id = filter_input( INPUT_POST, 'subscription_id', FILTER_VALIDATE_INT );
		$pricing = unserialize( stripslashes( $_POST['pricing'] ) );
		$user_id = filter_input( INPUT_POST, 'user_id', FILTER_VALIDATE_INT );

		// Check the hash is the same.  Ensures no tampering of the form data
		if ( $hash != Pronamic_Membership_IDeal_Addon::encrypt_data( $subscription_id, $pricing, $user_id ) )
			return;

		// Subscription Class, and load it
		$subscription = new M_Subscription( $subscription_id );
		$subscription->get();

		// Membership Class
		$membership = new M_Membership( $user_id );

		// Configuration Class
		$configuration_id = get_option( 'pronamic_ideal_membership_chosen_configuration' );
		$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $configuration_id );

		// Get gateway from configuration
		$gateway = Pronamic_WordPress_IDeal_IDeal::get_gateway( $configuration );

		// @todo add notifier class here
		if ( ! $gateway )
			return;

		// Prepare the form data
		$ideal_data = new Pronamic_WP_Pay_Membership_PaymentData( $subscription, $membership );

		if ( $gateway->is_html_form() ) {
			// Lets set it up, and get it started!
			$payment = Pronamic_WordPress_IDeal_IDeal::start( $configuration, $gateway, $ideal_data );
			
			$gateway->redirect( $payment );
		} else if( $gateway->is_http_redirect() ) {
			ob_start();

			?>
			<form method="post">
				<?php echo $gateway->get_input_html( $payment ); ?>
				<input type="hidden" name="pronamic_ideal_membership_checkout" value="<?php echo Pronamic_Membership_IDeal_Addon::encrypt_data( $subscription->sub_id(), $pricing, $user_id ); ?>" />
				<input type="hidden" name="subscription_id" value="<?php echo $subscription->sub_id(); ?>" />
				<input type="hidden" name="pricing" value='<?php echo serialize( $pricing ); ?>' />
				<input type="hidden" name="user_id" value="<?php echo $user_id; ?>"/>
				<input type="submit" value="<?php _e( 'Continue', 'pronamic_ideal' ); ?>" />
			</form>
			<?php

			self::$html = ob_get_clean();
		}

		add_filter( 'the_content', array( $this, 'clear_page' ) );
	}
	
	public function redirect_http() {
		if ( ! isset( $_POST['pronamic_ideal_issuer_id'] ) )
			return;
		
		// Posted variables
		$subscription_id = filter_input( INPUT_POST, 'subscription_id', FILTER_VALIDATE_INT );
		$pricing = unserialize( stripslashes( $_POST['pricing'] ) );
		$user_id = filter_input( INPUT_POST, 'user_id', FILTER_VALIDATE_INT );

		// Posted hash
		$hash = $_POST['pronamic_ideal_membership_checkout'];
		
		// Check the hash is the same.  Ensures no tampering of the form data
		if ( $hash != Pronamic_Membership_IDeal_Addon::encrypt_data( $subscription_id, $pricing, $user_id ) )
			return;
		
		// Subscription Class, and load it
		$subscription = new M_Subscription( $subscription_id );
		$subscription->get();

		// Membership Class
		$membership = new M_Membership( $user_id );

		// Configuration Class
		$configuration_id = get_option( 'pronamic_ideal_membership_chosen_configuration' );
		$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $configuration_id );

		// Get gateway from configuration
		$gateway = Pronamic_WordPress_IDeal_IDeal::get_gateway( $configuration );

		// @todo add notifier class here
		if ( ! $gateway )
			return;

		// Prepare the form data
		$ideal_data = new Pronamic_WP_Pay_Membership_PaymentData( $subscription, $membership );

		Pronamic_WordPress_IDeal_IDeal::start( $configuration, $gateway, $ideal_data );
		$gateway->redirect_via_http();
	}

	public function clear_page( $the_content ) {
		return self::$html;
	}
	
	function single_free_button($pricing, $subscription, $user_id, $sublevel = 0) {
		return '';
	}

	function build_custom($user_id, $sub_id, $amount) {
		return '';
	}
}
