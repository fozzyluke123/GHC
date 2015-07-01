//Holds the js for the main input form.

//function explanation 
//testSubmit = form validation (all fields complete) isNaN is automatic as they are number fields
//max_score_adjust = adjust the score so no more than 3 over par for females and 2 over par for males
//ini_handicap_calc = works out that total par and score for that card and places them in hidden field on the form
//template_autofill = validates and sends off the front-end template form
	var ghc_par_arr = new Array();
	var ghc_si_arr = new Array();
	var ghc_score_arr = new Array();
function testSubmit(){
	for (i = 1; i <= 18; i++){
		ghc_par_arr.push(Math.round(document.forms['ghc_form']['par-' + i].value));
		console.log(ghc_par_arr);
		ghc_score_arr.push(Math.round(document.forms['ghc_form']['score-' + i].value));
	}
	
	var handicap = document.forms["ghc_form"]["handicap"].value; 
	if (document.forms["ghc_form"]["db_user_sex"].value == ""){
		if(document.ghc_form.user_sex[0].checked){
			var user_sex = "Male";
		}
		else if(document.ghc_form.user_sex[1].checked){
			var user_sex = "Female";
		}
	}
	else{
		var user_sex = document.forms["ghc_form"]["db_user_sex"].value;
	}
	var validation = false;
	for (i = 0; i<=17; i++){//validate form

		if(ghc_par_arr < 3 || ghc_par_arr > 7){
			alert("You must fully complete the form before submitting.\nHint: Par must be at least 3 and no higher than 7")//Par fields validation failed
			return false;
		}
		else if(isNaN(ghc_score_arr) || isNaN(ghc_par_arr)){
			alert("You must fully complete the form before submitting.\nHint: Score and par must be numeric")//Score and par field numeric validation failed
			return false;
		}
		else if(ghc_score_arr < 1){
			alert("You must fully complete the form before submitting.\nHint: Score must be positive")//Score fields >1 validation failed
			return false;
		}
	}
	if (handicap !=-1 && handicap !=-2 && handicap !=""){	
		for (i = 0; i <= 18; i++){
			ghc_si_arr.push(Math.round(document.forms["ghc_form"]["si-" + i].value));
		}
		if( isNaN(ghc_si_arr) === true || ghc_si_arr < 1 || ghc_si_arr > 18){
				alert("You must complete the form before submitting.\nHint: Stroke index must be between 1 and 18")//Stroke Index fields validation failed
				return false;
			}
		}	
	if (handicap ==""){ //if first card and thus no user sex in the database then check they have selected their sex
		if (user_sex !="Male" && user_sex !="Female"){
			alert("Please select whether you are Male or Female"); //user sex validation failed
			return false;	
		}
	}
	return true; //validation succeeded
}
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
function max_score_adjust(){
	var handicap = document.forms["ghc_form"]["handicap"].value;
	if (document.forms["ghc_form"]["db_user_sex"].value == ""){ //if no user sex in the database then get from the form
		if(document.ghc_form.user_sex[0].checked){
			var user_sex = "Male";
		}
		else{
			var user_sex = "Female";
		}
	}
	else{ //else user_sex will be taken from the database
		var user_sex = document.forms["ghc_form"]["db_user_sex"].value;
	}
	if (handicap ==-1 || handicap ==-2 ||handicap ==""){ //initial handicap score adjustments
		if(user_sex == "Female"){
			//if the user is Female and the score is more than 3 over the par then knock it down to 3 over		
			for (var i = 0; i <= 17; i++){
				if ((ghc_par_arr+3) < ((ghc_score_arr)) == true){
					ghc_score_arr = (ghc_par_arr+3);
				}
			}
		}
		else if(user_sex == "Male"){
			//if the user is Male and the score is more than 2 over the par then knock it down to 2 over
			
			for (var i = 0; i <= 17; i++){
				if ((ghc_par_arr+2) < ((ghc_score_arr)) == true){
					document.forms["ghc_form"]["score-" + i].value = document.forms["ghc_form"]["par-" + i].value + 2;
				}
			}
	
		}
		else{
		}
	}
	else{ //updating handicap score adjustments (includes the stroke index calculation)
		if(user_sex =="Female"){//if the user is Female and the score is more than 3 over the par then knock it down to 3 over	
			for (var i = 0; i <= 17; i++){
				var si_adjust = Math.floor(((handicap - ghc_si_arr) / 18)+1)
				ghc_score_arr = ghc_score_arr - si_adjust;//work out the allowed shots from stroke index and subtract from that score
				if (ghc_score_arr < 1) {
					ghc_score_arr = 1;
				}
				if ((ghc_par_arr+3) < ((ghc_score_arr)) == true){
					ghc_score_arr = (ghc_par_arr+3);
				}
			}
		}
		else if(user_sex =="Male"){//if the user is Male and the score is more than 2 over the par then knock it down to 2 over
			for (var i = 0; i <= 17; i++){
				var si_adjust = Math.floor(((handicap - ghc_si_arr) / 18)+1)
				ghc_score_arr = ghc_score_arr - si_adjust;//work out the allowed shots from stroke index and subtract from that score
				if (ghc_score_arr < 1) {
					ghc_score_arr = 1;
				}
				if ((ghc_par_arr+2) < ((ghc_score_arr)) == true){
					ghc_score_arr = (ghc_par_arr+2);
				}
			}
	
		}
	}
}
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
function ini_handicap_calc(){//works out that total par and score for that card and places them in hidden field on the form
	var par_total = 0
	var score_total = 0
	for (var i = 0 ; i <=17; i++){//Sum of all the pars
		par_total = par_total + ghc_par_arr
	}
	for (i = 0 ; i <=17; i++){//Sum of all the scores
		score_total = score_total + ghc_score_arr 				
	}
	//Put the totals into the hidden fields on the form
	document.forms["ghc_form"]["par_total"].value = par_total;
	document.forms["ghc_form"]["score_total"].value = score_total;
	document.forms["ghc_form"]["valid_succeed"].value = "success";
}
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------------------------------
function submitForm(){//final check and form submit
	if(testSubmit()){
		max_score_adjust();
		ini_handicap_calc();
	   	document.forms["ghc_form"].submit(); 	
    	return true;
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
