<?php if ( is_wp_error( $error ) ) : ?>

	<div class="error">
		<?php

		foreach ( $error->get_error_codes() as $code ) {
			?>
			<dl>
				<dt><?php _e( 'Code', 'pronamic_ideal' ); ?></dt>
				<dd><?php echo $code; ?></dd>

				<dt><?php _e( 'Message', 'pronamic_ideal' ); ?></dt>
				<dd><?php echo $error->get_error_message( $code ); ?></dd>
			</dl>

			<?php

			if ( 'ideal_advanced_error' === $code ) {
				$ideal_error = $error->get_error_data( $code );

				if ( $ideal_error instanceof Pronamic_Gateways_IDealAdvanced_Error ) : ?>

					<dl>
						<dt><?php _e( 'Code', 'pronamic_ideal' ); ?></dt>
						<dd><?php echo $ideal_error->get_code(); ?></dd>

						<dt><?php _e( 'Message', 'pronamic_ideal' ); ?></dt>
						<dd><?php echo $ideal_error->get_message(); ?></dd>

						<dt><?php _e( 'Detail', 'pronamic_ideal' ); ?></dt>
						<dd><?php echo $ideal_error->get_detail(); ?></dd>

						<dt><?php _e( 'Suggested Action', 'pronamic_ideal' ); ?></dt>
						<dd><?php echo $ideal_error->get_suggested_action(); ?></dd>

						<dt><?php _e( 'Consumer Message', 'pronamic_ideal' ); ?></dt>
						<dd><?php echo $ideal_error->get_consumer_message(); ?></dd>
					</dl>

				<?php endif;

			}

			if ( 'ideal_advanced_v3_error' === $code ) {
				$ideal_error = $error->get_error_data( $code );

				if ( $ideal_error instanceof Pronamic_Gateways_IDealAdvancedV3_Error ) : ?>

					<dl>
						<dt><?php _e( 'Code', 'pronamic_ideal' ); ?></dt>
						<dd><?php echo $ideal_error->get_code(); ?></dd>

						<dt><?php _e( 'Message', 'pronamic_ideal' ); ?></dt>
						<dd><?php echo $ideal_error->get_message(); ?></dd>

						<dt><?php _e( 'Detail', 'pronamic_ideal' ); ?></dt>
						<dd><?php echo $ideal_error->get_detail(); ?></dd>

						<dt><?php _e( 'Suggested Action', 'pronamic_ideal' ); ?></dt>
						<dd><?php echo $ideal_error->get_suggested_action(); ?></dd>

						<dt><?php _e( 'Consumer Message', 'pronamic_ideal' ); ?></dt>
						<dd><?php echo $ideal_error->get_consumer_message(); ?></dd>
					</dl>

				<?php endif;
			}
		}

		?>
	</div>

<?php endif; ?>
