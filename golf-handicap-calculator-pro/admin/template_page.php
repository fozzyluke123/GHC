<?php
add_action( 'admin_enqueue_scripts', 'add_template_submit_script' );
function add_template_submit_script(){
	wp_enqueue_script( 'golf-handicap-calculator', plugins_url('/template_submit.js', __FILE__) );
};
function ghc_template_page(){// The page to enter templates for courses
	global $wpdb; //define database connection and return the corresponding users row
	$table_name = $wpdb->prefix . "ghc_ct_data";
	include "template_form_submit.php";//Does the calculations for the template addition form
	?>
	<div class="ghc_admin_page" style="width:30%;">
		</br>
		<h1>Course templates</h1>
		<p>To enter a new template fill out the form and save, to edit a form, select the form from the drop down list and then change the values in the form and save.</p>
		<br />
		<form method="post" name="ghc_add_form">
			<h2>Add a new course</h2>
			<br />
			<input type="text" name="ghc_name_add" maxlength="50"/>
			<input type ="submit" name="ghc_add_submit" value="Submit" onclick="return add_validation()"/>
		</form>
		<br />
		<hr />
		<br />
		<form method="post" name="ghc_edit_form">  <!-- form to select templates to either delete or edit-->
			<h2>Edit an existing template</h2>
			<br />
			<label for="ghc_name_select">Course Name</label>
			<select name="ghc_name_select" class="ghc_name_select" id="ghc_name_select">
				<option disabled selected>--Select a Course--</option>
				<?php
				$temp_name_arr = $wpdb->get_col("SELECT DISTINCT course_name FROM $table_name");//loads all courses into arrays for tee and name from the db
				$temp_name_count = count($temp_name_arr);
				for($i=0 ;$i < $temp_name_count ;$i++ ){
					echo("<option>" . $temp_name_arr[$i] . "</option>");
				}
				?>
			</select>
			<select name="ghc_tee_select" id="ghc_tee_select" hidden>
			</select>
			<script>// this will dynamically populate the tee select box depending on what tee's are completed for that course
				var course_array =
					<?php
					$temp_tee_arr = $wpdb->get_results("SELECT * FROM $table_name", "ARRAY_A");
					echo json_encode($temp_tee_arr); //converts from a php assoc_array into a js one
					?>;
				jQuery(".ghc_name_select").change(function(){
					document.getElementById("ghc_tee_select").removeAttribute("hidden"); // when a course has been selected then show the tee select box
					document.getElementById("ghc_tee_select").innerHTML = ("");
					var index = document.getElementById("ghc_name_select").selectedIndex - 1;
					if(course_array[index]["comp_par"] !== null && course_array[index]["comp_par"] !== "") {//if that tee has a template added then add it to the select box
						jQuery("#ghc_tee_select").append("<option value='comp'>Competition</option>");
					}
					if(course_array[index]["male_par"] !== null && course_array[index]["male_par"] !== "" ) {
						jQuery("#ghc_tee_select").append("<option>Male</option>");
					}
					if(course_array[index]["female_par"] !== null && course_array[index]["female_par"] !== "" ) {
						jQuery("#ghc_tee_select").append("<option>Female</option>");
					}
					if(course_array[index]["junior_par"] !== null && course_array[index]["junior_par"] !== ""  ) {
						jQuery("#ghc_tee_select").append("<option>Junior</option>");
					}
					if(document.getElementById("ghc_tee_select").innerHTML == ("")){//if that course has no templates disable the select box and show filler text
						document.getElementById("ghc_tee_select").innerHTML = ("<option value ='empty'>No Templates Saved</option>");
						document.getElementById("ghc_tee_select").setAttribute('disabled', 'true');
					}
					else{
						document.getElementById("ghc_tee_select").removeAttribute('disabled');
					}
				});
			</script>
			<input type="submit" name="ghc_edit_submit" value="Edit" onclick="return edit_validation()"/>
			<input type="submit" name="ghc_delete_submit" value="Delete" onclick="return delete_validation()"/>
		</form>
		<!--==================================================================================-->
		<!--==================================================================================-->
		<!--==================================================================================-->
		<br />
		<hr />
		<br />
		<form method="post" name="ghc_add_form"> <!-- Form to add more templates and edit templates -->
			<?php if (isset($_POST['ghc_edit_submit'])){
			?>
				<b style="color:red; font-size:1.2em; text-decoration:underline;">Currently editing - <?php echo $edit_name . " - " . $edit_tee; ?></b>
				<br />
				<input type="hidden" value= "<?php echo($edit_name); ?>" name="ghc_name_hidden"/>
				<input type="hidden" value= "<?php echo($edit_tee); ?>" name="ghc_tee_hidden"/>
			<?php
			}
			else{
			?>
				<h2>Add a new tee template</h2>
				<br />
				<label for="ghc_name_hidden">Course Name -</label>
				<select name="ghc_name_hidden" id="ghc_name_hidden">
					<option disabled selected>--Select a Course--</option>
					<?php
					$course_name_arr = $wpdb->get_col("SELECT DISTINCT course_name FROM $table_name");//loads all courses into arrays for tee and name from the db
					$course_name_count = count($course_name_arr);
					for($i=0 ;$i < $course_name_count ;$i++ ){
						echo("<option>" . $course_name_arr[$i] . "</option>");
					}
					?>
				</select>
				<select name="ghc_tee_hidden" id ="ghc_tee_hidden" hidden>
					<option selected>--Choose One--</option> <!-- Tee Selection-->
					<option id="ghc_tee_hidden_comp">Competition</option>
					<option id="ghc_tee_hidden_male">Male</option>
					<option id="ghc_tee_hidden_female">Female</option>
					<option id="ghc_tee_hidden_junior">Junior</option>
				</select>
				<script >// this will dynamically populate the tee select box depending on what tee's are completed for that course
					var course_array_add =
						<?php
						$temp_tee_arr = $wpdb->get_results("SELECT * FROM $table_name", "ARRAY_A");
						echo json_encode($temp_tee_arr); //converts from a php assoc_array into a js one
						?>;
					jQuery("#ghc_name_hidden").change(function(){
						document.getElementById("ghc_tee_hidden").removeAttribute("hidden"); // when a course has been selected then show the tee select box
						var index = document.getElementById("ghc_name_hidden").selectedIndex - 1;
						function dynamic_tee(tee){
							if(course_array_add[index][tee + "_par"] !== null && course_array_add[index][tee + "_par"] !== "") {//if that tee has a template added then add it to the select box
								document.getElementById("ghc_tee_hidden_" + tee).setAttribute('disabled', 'true');
							}
							else{
								document.getElementById("ghc_tee_hidden_" + tee).removeAttribute('disabled');
							}
						}
						dynamic_tee("comp");
						dynamic_tee("male");
						dynamic_tee("female");
						dynamic_tee("junior");
					});
				</script>
			<?php } ?>
			<br /><br />
			<table class = "ghc_template_form" cellspacing="0">
				<tr>
					<th>Hole No.</th>
					<th>Par</th>
					<th>SI</th>
				</tr>
				<?php
				for ($int = 1; $int <= 18; $int++){ //Loop building the form
					if(empty($temp_par[$int - 1])){
						$temp_par[$int - 1] = "";
						$temp_si[$int - 1] = "";
					}
					?>
					<tr>
						<td><?php echo $int ?></td>
						<td><input type="number" name="ghc_par_input_<?php echo $int ?>" value="<?php echo $temp_par[$int - 1] ?>" /></td>
						<td><input type="number" name="ghc_si_input_<?php echo $int ?>" value="<?php echo $temp_si[$int - 1] ?>"/></td>
					</tr>
				<?php } ?>
			</table>
			<br />
			<input class = "ghc_button" name = "ghc_save_submit" type = "submit" value="Save" onclick="return template_validation()"/>
			<input class = "ghc_button" name = "ghc_reset" type = "reset" value="reset"/>
			<?php if (isset($temp_id)){?>
				<input class="ghc_button" name = "ghc_edit_exit" type = "button" value = "Exit edit mode" onclick=" window.location.href =document.URL"/>  <?php // need to reset this way to erase the values set by php
			}
			?>
			<input type = "hidden" name = "ghc_save_type"/>
			<input type = "hidden" name = "ghc_course_id" <?php if (isset($temp_id)){ ?> value="<?php echo $temp_id ?>" <?php } ?>/>
		</form>
	</div>
<?php
};
