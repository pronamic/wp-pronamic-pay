<?php
/**
 * Plugin
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

namespace Pronamic\WordPress\Pay;

use Pronamic\WordPress\Pay\Core\Gateway;
use Pronamic\WordPress\Pay\Core\PaymentMethods;
use Pronamic\WordPress\Pay\Core\Statuses;
use Pronamic\WordPress\Pay\Payments\Payment;
use Pronamic\WordPress\Pay\Payments\PaymentDataInterface;
use Pronamic\WordPress\Pay\Payments\PaymentPostType;
use Pronamic\WordPress\Pay\Subscriptions\SubscriptionPostType;
use WP_Query;

/**
 * Title: WordPress iDEAL plugin
 *
 * @author Remco Tolsma
 * @version 4.5.3
 * @since 1.0.0
 */
class Plugin {
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

	/**
	 * The timezone
	 *
	 * @var string
	 */
	const TIMEZONE = 'UTC';

	/**
	 * Instance.
	 *
	 * @var Plugin
	 */
	protected static $instance = null;

	/**
	 * Instance.
	 *
	 * @param string $file The plugin file.
	 */
	public static function instance( $file = null ) {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			// Backward compatibility.
			self::$file    = $file;
			self::$dirname = dirname( $file );
		}

		return self::$instance;
	}

	/**
	 * Construct and initialize an Pronamic Pay plugin object
	 */
	public function __construct() {
		// Bootstrap the add-ons.
		Extensions\Charitable\Extension::bootstrap();
		Extensions\Give\Extension::bootstrap();
		Extensions\WooCommerce\Extension::bootstrap();
		Extensions\GravityForms\Extension::bootstrap();
		Extensions\Shopp\Extension::bootstrap();
		Extensions\Jigoshop\Extension::bootstrap();
		Extensions\WPeCommerce\Extension::bootstrap();
		Extensions\ClassiPress\Extension::bootstrap();
		Extensions\EventEspressoLegacy\Extension::bootstrap();
		Extensions\EventEspresso\Extension::bootstrap();
		Extensions\AppThemes\Extension::bootstrap();
		Extensions\S2Member\Extension::bootstrap();
		Extensions\Membership\Extension::bootstrap();
		Extensions\EasyDigitalDownloads\Extension::bootstrap();
		Extensions\IThemesExchange\Extension::bootstrap();
		Extensions\MemberPress\Extension::bootstrap();
		Extensions\FormidableForms\Extension::bootstrap();
		Extensions\RestrictContentPro\Extension::bootstrap();

		// Settings.
		$this->settings = new Settings( $this );

		// Data Stores.
		$this->payments_data_store      = new Payments\PaymentsDataStoreCPT();
		$this->subscriptions_data_store = new Subscriptions\SubscriptionsDataStoreCPT();

		// Post Types.
		$this->gateway_post_type      = new GatewayPostType();
		$this->payment_post_type      = new PaymentPostType();
		$this->subscription_post_type = new SubscriptionPostType();

		// License Manager.
		$this->license_manager = new LicenseManager( $this );

		// Modules.
		$this->forms_module         = new Forms\FormsModule( $this );
		$this->subscriptions_module = new Subscriptions\SubscriptionsModule( $this );

		// Payment Status Checker.
		$this->payment_status_checker = new Payments\StatusChecker();

		// Google Analytics Ecommerce.
		$this->google_analytics_ecommerce = new GoogleAnalyticsEcommerce();

		// Admin.
		if ( is_admin() ) {
			$this->admin = new Admin\AdminModule( $this );
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

		// Exclude payment and subscription notes.
		add_filter( 'comments_clauses', array( $this, 'exclude_comment_notes' ), 10, 2 );

		// Payment redirect URL.
		add_filter( 'pronamic_payment_redirect_url', array( $this, 'payment_redirect_url' ), 5, 2 );

		// Plugin locale.
		add_filter( 'plugin_locale', array( $this, 'plugin_locale' ), 10, 2 );

		// If WordPress is loaded check on returns and maybe redirect requests.
		add_action( 'wp_loaded', array( $this, 'handle_returns' ) );
		add_action( 'wp_loaded', array( $this, 'maybe_redirect' ) );
	}

	/**
	 * Get the version number of this plugin.
	 *
	 * @return string The version number of this plugin.
	 */
	public function get_version() {
		global $pronamic_pay_version;

		return $pronamic_pay_version;
	}

	/**
	 * Get plugin file path.
	 *
	 * @return string
	 */
	public function get_file() {
		return self::$file;
	}

	/**
	 * Get the plugin dir path.
	 *
	 * @return string
	 */
	public function get_plugin_dir_path() {
		return plugin_dir_path( $this->get_file() );
	}

	/**
	 * Comments clauses.
	 *
	 * @param array            $clauses Array with query clauses for the comments query.
	 * @param WP_Comment_Query $query   A WordPress comment query object.
	 *
	 * @return array
	 */
	public function exclude_comment_notes( $clauses, $query ) {
		$type = $query->query_vars['type'];

		// Ignore payment notes comments if it's not specifically requested.
		if ( 'payment_note' !== $type ) {
			$clauses['where'] .= " AND comment_type != 'payment_note'";
		}

		return $clauses;
	}

	/**
	 * Payment redirect URL filter.
	 *
	 * @param string  $url     A payment redirect URL.
	 * @param Payment $payment The payment to get a redirect URL for.
	 *
	 * @return string
	 */
	public function payment_redirect_url( $url, $payment ) {
		$page_id = null;

		switch ( $payment->status ) {
			case Core\Statuses::CANCELLED:
				$page_id = pronamic_pay_get_page_id( 'cancel' );

				break;
			case Core\Statuses::EXPIRED:
				$page_id = pronamic_pay_get_page_id( 'expired' );

				break;
			case Core\Statuses::FAILURE:
				$page_id = pronamic_pay_get_page_id( 'error' );

				break;
			case Core\Statuses::OPEN:
				$page_id = pronamic_pay_get_page_id( 'unknown' );

				break;
			case Core\Statuses::SUCCESS:
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

	/**
	 * Update payment.
	 *
	 * @param Payment $payment      The payment to update.
	 * @param bool    $can_redirect Flag to indicate if redirect is allowed after the payment update.
	 */
	public static function update_payment( $payment = null, $can_redirect = true ) {
		if ( empty( $payment ) ) {
			return;
		}

		$gateway = Plugin::get_gateway( $payment->config_id );

		if ( empty( $gateway ) ) {
			return;
		}

		$amount = $payment->get_amount();

		if ( empty( $amount ) ) {
			$payment->set_status( Core\Statuses::SUCCESS );
		} else {
			$gateway->update_status( $payment );

			if ( $gateway->has_error() ) {
				foreach ( $gateway->error->get_error_codes() as $code ) {
					$payment->add_note( sprintf( '%s: %s', $code, $gateway->error->get_error_message( $code ) ) );
				}
			}
		}

		// Update payment in data store.
		pronamic_pay_plugin()->payments_data_store->update( $payment );

		// Maybe redirect.
		if ( defined( 'DOING_CRON' ) && ( empty( $payment->status ) || Statuses::OPEN === $payment->status ) ) {
			$can_redirect = false;
		}

		if ( $can_redirect ) {
			$url = $payment->get_return_redirect_url();

			wp_redirect( $url );

			exit;
		}
	}

	/**
	 * Handle returns.
	 */
	public function handle_returns() {
		if ( ! filter_has_var( INPUT_GET, 'payment' ) ) {
			return;
		}

		$payment_id = filter_input( INPUT_GET, 'payment', FILTER_SANITIZE_NUMBER_INT );

		$payment = get_pronamic_payment( $payment_id );

		// Check if payment key is valid.
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

		// Check if we should redirect.
		$should_redirect = true;

		// Check if the request is an callback request.
		// Sisow gatway will extend callback requests with querystring "callback=true".
		if ( filter_has_var( INPUT_GET, 'callback' ) && filter_input( INPUT_GET, 'callback', FILTER_VALIDATE_BOOLEAN ) ) {
			$should_redirect = false;
		}

		// Check if the request is an notify request.
		// Sisow gatway will extend callback requests with querystring "notify=true".
		if ( filter_has_var( INPUT_GET, 'notify' ) && filter_input( INPUT_GET, 'notify', FILTER_VALIDATE_BOOLEAN ) ) {
			$should_redirect = false;
		}

		self::update_payment( $payment, $should_redirect );
	}

	/**
	 * Maybe redirect.
	 */
	public function maybe_redirect() {
		if ( ! filter_has_var( INPUT_GET, 'payment_redirect' ) ) {
			return;
		}

		$payment_id = filter_input( INPUT_GET, 'payment_redirect', FILTER_SANITIZE_NUMBER_INT );

		$payment = get_pronamic_payment( $payment_id );

		// HTML Answer.
		$html_answer = $payment->get_meta( 'ogone_directlink_html_answer' );

		if ( ! empty( $html_answer ) ) {
			echo $html_answer; // WPCS: XSS ok.

			exit;
		}

		$redirect_message = $payment->get_meta( 'payment_redirect_message' );

		if ( ! empty( $redirect_message ) ) {
			$key = filter_input( INPUT_GET, 'key', FILTER_SANITIZE_STRING );

			if ( $key !== $payment->key ) {
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

			include Plugin::$dirname . '/views/redirect-message.php';

			exit;
		}

		$gateway = Plugin::get_gateway( $payment->config_id );

		if ( $gateway && $gateway->is_html_form() ) {
			$gateway->start( $payment );

			$error = $gateway->get_error();

			if ( is_wp_error( $error ) ) {
				Plugin::render_errors( $error );
			} else {
				$gateway->redirect( $payment );
			}
		}

		if ( ! empty( $payment->action_url ) ) {
			wp_redirect( $payment->action_url );

			exit;
		}
	}

	/**
	 * Get number payments.
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

	/**
	 * Setup, creates or updates database tables. Will only run when version changes.
	 */
	public function plugins_loaded() {
		// Load plugin text domain.
		$rel_path = dirname( plugin_basename( self::$file ) ) . '/languages/';

		load_plugin_textdomain( 'pronamic_ideal', false, $rel_path );

		// Gateway Integrations.
		$integrations = new GatewayIntegrations();

		$this->gateway_integrations = $integrations->register_integrations();

		// Maybes.
		self::maybe_set_active_payment_methods();
	}

	/**
	 * Filter plugin locale.
	 *
	 * @param string $locale A WordPress locale identifier.
	 * @param string $domain A WordPress text domain indentifier.
	 *
	 * @return string
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

	/**
	 * Get payment states.
	 *
	 * @return array
	 */
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

	/**
	 * Get subscription states.
	 *
	 * @return array
	 */
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

	/**
	 * Get default error message.
	 *
	 * @return string
	 */
	public static function get_default_error_message() {
		return __( 'Something went wrong with the payment. Please try again later or pay another way.', 'pronamic_ideal' );
	}

	/**
	 * Get config select options.
	 *
	 * @param null|string $payment_method The gateway configuration options for the specified payment method.
	 *
	 * @return array
	 */
	public static function get_config_select_options( $payment_method = null ) {
		$args = array(
			'post_type' => 'pronamic_gateway',
			'nopaging'  => true,
		);

		if ( $payment_method ) {
			$gateways = array();

			switch ( $payment_method ) {
				case PaymentMethods::ALIPAY:
					$gateways = array( 'multisafepay-connect' );

					break;
				case PaymentMethods::BUNQ:
					$gateways = array( 'sisow-ideal' );

					break;
				case PaymentMethods::BANCONTACT:
				case PaymentMethods::MISTER_CASH:
					$gateways = array(
						'buckaroo',
						'ems-ecommerce',
						'icepay-ideal',
						'ogone-orderstandard',
						'mollie',
						'multisafepay-connect',
						'qantani-mollie',
						'rabobank-omnikassa',
						'rabobank-omnikassa-2',
						'sisow-ideal',
						'pay_nl',
						'ing-kassa-compleet',
					);

					break;
				case PaymentMethods::BELFIUS:
					$gateways = array(
						'mollie',
						'multisafepay-connect',
					);

					break;
				case PaymentMethods::BANK_TRANSFER:
					$gateways = array(
						'ing-kassa-compleet',
						'mollie',
						'multisafepay-connect',
						'sisow-ideal',
					);

					break;
				case PaymentMethods::CREDIT_CARD:
					$gateways = array(
						'buckaroo',
						'ems-ecommerce',
						'icepay-ideal',
						'ing-kassa-compleet',
						'mollie',
						'qantani-mollie',
						'ogone-directlink',
						'ogone-orderstandard',
						'rabobank-omnikassa',
						'rabobank-omnikassa-2',
						'sisow-ideal',
					);

					break;
				case PaymentMethods::DIRECT_DEBIT_BANCONTACT:
					$gateways = array(
						'mollie',
						'qantani-mollie',
					);

					break;
				case PaymentMethods::DIRECT_DEBIT_IDEAL:
					$gateways = array(
						'mollie',
						'qantani-mollie',
					);

					break;
				case PaymentMethods::DIRECT_DEBIT_SOFORT:
					$gateways = array(
						'mollie',
						'qantani-mollie',
					);

					break;
				case PaymentMethods::GIROPAY:
					$gateways = array( 'multisafepay-connect' );

					break;
				case PaymentMethods::IDEALQR:
					$gateways = array( 'multisafepay-connect' );

					break;
				case PaymentMethods::KBC:
					$gateways = array(
						'mollie',
						'multisafepay-connect',
					);

					break;
				case PaymentMethods::MAESTRO:
					$gateways = array(
						'ems-ecommerce',
						'rabobank-omnikassa',
						'rabobank-omnikassa-2',
					);

					break;
				case PaymentMethods::PAYCONIQ:
					$gateways = array( 'ing-kassa-compleet' );

					break;
				case PaymentMethods::PAYPAL:
					$gateways = array(
						'buckaroo',
						'ems-ecommerce',
						'ing-kassa-compleet',
						'mollie',
						'multisafepay-connect',
						'qantani-mollie',
						'sisow-ideal',
					);

					break;
				case PaymentMethods::SOFORT:
					$gateways = array(
						'ems-ecommerce',
						'mollie',
						'multisafepay-connect',
						'qantani-mollie',
						'sisow-ideal',
					);

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

	/**
	 * Maybe set active payment methods option.
	 *
	 * @since unreleased
	 */
	public static function maybe_set_active_payment_methods() {
		$active_methods = get_option( 'pronamic_pay_active_payment_methods' );

		if ( is_array( $active_methods ) ) {
			return;
		}

		PaymentMethods::update_active_payment_methods();
	}

	/**
	 * Render errors.
	 *
	 * @param array $errors An array with errors to render.
	 */
	public static function render_errors( $errors = array() ) {
		if ( ! is_array( $errors ) ) {
			$errors = array( $errors );
		}

		foreach ( $errors as $error ) {
			include Plugin::$dirname . '/views/error.php';
		}
	}

	/**
	 * Get gateway.
	 *
	 * @param string $config_id A gateway configuration ID.
	 *
	 * @return Gateway
	 */
	public static function get_gateway( $config_id ) {
		$gateway_id = get_post_meta( $config_id, '_pronamic_gateway_id', true );
		$mode       = get_post_meta( $config_id, '_pronamic_gateway_mode', true );
		$is_utf8    = strcasecmp( get_bloginfo( 'charset' ), 'UTF-8' ) === 0;

		$config = Core\ConfigProvider::get_config( $gateway_id, $config_id );

		switch ( $gateway_id ) {
			case 'abnamro-ideal-easy':
			case 'abnamro-ideal-only-kassa':
			case 'abnamro-internetkassa':
				$config->form_action_url = sprintf(
					'https://internetkassa.abnamro.nl/ncol/%s/orderstandard%s.asp',
					'test' === $mode ? 'test' : 'prod',
					$is_utf8 ? '_utf8' : ''
				);

				break;
			case 'abnamro-ideal-zelfbouw-v3':
				$config->payment_server_url = 'https://abnamro.ideal-payment.de/ideal/iDEALv3';

				if ( 'test' === $mode ) {
					$config->payment_server_url = 'https://abnamro-test.ideal-payment.de/ideal/iDEALv3';
				}

				$config->certificates = array();

				break;
			case 'deutschebank-ideal-expert-v3':
				$config->payment_server_url = 'https://myideal.db.com/ideal/iDealv3';

				$config->certificates = array();

				break;
			case 'ideal-simulator-ideal-basic':
				$config->url = 'https://www.ideal-simulator.nl/lite/';

				break;
			case 'ideal-simulator-ideal-advanced-v3':
				$config->payment_server_url = 'https://www.ideal-checkout.nl/simulator/';

				$config->certificates = array();

				break;
			case 'ing-ideal-basic':
				$config->url = 'https://ideal.secure-ing.com/ideal/mpiPayInitIng.do';

				if ( 'test' === $mode ) {
					$config->url = 'https://idealtest.secure-ing.com/ideal/mpiPayInitIng.do';
				}

				break;
			case 'ing-ideal-advanced-v3':
				$config->payment_server_url = 'https://ideal.secure-ing.com/ideal/iDEALv3';

				if ( 'test' === $mode ) {
					$config->payment_server_url = 'https://idealtest.secure-ing.com/ideal/iDEALv3';
				}

				$config->certificates = array();

				break;
			case 'mollie-ideal-basic':
				$config->url = 'https://secure.mollie.nl/xml/idealAcquirer/lite/';

				if ( 'test' === $mode ) {
					$config->url = 'https://secure.mollie.nl/xml/idealAcquirer/testmode/lite/';
				}

				break;
			case 'postcode-ideal':
				$config->payment_server_url = 'https://ideal.postcode.nl/ideal';

				if ( 'test' === $mode ) {
					$config->payment_server_url = 'https://ideal-test.postcode.nl/ideal';
				}

				$config->certificates = array();

				break;
			case 'rabobank-ideal-professional-v3':
				$config->payment_server_url = 'https://ideal.rabobank.nl/ideal/iDEALv3';

				if ( 'test' === $mode ) {
					$config->payment_server_url = 'https://idealtest.rabobank.nl/ideal/iDEALv3';
				}

				$config->certificates = array();

				break;
			case 'sisow-ideal-basic':
				$config->url = 'https://www.sisow.nl/Sisow/iDeal/IssuerHandler.ashx';

				if ( 'test' === $mode ) {
					$config->url = 'https://www.sisow.nl/Sisow/iDeal/IssuerHandler.ashx/test';
				}

				break;
		}

		$gateway = Core\GatewayFactory::create( $config );

		return $gateway;
	}

	/**
	 * Start a payment.
	 *
	 * @param string               $config_id      A gateway configuration ID.
	 * @param Gateway              $gateway        The gateway to start the payment at.
	 * @param PaymentDataInterface $data           A payment data interface object with all the required payment info.
	 * @param string|null          $payment_method The payment method to use to start the payment.
	 *
	 * @return Payment
	 */
	public static function start( $config_id, Gateway $gateway, PaymentDataInterface $data, $payment_method = null ) {
		$payment = new Payments\Payment();

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

		// User Agent (@see https://github.com/WordPress/WordPress/blob/4.9.4/wp-includes/comment.php#L1962-L1965).
		$payment->user_agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : null;

		// IP (@see https://github.com/WordPress/WordPress/blob/4.9.4/wp-includes/comment.php#L1957-L1960).
		$payment->user_ip = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : null;

		return self::start_payment( $payment, $gateway );
	}

	/**
	 * Start payment.
	 *
	 * @param Payment $payment The payment to start at the specified gateway.
	 * @param Gateway $gateway The gateway to start the payment at.
	 *
	 * @return Payment
	 */
	public static function start_payment( Payment $payment, Gateway $gateway ) {
		global $pronamic_ideal;

		$pronamic_ideal->payments_data_store->create( $payment );

		// Start payment at the gateway.
		$result = $gateway->start( $payment );

		// If result is false the payment failed to start.
		if ( false === $result ) {
			// If payment failed to start we directly update the payment status to 'failure'.
			$payment->set_status( Core\Statuses::FAILURE );

			// Check if there is a subscription attached to the payment.
			$subscription = $payment->get_subscription();

			if ( $subscription ) {
				if ( ! $payment->get_recurring() ) {
					// First payment.
					// Cancel subscription to prevent unwanted recurring payments in the future,
					// when a valid customer ID might be set for the user.
					$subscription->update_status( Core\Statuses::CANCELLED );
				} else {
					$subscription->set_status( Core\Statuses::FAILURE );
				}

				$pronamic_ideal->subscriptions_data_store->update( $subscription );
			}
		}

		// Check if the gateway has an error.
		if ( $gateway->has_error() ) {
			foreach ( $gateway->error->get_error_codes() as $code ) {
				$payment->add_note( sprintf( '%s: %s', $code, $gateway->error->get_error_message( $code ) ) );
			}
		}

		$pronamic_ideal->payments_data_store->update( $payment );

		if ( $gateway->supports( 'payment_status_request' ) ) {
			Payments\StatusChecker::schedule_event( $payment );
		}

		return $payment;
	}

	/**
	 * Get pages.
	 *
	 * @return array
	 */
	public function get_pages() {
		$return = array();

		$pages = array(
			'completed' => __( 'Completed', 'pronamic_ideal' ),
			'cancel'    => __( 'Canceled', 'pronamic_ideal' ),
			'expired'   => __( 'Expired', 'pronamic_ideal' ),
			'error'     => __( 'Error', 'pronamic_ideal' ),
			'unknown'   => __( 'Unknown', 'pronamic_ideal' ),
		);

		foreach ( $pages as $key => $label ) {
			$id = sprintf( 'pronamic_pay_%s_page_id', $key );

			$return[ $id ] = $label;
		}

		return $return;
	}
}
