<?php

/**
 * Title: WordPress iDEAL plugin
 * Description:
 * Copyright: Copyright (c) 2005 - 2016
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 4.1.0
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

		// Plugin
		return new Pronamic_WP_Pay_Plugin();
	}

	/**
	 * Construct and initialize an Pronamic Pay plugin object
	 */
	public function __construct() {
		// Bootstrap the add-ons
		Pronamic_WP_Pay_Extensions_Charitable_Extension::bootstrap();
		Pronamic_WP_Pay_Extensions_Give_Extension::bootstrap();
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
		Pronamic_WP_Pay_Extensions_EDD_Extension::bootstrap();
		Pronamic_WP_Pay_Extensions_IThemesExchange_Extension::bootstrap();
		Pronamic_WP_Pay_Extensions_MemberPress_Extension::bootstrap();
		Pronamic_WP_Pay_Extensions_FormidableForms_Extension::bootstrap();

		// Post Types
		$this->post_types = new Pronamic_WP_Pay_PostTypes();

		// Shortcodes
		$this->shortcodes = new Pronamic_WP_Pay_Shortcodes();

		// License
		$this->license_manager = new Pronamic_WP_Pay_LicenseManager();

		// Form Processor
		$this->form_processor = new Pronamic_WP_Pay_FormProcessor();

		// Admin
		if ( is_admin() ) {
			$this->admin = new Pronamic_WP_Pay_Admin( $this );
		}

		/*
		 * Plugins loaded.
		 *
		 * Priority should be at least lower then 8 to support the "WP eCommerce" plugin.
		 *
		 * new WP_eCommerce()
		 * add_action( 'plugins_loaded' , array( $this, 'init' ), 8 );
		 * $this->load();
		 * wpsc_core_load_gateways();
		 *
		 * @see https://github.com/wp-e-commerce/WP-e-Commerce/blob/branch-3.11.2/wp-shopping-cart.php#L342-L343
		 * @see https://github.com/wp-e-commerce/WP-e-Commerce/blob/branch-3.11.2/wp-shopping-cart.php#L26-L35
		 * @see https://github.com/wp-e-commerce/WP-e-Commerce/blob/branch-3.11.2/wp-shopping-cart.php#L54
		 * @see https://github.com/wp-e-commerce/WP-e-Commerce/blob/branch-3.11.2/wp-shopping-cart.php#L296-L297
		 */
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded' ), 5 );

		// Payment notes
		add_filter( 'comments_clauses', array( $this, 'exclude_comment_payment_notes' ), 10, 2 );

		// Payment redirect URL
		add_filter( 'pronamic_payment_redirect_url', array( $this, 'payment_redirect_url' ), 5, 2 );

		// Plugin locale
		add_filter( 'plugin_locale', array( $this, 'plugin_locale' ), 10, 2 );

		// Initialize requirements
		require_once self::$dirname . '/includes/version.php';
		require_once self::$dirname . '/includes/functions.php';
		require_once self::$dirname . '/includes/formatting.php';
		require_once self::$dirname . '/includes/page-functions.php';
		require_once self::$dirname . '/includes/providers.php';
		require_once self::$dirname . '/includes/payment.php';
		require_once self::$dirname . '/includes/post.php';
		require_once self::$dirname . '/includes/xmlseclibs/xmlseclibs-ing.php';

		// If WordPress is loaded check on returns and maybe redirect requests
		add_action( 'wp_loaded', array( $this, 'handle_returns' ) );
		add_action( 'wp_loaded', array( $this, 'maybe_redirect' ) );

		// The 'pronamic_ideal_check_transaction_status' hook is scheduled the status requests
		add_action( 'pronamic_ideal_check_transaction_status', array( $this, 'check_status' ), 10, 3 );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Get version
	 */
	public function get_version() {
		global $pronamic_pay_version;

		return $pronamic_pay_version;
	}

	//////////////////////////////////////////////////

	/**
	 * Enqueue scripts
	 */
	public function enqueue_scripts() {
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
	public function exclude_comment_payment_notes( $clauses, $query ) {
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
	public function check_status( $payment_id = null, $seconds = null, $number_tries = 1 ) {
		$payment = new Pronamic_WP_Pay_Payment( $payment_id );

		if ( null !== $payment ) {
			// http://pronamic.nl/wp-content/uploads/2011/12/iDEAL_Advanced_PHP_EN_V2.2.pdf (page 19)
			// - No status request after a final status has been received for a transaction;
			if ( empty( $payment->status ) || Pronamic_WP_Pay_Gateways_IDealAdvancedV3_Status::OPEN === $payment->status ) {
				self::update_payment( $payment );

				if ( empty( $payment->status ) || Pronamic_WP_Pay_Gateways_IDealAdvancedV3_Status::OPEN === $payment->status ) {
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
							'number_tries' => ++$number_tries,
						) );
					}
				}
			}
		}  // Payment with the specified ID could not be found, can't check the status
	}

	/**
	 * Payment redirect URL filter.
	 *
	 * @param string                  $url
	 * @param Pronamic_WP_Pay_Payment $payment
	 * @return string
	 */
	public function payment_redirect_url( $url, $payment ) {
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

		return $url;
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

				if ( defined( 'DOING_CRON' ) && ( empty( $payment->status ) || Pronamic_WP_Pay_Gateways_IDealAdvancedV3_Status::OPEN === $payment->status ) ) {
					$can_redirect = false;
				}

				do_action( "pronamic_payment_status_update_{$payment->source}_{$old_status}_to_{$new_status}", $payment, $can_redirect );
				do_action( "pronamic_payment_status_update_{$payment->source}", $payment, $can_redirect );
				do_action( 'pronamic_payment_status_update', $payment, $can_redirect );

				if ( $can_redirect ) {
					$url = $payment->get_return_redirect_url();

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
	public function handle_returns() {
		if ( filter_has_var( INPUT_GET, 'payment' ) ) {
			$payment_id = filter_input( INPUT_GET, 'payment', FILTER_SANITIZE_NUMBER_INT );

			$payment = get_pronamic_payment( $payment_id );

			// Check if payment key is valid
			$valid_key = false;

			if ( empty( $payment->key ) ) {
				$valid_key = true;
			} elseif ( filter_has_var( INPUT_GET, 'key' ) ) {
				$key = filter_input( INPUT_GET, 'key', FILTER_SANITIZE_STRING );

				$valid_key = ( $key === $payment->key );
			}

			if ( ! $valid_key ) {
				wp_redirect( home_url() );

				exit;
			}

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
	}

	/**
	 * Maybe redirect
	 */
	public function maybe_redirect() {
		if ( filter_has_var( INPUT_GET, 'payment_redirect' ) ) {
			$payment_id = filter_input( INPUT_GET, 'payment_redirect', FILTER_SANITIZE_NUMBER_INT );

			$payment = get_pronamic_payment( $payment_id );

			// HTML Answer
			$html_answer = $payment->get_meta( 'ogone_directlink_html_answer' );

			if ( ! empty( $html_answer ) ) {
				echo $html_answer; //xss ok

				exit;
			}

			if ( '' !== $payment->config_id ) {
				$gateway = Pronamic_WP_Pay_Plugin::get_gateway( $payment->config_id );

				if ( null !== $gateway && $gateway->is_html_form() ) {
					$gateway->start( $payment );
					$gateway->redirect( $payment );
				}

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
	public function plugins_loaded() {
		// Load plugin text domain
		$rel_path = dirname( plugin_basename( self::$file ) ) . '/languages/';

		load_plugin_textdomain( 'pronamic_ideal', false, $rel_path );

		// Gateway Integrations
		add_filter( 'pronamic_pay_gateway_integrations', array( $this, 'gateway_integrations' ) );

		$this->gateway_integrations = new Pronamic_WP_Pay_GatewayIntegrations();
	}

	/**
	 * Filter plugin locale.
	 */
	public function plugin_locale( $locale, $domain ) {
		if ( 'pronamic_ideal' !== $domain ) {
			return $locale;
		}

		if ( 'nl_NL_formal' === $locale ) {
			return 'nl_NL';
		}

		return $locale;
	}

	//////////////////////////////////////////////////
	// Functions from Pronamic_WordPress_IDeal_IDeal
	//////////////////////////////////////////////////

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
					$gateways[] = 'ing-kassa-compleet';
					$gateways[] = 'mollie';
					$gateways[] = 'qantani-mollie';
					$gateways[] = 'ogone-orderstandard';
					$gateways[] = 'rabobank-omnikassa';

					break;
				case Pronamic_WP_Pay_PaymentMethods::MISTER_CASH :
					$gateways[] = 'buckaroo';
					$gateways[] = 'icepay-ideal';
					$gateways[] = 'ogone-orderstandard';
					$gateways[] = 'mollie';
					$gateways[] = 'qantani-mollie';
					$gateways[] = 'rabobank-omnikassa';
					$gateways[] = 'sisow-ideal';
					$gateways[] = 'pay_nl';

					break;
				case Pronamic_WP_Pay_PaymentMethods::SOFORT :
					$gateways[] = 'mollie';
					$gateways[] = 'qantani-mollie';

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

		$options = array( __( '— Select Configuration —', 'pronamic_ideal' ) );

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

	public function gateway_integrations( $integrations ) {
		// ABN AMRO
		$integrations[] = 'Pronamic_WP_Pay_Gateways_AbnAmro_IDealEasy_Integration';
		$integrations[] = 'Pronamic_WP_Pay_Gateways_AbnAmro_IDealOnlyKassa_Integration';
		$integrations[] = 'Pronamic_WP_Pay_Gateways_AbnAmro_IDealZelfbouwV3_Integration';
		$integrations[] = 'Pronamic_WP_Pay_Gateways_AbnAmro_Internetkassa_Integration';
		// Buckaroo
		$integrations[] = 'Pronamic_WP_Pay_Gateways_Buckaroo_Integration';
		// Deutsche Bank
		$integrations[] = 'Pronamic_WP_Pay_Gateways_DeutscheBank_IDealExpertV3_Integration';
		$integrations[] = 'Pronamic_WP_Pay_Gateways_DeutscheBank_IDealViaOgone_Integration';
		// Easy iDEAL
		$integrations[] = 'Pronamic_WP_Pay_Gateways_EasyIDeal_Integration';
		// Fibonacci ORANGE
		$integrations[] = 'Pronamic_WP_Pay_Gateways_FibonacciOrange_Integration';
		// ICEPAY
		$integrations[] = 'Pronamic_WP_Pay_Gateways_Icepay_Integration';
		// iDEAL Simulator
		$integrations[] = 'Pronamic_WP_Pay_Gateways_IDealSimulator_IDealAdvancedV3_Integration';
		// ING
		$integrations[] = 'Pronamic_WP_Pay_Gateways_ING_IDealAdvancedV3_Integration';
		$integrations[] = 'Pronamic_WP_Pay_Gateways_ING_IDealBasic_Integration';
		$integrations[] = 'Pronamic_WP_Pay_Gateways_ING_KassaCompleet_Integration';
		// Mollie
		$integrations[] = 'Pronamic_WP_Pay_Gateways_Mollie_Integration';
		$integrations[] = 'Pronamic_WP_Pay_Gateways_Mollie_IDeal_Integration';
		$integrations[] = 'Pronamic_WP_Pay_Gateways_Mollie_IDealBasic_Integration';
		// MultiSafepay
		$integrations[] = 'Pronamic_WP_Pay_Gateways_MultiSafepay_Connect_Integration';
		// Ingenico/Ogone
		$integrations[] = 'Pronamic_WP_Pay_Gateways_Ogone_DirectLink_Integration';
		$integrations[] = 'Pronamic_WP_Pay_Gateways_Ogone_OrderStandard_Integration';
		// OmniKassa
		$integrations[] = 'Pronamic_WP_Pay_Gateways_OmniKassa_Integration';
		// Pay.nl
		$integrations[] = 'Pronamic_WP_Pay_Gateways_PayNL_Integration';
		// Paytor
		$integrations[] = 'Pronamic_WP_Pay_Gateways_Paytor_Integration';
		// Postcode.nl
		$integrations[] = 'Pronamic_WP_Pay_Gateways_PostcodeIDeal_Integration';
		// Qantani
		$integrations[] = 'Pronamic_WP_Pay_Gateways_Qantani_Mollie_Integration';
		$integrations[] = 'Pronamic_WP_Pay_Gateways_Qantani_Integration';
		// Rabobank
		$integrations[] = 'Pronamic_WP_Pay_Gateways_Rabobank_IDealAdvancedV3_Integration';
		// Sisow
		$integrations[] = 'Pronamic_WP_Pay_Gateways_Sisow_Integration';
		$integrations[] = 'Pronamic_WP_Pay_Gateways_Sisow_IDealBasic_Integration';
		// TargetPay
		$integrations[] = 'Pronamic_WP_Pay_Gateways_TargetPay_Integration';

		return $integrations;
	}

	public static function get_gateway( $config_id ) {
		$config = new Pronamic_WP_Pay_Config( $config_id );

		$config = Pronamic_WP_Pay_ConfigProvider::get_config( $config->gateway_id, $config_id );

		$gateway = Pronamic_WP_Pay_GatewayFactory::create( $config );

		return $gateway;
	}

	public static function start( $config_id, Pronamic_WP_Pay_Gateway $gateway, Pronamic_Pay_PaymentDataInterface $data, $payment_method = null ) {
		$payment = self::create_payment( $config_id, $gateway, $data, $payment_method );

		if ( $payment ) {
			$gateway->start( $payment );

			pronamic_wp_pay_update_payment( $payment );

			$gateway->payment( $payment );
		}

		return $payment;
	}

	public static function create_payment( $config_id, $gateway, $data, $payment_method = null ) {
		$payment = null;

		$result = wp_insert_post( array(
			'post_type'   => 'pronamic_payment',
			'post_title'  => sprintf( __( 'Payment for %s', 'pronamic_ideal' ), $data->get_title() ),
			'post_status' => 'payment_pending',
		), true );

		if ( ! is_wp_error( $result ) ) {
			// @todo what if result is error
			$post_id = $result;

			// @todo temporary solution for WPMU DEV
			$data->payment_post_id = $post_id;

			// Payment
			$payment = new Pronamic_WP_Pay_Payment( $post_id );
			$payment->config_id     = $config_id;
			$payment->key           = uniqid( 'pay_' );
			$payment->order_id      = $data->get_order_id();
			$payment->currency      = $data->get_currency();
			$payment->amount        = $data->get_amount();
			$payment->language      = $data->get_language();
			$payment->locale        = $data->get_language_and_country();
			$payment->entrance_code = $data->get_entrance_code();
			$payment->description   = $data->get_description();
			$payment->source        = $data->get_source();
			$payment->source_id     = $data->get_source_id();
			$payment->email         = $data->get_email();
			$payment->status        = null;
			$payment->method        = $payment_method;
			$payment->issuer        = $data->get_issuer_id();
			$payment->customer_name = $data->get_customer_name();
			$payment->address       = $data->get_address();
			$payment->zip           = $data->get_zip();
			$payment->city          = $data->get_city();
			$payment->country       = $data->get_country();
			$payment->telephone_number = $data->get_telephone_number();

			// Meta
			$prefix = '_pronamic_payment_';

			$meta = array(
				$prefix . 'config_id'               => $payment->config_id,
				$prefix . 'key'                     => $payment->key,
				$prefix . 'order_id'                => $payment->order_id,
				$prefix . 'currency'                => $payment->currency,
				$prefix . 'amount'                  => $payment->amount,
				$prefix . 'method'                  => $payment->method,
				$prefix . 'issuer'                  => $payment->issuer,
				$prefix . 'expiration_period'       => null,
				$prefix . 'language'                => $payment->language,
				$prefix . 'locale'                  => $payment->locale,
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
				$prefix . 'customer_name'           => $payment->customer_name,
				$prefix . 'address'                 => $payment->address,
				$prefix . 'zip'                     => $payment->zip,
				$prefix . 'city'                    => $payment->city,
				$prefix . 'country'                 => $payment->country,
				$prefix . 'telephone_number'        => $payment->telephone_number,
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
