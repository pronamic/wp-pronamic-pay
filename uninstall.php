<?php
/**
 * Uninstall
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2024 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

if ( '1' !== get_option( 'pronamic_pay_uninstall_clear_data', false ) ) {
	return;
}

// Delete tables.
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}pronamic_ideal_configurations" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}pronamic_ideal_payments" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}rg_ideal_feeds" );

// Delete posts.
$wpdb->query( "DELETE FROM {$wpdb->prefix}posts WHERE post_type = 'pronamic_gateway';" );
$wpdb->query( "DELETE FROM {$wpdb->prefix}posts WHERE post_type = 'pronamic_payment';" );
$wpdb->query( "DELETE FROM {$wpdb->prefix}posts WHERE post_type = 'pronamic_pay_gf';" );

$wpdb->query( "DELETE FROM {$wpdb->prefix}postmeta WHERE post_id NOT IN ( SELECT ID FROM {$wpdb->prefix}posts );" );

// Delete general options.
delete_option( 'pronamic_pay_version' );
delete_option( 'pronamic_pay_db_version' );
delete_option( 'pronamic_pay_license_key' );
delete_option( 'pronamic_pay_license_status' );
delete_option( 'pronamic_pay_config_id' );

delete_option( 'pronamic_pay_installation_date' );
delete_option( 'pronamic_pay_uninstall_clear_data' );
delete_option( 'pronamic_pay_debug_mode' );
delete_option( 'pronamic_pay_subscriptions_processing_disabled' );
delete_option( 'pronamic_pay_active_payment_methods' );
delete_option( 'pronamic_pay_about_page_version' );
delete_option( 'pronamic_pay_forms_version' );
delete_option( 'pronamic_pay_home_url' );

delete_option( 'pronamic_pay_completed_page_id' );
delete_option( 'pronamic_pay_cancel_page_id' );
delete_option( 'pronamic_pay_expired_page_id' );
delete_option( 'pronamic_pay_error_page_id' );
delete_option( 'pronamic_pay_unknown_page_id' );
delete_option( 'pronamic_pay_subscription_canceled_page_id' );

$wpdb->query( "DELETE FROM {$wpdb->prefix}options WHERE option_name LIKE '%pronamic_pay_payment_method_%_status%';" );

/**
 * Delete extension options.
 */

// Event Espresso.
delete_option( 'pronamic_pay_ideal_event_espreso_config_id' );

// Gravity Forms.
// There are options for Gravity Forms.
delete_option( '' );

// Jigoshop.
// Set default Jigoshop variables, load them form the WordPress options.
delete_option( 'pronamic_pay_ideal_jigoshop_enabled' );
delete_option( 'pronamic_pay_ideal_jigoshop_title' );
delete_option( 'pronamic_pay_ideal_jigoshop_description' );
delete_option( 'pronamic_pay_ideal_jigoshop_config_id' );

// Membership.
delete_option( 'pronamic_pay_ideal_membership_config_id' );

// s2Member®.
delete_option( 'pronamic_pay_ideal_s2member_config_id' );

// Shopp.
// Shopp options are stored in the Shopp meta table, we don't touch this.
delete_option( '' );

// WooCommerce.
delete_option( 'woocommerce_pronamic_pay_ideal_settings' );

// WP e-Commerce.
delete_option( 'pronamic_pay_ideal_wpsc_config_id' );

/**
 * Delete legacy options.
 */

// General.
delete_option( 'pronamic_ideal_version' );
delete_option( 'pronamic_ideal_key' );
delete_option( 'gf_ideal_version' );

// Event Espresso.
delete_option( 'pronamic_ideal_event_espresso_configuration_id' );

// Jigoshop.
delete_option( 'jigoshop_pronamic_ideal_enabled' );
delete_option( 'jigoshop_pronamic_ideal_title' );
delete_option( 'jigoshop_pronamic_ideal_description' );
delete_option( 'jigoshop_pronamic_ideal_configuration_id' );

// Membership.
delete_option( 'pronamic_ideal_membership_enabled' );
delete_option( 'pronamic_ideal_membership_chosen_configuration' );

// s2Member®.
delete_option( 'pronamic_ideal_s2member_enabled' );
delete_option( 'pronamic_ideal_s2member_chosen_configuration' );

// WooCommerce.
delete_option( 'woocommerce_pronamic_ideal_settings' );

// WP e-Commerce.
delete_option( 'pronamic_ideal_wpsc_configuration_id' );
