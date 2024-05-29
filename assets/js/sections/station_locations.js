$(document).ready( function () {

    // Use Jquery to hide div ca_state

    $('#station_locations_table').DataTable({
        "stateSave": true,
        "language": {
            url: getDataTablesLanguageUrl(),
        }
    });

    var stateMap = {
        '1': 'canada_state',
        '5': 'aland_state',
        '15': 'asiatic_russia_state',
        '27': 'belarus_state',
        '50': 'mexico_state',
        '54': 'eu_russia_state',
        '100': 'argentina_state',
        '108': 'brazil_state',
        '112': 'chile_state',
        '132': 'paraguay_state',
        '137': 'korea_state',
        '144': 'uruguay_state',
        '291': 'us_state'
    };

    // Hide all states initially
    $("#canada_state, #aland_state, #asiatic_russia_state, #belarus_state, #mexico_state, #eu_russia_state, #argentina_state, #brazil_state, #chile_state, #us_state, #paraguay_state, #korea_state, #uruguay_state").hide();

    var selectedDXCCID = $('#dxcc_select').find(":selected").val();
    var stateToShow = stateMap[selectedDXCCID];

    if (stateToShow) {
        // Show the selected state
        $("#" + stateToShow).show();
    } else {
        // If no state matches the selected value, show 'us_state' by default
        $("#us_state").show();
    }

    $('#dxcc_select').change(function(){
        var selectedValue = $(this).val();
        var stateToShow = stateMap[selectedValue] || stateMap['default'];
    
        // Hide all states
        $("#mexico_state, #belarus_state, #asiatic_russia_state, #aland_state, #canada_state, #us_state, #eu_russia_state, #argentina_state, #brazil_state, #chile_state, #paraguay_state, #korea_state, #uruguay_state").hide();
    
        // Show the selected state
        $("#" + stateToShow).show();
    });
} );
