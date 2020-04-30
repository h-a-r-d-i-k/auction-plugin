<?php
function auction_create() {	
    //insert
    if (isset($_POST['insert'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . "auction";
		
		 $name = $_POST["name"];
		 $type_auction = $_POST["auction"];
		 if($_POST["reference"]){
		 $reference = $_POST["reference"];
		 }else{
			  $reference = "";
		 }
		 // $advance_button = $_POST["advance_button"];
		  $result_operator = $_POST["result_operator"];
	

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
		  if(empty($imageurl)){
			 $imageurl =  WP_PLUGIN_URL."/auction-machine/image/default-image.png"; 
		  }
		  
		  $names_check = $wpdb->get_results("SELECT name FROM $table_name where name = '$name'");		  
		  $result = (int)$wpdb->num_rows;		  
			if($result == 0){				
				$data = $wpdb->insert(
                $table_name, //table
                array('name' => $name, 'image' => $imageurl, 'type' => $type_auction, 'reference' => $reference, 'result_operator' => $result_operator)); //data
                //array('%s', '%s', '%s', '%s') //data format			
			//);
	
        $message.= "Auction inserted";
		}else{	
		$error.= "Auction name is already added in your list.";
		}

    }
	 global $wpdb;
     $table_name = $wpdb->prefix . "auction";
	$auction_names = $wpdb->get_results("SELECT DISTINCT name FROM $table_name WHERE type = 'Auction'");

    ?>
    <div class="wrap">
        <h2>Add New Auction</h2>
        <?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p><a href="<?php echo admin_url('admin.php?page=auction_list') ?>">&laquo; Back to auction list</a></div><?php endif; ?>
        <?php if (isset($error)): ?><div class="error"><p><?php echo $error; ?></p></div><?php endif; ?>
        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
             <table class='wp-list-table widefat fixed'>
                <tr>
                    <th class="ss-th-width">Name</th>
                    <td><input type="text" name="name" class="ss-field-width" required/></td>
                </tr>
				<tr>
                    <th class="ss-th-width">Image</th>
                    <td><input type="file" name="image" class="ss-field-width" /></td>
                </tr>
				<tr>
                    <th class="ss-th-width">Type</th>
					  <td>
						  <label><input type="radio" class="auction_type" name="auction" value="Auction" required>Auction </label>
						  <label><input type="radio" class="auction_type" name="auction" value="Reference" required>Reference</label>
					  </td>                   
                </tr>
				<tr class="reference" style="display:none;">
                    <th class="ss-th-width">Reference</th>
                   <td>				    
					<select class="form-control ss-field-width" name="reference" id="reference">
						<option value="">------</option>	
					<?php
						foreach($auction_names as $name){							
							echo "<option value='".$name->name."'>".$name->name."</option>";					
						}
					?>
					
					  </select>
					</td>
                </tr>
				<tr>
                    <th class="ss-th-width">Result Operator</th>
					  <td>
						  <label><input type="text" class="ss-field-width" name="result_operator" required></label>
						 
					  </td>                   
                </tr>
						
            </table>
			<br>
            <input type='submit' name="insert" value='Save' class='button'>
        </form>
    </div>	

    <?php
}

function category_create() {	
    //insert
    if (isset($_POST['insert_cat'])) {
	    global $wpdb;
        $category_table_name = $wpdb->prefix . "auction_category";		
		$cat_name = $_POST['cat_name'];				
		$cat_type = $_POST['cat_type'];		
		$advance_feature = $_POST['advance_feature'];
		$auction_name = $_POST['auction_name'];	
		if(isset($_POST['advance_feature'])){
		$advance_feature = $_POST['advance_feature'];
		}else{
		$advance_feature = '0';	
		}		
		$data = $wpdb->insert(
		$category_table_name, //table
		array('cat_name' => $cat_name, 'cat_type' => $cat_type, 'auction_name' => $auction_name, 'advance_feature' => $advance_feature)); //data format	
		
		if($data){
		$message.= "Category inserted";
			}else{	
		$error.= "Error Occur.";
		}
		
    }
	 global $wpdb;
     $table_name = $wpdb->prefix . "auction";
	$auction_names = $wpdb->get_results("SELECT name FROM $table_name WHERE type='Auction'");
    ?>
    <div class="wrap">
        <h2>Add New Category</h2>
        <?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p>
		<a href="<?php echo admin_url('admin.php?page=category_list') ?>">&laquo; Back to options list</a>
		</div><?php endif; ?>
        <?php if (isset($error)): ?><div class="error"><p><?php echo $error; ?></p></div><?php endif; ?>
        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">
             <table class='wp-list-table widefat fixed'>
                <tr>
                    <th class="ss-th-width">Category Name</th>
                    <td><input type="text" name="cat_name" value="<?php echo $name; ?>" class="ss-field-width" required /></td>
                </tr>
				<tr>
                    <th class="ss-th-width">Category Type</th>
                    <td>				    
					<select class="form-control ss-field-width" name="cat_type" id="cat_type">
					<option value="Category Options">Category Options</option>
					<option value="Category Actions">Category Actions</option>
					</select>
					</td>
                </tr>
				<tr id="auction_field">
                    <th class="ss-th-width">Auction Name</th>
                    <td>				    
						<select class="form-control ss-field-width" name="auction_name" id="auction_name" required>
						<option value=''>----</option>
						<?php							
							foreach($auction_names as $name){								
								echo "<option value='".$name->name."'>".$name->name."</option>";
							}
						?>
						</select>
						</td>
                </tr>
				<tr>
                    <th class="ss-th-width">Advanced</th>
                    <td><input type="checkbox" name="advance_feature" value="1" id="advance_feature" /></td>
                </tr>
            </table>
			<br>
            <input type='submit' name="insert_cat" value='Save' class='button'>
        </form>
    </div>	

    <?php

}