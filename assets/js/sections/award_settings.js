// Award Settings - Handle checkbox changes for award preferences
jQuery(document).ready(function($) {
    // Listen for changes on all award checkboxes
    $('.award-checkbox-cell input[type="checkbox"]').on('change', function() {
        var $checkbox = $(this);
        var $row = $checkbox.closest('tr');
        var award_type = $checkbox.data('award');
        var award_value = $checkbox.is(':checked') ? '1' : '0';
        
        console.log('Award checkbox changed:', {
            award_type: award_type,
            award_value: award_value
        });
        
        $row.addClass('saving');
        $.ajax({
            url: base_url + 'index.php/award/saveAward',
            type: 'POST',
            data: {
                award_type: award_type,
                award_value: award_value
            },
            success: function(response) {
                console.log('Award saved successfully:', response);
                $row.removeClass('saving').addClass('saved');
                setTimeout(function() {
                    $row.removeClass('saved');
                }, 800);
            },
            error: function(xhr, status, error) {
                console.error('Error saving award:', error);
                $row.removeClass('saving');
                alert('Error saving award preference.');
            }
        });
    });
});
