
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

function displayVersionDialog() {
    BootstrapDialog.show({
		title: 'Stored Queries',
		type: BootstrapDialog.TYPE_WARNING,
		size: BootstrapDialog.SIZE_NORMAL,
		cssClass: 'queries-dialog',
		nl2br: false,
		message: 'You need to make a query before you search!',
		buttons: [{
			label: lang_admin_close,
			action: function(dialogItself) {
				dialogItself.close();
			}
		}]
	});
}