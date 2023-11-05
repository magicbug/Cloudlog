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
					label: lang_admin_close,
					action: function (dialogItself) {
						dialogItself.close();
					}
				}]
			});
		}
	});
}

function deletelabel(id) {
	BootstrapDialog.confirm({
		title: 'DANGER',
		message: 'Warning! Are you sure you want this label?',
		type: BootstrapDialog.TYPE_DANGER,
		closable: true,
		draggable: true,
		btnOKClass: 'btn-danger',
		callback: function(result) {
			if (result) {
				window.location.replace(base_url + 'index.php/labels/delete/'+id);
			}
		}
	});
}

function deletepaper(id) {
	var message = 'Warning! Are you sure you want delete this paper type?';
	var currentRow = $(".paper_"+id).first().closest('tr');
	var inuse = currentRow.find("td:eq(4)").text();
	if (inuse > 0) {
		message = 'Warning! This paper type is in use. Are you really sure you want delete this paper type?';
	}
	BootstrapDialog.confirm({
		title: 'DANGER',
		message: message,
		type: BootstrapDialog.TYPE_DANGER,
		closable: true,
		draggable: true,
		btnOKClass: 'btn-danger',
		callback: function(result) {
			if (result) {
				window.location.replace(base_url + 'index.php/labels/deletePaper/'+id);
			}
		}
	});
}
