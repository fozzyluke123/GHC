<?php
add_shortcode("ghc_display_users","ghc_shortcode_display_users");//Creates a table showing the golf details of all users on the site
function ghc_shortcode_display_users(){
	ob_start();//allow shortcode to appear where it is placed
	global $wpdb;
	$table_name = $wpdb->prefix . "ghc_cards";
	$user_count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE handicap <> '-1'  and handicap <> '-2'" );//count how many users to will be displayed

	?> <table class="ghc_stats_table"> <!-- Builds the tables headers -->
    	<th>Name</th>
        <th>M/F</th>
        <th>3rd net score*</th>
        <th>2nd net score*</th>
        <th>Latest net score*</th>
        <th>Handicap</th>
        <th>True Handicap</th>
        <?php
	for ($col_i = 0; $col_i < $user_count; $col_i++){
		$user_table = $wpdb->get_row( "SELECT * FROM  $table_name WHERE handicap <> '-1'  and handicap <> '' and handicap <> '-2' ORDER BY handicap DESC", ARRAY_N, $col_i ); //retrieve all the eligible users in handicap descending order
		$user_id = $user_table[0];
		$user_name = (get_user_meta( $user_id, 'first_name', true) . " " . get_user_meta( $user_id, 'last_name', true));
		$net_1 = $user_table[3] - $user_table[2];
		$net_2 = $user_table[5] - $user_table[4]; 
		$net_3 = $user_table[7] - $user_table[6];	
		?> <tr> 
        	<td> <?php echo $user_name ?> </td> <!-- Populate table -->
            <td> <?php echo $user_table[1] ?> </td>
            <td> <?php echo $net_1 ?> </td>
            <td> <?php echo $net_2 ?> </td>
            <td> <?php echo $net_3 ?> </td>
            <td> <?php echo round($user_table[8]) ?> </td>
            <td> <?php echo $user_table[8] ?> </td>
		</tr><?php
	};
	?>
    </table>
    *Net score = score - par <?php
	return ob_get_clean(); //allow shortcode to appear where it is placed
}
