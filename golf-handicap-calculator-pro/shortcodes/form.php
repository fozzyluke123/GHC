<?php
add_shortcode("ghc_form", "ghc_shortcode_form");//Main input form

function ghc_shortcode_form(){
	if (is_user_logged_in() == true){
		global $wpdb; //define database connection and return the corresponding users row
		$table_name = $wpdb->prefix . "ghc_cards";
		$current_user_id = get_current_user_id();
		$cardcalc = $wpdb->get_row( "SELECT * FROM  $table_name WHERE user_id = $current_user_id", ARRAY_N );
		ob_start();//allow shortcode to appear where it is placed	
		include ( plugin_dir_path( __DIR__ ) . "ghc_form.php");
		return ob_get_clean(); //allow shortcode to appear where it is placed
	}
	else{
		echo("You must be logged in to view the golf handicap calculator");
	}
};
