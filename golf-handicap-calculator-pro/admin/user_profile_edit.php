<?php
add_action('show_user_profile', 'ghc_user_profile_handicap_display');
add_action('edit_user_profile', 'ghc_admin_profile_handicap_display');
add_action( 'personal_options_update', 'ghc_profile_handicap_update' );
add_action('edit_user_profile_update', 'ghc_profile_handicap_update');

function ghc_user_profile_handicap_display(){//user profile handicap display
	global $wpdb; //define database connection and return the corresponding users row
	$table_name = $wpdb->prefix . "ghc_cards";
	$current_user_id = get_current_user_id();
	$cardcalc = $wpdb->get_row( "SELECT * FROM  $table_name WHERE user_id = $current_user_id", ARRAY_N );
	if($cardcalc[8] ==""){
		$cardcalc[8] = "N/A";
	}
	?>
    <table class = "form-table"/>
    <th>
    <label for = "user_handicap">Handicap</label>
    </th>
    <td>
    <input name = "user_handicap" type = "number" <?php if(!current_user_can('edit_users')){ ?> readonly <?php } ?> value =  "<?php echo $cardcalc[8]?>" style="background-color:#fff"/>
    </td>
    </table>
	<?php	
}
function ghc_admin_profile_handicap_display(){
	global $wpdb; //define database connection and return the corresponding users row
	$table_name = $wpdb->prefix . "ghc_cards";
	global $profileuser;
	$user_id = $profileuser->ID;
	global $user_id;
	$cardcalc = $wpdb->get_row( "SELECT * FROM  $table_name WHERE user_id = $user_id", ARRAY_N );
	if($cardcalc[8] ==""){
		$cardcalc[8] = "N/A";
	}
	?>
    <table class = "form-table"/>
    <th>
    <label for = "user_handicap">Handicap</label>
    </th>
    <td>
    <input name = "user_handicap" type = "number" value =  "<?php echo $cardcalc[8]?>" style="background-color:#fff"/>
    </td>
    </table>
	<?php
};
function ghc_profile_handicap_update() {
	global $user_id;
	global $wpdb; //define database connection and return the corresponding users row
	$table_name = $wpdb->prefix . "ghc_cards";
	if ( !current_user_can( 'edit_users') ){
		return false;
	}
	$handicap = $_POST['user_handicap'];
	$new_handicap = array(
		'user-id' => $user_id,
		'handicap' => $handicap
	);
	$wpdb->replace( $table_name, $new_handicap);
}
