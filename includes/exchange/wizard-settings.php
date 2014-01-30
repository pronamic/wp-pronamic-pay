<div class="field <?php echo Pronamic_Exchange_IDeal_AddOn::$slug; ?>-wizard">
	<h3><?php _e( 'iDEAL Gateway Settings', 'pronamic_ideal' ); ?></h3>

	<?php settings_fields( Pronamic_Exchange_IDeal_AddOn::OPTION_GROUP ); ?>

	<?php do_settings_sections( Pronamic_Exchange_IDeal_AddOn::OPTION_GROUP ); ?>

	<input
		class="enable-<?php echo Pronamic_Exchange_IDeal_AddOn::$slug; ?>"
		name="it-exchange-transaction-methods[]"
		value="<?php echo Pronamic_Exchange_IDeal_AddOn::$slug; ?>"
		type="hidden"
	/>
</div>