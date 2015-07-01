// The js for submitting the template add/edit page forms.
function add_validation(){
    if(document.forms["ghc_add_form"]["ghc_name_add"].value == ""){
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
	if(document.forms["ghc_add_form"]["ghc_name_hidden"].value == "--Choose One--" || document.forms["ghc_add_form"]["ghc_name_hidden"].value == ""){
		alert("Please enter a name and select a tee type");
		return false;
	}
	for(i=1 ; i<=18 ; i++){
		si_value =Math.round(document.forms["ghc_add_form"]["ghc_si_input_" + i].value );
		par_value =Math.round(document.forms["ghc_add_form"]["ghc_par_input_" + i].value );
		if (par_value < 3 || par_value > 7){
			alert("You must fully complete the form before submitting.\nHint: Par must be at least 3 and no higher than 7"); //Par fields validation failed
			return false;
		}
		else if( isNaN(si_value) === true || si_value < 1 || si_value > 18){
			alert("You must complete the form before submitting.\nHint: Stroke index must be between 1 and 18");//Stroke Index fields validation failed
			return false;
		}
		else if(isNaN(si_value) || isNaN(par_value)){
			alert("You must fully complete the form before submitting.\nHint: Stroke index and par must be numeric");//Stroke index and par field numeric validation failed
			return false;
		}
	}
	if( document.forms["ghc_add_form"]["ghc_course_id"].value != "" ){//If editing a loaded template
		if (confirm("This will overwrite the currently selected template.\nAre you sure you want to do this?")==false){
            return false;
		}
        else{
            document.forms["ghc_add_form"]["ghc_save_type"].value = "edit";
        }
	}	
	else{	
		for (i=0;i < document.forms["ghc_edit_form"]["ghc_templates"].length; i++){//Checks to make sure a saved template doesnt already have this name/tee combo
			if (document.forms["ghc_add_form"]["ghc_name_hidden"].value + " - " + document.forms["ghc_add_form"]["ghc_templates"].value === document.forms["ghc_edit_form"]["ghc_templates"][i].value){
				alert("There is already a template with that course and tee saved");
				return false;
			}	
		}
	}
}
