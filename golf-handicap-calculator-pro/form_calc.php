<?php
//Does the final calculations and saves the score to the database from the input form.
add_action('init', 'ghc_form_calc');
function ghc_form_calc(){
	if (isset($_POST['ghc_submit'])){
		include "form_validation.php";
		if (php_validation() == true){ //check form passes server-side validation
			global $wpdb; //define database connection
			$table_name = $wpdb->prefix . "ghc_cards";
			$current_user_id = get_current_user_id();
			global $user_sex;//fetch the user's sex and handicap from the ghc_form.php file
			global $handicap;
			$date_time = current_time('mysql', 1);
			$par_csv = "";
			$si_csv = "";
			$score_csv = "";
			for($i = 1; $i <= 18; $i++){//convert the form inputs into csv's for storing in the database
				$par_csv .= $_POST["par-$i"] . ",";
				$si_csv .= $_POST["si-$i"] . ",";
				$score_csv .= $_POST["score-$i"] . ",";
			}
			$form_inputs=array(
				"user_id" => $current_user_id,
				"par" => $par_csv,
				"si" => $si_csv,
				"score" => $score_csv,
				"date_time" => $date_time
			);
			$form_inputs_escapes = array('%d','%s','%s' ,'%s' ,'%s' ,'%s');
			$wpdb->insert($table_name, $form_inputs, $form_inputs_escapes);// add a new card
			$user_cards = $wpdb->get_results("SELECT * FROM $table_name WHERE user_id = $current_user_id", ARRAY_A);//fetch all the users cards
			/*
			if(count($user_cards) == 3){//if there are exactly 3 cards for that user then work out that users handicap
				$insert_card_one  =   $wpdb->insert($table_1_name, $card_one_inputs);	
				$cardcalc = $wpdb->get_row( "SELECT * FROM  $table_1_name WHERE `user-id` = $current_user_id", ARRAY_N );
				$handicap = round(((($user_cards["0"]["score"] + $user_cards["1"]["score"] + $user_cards["2"]["score"])-($user_cards["0"]["par"] + $user_cards["1"]["par"] + $user_cards["2"]["par"]))/3),1);
				$final_handicap_inputs =  array('handicap' => $handicap //set the handicap);
				$calc_final_handicap  =   $wpdb->update($table_1_name, $final_handicap_inputs, $user_id); // send handicap to database
				$cardcalc = $wpdb->get_row( "SELECT * FROM  $table_1_name WHERE `user-id` = $current_user_id", ARRAY_N );
			}
			--------------------
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
				$update_handicap = $wpdb->update($table_name, $handicap_update_inputs, $user_id); //update database
				$cardcalc = $wpdb->get_row( "SELECT * FROM  $table_name WHERE user_id = $current_user_id", ARRAY_A );
				$handicap = $cardcalc[8];
			}*/
		}
		else{ //server-side form validation fail
			?><script>alert("Please fill out the whole form correctly")</script><?php
		}
	}
}
