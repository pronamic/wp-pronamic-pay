<?php

$comments = get_comments( array(
	'post_id' => get_the_ID(),
	'type'    => 'payment_note',
) );

?>
<table class="widefat fixed comments comments-box" cellspacing="0">
	<tbody>

		<?php if ( empty( $comments ) ) : ?>

			<tr class="no-items">
				<td>
					<?php esc_html_e( 'No logs found.', 'pronamic_ideal' ); ?>
				</td>
			</tr>

		<?php else : ?>

			<?php foreach ( $comments as $comment ) : ?>

				<?php

				$html_id    = 'comment-' . $comment->comment_ID;
				$html_class = join( ' ', get_comment_class( wp_get_comment_status( $comment->comment_ID ) ) );

				?>
				<tr id="<?php esc_attr( $html_id ); ?>" class="<?php esc_attr( $html_class ); ?>">
					<td>
						<?php

						printf( esc_html__( '%1$s at %2$s', 'pronamic_ideal' ),
							/* translators: comment date format. See http://php.net/date */
							esc_html( get_comment_date( __( 'Y/m/d', 'pronamic_ideal' ), $comment->comment_ID ) ),
							esc_html( get_comment_date( get_option( 'time_format' ), $comment->comment_ID ) )
						);

						?>
					</td>
					<td>
						<?php comment_text( $comment ); ?>
					</td>
				</tr>

			<?php endforeach; ?>

		<?php endif; ?>

	</tbody>
</table>
