<?php
/**
 * Plugin Name: Pincode based product price woocommerce - WordPress Plugin
 * Plugin URI: https://www.mlmtrees.com/product/pincode-based-product-price-pro/
 * Description: This plugin is use for change the price of product according to user postcode.
 * Version: 2.0
 * Author: LetsCMS Pvt. Ltd
 * Author URI: http://www.letscms.com
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define PBDPW_PLUGIN_FILE.
if ( ! defined( 'PBDPW_PLUGIN_FILE' ) ) {
	define( 'PBDPW_PLUGIN_FILE', __FILE__ );
}


// Include the main WooCommerce class.
if ( ! class_exists( 'Pbdpw' ) ) {
	include_once dirname( __FILE__ ) . '/includes/class-pbdpw.php';
}

function PBDPW() {
	return Pbdpw::instance();
}
// Global for backwards compatibility.
$GLOBALS['PBDPW'] = PBDPW();