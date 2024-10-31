<?php

function bpdpw_price_based_on_pincode_recalculate_price($cart_object)
{

    global $wpdb;

    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    if ($cart_object->get_cart()) {

        foreach ($cart_object->get_cart() as $hash => $value) {

            $product_id = $value['product_id'];

            $pincode = $_SESSION['pincode_session_for_popup'];

            $newprice = $value['data']->get_regular_price();

            if (!empty($pincode)) {

                $price = $wpdb->get_var("SELECT price FROM {$wpdb->prefix}pbdpw_product_price_pincode WHERE pincode='" . $pincode . "' AND product_id='" . $product_id . "'");

                if (!empty($price)) {

                    $newprice = $price;
                }
            }

            $value['data']->set_price($newprice);
        }
    }
}

function bpdpw_pincode_product_price_bulk_meta_box_function($post)
{

    global $wpdb; ?>

    <div class="col-md-12">

        <form method="POST" action="">

            <table class="form-table" style="display:block; overflow-x:auto">

                <thead>

                    <tr class="tb-head ">

                        <th class="tb-head"><?php echo __('Pincode Range start', 'bpdpw'); ?></th>

                        <th class="tb-head"><?php echo __('Pincode Range end', 'bpdpw'); ?></th>

                        <th class="tb-head"><?php echo __('Price', 'bpdpw'); ?></th>

                        <!-- <th class="tb-head"><?php echo __('Quantity', 'bpdpw'); ?></th> -->

                        <!-- <th class="tb-head"><?php echo __('Stock', 'bpdpw'); ?></th> -->

                        <th class="tb-head"><?php echo __('action', 'bpdpw'); ?></th>

                    </tr>

                </thead>

                <tbody class="" id="">
                    <tr>
                        <input type="hidden" name="post_url" id="post_bulk_url" value="<?php echo admin_url('admin-ajax.php'); ?>">

                        <td class="tb-body"><input type="number" name="pin_start" id="pin_start" value=""></td>

                        <td class="tb-body"><input type="number" name="pin_end" id="pin_end" value=""></td>

                        <td class="tb-body"><input type="text" name="bulk_price" id="bulk_price" value=""></td>

                        <td class="tb-body" style="display:none"><input type="number" name="bulk_qty" id="bulk_qty" value="1"></td>

                        <td class="tb-body text-center" style="display:none"><select name='stock' id='bulk_stock'>
                                <option value='yes' selected><?php echo __('Yes', 'bpdpw'); ?></option>
                                <option value='no'><?php echo __('No', 'bpdpw'); ?></option>
                            </select>
                        </td>

                        <td><button class="text-center" type="button" onclick="bpdpw_save_bulk()" style="margin-left: 45px;">Save</button></td>

                    </tr>
                    <tr>
                        <td class="error_msg" colspan="4"></td>
                    </tr>
                </tbody>
            </table>
            <script>
                function bpdpw_save_bulk() {

                    var bulk_pincode_start = jQuery('#pin_start').val();

                    var bulk_pincode_end = jQuery('#pin_end').val();

                    var bulk_price = jQuery('#bulk_price').val();

                    var bulk_quantity = jQuery('#bulk_qty').val();

                    var bulk_stock = jQuery('#bulk_stock').val();

                    var link = jQuery('#post_bulk_url').val();

                    var product_id = jQuery('#post_ID').val();

                    console.log(parseInt(bulk_pincode_start) >= parseInt(bulk_pincode_end));
                    if (parseInt(bulk_pincode_start) > parseInt(bulk_pincode_end)) {
                        jQuery('.error_msg').html('<span class="alert alert-success bg-danger" style="background:#fa5246;padding:10px 25px 10px 25px;color:white;">Please enter valid Pincode range</span>');
                        Swal.fire({
                            title: "Bulk Pincode",
                            text: "Something went wrong!",
                            icon: "error",
                            button: "ok",
                        });
                        return false;
                    }
                    if (bulk_pincode_start == '' || bulk_pincode_end == '' || bulk_price == '' || bulk_quantity == '' || bulk_stock == '') {
                        jQuery('.error_msg').html('<span class="alert alert-success bg-danger" style="background:#fa5246;padding:10px 25px 10px 25px;color:white;">Please fill all details</span>');
                        Swal.fire({
                            title: "Bulk Pincode",
                            text: "Something went wrong!",
                            icon: "error",
                            button: "ok",
                        });
                        return false;
                    }

                    jQuery.ajax({

                        type: "POST",

                        url: link,

                        data: {

                            'pincode_start': bulk_pincode_start,

                            'pincode_end': bulk_pincode_end,

                            'action': 'bpdpw_bulk_data_save',

                            'price': bulk_price,

                            'quantity': bulk_quantity,

                            'stock': bulk_stock,

                            'product_id': product_id,

                        },

                        success: function(data) {


                            Swal.fire({
                                title: "Bulk Pincode",
                                text: "All pincode saved successfully!",
                                icon: "success",
                                button: "ok",
                            });

                            jQuery('#bpdpw-product-pincode-price .inside').html(data);



                        }

                    });

                }
            </script>
    </div>

<?php }

function bpdpw_pincode_product_price_meta_box_function($post)
{

    global $wpdb;

    $num_row = 0;

    $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}pbdpw_product_price_pincode WHERE product_id='" . $post->ID . "'");

?>

    <div>

        <div class="">

            <form method="POST" action="">

                <table class="form-table" style="display:block; overflow-x:auto">

                    <thead>

                        <tr class="tb-head ">

                            <th class="tb-head"><?php echo __('Pincode', 'bpdpw'); ?></th>

                            <th class="tb-head"><?php echo __('Price', 'bpdpw'); ?></th>

                            <!-- <th class="tb-head"><?php echo __('Quantity', 'bpdpw'); ?></th> -->

                            <!-- <th class="tb-head"><?php echo __('Stock', 'bpdpw'); ?></th> -->

                            <th class="tb-head"><?php echo __('Action', 'bpdpw'); ?></th>

                        </tr>

                    </thead>

                    <tbody class="append_data" id="append_data">

                        <input type="hidden" name="post_url" id="post_url" value="<?php echo admin_url('admin-ajax.php'); ?>">

                        <?php foreach ($results as $result) { ?>

                            <tr id="row_<?php echo $num_row; ?>">
                                <td><input type='text' value="<?php echo $result->pincode; ?>" name='pincode' id="pincode_<?php echo $num_row; ?>"></td>
                                <td><input type='text' value="<?php echo $result->price; ?>" name='price' id="price_<?php echo $num_row; ?>"></td>
                                <td style="display:none"><input type='text' name='quantity' value="<?php echo $result->quantity; ?>" id="quantity_<?php echo $num_row; ?>"></td>
                                <td class='tb-head' style="display:none"><select name='stock' id="stock_<?php echo $num_row; ?>">
                                        <option value='yes' <?php echo ($result->stock == "yes") ? "selected" : ""; ?>><?php echo __('Yes', 'bpdpw'); ?></option>
                                        <option value='no' <?php echo ($result->stock == "no") ? "selected" : ""; ?>><?php echo __('No', 'bpdpw'); ?></option>
                                    </select></td>
                                <td class="row" style="display: flex;"><button type='button' onclick='bpdpw_save(<?php echo $num_row; ?>); return true;' style="float: left"><?php echo __('Save', 'bpdpw'); ?></button><button type='button' onclick='bpdpw_delete(<?php echo $result->id; ?>,<?php echo $result->product_id; ?>); return true;' style="float: left;"><?php echo __('Delete', 'bpdpw'); ?></button></td>
                            </tr>

                        <?php

                            $num_row++;
                        } ?>
                    </tbody>

                    <tfoot>
                        <td colspan="2"></td>
                        <td><button id="add_row" style="margin-left: 70px;" type="button" data-num-row="<?php echo $num_row; ?>" onclick="add(jQuery(this).attr('data-num-row'))"><?php echo __('Add', 'bpdpw'); ?></button></td>

                    </tfoot>

                </table>

            </form>

        </div>

    </div>



    <script>
        var num_row = '<?php echo $num_row; ?>';

        function add(num_row) {

            jQuery("<tr id='row_" + num_row + "'><td><input type='text' name='pincode'  id='pincode_" + num_row + "'></td><td><input type='text' name='price'  id='price_" + num_row + "'></td><td style='display:none'><input type='text' name='quantity'  id='quantity_" + num_row + "'></td><td class='tb-head' style='display:none'><select name='stock'  id='stock_" + num_row + "'><option value='yes' selected><?php echo __('Yes', 'bpdpw'); ?></option><option value='no'><?php echo __('No', 'bpdpw'); ?></option></select></td><td><button type='button'  onclick='bpdpw_save(" + num_row + "); return true;''><?php echo __('Save', 'bpdpw'); ?></button></td></tr>").fadeIn().appendTo(".append_data");

            num_row++;

            jQuery('#add_row').attr('data-num-row', num_row);

        }



        function bpdpw_save(num_row) {

            var pincode = jQuery('#pincode_' + num_row).val();

            var price = jQuery('#price_' + num_row).val();

            var quantity = jQuery('#quantity_' + num_row).val();

            var stock = jQuery('#stock_' + num_row).val();

            var link = jQuery('#post_url').val();

            var product_id = jQuery('#post_ID').val();

            jQuery.ajax({

                type: "POST",

                url: link,

                data: {

                    'pincode': pincode,

                    'action': 'bpdpw_data_save',

                    'price': price,

                    'quantity': quantity,

                    'stock': stock,

                    'product_id': product_id,

                },

                success: function(data) {
                    Swal.fire({
                        title: "Pincode save",
                        text: "Pincode saved successfully!",
                        icon: "success",
                        button: "ok",
                    });
                    jQuery('#bpdpw-product-pincode-price .inside').html(data);

                    //location.reload(true);

                }

            });

        }



        function bpdpw_delete(id, pid) {

            var link = jQuery('#post_url').val();

            jQuery.ajax({

                type: "POST",

                url: link,

                data: {

                    'action': 'bpdpw_delete_data',

                    'id': id,

                    'post_id': pid,

                },

                success: function(data) {
                    Swal.fire({
                        title: "Pincode delete",
                        text: "Pincode delete successfully!",
                        icon: "success",
                        button: "ok",
                    });
                    jQuery('#bpdpw-product-pincode-price .inside').html(data);

                    //location.reload(true);

                }



            });

        }
    </script>

<?php

}

function bpdpw_delete_data_function()
{

    global $wpdb;

    $id = sanitize_text_field($_POST['id']);

    $post_id = sanitize_text_field($_POST['post_id']);

    $wpdb->query("DELETE FROM {$wpdb->prefix}pbdpw_product_price_pincode WHERE id='" . $id . "'");

    $p = get_post($post_id);

    bpdpw_pincode_product_price_meta_box_function($p);

    wp_die();
}

function bpdpw_save_function()
{

    global $wpdb;

    $product_id = sanitize_text_field($_POST['product_id']);

    $pincode = sanitize_text_field($_POST['pincode']);

    $price = sanitize_text_field($_POST['price']);

    $stock = sanitize_text_field($_POST['stock']);

    $quantity = sanitize_text_field($_POST['quantity']);

    $exist = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}pbdpw_product_price_pincode WHERE product_id='" . $product_id . "' AND pincode='" . $pincode . "'");

    if (!empty($exist)) {

        $wpdb->query("UPDATE {$wpdb->prefix}pbdpw_product_price_pincode SET price='" . $price . "',quantity='" . $quantity . "',stock='" . $stock . "' WHERE product_id='" . $product_id . "' AND pincode='" . $pincode . "'");
    } else {

        $wpdb->query("INSERT INTO {$wpdb->prefix}pbdpw_product_price_pincode SET product_id='" . $product_id . "',pincode='" . $pincode . "',price='" . $price . "',quantity='" . $quantity . "',stock='" . $stock . "'");
    }

    $p = get_post($product_id);

    bpdpw_pincode_product_price_meta_box_function($p);

    wp_die();
}

function bpdpw_bulk_save_function()
{

    global $wpdb;
    $data = [];

    $product_id = sanitize_text_field($_POST['product_id']);

    $pincode_start = sanitize_text_field($_POST['pincode_start']);

    $pincode_end = sanitize_text_field($_POST['pincode_end']);

    $price = sanitize_text_field($_POST['price']);

    $stock = sanitize_text_field($_POST['stock']);

    $quantity = sanitize_text_field($_POST['quantity']);
    if (!empty($pincode_start) && !empty($pincode_end) && isset($price) && is_numeric($price) && ($pincode_start <= $pincode_end)) {

        $pin_code_array = [];
        $diff = $pincode_end - $pincode_start;
        for ($i = 0; $i <= $diff; $i++) {
            $pin_code_array[] = $pincode_start;
            $pincode_start++;
        }
    } else {
        $data['status'] = false;
    }

    if (!empty($pin_code_array) && !empty($product_id) && !empty($pincode_start) && !empty($pincode_end) && !empty($price) && !empty($stock) && !empty($quantity)) {

        foreach ($pin_code_array as $pins) {

            $exist = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}pbdpw_product_price_pincode WHERE product_id='" . $product_id . "' AND pincode='" . $pins . "'");

            if (!empty($exist)) {
                $data['status'] = true;
                $wpdb->query("UPDATE {$wpdb->prefix}pbdpw_product_price_pincode SET price='" . $price . "',quantity='" . $quantity . "',stock='" . $stock . "' WHERE product_id='" . $product_id . "' AND pincode='" . $pins  . "'");
            } else {
                $data['status'] = true;
                $wpdb->query("INSERT INTO {$wpdb->prefix}pbdpw_product_price_pincode SET product_id='" . $product_id . "',pincode='" . $pins  . "',price='" . $price . "',quantity='" . $quantity . "',stock='" . $stock . "'");
            }
        }
    } else {
        $data['status'] = false;
    }

    $p = get_post($product_id);

    bpdpw_pincode_product_price_meta_box_function($p);
    // echo json_encode($data);
    wp_die();
}

function update_pincode_function()
{

    global $wpdb;

    $pincode = sanitize_text_field($_POST['pincode']);

    $_SESSION['pincode_session_for_popup'] = $pincode;

    if (!empty($_SESSION['pincode_session_for_popup'])) {

        return true;
    }

    wp_die();
}

// Register and load the widget

function wpb_load_widget()
{

    register_widget('wpb_widget');
}

function bpdpw_save_pincoden_function()
{

    global $wpdb;

    $pincode = sanitize_text_field($_POST['pincode']);

    $_SESSION['pincode_session_for_popup'] = $pincode;

    wp_die();
}

function bpdpw_import_csv_file()
{

?>

    <!DOCTYPE html>

    <html lang="en">

    <head>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" crossorigin="anonymous">

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

    </head>

    <body>

        <div id="wrap">

            <div class="container">
                <form class="product_price_import" id="product_price_import" action="" method="post" name="product_price_import">

                    <table class="table table-bordered">

                        <thead class="bg-primary">

                            <tr>

                                <th colspan="2" class="text-center">
                                    <h3> <?php _e('Product price import according to pincode'); ?></h3>
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            <tr>

                                <th class="text-center form-group"><?php _e('Select your CSV File'); ?></th>

                                <td class="form-group">

                                    <input type="hidden" name="action" value="pbdpw_import_list">

                                    <input type="file" name="pbdpw_file" id="pbdpw_file" class="input-large form-control">

                                </td>
                            </tr>
                        </tbody>

                        <tfoot>

                            <tr>
                                <td colspan="2" class="text-center bg-primary"> <input type="submit" id="submit" name="Import" class="btn btn-success button-loading" value="Import" data-loading-text="Loading...">

                                </td>
                            </tr>
                        </tfoot>



                </form>

            </div>



        </div>

        </div>

        <script>
            // jQuery(document).ready(function(){



            jQuery("#product_price_import").submit(function(e) {

                e.preventDefault();

                var form = new FormData($(this)[0]);



                $.ajax({

                    type: "POST",

                    url: ajaxurl,

                    processData: false,

                    contentType: false,

                    data: form,

                    success: function(data)

                    {

                        var obj = $.parseJSON(data);

                        if (obj.status = 'true') {

                            alert(obj.message);

                        }

                    }

                });

            });

            // });
        </script>

    </body>

    </html>

    <?php

}

function pbdpw_import_list_product_save()
{

    global $wpdb;

    $row = 1;

    $json['status'] = false;

    $Type = $_FILES["pbdpw_file"]["type"];

    $filename = $_FILES["pbdpw_file"]["tmp_name"];

    $_FILES["pbdpw_file"]["size"];

    if (($Type != 'text/csv')) {

        $json['status'] = false;

        $json['message'] = __('Only csv file is allow plese choose csv file', 'bdbpw');
    } else if (empty($_FILES)) {

        $json['status'] = false;

        $json['message'] = __('Please select the file ', 'bdbpw');
    } else if ($_FILES["pbdpw_file"]["size"] > 0) {

        $file = fopen($filename, "r");

        while (($getData = fgetcsv($file, 10000, ",")) !== false) {

            if ($row == 1) {

                $row++;
            } else {

                $result = $wpdb->query("INSERT into {$wpdb->prefix}pbdpw_product_price_pincode (product_id,pincode,price,quantity,stock  )

                   values ('" . $getData[0] . "','" . $getData[1] . "','" . $getData[2] . "','1','1')");
                $row++;
            }
        }

        fclose($file);

        $json['status'] = true;

        $json['message'] = __('Congratulation! your file is uploaded.', 'bdbpw');
    } else {

        $json['status'] = false;

        $json['message'] = __('Please select the file  ', 'bdbpw');
    }

    echo json_encode($json);

    wp_die();
}

function wc_billing_pincode_error()
{

    global $woocommerce;

    // Check if set, if its not set add an error. This one is only requite for companies

    if (!($_SESSION['pincode_session_for_popup'] == $_POST['billing_postcode'])) {

        wc_add_notice("Your Pin-code is not match with your updated pin-code, for update go to sidebar and update your pin-code.", 'error');
    }
}

function bpdpw_pincode_based_price($price, $product)
{

    global $wpdb, $post, $blog_id;

    if (isset($_SESSION['pincode_session_for_popup'])) {

        $pincode = $_SESSION['pincode_session_for_popup'];
    }

    $post_id = $product->get_id();

    $product = wc_get_product($post_id);

    if (!empty($pincode)) {

        $nprice = $wpdb->get_var("SELECT price FROM {$wpdb->prefix}pbdpw_product_price_pincode WHERE pincode='" . $pincode . "' AND product_id='" . $post_id . "'");

        if (!empty($nprice)) {

            $price = $nprice;
        }
    }

    return $price;
}
function bpdpw_pincode_based_price_html($price, $product)
{
    global $wpdb, $post, $blog_id;

    if (isset($_SESSION['pincode_session_for_popup'])) {

        $pincode = $_SESSION['pincode_session_for_popup'];
    }

    $post_id = $product->get_id();

    $product = wc_get_product($post_id);

    if (!empty($pincode)) {

        $nprice = $wpdb->get_var("SELECT price FROM {$wpdb->prefix}pbdpw_product_price_pincode WHERE pincode='" . $pincode . "' AND product_id='" . $post_id . "'");

        if (!empty($nprice)) {

            $price = wc_price($nprice);
        }
    }

    return $price;
}

function pbdpw_function_css()
{

    wp_enqueue_style('PBDPW_styles', PBDPW()->plugin_url() . '/assets/css/front.css', array(), '');
}
function bpdpw_check_pincode_session_function()
{
    global $wpdb;
    $data = array();
    if (isset($_SESSION['pincode_session_for_popup']) && !empty($_SESSION['pincode_session_for_popup'])) {
        $data['status'] = true;
    } else {
        $data['status'] = false;
    }
    echo json_encode($data);
    wp_die();
}

function bpdpw_pincode_popup_script()
{

    if (empty($_SESSION['pincode_session_for_popup'])) {

        do_action('wp_enqueue_styles');

    ?>



        <div id="light" class="white_content">
            <div style="padding-top:12%;" class=" text-center">
                <input type="text" placeholder="Enter Your Pincode" name="pincode" id="pincode">

                <br><br>

                <button type="button btn btn-sm btn-success mr-1" onclick="bpdpw_save_pincode();return false;"><?php echo __('Save', 'bpdpw'); ?></button>

                <a href="javascript:void(0)" onclick="document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'"><?php echo __('Close', 'bpdpw'); ?></a>
            </div>
        </div>

        <!-- <div id="fade" class="black_overlay"></div> -->

        <script>
            function bpdpw_save_pincode() {

                var pincode = jQuery('#pincode').val();

                if (pincode == 'undefined' || pincode == '') {

                    alert('Please fill your picode from where you want to purchage');
                    return false;

                }

                jQuery.ajax({

                    type: "POST",

                    url: woocommerce_params.ajax_url,

                    data: {

                        'action': 'bpdpw_save_pincode',

                        'pincode': pincode,

                    },

                    success: function(data) {

                        location.reload(true);

                    }

                });

            }

            jQuery(document).ready(function() {

                // jQuery('#light').css('display','block');

                // jQuery('#fade').css('display','block');

            });
        </script>

    <?php

    } else {

        do_action('wp_enqueue_styles');

    ?>

        <div id="right-pincode">

            <div id="mySidenav" class="sidenav">

                <input type="text" name="update_pin" id="update_pin" value="<?php echo $_SESSION['pincode_session_for_popup']; ?>">

                <button type="button" onclick="update_pincode()"><?php echo __('Update', 'bpdpw'); ?></button>

                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>



            </div>

            <span class="pin_icon" onclick="openNav()">&#10094;</span>

        </div>

        <script>
            function openNav() {

                document.getElementById("mySidenav").style.width = "250px";

            }



            function closeNav() {

                document.getElementById("mySidenav").style.width = "0";

            }



            function update_pincode() {

                var pincode = jQuery('#update_pin').val();

                jQuery.ajax({

                    type: "POST",

                    dataType: "json",

                    url: woocommerce_params.ajax_url,

                    data: {

                        'action': 'bpdpw_update_pincode',

                        'pincode': pincode,

                    },

                    success: function(data) {



                        location.reload(true);

                    }

                });

            }
        </script>

<?php

    }
}

?>