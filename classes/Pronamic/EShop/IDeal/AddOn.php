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
		add_action('init',  array(__CLASS__, 'initialize'), 20);

		/**
		 * Pronamic iDEAL filters
		 */
		add_filter('pronamic_payment_source_text_eshop', array(__CLASS__, 'sourceColumn'), 10, 2);
		
		/**
		 * eShop actions
		 */
		// @see http://plugins.trac.wordpress.org/browser/eshop/tags/6.2.12/eshop-settings-extends.php#L1049
		add_action('eshop_setting_merchant_load', array(__CLASS__, 'loadMerchantSettings'));

		/**
		 * eShop filter
		 */
		// @see http://plugins.trac.wordpress.org/browser/eshop/tags/6.2.12/eshop-settings-extends.php#L1460
		add_filter('eshop_setting_merchant_save', array(__CLASS__, 'saveMerchantSettings'), 20, 2);

		// @see http://plugins.trac.wordpress.org/browser/eshop/tags/6.2.12/eshop-settings-extends.php#L1365
		// @see http://plugins.trac.wordpress.org/browser/eshop/tags/6.2.12/checkout.php#L315
		$eshoppayment = 'pronamic_ideal';

		add_filter('eshop_merchant_img_' . $eshoppayment, array(__CLASS__,  'merchantImage'));
	}

	//////////////////////////////////////////////////

	/**
	 * Initialize
	 */
	public static function initialize() {
		
	}

	//////////////////////////////////////////////////

	/**
	 * Load merchant settings
	 */
	public static function loadMerchantSettings($metaBox) {
		add_meta_box(
			'eshop-m-pronamic_ideal' , 
			__('Pronamic iDEAL', 'pronamic_ideal') , 
			array(__CLASS__, 'metaBox') , 
			$metaBox->pagehook , 
			'normal' , 
			'core'
		);
	}

	/**
	 * Save merchant settings
	 * 
	 * @param array $eShopOptions
	 * @param array $postData
	 */
	public static function saveMerchantSettings($eShopOptions, $postData) {
		// We should move the post data to the options, eShop will take care of the actual saving


		return $eShopOptions;
	}

	//////////////////////////////////////////////////

	/**
	 * Merchant image
	 * 
	 * @param array $image
	 * @see http://plugins.trac.wordpress.org/browser/eshop/tags/6.2.12/eshop-settings-extends.php#L1365
	 * @see http://plugins.trac.wordpress.org/browser/eshop/tags/6.2.12/checkout.php#L315
	 */
	public static function merchantImage($image) {
		$relative = 'images/ideal-logo-pay-off-2-lines.png';

		$image['path'] = plugin_dir_path(Pronamic_WordPress_IDeal_Plugin::$file) . $relative;
		$image['url'] = plugins_url($relative, Pronamic_WordPress_IDeal_Plugin::$file);

		return $image;
	}

	//////////////////////////////////////////////////
	
	/**
	 * Meta box
	 */
	public static function metaBox() {
		// Globals
		global $eshop_metabox_plugin, $eshopoptions;

		// eShop options
		$config_id = @$eshopoptions['pronamic_ideal'];
		
		$configs = Pronamic_WordPress_IDeal_IDeal::get_config_select_options();

		?>
		<fieldset>
			<?php $eshop_metabox_plugin->show_img('pronamic_ideal'); ?>

			<p class="cbox">
				<input id="eshop_method_pronamic_ideal" name="eshop_method[]" type="checkbox" value="pronamic_ideal" <?php checked(in_array('pronamic_ideal', (array) $eshopoptions['method'])); ?> />

				<label for="eshop_method_pronamic_ideal" class="eshopmethod">
					<?php _e('Accept payment by iDEAL', 'pronamic_ideal'); ?>
				</label>
			</p>

			<label for="eshop_pronamic_ideal_config_id">
				<?php _e('Config', 'pronamic_ideal') ?>:
			</label>

			<select name="eshop_pronamic_ideal_config_id" id="eshop_pronamic_ideal_config_id">
				<?php
				
				foreach ( $configs as $id => $name ) {
					printf(
						'<option value="%s" %s>%s</option>',
						esc_attr( $id ),
						selected( $id, $config_id, false ),
						esc_html( $name )
					);
				}

				?>
			</select>
		</fieldset>
		<?php
	}

	//////////////////////////////////////////////////

	/**
	 * Source column
	 */
	public static function sourceColumn($text, $payment) {
		$text  = '';
		$text .= __('eShop', 'pronamic_ideal') . '<br />';
		$text .= sprintf('<a href="%s">', add_query_arg(array('page' => 'gf_pronamic_ideal', 'lid' => $payment->getSourceId()), admin_url('admin.php')));
		$text .= sprintf(__('Order #%s', 'pronamic_ideal'), $payment->getSourceId());
		$text .= '</a>';

		return $text;
	}
}
