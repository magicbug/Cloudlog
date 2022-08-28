$('.modetable').DataTable({
	"pageLength": 25,
	responsive: false,
	ordering: false,
	"scrollY": "500px",
	"scrollCollapse": true,
	"paging": false,
	"scrollX": true,	
	initComplete: function () {
		this.api()
			.columns('.select-filter')
			.every(function () {
				var column = this;
				var select = $('<select><option value=""></option></select>')
					.appendTo($(column.footer()).empty())
					.on('change', function () {
						var val = $.fn.dataTable.util.escapeRegex($(this).val());

						column.search(val ? '^' + val + '$' : '', true, false).draw();
					});

				column
					.data()
					.unique()
					.sort()
					.each(function (d, j) {
						select.append('<option value="' + d + '">' + d + '</option>');
					});
			});
	},
});
$($.fn.dataTable.tables(true)).DataTable().columns.adjust();

function createModeDialog() {
	$.ajax({
		url: base_url + 'index.php/mode/create',
		type: 'post',
		success: function (html) {
			BootstrapDialog.show({
				title: 'Create mode',
				size: BootstrapDialog.SIZE_WIDE,
				cssClass: 'create-mode-dialog',
				nl2br: false,
				message: html,
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

function createMode(form) {
	if (form.mode.value != '') {
		$.ajax({
			url: base_url + 'index.php/mode/create',
			type: 'post',
			data: {
				'mode': form.mode.value,
				'submode': form.submode.value,
				'qrgmode': form.qrgmode.value,
				'active': form.active.value
			},
			success: function (html) {
				location.reload();
			}
		});
	}
}

function deactivateMode(modeid) {
	$.ajax({
		url: base_url + 'index.php/mode/deactivate',
		type: 'post',
		data: { 'id': modeid },
		success: function (html) {
			$(".mode_" + modeid).text('not active');
			$('.btn_' + modeid).html('Activate');
			$('.btn_' + modeid).attr('onclick', 'activateMode(' + modeid + ')')
		}
	});
}

function activateMode(modeid) {
	$.ajax({
		url: base_url + 'index.php/mode/activate',
		type: 'post',
		data: { 'id': modeid },
		success: function (html) {
			$('.mode_' + modeid).text('active');
			$('.btn_' + modeid).html('Deactivate');
			$('.btn_' + modeid).attr('onclick', 'deactivateMode(' + modeid + ')')
		}
	});
}

function deleteMode(id, mode) {
	BootstrapDialog.confirm({
		title: 'DANGER',
		message: 'Warning! Are you sure you want to delete the following mode: ' + mode + '?',
		type: BootstrapDialog.TYPE_DANGER,
		closable: true,
		draggable: true,
		btnOKClass: 'btn-danger',
		callback: function (result) {
			if (result) {
				$.ajax({
					url: base_url + 'index.php/mode/delete',
					type: 'post',
					data: {
						'id': id
					},
					success: function (data) {
						$(".mode_" + id).parent("tr:first").remove(); // removes mode from table
					}
				});
			}
		}
	});
}

function activateAllModes() {
	BootstrapDialog.confirm({
		title: 'DANGER',
		message: 'Warning! Are you sure you want to activate all modes?',
		type: BootstrapDialog.TYPE_DANGER,
		closable: true,
		draggable: true,
		btnOKClass: 'btn-danger',
		callback: function (result) {
			if (result) {
				$.ajax({
					url: base_url + 'index.php/mode/activateall',
					type: 'post',
					success: function (data) {
						location.reload();
					}
				});
			}
		}
	});
}

function deactivateAllModes() {
	BootstrapDialog.confirm({
		title: 'DANGER',
		message: 'Warning! Are you sure you want to deactivate all modes?',
		type: BootstrapDialog.TYPE_DANGER,
		closable: true,
		draggable: true,
		btnOKClass: 'btn-danger',
		callback: function (result) {
			if (result) {
				$.ajax({
					url: base_url + 'index.php/mode/deactivateall',
					type: 'post',
					success: function (data) {
						location.reload();
					}
				});
			}
		}
	});
}