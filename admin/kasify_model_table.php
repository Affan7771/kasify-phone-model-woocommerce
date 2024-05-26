<?php
if (!defined('ABSPATH')) {
    die;
} // end if
$modelTable = getModels();
?>

<table id="modelTable" class="table" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Model</th>
            <th>Brand</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody id="modelTable">
        <?php echo $modelTable; ?>
    </tbody>
    <tfoot>
    <tr>
            <th>#</th>
            <th>Model</th>
            <th>Brand</th>
            <th>Action</th>
        </tr>
    </tfoot>
</table>