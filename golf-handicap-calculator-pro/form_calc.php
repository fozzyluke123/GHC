<?php
//Does the final calculations and saves the score to the database from the input form.

add_action('init', 'ghc_form_calc');
function ghc_form_calc(){
	global $wpdb; //define database connection and return the corresponding users row
	$table_name = $wpdb->prefix . "ghc_cards";
	$current_user_id = get_current_user_id();
	$cardcalc = $wpdb->get_row( "SELECT * FROM  $table_name WHERE user_id = $current_user_id", ARRAY_A );
if (isset($_POST['valid_succeed'])){
		if(($_POST['valid_succeed'])=="success"){
			include "form_validation.php";
			if (php_validation() == true){ //check form passes server-side validation
				$par_total = $_POST['par_total'];
				$score_total = $_POST['score_total'];
				$handicap_user_id = array(
				'user_id' => $current_user_id
				);
				if($cardcalc[8] === NULL){//if the handicap is empty then no cards have been sent and this is card 1
					$user_sex = $_POST['user_sex'];
					$card_one_inputs =  array(
						'card_1_score' => $score_total,
						'card_1_par' => $par_total,
						'user_sex' => $user_sex,
						'user_id' => $current_user_id,
						'handicap' => -1
					);
				$insert_card_one  =   $wpdb->insert($table_1_name, $card_one_inputs);	
				$cardcalc = $wpdb->get_row( "SELECT * FROM  $table_1_name WHERE user_id = $current_user_id", ARRAY_N );
				}
				else if ($cardcalc[8] === '-1'){ //if handicap is -1 then 1 card has already been sent and this is card 2
					$card_two_inputs =  array(
						'card_2_score' => $score_total,
						'card_2_par' => $par_total,
						'handicap' => -2
					);
				$insert_card_two  =   $wpdb->update($table_1_name, $card_two_inputs, $handicap_user_id);
				$cardcalc = $wpdb->get_row( "SELECT * FROM  $table_1_name WHERE user_id = $current_user_id", ARRAY_N );
				}
				 else if ($cardcalc[8] === '-2'){//if handicap is 2 then 2 cards have been sent and this is card 3
					$card_three_inputs =  array(
						'card_3_score' => $score_total,
						'card_3_par' => $par_total,
						'handicap' => -2
					);
				$insert_card_three  =   $wpdb->update($table_1_name, $card_three_inputs, $handicap_user_id);
				$cardcalc = $wpdb->get_row( "SELECT * FROM  $table_1_name WHERE user_id = $current_user_id", ARRAY_N );
				$handicap = round(((($cardcalc[3] + $cardcalc[5] + $cardcalc[7])-($cardcalc[2] + $cardcalc[4] + $cardcalc[6]))/3),1);//final initial handicap calculation
				$final_handicap_inputs =  array(
						'handicap' => $handicap //set the handicap
						
					);
					$calc_final_handicap  =   $wpdb->update($table_1_name, $final_handicap_inputs, $handicap_user_id); // send handicap to database
					$cardcalc = $wpdb->get_row( "SELECT * FROM  $table_1_name WHERE user_id = $current_user_id", ARRAY_N );
				}
				else{ //if there is already a handicap that is -1 or -2 then this will be updating the exisiting handicap
						$handicap = $cardcalc[8];
						$net_score = (($score_total - $par_total) - $handicap);
						switch($handicap){
							case $handicap >= 0.1 && $handicap <= 5.4: //category 1
								$category = 1;
								$reduction = -0.1;
							break;	
							case $handicap >= 5.5 && $handicap <= 12.4: //category 2
								$category = 2;
								$reduction = -0.2;
							break;	
							case $handicap >= 12.5 && $handicap <= 20.4: //category 3
								$category = 3;
								$reduction =- 0.3;
							break;	
							case $handicap >= 20.5 && $handicap <= 28.4: //category 4
								$category = 4;
								$reduction = -0.4;
							break;	
							case $handicap >= 28.5: //category 5
								$category = 5;
								$reduction = -0.5;
						}
						if($net_score > $category){ //increase handicap
							$handicap = $handicap+ 0.1;
						}
						else if($net_score < 0){ //lower handicap
							$handicap = round($handicap -( $net_score * $reduction),1);
						}
						else{ //handicap stays the same
						}
						if ($handicap < 0){ //handicap cant go below 0
							$handicap = 0;	
						}
						if ($handicap > 48){ // handicap cant go over 48
							$handicap = 48;	
						}		
					$handicap_update_inputs = array(
						'card_1_par' => $cardcalc[4],
						'card_1_score' => $cardcalc[5],
						'card_2_par' => $cardcalc[6],
						'card_2_score' => $cardcalc[7],
						'card_3_score' => $score_total,
						'card_3_par' => $par_total,					
						'handicap' => $handicap
					);
					$update_handicap = $wpdb->update($table_1_name, $handicap_update_inputs, $handicap_user_id); //update database
					$cardcalc = $wpdb->get_row( "SELECT * FROM  $table_1_name WHERE user_id = $current_user_id", ARRAY_N );
					$handicap = $cardcalc[8];
					}
				}
			else{ //server-side form validation fail
					?>
					<script>
						alert("Please fill out the whole form correctly")
					</script>
                    <?php
			}
		}	
	}
}
