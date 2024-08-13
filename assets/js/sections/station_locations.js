/**
 * Initializes the DataTable and handles the logic for showing/hiding states based on the selected DXCC ID.
 */
$(document).ready( function () {

    /**
     * Initializes the DataTable with state saving enabled and custom language settings.
     *
     * @type {DataTable}
     */
    $('#station_locations_table').DataTable({
        "stateSave": true,
        "language": {
            url: getDataTablesLanguageUrl(),
        }
    });

    /**
     * Maps DXCC IDs to their corresponding state IDs.
     *
     * @type {Object}
     */
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
        '291': 'us_state',
        '148': 'venezuela_state', 
        '150': 'australia_state',
        '163': 'png_state',
        '170': 'nz_state',
		'209': 'belgium_state',
		'248': 'italy_state',
        '263': 'netherlands_state',
        '6': 'us_state' // Alaska
    };

    // Hide all states initially
	  $("#canada_state, #aland_state, #asiatic_russia_state, #belarus_state, #mexico_state, #eu_russia_state, #argentina_state, #brazil_state, #chile_state, #us_state, #paraguay_state, #korea_state, #uruguay_state, #venezuela_state, #australia_state, #png_state, #nz_state, #belgium_state, #italy_state, #netherlands_state").hide();
    /**
     * Gets the selected DXCC ID and shows the corresponding state.
     */
    var selectedDXCCID = $('#dxcc_select').find(":selected").val();
    var stateToShow = stateMap[selectedDXCCID];

    if (stateToShow) {
        // Show the selected state
        $("#" + stateToShow).show();
    } else {
        // If no state matches the selected value, show 'us_state' by default
        $("#us_state").show();
    }

    /**
     * Handles the change event of the DXCC select element.
     * Shows the corresponding state based on the selected DXCC ID.
     */
    $('#dxcc_select').change(function(){
        var selectedValue = $(this).val();
        var stateToShow = stateMap[selectedValue] || stateMap['default'];
    
        // Hide all states
    		$("#mexico_state, #belarus_state, #asiatic_russia_state, #aland_state, #canada_state, #us_state, #eu_russia_state, #argentina_state, #brazil_state, #chile_state, #paraguay_state, #korea_state, #uruguay_state, #venezuela_state, #australia_state, #png_state, #nz_state, #belgium_state, #italy_state, #netherlands_state").hide();
    
        // Show the selected state
        $("#" + stateToShow).show();
    });
} );
