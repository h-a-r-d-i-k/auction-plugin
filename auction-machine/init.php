<?php
/*
Plugin Name: Auction Machine
Description:
Version: 1
Author: Amit Batra
Author URI: http://vreadev.com
*/
// function to create the DB / Options / Defaults					
function ss_options_install() {

    global $wpdb;

    $table_name = $wpdb->prefix . "auctions";
/*     $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
            `id` int(11) CHARACTER SET utf8 NOT NULL,
            `name` varchar(550) CHARACTER SET utf8 NOT NULL,
            `image` varchar(550) CHARACTER SET utf8 NOT NULL,
            `auction_type` varchar(550) CHARACTER SET utf8 NOT NULL,
            `reference` varchar(550) CHARACTER SET utf8 NOT NULL,
            PRIMARY KEY (`id`)
          ) $charset_collate; ";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql); */
}

// run the install scripts upon plugin activation
register_activation_hook(__FILE__, 'ss_options_install');

//menu items
add_action('admin_menu','auction_modifymenu');
function auction_modifymenu() {	
	add_menu_page('Auctions', //page title
	'Auctions', //menu title
	'manage_options', //capabilities
	'auction_list', //menu slug
	'auction_list' //function
	);	
	
	add_submenu_page('auction_list', //parent slug
	'Add New Auction', //page title
	'Add New Auction', //menu title
	'manage_options', //capability
	'auction_create', //menu slug
	'auction_create'); //function	
		
	add_submenu_page('auction_list', //parent slug
	'Auction option', //page title
	'Auction options', //menu title
	'manage_options', //capability
	'auction_option_list', //menu slug
	'auction_option_list'); //function
	
	add_submenu_page('auction_list', //parent slug
	'Add New Option', //page title
	'Add New Option', //menu title
	'manage_options', //capability
	'auction_option', //menu slug
	'auction_option'); //function	
	
	add_submenu_page('auction_list', //parent slug
	'Action List', //page title
	'Action List', //menu title
	'manage_options', //capability
	'action_list', //menu slug
	'action_list'); //function
	
	add_submenu_page('auction_list', //parent slug
	'Add New Action', //page title
	'Add New Action', //menu title
	'manage_options', //capability
	'create_action', //menu slug
	'create_action'); //function	
		
	 add_submenu_page('auction_list', //parent slug
	'Category List', //page title
	'Category', //menu title
	'manage_options', //capability
	'category_list', //menu slug
	'category_list'); //function
	
	//this submenu is HIDDEN, however, we need to add it anyways
	add_submenu_page(null, //parent slug
	'Category', //page title
	'Category', //menu title
	'manage_options', //capability
	'category_create', //menu slug
	'category_create'); //function
	
	add_submenu_page(null, //parent slug
	'Update Auction', //page title
	'Update', //menu title
	'manage_options', //capability
	'auction_update', //menu slug
	'auction_update'); //function
	
	add_submenu_page(null, //parent slug
	'Update Auction', //page title
	'Update', //menu title
	'manage_options', //capability
	'action_update', //menu slug
	'action_update'); //function
	
	add_submenu_page(null, //parent slug
	'Update Option', //page title
	'Update', //menu title
	'manage_options', //capability
	'option_update', //menu slug
	'option_update'); //function
	
	add_submenu_page(null, //parent slug
	'Update Option', //page title
	'Update', //menu title
	'manage_options', //capability
	'category_update', //menu slug
	'category_update'); //function
}

function auction_scripts_load_cdn(){
    wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js', array(), null, false );
    wp_register_script( 'datatables', 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', array(), null, false );
    wp_register_script( 'auctionscript', plugins_url( '/js/auctionscript.js', __FILE__ ), array( 'jquery' ) );
	wp_register_style( 'style-admin', plugins_url( '/auction-machine/style-admin.css'));
	wp_register_style( 'datatables-css',  'https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css');
    wp_enqueue_script( 'auctionscript' );
    wp_enqueue_script( 'datatables' );
    wp_enqueue_style( 'style-admin' );
    wp_enqueue_style( 'datatables-css' );
}
add_action( 'admin_enqueue_scripts', 'auction_scripts_load_cdn' );

define('ROOTDIR', plugin_dir_path(__FILE__));
require_once(ROOTDIR . 'auction-list.php');
require_once(ROOTDIR . 'auction-create.php');
require_once(ROOTDIR . 'auction-update.php');
require_once(ROOTDIR . 'auction-option.php');
require_once(ROOTDIR . 'form-shortcode.php');
