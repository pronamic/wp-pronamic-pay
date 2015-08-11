<div class="wrap">
	<h2><?php echo get_admin_page_title(); ?></h2>

	<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder columns-2">
			<div id="postbox-container-1" class="postbox-container">
				<div id="normal-sortables" class="meta-box-sortables ui-sortable">
					<div class="postbox">
						<h3 class="hndle"><span><?php _e( 'Tour', 'pronamic_ideal' ); ?></span></h3>

						<div class="inside">
							<p>
								<?php esc_html_e( 'Neem deze rondleiding om snel wegwijs te worden in het gebruik van deze plugin', 'pronamic_ideal' ); ?>
							</p>

							<?php

							printf(
								'<a href="%s" class="button-secondary">%s</a>',
								esc_attr(
									wp_nonce_url( add_query_arg( array(
										'page'                     => 'pronamic_ideal',
										'pronamic_pay_ignore_tour'  => '0',
									) ), 'pronamic_pay_ignore_tour', 'pronamic_pay_nonce' )
								),
								esc_html__( 'Start Tour', 'pronamic_ideal' )
							);

							?>
						</div>
					</div>

					<div class="postbox">
						<h3 class="hndle"><span><?php _e( 'Pending Payments', 'pronamic_ideal' ); ?></span></h3>

						<div class="inside">
							<?php

							$payments = get_posts( array(
								'post_type'      => 'pronamic_payment',
								'post_status'    => 'payment_pending',
								'posts_per_page' => 5,
							) );

							if ( empty( $payments ) ) : ?>



							<?php else : ?>

								<div id="dashboard_recent_drafts">
									<ul>

										<?php foreach ( $payments as $payment ) : ?>

											<li>
												<h4>
													<?php

													printf(
														'<a href="%s">%s</a>',
														get_edit_post_link( $payment ),
														get_the_title( $payment )
													);

													?>
													<?php

													printf( '<abbr title="%s">%s</abbr>',
														/* translators: comment date format. See http://php.net/date */
														get_the_time( __( 'c', 'pronamic_ideal' ), $payment ),
														get_the_time( get_option( 'date_format' ), $payment )
													);

													?>
												</h4>
											</li>

										<?php endforeach; ?>

									</ul>
								</div>

							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>

			<div id="postbox-container-2" class="postbox-container">
				<div id="side-sortables" class="meta-box-sortables ui-sortable">
					<div class="postbox">
						<h3 class="hndle"><span><?php _e( 'Pronamic News', 'pronamic_ideal' ); ?></span></h3>

						<div class="inside">
							<?php

							wp_widget_rss_output( 'http://feeds.feedburner.com/pronamic', array(
								'link'  => __( 'http://www.pronamic.eu/', 'pronamic_ideal' ),
								'url'   => 'http://feeds.feedburner.com/pronamic',
								'title' => __( 'Pronamic News', 'pronamic_ideal' ),
								'items' => 5,
							) );

							?>
						</div>
					</div>
				</div>
			</div>

			<div class="clear"></div>
		</div>
	</div>

	<?php include 'pronamic.php'; ?>
</div>
