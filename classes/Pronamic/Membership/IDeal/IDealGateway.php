<?php

/**
 * Title: WPMU Membership iDEAL gateway
 * Copyright: Pronamic (c) 2005 - 2013
 * Company: Pronamic
 * @author Leon Rowland <leon@rowland.nl>
 * @version 1.0
 */

class Pronamic_Membership_IDeal_IDealGateway extends M_Gateway {

	public $gateway = 'ideal';
	public $title = 'iDEAL';

	public static $html;

	public function __construct() {
		parent::M_Gateway();

		if ( $this->is_active() ) {
			add_action( 'init', array( $this, 'handle_real_form' ) );

			add_action( 'membership_purchase_button', array( $this, 'display_subscribe_button' ), 1, 3 );
		}
	}

	public function build_subscribe_button( $subscription, $pricing, $user_id ) {
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

	public function display_subscribe_button( $subscription, $pricing, $user_id ) {
		echo $this->build_subscribe_button( $subscription, $pricing, $user_id );
	}

	public function display_payment_form() {

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
		$ideal_data = new Pronamic_Membership_IDeal_IDealDataProxy( $subscription, $membership );

		// Lets set it up, and get it started!
		Pronamic_WordPress_IDeal_IDeal::start( $configuration, $gateway, $ideal_data );

		if ( $gateway->is_html_form() ) {
			self::$html = $gateway->get_form_html( true );
		} else if( $gateway->is_http_redirect() ) {
			ob_start();

			?>
			<form method="post" action="<?php echo $gateway->get_action_url(); ?>">
				<?php echo $gateway->get_input_html(); ?>
				<input type="submit" value="<?php _e( 'Continue', 'pronamic_ideal' ); ?>" />
			</form>
			<?php

			self::$html = ob_get_clean();
		}

		add_filter( 'the_content', array( $this, 'clear_page' ) );
	}

	public function clear_page( $the_content ) {
		return self::$html;
	}
}

