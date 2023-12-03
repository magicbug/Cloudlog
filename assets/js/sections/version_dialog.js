
// Admin Menu - Version Dialog Settings

function showCustomTextarea() {
    var selectedOptionValue = $("#version_dialog_mode option:selected").val();

    if (selectedOptionValue === "custom_text" || selectedOptionValue === "both") {
        $('#version_dialog_custom_textarea').show();
    } else {
        $('#version_dialog_custom_textarea').hide();
    }
}

$(document).ready(function () {
    showCustomTextarea();
});

$('#version_dialog_mode').on('change', function () {
    showCustomTextarea();
});

//