$('.bandtable').on('click', 'input[type="checkbox"]', function() {
	var $checkbox = $(this);
	var $cell = $checkbox.closest('td');
	
	// Add visual feedback
	$cell.addClass('saving');
	$checkbox.prop('disabled', true);
	
	var clickedbandid = $cell.attr("class");
	clickedbandid = clickedbandid.match(/\d+/)[0];
	
	saveBand(clickedbandid, function() {
		// Remove visual feedback on success
		$cell.removeClass('saving');
		$checkbox.prop('disabled', false);
		
		// Add success flash
		$cell.addClass('saved');
		setTimeout(function() {
			$cell.removeClass('saved');
		}, 1000);
	});
});

$('.bandtable tfoot').on('click', 'input[type="checkbox"]', function() {
	var $masterCheckbox = $(this);
	var clickedaward = $masterCheckbox.closest('th').attr("class");
	var status = $masterCheckbox.is(":checked");
	clickedaward = clickedaward.replace('master_', '');
	
	// Update all related checkboxes with animation
	$('[class^='+clickedaward+'_] input[type="checkbox"]').each(function() {
		var $checkbox = $(this);
		var $cell = $checkbox.closest('td');
		
		$cell.addClass('updating');
		setTimeout(function() {
			$checkbox.prop("checked", status);
			$cell.removeClass('updating').addClass('updated');
			setTimeout(function() {
				$cell.removeClass('updated');
			}, 500);
		}, Math.random() * 200); // Stagger the updates
	});
	
	saveBandAward(clickedaward, status);
});

function saveBandAward(award, status) {
	$.ajax({
		url: base_url + 'index.php/band/saveBandAward',
		type: 'post',
		data: {'award': award,  
			'status': status,  
		},
		success: function (html) {
		}
	});
}

$('.bandtable').DataTable({
	"pageLength": 25,
	responsive: false,
	ordering: false,
	"scrollY": "500px",
	"scrollCollapse": true,
	"paging": false,
	"scrollX": true,
	"searching": true,
	"language": {
		url: getDataTablesLanguageUrl(),
	},
	"columnDefs": [
		{
			"targets": [0, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], // Checkbox columns
			"orderable": false,
			"className": "text-center"
		},
		{
			"targets": [14, 15, 16], // Frequency columns
			"className": "text-center frequency-cell"
		}
	],
	"drawCallback": function() {
		updateStatistics();
	},
	"initComplete": function() {
		// Ensure statistics are updated when table is fully initialized
		updateStatistics();
	}
});

// Custom search functionality
$('#bandSearch').on('keyup', function() {
	$('.bandtable').DataTable().search(this.value).draw();
	updateStatistics();
});

// Clear search button
$('#clearSearch').on('click', function() {
	$('#bandSearch').val('');
	$('.bandtable').DataTable().search('').draw();
	updateStatistics();
});

// Keyboard shortcut to focus search (like GitHub)
$(document).on('keydown', function(e) {
	if (e.key === '/' && !$(e.target).is('input, textarea')) {
		e.preventDefault();
		$('#bandSearch').focus();
	}
	if (e.key === 'Escape' && $(e.target).is('#bandSearch')) {
		$('#bandSearch').blur().val('');
		$('.bandtable').DataTable().search('').draw();
	}
});

// Filter buttons
$('#showActiveOnly').on('click', function() {
	$('.bandtable tbody tr').each(function() {
		var $row = $(this);
		var isActive = $row.find('.band-checkbox-cell input[type="checkbox"]').is(':checked');
		if (!isActive) {
			$row.hide();
		} else {
			$row.show();
		}
	});
	$(this).addClass('active').siblings().removeClass('active');
	updateStatistics();
});

$('#showAll').on('click', function() {
	$('.bandtable tbody tr').show();
	$(this).addClass('active').siblings().removeClass('active');
	updateStatistics();
});

// Initialize with "Show All" active
$('#showAll').addClass('active');

// Update statistics
function updateStatistics() {
	var activeBands = $('.band-checkbox-cell input[type="checkbox"]:checked').length;
	
	// Fallback: if the class-based selector doesn't work, try alternative selectors
	if (activeBands === 0) {
		// Try finding by column position (first column checkboxes)
		activeBands = $('.bandtable tbody tr td:first-child input[type="checkbox"]:checked').length;
	}
	
	$('#activeBandsCount').text(activeBands);
	
	// Update visible rows count
	var visibleRows = $('.bandtable tbody tr:visible').length;
	var totalRows = $('.bandtable tbody tr').length;
	$('#visibleRowsCount').text(visibleRows + ' of ' + totalRows + ' bands');
}

// Update statistics on page load
$(document).ready(function() {
	// Wait for table to be fully rendered before calculating stats
	setTimeout(function() {
		updateStatistics();
	}, 500);
});

// Update statistics when band status changes
$('.bandtable').on('change', '.band-checkbox-cell input[type="checkbox"]', function() {
	updateStatistics();
});

// Bulk action buttons
$('#enableAllAwards').on('click', function() {
	if (confirm('This will enable ALL award tracking (DXCC, IOTA, SOTA, WWFF, POTA, etc.) for ALL bands. Continue?')) {
		$('.bandtable tbody tr').each(function() {
			var $row = $(this);
			// Check all award checkboxes except the first (active) column
			$row.find('input[type="checkbox"]').not('.band-checkbox-cell input').each(function() {
				if (!$(this).is(':checked')) {
					$(this).prop('checked', true).trigger('change');
				}
			});
		});
	}
});

$('#resetAllAwards').on('click', function() {
	if (confirm('This will disable ALL award tracking for ALL bands (bands will remain active for QSO entry). Continue?')) {
		$('.bandtable tbody tr').each(function() {
			var $row = $(this);
			// Uncheck all award checkboxes except the first (active) column
			$row.find('input[type="checkbox"]').not('.band-checkbox-cell input').each(function() {
				if ($(this).is(':checked')) {
					$(this).prop('checked', false).trigger('change');
				}
			});
		});
	}
});

function createBandDialog() {
	$.ajax({
		url: base_url + 'index.php/band/create',
		type: 'post',
		success: function (html) {
			BootstrapDialog.show({
				title: lang_options_bands_create,
				size: BootstrapDialog.SIZE_NORMAL,
				cssClass: 'create-band-dialog',
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
				title: lang_options_bands_edit,
				size: BootstrapDialog.SIZE_NORMAL,
				cssClass: 'edit-band-dialog',
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
		title: lang_general_word_danger,
		message: lang_options_bands_delete_warning + band + '?',
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
		title: lang_general_word_danger,
		message: lang_options_bands_activateall_warning,
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
		title: lang_general_word_danger,
		message: lang_options_bands_deactivateall_warning,
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

function saveBand(id, callback) {
	$.ajax({
		url: base_url + 'index.php/band/saveBand',
		type: 'post',
		data: {'id': id,  
			'status': $(".band_"+id+" input[type='checkbox']").is(":checked"),
			'cq': $(".cq_"+id+" input[type='checkbox']").is(":checked"),
			'dok': $(".dok_"+id+" input[type='checkbox']").is(":checked"),
			'dxcc': $(".dxcc_"+id+" input[type='checkbox']").is(":checked"),
			'iota': $(".iota_"+id+" input[type='checkbox']").is(":checked"),
			'pota': $(".pota_"+id+" input[type='checkbox']").is(":checked"),
			'sig': $(".sig_"+id+" input[type='checkbox']").is(":checked"),
			'sota': $(".sota_"+id+" input[type='checkbox']").is(":checked"),
			'uscounties': $(".uscounties_"+id+" input[type='checkbox']").is(":checked"),
			'was': $(".was_"+id+" input[type='checkbox']").is(":checked"),
			'wwff': $(".wwff_"+id+" input[type='checkbox']").is(":checked"),
			'vucc': $(".vucc_"+id+" input[type='checkbox']").is(":checked")
		},
		success: function (html) {
			if (callback) callback();
		},
		error: function() {
			// Show error state
			if (callback) callback();
		}
	});
}
