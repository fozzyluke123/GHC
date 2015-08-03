// The js for submitting the template add/edit page forms.
function add_validation(){
    if(document.forms["ghc_add_course_form"]["ghc_name_add"].value == ""){
        alert("Please enter a name for the course");
        return false;
    }
}
function edit_validation(){//checks that a course and tee has been selected before displaying the template in the edit form
	if(document.forms["ghc_edit_form"]["ghc_name_select"].value === "--Select a Course--"){
		alert("Please pick a course you wish to edit");
		return false;
	}
    if(document.forms["ghc_edit_form"]["ghc_tee_select"].value === "empty") {
        alert("Please pick a tee you wish to edit");
        return false;
    }
}
function delete_validation(){//Template deletion
    if(document.forms["ghc_edit_form"]["ghc_name_select"].value == "--Select a Course--"){
        alert("Please pick a course you wish to delete");
        return false;
    }
    else if(document.forms["ghc_edit_form"]["ghc_tee_select"].value == "empty"){
        confirm("Are you sure you want to permanently delete this course?");
    }
    else{
        if (confirm("Are you sure you want to delete this template?")==false){
            return false;
        }
    }
}
function template_validation(){//checks that the template creation/ edit form is valid
	var i;
    var si_value;
    var par_value;
    var validation = true;
    var name_tee_val = "";
    var par_val = "";
    var si_val = "";
    for (i=1; i<=18; i++){
    	jQuery("#ghc_par_input_" + i).css('border', '1px solid #ccc');
		jQuery("#ghc_si_input_" + i).css('border', '1px solid #ccc');
    }
    jQuery("#ghc_template_name").css('border', '1px solid #ccc');
	jQuery("#ghc_template_tee").css('border', '1px solid #ccc');
	if(document.forms["ghc_add_form"]["ghc_name_hidden"] == undefined && document.forms["ghc_add_form"]["ghc_template_name"].value == "--Select a Course--"){//if not editing template then check for name being selected
		jQuery("#ghc_template_name").css('border', '1px solid red');
		name_tee_val =  "\nPlease select a name and tee type";
		validation = false;
	}
	else if (document.forms["ghc_add_form"]["ghc_name_hidden"] == undefined && document.forms["ghc_add_form"]["ghc_template_tee"].value == "--Choose One--"){//and check for tee being selected
		jQuery("#ghc_template_tee").css('border', '1px solid red');
		name_tee_val =  "\nPlease select a tee type";
		validation = false;
	}
	for(i=1 ; i<=18 ; i++){
		si_value =Math.round(document.forms["ghc_add_form"]["ghc_si_input_" + i].value );
		par_value =Math.round(document.forms["ghc_add_form"]["ghc_par_input_" + i].value );
		if (par_value < 3 || par_value > 7){
			jQuery("#ghc_par_input_" + (i)).css('border', '1px solid red');
			par_val = "\nPar must be at least 3 and no higher than 7"; //Par fields validation failed
			validation = false;
		}
		if( isNaN(si_value) === true || si_value < 1 || si_value > 18){
			jQuery("#ghc_si_input_" + (i)).css('border', '1px solid red');
			si_val = "\nStroke index must be between 1 and 18";//Stroke Index fields validation failed
			validation = false;
		}
	}
	if (validation == false){
		alert("You must fully complete the form before submitting. Hint:" + name_tee_val + par_val + si_val);
		return false;
	}
	if( document.forms["ghc_add_form"]["ghc_course_id"].value != "" ){//If editing a loaded template
		if (confirm("This will overwrite the currently selected template.\nAre you sure you want to do this?")==false){
           	return false;
		}
        else{
            document.forms["ghc_add_form"]["ghc_save_type"].value = "edit";
        }
	}	
}
