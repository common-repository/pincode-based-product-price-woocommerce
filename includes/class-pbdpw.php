<?php

/**

 * Pbdpw setup

 *

 * @package Pbdpw

 * @since   1.0.0

 */



if (!defined('ABSPATH')) {

    exit;
}



/**

 * Main Pbdpw Class.

 *

 * @class Pbdpw

 */

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

final class Pbdpw
{



    public $version = '1.0.0';



    protected static $_instance = null;



    public $session = null;





    public $query = null;



    public $product_factory = null;



    public $countries = null;





    public $integrations = null;





    public $cart = null;





    public $customer = null;



    public $order_factory = null;



    public $structured_data = null;



    public $deprecated_hook_handlers = array();



    public static function instance()
    {



        if (is_plugin_active('woocommerce/woocommerce.php')) {



            if (is_null(self::$_instance)) {

                self::$_instance = new self();
            }

            return self::$_instance;
        } else {





            echo '<div class="notice notice-warning is-dismissible">';

            echo __("Before activating 'Pincode based product price woocommerce', Please activate  WooCommerce plugin.", "pbdpw");

            echo '</div>';





            exit;
        }
    }





    public function __construct()
    {

        $this->define_constants();

        $this->includes();

        $this->init_hooks();



        do_action('pbdpw_loaded');
    }



    private function init_hooks()
    {



        register_activation_hook(PBDPW_PLUGIN_FILE, array('PBDPW_Install', 'install'));

        add_action('init', array($this, 'init'), 0);

        add_action('init', array($this, 'lid_add_admin_menu'));

        register_deactivation_hook(PBDPW_PLUGIN_FILE, array('PBDPW_Install', 'deactivate'));



        register_uninstall_hook(PBDPW_PLUGIN_FILE, 'uninstall');
    }





    public function init()
    {



        if (!session_id()) {

            session_start();
        }

        add_action('wp_footer', array($this, 'pbdpw_sweetalert_popup_main'));

        $this->load_plugin_textdomain();

        if (is_admin()) {

            // add_action( 'admin_enqueue_scripts', array($this,'custom_pbdpw_admin_style' ));

        }

        wp_enqueue_script('PBDPW_swal_js', PBDPW()->plugin_url() . '/assets/js/sweetalert2.all.min.js', array('jquery'), '', true);
        // wp_enqueue_style( 'PBDPW_swal_css', 'https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css', array(''), '' );
        wp_enqueue_style('PBDPW_styles', PBDPW()->plugin_url() . '/assets/css/front.css', array(), '');
    }

    function pbdpw_sweetalert_popup_main()
    {
        $ajaxurl = admin_url('admin-ajax.php');
        echo '<script>
		var ajaxurl="' . $ajaxurl . '";
		window.addEventListener("load", function () {  jQuery.ajax({type: "POST",url: ajaxurl,data: {"action":"bpdpw_check_pincode_session",},dataType: "json",success: function (data) { console.log(data.status);if (data.status == false) {  Swal.fire({
			title: "Enter you pincode",
			html: `<div id="licence_form"><h5 class="let-alert let-alert-danger"> </h5><input type="text" id="pincode" class="swal2-input let-p-4" placeholder="Enter Your pincode"></div>`,
			confirmButtonText: "Save",
			allowOutsideClick: false,
			focusConfirm: false,
			preConfirm: () => {
				const pincode = Swal.getPopup().querySelector("#pincode").value
				if (!pincode) {
					Swal.showValidationMessage(`Please enter pincode`)
				}
				return { pincode: pincode }
			}
		}).then((result) => {
			
			jQuery.ajax({

				type: "POST",
	  
				url:woocommerce_params.ajax_url,
	  
				data:{
	  
				"action":"bpdpw_save_pincode",
	  
				"pincode":result.value.pincode,
	  
			  },
	  
			  success: function(data){
	  
			   location.reload(true);
	  
			  }
	  
			});
	  
		});} else {}}});});
		
		</script>';
    }

    private function define_constants()
    {

        $upload_dir = wp_upload_dir(null, false);

        $this->define('PBDPW_ABSPATH', dirname(PBDPW_PLUGIN_FILE) . '/');

        $this->define('PBDPW_PLUGIN_BASENAME', plugin_basename(PBDPW_PLUGIN_FILE));

        $this->define('PBDPW_VERSION', $this->version);
    }



    public function lid_add_admin_menu()
    {

        add_action('admin_menu', array($this, 'admin_menu'), 9);
    }



    public function admin_menu()

    {

        global $menu;



        $icon = PBDPW()->plugin_url() . '/assets/images/zip-code.png';

        add_menu_page(__('Pincode price', 'PBDPW'), __('Pincode price', 'PBDPW'), 'manage_options', 'PBDPW-user-report', array($this, 'settings_page_init'), $icon, 100);
    }



    public function settings_page_init()
    {

        global $wpdb;

        include_once PBDPW_ABSPATH . 'includes/admin/class-pbdpw-import.php';
    }

    private function define($name, $value)
    {

        if (!defined($name)) {

            define($name, $value);
        }
    }





    private function is_request($type)
    {

        switch ($type) {

            case 'admin':

                return is_admin();

            case 'ajax':

                return defined('DOING_AJAX');

            case 'cron':

                return defined('DOING_CRON');

            case 'frontend':

                return (!is_admin() || defined('DOING_AJAX')) && !defined('DOING_CRON') && !defined('REST_REQUEST');
        }
    }





    public function includes()
    {



        include_once PBDPW_ABSPATH . 'includes/class-pbdpw-install.php';

        include_once PBDPW_ABSPATH . 'includes/admin/class-pbdpw-admin.php';

        include_once PBDPW_ABSPATH . 'includes/class-pbdpw-hooks-function.php';

        include_once PBDPW_ABSPATH . 'includes/class-pbdpw-product-admin.php';

        include_once PBDPW_ABSPATH . 'includes/class-pbdpw-hooks.php';

        if ($this->is_request('admin')) {

            include_once PBDPW_ABSPATH . 'includes/admin/class-pbdpw-admin.php';
        }
    }

    // plugin text Domain

    public function load_plugin_textdomain()
    {

        $locale = is_admin() && function_exists('get_user_locale') ? get_user_locale() : get_locale();

        $locale = apply_filters('plugin_locale', $locale, 'pbdpw');

        unload_textdomain('pbdpw');



        load_textdomain('pbdpw', PBDPW_ABSPATH . '/lang/' . $locale . '.mo');

        load_plugin_textdomain('pbdpw', false, plugin_basename(dirname(PBDPW_PLUGIN_FILE)) . '/lang');
    }



    // plugin url

    public function plugin_url()
    {

        return untrailingslashit(plugins_url('/', PBDPW_PLUGIN_FILE));
    }



    // plugin path

    public function plugin_path()
    {

        return untrailingslashit(plugin_dir_path(PBDPW_PLUGIN_FILE));
    }
}
