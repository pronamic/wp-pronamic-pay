<?php

class Pronamic_GravityForms_PayFeed {
	/**
	 * Indicator for an link to an WordPress page
	 *
	 * @var string
	 */
	const LINK_TYPE_PAGE = 'page';

	/**
	 * Indicator for an link to an URL
	 *
	 * @var string
	 */
	const LINK_TYPE_URL = 'url';

	//////////////////////////////////////////////////

	/**
	 * The payment (post) ID.
	 *
	 * @var int
	 */
	public $id;

	/**
	 * The payment post object
	 */
	public $post;

	//////////////////////////////////////////////////

	public $condition_enabled;

	//////////////////////////////////////////////////
	// Delay
	//////////////////////////////////////////////////

	/**
	 * Delay notification ID's contains an array of notification ID's wich
	 * should be delayed till the payment is succesfull
	 *
	 * @since Gravity Forms 1.7
	 * @var array
	 */
	public $delay_notification_ids;

	/**
	 * Flag to delay the creation of an post till the the payment is succesfull
	 *
	 * @var boolean
	 */
	public $delay_post_creation;

	/**
	 * Flag to delay the creation of an post till the the payment is succesfull
	 *
	 * @deprecated Gravity Forms 1.7
	 * @var boolean
	 */
	public $delay_admin_notification;

	/**
	 * Flag to delay the creation of an post till the the payment is succesfull
	 *
	 * @deprecated Gravity Forms 1.7
	 * @var boolean
	 */
	public $delay_user_notification;

	//////////////////////////////////////////////////

	/**
	 * Construct and initialize payment object
	 *
	 * @param int $post_id
	 */
	public function __construct( $post_id ) {
		$this->id   = $post_id;
		$this->post = get_post( $post_id );

		// Load
		$this->config_id                = get_post_meta( $post_id, '_pronamic_pay_gf_config_id', true );

		$this->entry_id_prefix          = get_post_meta( $post_id, '_pronamic_pay_gf_entry_id_prefix', true );

		$this->transaction_description  = get_post_meta( $post_id, '_pronamic_pay_gf_transaction_description', true );

		$this->condition_enabled        = get_post_meta( $post_id, '_pronamic_pay_gf_condition_enabled', true );
		$this->condition_field_id       = get_post_meta( $post_id, '_pronamic_pay_gf_condition_field_id', true );
		$this->condition_operator       = get_post_meta( $post_id, '_pronamic_pay_gf_condition_operator', true );
		$this->condition_value          = get_post_meta( $post_id, '_pronamic_pay_gf_condition_value', true );

		$ids = get_post_meta( $post_id, '_pronamic_pay_gf_delay_notification_ids', true );
		$this->delay_notification_ids   = is_array( $ids ) ? $ids : array();

		$this->delay_admin_notification = get_post_meta( $post_id, '_pronamic_pay_gf_delay_admin_notification', true );
		$this->delay_user_notification  = get_post_meta( $post_id, '_pronamic_pay_gf_delay_user_notification', true );

		$this->delay_post_creation      = get_post_meta( $post_id, '_pronamic_pay_gf_delay_post_creation', true );

		$this->delay_campaignmonitor_subscription = get_post_meta( $post_id, '_pronamic_pay_gf_delay_campaignmonitor_subscription', true );
		$this->delay_mailchimp_subscription       = get_post_meta( $post_id, '_pronamic_pay_gf_delay_mailchimp_subscription', true );

		$this->delay_user_registration            = get_post_meta( $post_id, '_pronamic_pay_gf_delay_user_registration', true );

		$fields = get_post_meta( $post_id, '_pronamic_pay_gf_fields', true );
		$this->fields                   = is_array( $fields ) ? $fields : array();

		$links = get_post_meta( $post_id, '_pronamic_pay_gf_links', true );
		$this->links                    = is_array( $links ) ? $links : array();

		$this->user_role_field_id       = get_post_meta( $post_id, '_pronamic_pay_gf_user_role_field_id', true );
	}

	//////////////////////////////////////////////////

	/**
	 * Get the URL of the specified name
	 *
	 * @param string $name
	 */
	public function get_url( $name ) {
		$url = null;

		if ( isset( $this->links[ $name ] ) ) {
			$link = $this->links[ $name ];

			// link is een standard class object, the type variable could not be defined
			if ( isset( $link['type'] ) ) {
				switch ( $link['type'] ) {
					case self::LINK_TYPE_PAGE:
						$url = get_permalink( $link['page_id'] );

						break;
					case self::LINK_TYPE_URL:
						$url = $link['url'];

						break;
				}
			}
		}

		return $url;
	}

	/**
	 * Returns a boolean if this feed has some delayed notifications
	 *
	 * @return boolean
	 */
	public function has_delayed_notifications() {
		return ( ! empty( $this->delay_notification_ids ) );
	}
}
