<?php


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * PBDPW_Admin class.
 */
class PBDPW_Admin {

	public function __construct() {
		add_action( 'init', array( $this, 'includes' ) );
		
	}

	public function includes() {
		 include_once dirname( __FILE__ ) . '/class-pbdpw-admin-assets.php';
	}


}
return new PBDPW_Admin();