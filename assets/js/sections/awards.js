$(document).ready(function(){
	// Handle checkbox changes
	$('.award-table input[type="checkbox"]').change(function() {
		var $checkbox = $(this);
		var $row = $checkbox.closest('tr');
		var awardType = $checkbox.data('award');
		
		if (awardType) {
			saveAward(awardType, $checkbox.is(':checked'), $row);
		}
	});
});

function saveAward(awardType, isChecked, $row) {
	// Add saving state to row
	$row.addClass('saving');
	
	$.ajax({
		url: base_url + 'index.php/award/saveAward',
		type: 'post',
		data: {
			award_type: awardType,
			award_value: isChecked ? '1' : '0'
		},
		success: function(data) {
			if (data.message == 'OK') {
				// Visual feedback on entire row
				$row.removeClass('saving').addClass('saved');
				setTimeout(function() {
					$row.removeClass('saved');
				}, 800);
				
				// Show toast notification
				var awardName = $row.find('.award-name').text();
				var status = isChecked ? 'shown in' : 'hidden from';
				showToast('Success', awardName + ' will be ' + status + ' the Awards menu', 'success');
			}
		},
		error: function() {
			$row.removeClass('saving').addClass('error');
			showToast('Error', 'Failed to save award settings', 'danger');
		}
	});
}

function showToast(title, message, type) {
	// Create toast element
	var toastHtml = '<div class="toast align-items-center text-white bg-' + type + ' border-0" role="alert" aria-live="assertive" aria-atomic="true" style="position: fixed; top: 20px; right: 20px; z-index: 9999;">' +
		'<div class="d-flex">' +
			'<div class="toast-body">' +
				'<strong>' + title + ':</strong> ' + message +
			'</div>' +
			'<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>' +
		'</div>' +
	'</div>';
	
	// Append to body and show
	var $toast = $(toastHtml).appendTo('body');
	var toast = new bootstrap.Toast($toast[0], { delay: 3000 });
	toast.show();
	
	// Remove from DOM after hiding
	$toast.on('hidden.bs.toast', function() {
		$(this).remove();
	});
}

function activateAllAwards() {
	if (confirm('Are you sure you want to show all awards in the menu?')) {
		$.ajax({
			url: base_url + 'index.php/award/activateall',
			type: 'post',
			success: function(data) {
				if (data.message == 'OK') {
					location.reload();
				}
			}
		});
	}
}

function deactivateAllAwards() {
	if (confirm('Are you sure you want to hide all awards from the menu?')) {
		$.ajax({
			url: base_url + 'index.php/award/deactivateall',
			type: 'post',
			success: function(data) {
				if (data.message == 'OK') {
					location.reload();
				}
			}
		});
	}
}
