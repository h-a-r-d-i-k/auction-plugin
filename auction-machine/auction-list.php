<?php

function auction_list() {
    ?> 
    <div class="wrap">
        <h2>Auctions</h2>
        <div class="tablenav top">
            <div class="alignleft actions">
                <a href="<?php echo admin_url('admin.php?page=auction_create'); ?>">Add New</a>
            </div>
            <br class="clear">
        </div>
        <?php
        global $wpdb;
        $table_name = $wpdb->prefix . "auction";
        $rows = $wpdb->get_results("SELECT * from $table_name");
        ?>
        <table id="auction_list" class="display" style="width:100%">
			<thead>
				<tr>
					<th>Image</th>
					<th>Name</th>
					<th>Type</th>
					<th>Reference</th>					
					<th>Update</th>
				</tr>
			</thead>
			<tbody>
            <?php foreach ($rows as $row) {
			//$advanced = $row->advanced;
			//$advanced = ($advanced == '1') ?  "Yes" : "No" ;
			?>
                <tr>
                    <td class="manage-column ss-list-width"><img width="40" src="<?php echo $row->image; ?>"></td>
                    <td class="manage-column ss-list-width"><?php echo $row->name; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->type; ?></td>                   
                    <td class="manage-column ss-list-width"><?php echo $row->reference; ?></td>				
                    <td><a href="<?php echo admin_url('admin.php?page=auction_update&id=' . $row->id); ?>">Update</a></td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
				<th>Image</th>
				<th>Name</th>
				<th>Type</th>
				<th>Reference</th>			
				<th>Update</th>
            </tr>
        </tfoot>
    </table>
    </div>
    <?php
}

function action_list () {
    ?> 
    <div class="wrap">
        <h2>Actions</h2>
        <div class="tablenav top">
            <div class="alignleft actions">
                <a href="<?php echo admin_url('admin.php?page=create_action') ?>">Add New</a>
            </div>
            <br class="clear">
        </div>
        <?php
        global $wpdb;
        $table_name = $wpdb->prefix . "action";
        $cat_table = $wpdb->prefix . "auction_category";
        $rows = $wpdb->get_results("SELECT $table_name.*, $cat_table.advance_feature FROM $table_name INNER JOIN $cat_table ON $cat_table.cat_name=$table_name.category");
        ?>
        <table id="auction_list" class="display" style="width:100%">
			<thead>
				<tr>
					<th>Auction Name</th>
					<th>Action Name</th>
					<th>Category</th>
					<th>Advance</th>
					<th>Link</th>
					<th>Example</th>
					<th>Update</th>
				</tr>
			</thead>
			<tbody>
            <?php foreach ($rows as $row) { 
			?>
                <tr>
                    <td class="manage-column ss-list-width"><?php echo $row->auction_name; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->action_name; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->category; ?></td>
                    <td class="manage-column ss-list-width"><?php if($row->advance_feature == 0){ echo "No"; } else { echo "Yes";} ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->link; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->example; ?></td>
                    <td><a href="<?php echo admin_url('admin.php?page=action_update&id=' . $row->id); ?>">Update</a></td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
				<th>Auction Name</th>
				<th>Action Name</th>
				<th>Category</th>
				<th>Advance</th>
				<th>Link</th>
				<th>Example</th>
				<th>Update</th>
            </tr>
        </tfoot>
    </table>
    </div>
    <?php
}

function auction_option_list() {
    ?> 
    <div class="wrap">
        <h2>Option List</h2>
        <div class="tablenav top">
            <div class="alignleft actions">
                <a href="<?php echo admin_url('admin.php?page=auction_option'); ?>">Add New</a>
            </div>
            <br class="clear">
        </div>
        <?php
        global $wpdb;
        $options_table_name = $wpdb->prefix . "auction_options";
        $category_table_name = $wpdb->prefix . "auction_category";
        //$rows = $wpdb->get_results("SELECT * from $options_table_name");
		$rows = $wpdb->get_results("SELECT distinct $options_table_name.*, $category_table_name.advance_feature FROM $options_table_name INNER JOIN $category_table_name ON $category_table_name.cat_name=$options_table_name.category");
        ?>
        <table id="auction_list" class="display" style="width:100%">
			<thead>
				<tr>
					<th>Auction Name</th>
					<th>Option Name</th>
					<th>Fieldtype</th>
					<th>Multiple</th>					
					<th>Category</th>					
					<th>Advance</th>					
					<th>Update</th>
				</tr>
			</thead>
			<tbody>
            <?php foreach ($rows as $row) { 	
			//$advanced_option = ($advanced_option == 1) ?  "Yes" : "No" ;
			
			$field_type = $row->field_type;
			if($field_type == 0){
				$field_text = "No Field Type";
			}else if($field_type == 1){
				$field_text = "Text field";
			}else if($field_type == 2){
				$field_text = "Number field";
			}else if($field_type == 3){
				$field_text = "Radio Button";
			}else if($field_type == 4){
				$field_text = "Date picker";
			}else if($field_type == 5){
				$field_text = "Date and Time Picker";
			}else if($field_type == 6){
				$field_text = "Dropdown";
			}else if($field_type == 7){
				$field_text = "Multiple select";
			}else if($field_type == 8){
				$field_text = "Num & Dropdown Field";
			}else if($field_type == 9){
				$field_text = "Num | Dropdown | Num | Dropdown";
			}else if($field_type == 10){
				$field_text = "Dropdown | Text field";
			}else if($field_type == 11){
				$field_text = "Dropdown | Text field → var = val";
			}else if($field_type == 12){
				$field_text = "Text field - !var";
			}
			
			?>
                <tr>
                    <td class="manage-column ss-list-width"><?php echo $row->auction_name; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->auction_option_name; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $field_text; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->multiple; ?></td>                   
                    <td class="manage-column ss-list-width"><?php echo $row->category; ?></td>                   
                    <td class="manage-column ss-list-width"><?php if($row->advance_feature == 0){ echo "No"; } else { echo "Yes"; } ?></td>                   
                    <td><a href="<?php echo admin_url('admin.php?page=option_update&id=' . $row->id); ?>">Update</a></td>
                </tr>
            <?php } ?>
			</tbody>
			<tfoot>
				<tr>
					<th>Auction Name</th>
					<th>Option Name</th>
					<th>Fieldtype</th>
					<th>Multiple</th>					
					<th>Category</th>					
					<th>Advance</th>					
					<th>Update</th>
				</tr>
			</tfoot>
    </table>
    </div>
    <?php
}

function category_list() {
    ?> 
    <div class="wrap">
        <h2>Category List</h2>
        <div class="tablenav top">
            <div class="alignleft actions">
                <a href="<?php echo admin_url('admin.php?page=category_create'); ?>">Add New</a>
            </div>
            <br class="clear">
        </div>
        <?php
        global $wpdb;
        $category_table_name = $wpdb->prefix . "auction_category";
        $rows = $wpdb->get_results("SELECT * from $category_table_name");
        ?>
        <table id="auction_list" class="display category_list" style="width:100%">
			<thead>
				<tr>
					<th>Category ID</th>
					<th>Category Name</th>
					<th>Category Type</th>
					<th>Auction Name</th>
					<th>Advance</th>
					<th>Update</th>
				</tr>
			</thead>
			<tbody>
            <?php foreach ($rows as $row) { ?> 
                <tr>            
                    <td class="manage-column ss-list-width"><?php echo $row->id; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->cat_name; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->cat_type; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->auction_name; ?></td>
                    <td class="manage-column ss-list-width"><?php if($row->advance_feature == 1) { echo "Yes"; } else { echo "No"; } ?></td>
                    <td><a href="<?php echo admin_url('admin.php?page=category_update&id=' . $row->id); ?>">Update</a></td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
			<tr>
				<th>Category ID</th>
				<th>Category Name</th>
				<th>Category Type</th>
				<th>Auction Name</th>
				<th>Advance</th>
				<th>Update</th>
			</tr>
        </tfoot>
    </table>
    </div>
    <?php
}