<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'PBDPW_Admin_Assets', false ) ) :

	
	class PBDPW_Admin_Assets {

		/**
		 * Hook in tabs.
		 */
		public function __construct() {

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		}

	/**
	 * Enqueue styles.
	 */
	public function admin_styles() {
		global $wp_scripts;
		wp_enqueue_style( 'PBDPW_admin_styles', PBDPW()->plugin_url() . '/assets/css/admin/admin.css', array(), PBDPW_VERSION );
	}


	/**
	 * Enqueue scripts.
	 */
	public function admin_scripts() {
		global $wp_query, $post;
		// Register scripts.
		wp_enqueue_script( 'PBDPW_admin_jquery', PBDPW()->plugin_url() . '/assets/js/admin/admin.js', array(), PBDPW_VERSION );
	}
			
}
endif;

return new PBDPW_Admin_Assets();