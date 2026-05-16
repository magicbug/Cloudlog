(function() {
    'use strict';

    const STORAGE = {
        wsUrl: 'cloudlog.remoteOperation.wsUrl',
        linkPassword: 'cloudlog.remoteOperation.linkPassword',
        linkName: 'cloudlog.remoteOperation.linkName',
        radioName: 'cloudlog.remoteOperation.radioName',
        audioIn: 'cloudlog.remoteOperation.audioIn',
        audioOut: 'cloudlog.remoteOperation.audioOut',
        playbackVolume: 'cloudlog.remoteOperation.playbackVolume',
        playbackMuted: 'cloudlog.remoteOperation.playbackMuted',
        stun: 'cloudlog.remoteOperation.stun',
        turnUrl: 'cloudlog.remoteOperation.turnUrl',
        turnUser: 'cloudlog.remoteOperation.turnUser',
        turnPwd: 'cloudlog.remoteOperation.turnPwd',
        minimalMic: 'cloudlog.remoteOperation.minimalMicProcessing',
        preferOpus: 'cloudlog.remoteOperation.preferOpus',
        playoutHint: 'cloudlog.remoteOperation.playoutDelayHint',
        statsPoll: 'cloudlog.remoteOperation.statsPoll'
    };

    const DEFAULTS = {
        wsUrl: 'wss://relay.cloudlog.org/ws-webrtc',
        linkPassword: '',
        linkName: '',
        radioName: '',
        audioIn: '',
        audioOut: '',
        playbackVolume: 50,
        playbackMuted: false,
        stun: 'stun:stun.l.google.com:19302',
        turnUrl: '',
        turnUser: '',
        turnPwd: '',
        minimalMic: false,
        preferOpus: false,
        playoutHint: true,
        statsPoll: false
    };

    let ws = null;
    let pc = null;
    let mediaStream = null;
    let pendingRemoteIce = [];
    let wsInboundChain = Promise.resolve();
    let statsTimer = undefined;
    let rxLevelTimer = undefined;
    let audioWatchdogTimer = undefined;
    let reconnectTimer = undefined;
    let reconnectAttempt = 0;
    let reconnectCount = 0;
    let shouldAutoReconnect = false;
    let manualDisconnectRequested = false;
    let lastInboundPacketCount = 0;
    let lastInboundPacketTs = 0;
    let currentStatusState = 'disconnected';
    const reconnectBackoffMs = [1000, 2000, 5000, 10000];
    let currentSettings = Object.assign({}, DEFAULTS);
    let levelAudioContext = null;
    let levelMeterCleanup = {
        mic: null,
        rx: null
    };
    let rxLevelSample = {
        energy: null,
        duration: null
    };
    let quickDiag = {
        speaker: 'default',
        inboundPackets: 0,
        jitterMs: 'n/a',
        reconnects: 0,
        state: 'Disconnected'
    };

    function $(id) {
        return document.getElementById(id);
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    function log(line, cls) {
        const logEl = $('remoteOperationLog');
        if (!logEl) {
            return;
        }

        const t = new Date().toISOString().slice(11, 19);
        const span = cls ? '<span class="' + cls + '">' : '<span>';
        logEl.innerHTML += span + '[' + t + '] ' + escapeHtml(line) + '</span>\n';
        logEl.scrollTop = logEl.scrollHeight;
    }

    function setStatus(text, className) {
        ['remote_operation_status', 'remoteOperationModalStatus'].forEach(function(id) {
            const el = $(id);
            if (!el) {
                return;
            }
            el.className = className;
            el.innerHTML = text;
        });
    }

    function setDeviceWarning(message) {
        ['remoteOperationDeviceWarning', 'remoteOperationModalDeviceWarning'].forEach(function(id) {
            const box = $(id);
            if (!box) {
                return;
            }

            if (message) {
                box.textContent = message;
                box.classList.remove('d-none');
            } else {
                box.textContent = '';
                box.classList.add('d-none');
            }
        });
    }

    function renderQuickDiagnostics() {
        const el = $('remoteOperationQuickDiagnostics');
        if (!el) {
            return;
        }

        el.textContent = 'State: ' + quickDiag.state + ' | speaker: ' + quickDiag.speaker + ' | inbound packets: ' + quickDiag.inboundPackets + ' | jitter: ' + quickDiag.jitterMs + ' | reconnects: ' + quickDiag.reconnects;
    }

    function setAudioUnlockHint(message) {
        const hint = $('remoteOperationAudioUnlockHint');
        const button = $('remoteOperationEnableAudioButton');

        if (hint) {
            if (message) {
                hint.textContent = message;
                hint.classList.remove('d-none');
            } else {
                hint.textContent = '';
                hint.classList.add('d-none');
            }
        }

        if (button) {
            if (message) {
                button.classList.remove('d-none');
            } else {
                button.classList.add('d-none');
            }
        }
    }

    function setOperationalState(state, detail) {
        currentStatusState = state;
        let label = 'Disconnected';
        let className = 'badge bg-secondary';

        if (state === 'connecting') {
            label = 'Connecting';
            className = 'badge bg-warning text-dark';
        } else if (state === 'paired') {
            label = 'Connected';
            className = 'badge bg-success';
        } else if (state === 'media') {
            label = 'Media flowing';
            className = 'badge bg-success';
        } else if (state === 'noaudio') {
            label = 'Connected, no audio';
            className = 'badge bg-warning text-dark';
        } else if (state === 'reconnecting') {
            label = detail || 'Reconnecting';
            className = 'badge bg-warning text-dark';
        } else if (state === 'failed') {
            label = detail || 'Connection failed';
            className = 'badge bg-danger';
        }

        setStatus('Status: ' + label, className);
        quickDiag.state = label;
        renderQuickDiagnostics();
    }

    function clearReconnectTimer() {
        if (reconnectTimer !== undefined) {
            clearTimeout(reconnectTimer);
            reconnectTimer = undefined;
        }
    }

    function scheduleReconnect(reason) {
        if (!shouldAutoReconnect) {
            return;
        }

        clearReconnectTimer();
        const delay = reconnectBackoffMs[Math.min(reconnectAttempt, reconnectBackoffMs.length - 1)];
        reconnectAttempt += 1;
        reconnectCount += 1;
        quickDiag.reconnects = reconnectCount;
        renderQuickDiagnostics();
        setOperationalState('reconnecting', 'Reconnecting in ' + Math.round(delay / 1000) + 's');
        log('Connection dropped (' + reason + '). Reconnect attempt ' + reconnectAttempt + ' in ' + delay + ' ms.', 'err');

        reconnectTimer = window.setTimeout(function() {
            connectRemoteOperation(true);
        }, delay);
    }

    function applyPlaybackControlsToAudioElement() {
        const rx = $('remoteOperationRx');
        if (!rx) {
            return;
        }

        const volume = Math.max(0, Math.min(100, Number(currentSettings.playbackVolume || 0)));
        rx.volume = volume / 100;
        rx.muted = !!currentSettings.playbackMuted;
    }

    function syncPlaybackControlsToUi() {
        const volumeInput = $('remoteOperationPlaybackVolume');
        const muteToggle = $('remoteOperationMuteToggle');

        if (volumeInput) {
            volumeInput.value = String(Math.max(0, Math.min(100, Number(currentSettings.playbackVolume || 0))));
        }
        if (muteToggle) {
            muteToggle.checked = !!currentSettings.playbackMuted;
        }
    }

    async function applySelectedSpeakerOutput() {
        const rx = $('remoteOperationRx');
        if (!rx) {
            return;
        }

        const select = $('remoteOperationAudioOut');
        const selectedId = select ? select.value : currentSettings.audioOut;
        const selectedLabel = select && select.selectedOptions && select.selectedOptions.length > 0
            ? select.selectedOptions[0].textContent
            : (selectedId ? 'custom' : 'default');

        quickDiag.speaker = selectedLabel || 'default';
        renderQuickDiagnostics();

        if (!selectedId) {
            currentSettings.audioOut = '';
            writeSettingsToStorage(currentSettings);
            return;
        }

        if (!rx.setSinkId) {
            setDeviceWarning('This browser does not support selecting a specific speaker output.');
            return;
        }

        try {
            await rx.setSinkId(selectedId);
            currentSettings.audioOut = selectedId;
            writeSettingsToStorage(currentSettings);
            setDeviceWarning('');
        } catch (error) {
            setDeviceWarning('Could not switch to the selected speaker output.');
            log('setSinkId failed: ' + String(error && error.message ? error.message : error), 'err');
        }
    }

    async function tryEnableAudioPlayback(triggeredByUser) {
        const rx = $('remoteOperationRx');
        if (!rx) {
            return;
        }

        const ctx = getLevelAudioContext();
        if (ctx && ctx.state === 'suspended') {
            try {
                await ctx.resume();
            } catch (_) {}
        }

        applyPlaybackControlsToAudioElement();

        try {
            await rx.play();
            setAudioUnlockHint('');
        } catch (_) {
            if (!triggeredByUser) {
                setAudioUnlockHint('Browser blocked autoplay. Click Enable Audio.');
            }
        }
    }

    function setButtonState(state) {
        const isConnected = state === 'connected';
        const isConnecting = state === 'connecting';

        ['remoteOperationConnectButton', 'remoteOperationModalConnectButton'].forEach(function(id) {
            const connectButton = $(id);
            if (connectButton) {
                connectButton.disabled = isConnecting;
                connectButton.textContent = isConnected ? 'Disconnect' : (isConnecting ? 'Connecting...' : 'Connect');
                connectButton.className = isConnected ? 'btn btn-sm btn-danger' : 'btn btn-sm btn-primary';
            }
        });
    }

    function isAudioStat(stat) {
        const media = String(stat && (stat.kind || stat.mediaType) ? (stat.kind || stat.mediaType) : '').toLowerCase();
        return media === 'audio';
    }

    function setLevelBar(id, value) {
        const bar = $(id);
        if (!bar) {
            return;
        }

        const safeValue = Math.max(0, Math.min(100, Math.round(value || 0)));
        bar.style.width = safeValue + '%';
        bar.textContent = safeValue + '%';
        bar.setAttribute('aria-valuenow', String(safeValue));
    }

    function resetLevelBars() {
        setLevelBar('remoteOperationMicLevel', 0);
        setLevelBar('remoteOperationRxLevel', 0);
    }

    function getLevelAudioContext() {
        const Ctor = window.AudioContext || window.webkitAudioContext;
        if (!Ctor) {
            return null;
        }

        if (!levelAudioContext) {
            levelAudioContext = new Ctor();
        }

        if (levelAudioContext.state === 'suspended') {
            levelAudioContext.resume().catch(function() {});
        }

        return levelAudioContext;
    }

    function stopLevelMeter(kind) {
        if (levelMeterCleanup[kind]) {
            levelMeterCleanup[kind]();
            levelMeterCleanup[kind] = null;
        }

        if (kind === 'mic') {
            setLevelBar('remoteOperationMicLevel', 0);
        } else if (kind === 'rx') {
            setLevelBar('remoteOperationRxLevel', 0);
        }
    }

    function attachLevelMeter(kind, stream, barId) {
        stopLevelMeter(kind);

        if (!stream) {
            return;
        }

        const audioContext = getLevelAudioContext();
        if (!audioContext) {
            return;
        }

        const analyser = audioContext.createAnalyser();
        analyser.fftSize = 1024;
        analyser.smoothingTimeConstant = 0.7;

        const source = audioContext.createMediaStreamSource(stream);
        source.connect(analyser);

        const samples = new Uint8Array(analyser.fftSize);
        let frameId = null;

        function tick() {
            analyser.getByteTimeDomainData(samples);

            let sum = 0;
            for (let index = 0; index < samples.length; index++) {
                const normalized = (samples[index] - 128) / 128;
                sum += normalized * normalized;
            }

            const rms = Math.sqrt(sum / samples.length);
            const level = Math.min(100, rms * 220);
            setLevelBar(barId, level);
            frameId = window.requestAnimationFrame(tick);
        }

        tick();

        levelMeterCleanup[kind] = function() {
            if (frameId !== null) {
                window.cancelAnimationFrame(frameId);
                frameId = null;
            }

            try {
                source.disconnect();
            } catch (_) {}

            try {
                analyser.disconnect();
            } catch (_) {}
        };
    }

    function readSettingsFromStorage() {
        const settings = Object.assign({}, DEFAULTS);
        settings.wsUrl = localStorage.getItem(STORAGE.wsUrl) || DEFAULTS.wsUrl;
        settings.linkPassword = '';
        settings.linkName = localStorage.getItem(STORAGE.linkName) || '';
        settings.radioName = localStorage.getItem(STORAGE.radioName) || '';
        settings.audioIn = localStorage.getItem(STORAGE.audioIn) || '';
        settings.audioOut = localStorage.getItem(STORAGE.audioOut) || '';
        settings.playbackVolume = Number(localStorage.getItem(STORAGE.playbackVolume));
        if (!Number.isFinite(settings.playbackVolume)) {
            settings.playbackVolume = DEFAULTS.playbackVolume;
        }
        settings.playbackMuted = localStorage.getItem(STORAGE.playbackMuted) === '1';
        settings.stun = localStorage.getItem(STORAGE.stun) || DEFAULTS.stun;
        settings.turnUrl = localStorage.getItem(STORAGE.turnUrl) || '';
        settings.turnUser = localStorage.getItem(STORAGE.turnUser) || '';
        settings.turnPwd = localStorage.getItem(STORAGE.turnPwd) || '';
        settings.minimalMic = localStorage.getItem(STORAGE.minimalMic) === '1';
        settings.preferOpus = localStorage.getItem(STORAGE.preferOpus) === '1';
        settings.playoutHint = localStorage.getItem(STORAGE.playoutHint) !== '0';
        settings.statsPoll = localStorage.getItem(STORAGE.statsPoll) === '1';
        currentSettings = settings;
        return settings;
    }

    function writeSettingsToStorage(settings) {
        localStorage.setItem(STORAGE.wsUrl, settings.wsUrl || DEFAULTS.wsUrl);
        localStorage.setItem(STORAGE.linkName, settings.linkName || '');
        localStorage.setItem(STORAGE.radioName, settings.radioName || '');
        localStorage.setItem(STORAGE.audioIn, settings.audioIn || '');
        localStorage.setItem(STORAGE.audioOut, settings.audioOut || '');
        localStorage.setItem(STORAGE.playbackVolume, String(Math.max(0, Math.min(100, Number(settings.playbackVolume || 0)))));
        localStorage.setItem(STORAGE.playbackMuted, settings.playbackMuted ? '1' : '0');
        localStorage.setItem(STORAGE.stun, settings.stun || DEFAULTS.stun);
        localStorage.setItem(STORAGE.turnUrl, settings.turnUrl || '');
        localStorage.setItem(STORAGE.turnUser, settings.turnUser || '');
        localStorage.setItem(STORAGE.turnPwd, settings.turnPwd || '');
        localStorage.setItem(STORAGE.minimalMic, settings.minimalMic ? '1' : '0');
        localStorage.setItem(STORAGE.preferOpus, settings.preferOpus ? '1' : '0');
        localStorage.setItem(STORAGE.playoutHint, settings.playoutHint ? '1' : '0');
        localStorage.setItem(STORAGE.statsPoll, settings.statsPoll ? '1' : '0');
        currentSettings = settings;
    }

    async function loadRemoteOperationSecretFromAccount() {
        const response = await fetch(base_url + 'index.php/qso/remoteoperationsecret_json', {
            method: 'GET',
            credentials: 'same-origin'
        });

        const data = await response.json();
        if (!data || data.status !== 'ok') {
            throw new Error(data && data.message ? data.message : 'Failed to load remote operation secret');
        }

        currentSettings.linkPassword = typeof data.link_password === 'string' ? data.link_password : '';
        const input = $('remoteOperationLinkPassword');
        if (input) {
            input.value = currentSettings.linkPassword;
        }
    }

    async function saveRemoteOperationSecretToAccount(linkPassword) {
        const body = new URLSearchParams();
        body.set('link_password', linkPassword || '');

        const response = await fetch(base_url + 'index.php/qso/remoteoperationsecret_save', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
            },
            body: body.toString()
        });

        const data = await response.json();
        if (!data || data.status !== 'ok') {
            throw new Error(data && data.message ? data.message : 'Failed to save remote operation secret');
        }

        currentSettings.linkPassword = linkPassword || '';
    }

    function dismissRemoteOperationMarkup() {
        const modalEl = getModal();
        const backdropEl = $('modal-backdrop');

        if (modalEl) {
            modalEl.classList.remove('show');
            modalEl.style.display = 'none';
            modalEl.remove();
        }

        if (backdropEl) {
            backdropEl.classList.remove('show');
            backdropEl.style.display = 'none';
            backdropEl.remove();
        }

        const mountPoint = $('remote-modals-here');
        if (mountPoint) {
            mountPoint.innerHTML = '';
        }
    }

    function parseIceServers(settings) {
        const servers = (settings.stun || DEFAULTS.stun)
            .split(',')
            .map(function(server) { return server.trim(); })
            .filter(Boolean)
            .map(function(url) { return { urls: url }; });

        if (servers.length === 0) {
            servers.push({ urls: DEFAULTS.stun });
        }

        if (settings.turnUrl) {
            servers.push({
                urls: settings.turnUrl,
                username: settings.turnUser || '',
                credential: settings.turnPwd || ''
            });
        }

        return servers;
    }

    function buildMicConstraints(audioConstraint, settings) {
        if (!settings.minimalMic || audioConstraint === true) {
            return { audio: audioConstraint, video: false };
        }

        const proc = {
            echoCancellation: false,
            noiseSuppression: false,
            autoGainControl: false
        };

        const mic = typeof audioConstraint === 'object' && audioConstraint && 'deviceId' in audioConstraint
            ? Object.assign({}, proc, audioConstraint)
            : proc;

        return { audio: mic, video: false };
    }

    function latencyMinimalMicSel() {
        return $('remoteOperationMinimalMicProcessing');
    }

    function applyPlayoutDelayHints(rtpPc, settings) {
        if (!settings.playoutHint) {
            return;
        }

        rtpPc.getTransceivers().forEach(function(transceiver) {
            if (!transceiver.receiver || transceiver.receiver.track?.kind !== 'audio') {
                return;
            }

            try {
                transceiver.receiver.playoutDelayHint = 0;
            } catch (_) {
                // Unsupported in this browser build.
            }
        });
    }

    function preferOpusInOffer(rtpPc) {
        const caps = window.RTCRtpSender && RTCRtpSender.getCapabilities ? RTCRtpSender.getCapabilities('audio') : null;
        const list = caps && caps.codecs;
        if (!list || list.length === 0) {
            return;
        }

        const codecs = list.slice();
        const idx = codecs.findIndex(function(codec) {
            return (codec.mimeType || '').toLowerCase().includes('opus');
        });
        if (idx <= 0) {
            return;
        }

        const opus = codecs.splice(idx, 1)[0];
        codecs.unshift(opus);
        rtpPc.getTransceivers().forEach(function(transceiver) {
            if (!transceiver.sender || !transceiver.sender.track || transceiver.sender.track.kind !== 'audio') {
                return;
            }
            try {
                transceiver.setCodecPreferences(codecs);
            } catch (_) {
                // Keep the default codec order if the browser rejects the list.
            }
        });
    }

    async function populateDevices() {
        const settings = readSettingsFromStorage();
        setDeviceWarning('');
        try {
            await navigator.mediaDevices.getUserMedia({ audio: true, video: false });
        } catch (_) {
            // Enumeration may still work.
        }

        const audioIn = $('remoteOperationAudioIn');
        const audioOut = $('remoteOperationAudioOut');
        if (!audioIn || !audioOut) {
            return;
        }

        audioIn.innerHTML = '';
        audioOut.innerHTML = '';
        const devices = await navigator.mediaDevices.enumerateDevices();
        const inputs = devices.filter(function(device) { return device.kind === 'audioinput'; });
        const outputs = devices.filter(function(device) { return device.kind === 'audiooutput'; });

        inputs.forEach(function(device) {
            const option = document.createElement('option');
            option.value = device.deviceId;
            option.textContent = device.label || ('mic ' + device.deviceId.slice(0, 6));
            audioIn.appendChild(option);
        });

        outputs.forEach(function(device) {
            const option = document.createElement('option');
            option.value = device.deviceId;
            option.textContent = device.label || ('out ' + device.deviceId.slice(0, 6));
            audioOut.appendChild(option);
        });

        if (settings.audioIn) {
            audioIn.value = settings.audioIn;
        }
        if (settings.audioOut) {
            audioOut.value = settings.audioOut;
        }

        const missing = [];
        if (settings.audioIn && audioIn.value !== settings.audioIn) {
            missing.push('mic input');
            if (audioIn.options.length > 0) {
                audioIn.selectedIndex = 0;
            }
            currentSettings.audioIn = audioIn.value || '';
        }
        if (settings.audioOut && audioOut.value !== settings.audioOut) {
            missing.push('speaker output');
            if (audioOut.options.length > 0) {
                audioOut.selectedIndex = 0;
            }
            currentSettings.audioOut = audioOut.value || '';
        }

        if (missing.length > 0) {
            setDeviceWarning('Saved ' + missing.join(' and ') + ' not found. Falling back to current defaults.');
            writeSettingsToStorage(currentSettings);
        }

        await applySelectedSpeakerOutput();

        log('Device list refreshed.', 'ok');
    }

    function syncCardButtons() {
        const connected = !!(pc && pc.connectionState && pc.connectionState !== 'closed');
        setButtonState(connected ? 'connected' : 'disconnected');
    }

    function stopRxLevelPolling() {
        if (rxLevelTimer !== undefined) {
            clearInterval(rxLevelTimer);
            rxLevelTimer = undefined;
        }

        rxLevelSample.energy = null;
        rxLevelSample.duration = null;
    }

    function stopAudioWatchdog() {
        if (audioWatchdogTimer !== undefined) {
            clearInterval(audioWatchdogTimer);
            audioWatchdogTimer = undefined;
        }
        lastInboundPacketTs = 0;
        lastInboundPacketCount = 0;
    }

    function startAudioWatchdog() {
        stopAudioWatchdog();
        lastInboundPacketTs = Date.now();
        audioWatchdogTimer = setInterval(function() {
            if (!pc || pc.connectionState !== 'connected') {
                return;
            }

            if (Date.now() - lastInboundPacketTs > 8000) {
                setOperationalState('noaudio');
            }
        }, 3000);
    }

    async function sampleRxLevel() {
        if (!pc) {
            setLevelBar('remoteOperationRxLevel', 0);
            return;
        }

        const stats = await pc.getStats();
        let audioLevel = null;
        let totalAudioEnergy = null;
        let totalSamplesDuration = null;
        let packetsReceived = null;
        let jitterMs = null;

        stats.forEach(function(r) {
            const isInboundAudio = (r.type === 'inbound-rtp' || r.type === 'track') && isAudioStat(r);
            if (!isInboundAudio) {
                return;
            }

            if (audioLevel == null && Number.isFinite(Number(r.audioLevel))) {
                audioLevel = Number(r.audioLevel);
            }

            if (totalAudioEnergy == null && Number.isFinite(Number(r.totalAudioEnergy))) {
                totalAudioEnergy = Number(r.totalAudioEnergy);
            }

            if (totalSamplesDuration == null && Number.isFinite(Number(r.totalSamplesDuration))) {
                totalSamplesDuration = Number(r.totalSamplesDuration);
            }

            if (packetsReceived == null && Number.isFinite(Number(r.packetsReceived))) {
                packetsReceived = Number(r.packetsReceived);
            }

            if (jitterMs == null && Number.isFinite(Number(r.jitter))) {
                jitterMs = Number(r.jitter) * 1000;
            }

            if (jitterMs == null && r.jitterBufferDelay != null && r.jitterBufferEmittedCount) {
                const emitted = Number(r.jitterBufferEmittedCount);
                if (emitted > 0) {
                    jitterMs = (Number(r.jitterBufferDelay) / emitted) * 1000;
                }
            }
        });

        if (Number.isFinite(packetsReceived)) {
            if (packetsReceived > lastInboundPacketCount) {
                lastInboundPacketCount = packetsReceived;
                lastInboundPacketTs = Date.now();
                if (currentStatusState === 'noaudio') {
                    setOperationalState('media');
                }
            }
            quickDiag.inboundPackets = Math.round(packetsReceived);
            quickDiag.jitterMs = Number.isFinite(jitterMs) ? jitterMs.toFixed(2) + ' ms' : 'n/a';
            renderQuickDiagnostics();
        }

        if (audioLevel != null) {
            setLevelBar('remoteOperationRxLevel', Math.min(100, audioLevel * 140));
            return;
        }

        if (totalAudioEnergy == null || totalSamplesDuration == null) {
            setLevelBar('remoteOperationRxLevel', 0);
            return;
        }

        if (rxLevelSample.energy == null || rxLevelSample.duration == null) {
            rxLevelSample.energy = totalAudioEnergy;
            rxLevelSample.duration = totalSamplesDuration;
            setLevelBar('remoteOperationRxLevel', 0);
            return;
        }

        const deltaEnergy = totalAudioEnergy - rxLevelSample.energy;
        const deltaDuration = totalSamplesDuration - rxLevelSample.duration;

        rxLevelSample.energy = totalAudioEnergy;
        rxLevelSample.duration = totalSamplesDuration;

        if (!(deltaEnergy > 0) || !(deltaDuration > 0)) {
            setLevelBar('remoteOperationRxLevel', 0);
            return;
        }

        const rms = Math.sqrt(deltaEnergy / deltaDuration);
        setLevelBar('remoteOperationRxLevel', Math.min(100, rms * 160));
    }

    function startRxLevelPolling() {
        stopRxLevelPolling();
        rxLevelTimer = setInterval(function() {
            sampleRxLevel().catch(function() {});
        }, 250);
        sampleRxLevel().catch(function() {});
    }

    function stopStatsPolling() {
        if (statsTimer !== undefined) {
            clearInterval(statsTimer);
            statsTimer = undefined;
        }
    }

    function startStatsPolling() {
        stopStatsPolling();
        statsTimer = setInterval(function() {
            collectAndRenderStats(false).catch(function() {});
        }, 2000);
    }

    async function collectAndRenderStats(logAnomalies) {
        const box = $('remoteOperationStats');
        if (!pc || !box) {
            return;
        }

        const stats = await pc.getStats();
        let connState = pc.connectionState;
        let iceState = pc.iceConnectionState;
        let iceRttMs = null;
        let mediaRttMs = null;
        const byId = new Map();

        stats.forEach(function(r) {
            byId.set(r.id, r);
        });

        stats.forEach(function(r) {
            if (r.type === 'candidate-pair') {
                const ok = r.state === 'succeeded' || r.state === 'in-progress';
                if (ok && (r.currentRoundTripTime != null || r.totalRoundTripTime != null)) {
                    const seconds = r.currentRoundTripTime != null ? Number(r.currentRoundTripTime) : Number(r.totalRoundTripTime);
                    if (Number.isFinite(seconds) && seconds > 0) {
                        const ms = seconds * 1000;
                        if (r.nominated || iceRttMs == null || ms < iceRttMs) {
                            iceRttMs = ms;
                        }
                    }
                }
            }

            if (r.type === 'remote-inbound-rtp' && isAudioStat(r) && r.roundTripTime != null) {
                const ms = Number(r.roundTripTime) * 1000;
                if (Number.isFinite(ms) && ms > 0) {
                    mediaRttMs = ms;
                }
            }
        });

        const lines = [];
        lines.push('connectionState=' + connState + '  iceConnectionState=' + iceState);
        lines.push('ICE RTT(best pair)≈ ' + (iceRttMs != null ? iceRttMs.toFixed(0) + ' ms' : 'n/a') + '  SRTP-remote-inbound roundTrip≈ ' + (mediaRttMs != null ? mediaRttMs.toFixed(0) + ' ms' : 'n/a'));

        stats.forEach(function(r) {
            if (r.type === 'outbound-rtp' && isAudioStat(r)) {
                const codecStat = r.codecId ? byId.get(r.codecId) : null;
                const codecHint = codecStat && codecStat.mimeType ? codecStat.mimeType : '(see codec stat id)';
                lines.push('→ Shack outbound-audio packetsSent=' + (r.packetsSent != null ? r.packetsSent : '—') + ' codec=' + codecHint);
            }
        });

        stats.forEach(function(r) {
            if (r.type === 'inbound-rtp' && isAudioStat(r)) {
                const codecStat = r.codecId ? byId.get(r.codecId) : null;
                const codecHint = codecStat && codecStat.mimeType ? codecStat.mimeType : '—';
                const received = r.packetsReceived != null ? Number(r.packetsReceived) : 0;
                const lost = r.packetsLost != null ? Number(r.packetsLost) : 0;
                const jitterS = r.jitter != null ? Number(r.jitter) : null;
                let jbMs = null;
                if (r.jitterBufferDelay != null && r.jitterBufferEmittedCount) {
                    const emitted = Number(r.jitterBufferEmittedCount);
                    if (emitted > 0) {
                        jbMs = (Number(r.jitterBufferDelay) / emitted) * 1000;
                    }
                }

                lines.push('← Shack inbound-audio packetsReceived≈ ' + Math.round(received) + ' packetsLost(total)=' + lost);
                lines.push('   jitter ' + (jitterS != null ? (jitterS * 1000).toFixed(2) + ' ms' : 'n/a') + '; jitter-buffer smoothing≈ ' + (jbMs != null ? jbMs.toFixed(1) + ' ms/item' : 'n/a'));
                lines.push('   decoder codec ' + codecHint);

                quickDiag.inboundPackets = Math.round(received);
                quickDiag.jitterMs = jitterS != null ? (jitterS * 1000).toFixed(2) + ' ms' : 'n/a';
                renderQuickDiagnostics();

                const lossFrac = received + lost > 0 ? lost / (received + lost) : 0;
                if (logAnomalies && lossFrac > 0.03 && lost > 5) {
                    log('Stats: inbound packet loss spike totalLost=' + lost, 'err');
                }
            }
        });

        box.textContent = lines.join('\n');
        const refreshButton = $('remoteOperationStatsRefresh');
        if (refreshButton) {
            refreshButton.disabled = false;
        }
    }

    async function flushPendingRemoteIceCandidates() {
        if (!pc || pendingRemoteIce.length === 0) {
            return;
        }

        const batch = pendingRemoteIce;
        pendingRemoteIce = [];
        log('Applying ' + batch.length + ' queued remote ICE candidate(s).', 'ok');
        for (const init of batch) {
            try {
                await pc.addIceCandidate(init);
            } catch (e) {
                log('addIceCandidate (queued): ' + e.message, 'err');
            }
        }
    }

    async function startCall() {
        if (pc) {
            return;
        }

        pendingRemoteIce = [];
        mediaStream = null;
        pc = new RTCPeerConnection({ iceServers: parseIceServers(currentSettings) });

        const micId = $('remoteOperationAudioIn') ? $('remoteOperationAudioIn').value : currentSettings.audioIn;
        const micDev = micId ? { deviceId: { exact: micId } } : true;
        let stream;
        try {
            stream = await navigator.mediaDevices.getUserMedia(buildMicConstraints(micDev, currentSettings));
        } catch (error) {
            if (micId) {
                setDeviceWarning('Saved mic input is unavailable. Falling back to default microphone.');
                log('Mic device selection failed, retrying with browser default input.', 'err');
                currentSettings.audioIn = '';
                writeSettingsToStorage(currentSettings);
                stream = await navigator.mediaDevices.getUserMedia(buildMicConstraints(true, currentSettings));
            } else {
                throw error;
            }
        }
        mediaStream = stream;
        attachLevelMeter('mic', stream, 'remoteOperationMicLevel');
        stream.getTracks().forEach(function(track) {
            pc.addTrack(track, stream);
        });

        if (currentSettings.preferOpus) {
            preferOpusInOffer(pc);
        }

        pc.onconnectionstatechange = function() {
            syncCardButtons();
            if (pc.connectionState === 'connected') {
                setOperationalState('paired');
            } else if (pc.connectionState === 'failed') {
                setOperationalState('failed');
                setButtonState('disconnected');
            }
        };

        pc.ontrack = function(ev) {
            const rx = $('remoteOperationRx');
            if (rx) {
                rx.srcObject = ev.streams[0];
            }

            applySelectedSpeakerOutput().catch(function() {});
            startRxLevelPolling();
            startAudioWatchdog();
            applyPlaybackControlsToAudioElement();
            tryEnableAudioPlayback(false).catch(function() {});

            applyPlayoutDelayHints(pc, currentSettings);
            setOperationalState('media');
            setButtonState('connected');
            if (currentSettings.statsPoll || ($('remoteOperationStatsPoll') && $('remoteOperationStatsPoll').checked)) {
                startStatsPolling();
            }
            collectAndRenderStats(false).catch(function() {});
        };

        pc.onicecandidate = function(ev) {
            if (!ws || ws.readyState !== WebSocket.OPEN) {
                return;
            }

            if (ev.candidate) {
                send(ws, {
                    type: 'candidate',
                    candidate: ev.candidate.candidate,
                    sdpMid: ev.candidate.sdpMid,
                    sdpMLineIndex: ev.candidate.sdpMLineIndex
                });
            } else {
                send(ws, { type: 'candidate-end' });
            }
        };

        const offer = await pc.createOffer({ offerToReceiveAudio: true, offerToReceiveVideo: false });
        await pc.setLocalDescription(offer);
        const radioName = $('remoteOperationRadioName') ? $('remoteOperationRadioName').value.trim() : '';
        const payload = { type: 'offer', sdp: offer.sdp };
        if (radioName) {
            payload.radioName = radioName;
        }
        send(ws, payload);
        log((radioName ? ('Sent offer for radio "' + radioName + '".') : 'Sent offer (first remote-ready radio).'), 'ok');
    }

    function send(wsObj, obj) {
        if (wsObj && wsObj.readyState === WebSocket.OPEN) {
            wsObj.send(JSON.stringify(obj));
        }
    }

    async function onSignalPayload(obj) {
        if (obj.type === 'error') {
            const message = typeof obj.message === 'string' ? obj.message : '';
            const hint = message === 'audio_device_mapping_failed'
                ? ' On Aurora: open Radio Settings, refresh devices, re-pick Radio Output / Radio Input, and save.'
                : (message === 'no_radio_configured' ? ' On Aurora: enable internet remote audio on a radio and set both Radio Output & Radio Input.' : '');
            const nameHint = (message === 'unknown_radio_name' && obj.radioName !== undefined && obj.radioName !== null)
                ? ' No row named "' + String(obj.radioName) + '" with internet remote audio + both devices. Match the radio name exactly.'
                : '';
            log('Shack signaled error: ' + (message || JSON.stringify(obj)) + hint + nameHint, 'err');
            return;
        }

        if (!pc) {
            return;
        }

        if (obj.type === 'answer' && typeof obj.sdp === 'string') {
            await pc.setRemoteDescription({ type: 'answer', sdp: obj.sdp });
            applyPlayoutDelayHints(pc, currentSettings);
            log('Set remote answer.', 'ok');
            await flushPendingRemoteIceCandidates();
            return;
        }

        if (obj.type === 'candidate' && typeof obj.candidate === 'string' && obj.candidate.length) {
            const init = {
                candidate: obj.candidate,
                sdpMid: obj.sdpMid === undefined || obj.sdpMid === '' ? null : obj.sdpMid,
                sdpMLineIndex: obj.sdpMLineIndex ?? 0
            };
            if (!pc.remoteDescription) {
                pendingRemoteIce.push(init);
                return;
            }
            try {
                await pc.addIceCandidate(init);
            } catch (e) {
                log('addIceCandidate: ' + e.message, 'err');
            }
            return;
        }

        if (obj.type === 'candidate-end') {
            log('Peer candidate gathering done.', 'ok');
            return;
        }

        log('Unknown signal ' + JSON.stringify(obj), 'err');
    }

    async function dispatchSignalingMessage(ev) {
        let obj;
        try {
            obj = JSON.parse(ev.data);
        } catch (_) {
            log('Bad JSON from relay: ' + ev.data, 'err');
            return;
        }

        if (obj.op === 'webrtc-signaling-joined') {
            log('Joined signalling as ' + obj.role + ' link name=' + obj.sessionId, 'ok');
            return;
        }

        if (obj.op === 'webrtc-signaling-paired') {
            log('Paired with peer role=' + obj.with, 'ok');
            setOperationalState('paired');
            await startCall();
            return;
        }

        if (obj.op === 'webrtc-signaling-peer-left') {
            log('Peer left.', 'err');
            teardown(obj.reason || 'peer_left');
            scheduleReconnect(obj.reason || 'peer_left');
            return;
        }

        await onSignalPayload(obj);
    }

    function disconnectWebSocket() {
        if (ws !== null) {
            ws.close();
        }
    }

    function teardown(reason) {
        pendingRemoteIce = [];
        stopStatsPolling();
        stopRxLevelPolling();
        stopAudioWatchdog();
        clearReconnectTimer();
        stopLevelMeter('mic');
        stopLevelMeter('rx');
        if ($('remoteOperationStatsRefresh')) {
            $('remoteOperationStatsRefresh').disabled = true;
        }
        if ($('remoteOperationStats')) {
            $('remoteOperationStats').textContent = '(disconnected)';
        }

        if (pc) {
            if (pc.getSenders) {
                pc.getSenders().forEach(function(sender) {
                    try {
                        if (sender.track) {
                            sender.track.stop();
                        }
                    } catch (_) {}
                });
            }
            pc.close();
            pc = null;
        }

        if (mediaStream) {
            mediaStream.getTracks().forEach(function(track) {
                try {
                    track.stop();
                } catch (_) {}
            });
            mediaStream = null;
        }

        if (ws && ws.readyState === WebSocket.OPEN) {
            if (manualDisconnectRequested) {
                ws.onclose = null;
            }
            ws.close();
        }
        ws = null;
        log('Teardown ' + reason, 'err');
        setOperationalState('disconnected');
        setButtonState('disconnected');
        if ($('remoteOperationRx')) {
            $('remoteOperationRx').srcObject = null;
        }
        setAudioUnlockHint('');
        resetLevelBars();
        syncCardButtons();
    }

    async function connectRemoteOperation(isReconnectAttempt) {
        clearReconnectTimer();
        if (!isReconnectAttempt) {
            reconnectAttempt = 0;
            shouldAutoReconnect = true;
        }

        if (ws !== null) {
            ws.close();
        }

        currentSettings = readSettingsFromStorage();

        try {
            await loadRemoteOperationSecretFromAccount();
        } catch (error) {
            shouldAutoReconnect = false;
            alert('Could not load link password from your account.');
            log('Remote Operation link password load failed: ' + String(error && error.message ? error.message : error), 'err');
            return;
        }

        if (!/^wss?:\/\//i.test(currentSettings.wsUrl)) {
            shouldAutoReconnect = false;
            alert('WebSocket URL must start with ws:// or wss://');
            return;
        }

        if (!currentSettings.linkPassword || currentSettings.linkPassword.length < 16) {
            shouldAutoReconnect = false;
            alert('Link password must be at least 16 characters.');
            openRemoteOperationSettings();
            return;
        }

        if (!currentSettings.linkName) {
            shouldAutoReconnect = false;
            alert('Enter a link name.');
            openRemoteOperationSettings();
            return;
        }

        setOperationalState('connecting');
        setButtonState('connecting');
        log('Connecting to ' + currentSettings.wsUrl);

        wsInboundChain = Promise.resolve();
        ws = new WebSocket(currentSettings.wsUrl);

        ws.onopen = function() {
            manualDisconnectRequested = false;
            reconnectAttempt = 0;
            send(ws, {
                op: 'webrtc-signaling-join',
                mediaToken: currentSettings.linkPassword,
                sessionId: currentSettings.linkName,
                role: 'remote'
            });
            log('WS open, sent join.', 'ok');
        };

        ws.onmessage = function(ev) {
            wsInboundChain = wsInboundChain
                .then(function() {
                    return dispatchSignalingMessage(ev);
                })
                .catch(function(e) {
                    log(String(e && e.message ? e.message : e), 'err');
                });
        };

        ws.onerror = function() {
            log('WS error', 'err');
        };

        ws.onclose = function() {
            log('WS closed', 'err');
            const wasManual = manualDisconnectRequested;
            manualDisconnectRequested = false;
            ws = null;
            teardown('ws_close');
            if (!wasManual) {
                scheduleReconnect('ws_close');
            }
        };
    }

    function getModal() {
        return $('remoteOperationModal') || $('modal');
    }

    function toggleRemoteOperationConnection() {
        const activeConnection = (pc && pc.connectionState && pc.connectionState !== 'closed') || (ws && ws.readyState !== WebSocket.CLOSED);
        if (activeConnection) {
            shouldAutoReconnect = false;
            manualDisconnectRequested = true;
            teardown('user');
            return;
        }

        connectRemoteOperation(false);
    }

    function populateModalFromSettings() {
        const settings = readSettingsFromStorage();
        const mapping = {
            remoteOperationWsUrl: settings.wsUrl,
            remoteOperationLinkName: settings.linkName,
            remoteOperationRadioName: settings.radioName,
            remoteOperationStun: settings.stun,
            remoteOperationTurnUrl: settings.turnUrl,
            remoteOperationTurnUser: settings.turnUser,
            remoteOperationTurnPwd: settings.turnPwd
        };

        Object.keys(mapping).forEach(function(id) {
            const el = $(id);
            if (el) {
                el.value = mapping[id];
            }
        });

        const minimalMic = $('remoteOperationMinimalMicProcessing');
        const preferOpus = $('remoteOperationPreferOpus');
        const playoutHint = $('remoteOperationPlayoutDelayHint');
        const statsPoll = $('remoteOperationStatsPoll');
        if (minimalMic) {
            minimalMic.checked = settings.minimalMic;
        }
        if (preferOpus) {
            preferOpus.checked = settings.preferOpus;
        }
        if (playoutHint) {
            playoutHint.checked = settings.playoutHint;
        }
        if (statsPoll) {
            statsPoll.checked = settings.statsPoll;
        }

        syncPlaybackControlsToUi();
        applyPlaybackControlsToAudioElement();

        const modalStatus = $('remoteOperationModalStatus');
        if (modalStatus) {
            modalStatus.textContent = 'Saved settings';
            modalStatus.className = 'badge bg-secondary';
        }

        loadRemoteOperationSecretFromAccount().catch(function(error) {
            log('Could not load account link password: ' + String(error && error.message ? error.message : error), 'err');
        });
    }

    function resetRemoteOperationSettings() {
        Object.keys(STORAGE).forEach(function(key) {
            localStorage.removeItem(STORAGE[key]);
        });

        currentSettings = Object.assign({}, DEFAULTS);
        writeSettingsToStorage(currentSettings);
        syncPlaybackControlsToUi();
        applyPlaybackControlsToAudioElement();
        populateModalFromSettings();
        populateDevices().catch(function(error) {
            log(String(error && error.message ? error.message : error), 'err');
        });
        saveRemoteOperationSecretToAccount('').catch(function(error) {
            log('Could not clear account link password: ' + String(error && error.message ? error.message : error), 'err');
        });
        setDeviceWarning('Remote settings reset to defaults for this browser.');
        log('Remote Operation settings reset.', 'ok');
    }

    async function saveRemoteOperationSettings() {
        const saveButton = $('remoteOperationSaveButton');
        const modalStatus = $('remoteOperationModalStatus');
        const config = {
            wsUrl: $('remoteOperationWsUrl') ? $('remoteOperationWsUrl').value.trim() : DEFAULTS.wsUrl,
            linkPassword: $('remoteOperationLinkPassword') ? $('remoteOperationLinkPassword').value.trim() : '',
            linkName: $('remoteOperationLinkName') ? $('remoteOperationLinkName').value.trim() : '',
            radioName: $('remoteOperationRadioName') ? $('remoteOperationRadioName').value.trim() : '',
            audioIn: $('remoteOperationAudioIn') ? $('remoteOperationAudioIn').value : '',
            audioOut: $('remoteOperationAudioOut') ? $('remoteOperationAudioOut').value : '',
            playbackVolume: $('remoteOperationPlaybackVolume') ? Number($('remoteOperationPlaybackVolume').value) : DEFAULTS.playbackVolume,
            playbackMuted: !!($('remoteOperationMuteToggle') && $('remoteOperationMuteToggle').checked),
            stun: $('remoteOperationStun') ? $('remoteOperationStun').value.trim() : DEFAULTS.stun,
            turnUrl: $('remoteOperationTurnUrl') ? $('remoteOperationTurnUrl').value.trim() : '',
            turnUser: $('remoteOperationTurnUser') ? $('remoteOperationTurnUser').value.trim() : '',
            turnPwd: $('remoteOperationTurnPwd') ? $('remoteOperationTurnPwd').value : '',
            minimalMic: !!($('remoteOperationMinimalMicProcessing') && $('remoteOperationMinimalMicProcessing').checked),
            preferOpus: !!($('remoteOperationPreferOpus') && $('remoteOperationPreferOpus').checked),
            playoutHint: !!($('remoteOperationPlayoutDelayHint') && $('remoteOperationPlayoutDelayHint').checked),
            statsPoll: !!($('remoteOperationStatsPoll') && $('remoteOperationStatsPoll').checked)
        };

        if (!/^wss?:\/\//i.test(config.wsUrl)) {
            alert('WebSocket URL must start with ws:// or wss://');
            return;
        }

        if (config.linkPassword && config.linkPassword.length < 16) {
            alert('Link password must be at least 16 characters.');
            return;
        }

        if (!config.linkName) {
            alert('A link name is required.');
            return;
        }

        if (!config.stun) {
            config.stun = DEFAULTS.stun;
        }

        if (saveButton) {
            saveButton.disabled = true;
            saveButton.textContent = 'Saving...';
        }

        try {
            await saveRemoteOperationSecretToAccount(config.linkPassword);
        } catch (error) {
            if (saveButton) {
                saveButton.disabled = false;
                saveButton.textContent = 'Save settings';
            }
            if (modalStatus) {
                modalStatus.textContent = 'Save failed';
                modalStatus.className = 'badge bg-danger';
            }
            alert('Could not save link password to your account.');
            log('Remote Operation link password save failed: ' + String(error && error.message ? error.message : error), 'err');
            return;
        }

        writeSettingsToStorage(config);
        applyPlaybackControlsToAudioElement();
        applySelectedSpeakerOutput().catch(function() {});
        if (modalStatus) {
            modalStatus.textContent = 'Saved';
            modalStatus.className = 'badge bg-success';
        }
        if (saveButton) {
            saveButton.textContent = 'Saved';
        }
        log('Remote Operation settings saved.', 'ok');

        window.setTimeout(function() {
            if (saveButton) {
                saveButton.disabled = false;
                saveButton.textContent = 'Save settings';
            }
            dismissRemoteOperationMarkup();
        }, 600);
    }

    function closeRemoteOperationModal() {
        dismissRemoteOperationMarkup();
    }

    function openRemoteOperationSettings() {
        const modalEl = getModal();
        if (!modalEl) {
            const settingsButton = $('remoteOperationSettingsButton');
            if (settingsButton) {
                settingsButton.click();
            }
            return;
        }

        populateModalFromSettings();

        if (modalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        }
    }

    function refreshRemoteOperationStats() {
        const btn = $('remoteOperationStatsRefresh');
        if (btn) {
            btn.disabled = true;
        }
        collectAndRenderStats(true).catch(function() {
            if (btn) {
                btn.disabled = false;
            }
        });
    }

    function bindModalEvents() {
        const modalEl = getModal();
        if (!modalEl || modalEl.getAttribute('data-remote-operation-bound') === '1') {
            return;
        }

        const saveButton = $('remoteOperationSaveButton');
        const resetButton = $('remoteOperationResetSettingsButton');
        const statsRefresh = $('remoteOperationStatsRefresh');
        const closeButton = $('remoteOperationCloseButton');
        const enumerateButton = $('remoteOperationEnumerateDevices');
        const audioOut = $('remoteOperationAudioOut');
        const audioIn = $('remoteOperationAudioIn');

        if (enumerateButton) {
            enumerateButton.addEventListener('click', function() {
                populateDevices().catch(function(error) {
                    log(String(error && error.message ? error.message : error), 'err');
                });
            });
        }
        if (saveButton) {
            saveButton.addEventListener('click', saveRemoteOperationSettings);
        }
        if (resetButton) {
            resetButton.addEventListener('click', resetRemoteOperationSettings);
        }
        if (statsRefresh) {
            statsRefresh.addEventListener('click', refreshRemoteOperationStats);
        }
        if (closeButton) {
            closeButton.addEventListener('click', closeRemoteOperationModal);
        }
        if (audioOut) {
            audioOut.addEventListener('change', function() {
                currentSettings.audioOut = audioOut.value;
                writeSettingsToStorage(currentSettings);
                applySelectedSpeakerOutput().catch(function() {});
            });
        }
        if (audioIn) {
            audioIn.addEventListener('change', function() {
                currentSettings.audioIn = audioIn.value;
                writeSettingsToStorage(currentSettings);
            });
        }

        modalEl.setAttribute('data-remote-operation-bound', '1');
    }

    function bindEvents() {
        const modalEl = getModal();
        const volumeInput = $('remoteOperationPlaybackVolume');
        const muteToggle = $('remoteOperationMuteToggle');
        const unlockButton = $('remoteOperationEnableAudioButton');

        ['remoteOperationConnectButton', 'remoteOperationModalConnectButton'].forEach(function(id) {
            const connectButton = $(id);
            if (connectButton) {
                connectButton.addEventListener('click', toggleRemoteOperationConnection);
            }
        });

        if (volumeInput) {
            volumeInput.addEventListener('input', function() {
                currentSettings.playbackVolume = Number(volumeInput.value);
                writeSettingsToStorage(currentSettings);
                applyPlaybackControlsToAudioElement();
            });
        }

        if (muteToggle) {
            muteToggle.addEventListener('change', function() {
                currentSettings.playbackMuted = !!muteToggle.checked;
                writeSettingsToStorage(currentSettings);
                applyPlaybackControlsToAudioElement();
            });
        }

        if (unlockButton) {
            unlockButton.addEventListener('click', function() {
                tryEnableAudioPlayback(true).catch(function() {});
            });
        }

        if (modalEl) {
            modalEl.addEventListener('shown.bs.modal', function() {
                populateModalFromSettings();
                bindModalEvents();
                populateDevices().catch(function(error) {
                    log(String(error && error.message ? error.message : error), 'err');
                });
            });
        }

        if (document.body) {
            document.body.addEventListener('htmx:afterOnLoad', function(evt) {
                const detail = evt && evt.detail ? evt.detail : null;
                const target = detail && detail.target ? detail.target : null;
                if (target && target.id === 'remote-modals-here') {
                    populateModalFromSettings();
                    populateDevices().catch(function(error) {
                        log(String(error && error.message ? error.message : error), 'err');
                    });
                    bindModalEvents();
                }
            });
        }
    }

    function init() {
        readSettingsFromStorage();
        bindEvents();
        setOperationalState('disconnected');
        setButtonState('disconnected');
        syncPlaybackControlsToUi();
        applyPlaybackControlsToAudioElement();
        quickDiag.reconnects = reconnectCount;
        renderQuickDiagnostics();
        resetLevelBars();
        const statsPoll = $('remoteOperationStatsPoll');
        if (statsPoll) {
            statsPoll.checked = currentSettings.statsPoll;
        }
        if ($('remoteOperationStats')) {
            $('remoteOperationStats').textContent = '(run a call - stats appear after media flows)';
        }

        loadRemoteOperationSecretFromAccount().catch(function(error) {
            log('Could not load account link password: ' + String(error && error.message ? error.message : error), 'err');
        });
    }

    window.openRemoteOperationSettings = openRemoteOperationSettings;
    window.closeRemoteOperationModal = closeRemoteOperationModal;
    window.saveRemoteOperationSettings = saveRemoteOperationSettings;
    window.connectRemoteOperation = connectRemoteOperation;
    window.refreshRemoteOperationStats = refreshRemoteOperationStats;
    window.populateRemoteOperationDevices = function() {
        return populateDevices();
    };

    document.addEventListener('DOMContentLoaded', init);
})();
