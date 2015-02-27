<div class="wrap">
	<h2><?php _e( 'iDEAL', 'pronamic_ideal' ); ?></h2>

	<div class="it-exchange-return-to-addons">
		<p>
			<a href="<?php echo remove_query_arg( 'add-on-settings' ); ?>">&larr; <?php _e( 'Back to Add-ons', 'pronamic_ideal' ); ?></a>
		</p>
	</div>

	<form action="options.php" method="post">
		<?php settings_fields( Pronamic_WP_Pay_Extensions_IThemesExchange_Extension::OPTION_GROUP ); ?>

		<?php do_settings_sections( Pronamic_WP_Pay_Extensions_IThemesExchange_Extension::OPTION_GROUP ); ?>

		<?php submit_button(); ?>
	</form>
</div>
