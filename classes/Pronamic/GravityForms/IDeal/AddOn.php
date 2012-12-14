<?php 

/**
 * Title: Gravity Forms iDEAL Add-On
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_GravityForms_IDeal_AddOn {
	/**
	 * Slug
	 * 
	 * @var string
	 */
	const SLUG = 'gravityformsideal';

	/**
	 * Gravity Forms minimum required version
	 * 
	 * @var string
	 */
	const GRAVITY_FORMS_MINIMUM_VERSION = '1.0';

	//////////////////////////////////////////////////

	/**
	 * Option version
	 * 
	 * @var string
	 */
	const OPTION_VERSION = 'gf_ideal_version';

	/**
	 * The current version of this plugin
	 * 
	 * @var string
	 */
	const VERSION = '1.0';

	//////////////////////////////////////////////////

	/**
	 * Bootstrap
	 */
	public static function bootstrap() {
		// Initialize hook, Gravity Forms uses the default priority (10)
		add_action( 'init',           array( __CLASS__, 'initialize' ), 20 );

		add_action( 'plugins_loaded', array( __CLASS__, 'setup' ) );
	}

	//////////////////////////////////////////////////

	/**
	 * Initialize
	 */
	public static function initialize() {
		if ( self::isGravityFormsSupported() ) {
			// Admin
			if ( is_admin() ) {
				Pronamic_GravityForms_IDeal_Admin::bootstrap();
			} else {
				// @see http://www.gravityhelp.com/documentation/page/Gform_confirmation
				add_filter( 'gform_confirmation',     array( __CLASS__, 'handleIDeal' ), 10, 4 );

	            // Set entry meta after submission
	            add_action( 'gform_after_submission', array( __CLASS__, 'set_entry_meta' ), 5, 2 );

	            // Delay
	            add_filter( 'gform_disable_admin_notification', array( __CLASS__, 'maybe_delay_admin_notification' ), 10, 3 );
	            add_filter( 'gform_disable_user_notification',  array( __CLASS__, 'maybe_delay_user_notification' ), 10, 3 );
				add_filter( 'gform_disable_post_creation',      array( __CLASS__, 'maybe_delay_post_creation' ), 10, 3 );
			}

			add_action( 'pronamic_ideal_status_update', array( __CLASS__, 'update_status' ), 10, 2 );

			add_filter( 'pronamic_ideal_source_column_gravityformsideal', array( __CLASS__, 'source_column' ), 10, 2 );

			add_filter( 'gform_replace_merge_tags', array( __CLASS__, 'replace_merge_tags' ), 10, 7 );

			// iDEAL fields
			Pronamic_GravityForms_IDeal_Fields::bootstrap();
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Source column
	 */
	public static function source_column( $text, $payment ) {
		$text  = '';

		$text .= __( 'Gravity Forms', 'pronamic_ideal' ) . '<br />';

		$text .= sprintf(
			'<a href="%s">%s</a>', 
			add_query_arg( array( 'page' => 'gf_pronamic_ideal', 'lid' => $payment->getSourceId() ), admin_url( 'admin.php' ) ),
			sprintf( __( 'Entry #%s', 'pronamic_ideal' ), $payment->getSourceId() )
		);

		return $text;
	}

	//////////////////////////////////////////////////

	/**
	 * Setup, creates or updates database tables. Will only run when version changes
	 */
	public static function setup() {
		if ( self::isGravityFormsSupported() && ( get_option( self::OPTION_VERSION ) != self::VERSION ) ) {
			// Update tables
			Pronamic_GravityForms_IDeal_FeedsRepository::update_table();

			// Add some new capabilities
			$capabilities = array(
				'read'               => true,
				'gravityforms_ideal' => true
			);
			
			$roles = array(
				'pronamic_ideal_administrator' => array(
					'display_name' => __( 'iDEAL Administrator', 'pronamic_ideal' ),
					'capabilities' => $capabilities
				) , 
				'administrator' => array(
					'display_name' => __( 'Administrator', 'pronamic_ideal' ),
					'capabilities' => $capabilities
				)
			);
			
			Pronamic_WordPress_IDeal_Plugin::set_roles( $roles );
				
			// Update version
			update_option( self::OPTION_VERSION, self::VERSION );
		}
	}

	//////////////////////////////////////////////////

	/**
	 * Maybe update user role of the specified lead and feed
	 * 
	 * @param array $lead
	 * @param Feed $feed
	 */
	private static function maybe_update_user_role( $lead, $feed ) {
		$user = false;

		// Gravity Forms User Registration Add-On 
		if ( class_exists( 'GFUserData' ) ) {
			$user = GFUserData::get_user_by_entry_id( $lead['id'] );
		} 
		
		if ( $user == false ) {
			$created_by = $lead[Pronamic_GravityForms_GravityForms::LEAD_PROPERY_CREATED_BY];
				
			$user = new WP_User( $created_by );
		}

		if ( $user && ! empty( $feed->userRoleFieldId ) && isset( $lead[$feed->userRoleFieldId] ) ) {
			$value = $lead[$feed->userRoleFieldId];
			$value = GFCommon::get_selection_value( $value );

			$user->set_role( $value );
		}
	}

	//////////////////////////////////////////////////
	
	/**
	 * Update lead status of the specified payment
	 * 
	 * @param string $payment
	 */
	public static function update_status( Pronamic_WordPress_IDeal_Payment $payment, $can_redirect = false ) {
		if ( $payment->getSource() == self::SLUG ) {
			$lead_id = $payment->getSourceId();

			$lead = RGFormsModel::get_lead( $lead_id );

			if ( $lead ) {
				$form_id = $lead['form_id'];
				
				$feed = Pronamic_GravityForms_IDeal_FeedsRepository::getFeedByFormId( $form_id );

				if ( $feed ) {
					$url = null;

					switch ( $payment->status ) {
						case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_CANCELLED:
							$lead[Pronamic_GravityForms_GravityForms::LEAD_PROPERTY_PAYMENT_STATUS] = Pronamic_GravityForms_GravityForms::PAYMENT_STATUS_CANCELLED;

							$url = $feed->getUrl( Pronamic_GravityForms_IDeal_Feed::LINK_CANCEL );

							break;
						case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_EXPIRED:
							$lead[Pronamic_GravityForms_GravityForms::LEAD_PROPERTY_PAYMENT_STATUS] = Pronamic_GravityForms_GravityForms::PAYMENT_STATUS_EXPIRED;

							$url = $feed->getUrl( Pronamic_GravityForms_IDeal_Feed::LINK_EXPIRED );

							break;
						case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_FAILURE:
							$lead[Pronamic_GravityForms_GravityForms::LEAD_PROPERTY_PAYMENT_STATUS] = Pronamic_GravityForms_GravityForms::PAYMENT_STATUS_FAILED;

							$url = $feed->getUrl( Pronamic_GravityForms_IDeal_Feed::LINK_ERROR );

							break;
						case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_SUCCESS:
							$lead[Pronamic_GravityForms_GravityForms::LEAD_PROPERTY_PAYMENT_STATUS] = Pronamic_GravityForms_GravityForms::PAYMENT_STATUS_APPROVED;

							self::fulfill_order( $lead );

							$url = $feed->getUrl( Pronamic_GravityForms_IDeal_Feed::LINK_SUCCESS );

							break;
						case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_OPEN:
						default:
							$url = $feed->getUrl( Pronamic_GravityForms_IDeal_Feed::LINK_OPEN );

							break;
					}

					RGFormsModel::update_lead( $lead );

					if ( $url && $can_redirect ) {
						wp_redirect( $url, 303 );

						exit;
					}
				}
			}
		}
	}

	/**
	 * Fulfill order
	 * 
	 * @param array $entry
	 * @param string $transaction_id
	 * @param string $amount
	 */
    public static function fulfill_order( $entry ) {
        $formMeta = RGFormsModel::get_form_meta( $entry['form_id'] );

        $feed = Pronamic_GravityForms_IDeal_FeedsRepository::getFeedByFormId( $entry['form_id'] );

        if ( $feed !== null ) {
        	self::maybe_update_user_role( $entry, $feed );

			if ( $feed->delayAdminNotification ) {
				GFCommon::send_admin_notification( $formMeta, $entry );
			}

			if ( $feed->delayUserNotification ) {
				GFCommon::send_user_notification( $formMeta, $entry );
			}

			if ( $feed->delayPostCreation ) {
				RGFormsModel::create_post( $formMeta, $entry );
			}
        }

        // The Gravity Forms PayPal Add-On executes the 'gform_paypal_fulfillment' action
        do_action( 'gform_ideal_fulfillment', $entry, $feed );
    }

	//////////////////////////////////////////////////

	/**
	 * Checks if Gravity Forms is supporter
	 * 
	 * @return true if Gravity Forms is supported, false otherwise
	 */
	public static function isGravityFormsSupported() {
		if ( class_exists( 'GFCommon' ) ) {
			return version_compare( GFCommon::$version, self::GRAVITY_FORMS_MINIMUM_VERSION, '>=' );
        } else {
			return false;
        }
	}
	
	//////////////////////////////////////////////////

	/**
	 * Check if the iDEAL condition is true
	 * 
	 * @param mixed $form
	 * @param mixed $feed
	 */
	public static function is_condition_true( $form, $feed ) {
		$result = true;

        if ( $feed->conditionEnabled ) {
			$field = RGFormsModel::get_field( $form, $feed->conditionFieldId );

			if ( empty( $field ) ) {
				// unknown field
				$result = true;
			} else {
				$isHidden = RGFormsModel::is_field_hidden( $form, $field, array() );

				if ( $isHidden ) {
					// if conditional is enabled, but the field is hidden, ignore conditional
					$result = false;
				} else {
					$value = RGFormsModel::get_field_value( $field, array() );

					$isMatch = RGFormsModel::is_value_match( $value, $feed->conditionValue );
					
					switch ( $feed->conditionOperator ) {
						case Pronamic_GravityForms_GravityForms::OPERATOR_IS:
							$result = $isMatch;
							break;
						case Pronamic_GravityForms_GravityForms::OPERATOR_IS_NOT:
							$result = !$isMatch;
							break;
						default: // unknown operator
							$result = true;
							break;
					}
				}
			}
        } else {
        	// condition is disabled, result is true
        	$result = true;
        }

        return $result;
	}

	//////////////////////////////////////////////////
	// Maybe delay functions
	//////////////////////////////////////////////////

	/**
	 * Maybe delay admin notification
	 * 
	 * @param boolean $isDisabled
	 * @param array $form
	 * @param array $lead
	 * @return boolean true if admin notification is disabled / delayed, false otherwise
	 */
	public static function maybe_delay_admin_notification( $is_disabled, $form, $lead ) {
		$feed = Pronamic_GravityForms_IDeal_FeedsRepository::getFeedByFormId( $form['id'] );

		if ( $feed !== null ) {
			if ( self::is_condition_true( $form, $feed ) ) {
				$is_disabled = $feed->delayAdminNotification;
			}
		}
		
		return $is_disabled;
	}

	/**
	 * Maybe delay user notification
	 * 
	 * @param boolean $isDisabled
	 * @param array $form
	 * @param array $lead
	 * @return boolean true if user notification is disabled / delayed, false otherwise
	 */
	public static function maybe_delay_user_notification( $is_disabled, $form, $lead ) {
		$feed = Pronamic_GravityForms_IDeal_FeedsRepository::getFeedByFormId( $form['id'] );

		if ( $feed !== null ) {
			if ( self::is_condition_true( $form, $feed ) ) {
				$is_disabled = $feed->delayUserNotification;
			}
		}
		
		return $is_disabled;
	}

	/**
	 * Maybe delay post creation
	 * 
	 * @param boolean $isDisabled
	 * @param array $form
	 * @param array $lead
	 * @return boolean true if post creation is disabled / delayed, false otherwise
	 */
	public static function maybe_delay_post_creation( $is_disabled, $form, $lead ) {
		$feed = Pronamic_GravityForms_IDeal_FeedsRepository::getFeedByFormId( $form['id'] );

		if ( $feed !== null ) {
			if ( self::is_condition_true( $form, $feed ) ) {
				$is_disabled = $feed->delayPostCreation;
			}
		}
		
		return $is_disabled;
	}
	
	//////////////////////////////////////////////////

	/**
	 * Set entry meta
	 * 
	 * @param array $entry
	 * @param array $form
	 */
    public static function set_entry_meta( $entry, $form ) {
		// ignore requests that are not the current form's submissions
		if ( rgpost( 'gform_submit' ) != $form['id'] ) {
			return;
		}

		$feed = Pronamic_GravityForms_IDeal_FeedsRepository::getFeedByFormId( $form['id'] );
		if ( $feed !== null ) {
			// Update form meta with current feed id
			gform_update_meta( $entry['id'], 'ideal_feed_id', $feed->id );

			// Update form meta with current payment gateway
			gform_update_meta( $entry['id'], 'payment_gateway', 'ideal' );			
		}
    }
	
	//////////////////////////////////////////////////

	/**
	 * Handle iDEAL
	 * 
	 * @see http://www.gravityhelp.com/documentation/page/Gform_confirmation
	 */
	public static function handleIDeal( $confirmation, $form, $lead, $ajax ) {
		$feed = Pronamic_GravityForms_IDeal_FeedsRepository::getFeedByFormId( $form['id'] );

		if ( $feed !== null ) {
			if ( self::is_condition_true( $form, $feed ) ) {
				$configuration = $feed->getIDealConfiguration();

				if ( $configuration !== null ) {
					$variant = $configuration->getVariant();
	
					if ( $variant !== null ) {
						switch ( $variant->getMethod() ) {
							case Pronamic_IDeal_IDeal::METHOD_ADVANCED:
								$confirmation = self::handleIDealAdvanced( $confirmation, $form, $feed, $lead );
								break;
							default:
								$confirmation = self::handleIDealForm( $confirmation, $form, $feed, $lead );
								break;
						}
					}
				}
			}
		}
		
		if ( (headers_sent() || $ajax ) && is_array( $confirmation ) && isset( $confirmation['redirect'] ) ) {
			$url = $confirmation['redirect'];

			// Using esc_js() and esc_url() on the URL is causing problems, the & in the URL is modified to &amp; or &#038;
			$confirmation = sprintf( '<script>function gformRedirect(){document.location.href = %s;}', json_encode( $url ) );
			if ( !$ajax ) {
				$confirmation .= 'gformRedirect();';
			}
			$confirmation .= '</script>';
		}
		
		return $confirmation;
	}

	//////////////////////////////////////////////////

	/**
	 * Handle iDEAL advanced
	 * 
	 * @see http://www.gravityhelp.com/documentation/page/Gform_confirmation
	 */
	public static function handleIDealAdvanced( $confirmation, $form, $feed, $lead ) {
		$configuration = $feed->getIDealConfiguration();

		$data_proxy = new Pronamic_GravityForms_IDeal_IDealDataProxy( $form, $lead, $feed );
	
		$url = Pronamic_WordPress_IDeal_IDeal::process_ideal_advanced( $configuration, $data_proxy );

		if ( empty( $url ) ) {
			$error = Pronamic_WordPress_IDeal_IDeal::getError();
			if ( !empty( $error ) ) {
				$confirmation = sprintf(
					__( '%s (error code: %s)', 'pronamic_ideal' ),
					$error->getConsumerMessage(),
					$error->getCode()
				);
			}
		} else {
			// Updating lead's payment_status to Processing
			$lead[Pronamic_GravityForms_GravityForms::LEAD_PROPERTY_PAYMENT_STATUS]   = Pronamic_GravityForms_GravityForms::PAYMENT_STATUS_PROCESSING;
			$lead[Pronamic_GravityForms_GravityForms::LEAD_PROPERTY_PAYMENT_AMOUNT]   = $data_proxy->getAmount();
			$lead[Pronamic_GravityForms_GravityForms::LEAD_PROPERTY_PAYMENT_DATE]     = $payment->getDate()->format('y-m-d H:i:s');
			$lead[Pronamic_GravityForms_GravityForms::LEAD_PROPERTY_TRANSACTION_TYPE] = Pronamic_GravityForms_GravityForms::TRANSACTION_TYPE_PAYMENT;
			$lead[Pronamic_GravityForms_GravityForms::LEAD_PROPERTY_TRANSACTION_ID]   = $transaction->getId();
		
			RGFormsModel::update_lead( $lead );
	
			// Redirect user to the issuer
			$confirmation = array( 'redirect' => $url );
		}

		return $confirmation;
	}

	/**
	 * Handle iDEAL form
	 * 
	 * @see http://www.gravityhelp.com/documentation/page/Gform_confirmation
	 */
	public static function handleIDealForm( $confirmation, $form, $feed, $lead ) {
		$configuration = $feed->getIDealConfiguration();

		$dataProxy = new Pronamic_GravityForms_IDeal_IDealDataProxy( $form, $lead, $feed );

		// Updating lead's payment_status to Processing
        $lead[Pronamic_GravityForms_GravityForms::LEAD_PROPERTY_PAYMENT_STATUS]   = Pronamic_GravityForms_GravityForms::PAYMENT_STATUS_PROCESSING;
        $lead[Pronamic_GravityForms_GravityForms::LEAD_PROPERTY_PAYMENT_AMOUNT]   = $dataProxy->getAmount();
        $lead[Pronamic_GravityForms_GravityForms::LEAD_PROPERTY_PAYMENT_DATE]     = gmdate( 'y-m-d H:i:s' );
        $lead[Pronamic_GravityForms_GravityForms::LEAD_PROPERTY_TRANSACTION_TYPE] = Pronamic_GravityForms_GravityForms::TRANSACTION_TYPE_PAYMENT;
        $lead[Pronamic_GravityForms_GravityForms::LEAD_PROPERTY_TRANSACTION_ID]   = $lead['id'];

        RGFormsModel::update_lead( $lead );

        // HTML
        $html  = '';
        $html .= '<div id="gforms_confirmation_message">';
        $html .= 	GFCommon::replace_variables( $form['confirmation']['message'], $form, $lead, false, true, true );
        $html .= 	Pronamic_WordPress_IDeal_IDeal::getHtmlForm( $dataProxy, $configuration );
		$html .= '</div>';

        // Extend the confirmation with the iDEAL form
        $confirmation = $html;

        // Return
        return $confirmation;
	}

	/**
	 * Replace merge tags
	 * 
	 * @param string $text
	 * @param array $form
	 * @param array $entry
	 * @param boolean $url_encode
	 * @param boolean $esc_html
	 * @param boolean $nl2br
	 * @param string $format
	 */
	function replace_merge_tags( $text, $form, $entry, $url_encode, $esc_html, $nl2br, $format ) {
		$search = array(
			'{payment_status}' , 
			'{payment_date}' , 
			'{transaction_id}' , 
			'{payment_amount}'
		);
    	
		$replace = array(
			rgar( $entry, 'payment_status' ) , 
			rgar( $entry, 'payment_date' ) , 
			rgar( $entry, 'transaction_id' ) ,
			GFCommon::to_money( rgar( $entry, 'payment_amount' ) , rgar( $entry, 'currency' ) )
		);

		if ( $url_encode ) {
			foreach ( $replace as &$value ) {
    			$value = urlencode( $value );
    		}
    	}

    	$text = str_replace( $search, $replace, $text);
    
		return $text;
	}
}
