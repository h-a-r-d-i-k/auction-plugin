<?php
function auction_option () {	
//insert
    if (isset($_POST['insert_options'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . "auction_options";
	 	$auction_name = $_POST['auction_name'];
		$option_name = $_POST['option_name'];
		$field_type = $_POST['field_type'];
		$operator_names = $_POST['operator_names'];
		$operator_field = $_POST['operator_field'];
		
		if(isset($_POST['field_value']) && (!empty($_POST['field_value']))){
		$field_value = json_encode($_POST['field_value']);
		}else{
		$field_value = "";	
		}
		
		if(isset($_POST['field_value1']) && (!empty($_POST['field_value1']))){
		$dropdown_value_1 = json_encode($_POST['field_value1']);
		}else{
		$dropdown_value_1 = "";	
		}
		
		if(isset($_POST['field_value2']) && (!empty($_POST['field_value2']))){
		$dropdown_value_2 = json_encode($_POST['field_value2']);
		}else{
		$dropdown_value_2 = "";	
		}
		
		if(isset($_POST['field_value3']) && (!empty($_POST['field_value3']))){
		$dropdown_value_3 = json_encode($_POST['field_value3']);
		}else{
		$dropdown_value_3 = "";	
		}

		
		//print_r($add_value);
		if(isset($_POST['multiple'])){
		$multiple = $_POST['multiple'];
		}else{
		$multiple = '0';	
		}
		if(isset($advance_feature)){
		$advance_feature = $advance_feature;
		}else{
		$advance_feature = '0';
		}
		$description = $_POST['description'];
		$category = $_POST['category'];	
		
		$names_check = $wpdb->get_results("SELECT auction_option_name FROM $table_name where auction_option_name = '$option_name'");		  
		  $result = (int)$wpdb->num_rows;		  
			if($result == 0){			
			   $data = $wpdb->insert(
						$table_name, //table
						array('auction_name' => $auction_name,
						'auction_option_name' => $option_name,
						'field_type' => $field_type,
						'multiple' => $multiple,
						'description' => $description,
						'category' => $category,
						'field_value' => $field_value,
						'dropdown_value_1' => $dropdown_value_1,
						'dropdown_value_2' => $dropdown_value_2,
						'dropdown_value_3' => $dropdown_value_3,
						'operator_names' => $operator_names,
						'operator_field' => $operator_field) //data format			
				); 
				
				if($data){ $message.="Option Inserted.";}else{$error.="error occur";}
			}else{
				
				$error.="Option name is already created with this name.";
			}
    }
	 global $wpdb;
     $table_name = $wpdb->prefix . "auction";
	$auction_names = $wpdb->get_results("SELECT DISTINCT name FROM $table_name where type = 'Auction'");
	
	$category_table_name = $wpdb->prefix . "auction_category";
	$cat_names = $wpdb->get_results("SELECT * FROM $category_table_name WHERE cat_type='Category Options'");
    ?>    
    <div class="wrap">
        <h2>Add New Option</h2>
        <?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
        <?php if (isset($error)): ?><div class="error"><p><?php echo $error; ?></p></div><?php endif; ?>
        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" >
             <table class='wp-list-table widefat fixed'>
				<tr>
                    <th class="ss-th-width">Auction Name</th>
                    <td>				    
						<select class="form-control ss-field-width" name="auction_name" id="auction_name" onchange="get_auction_names();" required>
						<option value=''>----</option>
						<?php							
							foreach($auction_names as $name){
								if($reference == $name->name){
								echo "<option value='".$name->name."' selected>".$name->name."</option>";	
								}else{
								echo "<option value='".$name->name."'>".$name->name."</option>";
								}							
							}
						?>
						</select>
						</td>
                </tr>
                <tr>
                    <th class="ss-th-width">Option Name</th>
                    <td><input type="text" name="option_name" value="" class="ss-field-width" required/></td>
                </tr>
				<tr class="reference">
                    <th class="ss-th-width">Field Type</th>
                   <td>				    
					<select class="form-control ss-field-width" name="field_type" id="field_type" >
						<option value="0">No Field Type</option>	
						<option value="1">Text field</option>	
						<option value="2">Number field</option>	
						<option value="3">Radio Button</option>	
						<option value="4">Date picker</option>
						<option value="5">Date and Time Picker</option>	
						<option value="6">Dropdown</option>	
						<option value="7">Multiple select</option>	
						<option value="8">Number & Dropdown Field</option>	
						<option value="9">Num | Dropdown | Num | Dropdown | Num | Dropdown | Num</option>	
						<option value="10">Dropdown | Text Field</option>	
						<option value="11">Dropdown | Text field  with Operators</option>	
						<option value="12">Variable equals value</option>	
				  </select>
					</td>
                </tr>
				<tr class="operator_names" style="display:none;">
                    <th class="ss-th-width">Operators</th>
                   <td>				    
					<select class="form-control ss-field-width" name="operator_names" id="operator_names" required> 
						<option value="0">Select Operator</option>	
						<option value="1">==</option>	
						<option value="2">></option>	
						<option value="3"><</option>	
						<option value="4">>=</option>	
						<option value="5"><=</option>						
				    </select>
				   </td>
                </tr>
				<tr class="operator_field" style="display:none;">
					<th class="ss-th-width">Value</th>
					<td><input type="text" name="operator_field" value="" class="ss-field-width" /></td>
				</tr>
				<tr class="checkedradio appenddata" style="display:none;">
                    <th class="ss-th-width">Add Value</th>
                    <td><input type="text" name="field_value[]" value="" class="ss-field-width" /><div class="add_field_button">Add More Values</div></td>
                </tr>				
				<!-- For Multiple Dropdown Option  -->				
				<tr class="multidrop multidropappend" style="display:none;">
                    <th class="ss-th-width">Add Value Dropdown 1</th>
                    <td><input type="text" name="field_value1[]" data-id="field_value1" value="" class="ss-field-width"  /><div class="add_multi_field_button">Add More Values</div></td>
                </tr>
				<tr class="multidrop multidropappend" style="display:none;">
                    <th class="ss-th-width">Add Value Dropdown 2</th>
                    <td><input type="text" name="field_value2[]" data-id="field_value2"  value="" class="ss-field-width" /><div class="add_multi_field_button">Add More Values</div></td>
                </tr>
				<tr class="multidrop multidropappend" style="display:none;">
                    <th class="ss-th-width">Add Value Dropdown 3</th>
                    <td><input type="text" name="field_value3[]" data-id="field_value3" value="" class="ss-field-width" /><div class="add_multi_field_button">Add More Values</div></td>
                </tr>				
				<!-- For Multiple Dropdown Option  -->
			 
				
				<tr class="hideMutiple" style="display:none;">
                    <th class="ss-th-width">Multiple or Not</th>
                    <td><input type="checkbox" name="multiple" value="1" /></td>
                </tr>
				<tr>
                    <th class="ss-th-width">Description</th>
                    <td><textarea type="textarea"  class="form-control ss-field-width" name="description" required></textarea></td>
                </tr>
				<tr>
                    <th class="ss-th-width">Category</th>
                     <td>				    
					<select class="form-control ss-field-width" name="category" id="category_list" required>
		
					</select>
					</td>
                </tr>
            </table>		
			<br>
            <input type='submit' name="insert_options" value='Save' class='button'>
            <div class='button'><a href="<?php echo admin_url('admin.php?page=auction_option_list') ?>">Cancel</a></div>
        </form>
    </div>
	<script>
	function get_auction_names() { // Call to ajax function
    var auction_name = jQuery('#auction_name :selected').val();
	 jQuery.ajax({
		url: "<?php echo admin_url( 'admin-ajax.php' );?>", // this is the object instantiated in wp_localize_script function
		dataType: "html",
		type: 'POST',
		data:{ 
		  action: 'get_category_list', // this is the function in your functions.php that will be triggered
		  auction_name: auction_name
		},
		success: function( data ){
		  //Do something with the result from server
		 jQuery("#category_list").html(data);
		}
	  });
	}	
	</script>
    <?php
}

function get_category_list_callback() {
  $auction_name =  $_POST['auction_name'];
  global $wpdb;
  $category_table_name = $wpdb->prefix . "auction_category";
  $cat_names = $wpdb->get_results("SELECT cat_name FROM $category_table_name WHERE auction_name = '$auction_name' and cat_type ='Category Options'");  
	$result = (int)$wpdb->num_rows;	
	if($result >= 1){			
		echo "<option value=''>----</option>";
		foreach($cat_names as $cname){
		echo  "<option value='".$cname->cat_name."'>".$cname->cat_name."</option>";									
		}				 
	}else{
		echo  "<option value=''>No category</option>";	
	}
	
  die(); // this is required to return a proper result
}
add_action( 'wp_ajax_get_category_list', 'get_category_list_callback' );

function create_action () {	
//insert
    if (isset($_POST['insert_actions'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . "action";
	 	$auction_name = $_POST['auction_name'];
		$action_name = $_POST['action_name'];
		$category = $_POST['category'];
		$link = $_POST['link'];
		$example = $_POST['example'];
		//print_r($add_value);
		if(isset($_POST['multiple'])){
		$multiple = $_POST['multiple'];
		}else{
		$multiple = '0';	
		}
		if(isset($advance_feature)){
		$advance_feature = $advance_feature;
		}else{
		$advance_feature = '0';
		}
		$names_check = $wpdb->get_results("SELECT action_name FROM $table_name where action_name = '$action_name'");		  
		  $result = (int)$wpdb->num_rows;
			if($result == 0){			
			   $data = $wpdb->insert(
						$table_name, //table
						array('auction_name' => $auction_name, 'action_name' => $action_name, 'category' => $category, 'link' => $link, 'example' => $example) //data format			
				); 
				
				if($data){ $message.="Action Inserted.";}else{$error.="error occur";}
			}else{
				
				$error.="Action name is already created with this name.";
			}
    }
	 global $wpdb;
     $table_name = $wpdb->prefix . "auction";
	$auction_names = $wpdb->get_results("SELECT DISTINCT name FROM $table_name WHERE type = 'Auction'");
	
	$category_table_name = $wpdb->prefix . "auction_category";
	$cat_names = $wpdb->get_results("SELECT * FROM $category_table_name WHERE cat_type = 'Category Actions'");
    ?>    
    <div class="wrap">
        <h2>Add New Action</h2>
        <?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
        <?php if (isset($error)): ?><div class="error"><p><?php echo $error; ?></p></div><?php endif; ?>
        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
             <table class='wp-list-table widefat fixed'>
			 <tr>
                    <th class="ss-th-width">Auction Name</th>
                    <td>				    
						<select class="form-control ss-field-width" name="auction_name" id="auction_name" onchange="get_auction_names();" required>
						<option value=''>----</option>
						<?php							
							foreach($auction_names as $name){
								if($reference == $name->name){
								echo "<option value='".$name->name."' selected>".$name->name."</option>";	
								}else{
								echo "<option value='".$name->name."'>".$name->name."</option>";
								}							
							}
						?>
						</select>
						</td>
                </tr>
                <tr>
                    <th class="ss-th-width">Action Name</th>
                    <td><input type="text" name="action_name" value="" class="ss-field-width" required/></td>
                </tr>
				<tr>
                    <th class="ss-th-width">Category</th>
                     <td>				    
					<select class="form-control ss-field-width" name="category" id="category_list" required>
						
					</select>
					</td>
                </tr>
				<tr>
                    <th class="ss-th-width">Link</th>
                    <td><input type="text" name="link"  class="ss-field-width" value="" id="link" required/></td>
                </tr>
				<tr>
                    <th class="ss-th-width">Example</th>
                    <td><input type="text" name="example"  class="ss-field-width" value="" id="example" required/></td>
                </tr>
				
            </table>		
			<br>
            <input type='submit' name="insert_actions" value='Save' class='button'>
            <div class='button'><a href="<?php echo admin_url('admin.php?page=action_list') ?>">Cancel</a></div>
        </form>
    </div>
	<script>
	function get_auction_names() { // Call to ajax function
    var auction_name = jQuery('#auction_name :selected').val();
	 jQuery.ajax({
		url: "<?php echo admin_url( 'admin-ajax.php' );?>", // this is the object instantiated in wp_localize_script function
		dataType: "html",
		type: 'POST',
		data:{ 
		  action: 'get_category_list_action', // this is the function in your functions.php that will be triggered
		  auction_name: auction_name
		},
		success: function( data ){
		  //Do something with the result from server
		 jQuery("#category_list").html(data);
		}
	  });
	}	
	</script>
    <?php
}
function get_category_list_callback_action() {
  $auction_name =  $_POST['auction_name'];
  global $wpdb;
  $category_table_name = $wpdb->prefix . "auction_category";
  $cat_names = $wpdb->get_results("SELECT cat_name FROM $category_table_name WHERE auction_name = '$auction_name' and cat_type ='Category Actions'");  
	$result = (int)$wpdb->num_rows;	
	if($result >= 1){			
		echo "<option value=''>----</option>";
		foreach($cat_names as $cname){
		echo  "<option value='".$cname->cat_name."'>".$cname->cat_name."</option>";									
		}				 
	}else{
		echo  "<option value=''>No category</option>";	
	}
	
  die(); // this is required to return a proper result
}
add_action( 'wp_ajax_get_category_list_action', 'get_category_list_callback_action' );