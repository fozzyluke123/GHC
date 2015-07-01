<?php
add_shortcode("ghc_user_details", "ghc_shortcode_user_details");//Echoes the current users handicap
function ghc_shortcode_user_details(){
	ob_start();//allow shortcode to appear where it is placed
	global $wpdb;//connect to database
	$table_name = $wpdb->prefix . "ghc_cards";
	$current_user_id = get_current_user_id();
	if (is_user_logged_in() == true){
		$cardcalc = $wpdb->get_row( "SELECT * FROM  $table_name WHERE user_id = $current_user_id", ARRAY_N );
		if ($cardcalc[8] =="" || $cardcalc[8] ==-1 || $cardcalc[8] ==-2){
			echo("N/A");
		}
		else{
		echo $cardcalc[8];
		}
	}
	else{
		echo("You must be logged in to view your handicap");//echo if the user is not logged in
	}
	return ob_get_clean(); //allow shortcode to appear where it is placed
}
