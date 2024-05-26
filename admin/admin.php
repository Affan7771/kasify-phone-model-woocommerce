<?php
if (!defined('ABSPATH')) {
    die;
} // end if

/*****************************
 * Enqueue styles and scripts
 *****************************/
add_action('admin_enqueue_scripts', 'wpkpm_admin_scripts');
function wpkpm_admin_scripts($hook_suffix)
{

    // Enqueue script and style if page is "kasify-phone-model"
    if ($hook_suffix != 'toplevel_page_kasify-phone-model') {
        return;
    }

    wp_enqueue_script("wpkpm_bootstrap_js", WPKPM_PLUGIN_URL . "admin/assets/js/bootstrap.min.js", array(), WPKPM_VERSION, true);
    wp_enqueue_script("wpkpm_datatable_js", WPKPM_PLUGIN_URL . "admin/assets/js/datatable.js", array(), WPKPM_VERSION, true);
    wp_enqueue_script("wpkpm_datatable_bootstrap_js", WPKPM_PLUGIN_URL . "admin/assets/js/datatable.bootstrap.js", array(), WPKPM_VERSION, true);
    wp_enqueue_script('wpkpm_admin_script', WPKPM_PLUGIN_URL . "admin/assets/js/main.js", array(), WPKPM_VERSION, true);
    wp_localize_script("wpkpm_admin_script", "wpkpm", array(
        'ajax_url'  => admin_url('admin-ajax.php')
    ));

    wp_enqueue_style("wpkpm_bootstrap_css", WPKPM_PLUGIN_URL . "admin/assets/css/bootstrap.min.css", array(), WPKPM_VERSION);
    wp_enqueue_style("wpkpm_bootstrap_css", WPKPM_PLUGIN_URL . "admin/assets/css/datatable.bootstrap.css", array(), WPKPM_VERSION);
    wp_enqueue_style("wpkpm_admin_style", WPKPM_PLUGIN_URL . "admin/assets/css/main.css", array(), WPKPM_VERSION);
}

/*****************************
 * Admin menu setup
 *****************************/
add_action('admin_menu', 'wpkpm_admin_menu');
function wpkpm_admin_menu()
{
    add_menu_page(
        'Kasify Phone Model', // Page title
        'Kasify Phone Model', // Menu title
        'manage_options',     // Capability
        'kasify-phone-model', // Menu slug
        'kasify_phone_model_page', // Callback function
        'dashicons-smartphone', // Icon URL (you can use a dashicon or a custom URL)
        6                     // Position
    );
}

function kasify_phone_model_page()
{
    require_once WPKPM_PLUGIN_PATH . "admin/kasify_phone_model_page.php";
}

/*****************************
 * Add Brand
 *****************************/
add_action("wp_ajax_addBrand", "addBrand");
function addBrand()
{

    global $wpdb;
    $brandName = $_POST["name"];
    $table_brands = $wpdb->prefix . 'kasify_phone_brands';

    // Check if data already exist
    $query = $wpdb->prepare(
        "SELECT COUNT(*) FROM $table_brands WHERE brand_name = %s",
        $brandName
    );
    $count = $wpdb->get_var($query);

    if ($count > 0) {
        // Data exists
        echo json_encode(array(
            "message"   => "This data is already exist in the table",
            "status"    => false
        ));
    } else {
        // Data not exists, need to insert data
        $wpdb->insert(
            $table_brands,
            array('brand_name' => $brandName),
            array('%s')
        );

        if ($wpdb->insert_id) {
            $brands = getBrands();
            echo json_encode(array(
                "message"   => "Brand successfully added.",
                "status"    => true,
                "brands"    => $brands
            ));
        } else {
            echo json_encode(array(
                "message"   => "There was an error adding the brand name. Please try again",
                "status"    => false
            ));
        }
    }

    wp_die();
}

/*****************************
 * Update Brand
 *****************************/
add_action("wp_ajax_updateBrand", "updateBrand");
function updateBrand()
{
    global $wpdb;
    $brandName = $_POST["name"];
    $id = $_POST['id'];
    $table_brands = $wpdb->prefix . 'kasify_phone_brands';

    // Update brand table
    $result = $wpdb->update($table_brands,
        array( 'brand_name' => $brandName ),
        array( 'id' => $id ),
        array( '%s' ), // Data format for the updated columns
        array( '%d' ) // Where format
    );

    // Check if update was successful
    if ($result !== false) {
        $brands = getBrands();
        echo json_encode(array(
            "message"   => "Brand name updated successfully.",
            "status"    => true,
            "brands"    => $brands
        ));
    } else {
        echo json_encode(array(
            "message"   => "There was an error updating the brand name. Please try again",
            "status"    => false
        ));
    }
    wp_die();
}

/*****************************
 * Delete Brand
 *****************************/
add_action("wp_ajax_deleteBrand", "deleteBrand");
function deleteBrand()
{
    global $wpdb;
    $id = $_POST['id'];
    $table_brands = $wpdb->prefix . 'kasify_phone_brands';

    // Delete query
    $result = $wpdb->delete(
        $table_brands,
        array('id' => $id),
        array('%d') // Where format
    );

    // Check if delete was successful
    if ($result !== false) {
        $brands = getBrands();
        echo json_encode(array(
            "message"   => "Brand deleted successfully",
            "status"    => true,
            "brands"    => $brands
        ));
    } else {
        echo json_encode(array(
            "message"   => "Error deleting brand.",
            "status"    => false,
        ));
    }
    wp_die();
}

/*****************************
 * Add Model
 *****************************/
add_action("wp_ajax_addModel", "addModel");
function addModel()
{

    global $wpdb;
    $brandId = $_POST["brand"];
    $modelName = $_POST["model"];
    
    $table_brands = $wpdb->prefix . 'kasify_phone_brands';
    $table_models = $wpdb->prefix . 'kasify_phone_models';

    // Check if data already exist
    $query = $wpdb->prepare(
        "SELECT COUNT(*) FROM $table_models WHERE brand_id = %d AND model_name = %s",
        $brandId,
        $modelName
    );
    $count = $wpdb->get_var($query);

    if ($count > 0) {
        // Data exists
        echo json_encode(array(
            "message"   => "This data is already exist in the table",
            "status"    => false
        ));
    } else {
        // Data not exists, need to insert data
        $wpdb->insert(
            $table_models,
            array(
                'brand_id' => $brandId,
                'model_name' => $modelName
            ),
            array('%d', '%s')
        );

        if ($wpdb->insert_id) {
            $models = getModels();
            echo json_encode(array(
                "message"   => "Model successfully added.",
                "status"    => true,
                "models"    => $models
            ));
        } else {
            echo json_encode(array(
                "message"   => "There was an error adding the model. Please try again",
                "status"    => false
            ));
        }
    }

    wp_die();
}

/*****************************
 * Update Model
 *****************************/
add_action("wp_ajax_updateModel", "updateModel");
function updateModel()
{
    global $wpdb;
    $brandId = $_POST["brand"];
    $modelName = $_POST["model"];
    $id = $_POST["id"];
    
    $table_models = $wpdb->prefix . 'kasify_phone_models';

    // Update model table
    $result = $wpdb->update($table_models,
        array( 
            'brand_id'      => $brandId,
            'model_name'    => $modelName
        ),
        array( 'id' => $id ),
        array( '%d', '%s' ), // Data format for the updated columns
        array( '%d' ) // Where format
    );

    // Check if update was successful
    if ($result !== false) {
        $models = getModels();
        echo json_encode(array(
            "message"   => "Model updated successfully.",
            "status"    => true,
            "models"    => $models
        ));
    } else {
        echo json_encode(array(
            "message"   => "There was an error updating the model. Please try again",
            "status"    => false
        ));
    }

    wp_die();
}

/*****************************
 * Delete Brand
 *****************************/
add_action("wp_ajax_deleteModel", "deleteModel");
function deleteModel()
{
    global $wpdb;
    $id = $_POST['id'];
    $table_models = $wpdb->prefix . 'kasify_phone_models';

    // Delete query
    $result = $wpdb->delete(
        $table_models,
        array('id' => $id),
        array('%d') // Where format
    );

    // Check if delete was successful
    if ($result !== false) {
        $models = getModels();
        echo json_encode(array(
            "message"   => "Model deleted successfully",
            "status"    => true,
            "models"    => $models
        ));
    } else {
        echo json_encode(array(
            "message"   => "Error deleting model.",
            "status"    => false,
        ));
    }
    wp_die();
}

/*****************************
 * Fetch Brand Data
 *****************************/
function getBrands()
{
    global $wpdb;
    $table_brands = $wpdb->prefix . 'kasify_phone_brands';
    $results = $wpdb->get_results("SELECT * FROM $table_brands");
    $output = "";
    if (!empty($results)) :
        $count = 1;
        foreach ($results as $result) {
            $output .= "<tr>";
            $output .= "<th scope='row'>" . $count . "</th>";
            $output .= "<td>" . $result->brand_name . "</td>";
            $output .= "<td>
                            <button type='button' class='btn btn-info editBrand' data-name='". $result->brand_name ."' data-id='" . $result->id . "'>Edit</button>
                            <button type='button' class='btn btn-danger deleteBrand' data-id='" . $result->id . "'>Delete</button>
                        </td>";
            $output .= "</tr>";
            $count++;
        }
    endif;
    return $output;
}

/*****************************
 * Fetch Model Data
 *****************************/
function getModels()
{
    global $wpdb;
    $table_brands = $wpdb->prefix . 'kasify_phone_brands';
    $table_models = $wpdb->prefix . 'kasify_phone_models';

    $query = "
        SELECT m.*, b.brand_name
        FROM $table_models m
        JOIN $table_brands b ON m.brand_id = b.ID
    ";
    $results = $wpdb->get_results($query);

    $output = "";
    if (!empty($results)) :
        $count = 1;
        foreach ($results as $result) {
            $output .= "<tr>";
            $output .= "<th scope='row'>" . $count . "</th>";
            $output .= "<td>" . $result->brand_name . "</td>";
            $output .= "<td>" . $result->model_name . "</td>";
            $output .= "<td>
                            <button type='button' class='btn btn-info editModel' data-brand='". $result->brand_id ."' data-name='". $result->model_name ."' data-id='" . $result->id . "'>Edit</button>
                            <button type='button' class='btn btn-danger deleteModel' data-id='" . $result->id . "'>Delete</button>
                        </td>";
            $output .= "</tr>";
            $count++;
        }
    endif;
    return $output;
}