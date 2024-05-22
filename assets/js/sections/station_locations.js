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
    $("#asiatic_russia_state").hide();
    $("#belarus_state").hide();
    $("#mexico_state").hide();
    $("#eu_russia_state").hide();
    $("#argentina_state").hide();

    var selectedDXCCID = $('#dxcc_select').find(":selected").val();

    if(selectedDXCCID == '1'){
        $("#canada_state").show();
        $("#us_state").hide();
        $("#asiatic_russia_state").hide();
        $("#eu_russia_state").hide();
        $("#belarus_state").hide();
        $("#aland_state").hide();
        $("#mexico_state").hide();
        $("#argentina_state").hide();
    }

    // Show Aland States if Aland is selected
    if(selectedDXCCID == '5'){
        $("#aland_state").show();
        $("#canada_state").hide();
        $("#us_state").hide();
        $("#asiatic_russia_state").hide();
        $("#eu_russia_state").hide();
        $("#belarus_state").hide();
        $("#mexico_state").hide();
        $("#argentina_state").hide();
    }

    // Show Asiatic Russia States if Asiatic Russia is selected
    if(selectedDXCCID == '15'){
        $("#asiatic_russia_state").show();
        $("#aland_state").hide();
        $("#canada_state").hide();
        $("#us_state").hide();
        $("#eu_russia_state").hide();
        $("#belarus_state").hide();
        $("#mexico_state").hide();
        $("#argentina_state").hide();
    }

    // Show Belarus States if Belarus is selected
    if(selectedDXCCID == '27'){
        $("#belarus_state").show();
        $("#asiatic_russia_state").hide();
        $("#aland_state").hide();
        $("#canada_state").hide();
        $("#us_state").hide();
        $("#eu_russia_state").hide();
        $("#mexico_state").hide();
        $("#argentina_state").hide();
    }

    // Show Mexico States if Mexico is selected
    if(selectedDXCCID == '50'){
        $("#mexico_state").show();
        $("#belarus_state").hide();
        $("#asiatic_russia_state").hide();
        $("#aland_state").hide();
        $("#canada_state").hide();
        $("#us_state").hide();
        $("#eu_russia_state").hide();
        $("#argentina_state").hide();
    }

    // Show EU Russia States if EU Russia is selected
    if(selectedDXCCID == '54'){
        $("#mexico_state").hide();
        $("#belarus_state").hide();
        $("#asiatic_russia_state").hide();
        $("#aland_state").hide();
        $("#canada_state").hide();
        $("#us_state").hide();
        $("#eu_russia_state").show();
        $("#argentina_state").hide();
    }

    // Show Argentina States if Argentina is selected
    if(selectedDXCCID == '100'){
        $("#mexico_state").hide();
        $("#belarus_state").hide();
        $("#asiatic_russia_state").hide();
        $("#aland_state").hide();
        $("#canada_state").hide();
        $("#us_state").hide();
        $("#eu_russia_state").hide();
        $("#argentina_state").show();
    }

    $('#dxcc_select').change(function(){
        if($(this).val() == '1'){ // or this.value == 'volvo'
            $("#mexico_state").hide();
            $("#belarus_state").hide();
            $("#asiatic_russia_state").hide();
            $("#aland_state").hide();
            $("#canada_state").show();
            $("#us_state").hide();
            $("#eu_russia_state").hide();
            $("#argentina_state").hide();
        } else if($(this).val() == '5') {
            $("#mexico_state").hide();
            $("#belarus_state").hide();
            $("#asiatic_russia_state").hide();
            $("#aland_state").show();
            $("#canada_state").hide();
            $("#us_state").hide();
            $("#eu_russia_state").hide();
            $("#argentina_state").hide();
        } else if($(this).val() == '15') {
            $("#mexico_state").hide();
            $("#belarus_state").hide();
            $("#asiatic_russia_state").show();
            $("#aland_state").hide();
            $("#canada_state").hide();
            $("#us_state").hide();
            $("#eu_russia_state").hide();
            $("#argentina_state").hide();
        } else if($(this).val() == '27') {
            $("#mexico_state").hide();
            $("#belarus_state").show();
            $("#asiatic_russia_state").hide();
            $("#aland_state").hide();
            $("#canada_state").hide();
            $("#us_state").hide();
            $("#eu_russia_state").hide();
            $("#argentina_state").hide();
        } else if($(this).val() == '50') {
            $("#mexico_state").show();
            $("#belarus_state").hide();
            $("#asiatic_russia_state").hide();
            $("#aland_state").hide();
            $("#canada_state").hide();
            $("#us_state").hide();
            $("#eu_russia_state").hide();
            $("#argentina_state").hide();
        } else if($(this).val() == '54') {
            $("#mexico_state").hide();
            $("#belarus_state").hide();
            $("#asiatic_russia_state").hide();
            $("#aland_state").hide();
            $("#canada_state").hide();
            $("#us_state").hide();
            $("#eu_russia_state").show();
            $("#argentina_state").hide();
        } else if($(this).val() == '100') {
            $("#mexico_state").hide();
            $("#belarus_state").hide();
            $("#asiatic_russia_state").hide();
            $("#aland_state").hide();
            $("#canada_state").hide();
            $("#us_state").hide();
            $("#eu_russia_state").hide();
            $("#argentina_state").show();
        } else {
            $("#canada_state").hide();
            $("#us_state").show();
        }
    });
} );
