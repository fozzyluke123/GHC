<?php
//Builds the score card input form.
$handicap =  $cardcalc[8];
global $wpdb;
$ct_table_name = $wpdb->prefix . "ghc_ct_data";
$table_name = $wpdb->prefix . "ghc_cards";
$temp_name_arr = $wpdb->get_col("SELECT course_name FROM $ct_table_name  WHERE comp_par != '' OR male_par != '' OR female_par != '' OR junior_par != ''");
$temp_name_count = count($temp_name_arr);
$i = 0;
if (isset($_POST["ghc_fe_template_select"])){ //if loading a template
	$temp_name = $_POST["ghc_fe_template_select"];
	$par = strtolower($_POST["ghc_fe_tee_select"] . "_par");
	$si = strtolower($_POST["ghc_fe_tee_select"] . "_si");
	$temp_arr = $wpdb ->get_row("SELECT * FROM $ct_table_name WHERE course_name ='$temp_name'",ARRAY_A);
	$temp_id = $temp_arr["course_id"];
	$temp_par_arr = explode(",", $temp_arr[$par]);//extract the par and si strings into arrays
	$temp_si_arr = explode(",", $temp_arr[$si]);
}
?>
<form method="post" name="ghc_template"> <!-- Form for selecting the templates -->

<b> Select a Template </b>
<select name = "ghc_fe_template_select" id="ghc_fe_template_select">
<option disabled selected>--Choose One--</option>
<?php
for($i=0 ;$i < $temp_name_count ;$i++ ){
	?><option><?php echo($temp_name_arr[$i])?></option><?php //load the different templates
}?>
</select>
	<select name="ghc_fe_tee_select" id="ghc_fe_tee_select" hidden></select>
	<script>// this will dynamically populate the tee select box depending on what tee's are completed for that course
		var course_array =
			<?php
			$temp_tee_arr = $wpdb->get_results("SELECT * FROM $ct_table_name" , "ARRAY_A");
			echo json_encode($temp_tee_arr); //converts from a php assoc_array into a js one
			?>;
		jQuery("#ghc_fe_template_select").change(function(){
			document.getElementById("ghc_fe_tee_select").removeAttribute("hidden"); // when a course has been selected then show the tee select box
			document.getElementById("ghc_fe_tee_select").innerHTML = ("");
			var index = document.getElementById("ghc_fe_template_select").selectedIndex - 1;
			if(course_array[index]["comp_par"] !== null && course_array[index]["comp_par"] !== "") {//if that tee has a template added then add it to the select box
				jQuery("#ghc_fe_tee_select").append("<option value='comp'>Competition</option>");
			}
			if(course_array[index]["male_par"] !== null && course_array[index]["male_par"] !== "" ) {
				jQuery("#ghc_fe_tee_select").append("<option>Male</option>");
			}
			if(course_array[index]["female_par"] !== null && course_array[index]["female_par"] !== "" ) {
				jQuery("#ghc_fe_tee_select").append("<option>Female</option>");
			}
			if(course_array[index]["junior_par"] !== null && course_array[index]["junior_par"] !== ""  ) {
				jQuery("#ghc_fe_tee_select").append("<option>Junior</option>");
			}
			if(document.getElementById("ghc_fe_tee_select").innerHTML == ("")){//if that course has no templates disable the select box and show filler text
				document.getElementById("ghc_fe_tee_select").innerHTML = ("<option value ='empty'>No Templates Saved</option>");
				document.getElementById("ghc_fe_tee_select").setAttribute('disabled', 'true');
			}
			else{
				document.getElementById("ghc_fe_tee_select").removeAttribute('disabled');
			}
		});
	</script>
<input type = "button" onclick = "template_autofill()" style="float:left; position:absolute; margin-left:10px;" name="ghc_template_submit" value="Submit"/>
<input type = "hidden" name = "ghc_fe_temp_hidden"/>
<br /><br />
Will automatically fill in form with the courses details.
<br /><br />
</form>
<form method="post" name="ghc_form"> <!-- Main input form -->
<?php if (isset($_POST["ghc_fe_template_select"])){ ?> <h3>Template for <?php echo($temp_name . " - " . ucwords($_POST["ghc_fe_tee_select"])) ?> loaded <?php }?> <!-- Displays the loaded template -->
<br /><br />

    <table class = "ghcform">
        <tr>
            <th>Hole No.</th>
            <th>Par</th>
            <th>Score</th>
            
            <?php if($handicap == "" || $handicap == "-1" ||$handicap == "-2" ){ //check for whether the user already has handicap
			}
			else{
			?>
            <th>Stroke Index</th>
            <?php } ?>
        </tr>
        <?php for ($i=1;$i<=18;$i++){?> <!-- Build the par, score and si parts of the form-->
            <tr>
                <td><?php echo $i ?></td>
                <td><input type = "number" name = "par-<?php echo $i ?>" <?php if(isset($_POST['ghc_fe_temp_hidden'])){?> value = "<?php echo $temp_par_arr[$i - 1] ?>" <?php } ?> /></td> <!--populate with the tamplate data-->
                <td><input type = "number" name = "score-<?php echo $i ?>" /></td>
                <?php if($handicap == "" || $handicap == "-1" ||$handicap == "-2" ){//check for whether the user already has handicap
                }
                else{
                ?>
                <td><input type = "number" name = "si-<?php echo $i ?>" <?php if(isset($_POST['ghc_fe_temp_hidden'])){?> value = "<?php echo $temp_si_arr[$i - 1] ?>" <?php } ?>/></td>
                <?php } ?>
            </tr>
        <?php }
		if($cardcalc[1] == "" && isset($_POST["ghc_submit"]) == false){ ?>                
            <tr>
                <td>Sex:</td>
                <td><input type = radio name = "user_sex" value = "Male"/>Male</td>
                <td><input type = radio name = "user_sex" value = "Female"/>Female</td>
                <?php if($handicap == "" || $handicap == "-1" ||$handicap == "-2" ){//check for whether the user already has handicap
					}
				else{
					?>
                	<td></td>
                <?php } ?>
            </tr>
        <?php } ?>
        <tr>    
            <td><input class = "ghc_button" name = "ghc_submit" type = "button" value="Submit" onClick="submitForm()"/></td>
            <td><input class = "ghc_button" name = "ghc_reset" type = "reset" /></td>
            <td></td>
            <?php if($handicap == "" || $handicap == "-1" ||$handicap == "-2" ){//check for whether the user already has handicap
			}
			else{
			?>
            <td></td>
            <?php }
			$user_sex = $cardcalc[1]; ?>

        </tr>
    </table>
    <input type="hidden" name="valid_succeed" /> <!-- these hidden fields hold values from/for the submit.js -->
    <input type="hidden" name="par_total"/>    
    <input type="hidden" name="score_total"/>
    <input type="hidden" name="handicap" value ="<?php echo $handicap ?>"/>
    <input type="hidden" name="db_user_sex" value ="<?php echo $user_sex ?>"/>
</form>
