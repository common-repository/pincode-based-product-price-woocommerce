<?php

/**

 * BPDPW Meta Boxes

 *

 * Sets up the write panels used by products and orders (custom post types).

 *

 * @author      LETSCMS Pvt Ltd

 * @category    Admin

 * @package     BPDPW/Admin/Meta Boxes

 * @version     2.1.0

 */



if ( ! defined( 'ABSPATH' ) ) {

	exit; // Exit if accessed directly

}





class BPDPW_Admin_Meta_Boxes {



	/**

	 * Is meta boxes saved once?

	 *

	 * @var boolean

	 */

	private static $saved_meta_boxes = false;



	/**

	 * Meta box error messages.

	 *

	 * @var array

	 */

	public static $meta_box_errors = array();



	/**

	 * Constructor.

	 */

	public function __construct() { 

		

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );

		add_action( 'save_post', array( $this, 'save_meta_boxes' ), 1, 2 );



		// Error handling (for showing errors from meta boxes on next page load).

		add_action( 'admin_notices', array( $this, 'output_errors' ) );

		add_action( 'shutdown', array( $this, 'save_errors' ) );

	}



	/**

	 * Add an error message.

	 *

	 * @param string $text

	 */

	public static function add_error( $text ) {

		self::$meta_box_errors[] = $text;

	}



	/**

	 * Save errors to an option.

	 */

	public function save_errors() {

		update_option( 'woocommerce_meta_box_errors', self::$meta_box_errors );

	}



	/**

	 * Show any stored error messages.

	 */

	public function output_errors() {

		

	}



	/**

	 * Add WC Meta boxes.

	 */

	public function add_meta_boxes() {

		$screen    = get_current_screen();

		$screen_id = $screen ? $screen->id : '';



		// Coupons.

		add_meta_box( 'bpdpw-product-pincode-price', __( 'Product Pincode Price', 'bpdpw' ), array($this,'pincode_product_price_metta_box'), 'product', 'normal');
		add_meta_box( 'bpdpw-product-pincode-price-bulk', __( 'Product Pincode Price Bulk', 'bpdpw' ), array($this,'pincode_product_price_bulk_metta_box'), 'product', 'normal');

	}



	public function pincode_product_price_metta_box($post)

	{  if($post->post_status=='publish'){

			do_action('bpdpw_pincode_product_price_meta_box',$post);

		} else{

			echo 'Current Product is not Published. Please publish Product to use this feature';

		}

	}



	public function pincode_product_price_bulk_metta_box($post)

	{  if($post->post_status=='publish'){

			do_action('bpdpw_pincode_product_price_bulk_meta_box',$post);

		} else{

			echo 'Current Product is not Published. Please publish Product to use this feature';

		}

	}



	public function save_meta_boxes(){



	}

	

}



new BPDPW_Admin_Meta_Boxes();