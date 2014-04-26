<?php

function pronamic_ideal_create_pages( $pages, $parent = null ) {
	foreach ( $pages as $page ) {
		$post = array(
			'post_title'     => $page['post_title'],
			'post_name'      => $page['post_title'],
			'post_content'   => $page['post_content'],
			'post_status'    => 'publish',
			'post_type'      => 'page',
			'comment_status' => 'closed',
		);

		if ( isset( $parent ) ) {
			$post['post_parent'] = $parent;
		}

		$result = wp_insert_post( $post, true );

		if ( ! is_wp_error( $result ) ) {
			if ( isset( $page['post_meta'] ) ) {
				foreach ( $page['post_meta'] as $key => $value ) {
					update_post_meta( $result, $key, $value );
				}
			}

			if ( isset( $page['children'] ) ) {
				pronamic_ideal_create_pages( $page['children'], $result );
			}
		}
	}
}

if ( ! empty( $_POST ) && check_admin_referer( 'pronamic_ideal_pages_generator', 'pronamic_ideal_nonce' ) ) {
	pronamic_ideal_create_pages( $_POST['pronamic_ideal_pages'] );
}

?>
<div class="wrap">
	<h2>
		<?php _e( 'iDEAL Pages Generator', 'pronamic_ideal' ); ?>
	</h2>

	<p>
		<?php _e( 'This page allows you to easily create pages for each iDEAL payment status.', 'pronamic_ideal' ); ?>
	</p>

	<form action="" method="post">
		<?php wp_nonce_field( 'pronamic_ideal_pages_generator', 'pronamic_ideal_nonce' ); ?>

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

		function pronamic_ideal_pages( $posts, $name_prefix, $level = 0 ) {
			?>
			<ul style="padding-left: <?php echo $level * 25; ?>px">

				<?php foreach ( $posts as $i => $post ) : ?>

					<li>
						<?php $name = $name_prefix . '[' . $i . ']'; ?>

						<h3><?php echo $post['post_title']; ?></h3>

						<table class="form-table">
							<tr>
								<th scope="row">
									<label for="pronamic_ideal_page_<?php echo $i; ?>_post_title">
										<?php _e( 'Title', 'pronamic_ideal' ); ?>
									</label>
								</th>
								<td>
					                <input id="pronamic_ideal_page_<?php echo $i; ?>_post_title" name="<?php echo $name; ?>[post_title]" value="<?php echo $post['post_title']; ?>" type="text" class="regular-text" />
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="pronamic_ideal_page_<?php echo $i; ?>_post_name">
										<?php _e( 'Slug', 'pronamic_ideal' ); ?>
									</label>
								</th>
								<td>
					                <input id="pronamic_ideal_page_<?php echo $i; ?>_post_name" name="<?php echo $name; ?>[post_name]" value="<?php echo $post['post_name']; ?>" type="text" class="regular-text" />
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="pronamic_ideal_page_<?php echo $i; ?>_post_content">
										<?php _e( 'Content', 'pronamic_ideal' ); ?>
									</label>
								</th>
								<td>
					                <textarea id="pronamic_ideal_page_<?php echo $i; ?>_post_content" name="<?php echo $name; ?>[post_content]" rows="2" cols="60"><?php echo $post['post_content']; ?></textarea>
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
							pronamic_ideal_pages( $post['children'], $name . '[children]', $level + 1 );
						}

						?>
					</li>

				<?php endforeach; ?>

			</ul>
			<?php
		}

		pronamic_ideal_pages( $pages, 'pronamic_ideal_pages' );

		submit_button(
			__( 'Generate Pages', 'pronamic_ideal' ),
			'primary',
			'create_pages'
		);

		?>
	</form>

	<?php include 'pronamic.php'; ?>
</div>