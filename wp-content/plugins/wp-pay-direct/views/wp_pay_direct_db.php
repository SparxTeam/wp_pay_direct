<?php 
/**
 * 	WP Pay Direct Class
 * 	
 * 	This class will handle all the DataBase operations of the plugin
 * 
 */

Class WP_Pay_Direct_DB {
	
	// Constructor of the class
	function __construct() {
		
		// anything here would execute on instanciation the class
		
	}
	
	function create_wppd_db_table(){
		
		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		global $wpdb;
		global $charset_collate;
		
		$this->wppd_register_payment_log_table();
		
		$sql_create_table = "CREATE TABLE IF NOT EXISTS {$wpdb->wppd_payment_log} (
								`id` int(6) NOT NULL AUTO_INCREMENT,
  								`txnid` varchar(20) NOT NULL,
  								`payment_amount` decimal(7,2) NOT NULL,
  								`payment_status` varchar(25) NOT NULL,
  								`itemid` varchar(25) NOT NULL,
  								`createdtime` datetime NOT NULL,
  								`email` varchar(50) NOT NULL,
  								`hash` varchar(100),
  								`custom` LONGTEXT NULL,
  								PRIMARY KEY (`id`)
							) $charset_collate; ";
		
		dbDelta( $sql_create_table ); 
		
		
	}
	
	function wppd_register_payment_log_table() {
		
		global $wpdb;
		$wpdb->wppd_payment_log = "{$wpdb->prefix}wppd_payment_log";
	}
	
}

	global $wp_pay_direct_db;
	$wp_pay_direct_db	= 	new WP_Pay_Direct_DB();