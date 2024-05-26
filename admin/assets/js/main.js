jQuery(document).ready(function ($) {

    jQuery("table#modelTable").dataTable();

    // Add Brand Ajax
    $(document).on("click", "#btnAddBrand", function(e){
        e.preventDefault();
        var brandName = $("#brandName").val();
        if ( brandName == '' ){
            $("#brandName").addClass('input-error');
            $(".brandAlert").show();
        } else {
            $("#brandName").removeClass('input-error');
            $(".brandAlert").hide();
            $.ajax({
                type: "post",
                url: wpkpm.ajax_url,
                data: {
                    action: "addBrand",
                    name: brandName
                },
                dataType: "json",
                success: function (response) {
                    
                    if ( !response.status ){
                        $(".brandAlert").text(response.message);
                        $(".brandAlert").show();
                    } else {
                        $(".brandAlert").hide();
                        $(".brandAlertSuccess").text(response.message);
                        $(".brandAlertSuccess").show();
                        $("form#kpmAddBrand").trigger("reset");
                        $("tbody#brandTable").html(response.brands);
                    }
                }
            });
        }
    });

    // Edit Brand Popup
    $(document).on("click", ".editBrand", function(){
        var id = $(this).data('id');
        var name = $(this).data('name');
        $("#editBrandName").val(name);
        $("#brandId").val(id);
        $('#kpmEditBrand').modal('show');
    });

    // Edit Brand Ajax
    $(document).on("click", "#btnEditBrand", function(e){
        e.preventDefault();
        var brandName = $("#editBrandName").val();
        var brandId = $("#brandId").val();

        if ( brandName == '' ){
            $("#editBrandName").addClass('input-error');
            $(".brandAlert").show();
        } else {
            $("#editBrandName").removeClass('input-error');
            $(".brandAlert").hide();
            $.ajax({
                type: "post",
                url: wpkpm.ajax_url,
                data: {
                    action: "updateBrand",
                    name: brandName,
                    id: brandId
                },
                dataType: "json",
                success: function (response) {
                    
                    if ( !response.status ){
                        $(".brandAlert").text(response.message);
                        $(".brandAlert").show();
                    } else {
                        $(".brandAlert").hide();
                        $(".brandAlertSuccess").text(response.message);
                        $(".brandAlertSuccess").show();
                        $("tbody#brandTable").html(response.brands);
                    }
                }
            });
        }
    });

    // Delete Brand
    $(document).on("click", ".deleteBrand", function(e){
        e.preventDefault();
        var brandId = $(this).data('id');
        if ( confirm("Are you sure you want to delete this brand?") ){
            $.ajax({
                type: "post",
                url: wpkpm.ajax_url,
                data: {
                    action: "deleteBrand",
                    id: brandId
                },
                dataType: "json",
                success: function (response) {
                    if ( response.status ){
                        $("tbody#brandTable").html(response.brands);
                        alert(response.message);
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    });

    // Reset form
    $(document).on("click", ".brandReset", function(){
        $('form#kpmAddBrand').trigger("reset");
        $("#brandName").removeClass('input-error');
        $(".brandAlert").hide();
        $(".brandAlertSuccess").hide();
    });

    // Add Model Ajax
    $(document).on("click", "#btnAddModel", function(e){
        e.preventDefault();
        var brandName = $("#brandNameDrp").val();
        var modelName = $("#modelName").val();

        if ( brandName == '' ){
            $("#brandNameDrp").addClass('input-error');
            $(".modelAlert").show();
        } else if ( modelName == '' ) {
            $("#brandNameDrp").removeClass('input-error');
            $("#modelName").addClass('input-error');
            $(".modelAlert").show();
        } else {

            $("#brandNameDrp").removeClass('input-error');
            $("#modelName").removeClass('input-error');
            $(".modelAlert").hide();
            $.ajax({
                type: "post",
                url: wpkpm.ajax_url,
                data: {
                    action: "addModel",
                    brand: brandName,
                    model: modelName
                },
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    if ( !response.status ){
                        $(".modelAlert").text(response.message);
                        $(".modelAlert").show();
                    } else {
                        $(".modelAlert").hide();
                        $(".modelAlertSuccess").text(response.message);
                        $(".modelAlertSuccess").show();
                        $("form#kpmAddModel").trigger("reset");
                        $("tbody#modelTable").html(response.models);
                    }
                }
            });
        }
    });

    // Edit Brand Popup
    $(document).on("click", ".editModel", function(){
        var id = $(this).data('id');
        var brandId = $(this).data('brand');
        var modelName = $(this).data('name');
        $("#editModelId").val(id);
        $("#editBrandNameDrp").val(brandId);
        $("#editModelName").val(modelName);
        $('#kpmEditModel').modal('show');
    });

    // Edit Model Ajax
    $(document).on("click", "#btnUpdateModel", function(e){
        e.preventDefault();
        var brandName = $("#editBrandNameDrp").val();
        var modelName = $("#editModelName").val();
        var id = $("#editModelId").val();

        if ( brandName == '' ){
            $("#editBrandNameDrp").addClass('input-error');
            $(".modelAlert").show();
        } else if ( modelName == '' ) {
            $("#editBrandNameDrp").removeClass('input-error');
            $("#editModelName").addClass('input-error');
            $(".modelAlert").show();
        } else {

            $("#editBrandNameDrp").removeClass('input-error');
            $("#editModelName").removeClass('input-error');
            $(".modelAlert").hide();
            $.ajax({
                type: "post",
                url: wpkpm.ajax_url,
                data: {
                    action: "updateModel",
                    id: id,
                    brand: brandName,
                    model: modelName
                },
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    if ( !response.status ){
                        $(".modelAlert").text(response.message);
                        $(".modelAlert").show();
                    } else {
                        $(".modelAlert").hide();
                        $(".modelAlertSuccess").text(response.message);
                        $(".modelAlertSuccess").show();
                        $("form#kpmEditModel").trigger("reset");
                        $("tbody#modelTable").html(response.models);
                    }
                }
            });
        }
    });

    // Delete Model
    $(document).on("click", ".deleteModel", function(e){
        e.preventDefault();
        var modelId = $(this).data('id');
        if ( confirm("Are you sure you want to delete this model?") ){
            $.ajax({
                type: "post",
                url: wpkpm.ajax_url,
                data: {
                    action: "deleteModel",
                    id: modelId
                },
                dataType: "json",
                success: function (response) {
                    if ( response.status ){
                        $("tbody#modelTable").html(response.models);
                        alert(response.message);
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    });

    // Reset form
    $(document).on("click", ".modelReset", function(){
        $('form#kpmAddModel').trigger("reset");
        $("#brandNameDrp").removeClass('input-error');
        $("#modelName").removeClass('input-error');
        $("#editModelName").removeClass('input-error');
        $(".modelAlert").hide();
        $(".modelAlertSuccess").hide();
    });

});