(function () {
	'use strict';

	function filterListItems(searchInput, itemSelector) {
		if (!searchInput) {
			return;
		}

		var query = searchInput.value.trim().toLowerCase();
		document.querySelectorAll(itemSelector).forEach(function (item) {
			var name = item.getAttribute('data-name') || '';
			item.classList.toggle('d-none', query !== '' && name.indexOf(query) === -1);
		});
	}

	function getSelectedRadioValue(name) {
		var selected = document.querySelector('input[name="' + name + '"]:checked');
		return selected ? selected.value : null;
	}

	function selectRadioByValue(name, value) {
		if (!value) {
			return;
		}

		document.querySelectorAll('input[name="' + name + '"]').forEach(function (radio) {
			radio.checked = radio.value === String(value);
		});
	}

	function showStep(step) {
		var logbookStep = document.getElementById('quickSwitchStepLogbook');
		var locationStep = document.getElementById('quickSwitchStepLocation');
		var nextBtn = document.getElementById('quickSwitchNextBtn');
		var setActiveBtn = document.getElementById('quickSwitchSetActiveBtn');

		if (step === 'logbook') {
			logbookStep.classList.remove('d-none');
			locationStep.classList.add('d-none');
			nextBtn.classList.remove('d-none');
			setActiveBtn.classList.add('d-none');
		} else {
			logbookStep.classList.add('d-none');
			locationStep.classList.remove('d-none');
			nextBtn.classList.add('d-none');
			setActiveBtn.classList.remove('d-none');
		}
	}

	function resetModal(modal) {
		showStep('logbook');
		document.getElementById('quickSwitchLogbookSearch').value = '';
		document.getElementById('quickSwitchLocationSearch').value = '';
		filterListItems(document.getElementById('quickSwitchLogbookSearch'), '.quick-switch-logbook-item');
		filterListItems(document.getElementById('quickSwitchLocationSearch'), '.quick-switch-location-item');

		var activeLogbook = modal.getAttribute('data-active-logbook');
		var activeLocation = modal.getAttribute('data-active-location');
		selectRadioByValue('logbook_id', activeLogbook);
		selectRadioByValue('station_id', activeLocation);
	}

	document.addEventListener('DOMContentLoaded', function () {
		var modal = document.getElementById('logbookQuickSwitchModal');
		if (!modal) {
			return;
		}

		var logbookSearch = document.getElementById('quickSwitchLogbookSearch');
		var locationSearch = document.getElementById('quickSwitchLocationSearch');
		var nextBtn = document.getElementById('quickSwitchNextBtn');
		var backLink = document.getElementById('quickSwitchBackLink');
		var lastLocationUrl = modal.getAttribute('data-last-location-url');

		logbookSearch.addEventListener('input', function () {
			filterListItems(logbookSearch, '.quick-switch-logbook-item');
		});

		locationSearch.addEventListener('input', function () {
			filterListItems(locationSearch, '.quick-switch-location-item');
		});

		nextBtn.addEventListener('click', function () {
			var logbookId = getSelectedRadioValue('logbook_id');
			if (!logbookId) {
				return;
			}

			fetch(lastLocationUrl + '/' + encodeURIComponent(logbookId), {
				headers: {
					'X-Requested-With': 'XMLHttpRequest'
				}
			})
				.then(function (response) {
					return response.json();
				})
				.then(function (data) {
					if (data && data.station_id) {
						selectRadioByValue('station_id', data.station_id);
					}
					showStep('location');
				})
				.catch(function () {
					showStep('location');
				});
		});

		backLink.addEventListener('click', function (event) {
			event.preventDefault();
			showStep('logbook');
		});

		modal.addEventListener('show.bs.modal', function () {
			resetModal(modal);
		});
	});
})();
