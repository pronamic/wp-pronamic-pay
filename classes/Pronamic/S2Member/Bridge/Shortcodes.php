<?php

/**
 * Pronamic_S2Member_Bridge_Shortcodes
 *
 * Handles the generation and interpretation of
 * shortcodes for use in S2 Member
 *
 * @author Leon Rowland <leon@rowland.nl>
 * @since 1.2.6
 */
class Pronamic_S2Member_Bridge_Shortcodes {

	public static $html;

	public function __construct() {
		add_action( 'init', array( $this, 'ideal_page_step_one' ) );
		add_action( 'init', array( $this, 'ideal_page_step_two' ) );

		add_shortcode( 'pronamic_ideal_s2member', array( $this, 'ideal' ) );
	}

	/**
	 * Handles the generation of the form from shortcode arguments.
	 *
	 * Expected shortcode example (made by generator)
	 *
	 * [pronamic_ideal_s2member cost="10" period="1 Y" level="1" description="asdfasdfasdfas asdf asdf asdfa" ]
	 *
	 * period represents one of the predetermined durations they can
	 * selected from the dropdown.
	 *
	 * cost is set by the shortcode generator.  Must be ISO standard format ( . as decimal seperator )
	 *
	 * level is the level access upon payment will be granted.
	 *
	 * description is text shown at payment.
	 *
	 * @param array $attributes All arguments inside the shortcode
	 */
	public function ideal( $attributes ) {
		$defaults = array(
			'period'		 => null,
			'cost'			 => null,
			'level'			 => null,
			'description'	 => __( 'iDEAL s2Member Payment || {{order_id}}', 'pronamic_ideal' )
		);

		// Combine the passed options
		$options = shortcode_atts( $defaults, $attributes );

		// No duration ID or amount set, prevent shortcode generation
		if ( ! $options[ 'period' ] || ! $options[ 'cost' ] || ! $options[ 'level' ] )
			return;

		// Gets settings to determine if ideal for s2 members is enabled, and all page settings from s2 member
		$is_enabled = get_option( 'pronamic_pay_s2member_enabled' );

		if ( $is_enabled ) {
			ob_start();

			// Get the configuration id
			$config_id = get_option( 'pronamic_pay_s2member_config_id' );

			// Get the gateway from the configuration
			$gateway = Pronamic_WordPress_IDeal_IDeal::get_gateway( $config_id );

			?>
			<form method="post" action="">
				<input type="hidden" name="pronamic_ideal_s2member_checkout_1" value="<?php echo $this->encrypt_data( $options ); ?>"/>
				<input type="hidden" name="options[period]" value="<?php echo $options[ 'period' ]; ?>" />
				<input type="hidden" name="options[cost]" value="<?php echo $options[ 'cost' ]; ?>"/>
				<input type="hidden" name="options[level]" value="<?php echo $options[ 'level' ]; ?>"/>
				<input type="hidden" name="options[description]" value="<?php echo $options[ 'description' ]; ?>"/>
				<input type="submit" value="<?php _e( 'Pay with iDEAL', 'pronamic_ideal' ); ?>" />
			</form>
			<?php
			return ob_get_clean();
		}
	}

	public function encrypt_data( $data ) {
		return sha1( $data[ 'period' ] . $data[ 'cost' ] . $data[ 'level' ] . $data[ 'description' ] . AUTH_SALT );
	}

	public function ideal_page_step_one() {

		// Form submission, lets check the data!
		if ( ! isset( $_POST[ 'pronamic_ideal_s2member_checkout_1' ] ) || ! isset( $_POST[ 'options' ] ) )
			return;

		// Gets the security string
		$security_string = $_POST[ 'pronamic_ideal_s2member_checkout_1' ];

		// Checks no inputs have been tampered
		if ( $security_string != $this->encrypt_data( $_POST[ 'options' ] ) )
			return;

		// Store those options
		$options = $_POST[ 'options' ];

		// No more reference to the Bridge_Order. Instead the order id is just a uniqueid.
		$options[ 'status' ]	 = 'Open';
		$options[ 'orderID' ]	 = $order_id			 = uniqid();

		// Get the configuration id
		$configuration_id	 = get_option( 'pronamic_ideal_s2member_chosen_configuration' );
		$configuration		 = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $configuration_id );

		// Get the gateway from the configuration
		$gateway = Pronamic_WordPress_IDeal_IDeal::get_gateway( $configuration );

		// Check a gateway is valid, or just continue with the normal process @todo error reporting in the future
		if ( ! $gateway )
			return;

		ob_start();
		?>
		<form method="post" action="">
			<?php if ( ! is_user_logged_in() ) : ?>
				<label><?php _e( 'Email' ); ?></label><input type="text" name="pronamic_ideal_email" value=""/> 
			<?php endif; ?>
			<input type="hidden" name="pronamic_ideal_s2member_checkout_2" value="<?php echo $this->encrypt_data( $options ); ?>"/>

			<input type="hidden" name="options[period]" value="<?php echo $options[ 'period' ]; ?>" />
			<input type="hidden" name="options[cost]" value="<?php echo $options[ 'cost' ]; ?>"/>
			<input type="hidden" name="options[level]" value="<?php echo $options[ 'level' ]; ?>"/>
			<input type="hidden" name="options[description]" value="<?php echo $options[ 'description' ]; ?>"/>

			<?php echo $gateway->get_input_html(); ?>

			<input type="submit" value="<?php _e( 'Pay with iDEAL', 'pronamic_ideal' ); ?>" />
		</form>
		<?php
		self::$html = ob_get_clean();

		add_filter( 'the_content', array( $this, 'ideal_content_overide' ) );
	}

	public function ideal_page_step_two() {

		// Form submission, lets check the data!
		if ( ! isset( $_POST[ 'pronamic_ideal_s2member_checkout_2' ] ) || ! isset( $_POST[ 'options' ] ) )
			return;

		// Gets the security string
		$security_string = $_POST[ 'pronamic_ideal_s2member_checkout_2' ];

		// Checks no inputs have been tampered
		if ( $security_string != $this->encrypt_data( $_POST[ 'options' ] ) )
			return;

		// Store those options
		$options = $_POST[ 'options' ];

		// No more reference to the Bridge_Order. Instead the order id is just a uniqueid.
		$options[ 'status' ]	 = 'Open';
		$options[ 'orderID' ]	 = $order_id			 = uniqid();

		// Get the configuration id
		$configuration_id	 = get_option( 'pronamic_ideal_s2member_chosen_configuration' );
		$configuration		 = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $configuration_id );

		// Get the gateway from the configuration
		$gateway = Pronamic_WordPress_IDeal_IDeal::get_gateway( $configuration );

		// Check a gateway is valid, or just continue with the normal process @todo error reporting in the future
		if ( ! $gateway )
			return;

		// Prepare the form data
		$ideal_data = new Pronamic_WP_Pay_S2Member_PaymentData( $options );

		// Lets set it up, and get it started!
		Pronamic_WordPress_IDeal_IDeal::start( $configuration, $gateway, $ideal_data );

		$gateway->redirect();
	}

	public function ideal_content_overide() {
		return self::$html;
	}

}
