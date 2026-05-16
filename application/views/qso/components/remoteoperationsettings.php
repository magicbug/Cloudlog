<div id="modal-backdrop" class="modal-backdrop fade show" style="display:block;"></div>
<div id="modal" class="modal fade show" tabindex="-1" style="display:block;">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <div>
          <h5 class="modal-title mb-0">Remote Operation</h5>
          <small class="text-muted">Experimental browser-based shack audio control</small>
        </div>
        <button type="button" class="btn-close" onclick="closeRemoteOperationModal()" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="alert alert-warning py-2 small mb-3" role="note">
          <strong>Experimental:</strong> this card is enabled per account. The settings below are saved in this browser only for now.
        </div>

        <div class="d-flex align-items-center gap-2 mb-3">
          <span id="remoteOperationModalStatus" class="badge bg-secondary">Status: Disconnected</span>
          <button type="button" class="btn btn-sm btn-outline-primary" id="remoteOperationEnumerateDevices">Enumerate devices</button>
          <button type="button" class="btn btn-sm btn-outline-secondary" id="remoteOperationStatsRefresh">Refresh stats now</button>
        </div>

        <div class="row g-3">
          <div class="col-lg-7">
            <div class="card h-100">
              <div class="card-header fw-semibold">Connection</div>
              <div class="card-body">
                <div class="mb-3">
                  <label for="remoteOperationWsUrl" class="form-label">Signalling server WebSocket URL</label>
                  <input type="text" class="form-control" id="remoteOperationWsUrl" spellcheck="false" placeholder="ws://127.0.0.1:8787/ws-webrtc">
                </div>

                <div class="mb-3">
                  <label for="remoteOperationLinkPassword" class="form-label">Link password</label>
                  <input type="password" class="form-control" id="remoteOperationLinkPassword" autocomplete="off" placeholder="Same on both ends, at least 16 characters">
                </div>

                <div class="mb-3">
                  <label for="remoteOperationLinkName" class="form-label">Link name</label>
                  <input type="text" class="form-control" id="remoteOperationLinkName" spellcheck="false" placeholder="demo-session">
                </div>

                <div class="mb-0">
                  <label for="remoteOperationRadioName" class="form-label">Radio name</label>
                  <input type="text" class="form-control" id="remoteOperationRadioName" spellcheck="false" placeholder="Exact rig row name or leave blank for the first remote-ready rig">
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-5">
            <div class="card h-100">
              <div class="card-header fw-semibold">Audio and NAT</div>
              <div class="card-body">
                <div class="mb-3">
                  <label for="remoteOperationStun" class="form-label">STUN servers</label>
                  <input type="text" class="form-control" id="remoteOperationStun" spellcheck="false" placeholder="stun:stun.l.google.com:19302">
                </div>

                <div class="mb-3">
                  <label for="remoteOperationTurnUrl" class="form-label">TURN URL</label>
                  <input type="text" class="form-control" id="remoteOperationTurnUrl" spellcheck="false" placeholder="turn:your.server:3478?transport=udp">
                </div>

                <div class="row g-2 mb-3">
                  <div class="col-12 col-md-6">
                    <label for="remoteOperationTurnUser" class="form-label">TURN username</label>
                    <input type="text" class="form-control" id="remoteOperationTurnUser" autocomplete="off" spellcheck="false">
                  </div>
                  <div class="col-12 col-md-6">
                    <label for="remoteOperationTurnPwd" class="form-label">TURN password</label>
                    <input type="password" class="form-control" id="remoteOperationTurnPwd" autocomplete="off">
                  </div>
                </div>

                <div class="mb-3">
                  <label for="remoteOperationAudioIn" class="form-label">Mic input</label>
                  <select id="remoteOperationAudioIn" class="form-select"></select>
                </div>

                <div class="mb-3">
                  <label for="remoteOperationAudioOut" class="form-label">Speaker output</label>
                  <select id="remoteOperationAudioOut" class="form-select"></select>
                </div>

                <div id="remoteOperationModalDeviceWarning" class="alert alert-warning py-2 px-3 small mb-3 d-none" role="alert"></div>

                <div class="form-check form-switch mb-2">
                  <input class="form-check-input" type="checkbox" id="remoteOperationMinimalMicProcessing">
                  <label class="form-check-label" for="remoteOperationMinimalMicProcessing">Less mic DSP</label>
                </div>

                <div class="form-check form-switch mb-2">
                  <input class="form-check-input" type="checkbox" id="remoteOperationPreferOpus">
                  <label class="form-check-label" for="remoteOperationPreferOpus">Prefer Opus in browser offer</label>
                </div>

                <div class="form-check form-switch mb-0">
                  <input class="form-check-input" type="checkbox" id="remoteOperationPlayoutDelayHint" checked>
                  <label class="form-check-label" for="remoteOperationPlayoutDelayHint">Minimise inbound playout delay</label>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12">
            <div class="card">
              <div class="card-header fw-semibold">Diagnostics</div>
              <div class="card-body">
                <div class="form-check form-switch mb-3">
                  <input class="form-check-input" type="checkbox" id="remoteOperationStatsPoll">
                  <label class="form-check-label" for="remoteOperationStatsPoll">Poll stats every 2 seconds while connected</label>
                </div>

                <div class="d-flex flex-wrap gap-2 mb-3">
                  <button type="button" class="btn btn-primary" id="remoteOperationSaveButton">Save settings</button>
                  <button type="button" class="btn btn-outline-danger" id="remoteOperationResetSettingsButton">Reset settings</button>
                  <button type="button" class="btn btn-success" id="remoteOperationModalConnectButton">Connect</button>
                  <button type="button" class="btn btn-outline-secondary" id="remoteOperationCloseButton">Close</button>
                </div>

                <label for="remoteOperationStats" class="form-label">Delay / jitter</label>
                <div id="remoteOperationStats" class="border rounded p-2 bg-body-tertiary small" style="white-space: pre-wrap; min-height: 8rem; max-height: 12rem; overflow: auto;"></div>

                <label for="remoteOperationLog" class="form-label mt-3">Log</label>
                <div id="remoteOperationLog" class="border rounded p-2 bg-dark text-success small" style="white-space: pre-wrap; min-height: 8rem; max-height: 12rem; overflow: auto;"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
