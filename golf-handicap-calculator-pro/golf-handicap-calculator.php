<?php
/*
	Plugin Name: Golf Handicap Calculator Pro
	Plugin URI: http://www.inspired-plugins.com/
	Description: A Plugin that calculates your golf handicap
	Version: 1.0.0
	Author: Inspired Information Services
	Author URI: www.inspired-is.com
*/
//Index file for the plugin will load files, create the datebase table and echo the custom css.
//For more information on how the code works please refer to the code_documentation.txt file
//add all the plugins files
include "licensing.php";
include "admin/admin-page.php";
if (get_option( 'ghc_license_status' ) == "valid"){ //if the plugin is licensed then load the rest of the files to enable the plugin to fully work
	include "shortcodes/shortcode.php";
	add_action( 'wp_enqueue_scripts', 'add_submit_script' );
	include "form_calc.php";
};
add_action( 'wp_enqueue_scripts', 'add_stylesheet' );
add_action( 'admin_init', 'add_admin_stylesheet' );

function add_submit_script(){
	wp_enqueue_script( 'golf-handicap-calculator', plugins_url('submit.js', __FILE__) );
};
function add_stylesheet() {
	wp_enqueue_style( 'golf-handicap-calculator', plugins_url('style.css', __FILE__) );
};
function add_admin_stylesheet() {
	wp_enqueue_style( 'ghc_admin_style', plugins_url('/admin/admin_style.css', __FILE__) );
};
if ( ! is_admin() ) {
$ghc_custom_css = get_option("ghc_option_css");
?>
<style>
<?php
echo $ghc_custom_css; //Echo the custom css added in the admin settings page
?>
</style>
<?php
}
//register the plugins database table
	register_activation_hook( __FILE__, 'ghc_table_install' );
/* funcion for register custom table */
function ghc_table_install() {
	/**Create an instance of the database class**/
	global $ghc_db_version; 
	$ghc_db_version = '1.0'; //current database table version will need changing if db updated once released
	if (get_site_option( 'ghc_db_version' ) != $ghc_db_version) {
		/** @var wpdb $wpdb */
		global $wpdb;    
		/**Set the custom table **/
		$card_details_table = $wpdb->prefix . "ghc_cards";
		/**Execute the sql statement to create or update the custom table**/	
		$sql = "CREATE TABLE " . $card_details_table . ' (
			card_id INT NOT NULL AUTO_INCREMENT,
			user_id INT DEFAULT NULL, 
			competition_id INT DEFAULT NULL,
			course_id INT DEFAULT NULL,
			tee TEXT DEFAULT NULL,
			par TEXT DEFAULT NULL,
			si TEXT DEFAULT NULL,
			score TEXT DEFAULT NULL,
			date_time DATETIME DEFAULT NULL,
			note TEXT DEFAULT NULL,
			PRIMARY KEY (card_id)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		add_option( "ghc_db_version", '1.0' );
		
		//Create the template database table
		/**Create an instance of the database class**/   
		/**Set the custom table **/
		$course_template_table = $wpdb->prefix . "ghc_ct_data";
		/**Execute the sql statement to create or update the custom table**/	
		$sql = "CREATE TABLE " . $course_template_table . " (
			course_id INT NOT NULL AUTO_INCREMENT,
			course_name TEXT DEFAULT NULL,
			comp_par TEXT DEFAULT NULL,
			comp_si TEXT DEFAULT NULL,
			male_par TEXT DEFAULT NULL,
			male_si TEXT DEFAULT NULL,
			female_par TEXT DEFAULT NULL,
			female_si TEXT DEFAULT NULL,
			junior_par TEXT DEFAULT NULL,
			junior_si TEXT DEFAULT NULL,
			PRIMARY KEY (course_id)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		dbDelta($sql);
		
		//Create the competition database table
		/**Create an instance of the database class**/   
		/**Set the custom table **/
		$comp_details_table = $wpdb->prefix . "ghc_comp_data";
		/**Execute the sql statement to create or update the custom table**/	
		$sql = "CREATE TABLE " . $comp_details_table . " (
			competition_id INT NOT NULL AUTO_INCREMENT,
			date_start TEXT DEFAULT NULL,
			date_end TEXT DEFAULT NULL,
			template TEXT DEFAULT NULL,
			reference TEXT DEFAULT NULL,
			course_size int DEFAULT NULL,
			PRIMARY KEY (competition_id)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		dbDelta($sql);
	}
}
