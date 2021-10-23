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
		'csv'
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
				title: 'Add Contest',
				size: BootstrapDialog.SIZE_WIDE,
				cssClass: 'create-contest-dialog',
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
			$(".contest_" + contestid).text('not active');
			$('.btn_' + contestid).html('Activate');
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
			$('.contest_' + contestid).text('active');
			$('.btn_' + contestid).html('Deactivate');
			$('.btn_' + contestid).attr('onclick', 'deactivateContest(' + contestid + ')')
		}
	});
}

function deleteContest(id, contest) {
	BootstrapDialog.confirm({
		title: 'DANGER',
		message: 'Warning! Are you sure you want to delete the following contest: ' + contest + '?',
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
		title: 'DANGER',
		message: 'Warning! Are you sure you want to activate all contests?',
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
		title: 'DANGER',
		message: 'Warning! Are you sure you want to deactivate all contests?',
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