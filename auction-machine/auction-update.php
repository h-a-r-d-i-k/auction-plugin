<?php
function auction_update() {
    global $wpdb;
    $table_name = $wpdb->prefix . "auction";
    $id = $_GET["id"];
    $name = $_POST["name"];
    //$advance_button = $_POST["advance_button"];
    $result_operator = $_POST["result_operator"];
	$type_auction = $_POST["auction"];	
	$old_image = $_POST["old_image"];	
	if($_POST["reference"]){$reference = $_POST["reference"];}else{$reference = "";}
	if($type_auction == "Auction"){$reference = "";}
	if($_FILES['image']['name'] != ''){	 
		$uploadedfile = $_FILES['image'];
		$upload_overrides = array( 'test_form' => false );
		$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
		$imageurl = "";
		if ( $movefile && ! isset( $movefile['error'] ) ) {				
		   $imageurl = $movefile['url'];			 
		} else {			
		   echo $movefile['error'];
		}
	}
	if(empty($imageurl)){$imagepath = $old_image;}else{$imagepath = $imageurl;}
//update
    if (isset($_POST['update'])) {     
		
		$wpdb->query("UPDATE $table_name SET name='$name',image='$imagepath',type='$type_auction',reference='$reference',result_operator='$result_operator' WHERE id=$id");		
    }
//delete
    else if (isset($_POST['delete_auctions'])) {
        $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id = %s", $id));
    } else {//selecting value to update	
        $auctions = $wpdb->get_results($wpdb->prepare("SELECT * from $table_name where id=%s", $id));
        foreach ($auctions as $s) {
            $am_name = $s->name;
            $nameqq = $s->name;
            $image = $s->image;
            $type = $s->type;
			$reference = $s->reference;
			//$advanced = $s->advanced;
			$result_operator = $s->result_operator;			
			//$advanced = ($advanced == '1') ?  "checked" : "" ;
			
			$parent_auctions = $wpdb->get_results($wpdb->prepare("SELECT name from $table_name where reference=%s", $am_name));
			$count_parent = $wpdb->num_rows;
        }
    }		
   
	$auction_names = $wpdb->get_results("SELECT DISTINCT name FROM $table_name");
    ?>
    <div class="wrap">
        <h2>Auctions</h2>

        <?php if ($_POST['delete_auctions']) { ?>
            <div class="updated"><p>Auction deleted</p></div>
            <a href="<?php echo admin_url('admin.php?page=auction_list') ?>">&laquo; Back to auctions list</a>

        <?php } else if ($_POST['update']) { ?>
            <div class="updated"><p>Auction updated</p></div>
            <a href="<?php echo admin_url('admin.php?page=auction_list') ?>">&laquo; Back to auctions list</a>

        <?php } else { ?>
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
                <table class='wp-list-table widefat fixed'>
                    <tr><th>Name</th><td><input type="text" name="name" value="<?php echo $am_name; ?>"/></td></tr>
                    <tr><th>Image</th>
						<td><input type="file" name="image" class="ss-field-width" /></td>
						<td><img width="40" src="<?php echo $image; ?>"></td>
					</tr>
                    <tr><th>Type</th>
						<td>
						  <label><input type="radio" class="auction_type" name="auction" value="Auction" <?php echo ($type == 'Auction') ?  "checked" : "" ;  ?>/>Auction </label>
						  <label><input type="radio" class="auction_type" name="auction" value="Reference" <?php echo ($type == 'Reference') ?  "checked" : "" ;  ?> <?php echo ($count_parent >= 1) ?  "disabled='disabled'" : "" ;  ?> />Reference</label>
					  </td>  
					 </tr>
					 <?php
					 $reference_check = ($reference == '') ?  "none" : "table-row" ;
					 $required_check = ($reference == '') ?  "" : "required" ;
					 ?>
					<tr class="reference" style="display:<?php echo $reference_check;?>">
						<th>Reference</th>
					   <td>				    
						<select class="form-control ss-field-width" name="reference" id="reference" <?php echo $required_check;?>>
						<?php
							echo "<option value=''>------</option>";	
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
                    <th class="ss-th-width">Result Operator</th>
					  <td>
						  <label><input type="text" class="ss-field-width" name="result_operator" value="<?php echo $result_operator;?>" required></label>
						 
					  </td>                   
					</tr>
									
                </table>
                <input type='hidden' name="old_image" value='<?php echo $image;?>'>
                <input type='submit' name="update" value='Save' class='button'> &nbsp;&nbsp;
                <input type='submit' name="delete_auctions" value='Delete' class='button' onclick="return delete_auction_confirm('<?php echo $am_name; ?>')">
            </form>
        <?php } ?>
    </div>
<script>
function delete_auction_confirm(auction){		
		var result = confirm("Want to delete?");
	 	var check = '';	
		if (result) {						
			 jQuery.ajax({
				url: "<?php echo admin_url( 'admin-ajax.php' );?>", // this is the object instantiated in wp_localize_script function
				type: 'POST',
				async:false,
				dataType: "text",
				data:{ 
				  action: 'delete_auction_from_list', // this is the function in your functions.php that will be triggered
				  auction_name: auction
				},
				success: function(data){			
				var a = parseInt(data);						
					if(a > 0){
					check = 4;
					}			
				}	
			  });
			if(check == 4){
				alert("We can't delete this machine because we have machines under them.")
				return false;	
			}
		}else{
			return false;
		} 		
		
	}
</script>	
    <?php
}

function delete_auction_callback() {
   $auction_name =  $_POST['auction_name'];
	global $wpdb;
	$auction_table_name = $wpdb->prefix . "auction";
	$cat_names = $wpdb->get_results("SELECT name FROM $auction_table_name WHERE reference = '$auction_name'");  
	echo $result = (int)$wpdb->num_rows;		
	
  die(); // this is required to return a proper result
}
add_action( 'wp_ajax_delete_auction_from_list', 'delete_auction_callback' );

function action_update() {
	global $wpdb;
	$action_table_name = $wpdb->prefix . "action";
	$id = $_GET["id"];	
	//update
	if (isset($_POST['action_update'])) {     
	$auction_name = $_POST['auction_name'];
	$action_name = $_POST['action_name'];
	$category = $_POST['category'];
	$link = $_POST['link'];
	$example = $_POST['example'];
			
	 $query = $wpdb->query("UPDATE $action_table_name SET auction_name='$auction_name',action_name='$action_name',category='$category',link='$link',example = '$example' WHERE id=$id");
	if($query){ $message.="Action updated successfully."; }

	}
	//delete
	else if (isset($_POST['action_delete'])) {
	$wpdb->query($wpdb->prepare("DELETE FROM $action_table_name WHERE id = %s", $id));
	} else {//selecting value to update	
	$auctions = $wpdb->get_results($wpdb->prepare("SELECT * from $action_table_name where id=%s", $id));
	foreach ($auctions as $s) {
		$id = $s->id;
		$auctionname = $s->auction_name;
		$action_name = $s->action_name;
		$category = $s->category;
		$link = $s->link;
		$example = $s->example;
	}
	}		
	//global $wpdb;
	$auction_table_name = $wpdb->prefix . "auction";
	$auction_names = $wpdb->get_results("SELECT DISTINCT name FROM $auction_table_name where type = 'Auction'");

	$category_table_name = $wpdb->prefix . "auction_category";
	$cat_names = $wpdb->get_results("SELECT * FROM $category_table_name WHERE cat_type='Category Actions' AND auction_name='$auctionname'");

	?> 
	<div class="wrap">
	<h2>Actions</h2>

	<?php if ($_POST['action_delete']) { ?>
		<div class="updated"><p>Action deleted</p></div>
		<a href="<?php echo admin_url('admin.php?page=action_list') ?>">&laquo; Back to actions list</a>

	<?php } else if ($_POST['action_update']) { ?>
	   
		 <?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
		<?php if (isset($error)): ?><div class="error"><p><?php echo $error; ?></p></div><?php endif; ?>
		<a href="<?php echo admin_url('admin.php?page=action_list') ?>">&laquo; Back to actions list</a>

	<?php } else { ?>
		
		<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
			  <table class='wp-list-table widefat fixed'>
		 <tr>
				<th class="ss-th-width">Auction Name</th>
				<td>
				<select class="form-control ss-field-width" name="auction_name" onchange="get_auction_names();" id="auction_name">
					<?php							
						foreach($auction_names as $aname){
							if($auctionname == $aname->name){
							echo "<option value='".$aname->name."' selected>".$aname->name."</option>";	
							}else{
							echo "<option value='".$aname->name."'>".$aname->name."</option>";
							}							
						}
					?>
					
				</select>
				</td>
			</tr>
			<tr>
				<th class="ss-th-width">Action Name</th>
				<td><input type="text" name="action_name" value="<?php echo $action_name; ?>" class="ss-field-width" /></td>
			</tr>
			<tr>
				<th class="ss-th-width">Category</th>
				 <td>

				<select class="form-control ss-field-width" name="category" id="category_list"  required>
				<?php
					foreach($cat_names as $cname){
						if($category == $cname->cat_name){
							echo "<option value='".$cname->cat_name."' selected>".$cname->cat_name."</option>";	
							}else{
							echo "<option value='".$cname->cat_name."'>".$cname->cat_name."</option>";
						}							
					}
				?>
				</select>
				</td>
			</tr>
			<tr>
				<th class="ss-th-width">Link</th>
				<td><input type="text" name="link" value="<?php echo $link;?>" class="ss-field-width" /></td>
			</tr>
			<tr>
				<th class="ss-th-width">Example</th>
				<td><input type="text" name="example" value="<?php echo $example;?>" class="ss-field-width" /></td>
			</tr>
		</table>
		<br>			
			<input type='submit' name="action_update" value='Save' class='button'> &nbsp;&nbsp;
			<input type='submit' name="action_delete" value='Delete' class='button' onclick="return confirm('Want to delete?')">
		</form>
	<?php } ?>
	</div>
	<script>
	function get_auction_names() { // Call to ajax function
    var auction_name = jQuery('#auction_name :selected').val();
	 jQuery.ajax({
		url: "<?php echo admin_url( 'admin-ajax.php' );?>", // this is the object instantiated in wp_localize_script function
		dataType: "html",
		type: 'POST',
		data:{ 
		  action: 'update_category_list_action', // this is the function in your functions.php that will be triggered
		  auction_name: auction_name
		},
		success: function( data ){	
		 jQuery("#category_list").html(data);
		}
	  });
	}	
	</script>
	<?php
}
function update_category_list_callback_action() {
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
add_action( 'wp_ajax_update_category_list_action', 'update_category_list_callback_action' );

function option_update() {
    global $wpdb;
    $options_table_name = $wpdb->prefix . "auction_options";
    $id = $_GET["id"];
    if (isset($_POST['option_update'])) {     
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
			
		 $query = $wpdb->query("UPDATE $options_table_name SET auction_name='$auction_name',auction_option_name='$option_name',field_type='$field_type',field_value='$field_value',dropdown_value_1='$dropdown_value_1',dropdown_value_2='$dropdown_value_2',dropdown_value_3='$dropdown_value_3',multiple='$multiple',description='$description',category='$category',operator_names = '$operator_names',operator_field = '$operator_field' WHERE id=$id");
		if($query){ $message.="Option updated successfully."; }
		
    }
//delete
    else if (isset($_POST['option_delete'])) {
        $wpdb->query($wpdb->prepare("DELETE FROM $options_table_name WHERE id = %s", $id));
    } else {//selecting value to update	
        $auctions = $wpdb->get_results($wpdb->prepare("SELECT * from $options_table_name where id=%s", $id));
        foreach ($auctions as $s) {
			$id = $s->id;
            $auctionname = $s->auction_name;
            $auction_option_name = $s->auction_option_name;
            $field_value = json_decode($s->field_value, true);
            $dropdown_value_1 = json_decode($s->dropdown_value_1, true);
            $dropdown_value_2 = json_decode($s->dropdown_value_2, true);
            $dropdown_value_3 = json_decode($s->dropdown_value_3, true);
		    $field_type = $s->field_type;
            $multiple = $s->multiple;
			$description = $s->description;
			$categoryname = $s->category;
			$operator_names = $s->operator_names;
			$operator_field = $s->operator_field;
		
	      }
    }		
	//global $wpdb;
    $auction_table_name = $wpdb->prefix . "auction";
	$auction_names = $wpdb->get_results("SELECT DISTINCT name FROM $auction_table_name where type = 'Auction'");	
	$category_table_name = $wpdb->prefix . "auction_category";
	$cat_names = $wpdb->get_results("SELECT * FROM $category_table_name WHERE cat_type='Category Options' AND auction_name = '$auctionname'");
    ?> 
    <div class="wrap">
        <h2>Options</h2>

        <?php if ($_POST['option_delete']) { ?>
            <div class="updated"><p>Option deleted</p></div>
            <a href="<?php echo admin_url('admin.php?page=auction_option_list') ?>">&laquo; Back to options list</a>

        <?php } else if ($_POST['option_update']) { ?>
           
			 <?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
			<?php if (isset($error)): ?><div class="error"><p><?php echo $error; ?></p></div><?php endif; ?>
            <a href="<?php echo admin_url('admin.php?page=auction_option_list') ?>">&laquo; Back to options list</a>

        <?php } else { ?>
			
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" novalidate>
                  <table class='wp-list-table widefat fixed'>
			 <tr>
                    <th class="ss-th-width">Auction Name</th>
                    <td>
					<select class="form-control ss-field-width" name="auction_name" onchange="get_auction_names();" id="auction_name">
						<?php							
							foreach($auction_names as $aname){
								if($auctionname == $aname->name){
								echo "<option value='".$aname->name."' selected>".$aname->name."</option>";	
								}else{
								echo "<option value='".$aname->name."'>".$aname->name."</option>";
								}							
							}
						?>
						
					</select>
					</td>
                </tr>
                <tr>
                    <th class="ss-th-width">Option Name</th>
                    <td><input type="text" name="option_name" value="<?php echo $auction_option_name;?>" class="ss-field-width" /></td>
                </tr>
				<tr class="reference">
                    <th class="ss-th-width">Field Type</th>
                   <td>				    
					<select class="form-control ss-field-width" name="field_type" id="field_type">
						<option value="0" <?php if($field_type == 0){echo "selected";} ?>>No Field Type</option>	
						<option value="1" <?php if($field_type == 1){echo "selected";} ?>>Text field</option>	
						<option value="2" <?php if($field_type == 2){echo "selected";} ?>>Number field</option>	
						<option value="3" <?php if($field_type == 3){echo "selected";} ?>>Radio Button</option>	
						<option value="4" <?php if($field_type == 4){echo "selected";} ?>>Date picker</option>	
						<option value="5" <?php if($field_type == 5){echo "selected";} ?>>Date and Time Picker</option>	
						<option value="6" <?php if($field_type == 6){echo "selected";} ?>>Dropdown</option>	
						<option value="7" <?php if($field_type == 7){echo "selected";} ?>>Multiple select</option>	
						<option value="8" <?php if($field_type == 8){echo "selected";} ?>>Number & Dropdown Field</option>	
						<option value="9" <?php if($field_type == 9){echo "selected";} ?>>Num | Dropdown | Num | Dropdown | Num | Dropdown | Num</option>	
						<option value="10" <?php if($field_type == 10){echo "selected";} ?>>Dropdown | Text field</option>	
						<option value="11" <?php if($field_type == 11){echo "selected";} ?>>Dropdown | Text field → var = val</option>	
						<option value="12" <?php if($field_type == 12){echo "selected";} ?>>Text field - !var</option>	
				  </select>
					</td>
                </tr>			
				
				<?php 
				if(isset($field_value) && !empty($field_value)){ 			
				?>
				<tr class="checkedradio appenddata" style="display:none;">
                    <th class="ss-th-width">Add Value</th>
                    <td><input type="text" name="field_value[]" value="<?php  echo $field_value[0]; ?>" class="ss-field-width" required /> <div class="add_field_button">Add More Values</div></td>
                </tr>	
				
				<?php
				$removed_first_index = array_splice($field_value,0,1);	
				foreach($field_value as $fieldvalue) {
				$style = ($fieldvalue == '') ?  "none" : "table-row" ;
				?>
				<tr class="checkedradio appenddata" style="display:<?php echo $style;?>">
                    <th class="ss-th-width">Add Value</th>
                    <td><input type="text" name="field_value[]" value="<?php  echo $fieldvalue; ?>" class="ss-field-width"/><a href="JavaScript:void(0);" class="remove_field">Remove</a></td>
                </tr>
				<?php } }	?>				
				
				<!-- For Multiple Dropdown Option  -->				
				<tr class="multidrop multidropappend" style="display:none;">
                    <th class="ss-th-width">Add Value Dropdown 1</th>
                    <td><input type="text" name="field_value1[]" data-id="field_value1" value="<?php  echo $dropdown_value_1[0]; ?>" class="ss-field-width" required/><div class="add_multi_field_button">Add More Values</div></td>
                </tr>
				<?php			
				$removed_first_index = array_splice($dropdown_value_1,0,1);							
				foreach($dropdown_value_1 as $dropdown_1) {	
				$style = ($dropdown_1 == '') ?  "none" : "table-row" ;
				?>
				<tr class="multidrop multidropappend" style="display:<?php echo $style;?>">
                    <th class="ss-th-width">Add Value</th>
                    <td><input type="text" name="field_value1[]" data-id="field_value1" value="<?php  echo $dropdown_1; ?>" class="ss-field-width"/><a href="JavaScript:void(0);" class="remove_field">Remove</a></td>
                </tr>
				<?php }	?>	
				
				<tr class="multidrop multidropappend" style="display:none;">
                    <th class="ss-th-width">Add Value Dropdown 2</th>
                    <td><input type="text" name="field_value2[]" data-id="field_value2" value="<?php  echo $dropdown_value_2[0]; ?>" class="ss-field-width" required/><div class="add_multi_field_button">Add More Values</div></td>
                </tr>
				<?php				
				$removed_first_index = array_splice($dropdown_value_2,0,1);			
				foreach($dropdown_value_2 as $dropdown_2) {	
				$style = ($dropdown_2 == '') ?  "none" : "table-row" ;
				?>
				<tr class="multidrop multidropappend" style="display:<?php echo $style;?>">
                    <th class="ss-th-width">Add Value</th>
                    <td><input type="text" name="field_value2[]" data-id="field_value2"  value="<?php  echo $dropdown_2; ?>" class="ss-field-width"/><a href="JavaScript:void(0);" class="remove_field">Remove</a></td>
                </tr>
				<?php } ?>	
				
				<tr class="multidrop multidropappend" style="display:none;">
                    <th class="ss-th-width">Add Value Dropdown 3</th>
                    <td><input type="text" name="field_value3[]" data-id="field_value3" value="<?php  echo $dropdown_value_3[0]; ?>" class="ss-field-width" required/><div class="add_multi_field_button">Add More Values</div></td>
                </tr>
				<?php			
				$removed_first_index = array_splice($dropdown_value_3,0,1);				
				foreach($dropdown_value_3 as $dropdown_3) {	
				$style = ($dropdown_3 == '') ?  "none" : "table-row" ;
				?>
				<tr class="multidrop multidropappend" style="display:<?php echo $style;?>">
                    <th class="ss-th-width">Add Value</th>
                    <td><input type="text"name="field_value3[]" data-id="field_value3" value="<?php  echo $dropdown_3; ?>" class="ss-field-width"/><a href="JavaScript:void(0);" class="remove_field">Remove</a></td>
                </tr>
				<?php }	?>				
				
				<!-- For Multiple Dropdown Option  -->				
				
				<tr class="operator_names">
                    <th class="ss-th-width">Operators</th>
                   <td>				    
					<select class="form-control ss-field-width" name="operator_names" id="operator_names">
						<option value="0" <?php if($operator_names == 0){echo "selected";} ?>>Select Operator</option>	
						<option value="1" <?php if($operator_names == 1){echo "selected";} ?>>==</option>	
						<option value="2" <?php if($operator_names == 2){echo "selected";} ?>>></option>	
						<option value="3" <?php if($operator_names == 3){echo "selected";} ?>><</option>	
						<option value="4" <?php if($operator_names == 4){echo "selected";} ?>>>=</option>	
						<option value="5" <?php if($operator_names == 5){echo "selected";} ?>><=</option>						
				  </select>
					</td>
                </tr>
				<tr class="operator_field">
					<th class="ss-th-width">Value</th>
					<td><input type="text" name="operator_field" value="<?php echo $operator_field; ?>" class="ss-field-width" /></td>
				</tr>
				<tr class="field_type hideMutiple">
                    <th class="ss-th-width">Multiple or Not</th>
			        <td><input type="checkbox" name="multiple" value="1" <?php if($multiple == 1){echo "checked";} ?>/></td>
                </tr>
				<tr>
                    <th class="ss-th-width">Description</th>
                    <td><textarea type="textarea"  class="form-control ss-field-width" name="description"><?php echo $description; ?></textarea></td>
                </tr>
				<tr>
                    <th class="ss-th-width">Category</th>
                     <td>
		
					<select class="form-control ss-field-width" name="category" id="category_list"  required>
					<?php
						foreach($cat_names as $cname){
							if($categoryname == $cname->cat_name){
								echo "<option value='".$cname->cat_name."' selected>".$cname->cat_name."</option>";	
								}else{
								echo "<option value='".$cname->cat_name."'>".$cname->cat_name."</option>";
							}							
						}
					?>
					</select>
					</td>
                </tr>
            </table>
			<br>			
                <input type='submit' name="option_update" value='Save' class='button'> &nbsp;&nbsp;
                <input type='submit' name="option_delete" value='Delete' class='button' onclick="return confirm('Want to delete?')">
            </form>
        <?php } ?>
    </div>
	<script>
	function get_auction_names() { // Call to ajax function		
    var auction_name = jQuery('#auction_name :selected').val();
	 jQuery.ajax({
		url: "<?php echo admin_url( 'admin-ajax.php' );?>", // this is the object instantiated in wp_localize_script function
		dataType: "html",
		type: 'POST',
		data:{ 
		  action: 'update_category_list', // this is the function in your functions.php that will be triggered
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
function update_category_list_callback() {
	$auction_name =  $_POST['auction_name'];
	global $wpdb;
	$category_table_name = $wpdb->prefix . "auction_category";
	$cat_names = $wpdb->get_results("SELECT cat_name FROM $category_table_name WHERE auction_name = '$auction_name' and cat_type ='Category Options'");
	$result = (int)$wpdb->num_rows;	
	if($result >= 1){			
		echo "<option value=''>----</option>";
		foreach($cat_names as $cname){
		echo  "<option value='".$cname->cat_name."'>".$cname->cat_name."</option>";									
		}}
		else{
			echo  "<option value=''>No category</option>";	
		}
  die(); 
}
add_action( 'wp_ajax_update_category_list', 'update_category_list_callback' );

function category_update() {
    global $wpdb;
    $category_table_name = $wpdb->prefix . "auction_category";
    $id = $_GET["id"];	
//update
    if (isset($_POST['category_update'])) {     
		$cat_name = $_POST['cat_name'];		
		$cat_type = $_POST['cat_type'];		
		$advance_feature = $_POST['advance_feature'];		
		$auction_name = $_POST['auction_name'];		
		
		$names_check = $wpdb->get_results("SELECT cat_name FROM $category_table_name where cat_name = '$cat_name' AND id !=$id");		  
		  $result = (int)$wpdb->num_rows;
			if($result == 0){		
		 $query = $wpdb->query("UPDATE $category_table_name SET cat_name='$cat_name', cat_type='$cat_type', auction_name='$auction_name', advance_feature='$advance_feature' WHERE id=$id");
				if($query){ $message.="Category updated successfully."; }
			}else{
					$error.="Sorry! you cannot create the category with existing name.";
			}		 
    }
//delete
    else if (isset($_POST['category_delete'])) {
        $wpdb->query($wpdb->prepare("DELETE FROM $category_table_name WHERE id = %s", $id));
    } else {
		//selecting value to update	
     $category = $wpdb->get_results($wpdb->prepare("SELECT * from $category_table_name where id=%s", $id));
	 $category_name = $category[0]->cat_name;
	 $category_type = $category[0]->cat_type;
	 $advance_feature = $category[0]->advance_feature;
	 $auctionname = $category[0]->auction_name;
    }
   
    $table_name = $wpdb->prefix . "auction";
	$auction_names = $wpdb->get_results("SELECT DISTINCT name FROM $table_name where type = 'Auction'");

    ?> 
    <div class="wrap">
        <h2>Category</h2>

        <?php if ($_POST['category_delete']) { ?>
            <div class="updated"><p>Category deleted</p></div>
            <a href="<?php echo admin_url('admin.php?page=category_list') ?>">&laquo; Back to Category list</a>

        <?php } else if ($_POST['category_update']) { ?>
           
			 <?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
			<?php if (isset($error)): ?><div class="error"><p><?php echo $error; ?></p></div><?php endif; ?>
            <a href="<?php echo admin_url('admin.php?page=category_list') ?>">&laquo; Back to Category list</a>

        <?php } else { ?>
			
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" novalidate>
                 <table class='wp-list-table widefat fixed'>
                <tr>
                    <th class="ss-th-width">Category Name</th>
                    <td><input type="text" name="cat_name" value="<?php echo $category_name; ?>" class="ss-field-width" required/></td>
                </tr>
				<tr>
                    <th class="ss-th-width">Category Type</th>
                     <td>				    
					<select class="form-control ss-field-width" name="cat_type" id="cat_type">
						<option value="Category Options" <?php if($category_type == "Category Options"){ echo "selected";} ?>>Category Options</option>
						<option value="Category Actions" <?php if($category_type == "Category Actions"){echo "selected";} ?>>Category Actions</option>
					</select>
					</td>
                </tr>
				<tr>
                    <th class="ss-th-width">Auction Name</th>
                    <td>				    
						<select class="form-control ss-field-width" name="auction_name" id="auction_name" required>	
							<option value=' '>----</option>						
						<?php							
							foreach($auction_names as $name){
								if($auctionname == $name->name){
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
                    <th class="ss-th-width">Advanced</th>
                    <td><input type="checkbox" name="advance_feature"  value="1" id="advance_feature" <?php if($advance_feature == 1){echo "checked";} ?> /></td>
                </tr>
				<?php
				
				 $advance_feature = ($advance_feature == '0') ?  "none" : "table-row" ;
				 ?>
            </table>
			<br>			
                <input type='submit' name="category_update" value='Save' class='button'> &nbsp;&nbsp;
                <input type='submit' name="category_delete" value='Delete' class='button' onclick="return delete_category_confirm('<?php echo $category_name;?>')">
            </form>
        <?php } ?>
    </div>
	<script>
	function delete_category_confirm(cat_name){ 
    var result = confirm("Want to delete?");
	 	var check = '';	
		if (result) {						
			 jQuery.ajax({
				url: "<?php echo admin_url( 'admin-ajax.php' );?>", // this is the object instantiated in wp_localize_script function
				type: 'POST',
				async:false,
				dataType: "text",
				data:{ 
				  action: 'delete_category_from_list', // this is the function in your functions.php that will be triggered
				  cat_name: cat_name
				},
				success: function(data){			
				var a = parseInt(data);						
					if(a > 0){
					check = 3;
					}			
				}	
			  });
			if(check == 3){
				alert("We can't delete this Category because we have Options under them.")
				return false;	
			}
		}else{
			return false;
		}		
		
	}	
	</script>
	
    <?php
}

function delete_category_callback() {
   $cat_name =  $_POST['cat_name'];
	global $wpdb;
  $options_table_name = $wpdb->prefix . "auction_options";
  $cat_names = $wpdb->get_results("SELECT category FROM $options_table_name WHERE category = '$cat_name'");  
	$result = (int)$wpdb->num_rows;	
	echo $result;
	
  die(); // this is required to return a proper result
}
add_action( 'wp_ajax_delete_category_from_list', 'delete_category_callback' );
