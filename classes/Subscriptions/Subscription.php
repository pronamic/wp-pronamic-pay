<?php
/**
 * Subscription
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Subscriptions
 */

namespace Pronamic\WordPress\Pay\Subscriptions;

use DateInterval;
use DateTime;
use DateTimeZone;
use Pronamic\WordPress\Pay\Plugin;
use Pronamic_IDeal_IDeal;
use Pronamic\WordPress\Pay\Core\Statuses;
use Pronamic\WordPress\Pay\Util;

/**
 * Subscription.
 */
class Subscription {
	/**
	 * The ID of this subscription.
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * The key of this subscription, used in URL's for security.
	 *
	 * @var string
	 */
	public $key;

	/**
	 * The frequency of this subscription, for example: `daily`, `weekly`, `monthly` or `annually`.
	 *
	 * @var string
	 */
	public $frequency;

	/**
	 * The interval of this subscription, for example: 1, 2, 3, etc.
	 *
	 * @todo Improve documentation?
	 * @var  int
	 */
	public $interval;

	/**
	 * The interval period of this subscription.
	 *
	 * @todo Improve documentation?
	 * @var  int
	 */
	public $interval_period;

	/**
	 * The transaction ID of this subscription.
	 *
	 * @todo Is this required within a transaction?
	 * @var string
	 */
	public $transaction_id;

	/**
	 * The description of this subscription.
	 *
	 * @todo Is this required within a transaction?
	 * @var string
	 */
	public $description;

	/**
	 * The currency of this subscription, for example 'EUR' or 'USD'.
	 *
	 * @var string
	 */
	public $currency;

	/**
	 * The amount of this subscription, for example 18.95.
	 *
	 * @var float
	 */
	public $amount;

	/**
	 * The status of this subscription, for example 'Success'.
	 *
	 * @todo How to reference to a class constant?
	 * @see  Pronamic\WordPress\Pay\Core\Statuses
	 * @var  string
	 */
	public $status;

	/**
	 * Identifier for the source which started this subsription.
	 * For example: 'woocommerce', 'gravityforms', 'easydigitaldownloads', etc.
	 *
	 * @var string
	 */
	public $source;

	/**
	 * Unique ID at the source which started this subscription, for example:
	 * - WooCommerce order ID.
	 * - Easy Digital Downloads payment ID.
	 * - Gravity Forms entry ID.
	 *
	 * @var string
	 */
	public $source_id;

	/**
	 * The name of the consumer of this subscription.
	 *
	 * @todo Is this required and should we add the 'consumer' part?
	 * @var  string
	 */
	public $consumer_name;

	/**
	 * The IBAN of the consumer of this subscription.
	 *
	 * @todo Is this required and should we add the 'consumer' part?
	 * @var  string
	 */
	public $consumer_iban;

	/**
	 * The BIC of the consumer of this subscription.
	 *
	 * @todo Is this required and should we add the 'consumer' part?
	 * @var  string
	 */
	public $consumer_bic;

	/**
	 * The order ID of this subscription.
	 *
	 * @todo Is this required?
	 * @var  string
	 */
	public $order_id;

	/**
	 * The address of the consumer of this subscription.
	 *
	 * @todo Is this required?
	 * @var  string
	 */
	public $address;

	/**
	 * The city of the consumer of this subscription.
	 *
	 * @todo Is this required?
	 * @var  string
	 */
	public $city;

	/**
	 * The ZIP of the consumer of this subscription.
	 *
	 * @todo Is this required?
	 * @var  string
	 */
	public $zip;

	/**
	 * The country of the consumer of this subscription.
	 *
	 * @todo Is this required?
	 * @var  string
	 */
	public $country;

	/**
	 * The telephone number of the consumer of this subscription.
	 *
	 * @todo Is this required?
	 * @var  string
	 */
	public $telephone_number;

	/**
	 * The gateway configuration ID to use with this subscription.
	 *
	 * @todo Should we improve the name of this var?
	 * @var  string
	 */
	public $config_id;

	/**
	 * The email of the consumer of this subscription.
	 *
	 * @todo Is this required?
	 * @var  string
	 */
	public $email;

	/**
	 * The customer name of the consumer of this subscription.
	 *
	 * @todo Is this required?
	 * @var  string
	 */
	public $customer_name;

	/**
	 * The payment method which was used to create this subscription.
	 *
	 * @var  string
	 */
	public $payment_method;

	/**
	 * The date when this subscirption started.
	 *
	 * @var \DateTimeInterface
	 */
	public $start_date;

	/**
	 * The end date of the last succesfull payment.
	 *
	 * @var \DateTimeInterface
	 */
	public $expiry_date;

	/**
	 * The date of the first payment.
	 *
	 * @todo Is this required? And is this not equeal to start date of the subscription?
	 * @var \DateTimeInterface
	 */
	public $first_payment;

	/**
	 * The next payment date.
	 *
	 * @todo Is this required?
	 * @var \DateTimeInterface
	 */
	public $next_payment;

	/**
	 * The final payment date, can be null if the subscrption never ends.
	 *
	 * @todo Is this required?
	 * @var \DateTimeInterface
	 */
	public $final_payment;

	/**
	 * The renewal notice date.
	 *
	 * @todo Is this required?
	 * @var \DateTimeInterface
	 */
	public $renewal_notice;

	/**
	 * Array for extra meta data to store with this subscription.
	 *
	 * @var array
	 */
	public $meta;

	/**
	 * WordPress post object related to this subscription.
	 *
	 * @var \WP_Post
	 */
	public $post;

	/**
	 * Construct and initialize payment object.
	 *
	 * @param int $post_id A subscription post ID or null.
	 */
	public function __construct( $post_id = null ) {
		$this->id   = $post_id;
		$this->date = new \DateTime();
		$this->meta = array();

		if ( null !== $post_id ) {
			global $pronamic_ideal;

			$pronamic_ideal->subscriptions_data_store->read( $this );
		}
	}

	/**
	 * Get the ID of this subscription.
	 *
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Set the ID of this subscription.
	 *
	 * @param string $id The ID of this subscription.
	 */
	public function set_id( $id ) {
		$this->id = $id;
	}

	/**
	 * Get the unique key of this subscription.
	 *
	 * @return string
	 */
	public function get_key() {
		return $this->key;
	}

	/**
	 * Get the source identifier of this subscription, for example: 'woocommerce', 'gravityforms', etc.
	 *
	 * @return string
	 */
	public function get_source() {
		return $this->source;
	}

	/**
	 * Get the source ID of this subscription, for example a WooCommerce order ID or a Gravity Forms entry ID.
	 *
	 * @return string
	 */
	public function get_source_id() {
		return $this->source_id;
	}

	/**
	 * Get the frequency of this subscription, for example: 'daily', 'weekly', 'monthly' or 'annually'.
	 *
	 * @return string
	 */
	public function get_frequency() {
		return $this->frequency;
	}

	/**
	 * Get the interval, for example: 1, 2, 3, 4, etc., this specifies for example:
	 * - Repeat every *2* days
	 * - Repeat every *1* months
	 * - Repeat every *2* year
	 *
	 * @return int
	 */
	public function get_interval() {
		return $this->interval;
	}

	/**
	 * Get the interval period, for example 'D', 'M', 'Y', etc.
	 *
	 * @see    http://php.net/manual/en/dateinterval.construct.php#refsect1-dateinterval.construct-parameters
	 * @return string
	 */
	public function get_interval_period() {
		return $this->interval_period;
	}

	/**
	 * Get date interval.
	 *
	 * @see http://php.net/manual/en/dateinterval.construct.php#refsect1-dateinterval.construct-parameters
	 * @return \DateInterval
	 * @throws \Exception    Throws an Exception when the `interval_spec` cannot be parsed as an interval.
	 */
	public function get_date_interval() {
		$interval_spec = 'P' . $this->interval . $this->interval_period;

		$interval = new DateInterval( $interval_spec );

		return $interval;
	}

	/**
	 * Get the description of this subscription.
	 *
	 * @return string
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * Get the currency of this subscription.
	 *
	 * @return string
	 */
	public function get_currency() {
		return $this->currency;
	}

	/**
	 * Get the amount of this subscription.
	 *
	 * @return float
	 */
	public function get_amount() {
		return $this->amount;
	}

	/**
	 * Get the transaction ID of this subscription.
	 *
	 * @return string
	 */
	public function get_transaction_id() {
		return $this->transaction_id;
	}

	/**
	 * Set the transaction ID of this subscription.
	 *
	 * @param string $transaction_id A transaction ID.
	 */
	public function set_transaction_id( $transaction_id ) {
		$this->transaction_id = $transaction_id;
	}

	/**
	 * Get the status of this subscription.
	 *
	 * @todo   Check constant?
	 * @return string
	 */
	public function get_status() {
		return $this->status;
	}

	/**
	 * Set the status of this subscription.
	 *
	 * @todo  Check constant?
	 * @param string $status A status string.
	 */
	public function set_status( $status ) {
		$this->status = $status;
	}

	/**
	 * Set consumer name.
	 *
	 * @param string $name A name.
	 */
	public function set_consumer_name( $name ) {
		$this->consumer_name = $name;
	}

	/**
	 * Set consumer IBAN.
	 *
	 * @param string $iban A IBAN.
	 */
	public function set_consumer_iban( $iban ) {
		$this->consumer_iban = $iban;
	}

	/**
	 * Set consumer BIC.
	 *
	 * @param string $bic A BIC.
	 */
	public function set_consumer_bic( $bic ) {
		$this->consumer_bic = $bic;
	}

	/**
	 * Add the specified note to this subscription.
	 *
	 * @param string $note A Note.
	 */
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

	/**
	 * Get meta by the specified meta key.
	 *
	 * @param string $key A meta key.
	 * @return string
	 */
	public function get_meta( $key ) {
		$key = '_pronamic_subscription_' . $key;

		return get_post_meta( $this->id, $key, true );
	}

	/**
	 * Set meta data.
	 *
	 * @param  string $key   A meta key.
	 * @param  mixed  $value A meta value.
	 *
	 * @return boolean        True on successful update, false on failure.
	 */
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

	/**
	 * Get source description.
	 *
	 * @return string
	 */
	public function get_source_description() {
		$description = $this->source;

		$payment = $this->get_first_payment();

		if ( $payment ) {
			$description = apply_filters( 'pronamic_payment_source_description', $description, $payment );
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

	/**
	 * Get all the payments for this subscription.
	 *
	 * @return array
	 */
	public function get_payments() {
		return get_pronamic_payments_by_meta( '_pronamic_payment_subscription_id', $this->id );
	}

	/**
	 * Check if this subscription has one successful payment.
	 *
	 * @return boolean True if one successful payment was found, false otherwise.
	 */
	public function has_valid_payment() {
		$payments = $this->get_payments();

		foreach ( $payments as $payment ) {
			if ( Statuses::SUCCESS === $payment->get_status() ) {
				return $payment;
			}
		}

		return false;
	}

	/**
	 * Get the first payment of this subscription.
	 *
	 * @return Payment
	 */
	public function get_first_payment() {
		$payments = $this->get_payments();

		if ( count( $payments ) > 0 ) {
			return $payments[0];
		}

		return null;
	}

	/**
	 * Set the start date of this subscription.
	 *
	 * @todo  Should we set meta directly?
	 * @param string $value A date value.
	 */
	public function set_start_date( $value ) {
		$this->set_meta( 'start_date', $value );
	}

	/**
	 * Get the start date of this subscription.
	 *
	 * @todo Should we handle logic in this getter?
	 * @return \DateTimeInterface
	 */
	public function get_start_date() {
		$start_date = $this->get_meta( 'start_date' );

		if ( '' !== $start_date ) {
			return new DateTime( $start_date );
		}

		if ( Statuses::COMPLETED !== $this->get_status() ) {
			return new DateTime( $this->post->post_date_gmt );
		}

		return null;
	}

	/**
	 * Set the expiry date of this subscription.
	 *
	 * @todo  Should we set meta directly?
	 * @param string $value A date value.
	 */
	public function set_expiry_date( $value ) {
		$this->set_meta( 'expiry_date', $value );
	}

	/**
	 * Get the expiry date of this subscription.
	 *
	 * @todo Should we handle logic in this getter?
	 * @return \DateTimeInterface
	 */
	public function get_expiry_date() {
		$expiry_date = $this->get_meta( 'expiry_date' );

		if ( '' !== $expiry_date ) {
			return new DateTime( $expiry_date );
		}

		// If no meta expiry date is set, use start date + 1 interval period.
		$start_date = $this->get_start_date();

		if ( null === $start_date ) {
			return null;
		}

		return $start_date->modify(
			sprintf(
				'+%d %s',
				$this->get_interval(),
				Util::to_interval_name( $this->get_interval_period() )
			)
		);
	}

	/**
	 * Set the first payment date of this subscription.
	 *
	 * @todo  Should we set meta directly?
	 * @param string $value A date value.
	 */
	public function set_first_payment_date( $value ) {
		$this->set_meta( 'first_payment', $value );
	}

	/**
	 * Get the first payment date of this subscription.
	 *
	 * @todo Should we handle logic in this getter?
	 * @return \DateTimeInterface
	 */
	public function get_first_payment_date() {
		$first_date = $this->get_meta( 'first_payment' );

		if ( '' !== $first_date ) {
			return new DateTime( $first_date );
		}

		return new DateTime( $this->post->post_date_gmt );
	}

	/**
	 * Set the final payment date of this subscription.
	 *
	 * @todo  Should we set meta directly?
	 * @param string $value A date value.
	 */
	public function set_final_payment_date( $value ) {
		$this->set_meta( 'final_payment', $value );
	}

	/**
	 * Get the final payment date of this subscription.
	 *
	 * @todo Should we handle logic in this getter?
	 * @return \DateTimeInterface
	 */
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

		return $first_date->modify(
			sprintf(
				'+%d %s',
				( $frequency - 1 ) * $this->get_interval(),
				Util::to_interval_name( $this->get_interval_period() )
			)
		);
	}

	/**
	 * Set the next payment date of this subscription.
	 *
	 * @todo  Should we set meta directly?
	 * @param string $value A date value.
	 */
	public function set_next_payment_date( $value ) {
		$this->set_meta( 'next_payment', $value );

		if ( false === $value ) {
			$this->set_renewal_notice_date( false );
		}
	}

	/**
	 * Get the next payment date of this subscription.
	 *
	 * @todo   Should we handle logic in this getter?
	 * @param  int $cycle TODO.
	 * @return \DateTimeInterface
	 */
	public function get_next_payment_date( $cycle = 0 ) {
		// Meta next_payment, possibly doesn't exist if last payment has been processed.
		$next_payment = $this->get_meta( 'next_payment' );

		if ( '' === $next_payment ) {
			return;
		}

		$next = new DateTime( $next_payment );

		if ( 0 !== $cycle ) {
			$next->modify(
				sprintf(
					'+%d %s',
					( $cycle * $this->get_interval() ),
					Util::to_interval_name( $this->get_interval_period() )
				)
			);
		}

		return $next;
	}

	/**
	 * Refresh next payment date.
	 *
	 * @todo Should we handle logic like this in a private function?
	 */
	private function refresh_next_payment_date() {
		if ( null !== $this->get_next_payment_date() ) {
			// Only set next payment date if not set already.
			return;
		}

		$expiry = $this->get_expiry_date();
		$now    = new DateTime( 'now', new DateTimeZone( Plugin::TIMEZONE ) );

		if ( $expiry > $now ) {
			// Expiry date is in the future, use it.
			$next_payment = $expiry;
		} else {
			$next_payment = $now;
		}

		$this->set_next_payment_date( $next_payment );

		// Update renewal notice date.
		$next_renewal = new DateTime( $next_payment->format( DateTime::ISO8601 ) );
		$next_renewal->modify( '-1 week' );

		$this->set_renewal_notice_date( $next_renewal );
	}

	/**
	 * Set the renewal notice date of this subscription.
	 *
	 * @todo  Should we set meta directly?
	 * @param string $value A date value.
	 */
	public function set_renewal_notice_date( $value ) {
		$this->set_meta( 'renewal_notice', $value );
	}

	/**
	 * Get the renewal notice date of this subscription.
	 *
	 * @todo   Should we handle logic in this getter?
	 * @return \DateTimeInterface
	 */
	public function get_renewal_notice_date() {
		$renewal_notice = $this->get_meta( 'renewal_notice' );

		if ( '' !== $renewal_notice ) {
			return $renewal_notice;
		}

		return false;
	}

	/**
	 * Update status of this subscription.
	 *
	 * @todo  Is this something the subscription module class/object should handle?
	 * @param string $status The new status for this subscription.
	 * @param string $note   The note to store with this status update.
	 */
	public function update_status( $status, $note = null ) {
		if ( Statuses::ACTIVE === $status ) {
			$status = Statuses::SUCCESS;
		}

		$this->set_status( $status );

		switch ( $status ) {
			case Statuses::OPEN:
				$meta_status = $this->get_meta( 'status' );

				if ( Statuses::OPEN !== $meta_status ) {
					$this->refresh_next_payment_date();

					if ( ! $note ) {
						$this->add_note( __( "Subscription status changed to 'Open'", 'pronamic_ideal' ) );
					}
				}

				break;
			case Statuses::SUCCESS:
				$meta_status = $this->get_meta( 'status' );

				if ( Statuses::SUCCESS !== $meta_status ) {
					$this->refresh_next_payment_date();

					if ( ! $note ) {
						$this->add_note( __( "Subscription status changed to 'Active'", 'pronamic_ideal' ) );
					}
				}

				break;
			case Statuses::FAILURE:
				if ( ! $note ) {
					$this->add_note( __( "Subscription status changed to 'Failed'", 'pronamic_ideal' ) );
				}

				break;
			case Statuses::CANCELLED:
				$this->set_next_payment_date( false );

				if ( ! $note ) {
					$this->add_note( __( "Subscription status changed to 'Cancelled'", 'pronamic_ideal' ) );
				}

				break;
			case Statuses::COMPLETED:
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

		// Note: make sure Plugin::update_subscription( $subscription, $can_redirect ) is called after the status has been updated!
	}

	/**
	 * Update meta.
	 *
	 * @todo  Not sure how and when this function is used.
	 * @param array $meta The meta data to update.
	 */
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
