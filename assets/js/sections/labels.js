$('.labeltable').on('click', 'input[type="checkbox"]', function() {
	var clickedlabelid = $(this).closest('tr').attr("class");
	clickedlabelid = clickedlabelid.match(/\d+/)[0];
	saveDefault(clickedlabelid);
    $('input:checkbox').not(this).prop('checked', false);
});

function saveDefault(id) {
	$.ajax({
		url: base_url + 'index.php/labels/saveDefaultLabel',
		type: 'post',
		data: {'id': id},
		success: function (html) {
		}
	});
}

function printat(stationid) {
	$.ajax({
		url: base_url + 'index.php/labels/startAtLabel',
		type: 'post',
		data: {'stationid': stationid},
		success: function (html) {
			BootstrapDialog.show({
				title: 'Start printing at which label?',
				size: BootstrapDialog.SIZE_NORMAL,
				cssClass: 'qso-dialog',
				nl2br: false,
				message: html,
				onshown: function(dialog) {
				},
				buttons: [{
					label: 'Close',
					action: function (dialogItself) {
						dialogItself.close();
					}
				}]
			});
		}
	});
}
