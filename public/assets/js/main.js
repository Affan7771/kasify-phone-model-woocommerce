jQuery(document).ready(function ($) {
    
    $('#phoneBrand').on('change', function() {
        var brand_id = $(this).val();
        $.ajax({
            type: 'POST',
            url: wpkpm.ajax_url,
            data: {
                action: 'getPhoneModels',
                brand_id: brand_id
            },
            success: function(response) {
                $('#phoneModel').html(response);
                $('.phoneModelContainer').show();
                $('.fullBodyWrapContainer').show();
            }
        });
    });
});