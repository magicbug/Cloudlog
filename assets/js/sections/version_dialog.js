
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


// JavaScript function displayVersionDialog for Bootstrap 5
function displayVersionDialog() {

	$.ajax({
		url: base_url + "index.php/Version_Dialog/displayVersionDialog",
		type: 'GET',
		dataType: 'html',
		success: function (data) {
			$('body').append(data);

			// Activate the Bootstrap Modal
			var versionDialogModal = new bootstrap.Modal(document.getElementById('versionDialogModal'));
			versionDialogModal.show();
		},
		error: function () {
			// Handling of errors
			console.log('Fehler beim Laden der PHP-Datei.');
		}
	});
}

function dismissVersionDialog() {
	$.ajax({
		url: base_url + 'index.php/user_options/dismissVersionDialog',
		method: 'POST',
	});
}
