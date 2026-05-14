(function() {
    const CW_MAP = {
        'A': '.-', 'B': '-...', 'C': '-.-.', 'D': '-..', 'E': '.', 'F': '..-.', 'G': '--.',
        'H': '....', 'I': '..', 'J': '.---', 'K': '-.-', 'L': '.-..', 'M': '--', 'N': '-.',
        'O': '---', 'P': '.--.', 'Q': '--.-', 'R': '.-.', 'S': '...', 'T': '-', 'U': '..-',
        'V': '...-', 'W': '.--', 'X': '-..-', 'Y': '-.--', 'Z': '--..',
        '0': '-----', '1': '.----', '2': '..---', '3': '...--', '4': '....-',
        '5': '.....', '6': '-....', '7': '--...', '8': '---..', '9': '----.',
        '/': '-..-.', '?': '..--..', '.': '.-.-.-', ',': '--..--', '=': '-...-',
        '+': '.-.-.', '-': '-....-', '@': '.--.-.', ':': '---...', ';': '-.-.-.',
        '(': '-.--.', ')': '-.--.-', '\'': '.----.'
    };

    const state = {
        audioContext: null,
        activeOscillator: null,
        enabled: false,
        frequency: 600,
        volume: 0.05,
    };

    function getElement(id) {
        return document.getElementById(id);
    }

    function updateFrequencyLabel() {
        const label = getElement('cwSidetoneFrequencyValue');
        if (label) {
            label.textContent = state.frequency + ' Hz';
        }
    }

    function loadSettings() {
        const enabled = localStorage.getItem('cloudlog.cwSidetone.enabled');
        const frequency = parseInt(localStorage.getItem('cloudlog.cwSidetone.frequency') || '600', 10);

        state.enabled = enabled === '1';
        state.frequency = Number.isFinite(frequency) ? Math.max(300, Math.min(1000, frequency)) : 600;
    }

    function persistSettings() {
        localStorage.setItem('cloudlog.cwSidetone.enabled', state.enabled ? '1' : '0');
        localStorage.setItem('cloudlog.cwSidetone.frequency', String(state.frequency));
    }

    function initControls() {
        loadSettings();

        const enabledInput = getElement('cwSidetoneEnabled');
        const frequencyInput = getElement('cwSidetoneFrequency');

        if (enabledInput) {
            enabledInput.checked = state.enabled;
            enabledInput.addEventListener('change', function() {
                state.enabled = enabledInput.checked;
                persistSettings();
            });
        }

        if (frequencyInput) {
            frequencyInput.value = String(state.frequency);
            frequencyInput.addEventListener('input', function() {
                const value = parseInt(frequencyInput.value, 10);
                if (Number.isFinite(value)) {
                    state.frequency = value;
                    updateFrequencyLabel();
                    persistSettings();
                }
            });
        }

        updateFrequencyLabel();
    }

    function getAudioContext() {
        if (!window.AudioContext && !window.webkitAudioContext) {
            return null;
        }

        if (!state.audioContext) {
            const ContextCtor = window.AudioContext || window.webkitAudioContext;
            state.audioContext = new ContextCtor();
        }

        if (state.audioContext.state === 'suspended') {
            state.audioContext.resume();
        }

        return state.audioContext;
    }

    function appendSegment(segments, on, duration) {
        if (duration <= 0) {
            return;
        }

        const previous = segments[segments.length - 1];
        if (previous && previous.on === on) {
            previous.duration += duration;
        } else {
            segments.push({ on: on, duration: duration });
        }
    }

    function buildTimeline(text, wpm) {
        const cleanText = (text || '').toUpperCase();
        const dot = 1.2 / Math.max(5, parseInt(wpm || '20', 10));
        const segments = [];

        for (let i = 0; i < cleanText.length; i++) {
            const ch = cleanText.charAt(i);
            if (ch === ' ') {
                appendSegment(segments, false, dot * 7);
                continue;
            }

            const pattern = CW_MAP[ch];
            if (!pattern) {
                appendSegment(segments, false, dot * 3);
                continue;
            }

            for (let j = 0; j < pattern.length; j++) {
                const symbol = pattern.charAt(j);
                appendSegment(segments, true, symbol === '-' ? dot * 3 : dot);

                if (j < pattern.length - 1) {
                    appendSegment(segments, false, dot);
                }
            }

            const nextChar = cleanText.charAt(i + 1);
            if (nextChar && nextChar !== ' ') {
                appendSegment(segments, false, dot * 3);
            }
        }

        return segments;
    }

    function stopCurrentPlayback() {
        if (state.activeOscillator) {
            try {
                state.activeOscillator.stop();
            } catch (e) {
                // Ignore stop errors when already stopped.
            }
            state.activeOscillator = null;
        }
    }

    function playText(text, wpm) {
        if (!state.enabled) {
            return;
        }

        const ctx = getAudioContext();
        if (!ctx) {
            return;
        }

        const segments = buildTimeline(text, wpm);
        if (segments.length === 0) {
            return;
        }

        stopCurrentPlayback();

        const oscillator = ctx.createOscillator();
        const gain = ctx.createGain();

        oscillator.type = 'sine';
        oscillator.frequency.setValueAtTime(state.frequency, ctx.currentTime);
        gain.gain.setValueAtTime(0.0001, ctx.currentTime);

        oscillator.connect(gain);
        gain.connect(ctx.destination);

        let t = ctx.currentTime + 0.01;
        for (let i = 0; i < segments.length; i++) {
            const segment = segments[i];
            if (segment.on) {
                gain.gain.setValueAtTime(0.0001, t);
                gain.gain.exponentialRampToValueAtTime(state.volume, t + 0.004);
                t += segment.duration;
                gain.gain.setValueAtTime(state.volume, t);
                gain.gain.exponentialRampToValueAtTime(0.0001, t + 0.004);
            } else {
                t += segment.duration;
            }
        }

        oscillator.start(ctx.currentTime);
        oscillator.stop(t + 0.02);
        oscillator.onended = function() {
            if (state.activeOscillator === oscillator) {
                state.activeOscillator = null;
            }
        };
        state.activeOscillator = oscillator;
    }

    document.addEventListener('DOMContentLoaded', initControls);

    window.cloudlogCwSidetone = {
        playText: playText,
        initControls: initControls,
    };
})();