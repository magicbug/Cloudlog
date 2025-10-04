<div class="container-fluid">
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-radio"></i> Radio Commands</h3>
                </div>
                <div class="card-body">
                    <!-- Radio Selection and Command Form -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Send Command to Radio</h5>
                                </div>
                                <div class="card-body">
                                    <form id="commandForm">
                                        <div class="form-group mb-3">
                                            <label for="radio_select">Select Radio:</label>
                                            <select class="form-select" id="radio_select" name="radio_id" required>
                                                <option value="">-- Select Radio --</option>
                                                <?php foreach ($radios as $radio): ?>
                                                    <option value="<?php echo $radio->id; ?>"><?php echo $radio->radio; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group mb-3">
                                            <label for="command_type">Command Type:</label>
                                            <select class="form-select" id="command_type" name="command_type" required>
                                                <option value="">-- Select Command --</option>
                                                <option value="SET_FREQ">Set Frequency</option>
                                                <option value="SET_MODE">Set Mode</option>
                                                <option value="SET_VFO">Set VFO</option>
                                                <option value="SET_POWER">Set Power</option>
                                            </select>
                                        </div>
                                        
                                        <!-- Frequency Input (shown when SET_FREQ selected) -->
                                        <div class="form-group mb-3" id="frequency_group" style="display: none;">
                                            <label for="frequency">Frequency (MHz):</label>
                                            <input type="number" class="form-control" id="frequency" name="frequency" step="0.001" placeholder="14.074">
                                        </div>
                                        
                                        <!-- Mode Input (shown when SET_MODE selected) -->
                                        <div class="form-group mb-3" id="mode_group" style="display: none;">
                                            <label for="mode">Mode:</label>
                                            <select class="form-select" id="mode" name="mode">
                                                <option value="">-- Select Mode --</option>
                                                <option value="USB">USB</option>
                                                <option value="LSB">LSB</option>
                                                <option value="CW">CW</option>
                                                <option value="AM">AM</option>
                                                <option value="FM">FM</option>
                                                <option value="RTTY">RTTY</option>
                                                <option value="PSK31">PSK31</option>
                                                <option value="FT8">FT8</option>
                                                <option value="FT4">FT4</option>
                                            </select>
                                        </div>
                                        
                                        <!-- VFO Input (shown when SET_VFO selected) -->
                                        <div class="form-group mb-3" id="vfo_group" style="display: none;">
                                            <label for="vfo">VFO:</label>
                                            <select class="form-select" id="vfo" name="vfo">
                                                <option value="">-- Select VFO --</option>
                                                <option value="A">VFO A</option>
                                                <option value="B">VFO B</option>
                                                <option value="C">VFO C</option>
                                            </select>
                                        </div>
                                        
                                        <!-- Power Input (shown when SET_POWER selected) -->
                                        <div class="form-group mb-3" id="power_group" style="display: none;">
                                            <label for="power">Power (Watts):</label>
                                            <input type="number" class="form-control" id="power" name="power" min="1" max="1500" placeholder="100">
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane"></i> Send Command
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- Quick Frequency/Mode Command -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="card-title">Quick Frequency + Mode</h5>
                                </div>
                                <div class="card-body">
                                    <form id="quickCommandForm">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="quick_radio">Radio:</label>
                                                    <select class="form-select" id="quick_radio" name="radio_id" required>
                                                        <option value="">-- Select Radio --</option>
                                                        <?php foreach ($radios as $radio): ?>
                                                            <option value="<?php echo $radio->id; ?>"><?php echo $radio->radio; ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="quick_frequency">Frequency (MHz):</label>
                                                    <input type="number" class="form-control" id="quick_frequency" name="frequency" step="0.001" placeholder="14.074" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label for="quick_mode">Mode:</label>
                                                    <select class="form-select" id="quick_mode" name="mode">
                                                        <option value="">-- Select Mode --</option>
                                                        <option value="USB">USB</option>
                                                        <option value="LSB">LSB</option>
                                                        <option value="CW">CW</option>
                                                        <option value="AM">AM</option>
                                                        <option value="FM">FM</option>
                                                        <option value="RTTY">RTTY</option>
                                                        <option value="PSK31">PSK31</option>
                                                        <option value="FT8">FT8</option>
                                                        <option value="FT4">FT4</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-3">
                                                    <label>&nbsp;</label>
                                                    <button type="submit" class="btn btn-success form-control">
                                                        <i class="fas fa-broadcast-tower"></i> Set Freq + Mode
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Command History -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Recent Commands</h5>
                                </div>
                                <div class="card-body">
                                    <div id="command_history">
                                        <?php if (!empty($recent_commands)): ?>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>Time</th>
                                                            <th>Radio</th>
                                                            <th>Command</th>
                                                            <th>Value</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($recent_commands as $command): ?>
                                                            <tr>
                                                                <td><?php echo date('H:i:s', strtotime($command['created_at'])); ?></td>
                                                                <td><?php echo htmlspecialchars($command['radio_name']); ?></td>
                                                                <td><?php echo $command['command_type']; ?></td>
                                                                <td>
                                                                    <?php 
                                                                    if ($command['frequency']) {
                                                                        echo number_format($command['frequency'] / 1000000, 3) . ' MHz';
                                                                    } elseif ($command['mode']) {
                                                                        echo $command['mode'];
                                                                    } elseif ($command['vfo']) {
                                                                        echo 'VFO ' . $command['vfo'];
                                                                    } elseif ($command['power']) {
                                                                        echo $command['power'] . 'W';
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <span class="badge bg-<?php 
                                                                        echo ($command['status'] == 'COMPLETED') ? 'success' : 
                                                                             (($command['status'] == 'FAILED') ? 'danger' : 
                                                                              (($command['status'] == 'PROCESSING') ? 'warning' : 'secondary'));
                                                                    ?>">
                                                                        <?php echo $command['status']; ?>
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <?php if ($command['status'] == 'PENDING'): ?>
                                                                        <button class="btn btn-sm btn-outline-danger cancel-command" 
                                                                                data-command-id="<?php echo $command['id']; ?>">
                                                                            <i class="fas fa-times"></i>
                                                                        </button>
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php else: ?>
                                            <p class="text-muted">No recent commands found.</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alert Container -->
<div id="alertContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 1050;"></div>

<script type="text/javascript">
// Use document ready with window load to ensure everything is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Wait a bit more to ensure jQuery is loaded
    setTimeout(function() {
        initializeCommandsPage();
    }, 100);
});

function initializeCommandsPage() {
    // Check if jQuery is available
    if (typeof jQuery === 'undefined') {
        console.error('jQuery not loaded yet, retrying...');
        setTimeout(initializeCommandsPage, 500);
        return;
    }
    
    var $ = jQuery;
    console.log('Commands page initialized with jQuery');
    
    // Show/hide input fields based on command type selection
    $('#command_type').change(function() {
        var commandType = $(this).val();
        console.log('Command type selected:', commandType);
        
        // Hide all input groups
        $('#frequency_group, #mode_group, #vfo_group, #power_group').hide();
        
        // Show relevant input group
        switch(commandType) {
            case 'SET_FREQ':
                console.log('Showing frequency group');
                $('#frequency_group').show();
                break;
            case 'SET_MODE':
                console.log('Showing mode group');
                $('#mode_group').show();
                break;
            case 'SET_VFO':
                console.log('Showing VFO group');
                $('#vfo_group').show();
                break;
            case 'SET_POWER':
                console.log('Showing power group');
                $('#power_group').show();
                break;
            default:
                console.log('No matching command type');
        }
    });
    
    // Test that the elements exist
    console.log('Command type element found:', $('#command_type').length > 0);
    console.log('Frequency group element found:', $('#frequency_group').length > 0);
    
    // Handle individual command form submission
    $('#commandForm').submit(function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        
        $.post('<?php echo site_url('commands/send'); ?>', formData)
            .done(function(response) {
                if (response.success) {
                    showAlert('Command sent successfully!', 'success');
                    refreshCommandHistory();
                    $('#commandForm')[0].reset();
                    $('#frequency_group, #mode_group, #vfo_group, #power_group').hide();
                } else {
                    showAlert('Error: ' + response.message, 'danger');
                }
            })
            .fail(function() {
                showAlert('Failed to send command. Please try again.', 'danger');
            });
    });
    
    // Handle quick frequency/mode command form submission
    $('#quickCommandForm').submit(function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        
        $.post('<?php echo site_url('commands/send_freq_mode'); ?>', formData)
            .done(function(response) {
                if (response.success) {
                    showAlert('Frequency and mode commands sent!', 'success');
                    refreshCommandHistory();
                    $('#quickCommandForm')[0].reset();
                } else {
                    showAlert('Error: ' + response.message, 'danger');
                }
            })
            .fail(function() {
                showAlert('Failed to send commands. Please try again.', 'danger');
            });
    });
    
    // Handle cancel command buttons
    $(document).on('click', '.cancel-command', function() {
        var commandId = $(this).data('command-id');
        
        if (confirm('Are you sure you want to cancel this command?')) {
            $.post('<?php echo site_url('commands/cancel'); ?>', {command_id: commandId})
                .done(function(response) {
                    if (response.success) {
                        showAlert('Command cancelled successfully!', 'success');
                        refreshCommandHistory();
                    } else {
                        showAlert('Failed to cancel command.', 'danger');
                    }
                })
                .fail(function() {
                    showAlert('Failed to cancel command. Please try again.', 'danger');
                });
        }
    });
    
    // Auto-refresh command history every 10 seconds
    setInterval(refreshCommandHistory, 10000);
}

function showAlert(message, type) {
    var $ = jQuery;
    var alertHtml = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
                    message +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                    '</div>';
    
    $('#alertContainer').prepend(alertHtml);
    
    // Auto-remove alert after 5 seconds
    setTimeout(function() {
        $('#alertContainer .alert:last').fadeOut();
    }, 5000);
}

function refreshCommandHistory() {
    // Reload the page to refresh command history
    window.location.reload();
}
</script>
</script>