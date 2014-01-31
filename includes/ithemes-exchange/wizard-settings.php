<div class="field <?php echo Pronamic_IThemesExchange_IDeal_AddOn::$slug; ?>-wizard">
	<h3><?php _e( 'iDEAL', 'pronamic_ideal' ); ?></h3>

	<?php settings_fields( Pronamic_IThemesExchange_IDeal_AddOn::OPTION_GROUP ); ?>

	<?php do_settings_sections( Pronamic_IThemesExchange_IDeal_AddOn::OPTION_GROUP ); ?>

	<input
		class="enable-<?php echo Pronamic_IThemesExchange_IDeal_AddOn::$slug; ?>"
		name="it-exchange-transaction-methods[]"
		value="<?php echo Pronamic_IThemesExchange_IDeal_AddOn::$slug; ?>"
		type="hidden"
	/>
</div>