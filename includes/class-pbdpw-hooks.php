<?php

add_action('bpdpw_pincode_product_price_meta_box','bpdpw_pincode_product_price_meta_box_function',10,1);

add_action('bpdpw_pincode_product_price_bulk_meta_box','bpdpw_pincode_product_price_bulk_meta_box_function',10,1);



add_action('wp_ajax_bpdpw_data_save','bpdpw_save_function');

add_action('wp_ajax_nopriv_bpdpw_data_save','bpdpw_save_function');


add_action('wp_ajax_bpdpw_bulk_data_save','bpdpw_bulk_save_function');

add_action('wp_ajax_nopriv_bpdpw_bulk_data_save','bpdpw_bulk_save_function');



add_action('wp_ajax_bpdpw_delete_data','bpdpw_delete_data_function');

add_action('wp_ajax_nopriv_bpdpw_delete_data','bpdpw_delete_data_function');



add_action( 'woocommerce_before_calculate_totals', 'bpdpw_price_based_on_pincode_recalculate_price' );



add_action( 'wp_enqueue_styles', 'pbdpw_function_css' );



add_action('wp_ajax_bpdpw_save_pincode','bpdpw_save_pincoden_function');

add_action('wp_ajax_nopriv_bpdpw_save_pincode','bpdpw_save_pincoden_function');



add_action('wp_ajax_bpdpw_update_pincode','update_pincode_function');

add_action('wp_ajax_nopriv_bpdpw_update_pincode','update_pincode_function');



add_action('wp_ajax_pbdpw_import_list','pbdpw_import_list_product_save');

add_action('wp_ajax_nopriv_pbdpw_import_list','pbdpw_import_list_product_save');



add_filter('woocommerce_product_get_price', 'bpdpw_pincode_based_price', 10, 2);

add_filter('woocommerce_get_price_html', 'bpdpw_pincode_based_price_html', 10, 2);



add_action('wp_footer','bpdpw_pincode_popup_script',10000);



add_action('woocommerce_checkout_process', 'wc_billing_pincode_error');

add_action('bpdpw_import_csv_file', 'bpdpw_import_csv_file');

add_action('wp_ajax_bpdpw_check_pincode_session','bpdpw_check_pincode_session_function');
add_action('wp_ajax_nopriv_bpdpw_check_pincode_session','bpdpw_check_pincode_session_function');

?>