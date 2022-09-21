$('.bandtable').on('click', 'input[type="checkbox"]', function() {
	var clickedbandid = $(this).closest('td').attr("class");
	clickedbandid = clickedbandid.match(/\d+/)[0];
	saveBand(clickedbandid);
});

$('.bandtable').DataTable({
	"pageLength": 25,
	responsive: false,
	ordering: false,
	"scrollY": "500px",
	"scrollCollapse": true,
	"paging": false,
	"scrollX": true
});

function createBandDialog() {
	$.ajax({
		url: base_url + 'index.php/band/create',
		type: 'post',
		success: function (html) {
			BootstrapDialog.show({
				title: 'Create band',
				size: BootstrapDialog.SIZE_NORMAL,
				cssClass: 'create-band-dialog',
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

function createBand(form) {
	$(".alert").remove();
	if (form.band.value == "") {
		$('#create_mode').prepend('<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Please enter a band!</div>');
	}
	else {
		$.ajax({
			url: base_url + 'index.php/band/create',
			type: 'post',
			data: {
				'band': form.band.value,
				'bandgroup': form.bandgroup.value,
				'ssbqrg': form.ssbqrg.value,
				'dataqrg': form.dataqrg.value,
				'cwqrg': form.cwqrg.value
			},
			success: function (html) {
				location.reload();
			}
		});
	}
}

function editBandDialog(id) {
	$.ajax({
		url: base_url + 'index.php/band/edit',
		type: 'post',
		data: {
			'id': id
		},
		success: function (html) {
			BootstrapDialog.show({
				title: 'Edit band',
				size: BootstrapDialog.SIZE_NORMAL,
				cssClass: 'edit-band-dialog',
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

function saveUpdatedBand(form) {
	$(".alert").remove();
	if (form.band.value == "") {
		$('#edit_band_dialog').prepend('<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Please enter a band!</div>');
	}
	else {
		$.ajax({
			url: base_url + 'index.php/band/saveupdatedband',
			type: 'post',
			data: {'id': form.id.value,  
				'band': form.band.value,
				'bandgroup': form.bandgroup.value,
				'ssbqrg': form.ssbqrg.value,
				'dataqrg': form.dataqrg.value,
				'cwqrg': form.cwqrg.value
			},
			success: function (html) {
				location.reload();
			}
		});
	}
}

function deleteBand(id, band) {
	BootstrapDialog.confirm({
		title: 'DANGER',
		message: 'Warning! Are you sure you want to delete the following band: ' + band + '?',
		type: BootstrapDialog.TYPE_DANGER,
		closable: true,
		draggable: true,
		btnOKClass: 'btn-danger',
		callback: function (result) {
			if (result) {
				$.ajax({
					url: base_url + 'index.php/band/delete',
					type: 'post',
					data: {
						'id': id
					},
					success: function (data) {
						$(".band_" + id).parent("tr:first").remove(); // removes band from table
					}
				});
			}
		}
	});
}

function activateAllBands() {
	BootstrapDialog.confirm({
		title: 'DANGER',
		message: 'Warning! Are you sure you want to activate all bands?',
		type: BootstrapDialog.TYPE_DANGER,
		closable: true,
		draggable: true,
		btnOKClass: 'btn-danger',
		callback: function (result) {
			if (result) {
				$.ajax({
					url: base_url + 'index.php/band/activateall',
					type: 'post',
					success: function (data) {
						location.reload();
					}
				});
			}
		}
	});
}

function deactivateAllBands() {
	BootstrapDialog.confirm({
		title: 'DANGER',
		message: 'Warning! Are you sure you want to deactivate all bands?',
		type: BootstrapDialog.TYPE_DANGER,
		closable: true,
		draggable: true,
		btnOKClass: 'btn-danger',
		callback: function (result) {
			if (result) {
				$.ajax({
					url: base_url + 'index.php/band/deactivateall',
					type: 'post',
					success: function (data) {
						location.reload();
					}
				});
			}
		}
	});
}

function saveBand(id) {
	$.ajax({
		url: base_url + 'index.php/band/saveBand',
		type: 'post',
		data: {'id': id,  
			'status': $(".band_"+id+" input[type='checkbox']").is(":checked"),
			'cq': $(".cq_"+id+" input[type='checkbox']").is(":checked"),
			'dok': $(".dok_"+id+" input[type='checkbox']").is(":checked"),
			'dxcc': $(".dxcc_"+id+" input[type='checkbox']").is(":checked"),
			'iota': $(".iota_"+id+" input[type='checkbox']").is(":checked"),
			'sig': $(".sig_"+id+" input[type='checkbox']").is(":checked"),
			'sota': $(".sota_"+id+" input[type='checkbox']").is(":checked"),
			'uscounties': $(".uscounties_"+id+" input[type='checkbox']").is(":checked"),
			'was': $(".was_"+id+" input[type='checkbox']").is(":checked"),
			'wwff': $(".wwff_"+id+" input[type='checkbox']").is(":checked"),
			'vucc': $(".vucc_"+id+" input[type='checkbox']").is(":checked")
		},
		success: function (html) {
		}
	});
}