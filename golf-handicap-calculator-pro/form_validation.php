<?php
//Server-side form validation.
function php_validation(){
	$handicap =  $_POST["handicap"];
	$i = 0;
	for ($i=1; $i<=18 ;$i++){//validaition excluding stroke index forms
		if ($handicap == "" || $handicap == "-1" ||$handicap == "-2" ){//check for whether the user already has handicap
			if ((is_numeric($_POST[("par-" . $i)]) === true)
				&& (is_numeric($_POST[("score-" . $i)]) === true)
				&&($_POST[("score-" . $i)]>=1)
				&& ($_POST[("par-" . $i)]>=3)
				&& ($_POST[("par-" . $i)]<=7)){
				$response = true;
			}
			else{
				return false; //if validation fails then get out and return false
			}
		}
		else{ //validation including stroke index forms
			if ((is_numeric($_POST[("par-" . $i)]) === true)
				&& (is_numeric($_POST[("score-" . $i)]) === true)
				&&(is_numeric($_POST[("si-" . $i)]) === true)
				&&($_POST[("score-" . $i)]>=1)
				&& ($_POST[("par-" . $i)]>=3)
				&& ($_POST[("par-" . $i)]<=7)
				&& ($_POST[("si-" . $i)] >=1)
				&& ($_POST[("si-" . $i)] <=18)){
				$response = true;
			}
			else{
				return false; //if validation fails then get out and return false
			}
		}
	}	
	if (isset($_POST['user_sex'])||isset($_POST['db_user_sex'])){ //check that either user already has a sex or has entered one
			$response = true;
	}
	else{
		return false; //if validation fails then get out and return false
	}
	if($response == true){
		return true;
	}
}
