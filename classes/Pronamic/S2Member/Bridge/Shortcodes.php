<?php

/**
 * Pronamic_S2Member_Bridge_Shortcodes
 *
 * Handles the generation and interpretation of
 * shortcodes for use in S2 Member
 *
 * @author Leon Rowland <leon@rowland.nl>
 * @version 1.0
 */
class Pronamic_S2Member_Bridge_Shortcodes {
	public static $html;

	public function __construct() {
		add_action( 'init', array( $this, 'ideal_page' ) );

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
			'period' => null,
			'cost' => null,
			'level' => null,
			'description' => __( 'iDEAL s2Member Payment', 'pronamic_ideal' )
		);

		// Combine the passed options
		$options = shortcode_atts( $defaults, $attributes );

		// No duration ID or amount set, prevent shortcode generation
		if ( ! $options['period'] || ! $options['cost'] || ! $options['level'] ) return;

		// Gets settings to determine if ideal for s2 members is enabled, and all page settings from s2 member
		$ideal_active = get_option( 'pronamic_ideal_s2member_enabled' );
		$s2members_settings = get_option( 'ws_plugin__s2member_cache' );

		// No configuration set for the membership options page, stop shortcode
		if ( empty( $s2members_settings['membership_options_page'] ) )
			return;

		if ( $ideal_active ) {
			ob_start();

			?>
			<form method="post" action="<?php echo add_query_arg(array( 'pronamic_ideal_s2member_checkout' => 'true'), get_permalink( $s2members_settings['membership_options_page']['page'] ) ) ?>">
				<input type="hidden" name="pronamic_ideal_s2member_checkout" value="<?php echo $this->encrypt_data( $options ); ?>"/>
				<input type="hidden" name="options[period]" value="<?php echo $options['period']; ?>" />
				<input type="hidden" name="options[cost]" value="<?php echo $options['cost']; ?>"/>
				<input type="hidden" name="options[level]" value="<?php echo $options['level']; ?>"/>
				<input type="submit" value="<?php _e( 'Pay with iDEAL', 'pronamic_ideal'); ?>" />
			</form>
			<?php

			return ob_get_clean();
		}

	}

	public function encrypt_data( $data ) {

		return sha1( $data['period'] . $data['cost'] . $data['level'] . AUTH_SALT );

	}

	public function ideal_page() {
		if ( ! isset( $_GET['pronamic_ideal_s2member_checkout'] ) )
			return;

		// Form submission, lets check the data!
		if ( ! isset( $_POST['pronamic_ideal_s2member_checkout'] ) || ! isset( $_POST['options'] ) )
			return;

		// Gets the security string
		$security_string = $_POST['pronamic_ideal_s2member_checkout'];

		// Checks no inputs have been tampered
		if ( $security_string != $this->encrypt_data( $_POST['options'] ) )
			return;

		// Store those options
		$options = $_POST['options'];

		// Make an OrderID This will be the unique ID for this order. Since S2Member doesn't
		// store any information about orders we will use the meta information for a user.
		// As for the ID of this 'order', it will be the umeta_id (pk of the user meta table )
		// which will be returned with the below method
		$order = new Pronamic_S2Member_Bridge_Order();
		$options['status'] = 'Open';
		$options['orderID'] = $order_id = $order->add_order( $options, get_current_user_id() );

		// Get the configuration id
		$configuration_id = get_option( 'pronamic_ideal_s2member_chosen_configuration' );
		$configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $configuration_id );

		// Get the gateway from the configuration
		$gateway = Pronamic_WordPress_IDeal_IDeal::get_gateway( $configuration );

		// Check a gateway is valid, or just continue with the normal process @todo error reporting in the future
		if ( ! $gateway )
			return;

		// Prepare the form data
		$ideal_data = new Pronamic_S2Member_IDeal_IDealDataProxy( $options );

		// Lets set it up, and get it started!
		Pronamic_WordPress_IDeal_IDeal::start( $configuration, $gateway, $ideal_data );

		// Determine if a normal html form ( and not a redirect for ideal advanced )
		if ( $gateway->is_html_form() ) {

			self::$html = $gateway->get_form_html( true );

		} else if ( $gateway->is_http_redirect() ) {

			ob_start();

			?>
			<form method="post" action="<?php echo $gateway->get_action_url(); ?>">
					<?php echo $gateway->get_input_html(); ?>
				<input type="submit" value="Lets go!!"/>
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
