<?php
/**
 * Meta Box Payment Notes
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

if ( ! isset( $notes ) ) {
	return;
}

if ( ! is_array( $notes ) ) {
	return;
}

if ( empty( $notes ) ) : ?>

	<?php esc_html_e( 'No notes found.', 'pronamic_ideal' ); ?>

<?php else : ?>

	<table class="pronamic-pay-table widefat">
		<thead>
			<tr>
				<th scope="col"><?php esc_html_e( 'Date', 'pronamic_ideal' ); ?></th>
				<th scope="col"><?php esc_html_e( 'Note', 'pronamic_ideal' ); ?></th>
				<th scope="col"><?php esc_html_e( 'User', 'pronamic_ideal' ); ?></th>
			</tr>
		</thead>

		<tbody>

			<?php foreach ( $notes as $note ) : ?>

				<tr>
					<td>
						<?php

						printf(
							esc_html__( '%1$s at %2$s', 'pronamic_ideal' ),
							/* translators: comment date format. See http://php.net/date */
							esc_html( get_comment_date( __( 'Y/m/d', 'pronamic_ideal' ), $note->comment_ID ) ),
							esc_html( get_comment_date( get_option( 'time_format' ), $note->comment_ID ) )
						);

						?>
					</td>
					<td>
						<?php comment_text( $note ); ?>
					</td>
					<td>
						<?php comment_author( $note ); ?>
					</td>
				</tr>

			<?php endforeach; ?>

		</tbody>
	</table>

<?php endif; ?>
