<?php
if (!defined('ABSPATH')) {
    die;
} // end if

$brands = getBrands();
?>

<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Brand Name</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody id="brandTable">
        <?php echo $brands; ?>
    </tbody>
</table>