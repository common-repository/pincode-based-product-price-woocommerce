<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

/**
 * PBDPW_Install Class.
 */

class PBDPW_Install {

	private static $background_updater;

	/**
	 * Hook in tabs.
	 */
	public static function init() { 		 
	}
	/**
	 * Install BMP.
	 */
	public static function install() { 
		if ( ! is_blog_installed() ) {
			return;
		}

		self::create_tables(); 
		
		
	}

	private static function create_tables(){
		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$wpdb->hide_errors();
		$get_tables=self::get_schema();
		foreach($get_tables as $get_table){
			dbDelta($get_table);	
		}
	}

	private static function get_schema() { 
		global $wpdb;
		$tables = array();
		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}
	
		$tables[] = "CREATE TABLE {$wpdb->prefix}pbdpw_product_price_pincode (
			id BIGINT(20) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
		  product_id BIGINT(20) NOT NULL,
		  pincode VARCHAR(6) NOT NULL ,
		  price VARCHAR(128) NOT NULL ,
		  quantity INT(15) NOT NULL ,
		  stock INT(1) NOT NULL
		) $collate;"; 

		return $tables;
	}
	
	public static function get_table(){
	global $wpdb;

	$tables = array(
		"{$wpdb->prefix}pbdpw_product_price_pincode",
	);

	$tables = apply_filters( 'pbdpw_install_get_tables', $tables );

	return $tables;
}

	/**
	*	Deactivate function 
	*/
	public static function deactivate(){
	  global $wpdb;
	  $tables = $wpdb->prefix . 'pbdpw_product_price_pincode';
	    $sql = "DROP TABLE IF EXISTS $tables";
	    $wpdb->query($sql);
	}

	/**
	 * Drop WooCommerce tables.
	 *
	 * @return void
	 */
	public static function drop_tables() {
		global $wpdb;

		$tables = self::get_tables();

		foreach ( $tables as $table ) {
			$wpdb->query( "DROP TABLE IF EXISTS {$table}" ); 
		}
	}	
	
}

PBDPW_Install::init();