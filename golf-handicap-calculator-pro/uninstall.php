<?php
//Runs when deleting the plugin, will clear all database tables, options and files.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

// retrieve the license from the database
$license = trim( get_option( 'ghc_license_key' ) );
// data to send in our API request
	$api_params = array( 
		'edd_action'=> 'deactivate_license', 
		'license' 	=> $license, 
		'item_name' => urlencode( 'Golf Handicap Calculator Pro' ), // the name of our product in EDD
		'url'       => home_url()
	);
// Call the custom API.
$response = wp_remote_get( add_query_arg( $api_params, 'http://www.inspired-plugins.com' ), array( 'timeout' => 15, 'sslverify' => false ) );

// make sure the response came back okay
if ( is_wp_error( $response ) )
	return false;

// decode the license data
$license_data = json_decode( wp_remote_retrieve_body( $response ) );

// $license_data->license will be either "deactivated" or "failed"
if( $license_data->license == 'deactivated' )
	delete_option( 'ghc_license_status' );
global $wpdb;
$table_name = $wpdb->prefix . "ghc_cards";
$table_2_name = $wpdb->prefix . "ghc_ct_data";
$table_3_name = $wpdb->prefix . "ghc_comp_data";
$sql = "DROP TABLE IF EXISTS $table_name;";
$sql_2 = "DROP TABLE IF EXISTS $table_2_name;";
$sql_3 = "DROP TABLE IF EXISTS $table_3_name;";
$wpdb->query($sql);
$wpdb->query($sql_2);
$wpdb->query($sql_3);
$wpdb->query($sql_4);
delete_option("ghc_db_version");
delete_option("ghc_option_css");
delete_option("ghc_license_key");
