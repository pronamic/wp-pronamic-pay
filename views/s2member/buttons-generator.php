<div class="wrap">
	<?php screen_icon( 'pronamic_ideal' ); ?>

	<h2><?php echo get_admin_page_title(); ?></h2>

	<div class="pronamic_ideal_shortcode_generator">
		<script type="text/javascript">
			jQuery( function() {
				var cost = jQuery( '.jPronamicIdealCost' ),
					period = jQuery( '.jPronamicIdealPeriodShortcode' ),
					level = jQuery( '.jPronamicIdealLevelShortcode' ),
					description = jQuery( '.jPronamicIdealDescriptionShortcode' ),
					generate_button = jQuery( '.jPronamicIdealGenerateShortcode' ),
					output = jQuery( '.jPronamicIdealButtonShortcodeOutput' );

				generate_button.click( function() {
					var shortcode = '';

					shortcode += '[pronamic_ideal_s2member ';

					if ( cost.val().length > 0 )
						shortcode += 'cost="' + cost.val() + '" ';

					if ( period.val().length > 0 )
						shortcode += 'period="' + period.val() + '" ';

					if ( level.val().length > 0 )
						shortcode += 'level="' + level.val() + '" ';

					if ( description.val().length > 0 )
						shortcode += 'description="' + description.val() + ' {{order_id}}" ';

					shortcode += ']';

					output.val( shortcode );
				});
			});
		</script>

		<table class="form-table">
			<tbody>
				<tr>
					<th><?php _e( 'Generator', 'pronamic_ideal' ); ?></th>
					<td>
						<p>
							<?php

							$input = '<input type="text" autocomplete="off" size="6" class="jPronamicIdealCost" />';

							$select  = '';
							$select .= '<select class="jPronamicIdealPeriodShortcode">';
							foreach ( Pronamic_S2Member_Bridge_Order::$periods as $key => $period ) {
								$select .= sprintf( '<option value="%s">%s</option>', $key, $period );
							}
							$select .= '</select>';

							printf( __( 'I want to charge: %s / %s', 'pronamic_ideal' ), $input, $select );

							?>
						</p>
						<p>
							<?php

							$select  = '';
							$select .= '<select class="jPronamicIdealLevelShortcode">';
							for ( $level = 1; $level <= 4; $level++ ) {
								$select .= sprintf( '<option value="%s">%s</option>', $level, $level );
							}
							$select .= '</select>';

							printf( __( 'for access to level %s content', 'pronamic_ideal' ), $select );

							?>
						</p>
						<p>
							<?php _e( 'Description:', 'pronamic_ideal' ); ?>
							<input type='text' size='70' class='jPronamicIdealDescriptionShortcode'/>
						</p>
						<p>
							<a class="button-primary jPronamicIdealGenerateShortcode"><?php _e( 'Generate Shortcode', 'pronamic_ideal' ); ?></a>
						</p>
					</td>
				</tr>
				<tr>
					<th><?php _e( 'Shortcode', 'pronamic_ideal' ); ?></th>
					<td>
						<textarea class="jPronamicIdealButtonShortcodeOutput" style="width: 100%; min-height: 30px;"></textarea>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>