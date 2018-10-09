<?php
/**
 * Meta Box Payment Notes
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

namespace Pronamic\WordPress\Pay;

use Pronamic\WordPress\DateTime\DateTime;
use Pronamic\WordPress\DateTime\DateTimeZone;

if ( empty( $lines ) ) : ?>

	<p>
		<?php esc_html_e( 'No payment lines found.', 'pronamic_ideal' ); ?>
	</p>

<?php else : ?>

	<table class="pronamic-pay-table widefat">
		<thead>
			<tr>
				<th scope="col"><?php esc_html_e( 'ID', 'pronamic_ideal' ); ?></th>
				<th scope="col"><?php esc_html_e( 'SKU', 'pronamic_ideal' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Image', 'pronamic_ideal' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Name', 'pronamic_ideal' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Description', 'pronamic_ideal' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Unit Price', 'pronamic_ideal' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Quantity', 'pronamic_ideal' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Total Discount', 'pronamic_ideal' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Total Amount', 'pronamic_ideal' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Total Tax', 'pronamic_ideal' ); ?></th>
			</tr>
		</thead>

		<tfoot>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>
					<?php

					$quantities = array_map(
						function ( $line ) {
							return $line->get_quantity();
						},
						$lines->get_array()
					);

					echo esc_html( array_sum( $quantities ) );

					?>
				</td>
				<td>
					<?php

					$values = array_map(
						function ( $line ) {
							if ( null !== $line->get_discount_amount() ) {
								return $line->get_discount_amount()->get_amount();
							}
						},
						$lines->get_array()
					);

					echo esc_html( array_sum( $values ) );

					?>
				</td>
				<td>
					<?php

					$values = array_map(
						function ( $line ) {
							if ( null !== $line->get_total_amount() ) {
								return $line->get_total_amount()->get_amount();
							}
						},
						$lines->get_array()
					);

					echo esc_html( array_sum( $values ) );

					?>
				</td>
				<td>
					<?php

					$values = array_map(
						function ( $line ) {
							if ( null !== $line->get_tax_amount() ) {
								return $line->get_tax_amount()->get_amount();
							}
						},
						$lines->get_array()
					);

					echo esc_html( array_sum( $values ) );

					?>
				</td>
			</tr>
		</tfoot>

		<tbody>

			<?php foreach ( $lines as $line ) : ?>

				<tr>
					<td><?php echo esc_html( $line->get_id() ); ?></td>
					<td><?php echo esc_html( $line->get_sku() ); ?></td>
					<td>
						<?php

						$image_url = $line->get_image_url();

						if ( ! empty( $image_url ) ) {
							printf(
								'<img src="%s" alt="" />',
								esc_url( $image_url )
							);
						}

						?>
					</td>
					<td>
						<?php

						$url = $line->get_product_url();

						if ( empty( $url ) ) {
							echo esc_html( $line->get_name() );
						} else {
							printf(
								'<a href="%s">%s<a/>',
								esc_url( $url ),
								esc_html( $line->get_name() )
							);
						}

						?>
					</td>
					<td><?php echo esc_html( $line->get_description() ); ?></td>
					<td>
						<?php

						if ( null !== $line->get_unit_price() ) {
							echo esc_html( $line->get_unit_price()->format_i18n() );
						}

						?>
					</td>
					<td><?php echo esc_html( $line->get_quantity() ); ?></td>
					<td>
						<?php

						if ( null !== $line->get_discount_amount() ) {
							echo esc_html( $line->get_discount_amount()->format_i18n() );
						}

						?>
					</td>
					<td>
						<?php

						if ( null !== $line->get_total_amount() ) {
							echo esc_html( $line->get_total_amount()->format_i18n() );
						}

						?>
					</td>
					<td>
						<?php

						if ( null !== $line->get_tax_amount() ) {
							echo esc_html( $line->get_tax_amount()->format_i18n() );
						}

						?>
					</td>
				</tr>

			<?php endforeach; ?>

		</tbody>
	</table>

<?php endif; ?>
