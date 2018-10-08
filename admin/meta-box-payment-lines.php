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
				<td>
					<?php

					$quantities = array_map( function( $line ) {
						return $line->get_quantity();
					}, $lines->get_array() );

					echo esc_html( array_sum( $quantities ) );

					?>
				</td>
				<td>
					<?php

					$values = array_map( function( $line ) {
						if ( null !== $line->get_total_discount() ) {
							return $line->get_total_discount()->get_amount();
						}
					}, $lines->get_array() );

					echo esc_html( array_sum( $values ) );

					?>
				</td>
				<td>
					<?php

					$values = array_map( function( $line ) {
						if ( null !== $line->get_total_amount() ) {
							return $line->get_total_amount()->get_amount();
						}
					}, $lines->get_array() );

					echo esc_html( array_sum( $values ) );

					?>
				</td>
				<td>
					<?php

					$values = array_map( function( $line ) {
						if ( null !== $line->get_total_tax() ) {
							return $line->get_total_tax()->get_amount();
						}
					}, $lines->get_array() );

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
					<td><?php echo esc_html( $line->get_name() ); ?></td>
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

						if ( null !== $line->get_total_discount() ) {
							echo esc_html( $line->get_total_discount()->format_i18n() );
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

						if ( null !== $line->get_total_tax() ) {
							echo esc_html( $line->get_total_tax()->format_i18n() );
						}

						?>
					</td>
				</tr>

			<?php endforeach; ?>

		</tbody>
	</table>

<?php endif; ?>
