var callBookProcessingDialog = null;
var inCallbookProcessing = false;
var inCallbookItemProcessing = false;

$('#band').change(function () {
	var band = $("#band option:selected").text();
	if (band != "SAT") {
		$(".sats_dropdown").attr("hidden", true);
	} else {
		$(".sats_dropdown").removeAttr("hidden");
	}
});

$('#selectPropagation').change(function () {
	var prop_mode = $("#selectPropagation option:selected").text();
	if (prop_mode != "Satellite") {
		$(".sats_dropdown").attr("hidden", true);
	} else {
		$(".sats_dropdown").removeAttr("hidden");
	}
});

function updateRow(qso) {
	let row = $('#qsoID-' + qso.qsoID);
	let cells = row.find('td');
	let c = 1;
	cells.eq(c++).text(qso.qsoDateTime);
	cells.eq(c++).text(qso.de);
	cells.eq(c++).html('<a id="edit_qso" href="javascript:displayQso('+qso.qsoID+')">'+qso.dx+'</a>' + (qso.callsign == '' ? '' : ' <small id="lotw_info" class="badge badge-success'+qso.lotw_hint+'" data-toggle="tooltip" data-original-title="LoTW User. Last upload was ' + qso.lastupload + '">L</small>') + ' <a target="_blank" href="https://www.qrz.com/db/'+qso.dx+'"><img width="16" height="16" src="'+base_url+ 'images/icons/qrz.png" alt="Lookup ' + qso.dx + ' on QRZ.com"></a> <a target="_blank" href="https://www.hamqth.com/'+qso.dx+'"><img width="16" height="16" src="'+base_url+ 'images/icons/hamqth.png" alt="Lookup ' + qso.dx + ' on HamQTH"></a>');
	cells.eq(c++).text(qso.mode);
	cells.eq(c++).text(qso.rstS);
	cells.eq(c++).text(qso.rstR);
	cells.eq(c++).text(qso.band);
	cells.eq(c++).text(qso.deRefs);
	cells.eq(c++).html(qso.dxRefs);
	cells.eq(c++).text(qso.name);
	cells.eq(c++).text(qso.qslVia);
	cells.eq(c++).html(qso.qsl);
	if ($(".eqslconfirmation")[0]){
		cells.eq(c++).html(qso.eqsl);
	}
	if ($(".lotwconfirmation")[0]){
		cells.eq(c++).html(qso.lotw);
	}
	cells.eq(c++).text(qso.qslMessage);
	cells.eq(c++).text(qso.dxcc);
	cells.eq(c++).text(qso.state);
	cells.eq(c++).text(qso.cqzone);
	cells.eq(c++).html(qso.iota);

	$('[data-toggle="tooltip"]').tooltip();
	return row;
}

function loadQSOTable(rows) {
	var uninitialized = $('#qsoList').filter(function() {
		return !$.fn.DataTable.fnIsDataTable(this);
	});

	uninitialized.each(function() {
		$.fn.dataTable.moment(custom_date_format + ' HH:mm');
		$(this).DataTable({
			searching: false,
			responsive: false,
			ordering: true,
			"scrollY": window.innerHeight - $('#searchForm').innerHeight() - 250,
			"scrollCollapse": true,
			"paging":         false,
			"scrollX": true,
		});
	});

	var table = $('#qsoList').DataTable();

	table.clear();
	
	for (i = 0; i < rows.length; i++) {
		let qso = rows[i];

		var data = [];
		data.push('<div class="form-check"><input class="form-check-input" type="checkbox" /></div>');
		data.push(qso.qsoDateTime);
		data.push(qso.de);
		data.push('<a id="edit_qso" href="javascript:displayQso('+qso.qsoID+')">'+qso.dx+'</a>' + (qso.callsign == '' ? '' : ' <small id="lotw_info" class="badge badge-success'+qso.lotw_hint+'" data-toggle="tooltip" data-original-title="LoTW User. Last upload was ' + qso.lastupload + ' ">L</small>') + ' <a target="_blank" href="https://www.qrz.com/db/'+qso.dx+'"><img width="16" height="16" src="'+base_url+ 'images/icons/qrz.png" alt="Lookup ' + qso.dx + ' on QRZ.com"></a> <a target="_blank" href="https://www.hamqth.com/'+qso.dx+'"><img width="16" height="16" src="'+base_url+ 'images/icons/hamqth.png" alt="Lookup ' + qso.dx + ' on HamQTH"></a>');
		data.push(qso.mode);
		data.push(qso.rstS);
		data.push(qso.rstR);
		data.push(qso.band);
		data.push(qso.deRefs);
		data.push(qso.dxRefs);
		data.push(qso.name);
		data.push(qso.qslVia);
		data.push(qso.qsl);
		if ($(".eqslconfirmation")[0]){
			data.push(qso.eqsl);
		}
		if ($(".lotwconfirmation")[0]){
			data.push(qso.lotw);
		}
		data.push(qso.qslMessage);
		data.push(qso.dxcc+(qso.end == null ? '' : ' <span class="badge badge-danger">Deleted DXCC</span>'));
		data.push(qso.state);
		data.push(qso.cqzone);
		data.push(qso.iota);
		
		let createdRow = table.row.add(data).index();
		table.rows(createdRow).nodes().to$().data('qsoID', qso.qsoID);
		table.row(createdRow).node().id = 'qsoID-' + qso.qsoID;
	}
	table.draw();
	$('[data-toggle="tooltip"]').tooltip();
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
	element.addClass('activeRow');
}

function unselectQsoID(qsoID) {
	var element = $("#qsoID-" + qsoID);
	element.find("input[type=checkbox]").prop("checked", false);
	element.removeClass('activeRow');
	$('#checkBoxAll').prop("checked", false);
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
				qsoresults: this.qsoResults.value,
				sats: this.sats.value
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
			$(this).closest('tr').addClass('activeRow');
		} else {
			$(this).closest('tr').removeClass('activeRow');
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
		var elements = $('#qsoList tbody input:checked');
		var nElements = elements.length;
		if (nElements == 0) {
			return;
		}
		$('#exportAdif').prop("disabled", true);
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
		xhttp.send("id=" + JSON.stringify(id_list, null, 2)+"&sortorder=" +$('.table').DataTable().order());
		$('#exportAdif').prop("disabled", false);
	});

	$('#queueBureau').click(function (event) {
		handleQsl('Q','B', 'queueBureau');
	});

	$('#queueDirect').click(function (event) {
		handleQsl('Q','D', 'queueDirect');
	});

    $('#queueElectronic').click(function (event) {
		handleQsl('Q','E', 'queueElectronic');
	});

	$('#sentBureau').click(function (event) {
		handleQsl('Y','B', 'sentBureau');
	});

	$('#sentDirect').click(function (event) {
		handleQsl('Y','D', 'sentDirect');
	});

    $('#sentElectronic').click(function (event) {
		handleQsl('Y','E', 'sentElectronic');
	});

	$('#dontSend').click(function (event) {
		handleQsl('N','', 'dontSend');
	});
	$('#notRequired').click(function (event) {
		handleQsl('I','', 'notRequired');
	});
	$('#receivedBureau').click(function (event) {
		handleQslReceived('Y','B', 'receivedBureau');
	});
	$('#receivedDirect').click(function (event) {
		handleQslReceived('Y','D', 'receivedDirect');
	});

	$('#printLabel').click(function (event) {
		var elements = $('#qsoList tbody input:checked');
		var nElements = elements.length;
		if (nElements == 0) {
			return;
		}
		$('#printLabel').prop("disabled", true);

		var id_list=[];

		elements.each(function() {
			let id = $(this).first().closest('tr').data('qsoID')
			id_list.push(id);
		});

		$.ajax({
			url: base_url + 'index.php/labels/printids',
			type: 'post',
			data: {'id': JSON.stringify(id_list, null, 2) },
			xhr:function(){
				var xhr = new XMLHttpRequest();
				xhr.responseType= 'blob'
				return xhr;
			},
			success: function(data) {
				if(data){ 
					var file = new Blob([data], {type: 'application/pdf'});
					var fileURL = URL.createObjectURL(file);
					window.open(fileURL);   
				}
				$.each(id_list, function(k, v) {
					unselectQsoID(this);
				});
				$('#printLabel').prop("disabled", false);
			},
			error: function (data) {
				BootstrapDialog.alert({
					title: 'ERROR',
					message: 'Something went wrong with label print. Go to labels and check if you have defined a label, and that it is set for print!',
					type: BootstrapDialog.TYPE_DANGER,
					closable: false,
					draggable: false,
					callback: function (result) {
					}
				});
				$.each(id_list, function(k, v) {
					unselectQsoID(this);
				});
				$('#printLabel').prop("disabled", false);
			},
		});
	});

	$('#searchForm').on('reset', function(e) {
		setTimeout(function() {
			$('#searchForm').submit();
		});
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

	function handleQslReceived(sent, method, tag) {
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
			url: base_url + 'index.php/logbookadvanced/update_qsl_received',
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
		} else {
			$('#qsoList tbody tr').each(function (i) {
				unselectQsoID($(this).data('qsoID'))
			});
		}
	});

	$('#searchForm').submit();
});
