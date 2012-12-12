<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit ();
	
// Delete tables
global $wpdb;

$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}pronamic_ideal_configurations" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}pronamic_ideal_payments" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}rg_ideal_feeds" );

// Delete options
delete_option( 'pronamic_ideal_version' );
delete_option( 'pronamic_ideal_key' );
delete_option( 'gf_ideal_version' );

// Delete transient
delete_transient( 'pronamic_ideal_license_info' );
