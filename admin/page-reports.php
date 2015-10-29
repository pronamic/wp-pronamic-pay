<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<div id="poststuff">
		<div class="postbox">
			<div class="pronamic-pay-chart-filter">

			</div>

			<div class="inside pronamic-pay-chart-with-sidebar">
				<div class="pronamic-pay-chart-sidebar">
					<ul class="pronamci-pay-chart-legend">

						<?php foreach ( $this->reports->get_reports() as $i => $serie ) : ?>

							<li style="border-right-color: <?php echo esc_attr( $serie->color ); ?>;" data-pronamic-pay-highlight-serie="<?php echo esc_attr( $i ); ?>">
								<strong><?php

								if ( isset( $serie->tooltipFormatter ) && 'money' === $serie->tooltipFormatter ) {
									echo '&euro;&nbsp;' . esc_html( number_format_i18n( $serie->legendValue, 2 ) );
								} else {
									echo esc_html( $serie->legendValue );
								}

								?></strong>
								<?php echo esc_html( $serie->label ); ?>
							</li>

						<?php endforeach; ?>

					</ul>
				</div>

				<div id="chart1" style="height: 500px; width: 100%;"></div>
			</div>
		</div>
	</div>

	<?php include 'pronamic.php'; ?>
</div>
