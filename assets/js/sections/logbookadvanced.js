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
	cells.eq(c++).text(qso.qslMessage);
	cells.eq(c++).text(qso.dxcc);
	cells.eq(c++).text(qso.state);
	cells.eq(c++).text(qso.cqzone);
	cells.eq(c++).text(qso.iota);
	return row;
}

function loadQSOTable(rows) {
	var uninitialized = $('#qsoList').filter(function() {
		return !$.fn.DataTable.fnIsDataTable(this);
	});

	uninitialized.each(function() {
		$(this).DataTable({
			searching: false,
			responsive: false,
			ordering: true,
			"scrollY": window.innerHeight - 280,
			"scrollCollapse": true,
			"paging":         false,
			"scrollX": true,
			"order": [ 0, 'asc' ],
		});
	});

	var table = $('#qsoList').DataTable();

	table.clear();
	
	for (i = 0; i < rows.length; i++) {
		let qso = rows[i];
		
		var data = [
			'<div class="form-check"><input class="form-check-input" type="checkbox" /></div>',
			qso.qsoDateTime,
			qso.de,
			qso.dx,
			qso.mode,
			qso.rstS,
			qso.rstR,
			qso.band,
			qso.deRefs,
			qso.dxRefs,
			qso.name,
			qso.qslVia,
			qso.qslSent,
			qso.qslReceived,
			qso.qslMessage,
			qso.dxcc,
			qso.state,
			qso.cqzone,
			qso.iota,
		];
		
		let createdRow = table.row.add(data).index();
		table.rows(createdRow).nodes().to$().data('qsoID', qso.qsoID);
		table.row(createdRow).node().id = 'qsoID-' + qso.qsoID;
	}
	table.draw();
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
		url: site_url + '/logbookadvanced/updateFromCallbook',
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

$(document).ready(function () {
	$('#dateFrom').datetimepicker({
		format: 'DD/MM/YYYY',
	});
	$('#dateTo').datetimepicker({
		format: 'DD/MM/YYYY',
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
				qslReceived: this.qslReceived.value,
				iota: this.iota.value,
				dxcc: this.dxcc.value,
				propmode: this.selectPropagation.value,
				gridsquare: this.gridsquare.value,
				state: this.state.value,
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
					}
				});
			},
		});
		return false;
	});

	$('#qsoList').on('click', 'input[type="checkbox"]', function() {
		if ($(this).is(":checked")) {
			$(this).closest('tr').addClass('alert-success');
		} else {
			$(this).closest('tr').removeClass('alert-success');
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

	$('#deleteQsos').click(function (event) {
		var elements = $('#qsoList tbody input:checked');
		var nElements = elements.length;
		if (nElements == 0) {
			return;
		}

		$('#deleteQsos').prop("disabled", true);

		var table = $('#qsoList').DataTable();

		BootstrapDialog.confirm({
			title: 'DANGER',
			message: 'Warning! Are you sure you want to delete the marked QSO(s)?' ,
			type: BootstrapDialog.TYPE_DANGER,
			closable: true,
			draggable: true,
			btnOKClass: 'btn-danger',
			callback: function(result) {
				if(result) {
					elements.each(function() {
						let id = $(this).first().closest('tr').data('qsoID')
						$.ajax({
							url: base_url + 'index.php/qso/delete_ajax',
							type: 'post',
							data: {'id': id
							},
							success: function(data) {
								var row = $("#qsoID-" + id);
								table.row(row).remove().draw(false);
							}
						});
						$('#deleteQsos').prop("disabled", false);
					})
				}
			}
		});
	});

	$('#exportAdif').click(function (event) {
		$('#exportAdif').prop("disabled", true);
		var elements = $('#qsoList tbody input:checked');
		var nElements = elements.length;
		if (nElements == 0) {
			return;
		}
		var id_list=[];
		elements.each(function() {
			let id = $(this).first().closest('tr').data('qsoID')
			id_list.push(id);
			unselectQsoID(id);
		});
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			var a;
			if (xhttp.readyState === 4 && xhttp.status === 200) {
				// Trick for making downloadable link
				a = document.createElement('a');
				a.href = window.URL.createObjectURL(xhttp.response);
				// Give filename you wish to download
				a.download = "logbook_export.adi";
				a.style.display = 'none';
				document.body.appendChild(a);
				a.click();
			}
		};
		// Post data to URL which handles post request
		xhttp.open("POST", site_url+'/logbookadvanced/export_to_adif', true);
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		// You should set responseType as blob for binary responses
		xhttp.responseType = 'blob';
		xhttp.send("id=" + JSON.stringify(id_list, null, 2));
		$('#exportAdif').prop("disabled", false);
	});

	$('#queueBureau').click(function (event) {
		handleQsl('Q','B', 'queueBureau');
	});

	$('#queueDirect').click(function (event) {
		handleQsl('Q','D', 'queueDirect');
	});

	$('#sentBureau').click(function (event) {
		handleQsl('Y','B', 'sentBureau');
	});

	$('#sentDirect').click(function (event) {
		handleQsl('Y','D', 'sentDirect');
	});

	$('#dontSend').click(function (event) {
		handleQsl('N','', 'dontSend');
	});

	function handleQsl(sent, method, tag) {
		var elements = $('#qsoList tbody input:checked');
		var nElements = elements.length;
		if (nElements == 0) {
			return;
		}
		$('#'+tag).prop("disabled", true);
		var id_list=[];
		elements.each(function() {
			let id = $(this).first().closest('tr').data('qsoID')
			id_list.push(id);
		});
		$.ajax({
			url: base_url + 'index.php/logbookadvanced/update_qsl',
			type: 'post',
			data: {'id': JSON.stringify(id_list, null, 2),
				'sent' : sent,
				'method' : method
			},
			success: function(data) {
				if (data !== []) {
					$.each(data, function(k, v) {
						updateRow(this);
						unselectQsoID(this.qsoID);
					});
				}
				$('#'+tag).prop("disabled", false);
			}
		});
	}

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