<?php

use Pronamic\WordPress\Pay\Util;

?>
<div class="wrap">
	<h1 class="wp-heading-inline"><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<hr class="wp-header-end">

	<div id="poststuff">
		<div class="postbox">
			<div class="pronamic-pay-chart-filter">

			</div>

			<div class="inside pronamic-pay-chart-with-sidebar">
				<div class="pronamic-pay-chart-sidebar">
					<ul class="pronamci-pay-chart-legend">

						<?php foreach ( $this->reports->get_reports() as $i => $serie ) : ?>

							<li class="<?php echo esc_attr( $serie->class ); ?>" data-pronamic-pay-highlight-serie="<?php echo esc_attr( $i ); ?>">
								<?php

								echo '<strong>';

								if ( isset( $serie->tooltipFormatter ) && 'money' === $serie->tooltipFormatter ) {
									echo esc_html( Util::format_price( $serie->legendValue ) );
								} else {
									echo esc_html( $serie->legendValue );
								}

								echo '</strong>';

								echo esc_html( $serie->label );

								?>
							</li>

						<?php endforeach; ?>

					</ul>
				</div>

				<div id="chart1" style="height: 500px; width: 100%;"></div>
			</div>
		</div>
	</div>

	<?php require 'pronamic.php'; ?>
</div>
