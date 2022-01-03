<?php
/**
 * EShop settings meta box
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2022 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

global $eshop_metabox_plugin, $eshopoptions;

?>
<fieldset>
	<?php $eshop_metabox_plugin->show_img( 'pronamic_ideal' ); ?>

	<p class="cbox">
		<input id="eshop_method_pronamic_ideal" name="eshop_method[]" type="checkbox" value="pronamic_ideal" <?php checked( in_array( 'pronamic_ideal', (array) $eshopoptions['method'], true ) ); ?> />

		<label for="eshop_method_pronamic_ideal" class="eshopmethod">
			<?php esc_html_e( 'Accept payment by iDEAL', 'pronamic_ideal' ); ?>
		</label>
	</p>

	<label for="eshop_pronamic_ideal_config_id">
		<?php esc_html_e( 'Configuration', 'pronamic_ideal' ); ?>
	</label>

	<?php

	$selected = null;

	if ( isset( $eshopoptions, $eshopoptions['pronamic_ideal'], $eshopoptions['pronamic_ideal']['config_id'] ) ) {
		$selected = $eshopoptions['pronamic_ideal']['config_id'];
	}

	\Pronamic\WordPress\Pay\Admin\AdminModule::dropdown_configs(
		array(
			'name'     => 'eshop_pronamic_ideal_config_id',
			'selected' => $selected,
		)
	);

	?>
</fieldset>
