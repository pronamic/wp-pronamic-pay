<?php

class Pronamic_WP_Pay_Subscription {

	protected $id;

	//////////////////////////////////////////////////

	public $key;

	public $frequency;

	public $interval;

	public $interval_period;

	public $transaction_id;

	public $description;

	public $currency;

	public $amount;

	public $status;

	public $source;

	public $source_id;

	public $consumer_name;

	public $consumer_iban;

	public $consumer_bic;

	//////////////////////////////////////////////////

	/**
	 * Meta
	 *
	 * @var array
	 */
	public $meta;

	//////////////////////////////////////////////////

	/**
	 * The subscription post object
	 */
	public $post;

	//////////////////////////////////////////////////

	/**
	 * Construct and initialize payment object
	 *
	 * @param int $post_id
	 */
	public function __construct( $post_id = null ) {
		$this->id   = $post_id;
		$this->meta = array();

		if ( null !== $post_id ) {
			global $pronamic_ideal;

			$pronamic_ideal->subscriptions_data_store->read( $this );
		}
	}

	//////////////////////////////////////////////////

	public function get_id() {
		return $this->id;
	}

	//////////////////////////////////////////////////

	public function get_key() {
		return $this->key;
	}

	//////////////////////////////////////////////////

	public function get_source() {
		return $this->source;
	}

	public function get_source_id() {
		return $this->source_id;
	}

	//////////////////////////////////////////////////

	public function get_frequency() {
		return $this->frequency;
	}

	public function get_interval() {
		return $this->interval;
	}

	public function get_interval_period() {
		return $this->interval_period;
	}

	public function get_description() {
		return $this->description;
	}

	public function get_currency() {
		return $this->currency;
	}

	public function get_amount() {
		return $this->amount;
	}

	//////////////////////////////////////////////////

	public function get_transaction_id() {
		return $this->transaction_id;
	}

	public function set_transaction_id( $transaction_id ) {
		$this->transaction_id = $transaction_id;
	}

	//////////////////////////////////////////////////

	public function get_status() {
		return $this->status;
	}

	public function set_status( $status ) {
		$this->status = $status;
	}

	//////////////////////////////////////////////////

	public function set_consumer_name( $name ) {
		$this->consumer_name = $name;
	}

	public function set_consumer_iban( $iban ) {
		$this->consumer_iban = $iban;
	}

	public function set_consumer_bic( $bic ) {
		$this->consumer_bic = $bic;
	}

	//////////////////////////////////////////////////

	public function add_note( $note ) {
		$commentdata = array(
			'comment_post_ID'      => $this->id,
			'comment_author'       => 'admin',
			'comment_author_email' => 'admin@admin.com',
			'comment_author_url'   => 'http://',
			'comment_content'      => $note,
			'comment_type'         => 'subscription_note',
			'comment_parent'       => 0,
			'user_id'              => 0,
			'comment_approved'     => 1,
		);

		$comment_id = wp_insert_comment( $commentdata );

		return $comment_id;
	}

	//////////////////////////////////////////////////

	public function get_meta( $key ) {
		$key = '_pronamic_subscription_' . $key;

		return get_post_meta( $this->id, $key, true );
	}

	public function set_meta( $key, $value = false ) {
		$key = '_pronamic_subscription_' . $key;

		if ( $value instanceof DateTime ) {
			$value = $value->format( 'Y-m-d H:i:s' );
		}

		if ( empty( $value ) ) {
			return delete_post_meta( $this->id, $key );
		}

		return update_post_meta( $this->id, $key, $value );
	}

	//////////////////////////////////////////////////

	/**
	 * Get source description.
	 *
	 * @return string
	 */
	public function get_source_description() {
		$description = $this->source;

		$payment = $this->get_first_payment();

		if ( $payment ) {
			$description = apply_filters( 'pronamic_payment_source_description', $description,  $payment );
			$description = apply_filters( 'pronamic_payment_source_description_' . $this->source, $description, $payment );
		}

		return $description;
	}

	/**
	 * Get source link for this subscription.
	 *
	 * @return string
	 */
	public function get_source_link() {
		$url = null;

		$payment = $this->get_first_payment();

		if ( $payment ) {
			$url = apply_filters( 'pronamic_payment_source_url', $url, $payment );
			$url = apply_filters( 'pronamic_payment_source_url_' . $this->source, $url, $payment );
		}

		return $url;
	}

	//////////////////////////////////////////////////

	/**
	 * Get cancel URL for this subscription.
	 *
	 * @return string
	 */
	public function get_cancel_url() {
		$cancel_url = add_query_arg(
			array(
				'subscription' => $this->get_id(),
				'key'          => $this->get_key(),
				'action'       => 'cancel',
			), home_url()
		);

		return $cancel_url;
	}

	/**
	 * Get renewal URL for this subscription.
	 *
	 * @return string
	 */
	public function get_renewal_url() {
		$renewal_url = add_query_arg(
			array(
				'subscription' => $this->get_id(),
				'key'          => $this->get_key(),
				'action'       => 'renew',
			), home_url()
		);

		return $renewal_url;
	}

	//////////////////////////////////////////////////

	public function get_payments() {
		return get_pronamic_payments_by_meta( '_pronamic_payment_subscription_id', $this->id );
	}

	public function has_valid_payment() {
		$payments = $this->get_payments();

		foreach ( $payments as $payment ) {
			if ( Pronamic_WP_Pay_Statuses::SUCCESS === $payment->get_status() ) {
				return $payment;
			}
		}

		return false;
	}

	public function get_first_payment() {
		$payments = $this->get_payments();

		if ( count( $payments ) > 0 ) {
			return $payments[0];
		}

		return null;
	}

	//////////////////////////////////////////////////

	public function set_start_date( $value ) {
		$this->set_meta( 'start_date', $value );
	}

	public function get_start_date() {
		$start_date = $this->get_meta( 'start_date' );

		if ( '' !== $start_date ) {
			return new DateTime( $start_date );
		}

		if ( Pronamic_WP_Pay_Statuses::COMPLETED !== $this->get_status() ) {
			return new DateTime( $this->post->post_date_gmt );
		}

		return null;
	}

	//////////////////////////////////////////////////

	public function set_expiry_date( $value ) {
		$this->set_meta( 'expiry_date', $value );
	}

	public function get_expiry_date() {
		$expiry_date = $this->get_meta( 'expiry_date' );

		if ( '' !== $expiry_date ) {
			return new DateTime( $expiry_date );
		}

		// If no meta expiry date is set, use start date + 1 interval period
		$start_date = $this->get_start_date();

		if ( null === $start_date ) {
			return null;
		}

		return $start_date->modify( sprintf(
			'+%d %s',
			$this->get_interval(),
			Pronamic_WP_Util::to_interval_name( $this->get_interval_period() )
		) );
	}

	//////////////////////////////////////////////////

	public function set_first_payment_date( $value ) {
		$this->set_meta( 'first_payment', $value );
	}

	public function get_first_payment_date() {
		$first_date = $this->get_meta( 'first_payment' );

		if ( '' !== $first_date ) {
			return new DateTime( $first_date );
		}

		return new DateTime( $this->post->post_date_gmt );
	}

	//////////////////////////////////////////////////

	public function set_final_payment_date( $value ) {
		$this->set_meta( 'final_payment', $value );
	}

	public function get_final_payment_date() {
		$final = $this->get_meta( 'final_payment' );

		if ( '' !== $final ) {
			return new DateTime( $final );
		}

		// If no frequency is set, use next payment or start date.
		$frequency = $this->get_frequency();

		if ( '' === $frequency ) {
			$next_date = $this->get_next_payment_date();

			if ( null === $next_date ) {
				return $this->get_start_date();
			}

			return $next_date;
		}

		// Add frequency * interval period to first payment date.
		$first_date = $this->get_first_payment_date();

		return $first_date->modify( sprintf(
			'+%d %s',
			( $frequency - 1 ) * $this->get_interval(),
			Pronamic_WP_Util::to_interval_name( $this->get_interval_period() )
		) );
	}

	//////////////////////////////////////////////////

	public function set_next_payment_date( $value ) {
		$this->set_meta( 'next_payment', $value );

		if ( false === $value ) {
			$this->set_renewal_notice_date( false );
		}
	}

	public function get_next_payment_date( $cycle = 0 ) {
		// meta next_payment, possibly doesn't exist if last payment has been processed.

		$next_payment = $this->get_meta( 'next_payment' );

		if ( '' === $next_payment ) {
			return;
		}

		$next = new DateTime( $next_payment );

		if ( 0 !== $cycle ) {
			$next->modify( sprintf(
				'+%d %s',
				( $cycle * $this->get_interval() ),
				Pronamic_WP_Util::to_interval_name( $this->get_interval_period() )
			) );
		}

		return $next;
	}

	private function refresh_next_payment_date() {
		if ( null !== $this->get_next_payment_date() ) {
			// Only set next payment date if not set already.
			return;
		}

		$expiry = $this->get_expiry_date();
		$now    = new DateTime( 'now', new DateTimeZone( Pronamic_IDeal_IDeal::TIMEZONE ) );

		if ( $expiry > $now ) {
			// Expiry date is in the future, use it.
			$next_payment = $expiry;
		} else {
			$next_payment = $now;
		}

		$this->set_next_payment_date( $next_payment );

		// Update renewal notice date
		$next_renewal = new DateTime( $next_payment->format( DateTime::ISO8601 ) );
		$next_renewal->modify( '-1 week' );

		$this->set_renewal_notice_date( $next_renewal );
	}

	//////////////////////////////////////////////////

	public function set_renewal_notice_date( $value ) {
		$this->set_meta( 'renewal_notice', $value );
	}

	public function get_renewal_notice_date() {
		$renewal_notice = $this->get_meta( 'renewal_notice' );

		if ( '' !== $renewal_notice ) {
			return $renewal_notice;
		}

		return false;
	}

	//////////////////////////////////////////////////

	public function update_status( $status, $note = null ) {
		if ( Pronamic_WP_Pay_Statuses::ACTIVE === $status ) {
			$status = Pronamic_WP_Pay_Statuses::SUCCESS;
		}

		$this->set_status( $status );

		switch ( $status ) {
			case Pronamic_WP_Pay_Statuses::OPEN :
				$meta_status = $this->get_meta( 'status' );

				if ( $meta_status !== Pronamic_WP_Pay_Statuses::OPEN ) {
					$this->refresh_next_payment_date();

					if ( ! $note ) {
						$this->add_note( __( "Subscription status changed to 'Open'", 'pronamic_ideal' ) );
					}
				}

				break;
			case Pronamic_WP_Pay_Statuses::SUCCESS :
				$meta_status = $this->get_meta( 'status' );

				if ( $meta_status !== Pronamic_WP_Pay_Statuses::SUCCESS ) {
					$this->refresh_next_payment_date();

					if ( ! $note ) {
						$this->add_note( __( "Subscription status changed to 'Active'", 'pronamic_ideal' ) );
					}
				}

				break;
			case Pronamic_WP_Pay_Statuses::FAILURE :
				if ( ! $note ) {
					$this->add_note( __( "Subscription status changed to 'Failed'", 'pronamic_ideal' ) );
				}

				break;
			case Pronamic_WP_Pay_Statuses::CANCELLED :
				$this->set_next_payment_date( false );

				if ( ! $note ) {
					$this->add_note( __( "Subscription status changed to 'Cancelled'", 'pronamic_ideal' ) );
				}

				break;
			case Pronamic_WP_Pay_Statuses::COMPLETED :
				$this->set_next_payment_date( false );
				$this->set_start_date( false );

				if ( ! $note ) {
					$this->add_note( __( "Subscription status changed to 'Completed'", 'pronamic_ideal' ) );
				}

				break;
		}

		if ( $note ) {
			$this->add_note( $note );
		}

		// Note: make sure Pronamic_WP_Pay_Plugin::update_subscription( $subscription, $can_redirect ) is called after the status has been updated!
	}

	public function update_meta( $meta ) {
		if ( ! is_array( $meta ) || count( $meta ) === 0 ) {
			return;
		}

		$note = sprintf(
			'<p>%s:</p>',
			__( 'Subscription changed', 'pronamic_ideal' )
		);

		$note .= '<dl>';

		foreach ( $meta as $key => $value ) {
			$this->set_meta( $key, $value );

			if ( $value instanceof DateTime ) {
				$value = date_i18n( __( 'l jS \o\f F Y, h:ia', 'pronamic_ideal' ), $value->getTimestamp() );
			}

			$note .= sprintf( '<dt>%s</dt>', esc_html( $key ) );
			$note .= sprintf( '<dd>%s</dd>', esc_html( $value ) );
		}

		$note .= '</dl>';

		$this->add_note( $note );
	}
}
