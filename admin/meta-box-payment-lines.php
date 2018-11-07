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

use Pronamic\WordPress\Money\Money;

if ( empty( $lines ) ) : ?>

	<p>
		<?php esc_html_e( 'No payment lines found.', 'pronamic_ideal' ); ?>
	</p>

<?php else : ?>

	<div class="pronamic-pay-table-responsive">
		<table class="pronamic-pay-table widefat">
			<thead>
				<tr>
					<th scope="col"><?php esc_html_e( 'ID', 'pronamic_ideal' ); ?></th>
					<th scope="col"><?php esc_html_e( 'SKU', 'pronamic_ideal' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Image', 'pronamic_ideal' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Name', 'pronamic_ideal' ); ?></th>
					<th scope="col">
						<?php

						printf(
							'<span class="pronamic-pay-tip" title="%s">%s</span>',
							esc_attr__( 'Unit price without discount including tax.', 'pronamic_ideal' ),
							esc_html__( 'Unit Price', 'pronamic_ideal' )
						);

						?>
					</th>
					<th scope="col"><?php esc_html_e( 'Quantity', 'pronamic_ideal' ); ?></th>
					<th scope="col">
						<?php

						printf(
							'<span class="pronamic-pay-tip" title="%s">%s</span>',
							esc_attr__( 'Total discount.', 'pronamic_ideal' ),
							esc_html__( 'Discount', 'pronamic_ideal' )
						);

						?>
					</th>
					<th scope="col">
						<?php

						printf(
							'<span class="pronamic-pay-tip" title="%s">%s</span>',
							esc_attr__( 'Total amount with discount including tax.', 'pronamic_ideal' ),
							esc_html__( 'Total Amount', 'pronamic_ideal' )
						);

						?>
					</th>
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

						$quantities = array_map(
							function( $line ) {
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
							function( $line ) {
								if ( null !== $line->get_discount_amount() ) {
									return $line->get_discount_amount()->get_amount();
								}
							},
							$lines->get_array()
						);

						$discount_amount = new Money( array_sum( $values ), $payment->get_total_amount()->get_currency()->get_alphabetic_code() );

						echo esc_html( $discount_amount );

						?>
					</td>
					<td>
						<?php

						$values = array_map(
							function( $line ) {
								if ( null !== $line->get_total_amount_excluding_tax() ) {
									return $line->get_total_amount_excluding_tax()->get_amount();
								}
							},
							$lines->get_array()
						);

						$total_amount = new Money( array_sum( $values ), $payment->get_total_amount()->get_currency()->get_alphabetic_code() );

						echo esc_html( $total_amount );

						?>
					</td>
					<td>
						<?php

						$values = array_map(
							function( $line ) {
								if ( null !== $line->get_tax_amount() ) {
									return $line->get_tax_amount()->get_amount();
								}
							},
							$lines->get_array()
						);

						$tax_amount = new Money( array_sum( $values ), $payment->get_total_amount()->get_currency()->get_alphabetic_code() );

						echo esc_html( $tax_amount );

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
									'<img src="%s" alt="" width="50" height="50" />',
									esc_url( $image_url )
								);
							}

							?>
						</td>
						<td>
							<?php

							$product_url = $line->get_product_url();

							$description = $line->get_description();

							if ( ! empty( $product_url ) ) {
								// Product URL with or without description.
								$title = $line->get_name();

								$classes = array();

								if ( ! empty( $description ) ) {
									$title     = $line->get_description();
									$classes[] = 'pronamic-pay-tip';
								}

								printf(
									'<a class="%1$s" href="%2$s" title="%3$s">%4$s<a/>',
									esc_attr( implode( ' ', $classes ) ),
									esc_url( $line->get_product_url() ),
									esc_attr( $title ),
									esc_html( $line->get_name() )
								);
							} elseif ( ! empty( $description ) ) {
								// Description without product URL.
								printf(
									'<span class="pronamic-pay-tip" title="%1$s">%2$s</span>',
									esc_attr( $line->get_description() ),
									esc_html( $line->get_name() )
								);
							} else {
								// No description and no product URL.
								echo esc_html( $line->get_name() );
							}

							?>
						</td>
						<td>
							<?php

							$tip  = array();
							$text = '';

							if ( null !== $line->get_unit_price_excluding_tax() ) {
								$text = $line->get_unit_price_excluding_tax()->format_i18n();

								$tip[] = sprintf(
									/* translators: %s: unit price excluding tax */
									__( 'Exclusive tax: %s', 'pronamic_ideal' ),
									$text
								);
							}

							if ( null !== $line->get_unit_price_including_tax() ) {
								$tip[] = sprintf(
									/* translators: %s: unit price including tax */
									__( 'Inclusive tax: %s', 'pronamic_ideal' ),
									$line->get_unit_price_including_tax()->format_i18n()
								);
							}

							printf(
								'<span class="pronamic-pay-tip" title="%s">%s</span>',
								esc_attr( implode( '<br />', $tip ) ),
								esc_html( $text )
							);

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

							$tip  = array();
							$text = '';

							if ( null !== $line->get_total_amount_excluding_tax() ) {
								$text = $line->get_total_amount_excluding_tax()->format_i18n();

								$tip[] = sprintf(
									/* translators: %s: total amount excluding tax */
									__( 'Exclusive tax: %s', 'pronamic_ideal' ),
									$text
								);
							}

							if ( null !== $line->get_total_amount_including_tax() ) {
								$tip[] = sprintf(
									/* translators: %s: total amount including tax */
									__( 'Inclusive tax: %s', 'pronamic_ideal' ),
									$line->get_total_amount_including_tax()->format_i18n()
								);
							}


							printf(
								'<span class="pronamic-pay-tip" title="%s">%s</span>',
								esc_attr( implode( '<br />', $tip ) ),
								esc_html( $text )
							);

							?>
						</td>
						<td>
							<?php

							if ( null !== $line->get_tax_amount() ) {
								printf(
									'<span class="pronamic-pay-tip" title="%s">%s</span>',
									esc_attr( number_format_i18n( $line->get_tax_percentage() ) . '%' ),
									esc_html( $line->get_tax_amount()->format_i18n() )
								);
							}

							?>
						</td>
					</tr>

				<?php endforeach; ?>

			</tbody>
		</table>
	</div>

<?php endif; ?>
