<?php
if ( ! defined( 'ABSPATH' ) ) {die;} // end if

/*****************************
* Enqueue styles and scripts
*****************************/
add_action('wp_enqueue_scripts', 'wpkpm_scripts');
function wpkpm_scripts(){
    wp_enqueue_script( "wpkpm_public_script", WPKPM_PLUGIN_URL . "public/assets/js/main.js", array(), WPKPM_VERSION, true );
    wp_localize_script( "wpkpm_public_script", "wpkpm", array(
        'ajax_url'  => admin_url( 'admin-ajax.php' )
    ) );

    wp_enqueue_style( "wpkpm_public_style", WPKPM_PLUGIN_URL . "public/assets/css/main.css", array(), WPKPM_VERSION );

}

/*****************************
* Adding Brand Dropdown in single product page
*****************************/
function wpkpm_add_brand_dropdown() {
    global $product;
    if ($product->is_type('simple')) {
        ?>
        <div id="wpkpm-dropdown">
            <div class="phoneBrandContainer">
                <label for="phoneBrand">Select Brand <span style="color: #ff0000;">*</span></label>
                <select id="phoneBrand" name="phoneBrand" required>
                    <option value="">Select Brand</option>
                    <?php
                    global $wpdb;
                    $table_brands = $wpdb->prefix . 'kasify_phone_brands';
                    $brands = $wpdb->get_results("SELECT * FROM $table_brands");
                    foreach ($brands as $brand) {
                        echo '<option value="' . esc_attr($brand->id) . '">' . esc_html($brand->brand_name) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="phoneModelContainer" style="display: none;">
                <label for="phoneModel">Select Model <span style="color: #ff0000;">*</span></label>
                <select id="phoneModel" name="phoneModel" required>
                    <option value="">Select Model</option>
                </select>
            </div>
            <div class="fullBodyWrapContainer" style="display: none;">
                <label for="bodyWrap">Full Body Wrap (Cover Sides & Edges)</label>
                <select id="bodyWrap" name="bodyWrap">
                    <option value="Full Body Wrap (Cover Sides & Edges)">Full Body Wrap (Cover Sides & Edges)</option>
                    <option value="Only Back (No Sides)">Only Back (No Sides)</option>
                </select>
            </div>
        </div>
        <?php
    }
}
add_action('woocommerce_before_add_to_cart_button', 'wpkpm_add_brand_dropdown');

/*****************************
* Dynamic dropdown data for model
*****************************/
function getPhoneModels() {
    global $wpdb;
    $brand_id = intval($_POST['brand_id']);
    $table_models = $wpdb->prefix . 'kasify_phone_models';
    $models = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_models WHERE brand_id = %d", $brand_id));

    $output = '<option value="">Select Model</option>';
    foreach ($models as $model) {
        $output .= '<option value="' . esc_attr($model->id) . '">' . esc_html($model->model_name) . '</option>';
    }

    echo $output;
    wp_die();
}
add_action('wp_ajax_getPhoneModels', 'getPhoneModels');
add_action('wp_ajax_nopriv_getPhoneModels', 'getPhoneModels');

/*****************************
* Save Dropdown Data to Cart Item
*****************************/
function wpkpm_save_data_to_cart_item($cart_item_data, $product_id) {
    global $wpdb;
    $table_brands = $wpdb->prefix . 'kasify_phone_brands';
    $table_models = $wpdb->prefix . 'kasify_phone_models';

    if (isset($_POST['phoneBrand']) && isset($_POST['phoneModel'])) {

        $query = $wpdb->prepare("
            SELECT * 
            FROM $table_models AS models
            INNER JOIN $table_brands AS brands
            ON models.brand_id = brands.id
            WHERE models.brand_id = %d AND models.id = %d
            LIMIT 1
        ", $_POST['phoneBrand'], $_POST['phoneModel']);
        $result = $wpdb->get_row($query);

        if ( !empty($result) ) :
            $cart_item_data['phoneBrand'] = sanitize_text_field($result->brand_name);
            $cart_item_data['phoneModel'] = sanitize_text_field($result->model_name);
            $cart_item_data['bodyWrap'] = sanitize_text_field($_POST['bodyWrap']);
            $cart_item_data['unique_key'] = md5(microtime().rand());
        endif;
    }
    return $cart_item_data;
}
add_filter('woocommerce_add_cart_item_data', 'wpkpm_save_data_to_cart_item', 10, 2);

function wpkpm_display_data_fields_cart($item_data, $cart_item) {
    if (isset($cart_item['phoneBrand'])) {
        $item_data[] = array(
            'name' => 'Brand',
            'value' => $cart_item['phoneBrand']
        );
    }
    if (isset($cart_item['phoneModel'])) {
        $item_data[] = array(
            'name' => 'Model',
            'value' => $cart_item['phoneModel']
        );
    }
    if (isset($cart_item['bodyWrap'])) {
        $item_data[] = array(
            'name' => 'Body Wrap',
            'value' => $cart_item['bodyWrap']
        );
    }
    return $item_data;
}
add_filter('woocommerce_get_item_data', 'wpkpm_display_data_fields_cart', 10, 2);

/*****************************
* Display Dropdown Data on Cart, Checkout, Thank you, and Order Pages
*****************************/
function wpkpm_add_data_fields_order_meta($item_id, $values, $cart_item_key) {
    if (isset($values['phoneBrand'])) {
        wc_add_order_item_meta($item_id, 'Brand', $values['phoneBrand']);
    }
    if (isset($values['phoneModel'])) {
        wc_add_order_item_meta($item_id, 'Model', $values['phoneModel']);
    }
    if (isset($values['bodyWrap'])) {
        wc_add_order_item_meta($item_id, 'Body Wrap', $values['bodyWrap']);
    }
}
add_action('woocommerce_add_order_item_meta', 'wpkpm_add_data_fields_order_meta', 10, 3);

function wpkpm_display_order_meta_admin($item_id, $item, $order) {
    if ($brand = wc_get_order_item_meta($item_id, 'Brand')) {
        echo '<p><strong>Brand: </strong> ' . $brand . '</p>';
    }
    if ($model = wc_get_order_item_meta($item_id, 'Model')) {
        echo '<p><strong>Model: </strong> ' . $model . '</p>';
    }
    if ($bodyWrap = wc_get_order_item_meta($item_id, 'Body Wrap')) {
        echo '<p><strong>Body Wrap: </strong> ' . $bodyWrap . '</p>';
    }
}
add_action('woocommerce_order_item_meta_end', 'wpkpm_display_order_meta_admin', 10, 3);

