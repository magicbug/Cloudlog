// Award Settings - Handle checkbox changes for award preferences
jQuery(document).ready(function($) {
    // Listen for changes on all award checkboxes (core + plugin awards)
    $(document).on('change', '.award-checkbox-cell input[type="checkbox"]', function() {
        var $checkbox = $(this);
        var $row = $checkbox.closest('tr');
        var plugin_slug = $checkbox.attr('data-plugin-award') || '';

        // Plugin award visibility toggle
        if (plugin_slug !== '') {
            var show_in_menu = $checkbox.is(':checked') ? '1' : '0';

            $row.addClass('saving').removeClass('saved error');
            $.ajax({
                url: base_url + 'index.php/award/savePluginAward',
                type: 'POST',
                dataType: 'json',
                data: {
                    plugin_slug: plugin_slug,
                    show_in_menu: show_in_menu
                },
                success: function(response) {
                    if (!response || response.message !== 'OK') {
                        $row.removeClass('saving').addClass('error');
                        return;
                    }

                    $row.removeClass('saving').addClass('saved');
                    setTimeout(function() {
                        $row.removeClass('saved');
                    }, 800);
                },
                error: function(xhr, status, error) {
                    console.error('Error saving plugin award preference:', error);
                    $row.removeClass('saving').addClass('error');
                    setTimeout(function() {
                        $row.removeClass('error');
                    }, 1200);
                }
            });

            return;
        }

        // Core award visibility toggle
        var award_type = $checkbox.data('award');
        var award_value = $checkbox.is(':checked') ? '1' : '0';
        
        console.log('Award checkbox changed:', {
            award_type: award_type,
            award_value: award_value
        });
        
        $row.addClass('saving').removeClass('saved error');
        $.ajax({
            url: base_url + 'index.php/award/saveAward',
            type: 'POST',
            dataType: 'json',
            data: {
                award_type: award_type,
                award_value: award_value
            },
            success: function(response) {
                if (!response || response.message !== 'OK') {
                    $row.removeClass('saving').addClass('error');
                    return;
                }

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
