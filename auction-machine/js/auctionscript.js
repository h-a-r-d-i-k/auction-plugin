jQuery(document).ready(function() {
	jQuery('#auction_list').DataTable();
		
	jQuery(".auction_type"). click(function(){
		var radioValue = jQuery(".auction_type:checked"). val();
		if(radioValue == "Reference"){
		jQuery(".reference").show();
		jQuery("#reference").prop('required',true);		
		}else{
		jQuery(".reference").hide();
		jQuery("#reference").prop('required',false);				
		}
	});	
	jQuery('#field_type').on('change', function(){		
		if(this.value == 3 || this.value == 6 || this.value == 7 || this.value == 8 || this.value == 10){
			jQuery(".checkedradio").show();
			jQuery(".hideMutiple").hide(); 
			jQuery(".operator_names").hide();
			jQuery(".operator_field").hide(); 
			jQuery(".multidrop").hide();
		  }else if(this.value == 9){		
			jQuery(".multidrop").show();
			jQuery(".checkedradio").hide();
			jQuery(".hideMutiple").hide(); 
			jQuery(".operator_names").hide();
			jQuery(".operator_field").hide();  
		  }else if(this.value == 11){
			jQuery(".operator_names").show();  
			jQuery(".operator_field").show();
			jQuery(".checkedradio").hide();     
			jQuery(".hideMutiple").hide(); 
			jQuery(".multidrop").hide();
		  }else if(this.value == 1 || this.value == 2 || this.value == 4 || this.value == 5){
			  jQuery(".hideMutiple").show();
			  jQuery(".checkedradio").hide();
			  jQuery(".operator_names").hide();
			  jQuery(".operator_field").hide(); 
			  jQuery(".multidrop").hide();
		  }else if(this.value == 12){
			jQuery(".checkedradio").hide();  
			jQuery(".operator_names").hide();
			jQuery(".operator_field").show();    
			jQuery(".hideMutiple").hide();	
			jQuery(".multidrop").hide();			
		  }else{			 
			jQuery(".checkedradio").hide();  
			jQuery(".operator_names").hide();
			jQuery(".operator_field").hide();  
			jQuery(".hideMutiple").hide(); 
			jQuery(".multidrop").hide();			
		  }
	});
	
	var field_type = jQuery("#field_type").val();
	if(field_type == 12){
		jQuery(".operator_names").hide();
	}	
	var ft = jQuery("#field_type").val();
	if(ft == 3 || ft == 6 || ft == 7){
		jQuery(".checkedradio").show();
		jQuery(".hideMutiple").hide(); 
		jQuery(".operator_names").hide();
		jQuery(".multidrop").hide();
		jQuery(".operator_field").hide();
	} else if(ft == 1 || ft == 2 || ft == 4 || ft == 5){
		jQuery(".checkedradio").hide();
		jQuery(".hideMutiple").show(); 
		jQuery(".operator_names").hide();
		jQuery(".multidrop").hide();
		jQuery(".operator_field").hide();
	} else if(ft == 11){
		jQuery(".checkedradio").hide();
		jQuery(".hideMutiple").hide(); 
		jQuery(".operator_names").show();
		jQuery(".multidrop").hide();
		jQuery(".operator_field").show();
	} else if(ft == 10 || ft == 8) {
		jQuery(".hideMutiple").hide(); 
		jQuery(".operator_names").hide();
		jQuery(".operator_field").hide();
		jQuery(".multidrop").hide();
		jQuery(".checkedradio").show();
	} else if(ft == 9) {
		jQuery(".multidrop").show();
		jQuery(".hideMutiple").hide(); 
		jQuery(".operator_names").hide();
		jQuery(".operator_field").hide();
		jQuery(".checkedradio").hide();
	}  else {
		jQuery(".operator_names").hide();
		jQuery(".checkedradio").hide();
		jQuery(".hideMutiple").hide();
		jQuery(".multidrop").hide();
		jQuery(".operator_field").hide();
	}

	var max_fields      = 10; //maximum input boxes allowed
	var add_button      = jQuery(".add_field_button"); //Add button ID
	var add_multiple_button      = jQuery(".add_multi_field_button"); //Add button ID
	
	var x = 1; //initlal text box count
	jQuery(add_button).click(function(e){ //on add input button click	
		e.preventDefault();
		if(x < max_fields){ //max input box allowed
			x++; //text box increment
			var appendTxt = '<tr class="extrafield"><th class="ss-th-width">Add Value</th><td><input type="text" class="ss-field-width" name="field_value[]" /><a href="JavaScript:void(0);" class="remove_field">Remove</a></td></tr>';
			jQuery(".appenddata:last").after(appendTxt);
		}
	});
	
	jQuery(add_multiple_button).click(function(e){ //on add input button click			
		e.preventDefault();
		if(x < max_fields){ //max input box allowed
			x++; //text box increment
			var get_name = jQuery(this).parent().html();
			var field_name = get_name.split('"');	
			var appendTxt = '<tr class="extrafield"><th class="ss-th-width">Add Value</th><td><input type="text" class="ss-field-width" name="'+field_name[5]+'[]" /><a href="JavaScript:void(0);" class="remove_field">Remove</a></td></tr>';
			jQuery(this).parent().parent().closest(".multidropappend").after(appendTxt);
		
		}
	});

	jQuery(document).on("click",".remove_field", function(e){ //user click on remove text
		e.preventDefault();
		jQuery(this).parent().parent('tr').remove();
	});
	
	jQuery("#auction_name").change(function() {
		if (jQuery(this).data('options') === undefined) {
			/*Taking an array of all options-2 and kind of embedding it on the select1*/
			jQuery(this).data('options', jQuery('#category option').clone());
		  }
		  var id = jQuery(this).val();
		  var options = jQuery(this).data('options').filter('[value=' + id + ']');
		  jQuery('#category').html(options);
	});	
	
});


	
