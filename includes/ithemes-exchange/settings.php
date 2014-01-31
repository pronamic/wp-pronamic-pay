<div class="wrap">
	<h2><?php _e( 'iDEAL Gateway Settings', 'pronamic_ideal' ); ?></h2>

	<div class="it-exchange-return-to-addons">
		<p>
			<a href="<?php echo remove_query_arg( 'add-on-settings' ); ?>">&larr; <?php _e( 'Back to Add-ons', 'pronamic_ideal' ); ?></a>
		</p>
	</div>

	<form action="options.php" method="post">
		<?php settings_fields( Pronamic_IThemesExchange_IDeal_AddOn::OPTION_GROUP ); ?>

		<?php do_settings_sections( Pronamic_IThemesExchange_IDeal_AddOn::OPTION_GROUP ); ?>

		<?php submit_button(); ?>
	</form>
</div>