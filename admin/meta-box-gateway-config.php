<?php

$sections = $this->admin->gateway_settings->get_sections();
$fields   = $this->admin->gateway_settings->get_fields();

$sections_fields = array();

foreach ( $sections as $id => $section ) {
	$sections_fields[ $id ] = array();
}

foreach ( $fields as $id => $field ) {
	$section = $field['section'];

	$sections_fields[ $section ][ $id ] = $field;
}

// Sections
$variant_id = get_post_meta( get_the_ID(), '_pronamic_gateway_id', true );

$options = array();

global $pronamic_pay_providers;

bind_providers_and_gateways();

?>
<div id="pronamic-pay-gateway-config-editor">
	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="pronamic_gateway_id">
					<?php esc_html_e( 'Payment provider', 'pronamic_ideal' ); ?>
				</label>
			</th>
			<td>
				<select id="pronamic_gateway_id" name="_pronamic_gateway_id">
					<option value=""></option>

					<?php

					foreach ( $pronamic_pay_providers as $provider ) {
						if ( isset( $provider['integrations'] ) && is_array( $provider['integrations'] ) ) {
							printf( '<optgroup label="%s">', esc_attr( $provider['name'] ) );

							foreach ( $provider['integrations'] as $integration ) {
								$id      = $integration->get_id();
								$name    = $integration->get_name();
								$classes = array();
								$description = '';
								$links   = array();

								if ( isset( $integration->deprecated ) && $integration->deprecated  ) {
									$classes[] = 'deprecated';

									$name = sprintf( __( '%s (obsoleted)', 'pronamic_ideal' ), $name );

									if ( $variant_id !== $id ) {
										continue;
									}
								}

								// Dashboard links
								$dashboards = $integration->get_dashboard_url();

								if ( 1 === count( $dashboards ) ) {
									$links[] = sprintf( '<a href="%s" title="%s">%2$s</a>',
										esc_attr( $dashboards[0] ),
										__( 'Dashboard', 'pronamic_ideal' )
									);
								} elseif ( count( $dashboards ) > 1 ) {
									$dashboard_urls = array();

									foreach ( $dashboards as $dashboard_name => $dashboard_url ) {
										$dashboard_urls[] = sprintf( '<a href="%s" title="%s">%2$s</a>',
											esc_attr( $dashboard_url ),
											esc_html( ucfirst( $dashboard_name ) )
										);
									}

									$links[] = sprintf( '%s: %s',
										__( 'Dashboards', 'pronamic_ideal' ),
										strtolower( implode( ', ', $dashboard_urls ) )
									);
								}

								// Product link
								if ( $integration->get_product_url() ) {
									$links[] = sprintf( '<a href="%s" target="_blank" title="%s">%2$s</a>',
										$integration->get_product_url(),
										__( 'Product information', 'pronamic_ideal' )
									);
								}

								$description = implode( ' | ', $links );

								printf(
									'<option data-gateway-description="%s" data-pronamic-pay-settings="%s" value="%s" %s class="%s">%s</option>',
									esc_attr( $description ),
									esc_attr( wp_json_encode( $integration->get_settings() ) ),
									esc_attr( $id ),
									selected( $variant_id, $id, false ),
									esc_attr( implode( ' ', $classes ) ),
									esc_attr( $name )
								);
							}

							printf( '</optgroup>' );
						}
					}

					?>
				</select>

				<div id="pronamic-pay-gateway-description"></div>
			</td>
		</tr>
	</table>

	<div class="pronamic-pay-tabs">

		<ul class="pronamic-pay-tabs-items"></ul>

	<?php foreach ( $sections as $id => $section ) : ?>

		<?php

		$classes = array();
		if ( isset( $section['methods'] ) ) {
			$classes[] = 'pronamic-pay-tab';
			$classes[] = 'extra-settings';

			foreach ( $section['methods'] as $method ) {
				$classes[] = 'setting-' . $method;
			}
		}

		?>

		<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
			<?php if ( ! empty( $section['title'] ) || ! empty( $section['description'] ) ) : ?>

				<div class="gateway-config-section-header">
					<?php if ( ! empty( $section['title'] ) ) : ?>

						<h4><?php echo esc_html( $section['title'] ); ?></h4>

					<?php endif; ?>

					<?php if ( ! empty( $section['description'] ) ) : ?>

						<p><?php echo $section['description']; //xss ok ?></p>

					<?php endif; ?>
				</div>

			<?php endif; ?>

			<table class="form-table">

				<?php foreach ( $sections_fields[ $id ] as $field ) : ?>

					<?php

					$classes = array();
					if ( isset( $field['methods'] ) ) {
						$classes[] = 'extra-settings';

						foreach ( $field['methods'] as $method ) {
							$classes[] = 'setting-' . $method;
						}
					}

					if ( isset( $field['group'] ) ) {
						$classes[] = $field['group'];
					}

					if ( isset( $field['show_provider'] ) ) {
						$providers = $field['show_provider'];
						$classes[] = 'show-provider';

						if ( is_string( $providers ) ) {
							$providers = array( $providers );
						}

						if ( is_array( $providers ) ) {
							foreach ( $providers as $provider ) {
								$classes[] = sprintf( 'show-%s', $provider );
							}
						}
					}

					if ( isset( $field['hide_provider'] ) ) {
						$providers = $field['hide_provider'];

						if ( is_string( $providers ) ) {
							$providers = array( $providers );
						}

						if ( is_array( $providers ) ) {
							foreach ( $providers as $provider ) {
								$classes[] = sprintf( 'hide-%s', $provider );
							}
						}
					}

					if ( isset( $field['id'] ) ) {
						$id = $field['id'];
					} elseif ( isset( $field['meta_key'] ) ) {
						$id = $field['meta_key'];
					} else {
						$id = uniqid();
					}

					?>
					<tr class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">

						<?php if ( 'html' !== $field['type'] ) { ?>

						<th scope="col">
							<label for="<?php echo esc_attr( $id ); ?>">
								<?php echo esc_html( $field['title'] ); ?>
							</label>

							<?php

							if ( isset( $field['tooltip'] ) && ! empty( $field['tooltip'] ) ) {
								printf(
									'<span class="dashicons dashicons-editor-help pronamic-pay-tip" data-tip="%s"></span>',
									esc_attr( $field['tooltip'] )
								);
							}

							?>
						</th>

						<?php } ?>

						<td <?php if ( 'html' === $field['type'] ) : ?>colspan="2"<?php endif; ?>>
							<?php

							$attributes = array();
							$attributes['id']   = $id;
							$attributes['name'] = $id;

							$classes = array();
							if ( isset( $field['classes'] ) ) {
								$classes = $field['classes'];
							}

							if ( isset( $field['readonly'] ) && $field['readonly'] ) {
								$attributes['readonly'] = 'readonly';

								$classes[] = 'readonly';
							}

							if ( isset( $field['size'] ) ) {
								$attributes['size'] = $field['size'];
							}

							if ( ! empty( $classes ) ) {
								$attributes['class'] = implode( ' ', $classes );
							}

							$value = '';
							if ( isset( $field['meta_key'] ) ) {
								$attributes['name'] = $field['meta_key'];

								$value = get_post_meta( get_the_ID(), $field['meta_key'], true );
							} elseif ( isset( $field['value'] ) ) {
								$value = $field['value'];
							}

							// Set default
							if ( empty( $value ) && isset( $field['default'] ) ) {
								$value = $field['default'];
							}

							switch ( $field['type'] ) {
								case 'text' :
								case 'password' :
									$attributes['type']  = $field['type'];
									$attributes['value'] = $value;

									printf(
										'<input %s />',
										Pronamic_WP_HTML_Helper::array_to_html_attributes( $attributes )
									);

									break;
								case 'checkbox' :
									$attributes['type']  = $field['type'];
									$attributes['value'] = '1';

									printf(
										'<input %s %s />',
										Pronamic_WP_HTML_Helper::array_to_html_attributes( $attributes ),
										checked( $value, true, false )
									);

									printf( ' ' );

									printf(
										'<label for="%s">%s</label>',
										esc_attr( $attributes['id'] ),
										esc_html( $field['label'] )
									);

									break;
								case 'textarea' :
									$attributes['rows'] = 4;
									$attributes['cols'] = 65;

									printf(
										'<textarea %s />%s</textarea>',
										Pronamic_WP_HTML_Helper::array_to_html_attributes( $attributes ),
										esc_textarea( $value )
									);

									break;
								case 'file' :
									$attributes['type']  = 'file';

									printf(
										'<input %s />',
										Pronamic_WP_HTML_Helper::array_to_html_attributes( $attributes )
									);

									break;
								case 'select' :
									printf(
										'<select %s>%s</select>',
										Pronamic_WP_HTML_Helper::array_to_html_attributes( $attributes ),
										Pronamic_WP_HTML_Helper::select_options_grouped( $field['options'], $value )
									);

									break;
								case 'optgroup' :
									printf( '<fieldset>' );
									printf( '<legend class="screen-reader-text">%s</legend>', esc_html( $field['title'] ) );

									foreach ( $field['options'] as $key => $label ) {
										printf(
											'<label>%s %s</label><br />',
											sprintf(
												'<input type="radio" value="%s" name="%s" %s />',
												esc_attr( $key ),
												esc_attr( $attributes['name'] ),
												checked( $value, $key, false )
											),
											esc_html( $label )
										);
									}

									break;
							}

							if ( isset( $field['html'] ) ) {
								if ( 'description' !== $field['type'] && isset( $field['title'] ) && ! empty( $field['title'] ) ) {
									printf(
										'<strong>%s</strong><br>',
										esc_html( $field['title'] )
									);
								}

								echo $field['html']; //xss ok
							}

							if ( isset( $field['description'] ) ) {
								printf( //xss ok
									'<span class="description">%s</span>',
									$field['description']
								);
							}

							if ( isset( $field['callback'] ) ) {
								$callback = $field['callback'];

								call_user_func( $callback, $field );
							}

							?>

						</td>
					</tr>

				<?php endforeach; ?>

			</table>
		</div>

	<?php endforeach; ?>

	</div>

	<div style="clear:both;"></div>
</div>
