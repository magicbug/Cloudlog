$('.contesttable').DataTable({
	"pageLength": 25,
	responsive: false,
	ordering: false,
	"scrollY": "400px",
	"scrollCollapse": true,
	"paging": false,
	"scrollX": true,
	dom: 'Bfrtip',
	buttons: [
		{
			extend: 'csv',
			exportOptions: {
				columns: [ 0, 1, 2 ]
			}
		}
	]
});

// using this to change color of csv-button if dark mode is chosen
var background = $('body').css("background-color");

if (background != ('rgb(255, 255, 255)')) {
	$(".buttons-csv").css("color", "white");
}

function createContestDialog() {
	$.ajax({
		url: base_url + 'index.php/contesting/create',
		type: 'post',
		success: function (html) {
			BootstrapDialog.show({
				title: lang_admin_contest_add_contest,
				size: BootstrapDialog.SIZE_WIDE,
				cssClass: 'create-contest-dialog',
				nl2br: false,
				message: html,
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

function createContest(form) {
	if (form.contestname.value != '') {
		$.ajax({
			url: base_url + 'index.php/contesting/create',
			type: 'post',
			data: {
				'name': form.contestname.value,
				'adifname': form.adifcontestname.value
			},
			success: function (html) {
				location.reload();
			}
		});
	}
}

function deactivateContest(contestid) {
	$.ajax({
		url: base_url + 'index.php/contesting/deactivate',
		type: 'post',
		data: { 'id': contestid },
		success: function (html) {
			$(".contest_" + contestid).text(lang_admin_contest_menu_n_active);
			$('.btn_' + contestid).html(lang_admin_contest_menu_activate);
			$('.btn_' + contestid).attr('onclick', 'activateContest(' + contestid + ')')
		}
	});
}

function activateContest(contestid) {
	$.ajax({
		url: base_url + 'index.php/contesting/activate',
		type: 'post',
		data: { 'id': contestid },
		success: function (html) {
			$('.contest_' + contestid).text(lang_admin_contest_menu_active);
			$('.btn_' + contestid).html(lang_admin_contest_menu_deactivate);
			$('.btn_' + contestid).attr('onclick', 'deactivateContest(' + contestid + ')')
		}
	});
}

function deleteContest(id, contest) {
	BootstrapDialog.confirm({
		title: lang_admin_danger,
		message: lang_admin_contest_deletion_warning + contest + '?',
		type: BootstrapDialog.TYPE_DANGER,
		closable: true,
		draggable: true,
		btnOKClass: 'btn-danger',
		callback: function (result) {
			if (result) {
				$.ajax({
					url: base_url + 'index.php/contesting/delete',
					type: 'post',
					data: {
						'id': id
					},
					success: function (data) {
						$(".contest_" + id).parent("tr:first").remove(); // removes mode from table
					}
				});
			}
		}
	});
}

function activateAllContests() {
	BootstrapDialog.confirm({
		title: lang_admin_danger,
		message: lang_admin_contest_active_all_warning,
		type: BootstrapDialog.TYPE_DANGER,
		closable: true,
		draggable: true,
		btnOKClass: 'btn-danger',
		callback: function (result) {
			if (result) {
				$.ajax({
					url: base_url + 'index.php/contesting/activateall',
					type: 'post',
					success: function (data) {
						location.reload();
					}
				});
			}
		}
	});
}

function deactivateAllContests() {
	BootstrapDialog.confirm({
		title: lang_admin_danger,
		message: lang_admin_contest_deactive_all_warning,
		type: BootstrapDialog.TYPE_DANGER,
		closable: true,
		draggable: true,
		btnOKClass: 'btn-danger',
		callback: function (result) {
			if (result) {
				$.ajax({
					url: base_url + 'index.php/contesting/deactivateall',
					type: 'post',
					success: function (data) {
						location.reload();
					}
				});
			}
		}
	});
}
