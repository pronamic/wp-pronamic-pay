<?php

$comments = get_comments( array(
	'post_id'      => get_the_ID(),
	'comment_type' => 'payment_note'
) );

if ( empty( $comments ) ) : ?>

	

<?php else : ?>

	<table class="widefat fixed comments comments-box" cellspacing="0">	
		<tbody>
			<?php foreach ( $comments as $comment ) : ?>
			
				<?php 
				
				$html_id    = 'comment-' . $comment->comment_ID;
				$html_class = join( ' ', get_comment_class( wp_get_comment_status( $comment->comment_ID ) ) );
				
				?>
				<tr id="<?php esc_attr( $html_id ); ?>" class="<?php esc_attr( $html_class ); ?>">
					<td>
						<?php 

						printf( __( '%1$s at %2$s' ),
							/* translators: comment date format. See http://php.net/date */
							get_comment_date( __( 'Y/m/d' ), $comment->comment_ID ),
							get_comment_date( get_option( 'time_format' ), $comment->comment_ID )
						);

						?>
					</td>
					<td>
						<?php echo $comment->comment_content; ?>
					</td>
				</tr>
			
			<?php endforeach; ?>
		</tbody>
	</table>

<?php endif; ?>