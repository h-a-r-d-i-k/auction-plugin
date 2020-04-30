<?php
include_once '../../../wp-config.php';
include_once '../../../wp-load.php';
include_once '../../../wp-includes/wp-db.php'; 

global $wpdb;
if(isset($_POST['name'])){
$table_name = $wpdb->prefix . "auction";
$am_name = $_POST['name'];
$rows = $wpdb->get_results("SELECT id from $table_name where reference ='$am_name'");
$result = (int)$wpdb->num_rows;
echo $result;
}
?>