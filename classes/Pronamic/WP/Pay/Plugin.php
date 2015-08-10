<?php

/**
 * Title: WordPress iDEAL plugin
 * Description:
 * Copyright: Copyright (c) 2005 - 2015
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0.0
 * @since 1.0.0
 */
class Pronamic_WP_Pay_Plugin {
	/**
	 * The root file of this WordPress plugin
	 *
	 * @var string
	 */
	public static $file;

	/**
	 * The plugin dirname
	 *
	 * @var string
	 */
	public static $dirname;

	//////////////////////////////////////////////////

	/**
	 * Bootstrap
	 *
	 * @param string $file
	 */
	public static function bootstrap( $file ) {
		self::$file	= $file;
		self::$dirname = dirname( $file );

		// Bootstrap the add-ons
		Pronamic_WP_Pay_Extensions_WooCommerce_Extension::bootstrap();
		Pronamic_WP_Pay_Extensions_GravityForms_Extension::bootstrap();
		Pronamic_WP_Pay_Extensions_Shopp_Extension::bootstrap();
		Pronamic_WP_Pay_Extensions_Jigoshop_Extension::bootstrap();
		Pronamic_WP_Pay_Extensions_WPeCommerce_Extension::bootstrap();
		Pronamic_WP_Pay_Extensions_ClassiPress_Extension::bootstrap();
		Pronamic_WP_Pay_Extensions_EventEspressoLegacy_Extension::bootstrap();
		Pronamic_WP_Pay_Extensions_EventEspresso_Extension::bootstrap();
		Pronamic_WP_Pay_Extensions_AppThemes_Extension::bootstrap();
		Pronamic_WP_Pay_Extensions_S2Member_Extension::bootstrap();
		Pronamic_WP_Pay_Extensions_WPMUDEV_Membership_Extension::bootstrap();
		// Pronamic_WP_Pay_Extensions_EShop_Extension::bootstrap();
		Pronamic_WP_Pay_Extensions_EDD_Extension::bootstrap();
		Pronamic_WP_Pay_Extensions_IThemesExchange_Extension::bootstrap();

		// Admin
		if ( is_admin() ) {
			$admin = new Pronamic_WP_Pay_Admin();
		}

		// Post Types
		$post_types = new Pronamic_WP_Pay_PostTypes();

		// Shortcodes
		$shortcodes = new Pronamic_WP_Pay_Shortcodes();

		// License
		$license_manager = new Pronamic_WP_Pay_LicenseManager();

		$form_processor = new Pronamic_WP_Pay_FormProcessor();

		// Payment notes
		add_filter( 'comments_clauses', array( __CLASS__, 'exclude_comment_payment_notes' ), 10, 2 );

		// Setup
		add_action( 'plugins_loaded', array( __CLASS__, 'setup' ), 9 );

		// Initialize requirements
		require_once self::$dirname . '/includes/version.php';
		require_once self::$dirname . '/includes/functions.php';
		require_once self::$dirname . '/includes/formatting.php';
		require_once self::$dirname . '/includes/page-functions.php';
		require_once self::$dirname . '/includes/providers.php';
		require_once self::$dirname . '/includes/gateways.php';
		require_once self::$dirname . '/includes/payment.php';
		require_once self::$dirname . '/includes/post.php';
		require_once self::$dirname . '/includes/xmlseclibs/xmlseclibs-ing.php';

		// If WordPress is loaded check on returns and maybe redirect requests
		add_action( 'wp_loaded', array( __CLASS__, 'handle_returns' ) );
		add_action( 'wp_loaded', array( __CLASS__, 'maybe_redirect' ) );

		// The 'pronamic_ideal_check_transaction_status' hook is scheduled the status requests
		add_action( 'pronamic_ideal_check_transaction_status', array( __CLASS__, 'check_status' ) );

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Enqueue scripts
	 */
	public static function enqueue_scripts() {
		$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_style(
			'pronamic-pay-forms',
			plugins_url( 'css/forms' . $min . '.css', Pronamic_WP_Pay_Plugin::$file ),
			array(),
			'3.7.0'
		);

		wp_enqueue_style( 'pronamic-pay-forms' );
	}

	//////////////////////////////////////////////////

	/**
	 * Comments clauses
	 *
	 * @param array $clauses
	 * @param WP_Comment_Query $query
	 * @return array
	 */
	public static function exclude_comment_payment_notes( $clauses, $query ) {
		$type = $query->query_vars['type'];

		// Ignore payment notes comments if it's not specific requested
		if ( 'payment_note' !== $type ) {
			$clauses['where'] .= " AND comment_type != 'payment_note'";
		}

		return $clauses;
	}

	//////////////////////////////////////////////////

	/**
	 * Check status of the specified payment
	 *
	 * @param string $paymentId
	 */
	public static function check_status( $payment_id = null, $seconds = null, $number_tries = 1 ) {
		$payment = new Pronamic_WP_Pay_Payment( $payment_id );

		if ( null !== $payment ) {
			// http://pronamic.nl/wp-content/uploads/2011/12/iDEAL_Advanced_PHP_EN_V2.2.pdf (page 19)
			// - No status request after a final status has been received for a transaction;
			if ( empty( $payment->status ) || $payment->status === Pronamic_WP_Pay_Gateways_IDealAdvancedV3_Status::OPEN ) {
				self::update_payment( $payment );

				if ( empty( $payment->status ) || $payment->status === Pronamic_WP_Pay_Gateways_IDealAdvancedV3_Status::OPEN ) {
					$seconds = DAY_IN_SECONDS;

					switch ( $number_tries ) {
						case 0 :
							// 30 seconds after a transaction request is sent
							$seconds = 30;
							break;
						case 1 :
							// Half-way through an expirationPeriod
							$seconds = 30 * MINUTE_IN_SECONDS;
							break;
							// Half-way through an expirationPeriod
						case 2 :
							// Just after an expirationPeriod
							$seconds = HOUR_IN_SECONDS;
							break;
						case 3 :
						default :
							$seconds = DAY_IN_SECONDS;
							break;
					}

					if ( $number_tries < 4 ) {
						$time = time();

						wp_schedule_single_event( $time + $seconds, 'pronamic_ideal_check_transaction_status', array(
							'payment_id'   => $payment->get_id(),
							'seconds'      => $seconds,
							'number_tries' => $number_tries++,
						) );
					}
				}
			}
		} else {
			// Payment with the specified ID could not be found, can't check the status
		}
	}

	public static function update_payment( $payment = null, $can_redirect = true ) {
		if ( $payment ) {
			$gateway = Pronamic_WP_Pay_Plugin::get_gateway( $payment->config_id );

			if ( $gateway ) {
				$old_status = strtolower( $payment->status );

				if ( strlen( $old_status ) <= 0 ) {
					$old_status = 'unknown';
				}

				$gateway->update_status( $payment );

				$new_status = strtolower( $payment->status );

				pronamic_wp_pay_update_payment( $payment );

				do_action( "pronamic_payment_status_update_{$payment->source}_{$old_status}_to_{$new_status}", $payment, $can_redirect );
				do_action( "pronamic_payment_status_update_{$payment->source}", $payment, $can_redirect );
				do_action( 'pronamic_payment_status_update', $payment, $can_redirect );

				if ( $can_redirect ) {
					$url     = home_url( '/' );
					$page_id = null;

					switch ( $payment->status ) {
						case Pronamic_WP_Pay_Statuses::CANCELLED :
							$page_id = pronamic_pay_get_page_id( 'cancel' );
							break;
						case Pronamic_WP_Pay_Statuses::EXPIRED :
							$page_id = pronamic_pay_get_page_id( 'expired' );
							break;
						case Pronamic_WP_Pay_Statuses::FAILURE :
							$page_id = pronamic_pay_get_page_id( 'error' );
							break;
						case Pronamic_WP_Pay_Statuses::OPEN :
							$page_id = pronamic_pay_get_page_id( 'unknown' );
							break;
						case Pronamic_WP_Pay_Statuses::SUCCESS :
							$page_id = pronamic_pay_get_page_id( 'completed' );
							break;
						default:
							$page_id = pronamic_pay_get_page_id( 'unknown' );
							break;
					}

					if ( ! empty( $page_id ) ) {
						$page_url = get_permalink( $page_id );

						if ( false !== $page_url ) {
							$url = $page_url;
						}
					}

					wp_redirect( $url );

					exit;
				}
			}
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Handle returns
	 */
	public static function handle_returns() {
		if ( filter_has_var( INPUT_GET, 'payment' ) ) {
			$payment_id = filter_input( INPUT_GET, 'payment', FILTER_SANITIZE_NUMBER_INT );

			$payment = get_pronamic_payment( $payment_id );

			// Check if we should redirect
			$should_redirect = true;

			// Check if the request is an callback request
			// Sisow gatway will extend callback requests with querystring "callback=true"
			if ( filter_has_var( INPUT_GET, 'callback' ) ) {
				$is_callback = filter_input( INPUT_GET, 'callback', FILTER_VALIDATE_BOOLEAN );

				if ( $is_callback ) {
					$should_redirect = false;
				}
			}

			// Check if the request is an notify request
			// Sisow gatway will extend callback requests with querystring "notify=true"
			if ( filter_has_var( INPUT_GET, 'notify' ) ) {
				$is_notify = filter_input( INPUT_GET, 'notify', FILTER_VALIDATE_BOOLEAN );

				if ( $is_notify ) {
					$should_redirect = false;
				}
			}

			self::update_payment( $payment, $should_redirect );
		}

		Pronamic_WP_Pay_Gateways_IDealBasic_Listener::listen();
		Pronamic_WP_Pay_Gateways_OmniKassa_Listener::listen();
		Pronamic_WP_Pay_Gateways_Icepay_Listener::listen();
		Pronamic_WP_Pay_Gateways_Mollie_Listener::listen();
		Pronamic_WP_Pay_Gateways_Ogone_Listener::listen();
		Pronamic_WP_Pay_Buckaroo_Listener::listen();
	}

	/**
	 * Maybe redirect
	 */
	public static function maybe_redirect() {
		if ( filter_has_var( INPUT_GET, 'payment_redirect' ) ) {
			$payment_id = filter_input( INPUT_GET, 'payment_redirect', FILTER_SANITIZE_NUMBER_INT );

			$payment = get_pronamic_payment( $payment_id );

			// HTML Answer
			$html_answer = $payment->get_meta( 'ogone_directlink_html_answer' );

			if ( ! empty( $html_answer ) ) {
				echo $html_answer;

				exit;
			}

			// Action URL
			if ( ! empty( $payment->action_url ) ) {
				wp_redirect( $payment->action_url );

				exit;
			}
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Get number payments
	 *
	 * @return int
	 */
	public static function get_number_payments() {
		$number = false;

		$count = wp_count_posts( 'pronamic_payment' );

		if ( isset( $count, $count->payment_completed ) ) {
			$number = intval( $count->payment_completed );
		}

		return $number;
	}

	//////////////////////////////////////////////////

	/**
	 * Setup, creates or updates database tables. Will only run when version changes
	 */
	public static function setup() {
		// Load plugin text domain
		$rel_path = dirname( plugin_basename( self::$file ) ) . '/languages/';

		load_plugin_textdomain( 'pronamic_ideal', false, $rel_path );
	}

	//////////////////////////////////////////////////
	// Functions from Pronamic_WordPress_IDeal_IDeal
	//////////////////////////////////////////////////

	/**
	 * Get the translaction of the specified status notifier
	 *
	 * @param string $status
	 * @return string
	 */
	public static function translate_status( $status ) {
		switch ( $status ) {
			case Pronamic_WP_Pay_Statuses::CANCELLED :
				return __( 'Cancelled', 'pronamic_ideal' );
			case Pronamic_WP_Pay_Statuses::EXPIRED :
				return __( 'Expired', 'pronamic_ideal' );
			case Pronamic_WP_Pay_Statuses::FAILURE :
				return __( 'Failure', 'pronamic_ideal' );
			case Pronamic_WP_Pay_Statuses::OPEN :
				return __( 'Open', 'pronamic_ideal' );
			case Pronamic_WP_Pay_Statuses::SUCCESS :
				return __( 'Success', 'pronamic_ideal' );
			default:
				return __( 'Unknown', 'pronamic_ideal' );
		}
	}

	public static function get_payment_states() {
		return array(
			'payment_pending'    => _x( 'Pending', 'Payment status', 'pronamic_ideal' ),
			'payment_processing' => _x( 'Processing', 'Payment status', 'pronamic_ideal' ),
			'payment_on_hold'    => _x( 'On Hold', 'Payment status', 'pronamic_ideal' ),
			'payment_completed'  => _x( 'Completed', 'Payment status', 'pronamic_ideal' ),
			'payment_cancelled'  => _x( 'Cancelled', 'Payment status', 'pronamic_ideal' ),
			'payment_refunded'   => _x( 'Refunded', 'Payment status', 'pronamic_ideal' ),
			'payment_failed'     => _x( 'Failed', 'Payment status', 'pronamic_ideal' ),
			'payment_expired'    => _x( 'Expired', 'Payment status', 'pronamic_ideal' ),
		);
	}

	//////////////////////////////////////////////////

	/**
	 * Get default error message
	 *
	 * @return string
	 */
	public static function get_default_error_message() {
		return __( 'Paying with iDEAL is not possible. Please try again later or pay another way.', 'pronamic_ideal' );
	}

	//////////////////////////////////////////////////

	/**
	 * Get config select options
	 *
	 * @return array
	 */
	public static function get_config_select_options( $payment_method = null ) {
		$args = array(
			'post_type' => 'pronamic_gateway',
			'nopaging'  => true,
		);

		if ( isset( $payment_method ) ) {
			$gateways = array();

			switch ( $payment_method ) {
				case Pronamic_WP_Pay_PaymentMethods::CREDIT_CARD :
					$gateways[] = 'buckaroo';
					$gateways[] = 'icepay-ideal';
					$gateways[] = 'mollie';
					$gateways[] = 'rabobank-omnikassa';

					break;
				case Pronamic_WP_Pay_PaymentMethods::MISTER_CASH :
					$gateways[] = 'buckaroo';
					$gateways[] = 'icepay-ideal';
					$gateways[] = 'ogone-orderstandard';
					$gateways[] = 'mollie';
					$gateways[] = 'rabobank-omnikassa';
					$gateways[] = 'sisow-ideal';

					break;
				case Pronamic_WP_Pay_PaymentMethods::MINITIX :
					$gateways[] = 'rabobank-omnikassa';

					break;
				case Pronamic_WP_Pay_PaymentMethods::SOFORT :
					$gateways[] = 'mollie';

					break;
			}

			$args['meta_query'] = array(
				array(
					'key'     => '_pronamic_gateway_id',
					'value'   => $gateways,
					'compare' => 'IN',
				),
			);
		}

		$gateways = get_posts( $args );

		$options = array( __( '&mdash; Select Configuration &mdash;', 'pronamic_ideal' ) );

		foreach ( $gateways as $gateway ) {
			$options[ $gateway->ID ] = sprintf(
				'%s (%s)',
				get_the_title( $gateway->ID ),
				get_post_meta( $gateway->ID, '_pronamic_gateway_mode', true )
			);
		}

		return $options;
	}

	//////////////////////////////////////////////////

	/**
	 * Render errors
	 *
	 * @param array $errors
	 */
	public static function render_errors( $errors = array() ) {
		if ( ! is_array( $errors ) ) {
			$errors = array( $errors );
		}

		foreach ( $errors as $error ) {
			include Pronamic_WP_Pay_Plugin::$dirname . '/views/error.php';
		}
	}

	//////////////////////////////////////////////////

	public static function get_gateway( $config_id ) {
		$config = get_pronamic_pay_gateway_config( $config_id );

		$gateway_id = $config->gateway_id;

		$config_providers = array(
			'buckaroo'                 => 'Pronamic_WP_Pay_Buckaroo_ConfigFactory',
			'icepay'                   => 'Pronamic_WP_Pay_Gateways_Icepay_ConfigFactory',
			'ideal_advanced'           => 'Pronamic_WP_Pay_Gateways_IDealAdvanced_ConfigFactory',
			'ideal_advanced_v3'        => 'Pronamic_WP_Pay_Gateways_IDealAdvancedV3_ConfigFactory',
			'ideal_basic'              => 'Pronamic_WP_Pay_Gateways_IDealBasic_ConfigFactory',
			'mollie'                   => 'Pronamic_WP_Pay_Gateways_Mollie_ConfigFactory',
			'mollie_ideal'             => 'Pronamic_WP_Pay_Gateways_Mollie_IDeal_ConfigFactory',
			'multisafepay_connect'     => 'Pronamic_WP_Pay_Gateways_MultiSafepay_ConfigFactory',
			'ogone_directlink'         => 'Pronamic_WP_Pay_Gateways_Ogone_DirectLink_ConfigFactory',
			'ogone_orderstandard'      => 'Pronamic_WP_Pay_Gateways_Ogone_OrderStandard_ConfigFactory',
			'ogone_orderstandard_easy' => 'Pronamic_WP_Pay_Gateways_Ogone_OrderStandardEasy_ConfigFactory',
			'omnikassa'                => 'Pronamic_WP_Pay_Gateways_OmniKassa_ConfigFactory',
			'pay_nl'                   => 'Pronamic_WP_Pay_Gateways_PayNL_ConfigFactory',
			'paydutch'                 => 'Pronamic_WP_Pay_Gateways_PayDutch_ConfigFactory',
			'qantani'                  => 'Pronamic_WP_Pay_Gateways_Qantani_ConfigFactory',
			'sisow'                    => 'Pronamic_WP_Pay_Gateways_Sisow_ConfigFactory',
			'targetpay'                => 'Pronamic_WP_Pay_Gateways_TargetPay_ConfigFactory',
		);

		$config_providers = apply_filters( 'pronamic_pay_config_providers', $config_providers );

		foreach ( $config_providers as $name => $class_name ) {
			Pronamic_WP_Pay_ConfigProvider::register( $name, $class_name );
		}

		$config_gateways = array(
			'Pronamic_WP_Pay_Buckaroo_Config'                         => 'Pronamic_WP_Pay_Buckaroo_Gateway',
			'Pronamic_WP_Pay_Gateways_Icepay_Config'                  => 'Pronamic_WP_Pay_Gateways_Icepay_Gateway',
			'Pronamic_WP_Pay_Gateways_IDealAdvanced_Config'           => 'Pronamic_WP_Pay_Gateways_IDealAdvanced_Gateway',
			'Pronamic_WP_Pay_Gateways_IDealAdvancedV3_Config'         => 'Pronamic_WP_Pay_Gateways_IDealAdvancedV3_Gateway',
			'Pronamic_WP_Pay_Gateways_IDealBasic_Config'              => 'Pronamic_WP_Pay_Gateways_IDealBasic_Gateway',
			'Pronamic_WP_Pay_Gateways_Ogone_DirectLink_Config'        => 'Pronamic_WP_Pay_Gateways_Ogone_DirectLink_Gateway',
			'Pronamic_WP_Pay_Gateways_Ogone_OrderStandard_Config'     => 'Pronamic_WP_Pay_Gateways_Ogone_OrderStandard_Gateway',
			'Pronamic_WP_Pay_Gateways_Ogone_OrderStandardEasy_Config' => 'Pronamic_WP_Pay_Gateways_Ogone_OrderStandardEasy_Gateway',
			'Pronamic_WP_Pay_Gateways_Mollie_Config'                  => 'Pronamic_WP_Pay_Gateways_Mollie_Gateway',
			'Pronamic_WP_Pay_Gateways_Mollie_IDeal_Config'            => 'Pronamic_WP_Pay_Gateways_Mollie_IDeal_Gateway',
			'Pronamic_WP_Pay_Gateways_MultiSafepay_Config'            => 'Pronamic_WP_Pay_Gateways_MultiSafepay_Connect_Gateway',
			'Pronamic_WP_Pay_Gateways_OmniKassa_Config'               => 'Pronamic_WP_Pay_Gateways_OmniKassa_Gateway',
			'Pronamic_WP_Pay_Gateways_PayDutch_Config'                => 'Pronamic_WP_Pay_Gateways_PayDutch_Gateway',
			'Pronamic_WP_Pay_Gateways_PayNL_Config'                   => 'Pronamic_WP_Pay_Gateways_PayNL_Gateway',
			'Pronamic_WP_Pay_Gateways_Qantani_Config'                 => 'Pronamic_WP_Pay_Gateways_Qantani_Gateway',
			'Pronamic_WP_Pay_Gateways_Sisow_Config'                   => 'Pronamic_WP_Pay_Gateways_Sisow_Gateway',
			'Pronamic_WP_Pay_Gateways_TargetPay_Config'               => 'Pronamic_WP_Pay_Gateways_TargetPay_Gateway',
		);

		$config_gateways = apply_filters( 'pronamic_pay_config_gateways', $config_gateways );

		foreach ( $config_gateways as $config_class => $gateway_class ) {
			Pronamic_WP_Pay_GatewayFactory::register( $config_class, $gateway_class );
		}

		global $pronamic_pay_gateways;

		if ( isset( $pronamic_pay_gateways[ $gateway_id ] ) ) {
			$gateway      = $pronamic_pay_gateways[ $gateway_id ];
			$gateway_slug = $gateway['gateway'];

			$config = Pronamic_WP_Pay_ConfigProvider::get_config( $gateway_slug, $config_id );

			$gateway = Pronamic_WP_Pay_GatewayFactory::create( $config );

			return $gateway;
		}
	}

	public static function start( $config_id, Pronamic_WP_Pay_Gateway $gateway, Pronamic_Pay_PaymentDataInterface $data, $payment_method = null ) {
		$payment = self::create_payment( $config_id, $gateway, $data );

		if ( $payment ) {
			$gateway->start( $data, $payment, $payment_method );

			pronamic_wp_pay_update_payment( $payment );

			$gateway->payment( $payment );
		}

		return $payment;
	}

	public static function create_payment( $config_id, $gateway, $data ) {
		$payment = null;

		$result = wp_insert_post( array(
			'post_type'   => 'pronamic_payment',
			'post_title'  => sprintf( __( 'Payment for %s', 'pronamic_ideal' ), $data->get_title() ),
			'post_status' => 'payment_pending',
		), true );

		if ( is_wp_error( $result ) ) {
			// @todo what todo?
		} else {
			$post_id = $result;

			// @todo temporary solution for WPMU DEV
			$data->payment_post_id = $post_id;

			// Payment
			$payment = new Pronamic_WP_Pay_Payment( $post_id );
			$payment->config_id     = $config_id;
			$payment->currency      = $data->get_currency();
			$payment->amount        = $data->get_amount();
			$payment->language      = $data->get_language();
			$payment->entrance_code = $data->get_entrance_code();
			$payment->description   = $data->get_description();
			$payment->source        = $data->get_source();
			$payment->source_id     = $data->get_source_id();
			$payment->email         = $data->get_email();
			$payment->status        = null;

			// Meta
			$prefix = '_pronamic_payment_';

			$meta = array(
				$prefix . 'config_id'               => $payment->config_id,
				$prefix . 'currency'                => $payment->currency,
				$prefix . 'amount'                  => $payment->amount,
				$prefix . 'expiration_period'       => null,
				$prefix . 'language'                => $payment->language,
				$prefix . 'entrance_code'           => $payment->entrance_code,
				$prefix . 'description'             => $payment->description,
				$prefix . 'consumer_name'           => null,
				$prefix . 'consumer_account_number' => null,
				$prefix . 'consumer_iban'           => null,
				$prefix . 'consumer_bic'            => null,
				$prefix . 'consumer_city'           => null,
				$prefix . 'status'                  => null,
				$prefix . 'source'                  => $payment->source,
				$prefix . 'source_id'               => $payment->source_id,
				$prefix . 'email'                   => $payment->email,
			);

			foreach ( $meta as $key => $value ) {
				if ( ! empty( $value ) ) {
					update_post_meta( $post_id, $key, $value );
				}
			}
		}

		return $payment;
	}
}
