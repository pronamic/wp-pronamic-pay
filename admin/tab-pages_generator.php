<h3><?php _e( 'Pages Generator', 'pronamic_ideal' ); ?></h3>

<?php

if ( filter_has_var( INPUT_GET, 'message' ) ) {
	$message_id = filter_input( INPUT_GET, 'message', FILTER_SANITIZE_STRING );

	if ( '1' === $message_id ) {
		printf(
			'<div id="message" class="updated"><p>%s</p></div>',
			__( 'Pages created.', 'pronamic_ideal' )
		);
	}
}

?>

<p>
	<?php _e( 'This page allows you to easily create pages for each iDEAL payment status.', 'pronamic_ideal' ); ?>
</p>

<form action="" method="post">
	<?php wp_nonce_field( 'pronamic_pay_create_pages', 'pronamic_pay_nonce' ); ?>

	<?php

	$pages = array(
		'ideal' => array(
			'post_title'   => __( 'iDEAL', 'pronamic_ideal' ),
			'post_name'    => __( 'ideal', 'pronamic_ideal' ),
			'post_content' => '',
			'post_meta'    => array(
				'_yoast_wpseo_meta-robots-noindex' => true,
			),
			'children'     => array(
				'error'     => array(
					'post_title'   => __( 'iDEAL payment error', 'pronamic_ideal' ),
					'post_name'    => __( 'error', 'pronamic_ideal' ),
					'post_content' => __( '<p>Unfortunately an error has occurred during your iDEAL payment.</p>', 'pronamic_ideal' ),
					'post_meta'    => array(
						'_yoast_wpseo_meta-robots-noindex' => true,
					),
				),
				'cancel'    => array(
					'post_title'   => __( 'iDEAL payment canceled', 'pronamic_ideal' ),
					'post_name'    => __( 'cancelled', 'pronamic_ideal' ),
					'post_content' => __( '<p>You canceled the iDEAL payment.</p>', 'pronamic_ideal' ),
					'post_meta'    => array(
						'_yoast_wpseo_meta-robots-noindex' => true,
					),
				),
				'unknown'   => array(
					'post_title'   => __( 'iDEAL payment unknown', 'pronamic_ideal' ),
					'post_name'    => __( 'unknown', 'pronamic_ideal' ),
					'post_content' => __( '<p>The status of your iDEAL payment is unknown.</p>', 'pronamic_ideal' ),
					'post_meta'    => array(
						'_yoast_wpseo_meta-robots-noindex' => true,
					),
				),
				'expired'   => array(
					'post_title'   => __( 'iDEAL payment expired', 'pronamic_ideal' ),
					'post_name'    => __( 'expired', 'pronamic_ideal' ),
					'post_content' => __( '<p>Unfortunately your iDEAL payment session has expired.</p>', 'pronamic_ideal' ),
					'post_meta'    => array(
						'_yoast_wpseo_meta-robots-noindex' => true,
					),
				),
				'completed' => array(
					'post_title'   => __( 'iDEAL payment completed', 'pronamic_ideal' ),
					'post_name'    => __( 'completed', 'pronamic_ideal' ),
					'post_content' => __( '<p>The payment process is successfully completed.</p>', 'pronamic_ideal' ),
					'post_meta'    => array(
						'_yoast_wpseo_meta-robots-noindex' => true,
					),
				),
			),
		),
	);

	function pronamic_pay_pages( $posts, $name_prefix, $level = 0 ) {
		?>
		<ul style="padding-left: <?php echo $level * 25; ?>px">

			<?php foreach ( $posts as $i => $post ) : ?>

				<li>
					<?php $name = $name_prefix . '[' . $i . ']'; ?>

					<h4><?php echo $post['post_title']; ?></h4>

					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="pronamic_pay_page_<?php echo $i; ?>_post_title">
									<?php _e( 'Title', 'pronamic_ideal' ); ?>
								</label>
							</th>
							<td>
				                <input id="pronamic_pay_page_<?php echo $i; ?>_post_title" name="<?php echo $name; ?>[post_title]" value="<?php echo $post['post_title']; ?>" type="text" class="regular-text" />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="pronamic_pay_page_<?php echo $i; ?>_post_name">
									<?php _e( 'Slug', 'pronamic_ideal' ); ?>
								</label>
							</th>
							<td>
				                <input id="pronamic_pay_page_<?php echo $i; ?>_post_name" name="<?php echo $name; ?>[post_name]" value="<?php echo $post['post_name']; ?>" type="text" class="regular-text" />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="pronamic_pay_page_<?php echo $i; ?>_post_content">
									<?php _e( 'Content', 'pronamic_ideal' ); ?>
								</label>
							</th>
							<td>
				                <textarea id="pronamic_pay_page_<?php echo $i; ?>_post_content" name="<?php echo $name; ?>[post_content]" rows="2" cols="60"><?php echo $post['post_content']; ?></textarea>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<?php _e( 'Meta', 'pronamic_ideal' ); ?>
							</th>
							<td>
				                <?php if ( isset( $post['post_meta'] ) ) : ?>

				                	<dl>
				                		<?php foreach ( $post['post_meta'] as $key => $value ) : ?>

				                			<dt>
				                				<?php echo $key; ?>
				                			</dt>
				                			<dd>
				                				<input name="<?php echo $name; ?>[post_meta][<?php echo $key; ?>]" value="<?php echo $value; ?>" type="text" class="regular-text" />
				                			</dd>

				                		<?php endforeach; ?>
				                	</dl>

								<?php endif; ?>
							</td>
						</tr>
					</table>

					<?php

					if ( isset( $post['children'] ) ) {
						pronamic_pay_pages( $post['children'], $name . '[children]', $level + 1 );
					}

					?>
				</li>

			<?php endforeach; ?>

		</ul>
		<?php
	}

	pronamic_pay_pages( $pages, 'pronamic_pay_pages' );

	submit_button(
		__( 'Generate Pages', 'pronamic_ideal' ),
		'primary',
		'pronamic_pay_create_pages'
	);

	?>
</form>