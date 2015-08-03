//Holds the js for the main input form.
//function explanation: 
//testSubmit = form validation (all fields complete) isNaN is automatic as they are number fields
//max_score_adjust = adjust the score so no more than 3 over par for females and 2 over par for males
//ini_handicap_calc = works out that total par and score for that card and places them in hidden field on the form
//template_autofill = validates and sends off the front-end template form
function testSubmit(){//Main form validation
	ghc_par_arr = new Array();
	ghc_si_arr = new Array();
	ghc_score_arr = new Array();
	var par_val = "";
	var numeric_val ="";
	var score_val ="";
	var si_val ="";
	var sex_val ="";
	for (i = 1; i <= 18; i++){
		jQuery("#par-" + i).css('border', '1px solid #ccc');
		jQuery("#si-" + i).css('border', '1px solid #ccc');
		jQuery("#score-" + i).css('border', '1px solid #ccc');
		ghc_par_arr.push(Math.round(document.forms['ghc_form']['par-' + i].value));
		ghc_score_arr.push(Math.round(document.forms['ghc_form']['score-' + i].value));
	}
	if (user_sex == ""){
		if(document.ghc_form.user_sex[0].checked){
			user_sex = "Male";
		}
		else if(document.ghc_form.user_sex[1].checked){
			user_sex = "Female";
		}
	}
	var validation = true;
	for (i = 0; i<=17; i++){//validate form
		if(ghc_par_arr[i] < 3 || ghc_par_arr[i] > 7){
			par_val = "\nPar must be at least 3 and no higher than 7";//Par fields validation failed
			jQuery("#par-" + (i+1)).css('border', '1px solid red');
			validation = false;
		}
		if(ghc_score_arr[i] < 1){
			score_val ="\nScore must be a positive number";//Score fields >1 validation failed
			jQuery("#score-" + (i+1)).css('border', '1px solid red');
			validation = false;
		}
	}
	if (handicap !=-1 && handicap !=-2 && handicap !=""){	
		for (i = 1; i <= 18; i++){
			ghc_si_arr.push(Math.round(document.forms["ghc_form"]["si-" + i].value));	
			if( isNaN(ghc_si_arr[i - 1]) === true || ghc_si_arr[i -1] < 1 || ghc_si_arr[i - 1] > 18){
				si_val = "\nStroke index must be between 1 and 18";//Stroke Index fields validation failed
				jQuery("#si-" + (i)).css('border', '1px solid red');
				validation = false;
			}
		}
	}	
	if (handicap ==""){ //if first card and thus no user sex in the database then check they have selected their sex
		if (user_sex !="Male" && user_sex !="Female"){
			sex_val = "\nPlease select whether you are Male or Female"; //user sex validation failed
			validation = false;
		}
	}
	if (validation == false){
		alert("You must fully complete the form before submitting. Hint:" + par_val + numeric_val + score_val + si_val + sex_val);
		return false;//validation failed
	}
	else{
		return true; //validation succeeded
	}
}
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
function max_score_adjust(){
	if (user_sex == ""){ //if no user sex in the database then get from the form
		if(document.ghc_form.user_sex[0].checked){
			user_sex = "Male";
		}
		else{
			user_sex = "Female";
		}
	}
	if (handicap ==""){ //initial handicap score adjustments
		if(user_sex == "Female"){
			//if the user is Female and the score is more than 3 over the par then knock it down to 3 over		
			for (i = 0; i <= 17; i++){
				if ((ghc_par_arr[i]+3) < ((ghc_score_arr[i]))){
					ghc_ini_score[i] = (ghc_ini_par[i]+3);
				}
			}
		}
		else if(user_sex == "Male"){
			//if the user is Male and the score is more than 2 over the par then knock it down to 2 over
			for (i = 0; i <= 17; i++){
				if ((ghc_par_arr[i]+2) < ((ghc_score_arr[i]))){
					ghc_ini_score[i] = (ghc_ini_par[i]+2);
				}
			}
		}
	}
	else{ //updating handicap score adjustments (includes the stroke index calculation)
		if(user_sex =="Female"){//if the user is Female and the score is more than 3 over the par then knock it down to 3 over	
			for (i = 0; i <= 17; i++){
				var si_adjust = Math.floor(handicap / 18);
				var remainder = handicap % 18 ;
				if (-(ghc_si_arr[i]-18) <= remainder){
				 	si_adjust += 1;
				}
				if (ghc_score_arr[i] < 1) {
					ghc_score_arr[i] = 1;
				}
				if ((ghc_par_arr[i]+3) < ((ghc_score_arr[i]))){
					ghc_score_arr[i] = ghc_par_arr[i]+3;
				}	
			}
		}
		else if(user_sex =="Male"){//if the user is Male and the score is more than 2 over the par then knock it down to 2 over
			for (i = 0; i <= 17; i++){
				var si_adjust = Math.floor(handicap / 18);
				var remainder = handicap % 18 ;
				if (-(ghc_si_arr[i]-18) < remainder){
				 	si_adjust += 1;
				}
				ghc_score_arr[i] -= si_adjust;//work out the allowed shots from stroke index and subtract from that score
				if (ghc_score_arr[i] < 1) {
					ghc_score_arr[i] = 1;
				}
				if ((ghc_par_arr[i]+2) < ghc_score_arr[i]){
					ghc_score_arr[i] = ghc_par_arr[i]+2;
				}
			}
		}
	}
	for (i = 0; i <= 17; i++){
		document.forms["ghc_form"]["score-" + (i+1)].value = ghc_score_arr[i];
	}
}
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
function ini_handicap_calc(){//works out that total par and score for that card and places them in hidden field on the form
	var par_total = 0
	var score_total = 0
	for (i = 0 ; i <=17; i++){//Sum of all the pars
		par_total = par_total + ghc_par_arr[i]
	}
	for (i = 0 ; i <=17; i++){//Sum of all the scores
		score_total = score_total + ghc_score_arr[i] 				
	}
	//Put the totals into the hidden fields on the form
	document.forms["ghc_form"]["par_total"].value = par_total;
	document.forms["ghc_form"]["score_total"].value = score_total;
}
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
function submitForm(){//final check and form submit
	if(testSubmit()){
		max_score_adjust();
		//ini_handicap_calc();
	   	document.forms["ghc_form"].submit(); 	
    	return true;
  	}
  	else{
  		return false;
  	}
}
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
function template_autofill(){//validates and sends off the front-end template form
	if(document.forms["ghc_template"]["ghc_fe_template_select"].value == "--Choose One--"){
		alert("Please select a template to use");
		return false;		
	}
	else{
		document.forms["ghc_template"]["ghc_fe_temp_hidden"].value = "temp_fill";
		document.forms["ghc_template"].submit();
	}
}
