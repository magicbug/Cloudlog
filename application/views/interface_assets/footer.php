<script type="text/javascript">
    /*
     *
     * Define global javascript variables
     *
     */
    var base_url = "<?php echo base_url(); ?>"; // Base URL
    var site_url = "<?php echo site_url(); ?>"; // Site URL
    var icon_dot_url = "<?php echo base_url(); ?>assets/images/dot.png";
    // get the user_callsign from session
    var my_call = "<?php echo $this->session->userdata('user_callsign'); ?>".toUpperCase();
</script>

<script>
    /*
    General Language
    */
    var lang_general_word_qso_data = "<?php echo lang('general_word_qso_data'); ?>";
    var lang_general_word_danger = "<?php echo lang('general_word_danger'); ?>";
    var lang_general_word_attention = "<?php echo lang('general_word_attention'); ?>";
    var lang_general_word_warning = "<?php echo lang('general_word_warning'); ?>";
    var lang_general_word_cancel = "<?php echo lang('general_word_cancel'); ?>";
    var lang_general_word_ok = "<?php echo lang('general_word_ok'); ?>";
    var lang_qso_delete_warning = "<?php echo lang('qso_delete_warning'); ?>";
    var lang_general_word_colors = "<?php echo lang('general_word_colors'); ?>";
    var lang_general_word_confirmed = "<?php echo lang('general_word_confirmed'); ?>";
    var lang_general_word_worked_not_confirmed = "<?php echo lang('general_word_worked_not_confirmed'); ?>";
    var lang_general_word_not_worked = "<?php echo lang('general_word_not_worked'); ?>";
    var lang_admin_close = "<?php echo lang('admin_close'); ?>";
</script>
<!-- General JS Files used across Cloudlog -->
<script src="<?php echo base_url(); ?>assets/js/jquery-3.3.1.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.fancybox.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.bundle.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/leaflet/leaflet.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/leaflet/Control.FullScreen.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/leaflet/L.Maidenhead.qrb.js"></script>
<?php if ($this->uri->segment(1) == "activators") { ?>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/leaflet/L.Maidenhead.activators.js"></script>
<?php } ?>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/leaflet/leaflet.geodesic.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/radiohelpers.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/darkmodehelpers.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrapdialog/js/bootstrap-dialog.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/easyprint.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/sections/common.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/sections/eqslcharcounter.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/sections/version_dialog.js"></script>

<script src="<?php echo base_url(); ?>assets/js/htmx.min.js"></script>

<script>
    // Reinitialize tooltips after new content has been loaded
    document.addEventListener('htmx:afterSwap', function(event) {
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
<!-- Version Dialog START -->

<?php
if ($this->session->userdata('user_id') != null) {
    $versionDialog = $this->optionslib->get_option('version_dialog');
    if (empty($versionDialog)) {
        $this->optionslib->update('version_dialog', 'release_notes', 'yes');
    }
    $versionDialogHeader = $this->optionslib->get_option('version_dialog_header');
    if (empty($versionDialogHeader)) {
        $this->optionslib->update('version_dialog_header', $this->lang->line('options_version_dialog'), 'yes');
    }
    if ($versionDialog != "disabled") {
        $confirmed = $this->user_options_model->get_options('version_dialog', array('option_name' => 'confirmed'))->result();
        $confirmation_value = (isset($confirmed[0]->option_value)) ? $confirmed[0]->option_value : 'false';
        if ($confirmation_value != 'true') {
            $this->user_options_model->set_option('version_dialog', 'confirmed', array('boolean' => $confirmation_value));
?><script>
                displayVersionDialog();
            </script><?php
                    }
                }
            }
                        ?>

<!-- Version Dialog END -->

<?php if ($this->uri->segment(1) == "oqrs") { ?>
    <script src="<?php echo base_url(); ?>assets/js/sections/oqrs.js"></script>
<?php } ?>

<?php if ($this->uri->segment(1) == "options") { ?>
    <script>
        $('#sendTestMailButton').click(function() {
            $.ajax({
                url: base_url + 'index.php/options/sendTestMail',
                type: 'POST',
            });
        });
    </script>
<?php } ?>

<?php if ($this->uri->segment(1) == "awards" && ($this->uri->segment(2) == "cq")) { ?>
    <script src="<?php echo base_url(); ?>assets/js/Polyline.encoded.js"></script>
    <script id="cqmapjs" type="text/javascript" src="<?php echo base_url(); ?>assets/js/sections/cqmap.js" tileUrl="<?php echo $this->optionslib->get_option('option_map_tile_server'); ?>"></script>
<?php } ?>

<?php if ($this->uri->segment(1) == "awards" && ($this->uri->segment(2) == "iota")) { ?>
    <script id="iotamapjs" type="text/javascript" src="<?php echo base_url(); ?>assets/js/sections/iotamap.js" tileUrl="<?php echo $this->optionslib->get_option('option_map_tile_server'); ?>"></script>
<?php } ?>

<?php if ($this->uri->segment(1) == "awards" && ($this->uri->segment(2) == "dxcc")) { ?>
    <script id="dxccmapjs" type="text/javascript" src="<?php echo base_url(); ?>assets/js/sections/dxccmap.js" tileUrl="<?php echo $this->optionslib->get_option('option_map_tile_server'); ?>"></script>
<?php } ?>

<?php if ($this->uri->segment(1) == "statistics") { ?>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/chart.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/chartjs-plugin-piechart-outlabels.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/sections/statistics.js"></script>
<?php } ?>

<?php if ($this->uri->segment(1) == "continents") { ?>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/chart.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/chartjs-plugin-piechart-outlabels.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/sections/continents.js"></script>
<?php } ?>

<?php if ($this->uri->segment(1) == "adif" || $this->uri->segment(1) == "qrz" || $this->uri->segment(1) == "hrdlog" || $this->uri->segment(1) == "webadif" || $this->uri->segment(1) == "sattimers") { ?>
    <!-- Javascript used for ADIF Import and Export Areas -->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
<?php } ?>

<?php if ($this->uri->segment(1) == "maintenance") { ?>
    <script src="<?php echo base_url(); ?>assets/js/sections/maintenance.js"></script>
<?php } ?>

<?php if ($this->uri->segment(1) == "adif") { ?>
    <script src="<?php echo base_url(); ?>assets/js/sections/adif.js"></script>
<?php } ?>

<?php if ($this->uri->segment(1) == "notes" && ($this->uri->segment(2) == "add" || $this->uri->segment(2) == "edit")) { ?>
    <!-- Javascript used for Notes Area -->
    <script src="<?php echo base_url(); ?>assets/plugins/quill/quill.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/sections/notes.js"></script>
<?php } ?>

<?php if ($this->uri->segment(1) == "logbooks" && $this->uri->segment(2) == "edit") { ?>
    <script>
        function removeSlug() {
            var slugLink = document.getElementById("slugLink");
            if (slugLink !== null) {
                slugLink.style.display = "none";
            }
            document.getElementById('publicSlugInput').value = ''
        }
    </script>
<?php } ?>

<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/datatables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/selectize.js"></script>

<?php if ($this->uri->segment(1) == "station") { ?>
    <script language="javascript" src="<?php echo base_url(); ?>assets/js/HamGridSquare.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/sections/station_locations.js"></script>
    <script>
        var position;

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else {
                console.log('Geolocation is not supported by this browser.');
            }
        }

        function showPosition(position) {
            gridsquare = latLonToGridSquare(position.coords.latitude, position.coords.longitude);
            document.getElementById("stationGridsquareInput").value = gridsquare;
        }
    </script>
<?php } ?>

<?php if ($this->uri->segment(1) == "logbooks") { ?>
    <script src="<?php echo base_url(); ?>assets/js/sections/station_logbooks.js"></script>
<?php } ?>

<?php if ($this->uri->segment(1) == "debug") { ?>
    <script type="text/javascript">
        function copyURL(url) {
            var urlField = $('#baseUrl');
            navigator.clipboard.writeText(url).then(function() {});
            urlField.addClass('flash-copy')
                .delay('1000').queue(function() {
                    urlField.removeClass('flash-copy').dequeue();
                });
        }

        $(function() {
            $('[data-bs-toggle="tooltip"]').tooltip({
                'delay': {
                    show: 500,
                    hide: 0
                },
                'placement': 'right'
            });
        });
    </script>
<?php } ?>

<?php if ($this->uri->segment(1) == "api"  && $this->uri->segment(2) == "help") { ?>
    <script type="text/javascript">
        function copyApiKey(apiKey) {
            var apiKeyField = $('#' + apiKey);
            navigator.clipboard.writeText(apiKey).then(function() {});
            apiKeyField.addClass('flash-copy')
                .delay('1000').queue(function() {
                    apiKeyField.removeClass('flash-copy').dequeue();
                });
        }

        function copyApiUrl() {
            var apiUrlField = $('#apiUrl');
            navigator.clipboard.writeText("<?php echo base_url(); ?>").then(function() {});
            apiUrlField.addClass('flash-copy')
                .delay('1000').queue(function() {
                    apiUrlField.removeClass('flash-copy').dequeue();
                });
        }

        $(function() {
            $('[data-bs-toggle="tooltip"]').tooltip({
                'delay': {
                    show: 500,
                    hide: 0
                },
                'placement': 'right'
            });
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
                                    $(".card-body.main").append('<div class="alert alert-success">Your query has been saved!</div>');
                                    if ($("#querydropdown option").length == 0) {
                                        var dropdowninfo = ' <button class="btn btn-sm btn-primary" onclick="edit_stored_query_dialog()" id="btn-edit">Edit queries</button></p>' +
                                            '<div class="mb-3 row querydropdownform">' +
                                            '<label class="col-md-2 control-label" for="querydropdown">  Stored queries:</label>' +
                                            '<div class="col-md-3">' +
                                            '<select id="querydropdown" name="querydropdown" class="form-select form-select-sm">' +
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
                        label: lang_admin_close,
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
                        "language": {
                            url: getDataTablesLanguageUrl(),
                        },
                        dom: 'Bfrtip',
                        buttons: [
                            'csv'
                        ]
                    });
                    // change color of csv-button if dark mode is chosen
                    if (isDarkModeTheme()) {
                        $(".buttons-csv").css("color", "white");
                    }
                    $('[data-bs-toggle="tooltip"]').tooltip();
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
                                $(".bootstrap-dialog-message").prepend('<div class="alert alert-danger">The stored query has been deleted!</div>');
                                $("#query_" + id).remove(); // removes query from table in dialog
                                $("#querydropdown option[value='" + id + "']").remove(); // removes query from dropdown
                                if ($("#querydropdown option").length == 0) {
                                    $("#btn-edit").remove();
                                    $('.querydropdownform').remove();
                                };
                            },
                            error: function() {
                                $(".bootstrap-dialog-message").prepend('<div class="alert alert-danger">The stored query could not be deleted. Please try again!</div>');
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
                    $(".bootstrap-dialog-message").prepend('<div class="alert alert-success">The query description has been updated!</div>');
                    $("#querydropdown option[value='" + id + "']").text($('#description_' + id).html()); // Change text in dropdown
                },
                error: function() {
                    $(".bootstrap-dialog-message").prepend('<div class="alert alert-danger">Something went wrong with the save. Please try again!</div>');
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
                            label: lang_admin_close,
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
                            "language": {
                                url: getDataTablesLanguageUrl(),
                            },
                            dom: 'Bfrtip',
                            buttons: [
                                'csv'
                            ]
                        });
                        // change color of csv-button if dark mode is chosen
                        if (isDarkModeTheme()) {
                            $(".buttons-csv").css("color", "white");
                        }
                        $('[data-bs-toggle="tooltip"]').tooltip();
                        $(".searchbutton").removeClass('running');
                        $(".searchbutton").prop('disabled', false);
                        $("#btn-save").show();
                        $('.table-responsive .dropdown-toggle').off('mouseenter').on('mouseenter', function() {
                            showQsoActionsMenu($(this).closest('.dropdown'));
                        });
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
                        label: lang_admin_close,
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
        $("#create_station_profile #dxcc_select").change(function() {
            $('#country').val($("#dxcc_select option:selected").text());

        });
    });
</script>

<script>
    function printWarning() {
        if ($("#dxcc_select option:selected").text().includes("<?php echo lang('gen_hamradio_deleted_dxcc'); ?>")) {
            $('#warningMessageDXCC').show();
            $('#dxcc_select').css('border', '2px solid rgb(217, 83, 79)');
            $('#warningMessageDXCC').text("<?php echo lang('station_location_dxcc_warning'); ?>");
        } else {
            $('#dxcc_select').css('border', '');
            $('#warningMessageDXCC').hide();
        }
    }
    $('#dxcc_select').ready(function() {
        printWarning();
    });

    $('#dxcc_select').on('change', function() {
        printWarning();
    });
</script>

<script>
    var $ = jQuery.noConflict();
    $('[data-fancybox]').fancybox({
        toolbar: false,
        smallBtn: true,
        iframe: {
            preload: false
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

    function newpath(latlng1, latlng2, locator1, locator2) {
        // If map is already initialized
        var container = L.DomUtil.get('mapqrbcontainer');

        if (container != null) {
            container._leaflet_id = null;
            container.remove();
            $("#mapqrb").append('<div id="mapqrbcontainer" style="Height: 500px"></div>');
        }

        var map = new L.Map('mapqrbcontainer', {
            fullscreenControl: true,
            fullscreenControlOptions: {
                position: 'topleft'
            },
        }).setView([30, 0], 1.5);

        // Need to fix so that marker is placed at same place as end of line, but this only needs to be done when longitude is < -170
        if (latlng2[1] < -170) {
            latlng2[1] = parseFloat(latlng2[1]) + 360;
        }
        if (latlng1[1] < -170) {
            latlng1[1] = parseFloat(latlng1[1]) + 360;
        }

        map.fitBounds([
            [latlng1[0], latlng1[1]],
            [latlng2[0], latlng2[1]]
        ]);

        var maidenhead = L.maidenheadqrb().addTo(map);

        var osmUrl = '<?php echo $this->optionslib->get_option('option_map_tile_server'); ?>';
        var osmAttrib = 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors';
        var osm = new L.TileLayer(osmUrl, {
            minZoom: 1,
            maxZoom: 12,
            attribution: osmAttrib
        });

        var redIcon = L.icon({
            iconUrl: icon_dot_url,
            iconSize: [10, 10], // size of the icon
        });

        map.addLayer(osm);

        var marker = L.marker([latlng1[0], latlng1[1]], {
            closeOnClick: false,
            autoClose: false
        }).addTo(map).bindPopup(locator1);

        var marker2 = L.marker([latlng2[0], latlng2[1]], {
            closeOnClick: false,
            autoClose: false
        }).addTo(map).bindPopup(locator2);

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

        var result = "Callsign: " + call.replace('0', '&Oslash;') + "<br />";
        result += "Count: " + count + "<br/>";
        result += "Grids: " + grids + "<br/><br />";

        $(".activatorsmapResult").html(result);

        // If map is already initialized
        var container = L.DomUtil.get('mapactivators');

        if (container != null) {
            container._leaflet_id = null;
        }

        const map = new L.map('mapactivators').setView([30, 0], 1.5);

        var grid_four = grids.split(', ');

        var maidenhead = new L.maidenheadactivators(grid_four).addTo(map);

        var osmUrl = '<?php echo $this->optionslib->get_option('option_map_tile_server'); ?>';
        var osmAttrib = 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors';
        var osm = new L.TileLayer(osmUrl, {
            minZoom: 1,
            maxZoom: 12,
            attribution: osmAttrib
        });

        map.addLayer(osm);
    }
</script>

<?php if ($this->uri->segment(1) == "map" && $this->uri->segment(2) == "custom") { ?>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/leaflet/L.Maidenhead.js"></script>
    <script id="leafembed" type="text/javascript" src="<?php echo base_url(); ?>assets/js/leaflet/leafembed.js" tileUrl="<?php echo $this->optionslib->get_option('option_map_tile_server'); ?>"></script>
    <script type="text/javascript">
        $(function() {
            $('[data-bs-toggle="tooltip"]').tooltip()
        });

        <?php if ($qra == "set") { ?>
            var q_lat = <?php echo $qra_lat; ?>;
            var q_lng = <?php echo $qra_lng; ?>;
        <?php } else { ?>
            var q_lat = 40.313043;
            var q_lng = -32.695312;
        <?php } ?>

        var qso_loc = '<?php echo site_url('map/map_plot_json/'); ?>';
        var q_zoom = 3;

        $(document).ready(function() {
            <?php if ($this->config->item('map_gridsquares') != FALSE) { ?>
                var grid = "Yes";
            <?php } else { ?>
                var grid = "No";
            <?php } ?>
            initmap(grid, 'custommap', {
                'initmap_only': true
            });
            // Check and change date if to < from //
            $('.custom-map-QSOs input[name="to"]').off('change').on('change', function() {
                if ($('.custom-map-QSOs input[name="to"]').val().replaceAll('-', '') < $('.custom-map-QSOs input[name="from"]').val().replaceAll('-', '')) {
                    $('.custom-map-QSOs input[name="from"]').val($('.custom-map-QSOs input[name="to"]').val());
                }
            });
            $('.custom-map-QSOs input[name="from"]').off('change').on('change', function() {
                if ($('.custom-map-QSOs input[name="from"]').val().replaceAll('-', '') > $('.custom-map-QSOs input[name="to"]').val().replaceAll('-', '')) {
                    $('.custom-map-QSOs input[name="to"]').val($('.custom-map-QSOs input[name="from"]').val());
                }
            });
            // Form "submit" //
            $('.custom-map-QSOs .btn_submit_map_custom').off('click').on('click', function() {
                var customdata = {
                    'dataPost': {
                        'date_from': $('.custom-map-QSOs input[name="from"]').val(),
                        'date_to': $('.custom-map-QSOs input[name="to"]').val(),
                        'band': $('.custom-map-QSOs select[name="band"]').val(),
                        'mode': $('.custom-map-QSOs select[name="mode"]').val(),
                        'prop_mode': $('.custom-map-QSOs select[name="prop_mode"]').val(),
                        'isCustom': true
                    },
                    'map_id': '#custommap'
                };
                initplot(qso_loc, customdata);
            })


        });
    </script>
<?php } ?>

<?php if ($this->uri->segment(1) == "" || $this->uri->segment(1) == "dashboard") { ?>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/leaflet/L.Maidenhead.js"></script>

    <script>
        var iconsList = {
            'qso': {
                'color': '#FF0000',
                'icon': 'fas fa-dot-circle'
            }
        };
    </script>

    <script>
        // jquery onready
        $(document).ready(function() {
            $.ajax({
                url: base_url + 'index.php/user_options/get_map_custom',
                type: 'GET',
                dataType: 'json',
                error: function() {
                    console.log('[ERROR] ajax get_map_custom() function return error.');
                },
                success: function(json_mapinfo) {
                    console.log(json_mapinfo);
                    if (typeof json_mapinfo.qso !== "undefined") {
                        iconsList = json_mapinfo;
                    }
                }
            });
        });

        console.log(iconsList);
    </script>

    <script type="text/javascript">
        $(function() {
            $('[data-bs-toggle="tooltip"]').tooltip()
        });

        <?php if ($qra == "set") { ?>
            var q_lat = <?php echo $qra_lat; ?>;
            var q_lng = <?php echo $qra_lng; ?>;
        <?php } else { ?>
            var q_lat = 40.313043;
            var q_lng = -32.695312;
        <?php } ?>

        var qso_loc = '<?php echo site_url('map/map_plot_json'); ?>';
        var q_zoom = 3;
        var osmUrl = '<?php echo $this->optionslib->get_option('option_map_tile_server'); ?>';
        var osmCopyright = '<?php echo $this->optionslib->get_option('map_tile_server_copyright'); ?>';

        var redIconImg = L.icon({
            iconUrl: icon_dot_url,
            iconSize: [10, 10]
        });

        $(document).ready(function() {
            <?php if ($this->config->item('map_gridsquares') != FALSE) { ?>
                var ShowGrid = "Yes";
            <?php } else { ?>
                var ShowGrid = "No";
            <?php } ?>

            var layer = L.tileLayer(osmUrl, {
                maxZoom: 18,
                attribution: osmCopyright,
            });

            var map = L.map('map', {
                layers: [layer],
                center: [q_lat, q_lng],
                zoom: q_zoom,
                fullscreenControl: true,
                fullscreenControlOptions: {
                    position: 'topleft'
                },
            });

            /*var printer = L.easyPrint({
                sizeModes: ['Current'],
                filename: 'myMap',
                exportOnly: true,
                hideControlContainer: true
            }).addTo(map);*/

            var markers = {};

            function loadMarkers() {
                fetch(qso_loc)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error !== "No QSOs found") {
                            var newMarkers = {};
                            data.markers.forEach(marker => {
                                var key = `${marker.lat},${marker.lng}`;
                                var html = `<h3>${marker.flag}${marker.label}</h3> ${marker.html}`;
                                newMarkers[key] = marker;
                                if (!markers[key]) {
                                    var icon = L.divIcon({
                                        className: 'custom-icon',
                                        html: `<i class="${iconsList.qso.icon}" style="color:${iconsList.qso.color}"></i>`
                                    });								
                                    
                                    L.marker([marker.lat, marker.lng], {
                                            icon: icon
                                        })
                                        .addTo(map)
                                        .bindPopup(html);
                                }
                            });
                            Object.keys(markers).forEach(key => {
                                if (!newMarkers[key]) {
                                    map.removeLayer(markers[key]);
                                }
                            });
                            markers = newMarkers;
                            } else {
                                console.log("No QSOs found to populate dashboard map.");
                            }
                    });
            }

            loadMarkers();
            setInterval(loadMarkers, 5000);

        });
    </script>
<?php } ?>



<?php if ($this->uri->segment(1) == "radio") { ?>
    <!-- If this is the admin/radio page run the JS -->
    <script type="text/javascript">
        $(document).ready(function() {
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



<script type="text/javascript">
    $(function() {
        $('[data-bs-toggle="tooltip"]').tooltip()
    });
</script>

<?php if ($this->uri->segment(1) == "search") { ?>
    <script type="text/javascript">
        i = 0;

        function findduplicates() {
            event.preventDefault();
            $('#partial_view').load(base_url + "index.php/logbook/search_duplicates/" + $("#station_id").val(), function() {
                $('.qsolist').DataTable({
                    "pageLength": 25,
                    responsive: false,
                    ordering: false,
                    "scrollY": "500px",
                    "scrollCollapse": true,
                    "paging": false,
                    "scrollX": true,
                    "language": {
                        url: getDataTablesLanguageUrl(),
                    },
                    dom: 'Bfrtip',
                    buttons: [
                        'csv'
                    ]
                });
                // change color of csv-button if dark mode is chosen
                if (isDarkModeTheme()) {
                    $(".buttons-csv").css("color", "white");
                }
            });
        }

        function findlotwunconfirmed() {
            event.preventDefault();
            $('#partial_view').load(base_url + "index.php/logbook/search_lotw_unconfirmed/" + $("#station_id").val(), function() {
                $('.qsolist').DataTable({
                    "pageLength": 25,
                    responsive: false,
                    ordering: false,
                    "scrollY": "500px",
                    "scrollCollapse": true,
                    "paging": false,
                    "scrollX": true,
                    "language": {
                        url: getDataTablesLanguageUrl(),
                    },
                    dom: 'Bfrtip',
                    buttons: [
                        'csv'
                    ]
                });
                // change color of csv-button if dark mode is chosen
                if (isDarkModeTheme()) {
                    $(".buttons-csv").css("color", "white");
                }
            });
        }

        function findincorrectcqzones() {
            event.preventDefault();
            $('#partial_view').load(base_url + "index.php/logbook/search_incorrect_cq_zones/" + $("#station_id").val(), function() {
                $('.qsolist').DataTable({
                    "pageLength": 25,
                    responsive: false,
                    ordering: false,
                    "scrollY": "500px",
                    "scrollCollapse": true,
                    "paging": false,
                    "scrollX": true,
                    "language": {
                        url: getDataTablesLanguageUrl(),
                    },
                    dom: 'Bfrtip',
                    buttons: [
                        'csv'
                    ]
                });
                // change color of csv-button if dark mode is chosen
                if (isDarkModeTheme()) {
                    $(".buttons-csv").css("color", "white");
                }
            });
        }

        function searchButtonPress() {
            event.preventDefault()
            if ($('#callsign').val()) {
                let fixedcall = $('#callsign').val();
                $('#partial_view').load("logbook/search_result/" + fixedcall.replace('Ø', '0'), function() {
                    $('[data-bs-toggle="tooltip"]').tooltip()
                });
            }
        }

        $(document).ready(function() {

            <?php if ($this->input->post('callsign') != "") { ?>
                $('#partial_view').load("logbook/search_result/<?php echo str_replace("Ø", "0", $this->input->post('callsign')); ?>", function() {
                    $('[data-bs-toggle="tooltip"]').tooltip()
                });
            <?php } ?>

            $($('#callsign')).on('keypress', function(e) {
                if (e.which == 13) {

                    if ($('#callsign').val()) {
                        let fixedcall = $('#callsign').val();
                        $('#partial_view').load("logbook/search_result/" + fixedcall.replace('Ø', '0'), function() {
                            $('[data-bs-toggle="tooltip"]').tooltip()
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
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/leaflet/L.Maidenhead.js"></script>
    <script id="leafembed" type="text/javascript" src="<?php echo base_url(); ?>assets/js/leaflet/leafembed.js" tileUrl="<?php echo $this->optionslib->get_option('option_map_tile_server'); ?>"></script>
    <script type="text/javascript">
        $(function() {
            $('[data-bs-toggle="tooltip"]').tooltip()
        });
    </script>
    <script type="text/javascript">
        <?php if ($qra == "set") { ?>
            var q_lat = <?php echo $qra_lat; ?>;
            var q_lng = <?php echo $qra_lng; ?>;
        <?php } else { ?>
            var q_lat = 40.313043;
            var q_lng = -32.695312;
        <?php } ?>

        var qso_loc = '<?php echo site_url('map/map_plot_json'); ?>';
        var q_zoom = 3;

        <?php if ($this->config->item('map_gridsquares') != FALSE) { ?>
            var grid = "Yes";
        <?php } else { ?>
            var grid = "No";
        <?php } ?>
        initmap(grid, 'map', {
            'dataPost': {
                'nb_qso': '25',
                'offset': '<?php echo $this->uri->segment(3); ?>'
            }
        });
    </script>
<?php } ?>

<?php if ($this->uri->segment(1) == "qso") { ?>

    <script src="<?php echo base_url(); ?>assets/js/sections/qso.js"></script>
    <?php if ($this->session->userdata('isWinkeyEnabled')) { ?>
        <script src="<?php echo base_url(); ?>assets/js/winkey.js"></script>
    <?php }

    if ($this->optionslib->get_option('dxcache_url') != '') { ?>
        <script type="text/javascript">
            var dxcluster_provider = '<?php echo base_url(); ?>index.php/dxcluster';
            $(document).ready(function() {
                $("#check_cluster").on("click", function() {
                    $.ajax({
                        url: dxcluster_provider + "/qrg_lookup/" + $("#frequency").val() / 1000,
                        cache: false,
                        dataType: "json"
                    }).done(
                        function(dxspot) {
                            reset_fields();
                            $("#callsign").val(dxspot.spotted);
                            $("#callsign").trigger("blur");
                        }
                    );
                });
            });
        </script>

    <?php
    }


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
        $.ajax({
            url: base_url + 'index.php/logbook/qralatlngjson',
            type: 'post',
            data: {
                <?php if ($active_station_info->station_gridsquare != "") { ?>
                    qra: '<?php echo $user_gridsquare; ?>',
                <?php } else if (null !== $this->config->item('locator')) { ?>
                    qra: '<?php echo $this->config->item('locator'); ?>',
                <?php } else { ?>
                    // Fallback to London in case all else fails
                    qra: 'IO91WM',
                <?php } ?>
            },
            success: function(data) {
                result = JSON.parse(data);
                if (typeof result[0] !== "undefined" && typeof result[1] !== "undefined") {
                    mymap.panTo([result[0], result[1]]);
                    pos = result;
                }
            },
            error: function() {},
        });

        L.tileLayer('<?php echo $this->optionslib->get_option('option_map_tile_server'); ?>', {
            maxZoom: 18,
            attribution: '<?php echo $this->optionslib->get_option('option_map_tile_server_copyright'); ?>',
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

            if (!manual) {
                $(function($) {
                    resetTimers(0);
                });
            }
        });

        <?php if ($this->session->userdata('user_qso_end_times')  == 1) { ?>
            $('#callsign').focusout(function() {
                if (!manual && $('#callsign').val() != '') {
                    clearInterval(handleStart);
                    clearInterval(handleDate);
                }
            });
            $('#start_time').focusout(function() {
                if (manual && $('#start_time').val() != '') {
                    $('#end_time').val($('#start_time').val());
                }
            });
        <?php } ?>

        jQuery(function($) {
            var input = $('#callsign');
            input.on('keydown', function() {
                var key = event.keyCode || event.charCode;

                if (key == 8 || key == 46) {
                    reset_fields();
                }
            });

            $(document).keyup(function(e) {
                if (e.charCode === 0) {
                    let fixedcall = $('#callsign').val();
                    $('#callsign').val(fixedcall.replace('Ø', '0'));
                }
                if (e.key === "Escape") { // escape key maps to keycode `27`
                    reset_fields();
                    if (!manual) {
                        resetTimers(0)
                    }
                    $('#callsign').val("");
                    $("#callsign").focus();
                    updateFromCAT();
                    if (document.querySelector('#radio').value != '0') {
                        updateFromCAT();
                    }
                }
            });
        });

        <?php if ($this->session->userdata('user_sota_lookup') == 1) { ?>
            $('#sota_ref').change(function() {
                var sota = $('#sota_ref').val();
                if (sota.length > 0) {
                    $.ajax({
                        url: base_url + 'index.php/qso/get_sota_info',
                        type: 'post',
                        data: {
                            'sota': sota
                        },
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

        <?php if ($this->session->userdata('user_wwff_lookup') == 1) { ?>
            $('#wwff_ref').change(function() {
                var wwff = $('#wwff_ref').val();
                if (wwff.length > 0) {
                    $.ajax({
                        url: base_url + 'index.php/qso/get_wwff_info',
                        type: 'post',
                        data: {
                            'wwff': wwff
                        },
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

        <?php if ($this->session->userdata('user_pota_lookup') == 1) { ?>
            $('#pota_ref').change(function() {
                var pota = $('#pota_ref').val();
                if (pota.length > 0) {
                    $.ajax({
                        url: base_url + 'index.php/qso/get_pota_info',
                        type: 'post',
                        data: {
                            'pota': pota
                        },
                        success: function(res) {
                            $('#qth').val(res.name);
                            $('#locator').val(res.grid6);
                        },
                        error: function() {
                            $('#qth').val('');
                            $('#locator').val('');
                        },
                    });
                }
            });
        <?php } ?>

        $('#stationProfile').change(function() {
            var stationProfile = $('#stationProfile').val();
            $.ajax({
                url: base_url + 'index.php/qso/get_station_power',
                type: 'post',
                data: {
                    'stationProfile': stationProfile
                },
                success: function(res) {
                    $('#transmit_power').val(res.station_power);
                },
                error: function() {
                    $('#transmit_power').val('');
                },
            });
            // [eQSL default msg] change value on change station profle //
            qso_set_eqsl_qslmsg(stationProfile, false, '.qso_panel');
        });
        // [eQSL default msg] change value on clic //
        $('.qso_panel .qso_eqsl_qslmsg_update').off('click').on('click', function() {
            qso_set_eqsl_qslmsg($('.qso_panel #stationProfile').val(), true, '.qso_panel');
            $('#charsLeft').text(" ");
        });

        <?php if ($this->session->userdata('user_qth_lookup') == 1) { ?>
            $('#qth').focusout(function() {
                if ($('#locator').val() === '') {
                    var lat = 0;
                    var lon = 0;
                    $.ajax({
                        async: false,
                        type: 'GET',
                        dataType: "json",
                        url: "https://nominatim.openstreetmap.org/?city=" + $(this).val() + "&format=json&addressdetails=1&limit=1",
                        data: {},
                        success: function(data) {
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
                        if (ycalc[yi] > 0) ylp = Math.floor(yres);
                        else ylp = Math.ceil(yres);
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
<?php if ($this->uri->segment(1) == "qso" || ($this->uri->segment(1) == "contesting" && $this->uri->segment(2) != "add")) { ?>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/datetime-moment.js"></script>
    <script>
        $('#notice-alerts').delay(1000).fadeOut(5000);

        function setRst(mode) {
            if (mode == 'JT65' || mode == 'JT65B' || mode == 'JT6C' || mode == 'JTMS' || mode == 'ISCAT' || mode == 'MSK144' || mode == 'JTMSK' || mode == 'QRA64' || mode == 'FT8' || mode == 'FT4' || mode == 'JS8' || mode == 'JT9' || mode == 'JT9-1' || mode == 'ROS') {
                $('#rst_sent').val('-5');
                $('#rst_rcvd').val('-5');
            } else if (mode == 'FSK441' || mode == 'JT6M') {
                $('#rst_sent').val('26');
                $('#rst_rcvd').val('26');
            } else if (mode == 'CW' || mode == 'RTTY' || mode == 'PSK31' || mode == 'PSK63') {
                $('#rst_sent').val('599');
                $('#rst_rcvd').val('599');
            } else {
                $('#rst_sent').val('59');
                $('#rst_rcvd').val('59');
            }
        }

        function getUTCTimeStamp(el) {
            var now = new Date();
            var localTime = now.getTime();
            var utc = localTime + (now.getTimezoneOffset() * 60000);
            $(el).attr('value', ("0" + now.getUTCHours()).slice(-2) + ':' + ("0" + now.getUTCMinutes()).slice(-2) + ':' + ("0" + now.getUTCSeconds()).slice(-2));
        }

        function getUTCDateStamp(el) {
            var now = new Date();
            var localTime = now.getTime();
            var utc = localTime + (now.getTimezoneOffset() * 60000);
            $(el).attr('value', ("0" + now.getUTCDate()).slice(-2) + '-' + ("0" + (now.getUTCMonth() + 1)).slice(-2) + '-' + now.getUTCFullYear());
        }
    </script>

    <script>
        // Javascript for controlling rig frequency.
        var updateFromCAT = function() {
            var cat2UI = function(ui, cat, allow_empty, allow_zero, callback_on_update) {
                // Check, if cat-data is available
                if (cat == null) {
                    return;
                } else if (typeof allow_empty !== 'undefined' && !allow_empty && cat == '') {
                    return;
                } else if (typeof allow_zero !== 'undefined' && !allow_zero && cat == '0') {
                    return;
                }
                // Only update the ui-element, if cat-data has changed
                if (ui.data('catValue') != cat) {
                    ui.val(cat);
                    ui.data('catValue', cat);
                    if (typeof callback_on_update === 'function') {
                        callback_on_update(cat);
                    }
                }
            }

            if ($('select.radios option:selected').val() != '0') {
                radioID = $('select.radios option:selected').val();
                $.getJSON("radio/json/" + radioID, function(data) {
                    /* {
                    "frequency": "2400210000",
                        "frequency_rx": "10489710000",
                        "mode": "SSB",
                        "satmode": "S/X",
                        "satname": "QO-100"
                        "power": "20"
                        "prop_mode": "SAT",
                        "error": "not_logged_id" // optional, reserved for errors
                    }  */
                    if (data.error) {
                        if (data.error == 'not_logged_in') {
                            $(".radio_cat_state").remove();
                            if ($('.radio_login_error').length == 0) {
                                $('.qso_panel').prepend('<div class="alert alert-danger radio_login_error" role="alert"><i class="fas fa-broadcast-tower"></i> You\'re not logged it. Please <a href="<?php echo base_url(); ?>">login</a></div>');
                            }
                        }
                        // Put future Errorhandling here
                    } else {
                        if ($('.radio_login_error').length != 0) {
                            $(".radio_login_error").remove();
                        }
                        cat2UI($('#frequency'), data.frequency, false, true, function(d) {
                            $("#band").val(frequencyToBand(d))
                        });
                        cat2UI($('#frequency_rx'), data.frequency_rx, false, true, function(d) {
                            $("#band_rx").val(frequencyToBand(d))
                        });
                        cat2UI($('.mode'), data.mode, false, false, function(d) {
                            setRst($(".mode").val())
                        });
                        cat2UI($('#sat_name'), data.satname, false, false);
                        cat2UI($('#sat_mode'), data.satmode, false, false);
                        cat2UI($('#transmit_power'), data.power, false, false);
                        cat2UI($('#selectPropagation'), data.prop_mode, false, false);

                        // Display CAT Timeout warning based on the figure given in the config file
                        var minutes = Math.floor(<?php echo $this->optionslib->get_option('cat_timeout_interval'); ?> / 60);

                        if (data.updated_minutes_ago > minutes) {
                            $(".radio_cat_state").remove();
                            if ($('.radio_timeout_error').length == 0) {
                                $('#radio_status').prepend('<div class="alert alert-danger radio_timeout_error" role="alert"><i class="fas fa-broadcast-tower"></i> Radio connection timed-out: ' + $('select.radios option:selected').text() + ' data is ' + data.updated_minutes_ago + ' minutes old.</div>');
                            } else {
                                $('.radio_timeout_error').html('Radio connection timed-out: ' + $('select.radios option:selected').text() + ' data is ' + data.updated_minutes_ago + ' minutes old.');
                            }
                        } else {
                            $(".radio_timeout_error").remove();
                            text = '<i class="fas fa-broadcast-tower"></i><span style="margin-left:10px;"></span><b>TX:</b> ' + (Math.round(parseInt(data.frequency) / 100) / 10000).toFixed(4) + ' MHz';
                            if (data.mode != null) {
                                text = text + '<span style="margin-left:10px"></span>' + data.mode;
                            }
                            if (data.power != null && data.power != 0) {
                                text = text + '<span style="margin-left:10px"></span>' + data.power + ' W';
                            }
                            ptext = '';
                            if (data.prop_mode != null && data.prop_mode != '') {
                                ptext = ptext + data.prop_mode;
                                if (data.prop_mode == 'SAT') {
                                    ptext = ptext + ' ' + data.satname;
                                }
                            }
                            if (data.frequency_rx != null && data.frequency_rx != 0) {
                                ptext = ptext + '<span style="margin-left:10px"></span><b>RX:</b> ' + (Math.round(parseInt(data.frequency_rx) / 1000) / 1000).toFixed(3) + ' MHz';
                            }
                            if (ptext != '') {
                                text = text + '<span style="margin-left:10px"></span>(' + ptext + ')';
                            }
                            if (!$('#radio_cat_state').length) {
                                $('#radio_status').prepend('<div aria-hidden="true"><div id="radio_cat_state" class="alert alert-success radio_cat_state" role="alert">' + text + '</div></div>');
                            } else {
                                $('#radio_cat_state').html(text);
                            }
                        }
                    }
                });
            }
        };

        // Update frequency every three second
        setInterval(updateFromCAT, 3000);

        // If a radios selected from drop down select radio update.
        $('.radios').change(updateFromCAT);

        // If no radio is selected clear data
        $(".radios").change(function() {
            if ($(".radios option:selected").val() == 0) {
                $("#sat_name").val("");
                $("#sat_mode").val("");
                $("#frequency").val("");
                $("#frequency_rx").val("");
                $("#band_rx").val("");
                $("#selectPropagation").val($("#selectPropagation option:first").val());
                $(".radio_timeout_error").remove();
            }
        });
    </script>

<?php } ?>

<?php if ($this->uri->segment(1) == "logbook" && $this->uri->segment(2) == "view") { ?>
    <script>
        var mymap = L.map('map').setView([lat, long], 5);

        L.tileLayer('<?php echo $this->optionslib->get_option('option_map_tile_server'); ?>', {
            maxZoom: 18,
            attribution: '<?php echo $this->optionslib->get_option('option_map_tile_server_copyright'); ?>',
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
            iconSize: [18, 18], // size of the icon
        });

        L.marker([lat, long], {
                icon: redIcon
            }).addTo(mymap)
            .bindPopup(callsign);

        mymap.on('click', onMapClick);
    </script>
<?php } ?>

<?php if ($this->uri->segment(1) == "update") { ?>
    <script>
        $(document).ready(function() {
            $('#btn_update_dxcc').bind('click', function() {
                $('#dxcc_update_status').show();
                $.ajax({
                    url: "update/dxcc"
                });
                setTimeout(update_stats, 5000);
            });

            function update_stats() {
                $('#dxcc_update_status').load('<?php echo base_url() ?>updates/status.html', function(val) {
                    $('#dxcc_update_staus').html(val);

                    if ((val === null) || (val.substring(0, 4) != "DONE")) {
                        setTimeout(update_stats, 5000);
                    }
                });

            }

        });
    </script>

<?php } ?>

<?php if ($this->uri->segment(1) == "awards" && $this->uri->segment(2) == "wab") { ?>
    <script>
        var WorkedSquaresObject = <?php echo json_encode($worked_squares); ?>;
        var WorkedSquaresArray = Object.values(WorkedSquaresObject);

        var ConfirmedSquaresObject = <?php echo json_encode($confirmed_squares); ?>;
        var ConfirmedSquaresArray = Object.values(ConfirmedSquaresObject);

        var wab_squares = $.ajax({
            url: "<?php echo base_url(); ?>assets/json/WABSquares.geojson",
            dataType: "json",
            success: console.log("WAB data successfully loaded."),
            error: function(xhr) {
                alert(xhr.statusText)
            }
        })

        // Load the external GeoJSON file
        $.when(wab_squares).done(function() {
            var layer = L.tileLayer('<?php echo $this->optionslib->get_option('option_map_tile_server'); ?>', {
                maxZoom: 18,
                attribution: '<?php echo $this->optionslib->get_option('option_map_tile_server_copyright'); ?>',
                id: 'mapbox.streets'
            });

            var map = L.map('map', {
                layers: [layer],
                center: [54.970901, -2.457140],
                zoom: 8,
                minZoom: 8,
                fullscreenControl: true,
                fullscreenControlOptions: {
                    position: 'topleft'
                },
            });

            var printer = L.easyPrint({
                tileLayer: layer,
                sizeModes: ['Current'],
                filename: 'myMap',
                exportOnly: true,
                hideControlContainer: true
            }).addTo(map);

            /*Legend specific*/
            var legend = L.control({
                position: "topright"
            });

            legend.onAdd = function(map) {
                var div = L.DomUtil.create("div", "legend");
                div.innerHTML += "<h4>" + lang_general_word_colors + "</h4>";
                div.innerHTML += "<i style='background: green'></i><span> Confirmed Square</span><br>";
                div.innerHTML += "<i style='background: orange'></i><span> Unconfirmed Square</span><br>";
                return div;
            };

            legend.addTo(map);

            //console.log(wab_squares.responseJSON);
            // Add requested external GeoJSON to map
            var kywab_squares = L.geoJSON(wab_squares.responseJSON, {
                style: function(feature) {
                    if (WorkedSquaresArray.indexOf(feature.properties.name) !== -1) {
                        if (ConfirmedSquaresArray.indexOf(feature.properties.name) !== -1) {
                            return {
                                fillColor: 'green',
                                fill: true,
                                fillOpacity: 1,
                            };
                        } else {
                            return {
                                fillColor: 'orange',
                                fill: true,
                                fillOpacity: 1,
                            };
                        }
                    } else {
                        return {};
                    }
                },
                pointToLayer: function(feature, latlng) {
                    if (feature.properties && feature.properties.name) {
                        // Create a custom icon that displays the name from the GeoJSON data
                        var labelIcon = L.divIcon({
                            className: 'text-labels', // Set class for CSS styling
                            html: feature.properties.name
                        });

                        // Create a marker at the location of the point
                        return L.marker(latlng, {
                            icon: labelIcon
                        });
                    }
                },
                onEachFeature: function(feature, layer) {
                    layer.on('click', function() {
                        // Code to execute when the area is clicked
                        displaywabcontacts(feature.properties.name);
                    });
                }
            }).addTo(map);
            // Function to update labels based on zoom level
            function updateLabels() {
                var currentZoom = map.getZoom();
                kywab_squares.eachLayer(function(layer) {
                    if (currentZoom >= 8) {
                        // Show labels if zoom level is 10 or higher
                        layer.getElement().style.display = 'block';
                    } else {
                        // Hide labels if zoom level is less than 10
                        layer.getElement().style.display = 'none';
                    }
                });
            }

            // Update labels when the map zoom changes
            map.on('zoomend', updateLabels);

            // Update labels immediately after adding the GeoJSON data to the map
            updateLabels();
        });

        function displaywabcontacts(wabsquare) {
            var baseURL = "<?php echo base_url(); ?>";
            $.ajax({
                url: baseURL + 'index.php/awards/wab_details_ajax',
                type: 'post',
                data: {
                    'Wab': wabsquare,
                },
                success: function(html) {
                    BootstrapDialog.show({
                        title: lang_general_word_qso_data,
                        size: BootstrapDialog.SIZE_WIDE,
                        cssClass: 'qso-counties-dialog',
                        nl2br: false,
                        message: html,
                        onshown: function(dialog) {
                            $('[data-bs-toggle="tooltip"]').tooltip();
                        },
                        buttons: [{
                            label: lang_admin_close,
                            action: function(dialogItself) {
                                dialogItself.close();
                            }
                        }]
                    });
                }
            });
        }
    </script>
<?php } ?>

<?php if ($this->uri->segment(1) == "gridsquares" && !empty($this->uri->segment(2))) { ?>
    <script>
        var gridsquaremap = true;
    </script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/leaflet/L.MaidenheadColoured.js"></script>

    <script>
        var layer = L.tileLayer('<?php echo $this->optionslib->get_option('option_map_tile_server'); ?>', {
            maxZoom: 18,
            attribution: '<?php echo $this->optionslib->get_option('option_map_tile_server_copyright'); ?>',
            id: 'mapbox.streets'
        });

        var map = L.map('gridsquare_map', {
            layers: [layer],
            center: [19, 0],
            zoom: 2,
            minZoom: 1,
            fullscreenControl: true,
            fullscreenControlOptions: {
                position: 'topleft'
            },
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
            span.innerText = span.textContent = '(' + grid_four_confirmed_count + ' <?php echo lang('gridsquares_grid_squares'); ?>' + (grid_four_confirmed_count != 1 ? 's' : '') + ') ';
        }
        if ((grid_four_count - grid_four_confirmed_count) > 0) {
            var span = document.getElementById('worked_grids');
            span.innerText = span.textContent = '(' + (grid_four_count - grid_four_confirmed_count) + ' <?php echo lang('gridsquares_grid_squares'); ?>' + (grid_four_count - grid_four_confirmed_count != 1 ? 's' : '') + ') ';
        }
        var span = document.getElementById('sum_grids');
        span.innerText = span.textContent = ' <?php echo lang('gridsquares_total_count'); ?>' + ': ' + grid_four_count + ' <?php echo lang('gridsquares_grid_squares'); ?>' + (grid_four_count != 1 ? 's' : '');

        var maidenhead = L.maidenhead().addTo(map);

        map.on('click', onMapClick);

        function onMapClick(event) {
            var LatLng = event.latlng;
            var lat = LatLng.lat;
            var lng = LatLng.lng;
            var locator = LatLng2Loc(lat, lng, 10);
            var loc_4char = locator.substring(0, 4);

            if (map.getZoom() > 2) {
                <?php if ($this->session->userdata('user_callsign')) { ?>
                    spawnGridsquareModal(loc_4char);
                <?php } ?>
            }
        };

        function spawnGridsquareModal(loc_4char) {
            var band = '';
            var search_type = "<?php echo $this->uri->segment(2); ?>";
            if (search_type == "satellites") {
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
                success: function(html) {
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
                        $('[data-bs-toggle="tooltip"]').tooltip({
                            boundary: 'window'
                        });
                    }
                    $('.table-responsive .dropdown-toggle').off('mouseenter').on('mouseenter', function() {
                        showQsoActionsMenu($(this).closest('.dropdown'));
                    });
                }
            });
        }

        <?php if ($this->uri->segment(1) == "gridsquares" && $this->uri->segment(2) == "band") { ?>

            var bands_available = <?php echo $bands_available; ?>;
            $('#gridsquare_bands').append('<option value="All">All</option>')
            $.each(bands_available, function(key, value) {
                $('#gridsquare_bands')
                    .append($("<option></option>")
                        .attr("value", value)
                        .text(value));
            });

            var num = "<?php echo $this->uri->segment(3); ?>";
            $("#gridsquare_bands option").each(function() {
                if ($(this).val() == num) { // EDITED THIS LINE
                    $(this).attr("selected", "selected");
                }
            });

            $(function() {
                // bind change event to select
                $('#gridsquare_bands').on('change', function() {
                    var url = $(this).val(); // get selected value
                    if (url) { // require a URL
                        window.location = "<?php echo site_url('gridsquares/band/'); ?>" + url
                    }
                    return false;
                });
            });
        <?php } ?>
    </script>
<?php } ?>

<?php if ($this->uri->segment(1) == "activated_grids" && !empty($this->uri->segment(2))) { ?>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/leaflet/L.MaidenheadColoured.js"></script>

    <script>
        var layer = L.tileLayer('<?php echo $this->optionslib->get_option('option_map_tile_server'); ?>', {
            maxZoom: 18,
            attribution: '<?php echo $this->optionslib->get_option('option_map_tile_server_copyright'); ?>',
            id: 'mapbox.streets'
        });


        var map = L.map('gridsquare_map', {
            layers: [layer],
            center: [19, 0],
            zoom: 2,
            minZoom: 1,
            fullscreenControl: true,
            fullscreenControlOptions: {
                position: 'topleft'
            },
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
            span.innerText = span.textContent = '(' + grid_four_confirmed_count + ' <?php echo lang('gridsquares_grid_squares'); ?>' + (grid_four_confirmed_count != 1 ? 's' : '') + ') ';
        }
        if ((grid_four_count - grid_four_confirmed_count) > 0) {
            var span = document.getElementById('activated_grids');
            span.innerText = span.textContent = '(' + (grid_four_count - grid_four_confirmed_count) + ' <?php echo lang('gridsquares_grid_squares'); ?>' + (grid_four_count - grid_four_confirmed_count != 1 ? 's' : '') + ') ';
        }
        var span = document.getElementById('sum_grids');
        span.innerText = span.textContent = ' <?php echo lang('gridsquares_total_count'); ?>' + ': ' + grid_four_count + ' <?php echo lang('gridsquares_grid_squares'); ?>' + (grid_four_count != 1 ? 's' : '');

        var maidenhead = L.maidenhead().addTo(map);

        map.on('click', onMapClick);

        function onMapClick(event) {
            var LatLng = event.latlng;
            var lat = LatLng.lat;
            var lng = LatLng.lng;
            var locator = LatLng2Loc(lat, lng, 10);
            var loc_4char = locator.substring(0, 4);

            if (map.getZoom() > 2) {
                <?php if ($this->session->userdata('user_callsign')) { ?>
                    var band = '';
                    var search_type = "<?php echo $this->uri->segment(2); ?>";
                    if (search_type == "satellites") {
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
                        success: function(html) {
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
                                $('[data-bs-toggle="tooltip"]').tooltip({
                                    boundary: 'window'
                                });
                            }
                            $('.table-responsive .dropdown-toggle').off('mouseenter').on('mouseenter', function() {
                                showQsoActionsMenu($(this).closest('.dropdown'));
                            });
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
                        .attr("value", value)
                        .text(value));
            });

            var num = "<?php echo $this->uri->segment(3); ?>";
            $("#gridsquare_bands option").each(function() {
                if ($(this).val() == num) { // EDITED THIS LINE
                    $(this).attr("selected", "selected");
                }
            });

            $(function() {
                // bind change event to select
                $('#gridsquare_bands').on('change', function() {
                    var url = $(this).val(); // get selected value
                    if (url) { // require a URL
                        window.location = "<?php echo site_url('activated_grids/band/'); ?>" + url
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


<?php if ($this->uri->segment(1) == "hrdlog") { ?>
    <script src="<?php echo base_url(); ?>assets/js/sections/hrdlog.js"></script>
<?php } ?>
<?php if ($this->uri->segment(1) == "qrz") { ?>
    <script src="<?php echo base_url(); ?>assets/js/sections/qrzlogbook.js"></script>
<?php } ?>
<?php if ($this->uri->segment(1) == "webadif") { ?>
    <script src="<?php echo base_url(); ?>assets/js/sections/webadif.js"></script>
<?php } ?>

<script>
    function displayQso(id) {
        var baseURL = "<?php echo base_url(); ?>";
        $.ajax({
            url: baseURL + 'index.php/logbook/view/' + id,
            type: 'post',
            success: function(html) {
                BootstrapDialog.show({
                    title: lang_general_word_qso_data,
                    cssClass: 'qso-dialog',
                    size: BootstrapDialog.SIZE_WIDE,
                    nl2br: false,
                    message: html,
                    onshown: function(dialog) {
                        var qsoid = $("#qsoid").text();
                        $(".editButton").html('<a class="btn btn-primary" id="edit_qso" href="javascript:qso_edit(' + qsoid + ')"><i class="fas fa-edit"></i><?php echo lang('general_edit_qso'); ?></a>');
                        var lat = $("#lat").text();
                        var long = $("#long").text();
                        var callsign = $("#callsign").text();
                        var mymap = L.map('mapqso').setView([lat, long], 5);

                        var tiles = L.tileLayer('<?php echo $this->optionslib->get_option('option_map_tile_server'); ?>', {
                            maxZoom: 18,
                            attribution: '<?php echo $this->optionslib->get_option('option_map_tile_server_copyright'); ?>',
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
                            iconSize: [18, 18], // size of the icon
                        });

                        L.marker([lat, long], {
                                icon: redIcon
                            }).addTo(mymap)
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
            "scrollY": "400px",
            "scrollCollapse": true,
            "paging": false,
            "scrollX": true,
            "language": {
                url: getDataTablesLanguageUrl(),
            },
            dom: 'Bfrtip',
            buttons: [
                'csv'
            ]
        });

        $('.tablesummary').DataTable({
            info: false,
            searching: false,
            ordering: false,
            "paging": false,
            "language": {
                url: getDataTablesLanguageUrl(),
            },
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

<?php if ($this->uri->segment(2) == "waja") { ?>
    <script>
        $('.tablewaja').DataTable({
            "pageLength": 25,
            responsive: false,
            ordering: false,
            "scrollY": "400px",
            "scrollCollapse": true,
            "paging": false,
            "scrollX": true,
            "language": {
                url: getDataTablesLanguageUrl(),
            },
            dom: 'Bfrtip',
            buttons: [
                'csv'
            ]
        });

        $('.tablesummary').DataTable({
            info: false,
            searching: false,
            ordering: false,
            "paging": false,
            "language": {
                url: getDataTablesLanguageUrl(),
            },
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
            "scrollY": "400px",
            "scrollCollapse": true,
            "paging": false,
            "scrollX": true,
            "language": {
                url: getDataTablesLanguageUrl(),
            },
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

<?php if ($this->uri->segment(2) == "iota") { ?>
    <script>
        $('.tableiota').DataTable({
            "pageLength": 25,
            responsive: false,
            ordering: false,
            "scrollY": "400px",
            "scrollCollapse": true,
            "paging": false,
            "scrollX": true,
            "language": {
                url: getDataTablesLanguageUrl(),
            },
            dom: 'Bfrtip',
            buttons: [
                'csv'
            ]
        });

        $('.tablesummary').DataTable({
            info: false,
            searching: false,
            ordering: false,
            "paging": false,
            "language": {
                url: getDataTablesLanguageUrl(),
            },
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
            "scrollY": "400px",
            "scrollCollapse": true,
            "paging": false,
            "scrollX": true,
            "language": {
                url: getDataTablesLanguageUrl(),
            },
            dom: 'Bfrtip',
            buttons: [
                'csv'
            ]
        });

        $('.tablesummary').DataTable({
            info: false,
            searching: false,
            ordering: false,
            "paging": false,
            "language": {
                url: getDataTablesLanguageUrl(),
            },
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
            "scrollY": "400px",
            "scrollCollapse": true,
            "paging": false,
            "scrollX": true,
            "language": {
                url: getDataTablesLanguageUrl(),
            },
            dom: 'Bfrtip',
            buttons: [
                'csv'
            ]
        });

        $('.tablesummary').DataTable({
            info: false,
            searching: false,
            ordering: false,
            "paging": false,
            "language": {
                url: getDataTablesLanguageUrl(),
            },
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
    function selectize_usa_county() {
        var baseURL = "<?php echo base_url(); ?>";
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
                    url: baseURL + 'index.php/qso/get_county',
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
        var baseURL = "<?php echo base_url(); ?>";
        var myform = document.getElementById("qsoform");
        var fd = new FormData(myform);
        $.ajax({
            url: baseURL + 'index.php/qso/qso_save_ajax',
            data: fd,
            cache: false,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function(dataofconfirm) {
                $(".edit-dialog").modal('hide');
                $(".qso-dialog").modal('hide');
                <?php if ($this->uri->segment(1) != "search" && $this->uri->segment(2) != "filter" && $this->uri->segment(1) != "qso" && $this->uri->segment(1) != "logbookadvanced") { ?>location.reload();
            <?php } ?>
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
            "scrollY": "500px",
            "scrollCollapse": true,
            "paging": false,
            "scrollX": true,
            "language": {
                url: getDataTablesLanguageUrl(),
            },
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
            var baseURL = "<?php echo base_url(); ?>";
            $.ajax({
                url: baseURL + 'index.php/timeline/details',
                type: 'post',
                data: {
                    'Querystring': querystring,
                    'Band': band,
                    'Mode': mode,
                    'Type': type
                },
                success: function(html) {
                    BootstrapDialog.show({
                        title: lang_general_word_qso_data,
                        size: BootstrapDialog.SIZE_WIDE,
                        cssClass: 'qso-was-dialog',
                        nl2br: false,
                        message: html,
                        onshown: function(dialog) {
                            $('[data-bs-toggle="tooltip"]').tooltip();
                            $('.table-responsive .dropdown-toggle').off('mouseenter').on('mouseenter', function() {
                                showQsoActionsMenu($(this).closest('.dropdown'));
                            });
                        },
                        buttons: [{
                            label: lang_admin_close,
                            action: function(dialogItself) {
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
            "scrollY": "500px",
            "scrollCollapse": true,
            "paging": false,
            "scrollX": true,
            "language": {
                url: getDataTablesLanguageUrl(),
            },
            dom: 'Bfrtip',
            buttons: [
                'csv'
            ]
        });

        // change color of csv-button if dark mode is chosen
        if (isDarkModeTheme()) {
            $(".buttons-csv").css("color", "white");
        }

        $(document).ready(function() {
            $('#band').change(function() {
                if ($(this).val() == "SAT") {
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
            var baseURL = "<?php echo base_url(); ?>";
            $.ajax({
                url: baseURL + 'index.php/activators/details',
                type: 'post',
                data: {
                    'Callsign': call,
                    'Band': band,
                    'LeoGeo': leogeo
                },
                success: function(html) {
                    BootstrapDialog.show({
                        title: lang_general_word_qso_data,
                        size: BootstrapDialog.SIZE_WIDE,
                        cssClass: 'qso-was-dialog',
                        nl2br: false,
                        message: html,
                        onshown: function(dialog) {
                            $('[data-bs-toggle="tooltip"]').tooltip();
                        },
                        buttons: [{
                            label: lang_admin_close,
                            action: function(dialogItself) {
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

<?php if ($this->uri->segment(1) == "qsl" || $this->uri->segment(1) == "eqsl") {
    // Get Date format
    if ($this->session->userdata('user_date_format')) {
        // If Logged in and session exists
        $custom_date_format = $this->session->userdata('user_date_format');
    } else {
        // Get Default date format from /config/cloudlog.php
        $custom_date_format = $this->config->item('qso_date_format');
    }

    switch ($custom_date_format) {
        case 'd/m/y':
            $usethisformat = 'D/MM/YY';
            break;
        case 'd/m/Y':
            $usethisformat = 'D/MM/YYYY';
            break;
        case 'm/d/y':
            $usethisformat = 'MM/D/YY';
            break;
        case 'm/d/Y':
            $usethisformat = 'MM/D/YYYY';
            break;
        case 'd.m.Y':
            $usethisformat = 'D.MM.YYYY';
            break;
        case 'y/m/d':
            $usethisformat = 'YY/MM/D';
            break;
        case 'Y-m-d':
            $usethisformat = 'YYYY-MM-D';
            break;
        case 'M d, Y':
            $usethisformat = 'MMM D, YYYY';
            break;
        case 'M d, y':
            $usethisformat = 'MMM D, YY';
            break;
    }

?>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/datetime-moment.js"></script>
    <script>
        $.fn.dataTable.moment('<?php echo $usethisformat ?>');
        $.fn.dataTable.ext.buttons.clear = {
            className: 'buttons-clear',
            action: function(e, dt, node, config) {
                dt.search('').draw();
            }
        };
        <?php if ($this->uri->segment(1) == "qsl") { ?>
            $('.qsltable').DataTable({
                    <?php } else if ($this->uri->segment(1) == "eqsl") { ?>
                        $('.eqsltable').DataTable({
                        <?php } ?> "pageLength": 25,
                        responsive: false,
                        ordering: true,
                        "scrollY": "500px",
                        "scrollCollapse": true,
                        "paging": false,
                        "scrollX": true,
                        "language": {
                            url: getDataTablesLanguageUrl(),
                        },
                        "order": [2, 'desc'],
                        dom: 'Bfrtip',
                        buttons: [{
                            extend: 'clear',
                            text: 'Clear'
                        }]
                        });
                        // change color of csv-button if dark mode is chosen
                        if (isDarkModeTheme()) {
                            $('[class*="buttons"]').css("color", "white");
                        }
    </script>
<?php } ?>


<script>
    function viewQsl(picture, callsign) {
        let title = '';
        const baseURL = "<?php echo base_url(); ?>";
        const textAndPic = $('<div></div>').append('<center><img class="img-fluid w-qsl" style="height:auto;width:auto;"src="' + baseURL + '/assets/qslcard/' + picture + '" /><center>');

        if (callsign == null) {
            title = 'QSL Card';
        } else {
            title = 'QSL Card for ' + callsign.replace('0', '&Oslash;');
        }

        BootstrapDialog.show({
            title: title,
            size: BootstrapDialog.SIZE_WIDE,
            message: textAndPic,
            buttons: [{
                label: lang_admin_close,
                action: function(dialogRef) {
                    dialogRef.close();
                }
            }]
        });
    }
</script>
<script>
    function deleteQsl(id) {
        const baseURL = "<?php echo base_url(); ?>";
        BootstrapDialog.confirm({
            title: 'DANGER',
            message: 'Warning! Are you sure you want to delete this QSL card?',
            type: BootstrapDialog.TYPE_DANGER,
            closable: true,
            draggable: true,
            btnOKClass: 'btn-danger',
            callback: function(result) {
                if (result) {
                    $.ajax({
                        url: baseURL + 'index.php/qsl/delete',
                        type: 'post',
                        data: {
                            'id': id
                        },
                        success: function(data) {
                            $("#" + id).parent("tr:first").remove(); // removes qsl from table

                            // remove qsl from carousel
                            $(".carousel-indicators li:last-child").remove();
                            $(".carouselimageid_" + id).remove();
                            $('#carouselExampleIndicators').find('.carousel-item').first().addClass('active');

                            // remove table and hide tab if all qsls are deleted
                            if ($('.qsltable tr').length == 1) {
                                $('.qsltable').remove();
                                $('.qslcardtab').attr('hidden', '');
                            }
                        }
                    });
                }
            }
        });
    }
</script>
<script>
    function viewSstv(picture) {
        const title = 'SSTV Image';
        const baseURL = "<?php echo base_url(); ?>";
        const textAndPic = $('<div></div>').append(`<center><img class="img-fluid w-qsl" style="height:auto;width:auto;"src="${baseURL}/assets/sstvimages/${picture}" /><center>`);

        BootstrapDialog.show({
            title: title,
            size: BootstrapDialog.SIZE_WIDE,
            message: textAndPic,
            buttons: [{
                label: lang_admin_close,
                action: function(dialogRef) {
                    dialogRef.close();
                }
            }]
        });
    }

    function deleteSstv(id) {
        const baseURL = "<?php echo base_url(); ?>";
        BootstrapDialog.confirm({
            title: 'DANGER',
            message: 'Warning! Are you sure you want to delete this SSTV Image?',
            type: BootstrapDialog.TYPE_DANGER,
            closable: true,
            draggable: true,
            btnOKClass: 'btn-danger',
            callback: function(result) {
                if (result) {
                    $.ajax({
                        url: baseURL + 'index.php/sstv/delete',
                        type: 'post',
                        data: {
                            'id': id
                        },
                        success: function(data) {
                            // remove selected sstv image from table
                            $("#" + id).parent("tr:first").remove();

                            // remove sstv image from carousel
                            $("#sstvCarouselIndicators .carousel-indicators li:last-child").remove();
                            $("#sstvCarouselIndicators .carouselimageid_" + id).remove();
                            $('#sstvCarouselIndicators').find('.carousel-item').first().addClass('active');

                            // remove table and hide tab if all sstv images are deleted
                            if ($('.sstvtable tr').length == 1) {
                                $('.sstvtable').remove();
                                $('.sstvimagetab').attr('hidden', '');
                            }
                        }
                    });
                }
            }
        });
    }
</script>

<script>
    function viewEqsl(picture, callsign) {
        var baseURL = "<?php echo base_url(); ?>";
        var $textAndPic = $('<div></div>');
        $textAndPic.append('<img class="img-fluid" style="height:auto;width:auto;"src="' + baseURL + 'images/eqsl_card_images/' + picture + '" />');
        var title = '';
        if (callsign == null) {
            title = 'eQSL Card';
        } else {
            title = 'eQSL Card for ' + callsign.replace('0', '&Oslash;');
        }

        BootstrapDialog.show({
            title: title,
            size: BootstrapDialog.SIZE_WIDE,
            message: $textAndPic,
            buttons: [{
                label: lang_admin_close,
                action: function(dialogRef) {
                    dialogRef.close();
                }
            }]
        });
    }
</script>
<script>
    $('#displayAwardInfo').click(function(event) {
        var awardInfoLines = [
            lang_award_info_ln2,
            lang_award_info_ln3,
            lang_award_info_ln4
        ];
        var awardInfoContent = "";
        awardInfoLines.forEach(function(line) {
            awardInfoContent += line + "<br><br>";
        });
        BootstrapDialog.alert({
            title: "<h4>" + lang_award_info_ln1 + "</h4>",
            message: awardInfoContent,
        });
    });
</script>
<script>
    /*
     * Used to fetch QSOs from the logbook in the awards
     */
    function displayContacts(searchphrase, band, mode, type, qsl) {
        var baseURL = "<?php echo base_url(); ?>";
        $.ajax({
            url: baseURL + 'index.php/awards/qso_details_ajax',
            type: 'post',
            data: {
                'Searchphrase': searchphrase,
                'Band': band,
                'Mode': mode,
                'Type': type,
                'QSL': qsl
            },
            success: function(html) {
                BootstrapDialog.show({
                    title: lang_general_word_qso_data,
                    size: BootstrapDialog.SIZE_WIDE,
                    cssClass: 'qso-dialog',
                    nl2br: false,
                    message: html,
                    onshown: function(dialog) {
                        $('[data-bs-toggle="tooltip"]').tooltip();
                        $('.contacttable').DataTable({
                            "pageLength": 7,
                            responsive: false,
                            ordering: false,
                            "scrollY": "550px",
                            "scrollCollapse": true,
                            "paging": true,
                            "scrollX": true,
                            "language": {
                                url: getDataTablesLanguageUrl(),
                            },
                            dom: 'Bfrtip',
                            buttons: [
                                'csv'
                            ]
                        });
                        $('.table-responsive .dropdown-toggle').off('mouseenter').on('mouseenter', function() {
                            showQsoActionsMenu($(this).closest('.dropdown'));
                        });
                    },
                    buttons: [{
                        label: lang_admin_close,
                        action: function(dialogItself) {
                            dialogItself.close();
                        }
                    }]
                });
            }
        });
    }

    function displayContactsOnMap(target, searchphrase, band, mode, type, qsl) {
        $.ajax({
            url: base_url + 'index.php/awards/qso_details_ajax',
            type: 'post',
            data: {
                'Searchphrase': searchphrase,
                'Band': band,
                'Mode': mode,
                'Type': type,
                'QSL': qsl
            },
            success: function(html) {
                var dialog = new BootstrapDialog({
                    title: lang_general_word_qso_data,
                    size: BootstrapDialog.SIZE_WIDE,
                    cssClass: 'qso-dialog',
                    nl2br: false,
                    message: html,
                    onshown: function(dialog) {
                        $('[data-bs-toggle="tooltip"]').tooltip();
                        $('.contacttable').DataTable({
                            "pageLength": 25,
                            responsive: false,
                            ordering: false,
                            "scrollY": "550px",
                            "scrollCollapse": true,
                            "paging": false,
                            "scrollX": true,
                            "language": {
                                url: getDataTablesLanguageUrl(),
                            },
                            dom: 'Bfrtip',
                            buttons: [
                                'csv'
                            ]
                        });
                        $('.table-responsive .dropdown-toggle').off('mouseenter').on('mouseenter', function() {
                            showQsoActionsMenu($(this).closest('.dropdown'));
                        });
                    },
                    buttons: [{
                        label: lang_admin_close,
                        action: function(dialogItself) {
                            dialogItself.close();
                        }
                    }]
                });
                dialog.realize();
                target.append(dialog.getModal());
                dialog.open();
            }
        });
    }

    function createTable(title, type) {
        const tableClass = type === 'sstv' ? 'sstvtable' : 'qsltable';
        return `<table style="width:100%" class="${tableClass} table table-sm table-bordered table-hover table-striped table-condensed">` +
            '<thead>' +
            '<tr>' +
            '<th style="text-align: center">' + title + '</th>' +
            '<th style="text-align: center"></th>' +
            '<th style="text-align: center"></th>' +
            '</tr>' +
            '</thead>' +
            '<tbody></tbody>' +
            '</table>'
    }

    function createTableRow(image, type) {
        const viewFunction = type === 'sstv' ? 'viewSstv' : 'viewQsl';
        const deleteFunction = type === 'sstv' ? 'deleteSstv' : 'deleteQsl';
        return '<tr><td style="text-align: center">' + image.filename + '</td>' +
            `<td id="${image.insertid}" style="text-align: center"><button onclick="${deleteFunction}(${image.insertid});" class="btn btn-sm btn-danger">Delete</button></td>` +
            `<td style="text-align: center"><button onclick="${viewFunction}('${image.filename}')" class="btn btn-sm btn-success">View</button></td>` +
            '</tr>'
    }

    function handleSSTVImageUpload(sstvImage) {
        const baseURL = "<?php echo base_url(); ?>";
        const numCarouselItems = $('#sstv-carousel-indicators li').length;

        // Next, append card to the table
        $('.sstvtable').length === 0 ? $("#sstvupload").prepend(createTable("SSTV image file", "sstv")) : null;
        $('.sstvtable tbody:last').append(createTableRow(sstvImage, "sstv"));

        // Append card to the carousel
        const newCarouselItem = '<div class="' + (numCarouselItems === 0 ? 'active ' : '') + 'carousel-item carouselimageid_' + sstvImage.insertid + '"><img class="img-fluid w-qsl" src="' + baseURL + '/assets/sstvimages/' + sstvImage.filename + '" alt="QSL picture"></div>';
        $("#sstv-carousel-inner").append(newCarouselItem);

        // Append new carousel indicator
        const newCarouselIndicator = '<li class="' + (numCarouselItems === 0 ? 'active ' : '') + '" data-bs-target="#sstvCarouselIndicators" data-bs-slide-to="' + numCarouselItems + '"></li>';
        $("#sstv-carousel-indicators").append(newCarouselIndicator);

        // Initialize the bootstrap carousel
        $("#sstvCarouselIndicators").carousel();
    }

    function uploadSSTV() {
        const baseURL = "<?php echo base_url(); ?>";
        const formdata = new FormData(document.getElementById("sstvinfo"));

        $.ajax({
            url: baseURL + 'index.php/sstv/uploadsstv',
            type: 'post',
            data: formdata,
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            success: function(data) {
                // Iterate over each SSTV image and handle it
                data.forEach((sstvImage) => {
                    if (sstvImage.status == 'Success') {
                        // Show the SSTV image tab
                        $('.sstvimagetab').removeAttr('hidden');

                        // Handle the SSTV image upload
                        handleSSTVImageUpload(sstvImage);
                    } else if (sstvImage.status != '') {
                        $("#sstvupload").append('<div class="alert alert-danger">SSTV image:' +
                            sstvImage.error +
                            '</div>');
                    }
                    // Reset the image inputs
                    $("#sstvimages").val(null);
                })
            }
        });
    }

    function handleQslCardUpload(qslCard) {
        const baseURL = "<?php echo base_url(); ?>";
        const numCarouselItems = $('#qsl-carousel-indicators li').length;

        // append card to the qsl management table
        $('.qsltable').length === 0 ? $("#qslupload").prepend(createTable("QSL image file", "qsl")) : null;
        $('.qsltable tbody:last').append(createTableRow(qslCard, "qsl"));

        // Append card image to the carousel
        const newCarouselItem = '<div class="' + (numCarouselItems === 0 ? 'active ' : '') + 'carousel-item carouselimageid_' + qslCard.insertid + '"><img class="img-fluid w-qsl" src="' + baseURL + '/assets/qslcard/' + qslCard.filename + '" alt="QSL picture"></div>';
        $("#qsl-carousel-inner").append(newCarouselItem);

        // Append carousel indicator for the new card
        const newCarouselIndicator = '<li class="' + (numCarouselItems === 0 ? 'active ' : '') + '" data-bs-target="#qslCarouselIndicators" data-bs-slide-to="' + numCarouselItems + '"></li>';
        $("#qsl-carousel-indicators").append(newCarouselIndicator);

        // Initialize the bootstrap carousel
        $("#qslCarouselIndicators").carousel();
    }

    function uploadQsl() {
        const baseURL = "<?php echo base_url(); ?>";
        const formdata = new FormData(document.getElementById("fileinfo"));

        $.ajax({
            url: baseURL + 'index.php/qsl/uploadqsl',
            type: 'post',
            data: formdata,
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            success: function(data) {
                const qslCard = data.status || {}
                if (data.status.front.status == 'Success') {
                    handleQslCardUpload(qslCard.front);
                } else if (qslCard.front.status != '') {
                    $("#qslupload").append('<div class="alert alert-danger">Front QSL Card:' +
                        qslCard.front.error +
                        '</div>');
                }

                if (qslCard.back.status == 'Success') {
                    handleQslCardUpload(qslCard.back);
                } else if (qslCard.back.status != '') {
                    $("#qslupload").append('<div class="alert alert-danger">\nBack QSL Card: ' +
                        qslCard.back.error +
                        '</div>');
                }

                // Show the QSL card tab
                $('.qslcardtab').removeAttr('hidden');

                // Reset the image inputs
                $("#qslcardfront").val(null);
                $("#qslcardback").val(null);
            }
        });
    }
</script>
<script>
    function addQsosToQsl(filename) {
        var title = 'Add additional QSOs to a QSL Card';

        var baseURL = "<?php echo base_url(); ?>";
        $.ajax({
            url: baseURL + 'index.php/qsl/loadSearchForm',
            type: 'post',
            data: {
                'filename': filename
            },
            success: function(html) {
                BootstrapDialog.show({
                    title: title,
                    size: BootstrapDialog.SIZE_WIDE,
                    cssClass: 'qso-search_results',
                    nl2br: false,
                    message: html,
                    buttons: [{
                        label: lang_admin_close,
                        action: function(dialogItself) {
                            dialogItself.close();
                        }
                    }]
                });
            }
        });
    }

    function addQsoToQsl(qsoid, filename, id) {
        var title = 'Add additional QSOs to a QSL Card';

        var baseURL = "<?php echo base_url(); ?>";
        $.ajax({
            url: baseURL + 'index.php/qsl/addQsoToQsl',
            type: 'post',
            data: {
                'filename': filename,
                'qsoid': qsoid
            },
            success: function(html) {
                if (html.status == 'Success') {
                    location.reload();
                } else {
                    $(".alert").remove();
                    $('#searchresult').prepend('<div class="alert alert-danger">Something went wrong. Please try again!</div>');
                }
            }
        });
    }

    function searchAdditionalQsos(filename) {
        var baseURL = "<?php echo base_url(); ?>";
        $.ajax({
            url: baseURL + 'index.php/qsl/searchQsos',
            type: 'post',
            data: {
                'callsign': $('#callsign').val(),
                'filename': filename
            },
            success: function(html) {
                $('#searchresult').empty();
                $('#searchresult').append(html);
            }
        });
    }
</script>
<?php if ($this->uri->segment(1) == "contesting" && ($this->uri->segment(2) != "add" && $this->uri->segment(2) != "edit")) { ?>
    <script>
        var manual = <?php echo $_GET['manual']; ?>;
    </script>
    <script src="<?php echo base_url(); ?>assets/js/sections/contesting.js?v2"></script>
<?php } ?>

<?php if ($this->uri->segment(1) == "station") { ?>
    <script>
        var baseURL = "<?php echo base_url(); ?>";

        $(document).ready(function() {
            function checkSelectedValue() {
                var selectedValue = $('#dxcc_select').val();
                var valuesToShow = [223, 279, 294, 265, 106, 122];

                if (valuesToShow.includes(Number(selectedValue))) {
                    $('#WABbox').show();
                } else {
                    $('#WABbox').hide();
                }
            }

            // Call on page load
            checkSelectedValue();

            // Call on change event
            $('#dxcc_select').change(checkSelectedValue);
        });

        var state = $("#StateHelp option:selected").text();
        if (state != "") {
            $("#stationCntyInput").prop('disabled', false);
            station_profile_selectize_usa_county();
        }

        $('#StateHelp').change(function() {
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
                        url: baseURL + 'index.php/station/get_county',
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
            "scrollY": "390px",
            "scrollCollapse": true,
            "paging": false,
            "scrollX": true,
            "language": {
                url: getDataTablesLanguageUrl(),
            },
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
            var baseURL = "<?php echo base_url(); ?>";
            $.ajax({
                url: baseURL + 'index.php/awards/counties_details_ajax',
                type: 'post',
                data: {
                    'State': state,
                    'County': county
                },
                success: function(html) {
                    BootstrapDialog.show({
                        title: lang_general_word_qso_data,
                        size: BootstrapDialog.SIZE_WIDE,
                        cssClass: 'qso-counties-dialog',
                        nl2br: false,
                        message: html,
                        onshown: function(dialog) {
                            $('[data-bs-toggle="tooltip"]').tooltip();
                        },
                        buttons: [{
                            label: lang_admin_close,
                            action: function(dialogItself) {
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
            "scrollY": "400px",
            "scrollCollapse": true,
            "paging": false,
            "scrollX": true,
            "language": {
                url: getDataTablesLanguageUrl(),
            },
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
    <script src="<?php echo base_url(); ?>assets/js/sections/contestingnames.js"></script>
<?php } ?>

<?php if ($this->uri->segment(1) == "themes") { ?>
    <script>
        function deleteTheme(id, name) {
            BootstrapDialog.confirm({
                title: 'DANGER',
                message: 'Warning! Are you sure you want to delete the following theme: ' + name + '?',
                type: BootstrapDialog.TYPE_DANGER,
                closable: true,
                draggable: true,
                btnOKClass: 'btn-danger',
                callback: function(result) {
                    if (result) {
                        $.ajax({
                            url: base_url + 'index.php/themes/delete',
                            type: 'post',
                            data: {
                                'id': id
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
                            label: lang_admin_close,
                            action: function(dialogItself) {
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


<?php if ($this->uri->segment(1) == "eqsl") { ?>
    <script>
        $('.qsotable').DataTable({
            "stateSave": true,
            "pageLength": 25,
            responsive: false,
            "scrollY": "400px",
            "scrollCollapse": true,
            "paging": false,
            "scrollX": true,
            "language": {
                url: getDataTablesLanguageUrl(),
            },
            "ordering": true,
            "order": [0, 'desc'],
        });
    </script>
<?php } ?>

<?php if ($this->uri->segment(1) == "awards") {
    // Get Date format
    if ($this->session->userdata('user_date_format')) {
        // If Logged in and session exists
        $custom_date_format = $this->session->userdata('user_date_format');
    } else {
        // Get Default date format from /config/cloudlog.php
        $custom_date_format = $this->config->item('qso_date_format');
    }

    switch ($custom_date_format) {
        case 'd/m/y':
            $usethisformat = 'D/MM/YY';
            break;
        case 'd/m/Y':
            $usethisformat = 'D/MM/YYYY';
            break;
        case 'm/d/y':
            $usethisformat = 'MM/D/YY';
            break;
        case 'm/d/Y':
            $usethisformat = 'MM/D/YYYY';
            break;
        case 'd.m.Y':
            $usethisformat = 'D.MM.YYYY';
            break;
        case 'y/m/d':
            $usethisformat = 'YY/MM/D';
            break;
        case 'Y-m-d':
            $usethisformat = 'YYYY-MM-D';
            break;
        case 'M d, Y':
            $usethisformat = 'MMM D, YYYY';
            break;
        case 'M d, y':
            $usethisformat = 'MMM D, YY';
            break;
    }

?>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/datetime-moment.js"></script>
    <?php if ($this->uri->segment(2) == "wwff") { ?>
        <script>
            $.fn.dataTable.moment('<?php echo $usethisformat ?>');
            $.fn.dataTable.ext.buttons.clear = {
                className: 'buttons-clear',
                action: function(e, dt, node, config) {
                    dt.search('').draw();
                }
            };
            $('#wwfftable').DataTable({
                "pageLength": 25,
                responsive: false,
                ordering: true,
                "scrollY": "500px",
                "scrollCollapse": true,
                "paging": false,
                "scrollX": true,
                "language": {
                    url: getDataTablesLanguageUrl(),
                },
                "order": [0, 'asc'],
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'csv'
                    },
                    {
                        extend: 'clear',
                        text: 'Clear'
                    }
                ]
            });
            // change color of csv-button if dark mode is chosen
            if (isDarkModeTheme()) {
                $('[class*="buttons"]').css("color", "white");
            }
        </script>
    <?php } else if ($this->uri->segment(2) == "pota") { ?>
        <script>
            $.fn.dataTable.moment('<?php echo $usethisformat ?>');
            $.fn.dataTable.ext.buttons.clear = {
                className: 'buttons-clear',
                action: function(e, dt, node, config) {
                    dt.search('').draw();
                }
            };
            $('#potatable').DataTable({
                "pageLength": 25,
                responsive: false,
                ordering: true,
                "scrollY": "500px",
                "scrollCollapse": true,
                "paging": false,
                "scrollX": true,
                "language": {
                    url: getDataTablesLanguageUrl(),
                },
                "order": [0, 'asc'],
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'csv'
                    },
                    {
                        extend: 'clear',
                        text: 'Clear'
                    }
                ]
            });
            // change color of csv-button if dark mode is chosen
            if (isDarkModeTheme()) {
                $('[class*="buttons"]').css("color", "white");
            }
        </script>
    <?php } else if ($this->uri->segment(2) == "dok") { ?>
        <script>
            $.fn.dataTable.ext.buttons.clear = {
                className: 'buttons-clear',
                action: function(e, dt, node, config) {
                    dt.search('').draw();
                }
            };
            $('#doktable').DataTable({
                "pageLength": 25,
                responsive: false,
                ordering: false,
                "scrollY": "500px",
                "scrollCollapse": true,
                "paging": false,
                "scrollX": true,
                "language": {
                    url: getDataTablesLanguageUrl(),
                },
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'csv'
                    },
                    {
                        extend: 'clear',
                        text: 'Clear'
                    }
                ]
            });
            // change color of csv-button if dark mode is chosen
            if (isDarkModeTheme()) {
                $('[class*="buttons"]').css("color", "white");
            }
        </script>
    <?php } ?>
<?php } ?>

<?php if ($this->uri->segment(1) == "user") { ?>
    <!-- [MAP Custom] select list with icons -->
    <script>
        $(document).ready(function() {
            $('.icon_selectBox').off('click').on('click', function() {
                var boxcontent = $(this).attr('data-boxcontent');
                if ($('.icon_selectBox_data[data-boxcontent="' + boxcontent + '"]').is(":hidden")) {
                    $('.icon_selectBox_data[data-boxcontent="' + boxcontent + '"]').show();
                } else {
                    $('.icon_selectBox_data[data-boxcontent="' + boxcontent + '"]').hide();
                }
            });
            $('.icon_selectBox_data').off('mouseleave').on('mouseleave', function() {
                if ($(this).is(":visible")) {
                    $(this).hide();
                }
            });
            $('.icon_selectBox_data label').off('click').on('click', function() {
                var boxcontent = $(this).closest('.icon_selectBox_data').attr('data-boxcontent');
                $('input[name="user_map_' + boxcontent + '_icon"]').attr('value', $(this).attr('data-value'));
                if ($(this).attr('data-value') != "0") {
                    $('.user_icon_color[data-icon="' + boxcontent + '"]').show();
                    $('.icon_selectBox[data-boxcontent="' + boxcontent + '"] .icon_overSelect').html($(this).html());
                } else {
                    $('.user_icon_color[data-icon="' + boxcontent + '"]').hide();
                    $('.icon_selectBox[data-boxcontent="' + boxcontent + '"] .icon_overSelect').html($(this).html().substring(0, 10) + '.');
                }
                $('.icon_selectBox_data[data-boxcontent="' + boxcontent + '"]').hide();
            });

            $('.collapse').on('shown.bs.collapse', function(e) {
                var $card = $(this).closest('.accordion-item');
                var $open = $($(this).data('parent')).find('.collapse.show');

                var additionalOffset = 0;
                if ($card.prevAll().filter($open.closest('.accordion-item')).length !== 0) {
                    additionalOffset = $open.height();
                }
                $('html,body').animate({
                    scrollTop: $card.offset().top - additionalOffset
                }, 300);
            });
        });
    </script>
<?php } ?>

<?php
if (isset($scripts) && is_array($scripts)) {
    foreach ($scripts as $script) {
?><script type="text/javascript" src="<?php echo base_url() . $script; ?>"></script>
<?php
    }
}
?>
<script>
    <?php
    echo "var lang_datatables_language = '" . lang("datatables_language") . "';"
    ?>
</script>
</body>

</html>