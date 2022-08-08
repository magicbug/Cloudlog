var callBookProcessingDialog = null;
var inCallbookProcessing = false;
var inCallbookItemProcessing = false;

function updateRow(qso) {
	let row = $('#qsoID-' + qso.qsoID);
	let cells = row.find('td');
	let c = 1;
	cells.eq(c++).text(qso.qsoDateTime);
	cells.eq(c++).text(qso.de);
	cells.eq(c++).text(qso.dx);
	cells.eq(c++).text(qso.mode);
	cells.eq(c++).text(qso.rstS);
	cells.eq(c++).text(qso.rstR);
	cells.eq(c++).text(qso.band);
	cells.eq(c++).text(qso.deRefs);
	cells.eq(c++).text(qso.dxRefs);
	cells.eq(c++).text(qso.name);
	cells.eq(c++).text(qso.qslVia);
	cells.eq(c++).text(qso.qslSent);
	cells.eq(c++).text(qso.qslReceived);
	cells.eq(c++).text(qso.qslMsg);
	return row;
}

function loadQSOTable(rows) {
	let table = $("#qsoList");
	table.find('tbody').children('tr').not('.model').remove();
	for (i = 0; i < rows.length; i++) {
		let qso = rows[i];
		let row = table.find('tbody tr.model').clone();
		row.attr('id', 'qsoID-' + qso.qsoID)
		row.data('qsoID', qso.qsoID);
		table.find('tbody').append(row);
		updateRow(qso);
		row.removeClass('d-none');
		row.removeClass('model');

	}
}

function selectQsoID(qsoID) {
	var element = $("#qsoID-" + qsoID);
	element.find("input[type=checkbox]").prop("checked", true);
	element.addClass('alert-success')
}


function unselectQsoID(qsoID) {
	var element = $("#qsoID-" + qsoID);
	element.find("input[type=checkbox]").prop("checked", false);
	element.removeClass('alert-success')
}

function processNextCallbookItem() {
	if (!inCallbookProcessing) return;

	var elements = $('#qsoList tbody input:checked');
	var nElements = elements.length;
	if (nElements == 0) {
		inCallbookProcessing = false;
		callBookProcessingDialog.close();
		return;
	}

	callBookProcessingDialog.setMessage("Retrieving callbook data : " + nElements + " remaining");

	unselectQsoID(elements.first().closest('tr').data('qsoID'));

	$.ajax({
		url: site_url + '/qslmanager/updateFromCallbook',
		type: 'post',
		data: {
			qsoID: elements.first().closest('tr').data('qsoID')
		},
		dataType: 'json',
		success: function (data) {
			if (data !== []) {
				updateRow(data);
			}
			setTimeout("processNextCallbookItem()", 50);
		},
		error: function (data) {
			setTimeout("processNextCallbookItem()", 50);
		},
	});
}

$(document).ready(function () {
	$('#dateFrom').datetimepicker({
		format: 'YYYY-MM-DD',
	});
	$('#dateTo').datetimepicker({
		format: 'YYYY-MM-DD',
	});

	$('#searchForm').submit(function (e) {
		$('#searchButton').prop("disabled", true);

		$.ajax({
			url: this.action,
			type: 'post',
			data: {
				dateFrom: this.dateFrom.value,
				dateTo: this.dateTo.value,
				de: this.de.value,
				dx: this.dx.value,
				mode: this.mode.value,
				band: this.band.value,
				qslSent: this.qslSent.value,
				qslReceived: this.qslReceived.value
			},
			dataType: 'json',
			success: function (data) {
				$('#searchButton').prop("disabled", false);
				loadQSOTable(data);
			},
			error: function (data) {
				$('#searchButton').prop("disabled", false);
				BootstrapDialog.alert({
					title: 'ERROR',
					message: 'An error ocurred while making the request',
					type: BootstrapDialog.TYPE_DANGER,
					closable: false,
					draggable: false,
					callback: function (result) {
						//document.location=document.location;
					}
				});
			},
		});
		return false;
	});

	$("#qsoList tbody").on('click', 'tr', function (event) {
		var qsoID = $(event.currentTarget).data('qsoID');
		let checkBox;
		if (event.target.tagName === 'INPUT') {
			checkBox = $(this);
		} else {
			checkBox = $(this).find('input[type=checkbox]');
		}
		if (checkBox.prop("checked")) {
			unselectQsoID(qsoID);
		} else {
			selectQsoID(qsoID);
		}
	});

	$('#btnUpdateFromCallbook').click(function (event) {

		var elements = $('#qsoList tbody input:checked');
		var nElements = elements.length;
		if (nElements == 0) {
			return;
		}
		inCallbookProcessing = true;

		callBookProcessingDialog = BootstrapDialog.show({
			title: "Retrieving callbook data for " + nElements + " QSOs",
			message: "Retrieving callbook data for " + nElements + " QSOs",
			type: BootstrapDialog.TYPE_DANGER,
			closable: false,
			draggable: false,
			buttons: [{
				label: 'Cancel',
				action: function(dialog) {
					inCallbookProcessing = false;
					dialog.close();
				}
			}]
		});

		processNextCallbookItem();
	});

	$('#checkBoxAll').change(function (event) {
		if (this.checked) {
			$('#qsoList tbody tr').each(function (i) {
				selectQsoID($(this).data('qsoID'))
			});
		}else{
			$('#qsoList tbody tr').each(function (i) {
				unselectQsoID($(this).data('qsoID'))
			});
		}
	});

	$('#searchForm').submit();
});
