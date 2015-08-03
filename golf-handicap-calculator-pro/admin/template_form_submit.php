<?php
//Does the calculations for the template addition form
/** @var wpdb $wpdb */
global $wpdb; //define database connection
$table_name = $wpdb->prefix . "ghc_ct_data";
if (isset($_POST['ghc_add_submit'])){//adds a new course to the db
	$add_name= ucwords(strtolower($_POST["ghc_name_add"]));
	$name_check = $wpdb->get_var("SELECT course_name from $table_name WHERE course_name = '$add_name'");
	if ($name_check == ""){
		$wpdb->insert($table_name, array('course_name' => $add_name));
	}
	else{
		$string = 'That course already exists. \nPlease try another name or edit an existing template.';
		echo("<script> jQuery(document).ready(function(){alert(\"$string\");});</script>");
	}
}
if (isset($_POST['ghc_edit_submit'])){//populates the edit form with the selected template
	$edit_tee = $_POST["ghc_tee_select"];
	$edit_name = $_POST["ghc_name_select"];
	$par = strtolower($_POST["ghc_tee_select"]) . "_par";//converts par and si from select box value to the db column name
	$si= strtolower($_POST["ghc_tee_select"]) . "_si";
	$temp_arr = $wpdb -> get_row("select * from $table_name WHERE course_name = '$edit_name'", ARRAY_A);
	$temp_id = $temp_arr["course_id"];
	$temp_par = explode(",", $temp_arr["$par"]);//convert table data to string for the db
	$temp_si = explode(",", $temp_arr["$si"]);
}
if (isset($_POST['ghc_delete_submit'])){//extracts name of course, then deletes that row from the db
	$edit_name = $_POST["ghc_name_select"];
	if (!isset($_POST["ghc_tee_select"])){
		$wpdb -> delete($table_name, array('course_name' => $edit_name));
	}
	else{
		$tee = strtolower($_POST["ghc_tee_select"]);
		$wpdb->update($table_name, array( $tee . "_par" => null, $tee . "_si" => null), array("course_name" => $edit_name));//delete the template from the row
	}


}

if (isset($_POST['ghc_save_submit'])){//if submitting a template
	if (isset($_POST["ghc_name_hidden"])){//if editing a template
		$course_name = $_POST["ghc_name_hidden"];
		$course_tee = $_POST["ghc_tee_hidden"];
	}
	else{//if creating a new template
		$course_name = $_POST["ghc_template_name"];
		$course_tee = $_POST["ghc_template_tee"];
	}
	$temp_arr = $wpdb -> get_row("select * from $table_name WHERE course_name = '$course_name'", ARRAY_A);
	$course_id = $temp_arr["course_id"];
	$int_par = "";
	$int_si = "";
	for ($i=1 ;$i<=18 ;$i++){//convert table to string to store in db
		$int_par .= $_POST["ghc_par_input_" . $i] . ",";
		$int_si .= $_POST["ghc_si_input_" .$i]. ",";
	}
	$par = strtolower($course_tee) . "_par";//converts par and si from select box value to the db column name
	$si= strtolower($course_tee) . "_si";
	$add_template_edit_inputs = array(
		$par => $int_par,
		$si => $int_si
	);
	$add_template = $wpdb->update($table_name, $add_template_edit_inputs, array('course_id'=>$course_id)); //update template
}
