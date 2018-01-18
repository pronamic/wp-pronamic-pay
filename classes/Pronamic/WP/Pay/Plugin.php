<?php

/**
 * Title: WordPress iDEAL plugin
 * Description:
 * Copyright: Copyright (c) 2005 - 2018
 * Company: Pronamic
 *
 * @author Remco Tolsma
 * @version 4.5.3
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
		self::$file = $file;
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
		\Pronamic\WordPress\Pay\Extensions\EasyDigitalDownloads\Extension::bootstrap();
		Pronamic_WP_Pay_Extensions_IThemesExchange_Extension::bootstrap();
		Pronamic_WP_Pay_Extensions_MemberPress_Extension::bootstrap();
		Pronamic_WP_Pay_Extensions_FormidableForms_Extension::bootstrap();
		Pronamic_WP_Pay_Extensions_RCP_Extension::bootstrap();

		// Data Stores
		$this->payments_data_store      = new \Pronamic\WordPress\Pay\Payments\PaymentsDataStoreCPT();
		$this->subscriptions_data_store = new \Pronamic\WordPress\Pay\Subscriptions\SubscriptionsDataStoreCPT();

		// Post Types
		$this->post_types = new Pronamic_WP_Pay_PostTypes();
		$this->gateway_post_type = new Pronamic_WP_Pay_GatewayPostType();

		// License
		$this->license_manager = new Pronamic_WP_Pay_LicenseManager();

		// Modules
		$this->forms_module         = new \Pronamic\WordPress\Pay\Forms\FormsModule( $this );
		$this->subscriptions_module = new \Pronamic\WordPress\Pay\Subscriptions\SubscriptionsModule( $this );

		// Payment Status Checker
		$this->payment_status_checker = new Pronamic_WP_Pay_PaymentStatusChecker();

		// Google Analytics Ecommerce
		$this->google_analytics_ecommerce = new Pronamic_WP_Pay_GoogleAnalyticsEcommerce();

		// Admin
		if ( is_admin() ) {
			$this->admin = new \Pronamic\WordPress\Pay\Admin\AdminModule( $this );
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

		// Exclude payment and subscription notes
		add_filter( 'comments_clauses', array( $this, 'exclude_comment_notes' ), 10, 2 );

		// Payment redirect URL
		add_filter( 'pronamic_payment_redirect_url', array( $this, 'payment_redirect_url' ), 5, 2 );

		// Plugin locale
		add_filter( 'plugin_locale', array( $this, 'plugin_locale' ), 10, 2 );

		// If WordPress is loaded check on returns and maybe redirect requests
		add_action( 'wp_loaded', array( $this, 'handle_returns' ) );
		add_action( 'wp_loaded', array( $this, 'maybe_redirect' ) );
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
	 * Comments clauses
	 *
	 * @param array $clauses
	 * @param WP_Comment_Query $query
	 * @return array
	 */
	public function exclude_comment_notes( $clauses, $query ) {
		$type = $query->query_vars['type'];

		// Ignore payment notes comments if it's not specifically requested
		if ( 'payment_note' !== $type ) {
			$clauses['where'] .= " AND comment_type != 'payment_note'";
		}

		return $clauses;
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
		if ( empty( $payment ) ) {
			return;
		}

		$gateway = Pronamic_WP_Pay_Plugin::get_gateway( $payment->config_id );

		if ( empty( $gateway ) ) {
			return;
		}

		$amount = $payment->get_amount();

		if ( empty( $amount ) ) {
			$payment->set_status( Pronamic_WP_Pay_Statuses::SUCCESS );
		} else {
			$gateway->update_status( $payment );

			if ( $gateway->has_error() ) {
				foreach ( $gateway->error->get_error_codes() as $code ) {
					$payment->add_note( sprintf( '%s: %s', $code, $gateway->error->get_error_message( $code ) ) );
				}
			}
		}

		global $pronamic_ideal;

		$pronamic_ideal->payments_data_store->update( $payment );

		if ( defined( 'DOING_CRON' ) && ( empty( $payment->status ) || Pronamic_WP_Pay_Gateways_IDealAdvancedV3_Status::OPEN === $payment->status ) ) {
			$can_redirect = false;
		}

		if ( $can_redirect ) {
			$url = $payment->get_return_redirect_url();

			wp_redirect( $url );

			exit;
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

			$redirect_message = $payment->get_meta( 'payment_redirect_message' );

			if ( ! empty( $redirect_message ) ) {
				$valid_key = false;

				if ( filter_has_var( INPUT_GET, 'key' ) ) {
					$key = filter_input( INPUT_GET, 'key', FILTER_SANITIZE_STRING );

					$valid_key = ( $key === $payment->key );
				}

				if ( ! $valid_key ) {
					wp_redirect( home_url() );

					exit;
				}

				// @see https://github.com/woothemes/woocommerce/blob/2.3.11/includes/class-wc-cache-helper.php
				// @see https://www.w3-edge.com/products/w3-total-cache/
				if ( ! defined( 'DONOTCACHEPAGE' ) ) {
					define( 'DONOTCACHEPAGE', true );
				}

				if ( ! defined( 'DONOTCACHEDB' ) ) {
					define( 'DONOTCACHEDB', true );
				}

				if ( ! defined( 'DONOTMINIFY' ) ) {
					define( 'DONOTMINIFY', true );
				}

				if ( ! defined( 'DONOTCDN' ) ) {
					define( 'DONOTCDN', true );
				}

				if ( ! defined( 'DONOTCACHEOBJECT' ) ) {
					define( 'DONOTCACHEOBJECT', true );
				}

				nocache_headers();

				include Pronamic_WP_Pay_Plugin::$dirname . '/views/redirect-message.php';

				exit;
			}

			if ( '' !== $payment->config_id ) {
				$gateway = Pronamic_WP_Pay_Plugin::get_gateway( $payment->config_id );

				if ( null !== $gateway && $gateway->is_html_form() ) {
					$gateway->start( $payment );

					$error = $gateway->get_error();

					if ( is_wp_error( $error ) ) {
						Pronamic_WP_Pay_Plugin::render_errors( $error );
					} else {
						$gateway->redirect( $payment );
					}
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
		$this->gateway_integrations = new Pronamic_WP_Pay_GatewayIntegrations();

		$this->register_gateway_integrations();

		// Maybes
		self::maybe_set_active_payment_methods();
	}

	private function register_gateway_integrations() {
		$integrations = array();

		// ABN AMRO iDEAL Easy
		$integration = new Pronamic_WP_Pay_Gateways_Ogone_OrderStandardEasy_Integration();
		$integration->set_id( 'abnamro-ideal-easy' );
		$integration->set_name( 'ABN AMRO - iDEAL Easy' );
		$integration->url           = 'https://internetkassa.abnamro.nl/';
		$integration->product_url   = 'https://www.abnamro.nl/nl/zakelijk/betalen/online-betalen/betaaloplossing/';
		$integration->dashboard_url = 'https://internetkassa.abnamro.nl/';
		$integration->provider      = 'abnamro';

		$integrations[ $integration->get_id() ] = $integration;

		// ABN AMRO - iDEAL Only Kassa
		$integration = new Pronamic_WP_Pay_Gateways_Ogone_OrderStandard_Integration();
		$integration->set_id( 'abnamro-ideal-only-kassa' );
		$integration->set_name( 'ABN AMRO - iDEAL Only Kassa' );
		$integration->url           = 'https://internetkassa.abnamro.nl/';
		$integration->product_url   = 'https://www.abnamro.nl/nl/zakelijk/betalen/online-betalen/betaaloplossing/';
		$integration->dashboard_url = 'https://internetkassa.abnamro.nl/';
		$integration->provider      = 'abnamro';

		$integrations[ $integration->get_id() ] = $integration;

		// ABN AMRO - Internetkassa
		$integration = new Pronamic_WP_Pay_Gateways_Ogone_OrderStandard_Integration();
		$integration->set_id( 'abnamro-internetkassa' );
		$integration->set_name( 'ABN AMRO - Internetkassa' );
		$integration->url           = 'https://internetkassa.abnamro.nl/';
		$integration->product_url   = 'https://www.abnamro.nl/nl/zakelijk/betalen/online-betalen/betaaloplossing/';
		$integration->dashboard_url = 'https://internetkassa.abnamro.nl/';
		$integration->provider      = 'abnamro';

		$integrations[ $integration->get_id() ] = $integration;

		// ABN AMRO - iDEAL Zelfbouw (v3)
		$integration = new Pronamic_WP_Pay_Gateways_IDealAdvancedV3_Integration();
		$integration->set_id( 'abnamro-ideal-zelfbouw-v3' );
		$integration->set_name( 'ABN AMRO - iDEAL Zelfbouw (v3)' );
		$integration->url           = 'https://abnamro.ideal-payment.de/';
		$integration->product_url   = 'https://www.abnamro.nl/nl/zakelijk/betalen/online-betalen/betaaloplossing/';
		$integration->dashboard_url = array(
			'test' => 'https://abnamro-test.ideal-payment.de/',
			'live' => 'https://abnamro.ideal-payment.de/',
		);
		$integration->provider      = 'abnamro';

		$integrations[ $integration->get_id() ] = $integration;

		// Buckaroo
		$integration = new Pronamic_WP_Pay_Gateways_Buckaroo_Integration();

		$integrations[ $integration->get_id() ] = $integration;

		// Deutsche Bank - iDEAL via Ogone
		$integration = new Pronamic_WP_Pay_Gateways_Ogone_OrderStandardEasy_Integration();
		$integration->set_id( 'deutschebank-ideal-via-ogone' );
		$integration->set_name( 'Deutsche Bank - iDEAL via Ogone' );
		$integration->product_url   = 'https://www.deutschebank.nl/nl/content/producten_en_services_commercial_banking_cash_management_betalen_ideal.html';
		$integration->provider      = 'deutschebank';

		$integrations[ $integration->get_id() ] = $integration;

		// Deutsche Bank - iDEAL Expert (v3)
		$integration = new Pronamic_WP_Pay_Gateways_IDealAdvancedV3_Integration();
		$integration->set_id( 'deutschebank-ideal-expert-v3' );
		$integration->set_name( 'Deutsche Bank - iDEAL Expert (v3)' );
		$integration->product_url   = 'https://www.deutschebank.nl/nl/content/producten_en_services_commercial_banking_cash_management_betalen_ideal.html';
		$integration->dashboard_url = array(
			'test' => 'https://myideal.test.db.com/',
			'live' => 'https://myideal.db.com/',
		);
		$integration->provider      = 'deutschebank';

		$integrations[ $integration->get_id() ] = $integration;

		// EMS e-Commerce Gateway
		$integration = new Pronamic_WP_Pay_Gateways_EMS_ECommerce_Integration();

		$integrations[ $integration->get_id() ] = $integration;

		// Fibonacci ORANGE
		$integration = new Pronamic_WP_Pay_Gateways_Icepay_Integration();
		$integration->set_id( 'fibonacciorange' );
		$integration->set_name( 'Fibonacci ORANGE' );
		$integration->product_url = 'http://www.fibonacciorange.nl/';
		$integration->provider    = 'fibonacciorange';

		$integrations[ $integration->get_id() ] = $integration;

		// ICEPAY
		$integration = new Pronamic_WP_Pay_Gateways_Icepay_Integration();

		$integrations[ $integration->get_id() ] = $integration;

		// iDEAL Simulator - iDEAL Lite / Basic
		$integration = new Pronamic_WP_Pay_Gateways_IDealBasic_Integration();
		$integration->set_id( 'ideal-simulator-ideal-basic' );
		$integration->set_name( 'iDEAL Simulator - iDEAL Lite / Basic' );
		$integration->provider = 'ideal-simulator';

		$integrations[ $integration->get_id() ] = $integration;

		// iDEAL Simulator - iDEAL Professional / Advanced / Zelfbouw (v3)
		$integration = new Pronamic_WP_Pay_Gateways_IDealAdvancedV3_Integration();
		$integration->set_id( 'ideal-simulator-ideal-advanced-v3' );
		$integration->set_name( 'iDEAL Simulator - iDEAL Professional / Advanced / Zelfbouw (v3)' );
		$integration->provider    = 'ideal-simulator';
		$integration->product_url = 'https://www.ideal-checkout.nl/support/ideal-simulator';

		$integrations[ $integration->get_id() ] = $integration;

		// ING - iDEAL Basic
		$integration = new Pronamic_WP_Pay_Gateways_IDealBasic_Integration();
		$integration->set_id( 'ing-ideal-basic' );
		$integration->set_name( 'ING - iDEAL Basic' );
		$integration->provider      = 'ing';
		$integration->product_url   = 'https://www.ing.nl/zakelijk/betalen/geld-ontvangen/ideal/';
		$integration->dashboard_url = array(
			'test' => 'https://idealtest.secure-ing.com/',
			'live' => 'https://ideal.secure-ing.com/',
		);

		$integrations[ $integration->get_id() ] = $integration;

		// ING - iDEAL Advanced (v3)
		$integration = new Pronamic_WP_Pay_Gateways_IDealAdvancedV3_Integration();
		$integration->set_id( 'ing-ideal-advanced-v3' );
		$integration->set_name( 'ING - iDEAL Advanced (v3)' );
		$integration->provider      = 'ing';
		$integration->product_url   = 'https://www.ing.nl/zakelijk/betalen/geld-ontvangen/ideal/';
		$integration->dashboard_url = array(
			'test' => 'https://idealtest.secure-ing.com/',
			'live' => 'https://ideal.secure-ing.com/',
		);

		$integrations[ $integration->get_id() ] = $integration;

		// ING - Kassa Compleet
		$integration = new Pronamic_WP_Pay_Gateways_ING_KassaCompleet_Integration();

		$integrations[ $integration->get_id() ] = $integration;

		// Mollie
		$integration = new \Pronamic\WordPress\Pay\Gateways\Mollie\Integration();

		$integrations[ $integration->get_id() ] = $integration;

		// Mollie - iDEAL
		$integration = new Pronamic_WP_Pay_Gateways_Mollie_IDeal_Integration();

		$integrations[ $integration->get_id() ] = $integration;

		// Mollie - iDEAL Basic
		$integration = new Pronamic_WP_Pay_Gateways_IDealBasic_Integration();
		$integration->set_id( 'mollie-ideal-basic' );
		$integration->set_name( 'Mollie - iDEAL Basic' );
		$integration->dashboard_url = 'http://www.mollie.nl/beheer/';
		$integration->provider      = 'mollie';
		$integration->deprecated    = true;

		$integrations[ $integration->get_id() ] = $integration;

		// MultiSafepay
		$integration = new Pronamic_WP_Pay_Gateways_MultiSafepay_Connect_Integration();

		$integrations[ $integration->get_id() ] = $integration;

		// Ogone - DirectLink
		$integration = new Pronamic_WP_Pay_Gateways_Ogone_DirectLink_Integration();

		$integrations[ $integration->get_id() ] = $integration;

		// Ogone - OrderStandard
		$integration = new Pronamic_WP_Pay_Gateways_Ogone_OrderStandard_Integration();

		$integrations[ $integration->get_id() ] = $integration;

		// OmniKassa
		$integration = new Pronamic_WP_Pay_Gateways_OmniKassa_Integration();

		$integrations[ $integration->get_id() ] = $integration;

		// OmniKassa 2.0
		$integration = new \Pronamic\WordPress\Pay\Gateways\OmniKassa2\Integration();

		$integrations[ $integration->get_id() ] = $integration;

		// Pay.nl
		$integration = new Pronamic_WP_Pay_Gateways_PayNL_Integration();

		$integrations[ $integration->get_id() ] = $integration;

		// Paytor
		$integration = new \Pronamic\WordPress\Pay\Gateways\Mollie\Integration();
		$integration->set_id( 'paytor' );
		$integration->set_name( 'Paytor' );
		$integration->url           = 'http://paytor.com/';
		$integration->product_url   = 'http://paytor.com/';
		$integration->provider      = 'paytor';

		$integrations[ $integration->get_id() ] = $integration;

		// Postcode iDEAL
		$integration = new Pronamic_WP_Pay_Gateways_IDealAdvancedV3_Integration();
		$integration->set_id( 'postcode-ideal' );
		$integration->set_name( 'Postcode iDEAL' );
		$integration->provider      = 'postcode.nl';
		$integration->product_url   = 'https://services.postcode.nl/ideal';
		$integration->dashboard_url = 'https://services.postcode.nl/ideal';

		$integrations[ $integration->get_id() ] = $integration;

		// Qantani (new platform)
		$integration = new \Pronamic\WordPress\Pay\Gateways\Mollie\Integration();
		$integration->set_id( 'qantani-mollie' );
		$integration->set_name( __( 'Qantani (new platform)', 'pronamic_ideal' ) );
		$integration->url           = 'https://www.qantani.com/';
		$integration->product_url   = 'https://www.qantani.com/tarieven/';
		$integration->dashboard_url = 'https://www.qantani.eu/';
		$integration->provider      = 'qantani';

		$integrations[ $integration->get_id() ] = $integration;

		// Rabobank - iDEAL Professional (v3)
		$integration = new Pronamic_WP_Pay_Gateways_IDealAdvancedV3_Integration();
		$integration->set_id( 'rabobank-ideal-professional-v3' );
		$integration->set_name( 'Rabobank - iDEAL Professional (v3)' );
		$integration->provider      = 'rabobank';
		$integration->product_url   = 'https://www.rabobank.nl/bedrijven/betalen/geld-ontvangen/ideal-professional/';
		$integration->dashboard_url = array(
			'test' => 'https://idealtest.rabobank.nl/',
			'live' => 'https://ideal.rabobank.nl/',
		);

		$integrations[ $integration->get_id() ] = $integration;

		// Sisow
		$integration = new Pronamic_WP_Pay_Gateways_Sisow_Integration();

		$integrations[ $integration->get_id() ] = $integration;

		// Sisow - iDEAL Basic
		$integration = new Pronamic_WP_Pay_Gateways_IDealBasic_Integration();
		$integration->set_id( 'sisow-ideal-basic' );
		$integration->set_name( 'Sisow - iDEAL Basic' );
		$integration->url           = 'https://www.sisow.nl/';
		$integration->dashboard_url = 'https://www.sisow.nl/Sisow/iDeal/Login.aspx';
		$integration->provider      = 'sisow';
		$integration->deprecated    = true;

		$integrations[ $integration->get_id() ] = $integration;

		// TargetPay
		$integration = new Pronamic_WP_Pay_Gateways_TargetPay_Integration();

		$integrations[ $integration->get_id() ] = $integration;

		// Gateway integrations
		$this->gateway_integrations->integrations = $integrations;

		// Register config providers
		foreach ( $integrations as $integration ) {
			Pronamic_WP_Pay_ConfigProvider::register( $integration->get_id(), $integration->get_config_factory_class() );
		}
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

		if ( 'nl_BE' === $locale ) {
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

	public static function get_subscription_states() {
		return array(
			'subscr_pending'   => _x( 'Pending', 'Subscription status', 'pronamic_ideal' ),
			'subscr_cancelled' => _x( 'Cancelled', 'Subscription status', 'pronamic_ideal' ),
			'subscr_expired'   => _x( 'Expired', 'Subscription status', 'pronamic_ideal' ),
			'subscr_failed'    => _x( 'Failed', 'Subscription status', 'pronamic_ideal' ),
			'subscr_active'    => _x( 'Active', 'Subscription status', 'pronamic_ideal' ),
			'subscr_completed' => _x( 'Completed', 'Subscription status', 'pronamic_ideal' ),
		);
	}

	//////////////////////////////////////////////////

	/**
	 * Get default error message
	 *
	 * @return string
	 */
	public static function get_default_error_message() {
		return __( 'Something went wrong with the payment. Please try again later or pay another way.', 'pronamic_ideal' );
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
				case Pronamic_WP_Pay_PaymentMethods::ALIPAY :
					$gateways[] = 'multisafepay-connect';

					break;
				case Pronamic_WP_Pay_PaymentMethods::BUNQ :
					$gateways[] = 'sisow-ideal';

					break;
				case Pronamic_WP_Pay_PaymentMethods::BANCONTACT :
				case Pronamic_WP_Pay_PaymentMethods::MISTER_CASH :
					$gateways[] = 'buckaroo';
					$gateways[] = 'ems-ecommerce';
					$gateways[] = 'icepay-ideal';
					$gateways[] = 'ogone-orderstandard';
					$gateways[] = 'mollie';
					$gateways[] = 'multisafepay-connect';
					$gateways[] = 'qantani-mollie';
					$gateways[] = 'rabobank-omnikassa';
					$gateways[] = 'rabobank-omnikassa-2';
					$gateways[] = 'sisow-ideal';
					$gateways[] = 'pay_nl';
					$gateways[] = 'ing-kassa-compleet';

					break;
				case Pronamic_WP_Pay_PaymentMethods::BELFIUS :
					$gateways[] = 'mollie';
					$gateways[] = 'multisafepay-connect';

					break;
				case Pronamic_WP_Pay_PaymentMethods::BANK_TRANSFER :
					$gateways[] = 'ing-kassa-compleet';
					$gateways[] = 'mollie';
					$gateways[] = 'multisafepay-connect';
					$gateways[] = 'sisow-ideal';

					break;
				case Pronamic_WP_Pay_PaymentMethods::CREDIT_CARD :
					$gateways[] = 'buckaroo';
					$gateways[] = 'ems-ecommerce';
					$gateways[] = 'icepay-ideal';
					$gateways[] = 'ing-kassa-compleet';
					$gateways[] = 'mollie';
					$gateways[] = 'qantani-mollie';
					$gateways[] = 'ogone-directlink';
					$gateways[] = 'ogone-orderstandard';
					$gateways[] = 'rabobank-omnikassa';
					$gateways[] = 'rabobank-omnikassa-2';
					$gateways[] = 'sisow-ideal';

					break;
				case Pronamic_WP_Pay_PaymentMethods::DIRECT_DEBIT_BANCONTACT :
					$gateways[] = 'mollie';
					$gateways[] = 'qantani-mollie';

					break;
				case Pronamic_WP_Pay_PaymentMethods::DIRECT_DEBIT_IDEAL :
					$gateways[] = 'mollie';
					$gateways[] = 'qantani-mollie';

					break;
				case Pronamic_WP_Pay_PaymentMethods::DIRECT_DEBIT_SOFORT :
					$gateways[] = 'mollie';
					$gateways[] = 'qantani-mollie';

					break;
				case Pronamic_WP_Pay_PaymentMethods::GIROPAY :
					$gateways[] = 'multisafepay-connect';

					break;
				case Pronamic_WP_Pay_PaymentMethods::IDEALQR :
					$gateways[] = 'multisafepay-connect';

					break;
				case Pronamic_WP_Pay_PaymentMethods::KBC :
					$gateways[] = 'mollie';
					$gateways[] = 'multisafepay-connect';

					break;
				case Pronamic_WP_Pay_PaymentMethods::MAESTRO :
					$gateways[] = 'ems-ecommerce';
					$gateways[] = 'rabobank-omnikassa';
					$gateways[] = 'rabobank-omnikassa-2';

					break;
				case Pronamic_WP_Pay_PaymentMethods::PAYCONIQ :
					$gateways[] = 'ing-kassa-compleet';

					break;
				case Pronamic_WP_Pay_PaymentMethods::PAYPAL :
					$gateways[] = 'buckaroo';
					$gateways[] = 'ems-ecommerce';
					$gateways[] = 'ing-kassa-compleet';
					$gateways[] = 'mollie';
					$gateways[] = 'multisafepay-connect';
					$gateways[] = 'qantani-mollie';
					$gateways[] = 'sisow-ideal';

					break;
				case Pronamic_WP_Pay_PaymentMethods::SOFORT :
					$gateways[] = 'ems-ecommerce';
					$gateways[] = 'mollie';
					$gateways[] = 'multisafepay-connect';
					$gateways[] = 'qantani-mollie';
					$gateways[] = 'sisow-ideal';

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

		$query = new WP_Query( $args );

		$options = array( __( '— Select Configuration —', 'pronamic_ideal' ) );

		foreach ( $query->posts as $post ) {
			$id = $post->ID;

			$options[ $id ] = sprintf(
				'%s (%s)',
				get_the_title( $id ),
				get_post_meta( $id, '_pronamic_gateway_mode', true )
			);
		}

		return $options;
	}

	//////////////////////////////////////////////////

	/**
	 * Maybe set active payment methods option.
	 *
	 * @since unreleased
	 *
	 * @param void
	 */
	public static function maybe_set_active_payment_methods() {
		$active_methods = get_option( 'pronamic_pay_active_payment_methods' );

		if ( is_array( $active_methods ) ) {
			return;
		}

		Pronamic_WP_Pay_PaymentMethods::update_active_payment_methods();
	}

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

	public static function get_gateway( $config_id ) {
		$gateway_id = get_post_meta( $config_id, '_pronamic_gateway_id', true );
		$mode       = get_post_meta( $config_id, '_pronamic_gateway_mode', true );
		$is_utf8    = strcasecmp( get_bloginfo( 'charset' ), 'UTF-8' ) === 0;

		$config = Pronamic_WP_Pay_ConfigProvider::get_config( $gateway_id, $config_id );

		switch ( $gateway_id ) {
			case 'abnamro-ideal-easy' :
			case 'abnamro-ideal-only-kassa' :
			case 'abnamro-internetkassa' :
				$config->form_action_url = sprintf(
					'https://internetkassa.abnamro.nl/ncol/%s/orderstandard%s.asp',
					'test' === $mode ? 'test' : 'prod',
					$is_utf8 ? '_utf8' : ''
				);

				break;
			case 'abnamro-ideal-zelfbouw-v3' :
				$config->payment_server_url = 'https://abnamro.ideal-payment.de/ideal/iDEALv3';

				if ( 'test' === $mode ) {
					$config->payment_server_url = 'https://abnamro-test.ideal-payment.de/ideal/iDEALv3';
				}

				$config->certificates = array();

				break;
			case 'deutschebank-ideal-expert-v3' :
				$config->payment_server_url = 'https://myideal.db.com/ideal/iDealv3';

				$config->certificates = array();

				break;
			case 'ideal-simulator-ideal-basic' :
				$config->url = 'https://www.ideal-simulator.nl/lite/';

				break;
			case 'ideal-simulator-ideal-advanced-v3' :
				$config->payment_server_url = 'https://www.ideal-checkout.nl/simulator/';

				$config->certificates = array();

				break;
			case 'ing-ideal-basic' :
				$config->url = 'https://ideal.secure-ing.com/ideal/mpiPayInitIng.do';

				if ( 'test' === $mode ) {
					$config->url = 'https://idealtest.secure-ing.com/ideal/mpiPayInitIng.do';
				}

				break;
			case 'ing-ideal-advanced-v3' :
				$config->payment_server_url = 'https://ideal.secure-ing.com/ideal/iDEALv3';

				if ( 'test' === $mode ) {
					$config->payment_server_url = 'https://idealtest.secure-ing.com/ideal/iDEALv3';
				}

				$config->certificates = array();

				break;
			case 'mollie-ideal-basic' :
				$config->url = 'https://secure.mollie.nl/xml/idealAcquirer/lite/';

				if ( 'test' === $mode ) {
					$config->url = 'https://secure.mollie.nl/xml/idealAcquirer/testmode/lite/';
				}

				break;
			case 'postcode-ideal' :
				$config->payment_server_url = 'https://ideal.postcode.nl/ideal';

				if ( 'test' === $mode ) {
					$config->payment_server_url = 'https://ideal-test.postcode.nl/ideal';
				}

				$config->certificates = array();

				break;
			case 'rabobank-ideal-professional-v3' :
				$config->payment_server_url = 'https://ideal.rabobank.nl/ideal/iDEALv3';

				if ( 'test' === $mode ) {
					$config->payment_server_url = 'https://idealtest.rabobank.nl/ideal/iDEALv3';
				}

				$config->certificates = array();

				break;
			case 'sisow-ideal-basic' :
				$config->url = 'https://www.sisow.nl/Sisow/iDeal/IssuerHandler.ashx';

				if ( 'test' === $mode ) {
					$config->url = 'https://www.sisow.nl/Sisow/iDeal/IssuerHandler.ashx/test';
				}

				break;
		}

		$gateway = Pronamic_WP_Pay_GatewayFactory::create( $config );

		return $gateway;
	}

	public static function start( $config_id, Pronamic_WP_Pay_Gateway $gateway, Pronamic_Pay_PaymentDataInterface $data, $payment_method = null ) {
		$payment = new Pronamic_WP_Pay_Payment();

		$payment->title               = sprintf( __( 'Payment for %s', 'pronamic_ideal' ), $data->get_title() );
		$payment->user_id             = $data->get_user_id();
		$payment->config_id           = $config_id;
		$payment->key                 = uniqid( 'pay_' );
		$payment->order_id            = $data->get_order_id();
		$payment->currency            = $data->get_currency();
		$payment->amount              = $data->get_amount();
		$payment->language            = $data->get_language();
		$payment->locale              = $data->get_language_and_country();
		$payment->entrance_code       = $data->get_entrance_code();
		$payment->description         = $data->get_description();
		$payment->source              = $data->get_source();
		$payment->source_id           = $data->get_source_id();
		$payment->email               = $data->get_email();
		$payment->status              = null;
		$payment->method              = $payment_method;
		$payment->issuer              = $data->get_issuer( $payment_method );
		$payment->first_name          = $data->get_first_name();
		$payment->last_name           = $data->get_last_name();
		$payment->customer_name       = $data->get_customer_name();
		$payment->address             = $data->get_address();
		$payment->zip                 = $data->get_zip();
		$payment->city                = $data->get_city();
		$payment->country             = $data->get_country();
		$payment->telephone_number    = $data->get_telephone_number();
		$payment->analytics_client_id = $data->get_analytics_client_id();
		$payment->recurring           = $data->get_recurring();
		$payment->subscription        = $data->get_subscription();
		$payment->subscription_id     = $data->get_subscription_id();
		$payment->set_credit_card( $data->get_credit_card() );

		global $pronamic_ideal;

		$pronamic_ideal->payments_data_store->create( $payment );

		$gateway->start( $payment );

		if ( $gateway->has_error() ) {
			foreach ( $gateway->error->get_error_codes() as $code ) {
				$payment->add_note( sprintf( '%s: %s', $code, $gateway->error->get_error_message( $code ) ) );
			}
		}

		$pronamic_ideal->payments_data_store->update( $payment );		

		$gateway->payment( $payment );

		if ( $gateway->supports( 'payment_status_request' ) ) {
			Pronamic_WP_Pay_PaymentStatusChecker::schedule_event( $payment );
		}

		return $payment;
	}
}
