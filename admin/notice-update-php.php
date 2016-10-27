<div class="error">
	<p>
		<strong><?php esc_html_e( 'Pronamic iDEAL', 'pronamic_ideal' ); ?></strong> —
		<?php

		echo wp_kses(
			sprintf(
				__( 'Unfortunately the Pronamic iDEAL plugin will no longer work correctly in PHP versions older than 5.3. Read more information about <a href="%1$s" target="%2$s">how you can update</a>.', 'pronamic_ideal' ),
				esc_attr__( 'http://www.wpupdatephp.com/update/', 'pronamic_ideal' ),
				esc_attr( '_blank' )
			),
			array(
				'a' => array(
					'href'   => true,
					'target' => true,
				),
			)
		);

		?>
	</p>
</div>
