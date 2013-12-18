<?php 

/**
 * Title: Gravity Forms iDEAL Add-On
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_EShop_IDeal_AddOn {
	/**
	 * Slug
	 * 
	 * @var string
	 */
	const SLUG = 'eshop';

	//////////////////////////////////////////////////

	/**
	 * Bootstrap
	 */
	public static function bootstrap() {
		/**
		 * WordPress
		 */
		add_action( 'init',  array( __CLASS__, 'init' ), 20 );

		/**
		 * Pronamic iDEAL filters
		 */
		add_filter( 'pronamic_payment_source_text_eshop', array( __CLASS__, 'sourceColumn' ), 10, 2 );
		
		/**
		 * eShop actions
		 * 
		 * @see http://plugins.trac.wordpress.org/browser/eshop/tags/6.2.12/eshop-settings-extends.php#L1049
		 */
		add_action( 'eshop_setting_merchant_load', array( __CLASS__, 'setting_merchant_load' ) );

		/**
		 * eShop filter
		 * 
		 * @see http://plugins.trac.wordpress.org/browser/eshop/tags/6.2.12/eshop-settings-extends.php#L1460
		 */
		add_filter( 'eshop_setting_merchant_save', array( __CLASS__, 'setting_merchant_save' ), 20, 2 );

		// @see http://plugins.trac.wordpress.org/browser/eshop/tags/6.2.12/eshop-settings-extends.php#L1365
		// @see http://plugins.trac.wordpress.org/browser/eshop/tags/6.2.12/checkout.php#L315
		$eshoppayment = 'pronamic_ideal';

		add_filter( 'eshop_merchant_img_' . $eshoppayment, array( __CLASS__,  'merchant_img' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Initialize
	 */
	public static function init() {
		
	}

	//////////////////////////////////////////////////

	/**
	 * Load merchant settings
	 */
	public static function setting_merchant_load( $meta_box ) {
		add_meta_box(
			'eshop-m-pronamic_ideal',
			__( 'Pronamic iDEAL', 'pronamic_ideal' ), 
			array( __CLASS__, 'meta_box' ),
			$meta_box->pagehook,
			'normal',
			'core'
		);
	}

	/**
	 * Save merchant settings
	 * 
	 * @param array $eshopoptions
	 * @param array $post_data 
	 */
	public static function setting_merchant_save( $eshopoptions, $post_data ) {
		// We should move the post data to the options, eShop will take care of the actual saving
		if ( isset( $post_data['eshop_pronamic_ideal_config_id'] ) ) {
			$options = array(
				'config_id' => filter_var( $post_data['eshop_pronamic_ideal_config_id'], FILTER_VALIDATE_INT )
			);

			$eshopoptions['pronamic_ideal'] = $options;
		}

		return $eshopoptions;
	}

	//////////////////////////////////////////////////

	/**
	 * Merchant image
	 * 
	 * @param array $image
	 * @see http://plugins.trac.wordpress.org/browser/eshop/tags/6.2.12/eshop-settings-extends.php#L1365
	 * @see http://plugins.trac.wordpress.org/browser/eshop/tags/6.2.12/checkout.php#L315
	 */
	public static function merchant_img( $image ) {
		$relative = 'images/ideal-logo-pay-off-2-lines.png';

		$image['path'] = plugin_dir_path( Pronamic_WordPress_IDeal_Plugin::$file ) . $relative;
		$image['url']  = plugins_url( $relative, Pronamic_WordPress_IDeal_Plugin::$file );

		return $image;
	}

	//////////////////////////////////////////////////
	
	/**
	 * Meta box
	 */
	public static function meta_box() {
		include Pronamic_WordPress_IDeal_Plugin::$dirname . '/views/eshop/settings-meta-box.php';
	}

	//////////////////////////////////////////////////

	/**
	 * Source column
	 */
	public static function sourceColumn( $text, $payment ) {
		$text  = '';
		$text .= __( 'eShop', 'pronamic_ideal' ) . '<br />';
		$text .= sprintf('<a href="%s">', add_query_arg( array( 'page' => 'gf_pronamic_ideal', 'lid' => $payment->get_source_id() ), admin_url( 'admin.php' ) ) );
		$text .= sprintf( __( 'Order #%s', 'pronamic_ideal' ), $payment->get_source_id() );
		$text .= '</a>';

		return $text;
	}
}
