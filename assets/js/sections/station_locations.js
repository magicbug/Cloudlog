$(document).ready( function () {

    // Use Jquery to hide div ca_state

    $('#station_locations_table').DataTable({
        "stateSave": true,
        "language": {
            url: getDataTablesLanguageUrl(),
        }
    });

    $("#canada_state").hide();
    $("#aland_state").hide();

    var selectedDXCCID = $('#dxcc_select').find(":selected").val();

    if(selectedDXCCID == '1'){
        $("#canada_state").show();
        $("#us_state").hide();
    }

    // Show Aland States if Aland is selected
    if(selectedDXCCID == '5'){
        $("#aland_state").show();
        $("#canada_state").hide();
        $("#us_state").hide();
    }

    $('#dxcc_select').change(function(){
        if($(this).val() == '1'){ // or this.value == 'volvo'
          console.log("CANADA!");
          $("#canada_state").show();
          $("#us_state").hide();
        } else if($(this).val() == '5') {
            $("#aland_state").show();
            $("#canada_state").hide();
            $("#us_state").hide();
        } else {
            $("#canada_state").hide();
            $("#us_state").show();
        }
    });
} );
