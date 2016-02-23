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
					<?php esc_html_e( 'Variant', 'pronamic_ideal' ); ?>
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

								if ( isset( $integration->deprecated ) && $integration->deprecated  ) {
									$classes[] = 'deprecated';

									$name = sprintf( __( '%s (obsoleted)', 'pronamic_ideal' ), $name );

									if ( $variant_id !== $id ) {
										continue;
									}
								}

								printf(
									'<option data-pronamic-pay-settings="%s" value="%s" %s class="%s">%s</option>',
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
			</td>
		</tr>
	</table>

	<?php foreach ( $sections as $id => $section ) : ?>

		<?php

		$classes = array();
		if ( isset( $section['methods'] ) ) {
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

						<p><?php echo esc_html( $section['description'] ); ?></p>

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

					if ( isset( $field['id'] ) ) {
						$id = $field['id'];
					} elseif ( isset( $field['meta_key'] ) ) {
						$id = $field['meta_key'];
					} else {
						$id = uniqid();
					}

					?>
					<tr class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
						<th scope="col">
							<label for="<?php echo esc_attr( $id ); ?>">
								<?php echo esc_html( $field['title'] ); ?>
							</label>
						</th>
						<td>
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

							if ( isset( $field['description'] ) ) {
								printf( //xss ok
									'<span class="description"><br />%s</span>',
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

	<div class="extra-settings method-ideal_advanced_v3">
		<h4>
			<?php esc_html_e( 'Private Key and Certificate Generator', 'pronamic_ideal' ); ?>
		</h4>

		<p>
			<?php esc_html_e( 'You have to use the following commands to generate an private key and certificate for iDEAL v3:', 'pronamic_ideal' ); ?>
		</p>

		<table class="form-table">
			<tr>
				<th scope="col">
					<label for="pronamic_ideal_openssl_command_key">
						<?php esc_html_e( 'Private Key', 'pronamic_ideal' ); ?>
					</label>
				</th>
				<td>
					<?php

					$private_key_password = get_post_meta( get_the_ID(), '_pronamic_gateway_ideal_private_key_password', true );
					$number_days_valid    = get_post_meta( get_the_ID(), '_pronamic_gateway_number_days_valid', true );

					$filename = __( 'filename', 'pronamic_ideal' );

					$command = sprintf(
						'openssl genrsa -aes128 -out %s.key -passout pass:%s 2048',
						$filename,
						$private_key_password
					);

					?>
					<input id="pronamic_ideal_openssl_command_key" name="pronamic_ideal_openssl_command_key" value="<?php echo esc_attr( $command ); ?>" type="text" class="large-text code" readonly="readonly" />
				</td>
			</tr>
			<tr>
				<th scope="col">
					<label for="pronamic_ideal_openssl_command_certificate">
						<?php esc_html_e( 'Private Certificate', 'pronamic_ideal' ); ?>
					</label>
				</th>
				<td>
					<?php

					// @see http://www.openssl.org/docs/apps/req.html
					$subj_args = array(
						'C'            => get_post_meta( get_the_ID(), '_pronamic_gateway_country', true ),
						'ST'           => get_post_meta( get_the_ID(), '_pronamic_gateway_state_or_province', true ),
						'L'            => get_post_meta( get_the_ID(), '_pronamic_gateway_locality', true ),
						'O'            => get_post_meta( get_the_ID(), '_pronamic_gateway_organization', true ),
						'OU'           => get_post_meta( get_the_ID(), '_pronamic_gateway_organization_unit', true ),
						'CN'           => get_post_meta( get_the_ID(), '_pronamic_gateway_common_name', true ),
						'emailAddress' => get_post_meta( get_the_ID(), '_pronamic_gateway_email', true ),
					);

					$subj_args = array_filter( $subj_args );

					$subj = '';
					foreach ( $subj_args as $type => $value ) {
						$subj .= '/' . $type . '=' . addslashes( $value );
					}

					$command = trim( sprintf(
						'openssl req -x509 -sha256 -new -key %s.key -passin pass:%s -days %d -out %s.cer %s',
						$filename,
						$private_key_password,
						$number_days_valid,
						$filename,
						empty( $subj ) ? '' : sprintf( "-subj '%s'", $subj )
					) );

					?>
					<input id="pronamic_ideal_openssl_command_certificate" name="pronamic_ideal_openssl_command_certificate" value="<?php echo esc_attr( $command ); ?>" type="text" class="large-text code" readonly="readonly" />
				</td>
			</tr>
		</table>
	</div>
</div>
