<?php
//Activates and deactivates the license for the plugin and checks for any updates.
set_site_transient( 'update_plugins', null );
// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
define( 'ghc_STORE_URL', 'http://www.inspired-plugins.com' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file

// the name of your product. This should match the download name in EDD exactly
define( 'ghc_ITEM_NAME', 'Golf Handicap Calculator Pro' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file

if( !class_exists( 'GHC_SL_Plugin_Updater' ) ) {
	// load our custom updater
	include( dirname( __FILE__ ) . '/GHC_SL_Plugin_Updater.php' );
}

function ghc_sl_sample_plugin_updater() {

	// retrieve our license key from the DB
	$license_key = trim( get_option( 'ghc_license_key' ) );

	// setup the updater
	$ghc_updater = new GHC_SL_Plugin_Updater( ghc_STORE_URL, __FILE__, array( 
			'version' 	=> '1.0.0', 				// current version number
			'license' 	=> $license_key, 		// license key (used get_option above to retrieve from DB)
			'item_name' => ghc_ITEM_NAME, 	// name of this plugin
			'author' 	=> 'Luke Foster'  // author of this plugin
		)
	);
}
add_action( 'admin_init', 'ghc_sl_sample_plugin_updater',0 );

/************************************
* this illustrates how to activate 
* a license key
*************************************/

function ghc_activate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['ghc_license_activate'] ) ) {

		// run a quick security check 
	 	if( ! check_admin_referer( 'ghc_nonce', 'ghc_nonce' ) ) 	
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = $_POST['ghc_license_key'] ;
			

		// data to send in our API request
		$api_params = array( 
			'edd_action'=> 'activate_license', 
			'license' 	=> $license, 
			'item_name' => urlencode( ghc_ITEM_NAME ), // the name of our product in EDD
			'url'       => home_url()
		);
		
		// Call the custom API.
		$response = wp_remote_get( add_query_arg( $api_params, ghc_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		
		// $license_data->license will be either "valid" or "invalid"

		update_option( 'ghc_license_status', $license_data->license );

	}
}
add_action('admin_init', 'ghc_activate_license');


/***********************************************
* Illustrates how to deactivate a license key.
* This will descrease the site count
***********************************************/

function ghc_deactivate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['ghc_license_deactivate'] ) ) {

		// run a quick security check 
	 	if( ! check_admin_referer( 'ghc_nonce', 'ghc_nonce' ) ) 	
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( 'ghc_license_key' ) );
			

		// data to send in our API request
		$api_params = array( 
			'edd_action'=> 'deactivate_license', 
			'license' 	=> $license, 
			'item_name' => urlencode( ghc_ITEM_NAME ), // the name of our product in EDD
			'url'       => home_url()
		);
		
		// Call the custom API.
		$response = wp_remote_get( add_query_arg( $api_params, ghc_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		
		// $license_data->license will be either "deactivated" or "failed"
		if( $license_data->license == 'deactivated' )
			delete_option( 'ghc_license_status' );

	}
}
add_action('admin_init', 'ghc_deactivate_license');
