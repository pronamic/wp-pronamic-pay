<?php
/**
 * Page Dashboard
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

?>
<div class="wrap">
	<h1 class="wp-heading-inline"><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<hr class="wp-header-end">

	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder columns-2">
			<div id="postbox-container-1" class="postbox-container">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">

					<?php if ( current_user_can( 'manage_options' ) ) : ?>

						<div class="postbox">
							<h2 class="hndle"><span><?php esc_html_e( 'Support', 'pronamic_ideal' ); ?></span></h2>

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
											), 'pronamic_pay_ignore_tour', 'pronamic_pay_nonce'
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
						<h2 class="hndle"><span><?php esc_html_e( 'Pending Payments', 'pronamic_ideal' ); ?></span></h2>

						<div class="inside">
							<?php

							$query = new WP_Query(
								array(
									'post_type'      => 'pronamic_payment',
									'post_status'    => 'payment_pending',
									'posts_per_page' => 5,
								)
							);

							if ( $query->have_posts() ) :

								?>

								<div id="dashboard_recent_drafts">
									<ul>

										<?php while ( $query->have_posts() ) : $query->the_post(); ?>

											<li>
												<h4>
													<?php

													$post = get_post();

													printf(
														'<a href="%s">%s</a>',
														esc_attr( get_edit_post_link( $post ) ),
														esc_html( get_the_title( $post ) )
													);

													?>
													<?php

													printf(
														'<abbr title="%s">%s</abbr>',
														/* translators: comment date format. See http://php.net/date */
														esc_attr( get_the_time( __( 'c', 'pronamic_ideal' ), $post ) ),
														esc_html( get_the_time( get_option( 'date_format' ), $post ) )
													);

													?>
												</h4>
											</li>

										<?php endwhile; ?>

									</ul>
								</div>

								<?php wp_reset_postdata(); ?>

							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>

			<div id="postbox-container-2" class="postbox-container">
				<div id="side-sortables" class="meta-box-sortables ui-sortable">
					<div class="postbox">
						<h2 class="hndle"><span><?php esc_html_e( 'Pronamic News', 'pronamic_ideal' ); ?></span></h2>

						<div class="inside">
							<?php

							wp_widget_rss_output(
								'http://feeds.feedburner.com/pronamic', array(
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
