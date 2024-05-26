<?php
if (!defined('ABSPATH')) {
    die;
} // end if

global $wpdb;

// Fetch brand data
$table_brands = $wpdb->prefix . 'kasify_phone_brands';
$results = $wpdb->get_results("SELECT * FROM $table_brands");

?>

<div class="wrap">
    <h1>Kasify Phone Model Page</h1>
    <!-- Your plugin's admin page content goes here -->
    <div class="kpm_button_container">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kpmAddBrand">Add Brand</button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kpmAddModel">Add Model</button>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-8">
                <h4><u>Brands</u></h4>
                <?php require_once WPKPM_PLUGIN_PATH . "admin/kasify_brand_table.php"; ?>
            </div>
            <div class="col-12 mt-5">
                <h4><u>Models</u></h4>
                <?php require_once WPKPM_PLUGIN_PATH . "admin/kasify_model_table.php"; ?>
            </div>
        </div>
    </div>
</div>

<!-- Add Brand Modal -->
<div class="modal fade" id="kpmAddBrand" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Brand</h5>
                <button type="button" class="btn-close brandReset" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="kpmAddBrand">
                <div class="modal-body">
                    <div class="alert alert-success brandAlertSuccess" role="alert" style="display: none;"></div>
                    <div class="alert alert-danger brandAlert" role="alert" style="display: none;"> Please fill all required fields </div>
                    <div class="mb-3">
                        <label for="brandName" class="form-label">Brand Name</label>
                        <input type="text" class="form-control brandName" name="brandName" id="brandName" autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary brandReset" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btnAddBrand">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Brand Modal -->
<div class="modal fade" id="kpmEditBrand" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Brand</h5>
                <button type="button" class="btn-close brandReset" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="kpmEditBrand">
                <input type="hidden" name="brandId" id="brandId">
                <div class="modal-body">
                    <div class="alert alert-success brandAlertSuccess" role="alert" style="display: none;"></div>
                    <div class="alert alert-danger brandAlert" role="alert" style="display: none;"> Please fill all required fields </div>
                    <div class="mb-3">
                        <label for="brandName" class="form-label">Brand Name</label>
                        <input type="text" class="form-control brandName" name="editBrandName" id="editBrandName" autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary brandReset" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btnEditBrand">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Models Modal -->
<div class="modal fade" id="kpmAddModel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Model</h5>
                <button type="button" class="btn-close modelReset" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="kpmAddModel">
                <div class="modal-body">
                    <div class="alert alert-success modelAlertSuccess" role="alert" style="display: none;"></div>
                    <div class="alert alert-danger modelAlert" role="alert" style="display: none;"> Please fill all required fields </div>
                    <div class="mb-3">
                        <label for="brandNameDrp" class="form-label">Select Brand</label>
                        <select class="form-select w-100" name="brandNameDrp" id="brandNameDrp">
                            <option value="">Select Brand</option>
                            <?php 
                            if ( !empty( $results ) ) : 
                                foreach ($results as $result) {
                                    echo '<option value="'. $result->id .'">'. $result->brand_name .'</option>';
                                }
                            endif; 
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="modelName" class="form-label">Model Name</label>
                        <input type="text" class="form-control modelName" name="modelName" id="modelName" autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modelReset" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btnAddModel">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Models Modal -->
<div class="modal fade" id="kpmEditModel" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Model</h5>
                <button type="button" class="btn-close modelReset" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="kpmEditModel">
                <input type="hidden" id="editModelId">
                <div class="modal-body">
                    <div class="alert alert-success modelAlertSuccess" role="alert" style="display: none;"></div>
                    <div class="alert alert-danger modelAlert" role="alert" style="display: none;"> Please fill all required fields </div>
                    <div class="mb-3">
                        <label for="editBrandNameDrp" class="form-label">Select Brand</label>
                        <select class="form-select w-100" name="editBrandNameDrp" id="editBrandNameDrp">
                            <option value="">Select Brand</option>
                            <?php 
                            if ( !empty( $results ) ) : 
                                foreach ($results as $result) {
                                    echo '<option value="'. $result->id .'">'. $result->brand_name .'</option>';
                                }
                            endif; 
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editModelName" class="form-label">Model Name</label>
                        <input type="text" class="form-control modelName" name="editModelName" id="editModelName" autocomplete="off">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modelReset" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btnUpdateModel">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>