<!-- General JS Files used across Cloudlog -->
<script src="<?php echo base_url(); ?>assets/js/jquery-3.3.1.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/popper.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.fancybox.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.bundle.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.jclock.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/leaflet/leaflet.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/leaflet/L.Maidenhead.qrb.js"></script>
<?php if ($this->uri->segment(1) == "activators") { ?>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/leaflet/L.Maidenhead.activators.js"></script>
<?php } ?>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/leaflet/leaflet.geodesic.js"></script>
<script type="text/javascript" src="<?php echo base_url() ;?>assets/js/radiohelpers.js"></script>
<script type="text/javascript" src="<?php echo base_url() ;?>assets/js/darkmodehelpers.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrapdialog/js/bootstrap-dialog.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ;?>assets/js/easyprint.js"></script>
<script src="https://unpkg.com/htmx.org@1.6.1"></script>

<script type="text/javascript">
  /*
  *
  * Define global javascript variables
  *
  */
  var base_url = "<?php echo base_url(); ?>"; // Base URL
  var site_url = "<?php echo site_url(); ?>"; // Site URL
  var icon_dot_url = "<?php echo base_url();?>assets/images/dot.png";
</script>

<?php if ($this->uri->segment(1) == "awards" && ($this->uri->segment(2) == "was") ) { ?>
<script>
function load_was_map() {
    BootstrapDialog.show({
            title: 'Worked All States Map ('+$('#band2').val()+' '+$('#mode').val()+')',
            cssClass: 'was-map-dialog',
            message: $('<div></div>').load(site_url + '/awards/was_map/' + $('#band2').val() + '/' + $('#mode').val())
    });
}
</script>
<?php } ?>

<?php if ($this->uri->segment(1) == "awards" && ($this->uri->segment(2) == "cq") ) { ?>
    <script src="<?php echo base_url(); ?>assets/js/Polyline.encoded.js"></script>
    <script id="cqmapjs" type="text/javascript" src="<?php echo base_url(); ?>assets/js/sections/cqmap.js" tileUrl="<?php echo $this->optionslib->get_option('option_map_tile_server');?>"></script>
<?php } ?>

<?php if ($this->uri->segment(1) == "statistics") { ?>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/chart.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/chartjs-plugin-piechart-outlabels.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/sections/statistics.js"></script>
<?php } ?>

<?php if ($this->uri->segment(1) == "adif") { ?>
    <!-- Javascript used for ADIF Import and Export Areas -->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="<?php echo base_url() ;?>assets/js/sections/adif.js"></script>
<?php } ?>

<?php if ($this->uri->segment(1) == "notes" && ($this->uri->segment(2) == "add" || $this->uri->segment(2) == "edit") ) { ?>
    <!-- Javascript used for Notes Area -->
    <script src="<?php echo base_url() ;?>assets/plugins/quill/quill.min.js"></script>
    <script src="<?php echo base_url() ;?>assets/js/sections/notes.js"></script>
<?php } ?>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/datatables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/selectize.js"></script>

<?php if ($this->uri->segment(1) == "station") { ?>
    <script language="javascript" src="<?php echo base_url() ;?>assets/js/HamGridSquare.js"></script>
    <script src="<?php echo base_url() ;?>assets/js/sections/station_locations.js"></script>
    <script>
        var position;
        function getLocation() {
            console.log("'clicked");
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else { 
                console.log('Geolocation is not supported by this browser.');
            }
        }

        function showPosition(position) {
            gridsquare = latLonToGridSquare(position.coords.latitude,position.coords.longitude);
            document.getElementById("stationGridsquareInput").value = gridsquare;
  }
    </script>
<?php } ?>

<?php if ($this->uri->segment(1) == "logbooks") { ?>
    <script src="<?php echo base_url() ;?>assets/js/sections/station_logbooks.js"></script>
<?php } ?>

<?php if ($this->uri->segment(1) == "api"  && $this->uri->segment(2) == "help") { ?>
<script type="text/javascript">
function copyApiKey(apiKey) {
   var apiKeyField = $('#'+apiKey);
   navigator.clipboard.writeText(apiKey).then(function() {
   });
   apiKeyField.addClass('flash-copy')
      .delay('1000').queue(function() {
         apiKeyField.removeClass('flash-copy').dequeue();
      });
}

$(function () {
   $('[data-toggle="tooltip"]').tooltip({'delay': { show: 500, hide: 0 }, 'placement': 'right'});
});
</script>
<?php } ?>

<?php if ($this->uri->segment(1) == "search" && $this->uri->segment(2) == "filter") { ?>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/query-builder.standalone.min.js"></script>

<script type="text/javascript">
    $(".search-results-box").hide();

    $('#builder').queryBuilder({
        filters: [
            <?php foreach ($get_table_names->result() as $row) {
                $value_name = str_replace("COL_", "", $row->Field);
                if ($value_name != "PRIMARY_KEY" && strpos($value_name, 'MY_') === false && strpos($value_name, '_INTL') == false) { ?> {
                        id: '<?php echo $row->Field; ?>',
                        label: '<?php echo $value_name; ?>',
                        <?php if (strpos($row->Type, 'int(') !== false) { ?>
                            type: 'integer',
                            operators: ['equal', 'not_equal', 'less', 'less_or_equal', 'greater', 'greater_or_equal']
                        <?php } elseif (strpos($row->Type, 'double') !== false) { ?>
                            type: 'double',
                            operators: ['equal', 'not_equal', 'less', 'less_or_equal', 'greater', 'greater_or_equal']
                        <?php } elseif (strpos($row->Type, 'datetime') !== false) { ?>
                            type: 'datetime',
                            operators: ['equal', 'not_equal', 'less', 'less_or_equal', 'greater', 'greater_or_equal']
                        <?php } else { ?>
                            type: 'string',
                            operators: ['equal', 'not_equal', 'begins_with', 'contains', 'ends_with', 'is_empty', 'is_not_empty', 'is_null', 'is_not_null']
                        <?php } ?>
                    },
                <?php } ?>
            <?php } ?>
        ]
    });


    function export_search_result() {
        var result = $('#builder').queryBuilder('getRules');
        if (!$.isEmptyObject(result)) {
            xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                var a;
                if (xhttp.readyState === 4 && xhttp.status === 200) {
                    // Trick for making downloadable link
                    a = document.createElement('a');
                    a.href = window.URL.createObjectURL(xhttp.response);
                    // Give filename you wish to download
                    a.download = "advanced_search_export.adi";
                    a.style.display = 'none';
                    document.body.appendChild(a);
                    a.click();
                }
            };
            // Post data to URL which handles post request
            xhttp.open("POST", "<?php echo site_url('search/export_to_adif'); ?>", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            // You should set responseType as blob for binary responses
            xhttp.responseType = 'blob';
            xhttp.send("search=" + JSON.stringify(result, null, 2));
        }
    }

    function export_stored_query(id) {
        xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            var a;
            if (xhttp.readyState === 4 && xhttp.status === 200) {
                // Trick for making downloadable link
                a = document.createElement('a');
                a.href = window.URL.createObjectURL(xhttp.response);
                // Give filename you wish to download
                a.download = "advanced_search_export.adi";
                a.style.display = 'none';
                document.body.appendChild(a);
                a.click();
            }
        };
        // Post data to URL which handles post request
        xhttp.open("POST", "<?php echo site_url('search/export_stored_query_to_adif'); ?>", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        // You should set responseType as blob for binary responses
        xhttp.responseType = 'blob';
        xhttp.send("id=" + id);
    }

    $('#btn-save').on('click', function() {
        var resultquery = $('#builder').queryBuilder('getRules');
        if (!$.isEmptyObject(resultquery)) {
            let message = 'Description: <input class="form-control input-group-sm getqueryname">'

            BootstrapDialog.confirm({
                title: 'Query description',
                size: BootstrapDialog.SIZE_NORMAL,
                cssClass: 'description-dialog',
                closable: true,
                nl2br: false,
                message: message,
                btnCancelLabel: 'Cancel',
                btnOKLabel: 'Save',
                callback: function(result) {
                    if (result) {
                        $.post("<?php echo site_url('search/save_query'); ?>", {
                                search: JSON.stringify(resultquery, null, 2),
                                description: $(".getqueryname").val()
                            })
                            .done(function(data) {
                                $(".alert").remove();
                                $(".card-body.main").append('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Your query has been saved!</div>');
                                if ($("#querydropdown option").length == 0) {
                                    var dropdowninfo = ' <button class="btn btn-sm btn-primary" onclick="edit_stored_query_dialog()" id="btn-edit">Edit queries</button></p>' +
                                    '<div class="form-group row querydropdownform">' +
                                        '<label class="col-md-2 control-label" for="querydropdown">  Stored queries:</label>' +
                                        '<div class="col-md-3">' +
                                            '<select id="querydropdown" name="querydropdown" class="form-control custom-select-sm">' +
                                            '</select>' +
                                        '</div>' +
                                        '<button class="btn btn-sm btn-primary ld-ext-right runbutton" onclick="run_query()">Run Query<div class="ld ld-ring ld-spin"></div></button>' +
                                    '</div>';
                                    $("#btn-save").after(dropdowninfo);
                                }
                                $('#querydropdown').append(new Option(data.description, data.id)); // We add the saved query to the dropdown
                            });
                    }
                },
            });

        } else {
            BootstrapDialog.show({
                title: 'Stored Queries',
                type: BootstrapDialog.TYPE_WARNING,
                size: BootstrapDialog.SIZE_NORMAL,
                cssClass: 'queries-dialog',
                nl2br: false,
                message: 'You need to make a query before you search!',
                buttons: [{
                    label: 'Close',
                    action: function(dialogItself) {
                        dialogItself.close();
                    }
                }]
            });
        }
    });

    function run_query() {
        $(".alert").remove();
        $(".runbutton").addClass('running');
        $(".runbutton").prop('disabled', true);
        let id = $('#querydropdown').val();
        $.post("<?php echo site_url('search/run_query'); ?>", {
                id: id
            })
            .done(function(data) {

                $('.exportbutton').html('<button class="btn btn-sm btn-primary" onclick="export_stored_query(' + id + ')">Export to ADIF</button>');
                $('.card-body.result').empty();
                $(".search-results-box").show();

                $('.card-body.result').append(data);
                $('.table').DataTable({
                    "pageLength": 25,
                    responsive: false,
                    ordering: false,
                    "scrollY": "400px",
                    "scrollCollapse": true,
                    "paging": false,
                    "scrollX": true,
                    dom: 'Bfrtip',
                    buttons: [
                        'csv'
                    ]
                });
                // change color of csv-button if dark mode is chosen
                if (isDarkModeTheme()) {
                    $(".buttons-csv").css("color", "white");
                }
                $(".runbutton").removeClass('running');
                $(".runbutton").prop('disabled', false);
            });
    }

    function delete_stored_query(id) {
        BootstrapDialog.confirm({
            title: 'DANGER',
            message: 'Warning! Are you sure you want delete this stored query?',
            type: BootstrapDialog.TYPE_DANGER,
            closable: true,
            draggable: true,
            btnOKClass: 'btn-danger',
            callback: function(result) {
                if (result) {
                    $.ajax({
                        url: base_url + 'index.php/search/delete_query',
                        type: 'post',
                        data: {
                            'id': id
                        },
                        success: function(data) {
                            $(".bootstrap-dialog-message").prepend('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>The stored query has been deleted!</div>');
                            $("#query_" + id).remove(); // removes query from table in dialog
                            $("#querydropdown option[value='" + id + "']").remove(); // removes query from dropdown
                            if ($("#querydropdown option").length == 0) { 
                                $("#btn-edit").remove();
                                $('.querydropdownform').remove();
                            };
                        },
                        error: function() {
                            $(".bootstrap-dialog-message").prepend('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>The stored query could not be deleted. Please try again!</div>');
                        },
                    });
                }
            }
        });
    }

    function edit_stored_query(id) {
        $('#description_' + id).attr('contenteditable', 'true');
        $('#description_' + id).focus();
        $('#edit_' + id).html('<a class="btn btn-primary btn-sm" href="javascript:save_edited_query(' + id + ');">Save</a>'); // Change to save button
    }

    function save_edited_query(id) {
        $('#description_' + id).attr('contenteditable', 'false');
        $('#edit_' + id).html('<a class="btn btn-outline-primary btn-sm" href="javascript:edit_stored_query(' + id + ');">Edit</a>');
        $.ajax({
            url: base_url + 'index.php/search/save_edited_query',
            type: 'post',
            data: {
                id: id,
                description: $('#description_' + id).html(),
            },
            success: function(html) {
                $('#edit_' + id).html('<a class="btn btn-outline-primary btn-sm" href="javascript:edit_stored_query(' + id + ');">Edit</a>'); // Change to edit button
                $(".bootstrap-dialog-message").prepend('<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>The query description has been updated!</div>');
                $("#querydropdown option[value='" + id + "']").text($('#description_' + id).html()); // Change text in dropdown
            },
            error: function() {
                $(".bootstrap-dialog-message").prepend('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something went wrong with the save. Please try again!</div>');
            },
        });
    }

    function edit_stored_query_dialog() {
        $(".alert").remove();
        $.ajax({
            url: base_url + 'index.php/search/get_stored_queries',
            type: 'post',
            success: function(html) {
                BootstrapDialog.show({
                    title: 'Stored Queries',
                    size: BootstrapDialog.SIZE_WIDE,
                    cssClass: 'queries-dialog',
                    nl2br: false,
                    message: html,
                    buttons: [{
                        label: 'Close',
                        action: function(dialogItself) {
                            dialogItself.close();
                        }
                    }]
                });
            }
        });
    }

    $('#btn-get').on('click', function() {
        $(".alert").remove();
        var result = $('#builder').queryBuilder('getRules');
        if (!$.isEmptyObject(result)) {
            $(".searchbutton").addClass('running');
            $(".searchbutton").prop('disabled', true);

            $.post("<?php echo site_url('search/search_result'); ?>", {
                    search: JSON.stringify(result, null, 2),
                    temp: "testvar"
                })
                .done(function(data) {
                    $('.exportbutton').html('<button class="btn btn-sm btn-primary" onclick="export_search_result();">Export to ADIF</button>');

                    $('.card-body.result').empty();
                    $(".search-results-box").show();

                    $('.card-body.result').append(data);
                    $('.table').DataTable({
                        "pageLength": 25,
                        responsive: false,
                        ordering: false,
                        "scrollY": "400px",
                        "scrollCollapse": true,
                        "paging": false,
                        "scrollX": true,
                        dom: 'Bfrtip',
                        buttons: [
                            'csv'
                        ]
                    });
                    // change color of csv-button if dark mode is chosen
                    if (isDarkModeTheme()) {
                        $(".buttons-csv").css("color", "white");
                    }
                    $('[data-toggle="tooltip"]').tooltip();
                    $(".searchbutton").removeClass('running');
                    $(".searchbutton").prop('disabled', false);
                    $("#btn-save").show();
                });
        } else {
            BootstrapDialog.show({
                title: 'Stored Queries',
                type: BootstrapDialog.TYPE_WARNING,
                size: BootstrapDialog.SIZE_NORMAL,
                cssClass: 'queries-dialog',
                nl2br: false,
                message: 'You need to make a query before you search!',
                buttons: [{
                    label: 'Close',
                    action: function(dialogItself) {
                        dialogItself.close();
                    }
                }]
            });
        }
    });

    $('#btn-set').on('click', function() {
        //$('#builder').queryBuilder('setRules', rules_basic);
        var result = $('#builder').queryBuilder('getRules');
        if (!$.isEmptyObject(result)) {
            rules_basic = result;
        }
    });

    //When rules changed :
    $('#builder').on('getRules.queryBuilder.filter', function(e) {
        //$log.info(e.value);
    });
</script>

<?php } ?>

<script>
$(document).ready(function() {
	$('#create_station_profile #country').val($("#dxcc_select option:selected").text());
	$("#create_station_profile #dxcc_select" ).change(function() {
	$('#country').val($("#dxcc_select option:selected").text());

	});
});
</script>

<script>
var $= jQuery.noConflict();
$('[data-fancybox]').fancybox({
    toolbar  : false,
    smallBtn : true,
    iframe : {
        preload : false
    }
});

// Here we capture ALT-L to invoke the Quick lookup
document.onkeyup = function(e) {
	if (e.altKey && e.which == 76) {
		spawnLookupModal();
	}
    if (e.altKey && e.which == 81) {
		spawnQrbCalculator();
	}
};

function spawnQrbCalculator(locator1, locator2) {
	$.ajax({
		url: base_url + 'index.php/qrbcalc',
		type: 'post',
		success: function (html) {
			BootstrapDialog.show({
				title: 'Compute QRB and QTF',
				size: BootstrapDialog.SIZE_WIDE,
				cssClass: 'lookup-dialog',
				nl2br: false,
				message: html,
				onshown: function(dialog) {
                    if (locator1 !== undefined) {
                        $("#qrbcalc_locator1").val(locator1);
                    }
                    if (locator2 !== undefined) {
                        $("#qrbcalc_locator2").val(locator2);
                        calculateQrb();
                    }
				},
				buttons: [{
					label: 'Close',
					action: function (dialogItself) {
						dialogItself.close();
					}
				}]
			});
		}
	});
}

function spawnActivatorsMap(call, count, grids) {
	$.ajax({
		url: base_url + 'index.php/activatorsmap',
		type: 'post',
		success: function (html) {
			BootstrapDialog.show({
				title: 'Activators Map',
				size: BootstrapDialog.SIZE_WIDE,
				cssClass: 'lookup-dialog',
				nl2br: false,
				message: html,
				onshown: function(dialog) {
					showActivatorsMap(call, count, grids);
				},
				buttons: [{
					label: 'Close',
					action: function (dialogItself) {
						dialogItself.close();
					}
				}]
			});
		}
	});
}

function calculateQrb() {
    let locator1 = $("#qrbcalc_locator1").val();
    let locator2 = $("#qrbcalc_locator2").val();

    $(".qrbalert").remove();

    if (validateLocator(locator1) && validateLocator(locator2)) {
        $.ajax({
            url: base_url+'index.php/qrbcalc/calculate',
            type: 'post',
            data: {'locator1': locator1,
                    'locator2': locator2},
            success: function (html) {
                
                var result = "<h5>Negative latitudes are south of the equator, negative longitudes are west of Greenwich. <br/>";
                result += ' ' + locator1.toUpperCase() + ' Latitude = ' + html['latlng1'][0] + ' Longitude = ' + html['latlng1'][1] + '<br/>';
                result += ' ' + locator2.toUpperCase() + ' Latitude = ' + html['latlng2'][0] + ' Longitude = ' + html['latlng2'][1] + '<br/>';
                result += 'Distance between ' + locator1.toUpperCase() + ' and ' + locator2.toUpperCase() + ' is ' + html['distance'] + '.<br />';
                result += 'The bearing is ' + html['bearing'] + '.</h5>';
                
                $(".qrbResult").html(result);
                newpath(html['latlng1'], html['latlng2'], locator1, locator2);
            }
        });
    } else {
        $('.qrbResult').html('<div class="qrbalert alert alert-danger" role="alert">Error in locators. Please check.</div>');
    }
}

function validateLocator(locator) {
    if(locator.length < 4 && !(/^[a-rA-R]{2}[0-9]{2}[a-xA-X]{0,2}[0-9]{0,2}[a-xA-X]{0,2}$/.test(locator))) {
        return false;
    }
    
    return true;
}

function newpath(latlng1, latlng2, locator1, locator2) {
    // If map is already initialized
    var container = L.DomUtil.get('mapqrb');

    if(container != null){
        container._leaflet_id = null;
    }

    const map = new L.map('mapqrb').setView([30, 0], 1.5);

    var maidenhead = L.maidenheadqrb().addTo(map);

    var osmUrl='<?php echo $this->optionslib->get_option('option_map_tile_server');?>';
    var osmAttrib='Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors';
    var osm = new L.TileLayer(osmUrl, {minZoom: 1, maxZoom: 9, attribution: osmAttrib}); 

    var redIcon = L.icon({
					iconUrl: icon_dot_url,
					iconSize:     [10, 10], // size of the icon
				});

    map.addLayer(osm);

    var marker = L.marker([latlng1[0], latlng1[1]], {closeOnClick: false, autoClose: false}).addTo(map).bindPopup(locator1);
    var marker2 = L.marker([latlng2[0], latlng2[1]], {closeOnClick: false, autoClose: false}).addTo(map).bindPopup(locator2);

    const multiplelines = [];
		multiplelines.push(
            new L.LatLng(latlng1[0], latlng1[1]),
            new L.LatLng(latlng2[0], latlng2[1])
        )

    const geodesic = L.geodesic(multiplelines, {
        weight: 3,
        opacity: 1,
        color: 'red',
        wrap: false,
        steps: 100
    }).addTo(map);
}

function showActivatorsMap(call, count, grids) {

    let re = /,/g;
    grids = grids.replace(re, ', ');

    var result = "Callsign: "+call.replace('0', '&Oslash;')+"<br />";
    result +=    "Count: "+count+"<br/>";
    result +=    "Grids: "+grids+"<br/><br />";

    $(".activatorsmapResult").html(result);

    // If map is already initialized
    var container = L.DomUtil.get('mapactivators');

    if(container != null){
        container._leaflet_id = null;
    }

    const map = new L.map('mapactivators').setView([30, 0], 1.5);

    var grid_four = grids.split(', ');

    var maidenhead = new L.maidenheadactivators(grid_four).addTo(map);

    var osmUrl='<?php echo $this->optionslib->get_option('option_map_tile_server');?>';
    var osmAttrib='Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors';
    var osm = new L.TileLayer(osmUrl, {minZoom: 1, maxZoom: 9, attribution: osmAttrib}); 

    map.addLayer(osm);
}

// This displays the dialog with the form and it's where the resulttable is displayed
function spawnLookupModal() {
	$.ajax({
		url: base_url + 'index.php/lookup',
		type: 'post',
		success: function (html) {
			BootstrapDialog.show({
				title: 'Quick lookup',
				size: BootstrapDialog.SIZE_WIDE,
				cssClass: 'lookup-dialog',
				nl2br: false,
				message: html,
				onshown: function(dialog) {
					$('#quicklookuptype').change(function(){
						var type = $('#quicklookuptype').val();
						if (type == "dxcc") {
							$('#quicklookupdxcc').show();
							$('#quicklookupiota').hide();
							$('#quicklookupcqz').hide();
							$('#quicklookupwas').hide();
							$('#quicklookuptext').hide();
						} else if (type == "iota") {
							$('#quicklookupiota').show();
							$('#quicklookupdxcc').hide();
							$('#quicklookupcqz').hide();
							$('#quicklookupwas').hide();
							$('#quicklookuptext').hide();
						} else if (type == "grid" || type == "sota" || type == "wwff") {
							$('#quicklookuptext').show();
							$('#quicklookupiota').hide();
							$('#quicklookupdxcc').hide();
							$('#quicklookupcqz').hide();
							$('#quicklookupwas').hide();
						} else if (type == "cqz") {
							$('#quicklookupcqz').show();
							$('#quicklookupiota').hide();
							$('#quicklookupdxcc').hide();
							$('#quicklookupwas').hide();
							$('#quicklookuptext').hide();
						} else if (type == "was") {
							$('#quicklookupwas').show();
							$('#quicklookupcqz').hide();
							$('#quicklookupiota').hide();
							$('#quicklookupdxcc').hide();
							$('#quicklookuptext').hide();
						}
					});
				},
				buttons: [{
					label: 'Close',
					action: function (dialogItself) {
						dialogItself.close();
					}
				}]
			});
		}
	});
}

// This function executes the call to the backend for fetching queryresult and displays the table in the dialog
function getLookupResult() {
	$(".ld-ext-right").addClass('running');
	$(".ld-ext-right").prop('disabled', true);
	$.ajax({
		url: base_url + 'index.php/lookup/search',
		type: 'post',
		data: {
			type: $('#quicklookuptype').val(),
			dxcc: $('#quicklookupdxcc').val(),
			was:  $('#quicklookupwas').val(),
			grid: $('#quicklookuptext').val(),
			cqz:  $('#quicklookupcqz').val(),
			iota: $('#quicklookupiota').val(),
			sota: $('#quicklookuptext').val(),
			wwff: $('#quicklookuptext').val(),
		},
		success: function (html) {
			$('#lookupresulttable').html(html);
			$(".ld-ext-right").removeClass('running');
			$(".ld-ext-right").prop('disabled', false);
		}
	});
}

</script>

<?php if ($this->uri->segment(1) == "map" && $this->uri->segment(2) == "custom") { ?>
<!-- Javascript used for ADIF Import and Export Areas -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/tempusdominus-bootstrap-4.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/leaflet/L.Maidenhead.js"></script>
    <script id="leafembed" type="text/javascript" src="<?php echo base_url();?>assets/js/leaflet/leafembed.js" tileUrl="<?php echo $this->optionslib->get_option('option_map_tile_server');?>"></script>
    <script type="text/javascript">
      $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      });

        <?php if($qra == "set") { ?>
        var q_lat = <?php echo $qra_lat; ?>;
        var q_lng = <?php echo $qra_lng; ?>;
        <?php } else { ?>
        var q_lat = 40.313043;
        var q_lng = -32.695312;
        <?php } ?>

        var qso_loc = '<?php echo site_url('map/map_data_custom/');?><?php echo rawurlencode($date_from); ?>/<?php echo rawurlencode($date_to); ?>/<?php echo rawurlencode($this->input->post('band')); ?>/<?php echo rawurlencode($this->input->post('mode')); ?>/<?php echo rawurlencode($this->input->post('prop_mode')); ?>';
        var q_zoom = 3;

      $(document).ready(function(){
            <?php if ($this->config->item('map_gridsquares') != FALSE) { ?>
              var grid = "Yes";
            <?php } else { ?>
              var grid = "No";
            <?php } ?>
            initmap(grid, 'custommap');

      });
    </script>
<?php } ?>

<?php if ($this->uri->segment(1) == "map" && $this->uri->segment(2) == "") { ?>
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/leaflet/L.Maidenhead.js"></script>
    <script id="leafembed" type="text/javascript" src="<?php echo base_url();?>assets/js/leaflet/leafembed.js" tileUrl="<?php echo $this->optionslib->get_option('option_map_tile_server');?>"></script>
    <script type="text/javascript">
      $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      });

        <?php if($qra == "set") { ?>
        var q_lat = <?php echo $qra_lat; ?>;
        var q_lng = <?php echo $qra_lng; ?>;
        <?php } else { ?>
        var q_lat = 40.313043;
        var q_lng = -32.695312;
        <?php } ?>

        var qso_loc = '<?php echo site_url('map/map_data');?>';
        var q_zoom = 2;

      $(document).ready(function(){
            <?php if ($this->config->item('map_gridsquares') != FALSE) { ?>
              var grid = "Yes";
            <?php } else { ?>
              var grid = "No";
            <?php } ?>
            initmap(grid);

      });
    </script>
<?php } ?>

<?php if ($this->uri->segment(1) == "" || $this->uri->segment(1) == "dashboard" ) { ?>
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/leaflet/L.Maidenhead.js"></script>
    <script id="leafembed" type="text/javascript" src="<?php echo base_url();?>assets/js/leaflet/leafembed.js" tileUrl="<?php echo $this->optionslib->get_option('option_map_tile_server');?>"></script>
    <script type="text/javascript">
      $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      });

        <?php if($qra == "set") { ?>
        var q_lat = <?php echo $qra_lat; ?>;
        var q_lng = <?php echo $qra_lng; ?>;
        <?php } else { ?>
        var q_lat = 40.313043;
        var q_lng = -32.695312;
        <?php } ?>

        var qso_loc = '<?php echo site_url('dashboard/map');?>';
        var q_zoom = 3;

      $(document).ready(function(){
            <?php if ($this->config->item('map_gridsquares') != FALSE) { ?>
              var grid = "Yes";
            <?php } else { ?>
              var grid = "No";
            <?php } ?>
            initmap(grid);

      });
    </script>
<?php } ?>



<?php if ($this->uri->segment(1) == "radio") { ?>
<!-- If this is the admin/radio page run the JS -->
<script type="text/javascript">
    $(document).ready(function(){
        setInterval(function() {
            // Get Mode
            $.get('radio/status/', function(result) {
                    //$('.status').append(result);
                    $('.status').html(result);
            });
        }, 2000);
 });
</script>
<?php } ?>


<?php if ($this->uri->segment(1) == "search") { ?>
<script type="text/javascript">
  $(function () {
     $('[data-toggle="tooltip"]').tooltip()
  });
</script>
<script type="text/javascript">
i=0;

function searchButtonPress(){
    event.preventDefault()
    if ($('#callsign').val()) {
      let fixedcall = $('#callsign').val();
      $('#partial_view').load("logbook/search_result/" + fixedcall.replace('Ø', '0'), function() {
         $('[data-toggle="tooltip"]').tooltip()
      });
    }
}

$(document).ready(function(){

  <?php if($this->input->post('callsign') != "") { ?>
        $('#partial_view').load("logbook/search_result/<?php echo str_replace("Ø","0",$this->input->post('callsign')); ?>", function() {
           $('[data-toggle="tooltip"]').tooltip()
    });
  <?php } ?>

$(document).on('keypress',function(e) {
  if(e.which == 13) {

    if ($('#callsign').val()) {
        let fixedcall = $('#callsign').val();
        $('#partial_view').load("logbook/search_result/" + fixedcall.replace('Ø', '0'), function() {
           $('[data-toggle="tooltip"]').tooltip()
        });
    }

     event.preventDefault();
        return false;
  }
});


});
</script>
<?php } ?>

<?php if ($this->uri->segment(1) == "logbook" && $this->uri->segment(2) != "view") { ?>
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/leaflet/L.Maidenhead.js"></script>
    <script id="leafembed" type="text/javascript" src="<?php echo base_url();?>assets/js/leaflet/leafembed.js" tileUrl="<?php echo $this->optionslib->get_option('option_map_tile_server');?>"></script>
    <script type="text/javascript">
      $(function () {
         $('[data-toggle="tooltip"]').tooltip()
      });
    </script>
    <script type="text/javascript">
        <?php if($qra == "set") { ?>
        var q_lat = <?php echo $qra_lat; ?>;
        var q_lng = <?php echo $qra_lng; ?>;
        <?php } else { ?>
        var q_lat = 40.313043;
        var q_lng = -32.695312;
        <?php } ?>

        var qso_loc = '<?php echo site_url('logbook/qso_map/25/'.$this->uri->segment(3)); ?>';
        var q_zoom = 3;

        <?php if ($this->config->item('map_gridsquares') != FALSE) { ?>
              var grid = "Yes";
        <?php } else { ?>
              var grid = "No";
        <?php } ?>
            initmap(grid);

    </script>
<?php } ?>

<?php if ($this->uri->segment(1) == "qso") { ?>
<script src="<?php echo base_url() ;?>assets/js/sections/qso.js"></script>

<?php 

    $this->load->model('stations');
    $active_station_id = $this->stations->find_active();
    $station_profile = $this->stations->profile($active_station_id);
    $active_station_info = $station_profile->row();

    if (strpos($active_station_info->station_gridsquare, ',') !== false) {
        $gridsquareArray = explode(',', $active_station_info->station_gridsquare);
        $user_gridsquare = $gridsquareArray[0];
    } else {
        $user_gridsquare = $active_station_info->station_gridsquare;
    }
?>

<script>
  var markers = L.layerGroup();
  var pos = [51.505, -0.09];
  var mymap = L.map('qsomap').setView(pos, 12);
  <?php
  if ($active_station_info->station_gridsquare != "") { ?>
  $.getJSON('logbook/qralatlngjson/<?php echo $user_gridsquare; ?>', function(result) {
     mymap.panTo([result[0], result[1]]);
     pos = result;
  })
  <?php } else if (null !== $this->config->item('locator')) { ?>
  $.getJSON('logbook/qralatlngjson/<?php echo $this->config->item('locator'); ?>', function(result) {
     mymap.panTo([result[0], result[1]]);
     pos = result;
  })
  <?php } ?>

  L.tileLayer('<?php echo $this->optionslib->get_option('option_map_tile_server');?>', {
    maxZoom: 18,
    attribution: '<?php echo $this->optionslib->get_option('option_map_tile_server_copyright');?>',
    id: 'mapbox.streets'
  }).addTo(mymap);

</script>

  <script type="text/javascript">

    var manual = <?php echo $_GET['manual']; ?>;

    $(document).ready(function() {

    $('.callsign-suggest').hide();

    setRst($(".mode").val());

    /* On Page Load */
    var catcher = function() {
      var changed = false;
      $('form').each(function() {
        if ($(this).data('initialForm') != $(this).serialize()) {
          changed = true;
          $(this).addClass('changed');
        } else {
          $(this).removeClass('changed');
        }
      });
      if (changed) {
        return 'Unsaved QSO!';
      }
    };

    $(function() {
      $('form').each(function() {
        $(this).data('initialForm', $(this).serialize());
      }).submit(function(e) {
        var formEl = this;
        var changed = false;
        $('form').each(function() {
          if (this != formEl && $(this).data('initialForm') != $(this).serialize()) {
            changed = true;
            $(this).addClass('changed');
          } else {
            $(this).removeClass('changed');
          }
        });
        if (changed && !confirm('You have an unsaved QSO. Continue with QSO?')) {
          e.preventDefault();
        } else {
          $(window).unbind('beforeunload', catcher);
        }
      });
      $(window).bind('beforeunload', catcher);
    });

     // Callsign always has focus on load
      $("#callsign").focus();

      if ( ! manual ) {
        $(function($) {
          var options = {
            utc: true,
            format: '%H:%M:%S'
          }
          $('.input_time').jclock(options);
        });

        $(function($) {
          var options = {
            utc: true,
            format: '%d-%m-%Y'
          }
          $('.input_date').jclock(options);
        });
      }
    });



  jQuery(function($) {
  var input = $('#callsign');
  input.on('keydown', function() {
    var key = event.keyCode || event.charCode;

    if( key == 8 || key == 46 ) {
      reset_fields();
    }
  });

  $(document).keyup(function(e) {
     if (e.key === "Escape") { // escape key maps to keycode `27`
       reset_fields();
	   $('#callsign').val("");
	   $("#callsign").focus();
    }
  });
});

<?php if ($this->session->userdata('user_sota_lookup') == 1) { ?>
	$('#sota_ref').change(function() {
		var sota = $('#sota_ref').val();
		if (sota.length > 0) {
			$.ajax({
				url: base_url+'index.php/qso/get_sota_info',
				type: 'post',
				data: {'sota': sota},
				success: function(res) {
					$('#qth').val(res.name);
					$('#locator').val(res.locator);
				},
				error: function() {
					$('#qth').val('');
					$('#locator').val('');
				},
			});
		}
	});
<?php } ?>

<?php if ($this->config->item('qso_auto_qth')) { ?>
    $('#qth').focusout(function() {
    	if ($('#locator').val() === '') {
			var lat = 0;
			var lon = 0;
			$.ajax({
				async: false,
				type: 'GET',
				dataType: "json",
				url: "https://nominatim.openstreetmap.org/search/?city=" + $(this).val() + "&format=json&addressdetails=1&limit=1",
				data: {},
				success: function (data) {
					if (typeof data[0].lat !== 'undefined') {
						lat = parseFloat(data[0].lat);
					}
					if (typeof data[0].lon !== 'undefined') {
						lon = parseFloat(data[0].lon);
					}
				},
			});
			if (lat !== 0 && lon !== 0) {
				var qthloc = LatLng2Loc(lat, lon, 10);
				if (qthloc.length > 0) {
					$('#locator').val(qthloc.substr(0, 6)).trigger('focusout');
				}
			}
		}
	});

	LatLng2Loc = function(y, x, num) {
		if (x < -180) {
			x = x + 360;
		}
		if (x > 180) {
			x = x - 360;
		}
		var yqth, yi, yk, ydiv, yres, ylp, y;
		var ycalc = new Array(0, 0, 0);
		var yn = new Array(0, 0, 0, 0, 0, 0, 0);

		var ydiv_arr = new Array(10, 1, 1 / 24, 1 / 240, 1 / 240 / 24);
		ycalc[0] = (x + 180) / 2;
		ycalc[1] = y + 90;

		for (yi = 0; yi < 2; yi++) {
			for (yk = 0; yk < 5; yk++) {
				ydiv = ydiv_arr[yk];
				yres = ycalc[yi] / ydiv;
				ycalc[yi] = yres;
				if (ycalc[yi] > 0) ylp = Math.floor(yres); else ylp = Math.ceil(yres);
				ycalc[yi] = (ycalc[yi] - ylp) * ydiv;
				yn[2 * yk + yi] = ylp;
			}
		}

		var qthloc = "";
		if (num >= 2) qthloc += String.fromCharCode(yn[0] + 0x41) + String.fromCharCode(yn[1] + 0x41);
		if (num >= 4) qthloc += String.fromCharCode(yn[2] + 0x30) + String.fromCharCode(yn[3] + 0x30);
		if (num >= 6) qthloc += String.fromCharCode(yn[4] + 0x41) + String.fromCharCode(yn[5] + 0x41);
		if (num >= 8) qthloc += ' ' + String.fromCharCode(yn[6] + 0x30) + String.fromCharCode(yn[7] + 0x30);
		if (num >= 10) qthloc += String.fromCharCode(yn[8] + 0x61) + String.fromCharCode(yn[9] + 0x61);
		return qthloc;
	}
	<?php } ?>

  </script>

<?php } ?>
<?php if ( $this->uri->segment(1) == "qso" || ($this->uri->segment(1) == "contesting" && $this->uri->segment(2) != "add")) { ?>
    <script>
    function setRst(mode) {
        if(mode == 'JT65' || mode == 'JT65B' || mode == 'JT6C' || mode == 'JTMS' || mode == 'ISCAT' || mode == 'MSK144' || mode == 'JTMSK' || mode == 'QRA64' || mode == 'FT8' || mode == 'FT4' || mode == 'JS8' || mode == 'JT9' || mode == 'JT9-1' || mode == 'ROS'){
            $('#rst_sent').val('-5');
            $('#rst_recv').val('-5');
        } else if (mode == 'FSK441' || mode == 'JT6M') {
            $('#rst_sent').val('26');
            $('#rst_recv').val('26');
        } else if (mode == 'CW' || mode == 'RTTY' || mode == 'PSK31' || mode == 'PSK63') {
            $('#rst_sent').val('599');
            $('#rst_recv').val('599');
        } else {
            $('#rst_sent').val('59');
            $('#rst_recv').val('59');
        }
    }
    </script>

    <script>
    // Javascript for controlling rig frequency.
    var updateFromCAT = function() {
    if($('select.radios option:selected').val() != '0') {
      radioID = $('select.radios option:selected').val();
      $.getJSON( "radio/json/" + radioID, function( data ) {
          /* {
              "frequency": "2400210000",
              "frequency_rx": "10489710000",
              "mode": "SSB",
              "satmode": "S/X",
              "satname": "QO-100"
              "power": "20"
              "prop_mode": "SAT"
          }  */
          $('#frequency').val(data.frequency);
          $("#band").val(frequencyToBand(data.frequency));
          if (data.frequency_rx != "") {
            $('#frequency_rx').val(data.frequency_rx);
            $("#band_rx").val(frequencyToBand(data.frequency_rx));
          }

          old_mode = $(".mode").val();
          $(".mode").val(data.mode);

          if (old_mode !== $(".mode").val()) {
            // Update RST on mode change via CAT
            setRst($(".mode").val());
          }
          $("#sat_name").val(data.satname);
          $("#sat_mode").val(data.satmode);
          if(data.power != 0) {
            $("#transmit_power").val(data.power);
          }
          $("#selectPropagation").val(data.prop_mode);

          // Display CAT Timeout warning based on the figure given in the config file
          var minutes = Math.floor(<?php echo $this->optionslib->get_option('cat_timeout_interval'); ?> / 60);

          if(data.updated_minutes_ago > minutes) {
            if($('.radio_timeout_error').length == 0) {
              $('.qso_panel').prepend('<div class="alert alert-danger radio_timeout_error" role="alert">Radio connection timed-out: ' + $('select.radios option:selected').text() + ' data is ' + data.updated_minutes_ago + ' minutes old.</div>');
            } else {
              $('.radio_timeout_error').text('Radio connection timed-out: ' + $('select.radios option:selected').text() + ' data is ' + data.updated_minutes_ago + ' minutes old.');
            }
          } else {
            $(".radio_timeout_error" ).remove();
          }

      });
    }
  };

  // Update frequency every three second
  setInterval(updateFromCAT, 3000);

  // If a radios selected from drop down select radio update.
  $('.radios').change(updateFromCAT);

  // If no radio is selected clear data
  $( ".radios" ).change(function() {
      if ($(".radios option:selected").val() == 0) {
        $("#sat_name").val("");
        $("#sat_mode").val("");
        $("#frequency").val("");
        $("#frequency_rx").val("");
        $("#band_rx").val("");
        $("#selectPropagation").val($("#selectPropagation option:first").val());
        $(".radio_timeout_error" ).remove();
      }
  });
  </script>

<?php } ?>

<?php if ($this->uri->segment(1) == "logbook" && $this->uri->segment(2) == "view") { ?>
<script>

  var mymap = L.map('map').setView([lat,long], 5);

  L.tileLayer('<?php echo $this->optionslib->get_option('option_map_tile_server');?>', {
    maxZoom: 18,
    attribution: '<?php echo $this->optionslib->get_option('option_map_tile_server_copyright');?>',
    id: 'mapbox.streets'
  }).addTo(mymap);



  var printer = L.easyPrint({
      		tileLayer: tiles,
      		sizeModes: ['Current', 'A4Landscape', 'A4Portrait'],
      		filename: 'myMap',
      		exportOnly: true,
      		hideControlContainer: true
		}).addTo(mymap);

  var redIcon = L.icon({
      iconUrl: icon_dot_url,
      iconSize:     [18, 18], // size of the icon
  });

  L.marker([lat,long], {icon: redIcon}).addTo(mymap)
    .bindPopup(callsign);

  mymap.on('click', onMapClick);

</script>
<?php } ?>

<?php if ($this->uri->segment(1) == "update") { ?>
<script>
$(document).ready(function(){
    $('#btn_update_dxcc').bind('click', function(){
        $('#dxcc_update_status').show();
        $.ajax({url:"update/dxcc"});
        setTimeout(update_stats,5000);
    });
    function update_stats(){
        $('#dxcc_update_status').load('<?php echo base_url()?>updates/status.html', function(val){
            $('#dxcc_update_staus').html(val);

            if ((val  === null) || (val.substring(0,4) !="DONE")){
                setTimeout(update_stats, 5000);
            }
        });

    }

});
</script>

<?php } ?>

<?php if ($this->uri->segment(1) == "gridsquares" && !empty($this->uri->segment(2))) { ?>

<script type="text/javascript" src="<?php echo base_url();?>assets/js/leaflet/L.MaidenheadColoured.js"></script>

<script>

  var layer = L.tileLayer('<?php echo $this->optionslib->get_option('option_map_tile_server');?>', {
    maxZoom: 18,
    attribution: '<?php echo $this->optionslib->get_option('option_map_tile_server_copyright');?>',
    id: 'mapbox.streets'
  });

  var map = L.map('gridsquare_map', {
    layers: [layer],
    center: [19, 0],
    zoom: 2,
    minZoom: 1
  });

  var printer = L.easyPrint({
        tileLayer: layer,
        sizeModes: ['Current'],
        filename: 'myMap',
        exportOnly: true,
        hideControlContainer: true
    }).addTo(map);

  var grid_two = <?php echo $grid_2char; ?>;
  var grid_four = <?php echo $grid_4char; ?>;
  var grid_six = <?php echo $grid_6char; ?>;

  var grid_two_count = grid_two.length;
  var grid_four_count = grid_four.length;
  var grid_six_count = grid_six.length;

  var grid_two_confirmed = <?php echo $grid_2char_confirmed; ?>;
  var grid_four_confirmed = <?php echo $grid_4char_confirmed; ?>;
  var grid_six_confirmed = <?php echo $grid_6char_confirmed; ?>;

  var grid_two_confirmed_count = grid_two_confirmed.length;
  var grid_four_confirmed_count = grid_four_confirmed.length;
  var grid_six_confirmed_count = grid_six_confirmed.length;

  if (grid_four_confirmed_count > 0) {
     var span = document.getElementById('confirmed_grids');
     span.innerText = span.textContent = '('+grid_four_confirmed_count+' grid square'+(grid_four_confirmed_count != 1 ? 's' : '')+') ';
  }
  if ((grid_four_count-grid_four_confirmed_count) > 0) {
     var span = document.getElementById('worked_grids');
     span.innerText = span.textContent = '('+(grid_four_count-grid_four_confirmed_count)+' grid square'+(grid_four_count-grid_four_confirmed_count != 1 ? 's' : '')+') ';
  }
  var span = document.getElementById('sum_grids');
  span.innerText = span.textContent = ' Total Count: '+grid_four_count+' grid square'+(grid_four_count != 1 ? 's' : '');

  var maidenhead = L.maidenhead().addTo(map);

  map.on('click', onMapClick);

  function onMapClick(event) {
    var LatLng = event.latlng;
    var lat = LatLng.lat;
    var lng = LatLng.lng;
    var locator = LatLng2Loc(lat,lng, 10);
    var loc_4char = locator.substring(0, 4);
    console.log(loc_4char);
    console.log(map.getZoom());

    if(map.getZoom() > 2) {
    	<?php if ($this->session->userdata('user_callsign')) { ?>
	  var band = '';
      var search_type = "<?php echo $this->uri->segment(2); ?>";
      if(search_type == "satellites") {
		band = 'SAT';
      } else {
        band = "<?php echo $this->uri->segment(3); ?>";
      }
		$(".modal-body").empty();
		  $.ajax({
			  url: base_url + 'index.php/awards/qso_details_ajax',
			  type: 'post',
			  data: {
				  'Searchphrase': loc_4char,
				  'Band': band,
				  'Mode': 'All',
				  'Type': 'VUCC'
			  },
			  success: function (html) {
				$(".modal-body").html(html);
				  $(".modal-body table").addClass('table-sm');
				  $(".modal-body h5").empty();
				  var count = $('.table tr').length;
				  count = count - 1;
				  $('#qso_count').text(count);
				  if (count > 1) {
					  $('#gt1_qso').text("s");
				  } else {
					  $('#gt1_qso').text("");
				  }

				  if (count > 0) {
					  $('#square_number').text(loc_4char);
					  $('#exampleModal').modal('show');
					  $('[data-toggle="tooltip"]').tooltip({ boundary: 'window' });
				  }
			  }
		  });
		  <?php } ?>
    }
  };

<?php if ($this->uri->segment(1) == "gridsquares" && $this->uri->segment(2) == "band") { ?>

  var bands_available = <?php echo $bands_available; ?>;
  $('#gridsquare_bands').append('<option value="All">All</option>')
  $.each(bands_available, function(key, value) {
     $('#gridsquare_bands')
         .append($("<option></option>")
                    .attr("value",value)
                    .text(value));
  });

  var num = "<?php echo $this->uri->segment(3);?>";
    $("#gridsquare_bands option").each(function(){
        if($(this).val()==num){ // EDITED THIS LINE
            $(this).attr("selected","selected");
        }
    });

  $(function(){
      // bind change event to select
      $('#gridsquare_bands').on('change', function () {
          var url = $(this).val(); // get selected value
          if (url) { // require a URL
              window.location = "<?php echo site_url('gridsquares/band/');?>" + url
          }
          return false;
      });
    });
<?php } ?>

</script>
<?php } ?>

<?php if ($this->uri->segment(1) == "activated_grids" && !empty($this->uri->segment(2))) { ?>

<script type="text/javascript" src="<?php echo base_url();?>assets/js/leaflet/L.MaidenheadColoured.js"></script>

<script>
  var layer = L.tileLayer('<?php echo $this->optionslib->get_option('option_map_tile_server');?>', {
    maxZoom: 18,
    attribution: '<?php echo $this->optionslib->get_option('option_map_tile_server_copyright');?>',
    id: 'mapbox.streets'
  });


  var map = L.map('gridsquare_map', {
    layers: [layer],
    center: [19, 0],
    zoom: 2,
    minZoom: 1
  });

  var grid_two = <?php echo $grid_2char; ?>;
  var grid_four = <?php echo $grid_4char; ?>;
  var grid_six = <?php echo $grid_6char; ?>;

  var grid_two_count = grid_two.length;
  var grid_four_count = grid_four.length;
  var grid_six_count = grid_six.length;

  var grid_two_confirmed = <?php echo $grid_2char_confirmed; ?>;
  var grid_four_confirmed = <?php echo $grid_4char_confirmed; ?>;
  var grid_six_confirmed = <?php echo $grid_6char_confirmed; ?>;

  var grid_two_confirmed_count = grid_two_confirmed.length;
  var grid_four_confirmed_count = grid_four_confirmed.length;
  var grid_six_confirmed_count = grid_six_confirmed.length;

  if (grid_four_confirmed_count > 0) {
     var span = document.getElementById('confirmed_grids');
     span.innerText = span.textContent = '('+grid_four_confirmed_count+' grid square'+(grid_four_confirmed_count != 1 ? 's' : '')+') ';
  }
  if ((grid_four_count-grid_four_confirmed_count) > 0) {
     var span = document.getElementById('activated_grids');
     span.innerText = span.textContent = '('+(grid_four_count-grid_four_confirmed_count)+' grid square'+(grid_four_count-grid_four_confirmed_count != 1 ? 's' : '')+') ';
  }
  var span = document.getElementById('sum_grids');
  span.innerText = span.textContent = ' Total Count: '+grid_four_count+' grid square'+(grid_four_count != 1 ? 's' : '');

  var maidenhead = L.maidenhead().addTo(map);

  map.on('click', onMapClick);

  function onMapClick(event) {
    var LatLng = event.latlng;
    var lat = LatLng.lat;
    var lng = LatLng.lng;
    var locator = LatLng2Loc(lat,lng, 10);
    var loc_4char = locator.substring(0, 4);
    console.log(loc_4char);
    console.log(map.getZoom());

    if(map.getZoom() > 2) {
    	<?php if ($this->session->userdata('user_callsign')) { ?>
	  var band = '';
      var search_type = "<?php echo $this->uri->segment(2); ?>";
      if(search_type == "satellites") {
		band = 'SAT';
      } else {
        band = "<?php echo $this->uri->segment(3); ?>";
      }
		$(".modal-body").empty();
		  $.ajax({
			  url: base_url + 'index.php/activated_grids/qso_details_ajax',
			  type: 'post',
			  data: {
				  'Searchphrase': loc_4char,
				  'Band': band,
				  'Mode': 'All',
			  },
			  success: function (html) {
				$(".modal-body").html(html);
				  $(".modal-body table").addClass('table-sm');
				  $(".modal-body h5").empty();
				  var count = $('.table tr').length;
				  count = count - 1;
				  $('#qso_count').text(count);
				  if (count > 1) {
					  $('#gt1_qso').text("s");
				  } else {
					  $('#gt1_qso').text("");
				  }

				  if (count > 0) {
					  $('#square_number').text(loc_4char);
					  $('#exampleModal').modal('show');
					  $('[data-toggle="tooltip"]').tooltip({ boundary: 'window' });
				  }
			  }
		  });
		  <?php } ?>
    }
  };

<?php if ($this->uri->segment(1) == "activated_grids" && $this->uri->segment(2) == "band") { ?>

  var bands_available = <?php echo $bands_available; ?>;
  $('#gridsquare_bands').append('<option value="All">All</option>')
  $.each(bands_available, function(key, value) {
     $('#gridsquare_bands')
         .append($("<option></option>")
                    .attr("value",value)
                    .text(value));
  });

  var num = "<?php echo $this->uri->segment(3);?>";
    $("#gridsquare_bands option").each(function(){
        if($(this).val()==num){ // EDITED THIS LINE
            $(this).attr("selected","selected");
        }
    });

  $(function(){
      // bind change event to select
      $('#gridsquare_bands').on('change', function () {
          var url = $(this).val(); // get selected value
          if (url) { // require a URL
              window.location = "<?php echo site_url('activated_grids/band/');?>" + url
          }
          return false;
      });
    });
<?php } ?>

</script>
<?php } ?>

<?php if ($this->uri->segment(1) == "dayswithqso") { ?>
    <script src="<?php echo base_url(); ?>assets/js/chart.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/sections/dayswithqso.js"></script>
<?php } ?>

<?php if ($this->uri->segment(1) == "distances") { ?>
    <script src="<?php echo base_url(); ?>assets/js/highstock.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/highstock/exporting.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/highstock/offline-exporting.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/highstock/export-data.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/sections/distances.js"></script>
<?php } ?>

    <?php if ($this->uri->segment(2) == "import") { ?>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/tempusdominus-bootstrap-4.min.js"></script>
        <script type="text/javascript">
            $(function () {
                $('#datetimepicker1').datetimepicker({
                    format: 'DD/MM/YYYY',
                });
            });
        </script>
    <?php } ?>

    <?php if ($this->uri->segment(1) == "qrz") { ?>
		<script src="<?php echo base_url(); ?>assets/js/sections/qrzlogbook.js"></script>
    <?php } ?>

	<script>
		function displayQso(id) {
			var baseURL= "<?php echo base_url();?>";
			$.ajax({
				url: baseURL + 'index.php/logbook/view/' + id,
				type: 'post',
				success: function(html) {
					BootstrapDialog.show({
						title: 'QSO Data',
						cssClass: 'qso-dialog',
						size: BootstrapDialog.SIZE_WIDE,
						nl2br: false,
						message: html,
						onshown: function(dialog) {
							var qsoid = $("#qsoid").text();
							$(".editButton").html('<a class="btn btn-primary" id="edit_qso" href="javascript:qso_edit('+qsoid+')"><i class="fas fa-edit"></i> Edit QSO</a>');
							var lat = $("#lat").text();
							var long = $("#long").text();
							var callsign = $("#callsign").text();
							var mymap = L.map('mapqso').setView([lat,long], 5);

							var tiles = L.tileLayer('<?php echo $this->optionslib->get_option('option_map_tile_server');?>', {
								maxZoom: 18,
								attribution: '<?php echo $this->optionslib->get_option('option_map_tile_server_copyright');?>',
							}).addTo(mymap);

                            
                            var printer = L.easyPrint({
                                tileLayer: tiles,
                                sizeModes: ['Current'],
                                filename: 'myMap',
                                exportOnly: true,
                                hideControlContainer: true
                            }).addTo(mymap);

							var redIcon = L.icon({
								iconUrl: icon_dot_url,
								iconSize:     [18, 18], // size of the icon
							});

							L.marker([lat,long], {icon: redIcon}).addTo(mymap)
								.bindPopup(callsign);

						},
					});

				}
			});
		}
		</script>


<?php if ($this->uri->segment(2) == "dxcc") { ?>
<script>
    $('.tabledxcc').DataTable({
        "pageLength": 25,
        responsive: false,
        ordering: false,
        "scrollY":        "400px",
        "scrollCollapse": true,
        "paging":         false,
        "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            'csv'
        ]
    });

    $('.tablesummary').DataTable({
        info: false,
        searching: false,
        ordering: false,
        "paging":         false,
        dom: 'Bfrtip',
        buttons: [
            'csv'
        ]
    });

    // change color of csv-button if dark mode is chosen
    if (isDarkModeTheme()) {
        $(".buttons-csv").css("color", "white");
    }
 </script>
    <?php } ?>

<?php if ($this->uri->segment(2) == "vucc_band") { ?>
    <script>
    $('.tablevucc').DataTable({
        "pageLength": 25,
        responsive: false,
        ordering: false,
        "scrollY":        "400px",
        "scrollCollapse": true,
        "paging":         false,
        "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            'csv'
        ]
    });

    // change color of csv-button if dark mode is chosen
    if (isDarkModeTheme()) {
        $(".buttons-csv").css("color", "white");
    }
    </script>
<?php } ?>


<?php if ($this->uri->segment(2) == "dok") { ?>
    <script>
        function displayDokContacts(dok, band) {
            var baseURL= "<?php echo base_url();?>";
            $.ajax({
                url: baseURL + 'index.php/awards/dok_details_ajax',
                type: 'post',
                data: {'DOK': dok,
                    'Band': band
                },
                success: function(html) {
                    BootstrapDialog.show({
                        title: 'QSO Data',
                        size: BootstrapDialog.SIZE_WIDE,
                        cssClass: 'qso-dok-dialog',
                        nl2br: false,
                        message: html,
                        onshown: function(dialog) {
                           $('[data-toggle="tooltip"]').tooltip();
                        },
                        buttons: [{
                            label: 'Close',
                            action: function (dialogItself) {
                                dialogItself.close();
                            }
                        }]
                    });
                }
            });
        }
    </script>
<?php } ?>

<?php if ($this->uri->segment(2) == "iota") { ?>
    <script>

        $('.tableiota').DataTable({
            "pageLength": 25,
            responsive: false,
            ordering: false,
            "scrollY":        "400px",
            "scrollCollapse": true,
            "paging":         false,
            "scrollX": true,
            dom: 'Bfrtip',
            buttons: [
                'csv'
            ]
        });

        $('.tablesummary').DataTable({
            info: false,
            searching: false,
            ordering: false,
            "paging":         false,
            dom: 'Bfrtip',
            buttons: [
                'csv'
            ]
        });

        // change color of csv-button if dark mode is chosen
        if (isDarkModeTheme()) {
            $(".buttons-csv").css("color", "white");
        }
    </script>

<?php } ?>

<?php if ($this->uri->segment(2) == "cq") { ?>
    <script>
        $('.tablecq').DataTable({
            "pageLength": 25,
            responsive: false,
            ordering: false,
            "scrollY":        "400px",
            "scrollCollapse": true,
            "paging":         false,
            "scrollX": true,
            dom: 'Bfrtip',
            buttons: [
                'csv'
            ]
        });

        $('.tablesummary').DataTable({
            info: false,
            searching: false,
            ordering: false,
            "paging":         false,
            dom: 'Bfrtip',
            buttons: [
                'csv'
            ]
        });

        // change color of csv-button if dark mode is chosen
        if (isDarkModeTheme()) {
            $(".buttons-csv").css("color", "white");
        }
    </script>
<?php } ?>

<?php if ($this->uri->segment(2) == "was") { ?>
    <script>
        $('.tablewas').DataTable({
            "pageLength": 25,
            responsive: false,
            ordering: false,
            "scrollY":        "400px",
            "scrollCollapse": true,
            "paging":         false,
            "scrollX": true,
            dom: 'Bfrtip',
            buttons: [
                'csv'
            ]
        });

        $('.tablesummary').DataTable({
            info: false,
            searching: false,
            ordering: false,
            "paging":         false,
            dom: 'Bfrtip',
            buttons: [
                'csv'
            ]
        });

        // change color of csv-button if dark mode is chosen
        if (isDarkModeTheme()) {
            $(".buttons-csv").css("color", "white");
        }
    </script>
<?php } ?>

<script>
        function qsl_rcvd(id, method) {
            var baseURL= "<?php echo base_url();?>";
            $.ajax({
                url: baseURL + 'index.php/qso/qsl_rcvd_ajax',
                type: 'post',
                data: {'id': id,
                    'method': method
                },
                success: function(data) {
                    if (data.message == 'OK') {
                        $("#qso_" + id).find("td:eq(8)").find("span:eq(1)").attr('class', 'qsl-green'); // Paints arrow green
                        $(".qsl_rcvd_" + id).remove(); // removes choice from menu
                    }
                    else {
                        $(".bootstrap-dialog-message").append('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>You are not allowed to update QSL status!</div>');
                    }
                }
            });
        }

        function qsl_sent(id, method) {
            var baseURL= "<?php echo base_url();?>";
            $.ajax({
                url: baseURL + 'index.php/qso/qsl_sent_ajax',
                type: 'post',
                data: {'id': id,
                    'method': method
                },
                success: function(data) {
                    if (data.message == 'OK') {
                        $("#qso_" + id).find("td:eq(8)").find("span:eq(0)").attr('class', 'qsl-green'); // Paints arrow green
                        $(".qsl_sent_" + id).remove(); // removes choice from menu
                    }
                    else {
                        $(".bootstrap-dialog-message").append('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>You are not allowed to update QSL status!</div>');
                    }
                }
            });
        }

        // Function: qsl_requested
        // Marks QSL card requested against the QSO.
        function qsl_requested(id, method) {
            var baseURL= "<?php echo base_url();?>";
            $.ajax({
                url: baseURL + 'index.php/qso/qsl_requested_ajax',
                type: 'post',
                data: {'id': id,
                    'method': method
                },
                success: function(data) {
                    if (data.message == 'OK') {
                        $("#qso_" + id).find("td:eq(8)").find("span:eq(0)").attr('class', 'qsl-yellow'); // Paints arrow green
                    }
                    else {
                        $(".bootstrap-dialog-message").append('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>You are not allowed to update QSL status!</div>');
                    }
                }
            });
        }

        // Function: qsl_ignore
        // Marks QSL card ignore against the QSO.
        function qsl_ignore(id, method) {
            var baseURL= "<?php echo base_url();?>";
            $.ajax({
                url: baseURL + 'index.php/qso/qsl_ignore_ajax',
                type: 'post',
                data: {'id': id,
                    'method': method
                },
                success: function(data) {
                    if (data.message == 'OK') {
                        $("#qso_" + id).find("td:eq(8)").find("span:eq(0)").attr('class', 'qsl-grey'); // Paints arrow grey
                    }
                    else {
                        $(".bootstrap-dialog-message").append('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>You are not allowed to update QSL status!</div>');
                    }
                }
            });
        }

        function qso_delete(id, call) {
            BootstrapDialog.confirm({
                title: 'DANGER',
                message: 'Warning! Are you sure you want delete QSO with ' + call + '?' ,
                type: BootstrapDialog.TYPE_DANGER,
                closable: true,
                draggable: true,
                btnOKClass: 'btn-danger',
                callback: function(result) {
                    if(result) {
                        $(".edit-dialog").modal('hide');
                        $(".qso-dialog").modal('hide');
                        var baseURL= "<?php echo base_url();?>";
                        $.ajax({
                            url: baseURL + 'index.php/qso/delete_ajax',
                            type: 'post',
                            data: {'id': id
                            },
                            success: function(data) {
                                $(".alert").remove();
                                $(".bootstrap-dialog-message").prepend('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>The contact with ' + call + ' has been deleted!</div>');
                                $("#qso_" + id).remove(); // removes qso from table in dialog
                            }
                        });
                    }
                }
            });
        }

        function qso_edit(id) {
            var baseURL= "<?php echo base_url();?>";
            $.ajax({
                url: baseURL + 'index.php/qso/edit_ajax',
                type: 'post',
                data: {'id': id
                },
                success: function(html) {
                    BootstrapDialog.show({
                        title: 'QSO Data',
                        cssClass: 'edit-dialog',
                        size: BootstrapDialog.SIZE_WIDE,
                        nl2br: false,
                        message: html,
                        onshown: function(dialog) {
                            var state = $("#input_usa_state_edit option:selected").text();
                            if (state != "") {
                                $("#stationCntyInputEdit").prop('disabled', false);
                                selectize_usa_county();
                            }

                            $('#input_usa_state_edit').change(function(){
                                var state = $("#input_usa_state_edit option:selected").text();
                                if (state != "") {
                                    $("#stationCntyInputEdit").prop('disabled', false);

                                    selectize_usa_county();

                                } else {
                                    $("#stationCntyInputEdit").prop('disabled', true);
                                    //$('#stationCntyInput')[0].selectize.destroy();
                                    $("#stationCntyInputEdit").val("");
                                }
                            });

                            $('#sota_ref_edit').selectize({
                                maxItems: 1,
                                closeAfterSelect: true,
                                loadThrottle: 250,
                                valueField: 'name',
                                labelField: 'name',
                                searchField: 'name',
                                options: [],
                                create: false,
                                load: function(query, callback) {
                                    if (!query || query.length < 3) return callback();  // Only trigger if 3 or more characters are entered
                                    $.ajax({
                                        url: baseURL+'index.php/qso/get_sota',
                                        type: 'GET',
                                        dataType: 'json',
                                        data: {
                                            query: query,
                                        },
                                        error: function() {
                                            callback();
                                        },
                                        success: function(res) {
                                            callback(res);
                                        }
                                    });
                                }
                            });

                            $('#wwff_ref_edit').selectize({
                                maxItems: 1,
                                closeAfterSelect: true,
                                loadThrottle: 250,
                                valueField: 'name',
                                labelField: 'name',
                                searchField: 'name',
                                options: [],
                                create: false,
                                load: function(query, callback) {
                                    if (!query || query.length < 3) return callback();  // Only trigger if 3 or more characters are entered
                                    $.ajax({
                                        url: baseURL+'index.php/qso/get_wwff',
                                        type: 'GET',
                                        dataType: 'json',
                                        data: {
                                            query: query,
                                        },
                                        error: function() {
                                            callback();
                                        },
                                        success: function(res) {
                                            callback(res);
                                        }
                                    });
                                }
                            });

                            $('#darc_dok_edit').selectize({
                                maxItems: 1,
                                closeAfterSelect: true,
                                loadThrottle: 250,
                                valueField: 'name',
                                labelField: 'name',
                                searchField: 'name',
                                options: [],
                                create: true,
                                load: function(query, callback) {
                                    if (!query) return callback();  // Only trigger if 3 or more characters are entered
                                    $.ajax({
                                        url: baseURL+'index.php/qso/get_dok',
                                        type: 'GET',
                                        dataType: 'json',
                                        data: {
                                            query: query,
                                        },
                                        error: function() {
                                            callback();
                                        },
                                        success: function(res) {
                                            callback(res);
                                        }
                                    });
                                }
                            });
                        },
                    });
                }
            });
        }

        function selectize_usa_county() {
            var baseURL= "<?php echo base_url();?>";
            $('#stationCntyInputEdit').selectize({
				delimiter: ';',
                maxItems: 1,
                closeAfterSelect: true,
                loadThrottle: 250,
                valueField: 'name',
                labelField: 'name',
                searchField: 'name',
                options: [],
                create: false,
                load: function(query, callback) {
                    var state = $("#input_usa_state_edit option:selected").text();

                    if (!query || state == "") return callback();
                    $.ajax({
                        url: baseURL+'index.php/qso/get_county',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            query: query,
                            state: state,
                        },
                        error: function() {
                            callback();
                        },
                        success: function(res) {
                            callback(res);
                        }
                    });
                }
            });
        }

        function qso_save() {
            var baseURL= "<?php echo base_url();?>";
            var myform = document.getElementById("qsoform");
            var fd = new FormData(myform);
            $.ajax({
                url: baseURL + 'index.php/qso/qso_save_ajax',
                data: fd,
                cache: false,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function (dataofconfirm) {
                    $(".edit-dialog").modal('hide');
                    $(".qso-dialog").modal('hide');
                    <?php if ($this->uri->segment(1) != "search" && $this->uri->segment(2) != "filter" && $this->uri->segment(1) != "qso") { ?>location.reload();<?php } ?>
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }
        </script>
    <?php if ($this->uri->segment(1) == "timeline") { ?>
        <script>
            $('.timelinetable').DataTable({
                "pageLength": 25,
                responsive: false,
                ordering: false,
                "scrollY":        "500px",
                "scrollCollapse": true,
                "paging":         false,
                "scrollX": true,
                dom: 'Bfrtip',
                buttons: [
                    'csv'
                ]
            });

            // change color of csv-button if dark mode is chosen
            if (isDarkModeTheme()) {
                $(".buttons-csv").css("color", "white");
            }

            function displayTimelineContacts(querystring, band, mode, type) {
                var baseURL= "<?php echo base_url();?>";
                $.ajax({
                    url: baseURL + 'index.php/timeline/details',
                    type: 'post',
                    data: {'Querystring': querystring,
                        'Band': band,
                        'Mode': mode,
                        'Type': type
                    },
                    success: function(html) {
                        BootstrapDialog.show({
                            title: 'QSO Data',
                            size: BootstrapDialog.SIZE_WIDE,
                            cssClass: 'qso-was-dialog',
                            nl2br: false,
                            message: html,
                            onshown: function(dialog) {
                               $('[data-toggle="tooltip"]').tooltip();
                            },
                            buttons: [{
                                label: 'Close',
                                action: function (dialogItself) {
                                    dialogItself.close();
                                }
                            }]
                        });
                    }
                });
            }
        </script>
        <?php } ?>
    <?php if ($this->uri->segment(1) == "activators") { ?>
        <script>
            $('.activatorstable').DataTable({
                "pageLength": 25,
                responsive: false,
                ordering: false,
                "scrollY":        "500px",
                "scrollCollapse": true,
                "paging":         false,
                "scrollX": true,
                dom: 'Bfrtip',
                buttons: [
                    'csv'
                ]
            });

            // change color of csv-button if dark mode is chosen
            if (isDarkModeTheme()) {
                $(".buttons-csv").css("color", "white");
            }

      $(document).ready(function(){
         $('#band').change(function()
         {
            if($(this).val() == "SAT")
            {
               $('#leogeo').show();
            } else {
               $('#leogeo').hide();
            }
         });
         <?php if ($this->input->post('band') != "SAT") { ?>
         $('#leogeo').hide();
         <?php } ?>
      });
            function displayActivatorsContacts(call, band, leogeo) {
                var baseURL= "<?php echo base_url();?>";
                $.ajax({
                    url: baseURL + 'index.php/activators/details',
                    type: 'post',
                    data: {'Callsign': call,
                        'Band': band,
                        'LeoGeo': leogeo
                    },
                    success: function(html) {
                        BootstrapDialog.show({
                            title: 'QSO Data',
                            size: BootstrapDialog.SIZE_WIDE,
                            cssClass: 'qso-was-dialog',
                            nl2br: false,
                            message: html,
                            onshown: function(dialog) {
                               $('[data-toggle="tooltip"]').tooltip();
                            },
                            buttons: [{
                                label: 'Close',
                                action: function (dialogItself) {
                                    dialogItself.close();
                                }
                            }]
                        });
                    }
                });
            }
        </script>
        <?php } ?>

    <?php if ($this->uri->segment(1) == "mode") { ?>
		<script src="<?php echo base_url(); ?>assets/js/sections/mode.js"></script>
    <?php } ?>

    <?php if ($this->uri->segment(1) == "band") { ?>
		<script src="<?php echo base_url(); ?>assets/js/sections/bands.js"></script>
    <?php } ?>

<?php if ($this->uri->segment(1) == "accumulated") { ?>
    <script src="<?php echo base_url(); ?>assets/js/chart.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/sections/accumulatedstatistics.js"></script>
<?php } ?>

<?php if ($this->uri->segment(1) == "timeplotter") { ?>
    <script src="<?php echo base_url(); ?>assets/js/highstock.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/highstock/exporting.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/highstock/offline-exporting.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/highstock/export-data.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/sections/timeplot.js"></script>
<?php } ?>

<?php if ($this->uri->segment(1) == "qsl") { 
    	// Get Date format
	if($this->session->userdata('user_date_format')) {
		// If Logged in and session exists
		$custom_date_format = $this->session->userdata('user_date_format');
	} else {
		// Get Default date format from /config/cloudlog.php
		$custom_date_format = $this->config->item('qso_date_format');
	}

    switch ($custom_date_format) {
        case 'd/m/y': $usethisformat = 'D/MM/YY';break;
        case 'd/m/Y': $usethisformat = 'D/MM/YYYY';break;
        case 'm/d/y': $usethisformat = 'MM/D/YY';break;
        case 'm/d/Y': $usethisformat = 'MM/D/YYYY';break;
        case 'd.m.Y': $usethisformat = 'D.MM.YYYY';break;
        case 'y/m/d': $usethisformat = 'YY/MM/D';break;
        case 'Y-m-d': $usethisformat = 'YYYY-MM-D';break;
        case 'M d, Y': $usethisformat = 'MMM D, YYYY';break;
        case 'M d, y': $usethisformat = 'MMM D, YY';break;
    }
    
    ?>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/datetime-moment.js"></script>
    <script>
        $.fn.dataTable.moment('<?php echo $usethisformat ?>');
        $('.qsltable').DataTable({
            "pageLength": 25,
            responsive: false,
            ordering: true,
            "scrollY":        "500px",
            "scrollCollapse": true,
            "paging":         false,
            "scrollX": true,
            "order": [ 2, 'desc' ],
        });
        
        
    </script>
<?php } ?>

<?php if ($this->uri->segment(1) == "kml") { ?>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/tempusdominus-bootstrap-4.min.js"></script>
    <script type="text/javascript">
        $(function () {
            $('#datetimepicker1').datetimepicker({
                format: 'DD/MM/YYYY',
            });
        });
        $(function () {
            $('#datetimepicker2').datetimepicker({
                format: 'DD/MM/YYYY',
            });
        });
    </script>
<?php } ?>

<script>
function viewQsl(picture, callsign) {
            var baseURL= "<?php echo base_url();?>";
            var $textAndPic = $('<div></div>');
                $textAndPic.append('<img class="img-fluid" style="height:auto;width:auto;"src="'+baseURL+'/assets/qslcard/'+picture+'" />');
            var title = '';
            if (callsign == null) {
                title = 'QSL Card';
            } else {
                title = 'QSL Card for ' + callsign;
            }

            BootstrapDialog.show({
                title: title,
                size: BootstrapDialog.SIZE_WIDE,
                message: $textAndPic,
                buttons: [{
                    label: 'Close',
                    action: function(dialogRef){
                        dialogRef.close();
                    }
                }]
            });
        }
</script>
<script>
function deleteQsl(id) {
            BootstrapDialog.confirm({
                title: 'DANGER',
                message: 'Warning! Are you sure you want to delete this QSL card?'  ,
                type: BootstrapDialog.TYPE_DANGER,
                closable: true,
                draggable: true,
                btnOKClass: 'btn-danger',
                callback: function(result) {
                    if(result) {
                        var baseURL= "<?php echo base_url();?>";
                        $.ajax({
                            url: baseURL + 'index.php/qsl/delete',
                            type: 'post',
                            data: {'id': id
                            },
                            success: function(data) {
                                $("#" + id).parent("tr:first").remove(); // removes qsl from table

                                // remove qsl from carousel
                                $(".carousel-indicators li:last-child").remove();
                                $(".carouselimageid_"+id).remove();
                                $('#carouselExampleIndicators').find('.carousel-item').first().addClass('active');

                                // remove table and hide tab if all qsls are deleted
                                if ($('.qsltable tr').length == 1) {
                                    $('.qsltable').remove();
                                    $('.qslcardtab').attr('hidden','');
                                }
                            }
                        });
                    }
                }
            });
        }
</script>

<script>
  /*
   * Used to fetch QSOs from the logbook in the awards
   */
    function displayContacts(searchphrase, band, mode, type) {
        var baseURL = "<?php echo base_url();?>";
        $.ajax({
            url: baseURL + 'index.php/awards/qso_details_ajax',
            type: 'post',
            data: {
                'Searchphrase': searchphrase,
                'Band': band,
                'Mode': mode,
                'Type': type
            },
            success: function (html) {
                BootstrapDialog.show({
                    title: 'QSO Data',
                    size: BootstrapDialog.SIZE_WIDE,
                    cssClass: 'qso-dialog',
                    nl2br: false,
                    message: html,
                    onshown: function(dialog) {
                       $('[data-toggle="tooltip"]').tooltip();
                    },
                    buttons: [{
                        label: 'Close',
                        action: function (dialogItself) {
                            dialogItself.close();
                        }
                    }]
                });
            }
        });
    }

    function uploadQsl() {
        var baseURL= "<?php echo base_url();?>";
        var formdata = new FormData(document.getElementById("fileinfo"));

        $.ajax({
            url: baseURL + 'index.php/qsl/uploadqsl',
            type: 'post',
            data: formdata,
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            success: function(data) {
                if (data.status.front.status == 'Success') {
                    if ($('.qsltable').length > 0) {
                        $('.qsltable tr:last').after('<tr><td style="text-align: center">'+data.status.front.filename+'</td>' +
                            '<td id="'+data.status.front.insertid+'"style="text-align: center"><button onclick="deleteQsl('+data.status.front.insertid+');" class="btn btn-sm btn-danger">Delete</button></td>' +
                            '<td style="text-align: center"><button onclick="viewQsl(\'' + data.status.front.filename + '\')" class="btn btn-sm btn-success">View</button></td>'+
                            '</tr>');
                        var quantity = $(".carousel-indicators li").length;
                        $(".carousel-indicators").append('<li data-target="#carouselExampleIndicators" data-slide-to="'+quantity+'"></li>');
                        $(".carousel-inner").append('<div class="carousel-item carouselimageid_'+data.status.front.insertid+'"><img class="d-block w-100" src="'+baseURL+'/assets/qslcard/'+data.status.front.filename+'" alt="QSL picture #'+(quantity+1)+'"></div>');
                        $("#qslcardfront").val(null);
                    }
                    else {
                        $("#qslupload").prepend('<table style="width:100%" class="qsltable table table-sm table-bordered table-hover table-striped table-condensed">'+
                            '<thead>'+
                               '<tr>'+
                            '<th style="text-align: center">QSL image file</th>'+
                            '<th style="text-align: center"></th>'+
                            '<th style="text-align: center"></th>'+
                            '</tr>'+
                            '</thead><tbody>'+
                                '<tr><td style="text-align: center">'+data.status.front.filename+'</td>' +
                            '<td id="'+data.status.front.insertid+'"style="text-align: center"><button onclick="deleteQsl('+data.status.front.insertid+');" class="btn btn-sm btn-danger">Delete</button></td>' +
                            '<td style="text-align: center"><button onclick="viewQsl(\'' + data.status.front.filename + '\')" class="btn btn-sm btn-success">View</button></td>'+
                            '</tr>'+
                        '</tbody></table>');
                        $('.qslcardtab').removeAttr('hidden');
                        var quantity = $(".carousel-indicators li").length;
                        $(".carousel-indicators").append('<li class="active" data-target="#carouselExampleIndicators" data-slide-to="'+quantity+'"></li>');
                        $(".carousel-inner").append('<div class="active carousel-item carouselimageid_'+data.status.front.insertid+'"><img class="d-block w-100" src="'+baseURL+'/assets/qslcard/'+data.status.front.filename+'" alt="QSL picture #'+(quantity+1)+'"></div>');
                        $(".carouselExampleIndicators").carousel();
                        $("#qslcardfront").val(null);
                    }

                } else {
                    $("#qslupload").append('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Front QSL Card:' +
                    data.status.front.error +
                        '</div>');
                }
                if (data.status.back.status == 'Success') {
                    var qsoid = $("#qsoid").text();
                    if ($('.qsltable').length > 0) {
                        $('.qsltable tr:last').after('<tr><td style="text-align: center">'+data.status.back.filename+'</td>' +
                            '<td id="'+data.status.back.insertid+'"style="text-align: center"><button onclick="deleteQsl('+data.status.back.insertid+');" class="btn btn-sm btn-danger">Delete</button></td>' +
                            '<td style="text-align: center"><button onclick="viewQsl(\'' + data.status.back.filename + '\')" class="btn btn-sm btn-success">View</button></td>'+
                            '</tr>');
                        var quantity = $(".carousel-indicators li").length;
                        $(".carousel-indicators").append('<li data-target="#carouselExampleIndicators" data-slide-to="'+quantity+'"></li>');
                        $(".carousel-inner").append('<div class="carousel-item carouselimageid_'+data.status.back.insertid+'"><img class="d-block w-100" src="'+baseURL+'/assets/qslcard/'+data.status.back.filename+'" alt="QSL picture #'+(quantity+1)+'"></div>');
                        $("#qslcardback").val(null);
                    }
                    else {
                        $("#qslupload").prepend('<table style="width:100%" class="qsltable table table-sm table-bordered table-hover table-striped table-condensed">'+
                            '<thead>'+
                            '<tr>'+
                            '<th style="text-align: center">QSL image file</th>'+
                            '<th style="text-align: center"></th>'+
                            '<th style="text-align: center"></th>'+
                            '</tr>'+
                            '</thead><tbody>'+
                            '<tr><td style="text-align: center">'+data.status.back.filename+'</td>' +
                            '<td id="'+data.status.back.insertid+'"style="text-align: center"><button onclick="deleteQsl('+data.status.back.insertid+');" class="btn btn-sm btn-danger">Delete</button></td>' +
                            '<td><button onclick="viewQsl(\'' + data.status.back.filename + '\')" class="btn btn-sm btn-success">View</button></td>'+
                            '</tr>'+
                            '</tbody></table>');
                        $('.qslcardtab').removeAttr('hidden');
                        var quantity = $(".carousel-indicators li").length;
                        $(".carousel-indicators").append('<li class="active" data-target="#carouselExampleIndicators" data-slide-to="'+quantity+'"></li>');
                        $(".carousel-inner").append('<div class="active carousel-item carouselimageid_'+data.status.back.insertid+'"><img class="d-block w-100" src="'+baseURL+'/assets/qslcard/'+data.status.back.filename+'" alt="QSL picture #'+(quantity+1)+'"></div>');
                        $(".carouselExampleIndicators").carousel();
                        $("#qslcardback").val(null);
                    }
                } else {
                    $("#qslupload").append('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\nBack QSL Card: ' +
                    data.status.back.error +
                        '</div>');
                }
            }
        });
    }
</script>
<script>

	function addQsosToQsl(filename) {
		var title = 'Add additional QSOs to a QSL Card';

		var baseURL= "<?php echo base_url();?>";
		$.ajax({
			url: baseURL + 'index.php/qsl/loadSearchForm',
			type: 'post',
			data: {'filename': filename},
			success: function(html) {
				BootstrapDialog.show({
					title: title,
					size: BootstrapDialog.SIZE_WIDE,
					cssClass: 'qso-search_results',
					nl2br: false,
					message: html,
					buttons: [{
						label: 'Close',
						action: function (dialogItself) {
							dialogItself.close();
						}
					}]
				});
			}
		});
	}

	function addQsoToQsl(qsoid, filename, id) {
		var title = 'Add additional QSOs to a QSL Card';

		var baseURL= "<?php echo base_url();?>";
		$.ajax({
			url: baseURL + 'index.php/qsl/addQsoToQsl',
			type: 'post',
			data: {'filename': filename, 'qsoid': qsoid},
			success: function(html) {
				if (html.status == 'Success') {
					location.reload();
				} else {
					$(".alert").remove();
					$('#searchresult').prepend('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Something went wrong. Please try again!</div>');
				}
			}
		});
	}

	function searchAdditionalQsos(filename) {
		var baseURL= "<?php echo base_url();?>";
		$.ajax({
			url: baseURL + 'index.php/qsl/searchQsos',
			type: 'post',
			data: {'callsign': $('#callsign').val(), 'filename': filename},
			success: function(html) {
				$('#searchresult').empty();
				$('#searchresult').append(html);
			}
		});
	}
</script>
<?php if ($this->uri->segment(1) == "contesting" && $this->uri->segment(2) != "add" ) { ?>
    <script>
        var manual = <?php echo $_GET['manual']; ?>;
    </script>
    <script src="<?php echo base_url() ;?>assets/js/sections/contesting.js?v2"></script>
<?php } ?>

<?php if ($this->uri->segment(1) == "station") { ?>
<script>
    var baseURL= "<?php echo base_url();?>";

	var state = $("#StateHelp option:selected").text();
	if (state != "") {
		$("#stationCntyInput").prop('disabled', false);
		station_profile_selectize_usa_county();
	}

    $('#StateHelp').change(function(){
        var state = $("#StateHelp option:selected").text();
        if (state != "") {
            $("#stationCntyInput").prop('disabled', false);
			station_profile_selectize_usa_county();
        } else {
            $("#stationCntyInput").prop('disabled', true);
            //$('#stationCntyInput')[0].selectize.destroy();
            $("#stationCntyInput").val("");
        }
    });

    function station_profile_selectize_usa_county() {
		$('#stationCntyInput').selectize({
			maxItems: 1,
			closeAfterSelect: true,
			loadThrottle: 250,
			valueField: 'name',
			labelField: 'name',
			searchField: 'name',
			options: [],
			create: false,
			load: function(query, callback) {
				var state = $("#StateHelp option:selected").text();

				if (!query || state == "") return callback();
				$.ajax({
					url: baseURL+'index.php/station/get_county',
					type: 'GET',
					dataType: 'json',
					data: {
						query: query,
						state: state,
					},
					error: function() {
						callback();
					},
					success: function(res) {
						callback(res);
					}
				});
			}
		});
	}
</script>

<?php } ?>

<?php if ($this->uri->segment(2) == "counties" || $this->uri->segment(2) == "counties_details") { ?>
<script>
    $('.countiestable').DataTable({
        "pageLength": 25,
        responsive: false,
        ordering: false,
        "scrollY":        "390px",
        "scrollCollapse": true,
        "paging":         false,
        "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            'csv'
        ]
    });

    // change color of csv-button if dark mode is chosen
    if (isDarkModeTheme()) {
        $(".buttons-csv").css("color", "white");
    }

    function displayCountyContacts(state, county) {
        var baseURL= "<?php echo base_url();?>";
        $.ajax({
            url: baseURL + 'index.php/awards/counties_details_ajax',
            type: 'post',
            data: {'State': state, 'County': county },
            success: function(html) {
                BootstrapDialog.show({
                    title: 'QSO Data',
                    size: BootstrapDialog.SIZE_WIDE,
                    cssClass: 'qso-counties-dialog',
                    nl2br: false,
                    message: html,
                    onshown: function(dialog) {
                       $('[data-toggle="tooltip"]').tooltip();
                    },
                    buttons: [{
                        label: 'Close',
                        action: function (dialogItself) {
                            dialogItself.close();
                        }
                    }]
                });
            }
        });
    }
</script>
<?php } ?>

<?php if ($this->uri->segment(2) == "sig_details") { ?>
	<script>
		$('.tablesig').DataTable({
			"pageLength": 25,
			responsive: false,
			ordering: false,
			"scrollY":        "400px",
			"scrollCollapse": true,
			"paging":         false,
			"scrollX": true,
			dom: 'Bfrtip',
			buttons: [
				'csv'
			]
		});

		// change color of csv-button if dark mode is chosen
		if (isDarkModeTheme()) {
			$(".buttons-csv").css("color", "white");
		}
	</script>
<?php } ?>

<?php if ($this->uri->segment(1) == "contesting" && $this->uri->segment(2) == "add") { ?>
	<script src="<?php echo base_url() ;?>assets/js/sections/contestingnames.js"></script>
<?php } ?>

<?php if ($this->uri->segment(1) == "themes") { ?>
    <script>
    function deleteTheme(id, name) {
        BootstrapDialog.confirm({
            title: 'DANGER',
            message: 'Warning! Are you sure you want to delete the following theme: ' + name + '?'  ,
            type: BootstrapDialog.TYPE_DANGER,
            closable: true,
            draggable: true,
            btnOKClass: 'btn-danger',
            callback: function(result) {
                if(result) {
                    $.ajax({
                        url: base_url + 'index.php/themes/delete',
                        type: 'post',
                        data: {'id': id
                        },
                        success: function(data) {
                            $(".theme_" + id).parent("tr:first").remove(); // removes mode from table
                        }
                    });
                }
            }
        });
    }

	function addThemeDialog() {
		$.ajax({
			url: base_url + 'index.php/themes/add',
			type: 'post',
			success: function(html) {
				BootstrapDialog.show({
					title: 'Create Theme',
					size: BootstrapDialog.SIZE_WIDE,
					cssClass: 'create-theme-dialog',
					nl2br: false,
					message: html,
					buttons: [{
						label: 'Close',
						action: function (dialogItself) {
							dialogItself.close();
						}
					}]
				});
			}
		});
	}

	function addTheme(form) {
		if (form.name.value != '') {
			$.ajax({
				url: base_url + 'index.php/themes/add',
				type: 'post',
				data: {
					'name': form.name.value,
					'foldername': form.foldername.value,
				},
				success: function(html) {
					location.reload();
				}
			});
		}
	}
    </script>
<?php } ?>

<?php if ($this->uri->segment(1) == "dxatlas") { ?>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/tempusdominus-bootstrap-4.min.js"></script>
	<script>
		$(function () {
			$('#datetimepicker1').datetimepicker({
				format: 'DD/MM/YYYY',
			});
		});

		$(function () {
			$('#datetimepicker2').datetimepicker({
				format: 'DD/MM/YYYY',
			});
		});
	</script>
<?php } ?>

<?php if ($this->uri->segment(1) == "csv") { ?>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/tempusdominus-bootstrap-4.min.js"></script>
	<script>
		$(function () {
			$('#datetimepicker1').datetimepicker({
				format: 'DD/MM/YYYY',
			});
		});

		$(function () {
			$('#datetimepicker2').datetimepicker({
				format: 'DD/MM/YYYY',
			});
		});
	</script>
<?php } ?>

<?php if ($this->uri->segment(1) == "qslprint") { ?>
	<script>
		function deleteFromQslQueue(id) {
			BootstrapDialog.confirm({
				title: 'DANGER',
				message: 'Warning! Are you sure you want to removes this QSL from the queue?',
				type: BootstrapDialog.TYPE_DANGER,
				closable: true,
				draggable: true,
				btnOKClass: 'btn-danger',
				callback: function(result) {
					$.ajax({
						url: base_url + 'index.php/qslprint/delete_from_qsl_queue',
						type: 'post',
						data: {'id': id	},
						success: function(html) {
							$("#qslprint_"+id).remove();
						}
					});
				}
			});
		}

		function openQsoList(callsign) {
			$.ajax({
				url: base_url + 'index.php/qslprint/open_qso_list',
				type: 'post',
				data: {'callsign': callsign},
				success: function(html) {
					BootstrapDialog.show({
						title: 'QSO List',
						size: BootstrapDialog.SIZE_WIDE,
						cssClass: 'qso-dialog',
						nl2br: false,
						message: html,
						onshown: function(dialog) {
							$('[data-toggle="tooltip"]').tooltip();
						},
						buttons: [{
							label: 'Close',
							action: function (dialogItself) {
								dialogItself.close();
							}
						}]
					});
				}
			});
		}

		function addQsoToPrintQueue(id) {
			$.ajax({
				url: base_url + 'index.php/qslprint/add_qso_to_print_queue',
				type: 'post',
				data: {'id': id},
				success: function(html) {
					var line = '<tr id="qslprint_'+id+'">';
					line += '<td style=\'text-align: center\'>'+$("#qsolist_"+id).find("td:eq(0)").text()+'</td>';
					line += '<td style=\'text-align: center\'>'+$("#qsolist_"+id).find("td:eq(1)").text()+'</td>';
					line += '<td style=\'text-align: center\'>'+$("#qsolist_"+id).find("td:eq(2)").text()+'</td>';
					line += '<td style=\'text-align: center\'>'+$("#qsolist_"+id).find("td:eq(3)").text()+'</td>';
					line += '<td style=\'text-align: center\'>'+$("#qsolist_"+id).find("td:eq(4)").text()+'</td>';
					line += '<td style=\'text-align: center\'><span class="badge badge-light">'+$("#qsolist_"+id).find("td:eq(5)").text()+'</span></td>';
					line += '<td style=\'text-align: center\'><button onclick="deleteFromQslQueue('+id+')" class="btn btn-sm btn-danger">Delete from queue</button></td></td>';
					line += '<td style=\'text-align: center\'><button onclick="openQsoList(\''+$("#qsolist_"+id).find("td:eq(0)").text()+'\')" class="btn btn-sm btn-success">Open QSO list</button></td>';
					line += '</tr>';
					$('.table tr:last').after(line);
					$("#qsolist_"+id).remove();''
				}
			});
		}

		$(".station_id").change(function(){
			var station_id = $(".station_id").val();
			$.ajax({
				url: base_url + 'index.php/qslprint/get_qsos_for_print_ajax',
				type: 'post',
				data: {'station_id': station_id},
				success: function(html) {
					$('.resulttable').empty();
					$('.resulttable').append(html);
				}
			});
		});
	</script>
<?php } ?>

  </body>
</html>
