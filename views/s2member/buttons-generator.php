<div class="wrap">
	<?php screen_icon(); ?>

	<h2><?php echo get_admin_page_title(); ?></h2>

	<div class="pronamic_ideal_shortcode_generator">
		<script type="text/javascript">
			jQuery(function() {
				var cost = jQuery('.jPronamicIdealCost'),
					period = jQuery('.jPronamicIdealPeriodShortcode'),
					level = jQuery('.jPronamicIdealLevelShortcode'),
					description = jQuery('.jPronamicIdealDescriptionShortcode'),
					generate_button = jQuery('.jPronamicIdealGenerateShortcode'),
					output = jQuery('.jPronamicIdealButtonShortcodeOutput');

				generate_button.click( function() {
					var shortcode = '';

					shortcode += '[pronamic_ideal_s2member ';

					if (cost.val().length > 0)
						shortcode += 'cost="' + cost.val() + '" ';

					if (period.val().length > 0)
						shortcode += 'period="' + period.val() + '" ';

					if (level.val().length > 0)
						shortcode += 'level="' + level.val() + '" ';

					if (description.val().length > 0)
						shortcode += 'description="' + description.val() + '" ';

					shortcode += ']';

					output.val(shortcode);
				});

			});
		</script>

		<table class="form-table">
			<tbody>
				<tr>
					<th><?php _e( 'iDEAL Buttons Code Generator', 'pronamic_ideal' ); ?></th>
					<td>
						<p>
							I want to charge:
							<input type='text' autocomplete='off' size='6' class='jPronamicIdealCost'/>
							/
							<select class='jPronamicIdealPeriodShortcode'>
								<?php foreach ( Pronamic_S2Member_Bridge_Order::$periods as $key => $period ) : ?>
								<option value="<?php echo $key; ?>"><?php echo $period; ?></option>
								<?php endforeach; ?>
							</select>
						</p>
						<p>
							for access to level
							<select class='jPronamicIdealLevelShortcode'>
								<?php for( $level = 1; $level <= 4; $level++ ) : ?>
								<option value='<?php echo $level; ?>'><?php echo $level; ?></option>
								<?php endfor; ?>
							</select>
							content
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
					<th><?php _e( 'Button Shortcode', 'pronamic_ideal' ); ?></th>
					<td>
						<textarea class='jPronamicIdealButtonShortcodeOutput' style='width:100%;min-height:30px;'></textarea>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>