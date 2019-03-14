<?php
/**
 * Page Dashboard
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2019 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

?>
<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">
			<div id="postbox-container-1" class="postbox-container">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">

					<div class="postbox">
						<h2 class="hndle"><span><?php esc_html_e( 'Latest Payments', 'pronamic_ideal' ); ?></span></h2>

						<div class="inside">
							<?php

							$payments_post_type = \Pronamic\WordPress\Pay\Admin\AdminPaymentPostType::POST_TYPE;

							$query = new WP_Query(
								array(
									'post_type'      => $payments_post_type,
									'post_status'    => \Pronamic\WordPress\Pay\Payments\PaymentPostType::get_payment_states(),
									'posts_per_page' => 5,
								)
							);

							if ( $query->have_posts() ) :

								$columns = array(
									'status',
									'subscription',
									'title',
									'amount',
									'date',
								);

								$column_titles = apply_filters( 'manage_edit-' . $payments_post_type . '_columns', array() );

								?>

								<div id="dashboard_recent_drafts">
									<table class="wp-list-table widefat fixed striped posts">

										<tr class="type-<?php echo esc_attr( $payments_post_type ); ?>">

											<?php

											foreach ( $columns as $column ) :
												$custom_column = sprintf( '%1$s_%2$s', $payments_post_type, $column );

												// Column classes.
												$classes = array(
													sprintf( 'column-%s', $custom_column ),
												);

												if ( 'pronamic_payment_title' === $custom_column ) :
													$classes[] = 'column-primary';
												endif;

												printf(
													'<th class="%1$s">%2$s</th>',
													esc_attr( implode( ' ', $classes ) ),
													wp_kses_post( $column_titles[ $custom_column ] )
												);

											endforeach;

											?>

										</tr>

										<?php while ( $query->have_posts() ) : $query->the_post(); ?>

											<tr class="type-<?php echo esc_attr( $payments_post_type ); ?>">
												<?php

												$post_id = get_the_ID();

												// Loop columns.
												foreach ( $columns as $column ) :

													$custom_column = sprintf( '%1$s_%2$s', $payments_post_type, $column );

													// Column classes.
													$classes = array(
														$custom_column,
														'column-' . $custom_column,
													);

													if ( 'pronamic_payment_title' === $custom_column ) {
														$classes[] = 'column-primary';
													}

													printf(
														'<td class="%1$s" data-colname="%2$s">',
														esc_attr( implode( ' ', $classes ) ),
														esc_html( $column_titles[ $custom_column ] )
													);

													// Do custom column action.
													do_action(
														'manage_' . $payments_post_type . '_posts_custom_column',
														$custom_column,
														$post_id
													);

													if ( 'pronamic_payment_title' === $custom_column ) :

														printf(
															'<button type = "button" class="toggle-row" ><span class="screen-reader-text">%1$s</span ></button>',
															esc_html( __( 'Show more details', 'pronamic_ideal' ) )
														);

													endif;

													echo '</td>';

												endforeach;

												?>

											</tr>

										<?php endwhile; ?>

									</table>
								</div>

								<?php wp_reset_postdata(); ?>

							<?php else : ?>

								<p><?php esc_html_e( 'No pending payments found.', 'pronamic_ideal' ); ?></p>

							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>

			<div id="postbox-container-2" class="postbox-container">
				<div id="side-sortables" class="meta-box-sortables ui-sortable">
					<?php if ( current_user_can( 'manage_options' ) ) : ?>

						<div class="postbox">
							<h2 class="hndle"><span><?php esc_html_e( 'Getting Started', 'pronamic_ideal' ); ?></span></h2>

							<div class="inside">
								<p>
									<?php esc_html_e( "Please follow the tour, read the 'What is new' and 'Getting Started' pages before contacting us. Also, check the system status page for any issues.", 'pronamic_ideal' ); ?>
								</p>

								<?php

								printf(
									'<a href="%s" class="button-secondary">%s</a>',
									esc_attr(
										wp_nonce_url(
											add_query_arg(
												array(
													'page' => 'pronamic_ideal',
													'pronamic_pay_ignore_tour' => '0',
												)
											),
											'pronamic_pay_ignore_tour',
											'pronamic_pay_nonce'
										)
									),
									esc_html__( 'Start tour', 'pronamic_ideal' )
								);

								echo ' ';

								printf(
									'<a href="%s" class="button-secondary">%s</a>',
									esc_attr(
										add_query_arg(
											array(
												'page' => 'pronamic-pay-about',
												'tab'  => 'new',
											)
										)
									),
									esc_html__( 'What is new', 'pronamic_ideal' )
								);

								echo ' ';

								printf(
									'<a href="%s" class="button-secondary">%s</a>',
									esc_attr(
										add_query_arg(
											array(
												'page' => 'pronamic-pay-about',
												'tab'  => 'getting-started',
											)
										)
									),
									esc_html__( 'Getting started', 'pronamic_ideal' )
								);

								echo ' ';

								printf(
									'<a href="%s" class="button-secondary">%s</a>',
									esc_attr(
										add_query_arg(
											array(
												'page' => 'pronamic_pay_tools',
											)
										)
									),
									esc_html__( 'System Status', 'pronamic_ideal' )
								);

								?>
							</div>
						</div>

					<?php endif; ?>

					<div class="postbox">
						<h2 class="hndle"><span><?php esc_html_e( 'Pronamic News', 'pronamic_ideal' ); ?></span></h2>

						<div class="inside">
							<?php

							wp_widget_rss_output(
								'http://feeds.feedburner.com/pronamic',
								array(
									'link'  => __( 'http://www.pronamic.eu/', 'pronamic_ideal' ),
									'url'   => 'http://feeds.feedburner.com/pronamic',
									'title' => __( 'Pronamic News', 'pronamic_ideal' ),
									'items' => 5,
								)
							);

							?>
						</div>
					</div>
				</div>
			</div>

			<div class="clear"></div>
		</div>
	</div>

	<?php require 'pronamic.php'; ?>
</div>
