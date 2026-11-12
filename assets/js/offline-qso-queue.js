/* global base_url, jQuery */
(function (window, $) {
	'use strict';

	var STORAGE_KEY = 'cloudlog_pending_qsos_v1';
	var LOCK_KEY = 'cloudlog_pending_qsos_flush_lock';
	var LOCK_TTL_MS = 60000;
	var TAB_ID = String(Date.now()) + '_' + String(Math.random()).slice(2);

	function generateClientRef() {
		if (window.crypto && typeof window.crypto.randomUUID === 'function') {
			return window.crypto.randomUUID();
		}
		var hex = '0123456789abcdef';
		var s = '';
		for (var i = 0; i < 36; i++) {
			if (i === 8 || i === 13 || i === 18 || i === 23) {
				s += '-';
			} else if (i === 14) {
				s += '4';
			} else if (i === 19) {
				s += hex.charAt((Math.random() * 4) | 8);
			} else {
				s += hex.charAt((Math.random() * 16) | 0);
			}
		}
		return s;
	}

	function readQueue() {
		try {
			var raw = window.localStorage.getItem(STORAGE_KEY);
			if (!raw) {
				return [];
			}
			var parsed = JSON.parse(raw);
			return Array.isArray(parsed) ? parsed : [];
		} catch (e) {
			return [];
		}
	}

	function writeQueue(items) {
		window.localStorage.setItem(STORAGE_KEY, JSON.stringify(items));
		updateBadge();
	}

	function queueCount() {
		return readQueue().length;
	}

	function formDataToObject(formData) {
		var obj = {};
		if (!formData || typeof formData.forEach !== 'function') {
			return obj;
		}
		formData.forEach(function (value, key) {
			if (Object.prototype.hasOwnProperty.call(obj, key)) {
				if (!Array.isArray(obj[key])) {
					obj[key] = [obj[key]];
				}
				obj[key].push(value);
			} else {
				obj[key] = value;
			}
		});
		return obj;
	}

	function shouldEnqueue(xhr, textStatus) {
		if (textStatus === 'timeout' || textStatus === 'error' || textStatus === 'abort') {
			return true;
		}
		if (!xhr) {
			return true;
		}
		var status = xhr.status;
		if (status === 0) {
			return true;
		}
		if (status >= 500) {
			return true;
		}
		return false;
	}

	function ensureBadge() {
		var existing = document.getElementById('offline-qso-queue-badge');
		if (existing) {
			return existing;
		}
		var wrapper = document.createElement('div');
		wrapper.id = 'offline-qso-queue-badge';
		wrapper.className = 'alert alert-warning py-1 px-2 mb-2';
		wrapper.setAttribute('role', 'status');
		wrapper.style.display = 'none';

		var text = document.createElement('span');
		text.id = 'offline-qso-queue-badge-text';
		text.className = 'me-2';
		wrapper.appendChild(text);

		var btn = document.createElement('button');
		btn.type = 'button';
		btn.className = 'btn btn-sm btn-warning';
		btn.id = 'offline-qso-queue-sync-btn';
		btn.textContent = 'Sync now';
		btn.addEventListener('click', function () {
			tryFlushWithLock();
		});
		wrapper.appendChild(btn);

		var anchor = document.querySelector('.qso_panel .card-header')
			|| document.querySelector('.qso_panel')
			|| document.querySelector('.container')
			|| document.body;
		if (anchor && anchor.firstChild) {
			anchor.insertBefore(wrapper, anchor.firstChild);
		} else if (anchor) {
			anchor.appendChild(wrapper);
		}
		return wrapper;
	}

	function updateBadge() {
		var count = queueCount();
		var badge = ensureBadge();
		var text = document.getElementById('offline-qso-queue-badge-text');
		if (!text) {
			return;
		}
		if (count < 1) {
			badge.style.display = 'none';
			return;
		}
		badge.style.display = '';
		var label = count === 1 ? 'QSO' : 'QSOs';
		text.textContent = count + ' pending ' + label + ' waiting to sync';
	}

	function enqueue(item) {
		var queue = readQueue();
		queue.push({
			clientRef: item.clientRef,
			url: item.url,
			source: item.source || 'qso',
			payloadType: item.payloadType || 'object',
			payload: item.payload,
			createdAt: Date.now(),
		});
		writeQueue(queue);
	}

	function parseAjaxJsonResponse(response) {
		if (typeof response === 'object' && response !== null) {
			return response;
		}
		if (typeof response !== 'string') {
			return null;
		}
		try {
			return JSON.parse(response);
		} catch (e) {
			return null;
		}
	}

	function sendItem(item) {
		return new Promise(function (resolve, reject) {
			var data = item.payload;
			var options = {
				url: item.url,
				type: 'POST',
				data: data,
				timeout: 60000,
				success: function (response, textStatus, xhr) {
					if (item.url.indexOf('ajax_saveqso') !== -1) {
						var json = parseAjaxJsonResponse(response);
						if (json && json.status === 'error') {
							reject({ validation: true, response: json });
							return;
						}
					}
					if (xhr && xhr.status === 403) {
						reject({ forbidden: true });
						return;
					}
					resolve(true);
				},
				error: function (xhr, textStatus) {
					if (!shouldEnqueue(xhr, textStatus)) {
						reject({ fatal: true, xhr: xhr, textStatus: textStatus });
						return;
					}
					reject({ network: true, xhr: xhr, textStatus: textStatus });
				},
			};

			if (item.payloadType === 'serialize' && typeof data === 'string') {
				options.data = data;
				options.contentType = 'application/x-www-form-urlencoded; charset=UTF-8';
				if (item.url.indexOf('ajax_saveqso') !== -1) {
					options.dataType = 'json';
				}
			} else if (data && typeof data === 'object') {
				data.client_ref = item.clientRef;
				options.data = data;
			}

			$.ajax(options);
		});
	}

	function acquireLock() {
		var now = Date.now();
		try {
			var raw = window.localStorage.getItem(LOCK_KEY);
			if (raw) {
				var lock = JSON.parse(raw);
				if (lock && lock.until && lock.until > now && lock.tabId !== TAB_ID) {
					return false;
				}
			}
			window.localStorage.setItem(LOCK_KEY, JSON.stringify({
				tabId: TAB_ID,
				until: now + LOCK_TTL_MS,
			}));
			return true;
		} catch (e) {
			return true;
		}
	}

	function releaseLock() {
		try {
			var raw = window.localStorage.getItem(LOCK_KEY);
			if (!raw) {
				return;
			}
			var lock = JSON.parse(raw);
			if (lock && lock.tabId === TAB_ID) {
				window.localStorage.removeItem(LOCK_KEY);
			}
		} catch (e) {
			// ignore
		}
	}

	function flushQueue() {
		return new Promise(function (resolve) {
			var queue = readQueue();
			if (!queue.length) {
				updateBadge();
				resolve({ synced: 0, remaining: 0 });
				return;
			}

			var remaining = [];
			var synced = 0;
			var index = 0;

			function next() {
				if (index >= queue.length) {
					writeQueue(remaining);
					resolve({ synced: synced, remaining: remaining.length });
					return;
				}
				var item = queue[index];
				index += 1;
				sendItem(item).then(function () {
					synced += 1;
					next();
				}).catch(function (err) {
					if (err && err.network) {
						remaining.push(item);
						while (index < queue.length) {
							remaining.push(queue[index]);
							index += 1;
						}
						writeQueue(remaining);
						resolve({ synced: synced, remaining: remaining.length });
						return;
					}
					if (err && err.validation) {
						remaining.push(item);
						next();
						return;
					}
					remaining.push(item);
					next();
				});
			}

			next();
		});
	}

	function tryFlushWithLock() {
		if (!acquireLock()) {
			return Promise.resolve(false);
		}
		return flushQueue().finally(function () {
			releaseLock();
		});
	}

	function showQueuedNotice() {
		var count = queueCount();
		if (typeof showQsoNotice === 'function') {
			showQsoNotice(
				'QSO queued for sync. <strong>' + count + '</strong> pending in the offline queue.',
				'warning'
			);
		}
	}

	function appendClientRefToSerialized(serialized, clientRef) {
		if (serialized.indexOf('client_ref=') !== -1) {
			return serialized.replace(
				/(^|&)client_ref=[^&]*/,
				'$1client_ref=' + encodeURIComponent(clientRef)
			);
		}
		return serialized + (serialized ? '&' : '') + 'client_ref=' + encodeURIComponent(clientRef);
	}

	var api = {
		generateClientRef: generateClientRef,
		shouldEnqueue: shouldEnqueue,
		enqueue: enqueue,
		queueCount: queueCount,
		updateBadge: updateBadge,
		tryFlushWithLock: tryFlushWithLock,
		formDataToObject: formDataToObject,
		showQueuedNotice: showQueuedNotice,
		appendClientRefToSerialized: appendClientRefToSerialized,
	};

	window.CloudlogOfflineQsoQueue = api;

	$(function () {
		ensureBadge();
		updateBadge();
		window.addEventListener('online', function () {
			tryFlushWithLock();
		});
		tryFlushWithLock();
	});
})(window, jQuery);
