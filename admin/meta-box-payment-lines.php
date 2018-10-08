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
				<th scope="col"><?php esc_html_e( 'Item ID', 'pronamic_ideal' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Description', 'pronamic_ideal' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Quantity', 'pronamic_ideal' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Pricy', 'pronamic_ideal' ); ?></th>
			</tr>
		</thead>

		<tbody>

			<?php foreach ( $lines as $line ) : ?>

				<tr>
					<td><?php echo esc_html( $item->get_id() ); ?></td>
					<td><?php echo esc_html( $item->get_description() ); ?></td>
					<td><?php echo esc_html( $item->get_quantity() ); ?></td>
					<td><?php echo esc_html( $amount->format_i18n() ); ?></td>
				</tr>

			<?php endforeach; ?>

		</tbody>
	</table>

<?php endif; ?>
