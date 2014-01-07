<?php

/**
 * Title: Easy Digital Downloads iDEAL Add-On
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Stefan Boonstra
 * @version 1.0
 */
class Pronamic_EasyDigitalDownloads_IDeal_AddOn {
    /**
     * Slug
     *
     * @var string
     */
    const SLUG = 'easydigitaldownloads';

    //////////////////////////////////////////////////

    /**
     * Bootstrap
     */
    public static function bootstrap() {
        // The "plugins_loaded" is one of the earliest hooks after EDD is set up
        add_action( 'plugins_loaded', array( __CLASS__, 'plugins_loaded' ) );
    }

    //////////////////////////////////////////////////

    /**
     * Test to see if the Easy Digital Downloads plugin is active, then add all actions.
     */
    public static function plugins_loaded() {
        if ( Pronamic_EasyDigitalDownloads_EasyDigitalDownloads::is_active() ) {
            // Remove the Easy Digital Downloads credit card form
            add_action( 'edd_' . self::SLUG . '_cc_form', '__return_false' );

            // Other actions
            add_action( 'edd_purchase_form_before_submit'             , array( __CLASS__, 'payment_fields' ) );
            add_action( 'edd_gateway_' . self::SLUG                   , array( __CLASS__, 'process_purchase' ) );
            add_action( "pronamic_payment_status_update_" . self::SLUG, array( __CLASS__, 'status_update' ), 10, 2 );
            add_filter( "pronamic_payment_source_text_" . self::SLUG  , array( __CLASS__, 'source_text' ), 10, 2 );

            // Filters
            add_filter( 'edd_settings_gateways', array( __CLASS__, 'settings_gateways' ) );
            add_filter( 'edd_payment_gateways' , array( __CLASS__, 'payment_gateways' ) );
        }
    }

    //////////////////////////////////////////////////

    /**
     * Add the gateway to Easy Digital Downloads
     *
     * @param mixed $gateways
     *
     * @return mixed $gateways
     */
    public static function payment_gateways( $gateways ) {
        $gateways[ self::SLUG ] = array(
            'admin_label'    => __( 'iDEAL', 'pronamic_ideal' ),
            'checkout_label' => __( 'iDEAL', 'pronamic_ideal' ),
            'supports'       => array( 'buy_now' ),
        );

        return $gateways;
    }

    //////////////////////////////////////////////////

    /**
     * Add the iDEAL configuration settings to the Easy Digital Downloads payment gateways settings page.
     *
     * @param mixed $settings_gateways
     *
     * @return mixed $settings_gateways
     */
    public static function settings_gateways( $settings_gateways ) {
        // Get configurations
        $ideal_configurations_query = new WP_Query( array(
            'post_type'   => 'pronamic_gateway',
            'post_status' => 'publish',
        ) );

        // Build array with configurations
        if ( $ideal_configurations_query->have_posts() ) {
            $ideal_configurations = array( -1 =>  __( 'Select configuration', 'pronamic_ideal' ) );

            while ( $ideal_configurations_query->have_posts() ) {
                $ideal_configuration = $ideal_configurations_query->next_post();

                $ideal_configurations[ $ideal_configuration->ID ] = $ideal_configuration->post_title;
            }
        } else {
            $ideal_configurations = array( -1 => __( 'No iDEAL configuration has been created yet', 'pronamic_ideal' ) );
        }

        // Add header
        $settings_gateways[ self::SLUG ] = array(
            'id'   => self::SLUG,
            'name' => '<strong>' . __( 'iDEAL Settings', 'pronamic_ideal' ) . '</strong>',
            'desc' => __( 'Configure the iDEAL settings', 'pronamic_ideal' ),
            'type' => 'header',
        );

        // Add configurations
        $settings_gateways[ self::SLUG . '_config_id' ] = array(
            'id'      => self::SLUG . '_config_id',
            'name'    => __( 'iDEAL configuration', 'pronamic_ideal' ),
            'desc'    => __( 'Select configuration', 'pronamic_ideal' ),
            'type'    => 'select',
            'options' => $ideal_configurations,
        );

        return $settings_gateways;
    }

    //////////////////////////////////////////////////

    public static function payment_fields() {
        $gateway = Pronamic_WordPress_IDeal_IDeal::get_gateway( edd_get_option( self::SLUG . '_config_id' ) );

        if ( $gateway ) {
            echo $gateway->get_input_html();
        }
    }

    //////////////////////////////////////////////////

    /**
     * The $purchase_data array consists of the following data:
     *
     * $purchase_data = array(
     *   'downloads'     => array of download IDs,
     *   'tax'           => taxed amount on shopping cart
     *   'subtotal'	     => total price before tax
     *   'price'         => total price of cart contents after taxes,
     *   'purchase_key'  => Random key
     *   'user_email'    => $user_email,
     *   'date'          => date( 'Y-m-d H:i:s' ),
     *   'user_id'       => $user_id,
     *   'post_data'     => $_POST,
     *   'user_info'     => array of user's information and used discount code
     *   'cart_details'  => array of cart details,
     * );
     */
    public static function process_purchase( $purchase_data ) {
        $config_id = edd_get_option( self::SLUG . '_config_id' );

        // Collect payment data
        $payment_data = array(
            'price'         => $purchase_data['price'],
            'date'          => $purchase_data['date'],
            'user_email'    => $purchase_data['user_email'],
            'purchase_key'  => $purchase_data['purchase_key'],
            'currency'      => edd_get_currency(),
            'downloads'     => $purchase_data['downloads'],
            'user_info'     => $purchase_data['user_info'],
            'cart_details'  => $purchase_data['cart_details'],
            'gateway'       => self::SLUG,
            'status'        => 'pending',
        );

        // Record the pending payment
        $payment_id = edd_insert_payment( $payment_data );

        // Check payment
        if ( ! $payment_id ) {
            // Log error
            edd_record_gateway_error( __( 'Payment Error', 'edd' ), sprintf( __( 'Payment creation failed before sending buyer to the iDEAL provider. Payment data: %s', 'pronamic_ideal' ), json_encode( $payment_data ) ), $payment_id );

            edd_send_back_to_checkout( '?payment-mode=' . $purchase_data['post_data']['edd-gateway'] );
        } else {
            $data = new Pronamic_EasyDigitalDownloads_PaymentData( $payment_id, $payment_data );

            $gateway = Pronamic_WordPress_IDeal_IDeal::get_gateway( $config_id );

            if ( $gateway ) {
                // Start
                $payment = Pronamic_WordPress_IDeal_IDeal::start( $config_id, $gateway, $data );

                // Redirect
                $gateway->redirect( $payment );

                exit;
            }
        }
    }

    //////////////////////////////////////////////////

    /**
     * Update lead status of the specified payment
     *
     * @param Pronamic_Pay_Payment $payment
     * @param boolean              $can_redirect
     */
    public static function status_update( Pronamic_Pay_Payment $payment, $can_redirect = false ) {
        $source_id = $payment->get_source_id();

        $data = new Pronamic_EasyDigitalDownloads_PaymentData( $source_id, array() );

        // Only update if order is not completed
        $should_update = edd_get_payment_status( $source_id ) != Pronamic_EasyDigitalDownloads_EasyDigitalDownloads::ORDER_STATUS_PUBLISH;

        // Defaults
        $status = null;
        $note   = null;
        $url    = $data->get_normal_return_url();

        $status = $payment->get_status();

        switch ( $status ) {
            case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_CANCELLED:
                $url = $data->get_cancel_url();

                break;
            case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_EXPIRED:
                if ( $should_update ) {
                    edd_update_payment_status( $source_id, Pronamic_EasyDigitalDownloads_EasyDigitalDownloads::ORDER_STATUS_ABANDONED );
                }

                $url = $data->get_error_url();

                break;
            case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_FAILURE:
                if ( $should_update ) {
                    edd_update_payment_status( $source_id, Pronamic_EasyDigitalDownloads_EasyDigitalDownloads::ORDER_STATUS_FAILED );
                }

                $url = $data->get_error_url();

                break;
            case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_SUCCESS:
                if ( $should_update ) {
                    edd_insert_payment_note( $source_id, __( 'iDEAL payment completed.', 'pronamic_ideal' ) );
                }

                edd_update_payment_status( $source_id, Pronamic_EasyDigitalDownloads_EasyDigitalDownloads::ORDER_STATUS_PUBLISH );

                edd_empty_cart();

                $url = $data->get_success_url();

                break;
            case Pronamic_Gateways_IDealAdvanced_Transaction::STATUS_OPEN:
                if ( $should_update ) {
                    edd_insert_payment_note( $source_id, __( 'iDEAL payment open.', 'pronamic_ideal' ) );
                }

                break;
            default:
                if ( $should_update ) {
                    edd_insert_payment_note( $source_id, __( 'iDEAL payment unknown.', 'pronamic_ideal' ) );
                }

                break;
        }

        if ( $can_redirect ) {
            wp_redirect( $url, 303 );

            exit;
        }
    }

    //////////////////////////////////////////////////

    /**
     * Source column
     *
     * @param string                  $text
     * @param Pronamic_WP_Pay_Payment $payment
     *
     * @return string $text
     */
    public static function source_text( $text, Pronamic_WP_Pay_Payment $payment ) {
        $text  = '';

        $text .= __( 'Easy Digital Downloads', 'pronamic_ideal' ) . '<br />';

        $text .= sprintf(
            '<a href="%s">%s</a>',
            get_edit_post_link( $payment->source_id ),
            sprintf( __( 'Order #%s', 'pronamic_ideal' ), $payment->source_id )
        );

        return $text;
    }
}
