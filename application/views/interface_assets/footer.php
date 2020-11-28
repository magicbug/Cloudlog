<!-- General JS Files used across Cloudlog -->
<script src="<?php echo base_url(); ?>assets/js/jquery-3.3.1.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/popper.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.fancybox.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.bundle.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.jclock.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/leaflet/leaflet.js"></script>
<script type="text/javascript" src="<?php echo base_url() ;?>assets/js/radiohelpers.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrapdialog/js/bootstrap-dialog.min.js"></script>
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

<?php if ($this->uri->segment(1) == "search" && $this->uri->segment(2) == "filter") { ?>

<script type="text/javascript" src="<?php echo base_url() ;?>assets/js/query-builder.standalone.min.js"></script>

<script type="text/javascript">

$(".search-results-box").hide();

    $('#builder').queryBuilder({
    filters: [
      <?php foreach ($get_table_names->result() as $row) {
        $value_name = str_replace("COL_", "", $row->Field);
        if ($value_name != "PRIMARY_KEY" && strpos($value_name, 'MY_') === false && strpos($value_name, '_INTL') == false) { ?>
        {
          id: '<?php echo $row->Field; ?>',
          label: '<?php echo $value_name; ?>',
          <?php if (strpos($row->Type, 'int(') !== false) { ?>
          type: 'integer',
          operators: ['equal', 'not_equal', 'less', 'less_or_equal', 'greater', 'greater_or_equal']
          <?php } elseif(strpos($row->Type, 'double') !== false) { ?>
          type: 'double',
          operators: ['equal', 'not_equal', 'less', 'less_or_equal', 'greater', 'greater_or_equal']
          <?php } elseif(strpos($row->Type, 'datetime') !== false) { ?>
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

  $('#btn-get').on('click', function() {
    var result = $('#builder').queryBuilder('getRules');
    if (!$.isEmptyObject(result)) {
      //alert(JSON.stringify(result, null, 2));

      $.post( "<?php echo site_url('search/json_result');?>", { search: JSON.stringify(result, null, 2), temp: "testvar" })
      .done(function( data ) {
        //console.log(data)
        //alert( "Data Loaded: " + data );
        $('.qso').remove();
        $(".search-results-box").show();

        $.each(JSON.parse(data), function(i, item) {

          var band = "";
          if(item.COL_SAT_NAME != "") {
            band = item.COL_SAT_NAME;
          } else {
            band = item.COL_BAND;
          }
          var callsign = '<a href="javascript:displayQso(' + item.COL_PRIMARY_KEY + ');" >' + item.COL_CALL + '</a>';
          if (item.COL_SUBMODE == '' || item.COL_SUBMODE == null) {
            $('#results').append('<tr class="qso"><td>' + item.COL_TIME_ON + '</td><td>' + callsign + '</td><td>' + item.COL_MODE + '</td><td>' + item.COL_RST_SENT + '</td><td>' + item.COL_RST_RCVD + '</td><td>' + band + '</td><td>' + item.COL_COUNTRY + '</td><td></td></tr>');
          }
          else {
            $('#results').append('<tr class="qso"><td>' + item.COL_TIME_ON + '</td><td>' + callsign + '</td><td>' + item.COL_SUBMODE + '</td><td>' + item.COL_RST_SENT + '</td><td>' + item.COL_RST_RCVD + '</td><td>' + band + '</td><td>' + item.COL_COUNTRY + '</td><td></td></tr>');
          }
        });

      });
    }
    else{
      //console.log("invalid object :");
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

</script>

<?php if ($this->uri->segment(1) == "" || $this->uri->segment(1) == "dashboard" ) { ?>
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/leaflet/L.Maidenhead.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/leaflet/leafembed.js"></script>
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
i=0;

function searchButtonPress(){
    event.preventDefault()
    if ($('#callsign').val()) {
      $('#partial_view').load("logbook/search_result/" + $('#callsign').val(), function() {});
    }
}

$(document).ready(function(){

  <?php if($this->input->post('callsign') != "") { ?>
    $('#partial_view').load("logbook/search_result/<?php echo $this->input->post('callsign'); ?>", function() {
    });
  <?php } ?>
    
$(document).on('keypress',function(e) { 
  if(e.which == 13) {

    if ($('#callsign').val()) {
      $('#partial_view').load("logbook/search_result/" + $('#callsign').val(), function() {});
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
    <script type="text/javascript" src="<?php echo base_url();?>assets/js/leaflet/leafembed.js"></script>
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

<script type="text/javascript">
$( document ).ready(function() {
  /*
    Populate the Satellite Names Field on the QSO Panel
  */
  $.getJSON( "<?php echo base_url();?>assets/json/satellite_data.json", function( data ) {

    // Build the options array
    var items = [];
    $.each( data, function( key, val ) {
      items.push(
        '<option value="' + key + '">' + key + '</option>'
        );
    });

    // Add to the datalist
    $('.satellite_names_list').append(items.join( "" ));
  });

});

var selected_sat;
var selected_sat_mode;

$(document).on('change', 'input', function(){
    var optionslist = $('.satellite_names_list')[0].options;
    var value = $(this).val();
    for (var x=0;x<optionslist.length;x++){
       if (optionslist[x].value === value) {
          $("#sat_mode").val(""); 
          $('.satellite_modes_list').find('option').remove().end();
          selected_sat = value;
          // get Json file
          $.getJSON( "<?php echo base_url();?>assets/json/satellite_data.json", function( data ) {

          // Build the options array
          var sat_modes = [];
          $.each( data, function( key, val ) {
            if (key == value) {
              $.each( val.Modes, function( key1, val2 ) {
                  //console.log (key1);
                  sat_modes.push('<option value="' + key1 + '">' + key1 + '</option>');
              });
            }
          });

          // Add to the datalist
          $('.satellite_modes_list').append(sat_modes.join( "" ));

        });
       }
    }
});

$(document).on('change', 'input', function(){
    var optionslist = $('.satellite_modes_list')[0].options;
    var value = $(this).val();
    for (var x=0;x<optionslist.length;x++){
       if (optionslist[x].value === value) {

          // Store selected sat mode
          selected_sat_mode = value;

          // get Json file
          $.getJSON( "<?php echo base_url();?>assets/json/satellite_data.json", function( data ) {

          // Build the options array
          var sat_modes = [];
          $.each( data, function( key, val ) {
            if (key == selected_sat) {
              $.each( val.Modes, function( key1, val2 ) {
                  if(key1 == selected_sat_mode) {
  
                    if (val2[0].Uplink_Mode == "LSB" || val2[0].Uplink_Mode == "USB") {
                      $("#mode").val("SSB");  
                    } else {
                      $("#mode").val(val2[0].Uplink_Mode);  
                    }
                    $("#band").val(frequencyToBand(val2[0].Uplink_Freq));
                    $("#frequency").val(val2[0].Uplink_Freq);  
                    $("#frequency_rx").val(val2[0].Downlink_Freq); 
                    $("#selectPropagation").val('SAT');
                  }
              });
            }
          });

        });
       }
    }
});

</script>

<script>
  var markers = L.layerGroup();
  var mymap = L.map('qsomap').setView([51.505, -0.09], 13);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
      '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
      'Created by Cloudlog',
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

  /* Function: reset_fields is used to reset the fields on the QSO page */
  function reset_fields() {

      $('#locator_info').text("");
      $('#country').val("");
      $('#lotw_info').text("");
      $('#dxcc_id').val("");
      $('#cqz').val("");
      $('#name').val("");
      $('#qth').val("");
      $('#locator').val("");
      $('#iota_ref').val("");
      $("#locator").removeClass("workedGrid");
      $("#locator").removeClass("newGrid");
      $("#callsign").removeClass("workedGrid");
      $("#callsign").removeClass("newGrid");
      $('#callsign_info').removeClass("badge-secondary");
      $('#callsign_info').removeClass("badge-success");
      $('#callsign_info').removeClass("badge-danger");
      $('#qsl_via').val("");
      $('#callsign_info').text("");
      $('#input_usa_state').val("");
      $('#qso-last-table').show();
      $('#partial_view').hide();

      mymap.setView([51.505, -0.09], 13);
      mymap.removeLayer(markers);
      $('.callsign-suggest').hide();
  }

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


    // On Key up check and suggest callsigns
    $("#callsign").keyup(function() {
    if ($(this).val().length >= 3) {
      $('.callsign-suggest').show();
      $.get('lookup/scp/' + $(this).val().toUpperCase(), function(result) {

        $('.callsign-suggestions').text(result);
      });
    }
    });

    $('#dxcc_id').on('change', function() {
        $.getJSON('logbook/jsonentity/' + $(this).val(), function (result) {
            if (result.dxcc.name != undefined) {

                $('#country').val(convert_case(result.dxcc.name));
                $('#cqz').val(convert_case(result.dxcc.cqz));

                $('#callsign_info').removeClass("badge-secondary");
                $('#callsign_info').removeClass("badge-success");
                $('#callsign_info').removeClass("badge-danger");
                $('#callsign_info').attr('title', '');
                $('#callsign_info').text(convert_case(result.dxcc.name));

                changebadge(result.dxcc.name);

                // Set Map to Lat/Long it locator is not empty
                if($('#locator').val() == "") {
                    var redIcon = L.icon({
                        iconUrl: icon_dot_url,
                        iconSize:     [18, 18], // size of the icon
                    });

                    markers.clearLayers();
                    var marker = L.marker([result.dxcc.lat, result.dxcc.long], {icon: redIcon});
					          mymap.setZoom(8);
					          mymap.panTo([result.dxcc.lat, result.dxcc.long]);
                    markers.addLayer(marker).addTo(mymap);
                }
            }
        });
    });

    function changebadge(entityname) {
        if($("#sat_name" ).val() != "") {
            $.getJSON('logbook/jsonlookupdxcc/' + convert_case(entityname) + '/SAT/0/0', function(result)
            {

                $('#callsign_info').removeClass("badge-secondary");
                $('#callsign_info').removeClass("badge-success");
                $('#callsign_info').removeClass("badge-danger");
                $('#callsign_info').attr('title', '');

                if (result.workedBefore)
                {
                    $('#callsign_info').addClass("badge-success");
                    $('#callsign_info').attr('title', 'DXCC was already worked in the past on this band and mode!');
                }
                else
                {
                    $('#callsign_info').addClass("badge-danger");
                    $('#callsign_info').attr('title', 'New DXCC, not worked on this band and mode!');
                }
            })
        } else {
            $.getJSON('logbook/jsonlookupdxcc/' + convert_case(entityname) + '/0/' + $("#band").val() +'/' + $("#mode").val(), function(result)
            {
                // Reset CSS values before updating
                $('#callsign_info').removeClass("badge-secondary");
                $('#callsign_info').removeClass("badge-success");
                $('#callsign_info').removeClass("badge-danger");
                $('#callsign_info').attr('title', '');

                if (result.workedBefore)
                {
                    $('#callsign_info').addClass("badge-success");
                    $('#callsign_info').attr('title', 'DXCC was already worked in the past on this band and mode!');
                }
                else
                {
                    $('#callsign_info').addClass("badge-danger");
                    $('#callsign_info').attr('title', 'New DXCC, not worked on this band and mode!');
                }
            })
        }
    }
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

    $("#callsign").focusout(function() {

        if ($(this).val().length >= 3) {
            /* Find and populate DXCC */
            $('.callsign-suggest').hide();

            if($("#sat_name").val() != ""){
              var sat_type = "SAT";
              var json_band = "0";
              var json_mode = "0";
            } else {
              var sat_type = "0";
              var json_band = $("#band").val();
              var json_mode = $("#mode").val();
            }


            $.getJSON('logbook/json/' + $(this).val().toUpperCase() + '/' + sat_type + '/' + json_band + '/' + json_mode, function(result)
            {
              //$('#country').val(result); lotw_info
              if(result.dxcc.entity != undefined) {
                $('#country').val(convert_case(result.dxcc.entity));
                $('#callsign_info').text(convert_case(result.dxcc.entity));
				
				if($("#sat_name" ).val() != "") {
					//logbook/jsonlookupgrid/io77/SAT/0/0
					$.getJSON('logbook/jsonlookupcallsign/' + $("#callsign").val().toUpperCase() + '/SAT/0/0', function(result)
					{
					  // Reset CSS values before updating
					  $('#callsign').removeClass("workedGrid");
					  $('#callsign').removeClass("newGrid");
					  $('#callsign').attr('title', '');

					  if (result.workedBefore)
					  {
						$('#callsign').addClass("workedGrid");
						$('#callsign').attr('title', 'Callsign was already worked in the past on this band and mode!');
					  }
					  else
					  {
						$('#callsign').addClass("newGrid");
						$('#callsign').attr('title', 'New Callsign!');
					  }
					})
				  } else {
					$.getJSON('logbook/jsonlookupcallsign/' + $("#callsign").val().toUpperCase() + '/0/' + $("#band").val() +'/' + $("#mode").val(), function(result)
					{
					  // Reset CSS values before updating
					  $('#callsign').removeClass("workedGrid");
					  $('#callsign').removeClass("newGrid");
					  $('#callsign').attr('title', '');

					  if (result.workedBefore)
					  {
						$('#callsign').addClass("workedGrid");
						$('#callsign').attr('title', 'Callsign was already worked in the past on this band and mode!');
					  }
					  else
					  {
						$('#callsign').addClass("newGrid");
						$('#callsign').attr('title', 'New Callsign!');
					  }
					})
				  }

                  changebadge(result.dxcc.entity);
              }

              if(result.lotw_member == "active") {
                $('#lotw_info').text("LoTW");
              }

              $('#dxcc_id').val(result.dxcc.adif);
              $('#cqz').val(result.dxcc.cqz);
              $('#ituz').val(result.dxcc.ituz);


              var redIcon = L.icon({
                iconUrl: icon_dot_url,
                iconSize:     [18, 18], // size of the icon
              });

              // Set Map to Lat/Long
              markers.clearLayers();
				      mymap.setZoom(8);
              if (typeof result.latlng !== "undefined" && result.latlng !== false) {
                var marker = L.marker([result.latlng[0], result.latlng[1]], {icon: redIcon});
                mymap.panTo([result.latlng[0], result.latlng[1]]);
              } else {
                var marker = L.marker([result.dxcc.lat, result.dxcc.long], {icon: redIcon});
                mymap.panTo([result.dxcc.lat, result.dxcc.long]);
              }

              markers.addLayer(marker).addTo(mymap);
        

            /* Find Locator if the field is empty */
            if($('#locator').val() == "") {
                $('#locator').val(result.callsign_qra);
                $('#locator_info').html(result.bearing);

                if (result.callsign_qra != "")
                {
                  if (result.workedBefore)
                  {
                    $('#locator').addClass("workedGrid");
                    $('#locator').attr('title', 'Grid was already worked in the past');
                  }
                  else
                  {
                    $('#locator').addClass("newGrid");
                    $('#locator').attr('title', 'New grid!');
                  }
                }
                else
                {
                  $('#locator').removeClass("workedGrid");
                  $('#locator').removeClass("newGrid");
                  $('#locator').attr('title', '');
                }
                
            }

            /* Find Operators Name */
            if($('#qsl_via').val() == "") {
                $('#qsl_via').val(result.qsl_manager);
            }

            /* Find Operators Name */
            if($('#name').val() == "") {
                $('#name').val(result.callsign_name);
            }

            if($('#qth').val() == "") {
                $('#qth').val(result.callsign_qth);
            }

            /*
            * Update state with returned value
            */
            if($("#input_usa_state").val() == "") {
              $("#input_usa_state").val(result.callsign_state).trigger('change');
            }


            if($('#iota_ref').val() == "") {
                $('#iota_ref').val(result.callsign_iota);
            }
            // Hide the last QSO table
            $('#qso-last-table').hide();
            $('#partial_view').show();
            /* display past QSOs */
            $('#partial_view').html(result.partial);
            });
          } else {
            /* Reset fields ... */
            $('#callsign_info').text("");
            $('#locator_info').text("");
            $('#country').val("");
            $('#dxcc_id').val("");
            $('#cqz').val("");
            $('#name').val("");
            $('#qth').val("");
            $('#locator').val("");
            $('#iota_ref').val("");
            $("#locator").removeClass("workedGrid");
            $("#locator").removeClass("newGrid");
            $("#callsign").removeClass("workedGrid");
            $("#callsign").removeClass("newGrid");
			      $('#callsign_info').removeClass("badge-secondary");
			      $('#callsign_info').removeClass("badge-success");
			      $('#callsign_info').removeClass("badge-danger");
            $('#input_usa_state').val("");
        }  
    })

        // Only set the frequency when not set by userdata/PHP.
    if ($('#frequency').val() == "")
    {
      $.get('qso/band_to_freq/' + $('#band').val() + '/' + $('.mode').val(), function(result) {
            $('#frequency').val(result);
            $('#frequency_rx').val("");
      });
    } 

    /* on mode change */
    $('.mode').change(function() {
        $.get('qso/band_to_freq/' + $('#band').val() + '/' + $('.mode').val(), function(result) {
            $('#frequency').val(result);
            $('#frequency_rx').val("");
          });  
      });  

    /* Calculate Frequency */
      /* on band change */
      $('#band').change(function() {
        $.get('qso/band_to_freq/' + $(this).val() + '/' + $('.mode').val(), function(result) {
            $('#frequency').val(result);
            $('#frequency_rx').val("");
          });  
      });
  
      /* On Key up Calculate Bearing and Distance */
    $("#locator").keyup(function(){
      if ($(this).val()) {
        var qra_input = $(this).val();

        var qra_lookup = qra_input.substring(0, 4);

        if(qra_lookup.length >= 4) {
          
          // Check Log if satname is provided
          if($("#sat_name" ).val() != "") {

            //logbook/jsonlookupgrid/io77/SAT/0/0

            $.getJSON('logbook/jsonlookupgrid/' + qra_lookup.toUpperCase() + '/SAT/0/0', function(result)
            {
              // Reset CSS values before updating
              $('#locator').removeClass("workedGrid");
              $('#locator').removeClass("newGrid");
              $('#locator').attr('title', '');

              if (result.workedBefore)
              {
                $('#locator').addClass("workedGrid");
                $('#locator').attr('title', 'Grid was already worked in the past');
              }
              else
              {
                $('#locator').addClass("newGrid");
                $('#locator').attr('title', 'New grid!');
              }
            })
          } else {
            $.getJSON('logbook/jsonlookupgrid/' + qra_lookup.toUpperCase() + '/0/' + $("#band").val() +'/' + $("#mode").val(), function(result)
            {
              // Reset CSS values before updating
              $('#locator').removeClass("workedGrid");
              $('#locator').removeClass("newGrid");
              $('#locator').attr('title', '');

              if (result.workedBefore)
              {
                $('#locator').addClass("workedGrid");
                $('#locator').attr('title', 'Grid was already worked in the past');
              }
              else
              {
                $('#locator').addClass("newGrid");
                $('#locator').attr('title', 'New grid!');
              }
            })
          }
        }

        if(qra_input.length >= 4 && $(this).val().length > 0) {
          $.getJSON('logbook/qralatlngjson/' + $(this).val(), function(result)
          {
            // Set Map to Lat/Long
            markers.clearLayers();
            if (typeof result !== "undefined") {
              var redIcon = L.icon({
                        iconUrl: icon_dot_url,
                        iconSize:     [18, 18], // size of the icon
                    });

              var marker = L.marker([result[0], result[1]], {icon: redIcon});
              mymap.setZoom(8);
              mymap.panTo([result[0], result[1]]);
            }
            markers.addLayer(marker).addTo(mymap);
          })
          
          $('#locator_info').load("logbook/searchbearing/" + $(this).val()).fadeIn("slow");
        }
      }
    });

    // Change report based on mode
    $('.mode').change(function(){
      setRst($('.mode') .val());
    });

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


  /* Javascript for controlling rig frequency. */
<?php if ( $_GET['manual'] == 0 ) { ?>
  var updateFromCAT = function() {
    if($('select.radios option:selected').val() != '0') {
      radioID = $('select.radios option:selected').val(); 
      $.getJSON( "radio/json/" + radioID, function( data ) {
          /* {
              "uplink_freq": "2400210000",
              "downlink_freq": "10489710000",
              "mode": "SSB",
              "satmode": "",
              "satname": "ES'HAIL-2"
          }  */
          if (data.uplink_freq != "")
          {
            $('#frequency').val(data.uplink_freq);
            $("#band").val(frequencyToBand(data.uplink_freq));
          }
          if (data.downlink_freq != "")
          {
            $('#frequency_rx').val(data.downlink_freq);
          }

          old_mode = $(".mode").val();
          if (data.mode == "LSB" || data.mode == "USB" || data.mode == "SSB") {
            $(".mode").val('SSB');
          } else {
            $(".mode").val(data.mode);  
          }

          if (old_mode !== $(".mode").val()) {
            // Update RST on mode change via CAT
            setRst($(".mode").val());
          }
          $("#sat_name").val(data.satname);  
          $("#sat_mode").val(data.satmode);  

          // Display CAT Timeout warnng based on the figure given in the config file
            var minutes = Math.floor(<?php echo $this->config->item('cat_timeout_interval'); ?> / 60);

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

  // If radio isn't SatPC32 clear sat_name and sat_mode
  $( ".radios" ).change(function() {
      if ($(".radios option:selected").text() != "SatPC32") {
        $("#sat_name").val("");  
        $("#sat_mode").val("");  
        $("#frequency").val("");  
        $("#frequency_rx").val(""); 
        $("#selectPropagation").val($("#selectPropagation option:first").val());
      }

      if ($(".radios option:selected").text() == "None") {
        $(".radio_timeout_error" ).remove();
      }

  });

<?php } ?>

  function convert_case(str) {
    var lower = str.toLowerCase();
    return lower.replace(/(^| )(\w)/g, function(x) {
      return x.toUpperCase();
    });
  }

  </script>

<?php } ?>

<?php if ($this->uri->segment(1) == "logbook" && $this->uri->segment(2) == "view") { ?>
<script>

  var mymap = L.map('map').setView([lat,long], 5);

  L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>, ' +
      'Generated by <a href="http://www.cloudlog.co.uk/">Cloudlog</a>',
    id: 'mapbox.streets'
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

<?php if ($this->uri->segment(1) == "gridsquares") { ?>

<script type="text/javascript" src="<?php echo base_url();?>assets/js/leaflet/L.MaidenheadColoured.js"></script>

<script>

  var layer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
      '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
      'Created by Cloudlog',
    id: 'mapbox.streets'
  });


  var map = L.map('map', {
    layers: [layer],
    center: [19, 0],
    zoom: 3
  });
  
  var grid_two = <?php echo $grid_2char; ?>;
  var grid_four = <?php echo $grid_4char; ?>;
  var grid_six = <?php echo $grid_6char; ?>;

  var grid_two_confirmed = <?php echo $grid_2char_confirmed; ?>;
  var grid_four_confirmed = <?php echo $grid_4char_confirmed; ?>;
  var grid_six_confirmed = <?php echo $grid_6char_confirmed; ?>;

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

    if(map.getZoom() > 5) {
      var search_type = "<?php echo $this->uri->segment(2); ?>";
      if(search_type == "satellites") {
        console.log("satellites search");
        var search_tags = "search_sat/" + loc_4char;
      } else {
        var band = "<?php echo $this->uri->segment(3); ?>";
        console.log(band);
        var search_tags = "search_band/" + band + "/" + loc_4char;
      }

      $.getJSON( "<?php echo site_url('gridsquares/');?>" + search_tags, function( data ) {
        var count = Object.keys(data).length;
        console.log("Count: " + count);
        var items = [];
        $.each( data, function( i, item ) {
          console.log(item.COL_CALL + item.COL_SAT_NAME);
          if(item.COL_SAT_NAME != undefined) {
            items.push( "<tr><td>" + item.COL_TIME_ON + "</td><td>" + item.COL_CALL + "</td><td>" + item.COL_MODE + "</td><td>" + item.COL_SAT_NAME + "</td><td>" + item.COL_GRIDSQUARE + "</td></tr>" );
          } else {
            items.push( "<tr><td>" + item.COL_TIME_ON + "</td><td>" + item.COL_CALL + "</td><td>" + item.COL_MODE + "</td><td>" + item.COL_BAND + "</td><td>" + item.COL_GRIDSQUARE + "</td></tr>" );
          }
        });

        $('#qso_count').text(count);
        if (count > 1) {
           $('#gt1_qso').text("s");
        } else {
           $('#gt1_qso').text("");
        }

        $("#grid_results tbody").empty(); 
        if (count > 0) {
          $("#grid_results tbody").append(items.join( "" ));

          $('#square_number').text(loc_4char);
          $('#exampleModal').modal('show');
        }

      });
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

<?php if ($this->uri->segment(1) == "dayswithqso") { ?>
    <script src="<?php echo base_url(); ?>assets/js/chart.js"></script>
    <script>
        var baseURL= "<?php echo base_url();?>";
        $.ajax({
            url: baseURL+'index.php/dayswithqso/get_days',
            success: function(data) {
                var labels = [];
                var dataDxcc = [];
                $.each(data, function(){
                    labels.push(this.Year);
                    dataDxcc.push(this.Days);
                });
                var ctx = document.getElementById("myChartDiff").getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Days with QSOs',
                            data: dataDxcc,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                beginAtZero:true
                                }
                            }]
                        },
                    }
                });
            }
        });
    </script>
<?php } ?>

<?php if ($this->uri->segment(1) == "distances") { ?>
    <script src="<?php echo base_url(); ?>assets/js/highstock.js"></script>
<script>

  var bands_available = <?php echo $bands_available; ?>;

  $.each(bands_available, function(key, value) {
     $('#distplot_bands')
         .append($("<option></option>")
                    .attr("value",value)
                    .text(value));
  });

  var num = "<?php echo $this->uri->segment(3);?>";
    $("#distplot_bands option").each(function(){
        if($(this).val()==num){ // EDITED THIS LINE
            $(this).attr("selected","selected");
        }
    });

  function distPlot(form) {
      $(".alert").remove();
      var baseURL= "<?php echo base_url();?>";
      $.ajax({
          url: baseURL+'index.php/distances/get_distances',
          type: 'post',
          data: {'band': form.distplot_bands.value},
          success: function(tmp) {
              if (tmp.ok == 'OK') {
                  if (!($('#information').length > 0))
                      $("#distances_div").append('<div id="information"></div><div id="graphcontainer" style="height: 600px; margin: 0 auto"></div>');
                  var options = {
                      chart: {
                          type: 'column',
                          zoomType: 'xy',
                          renderTo: 'graphcontainer'
                      },
                      title: {
                          text: 'Distance distribution'
                      },
                      xAxis: {
                          categories: [],
                          crosshair: true,
                          type: "category",
                          min:0,
                          max:100

                      },
                      yAxis: {
                          title: {
                              text: '# QSOs'
                          }
                      },
                      navigator: {
                          enabled: true,
                          xAxis: {
                              labels: {
                                  formatter: function() {
                                      return this.value * '50' + ' ' + tmp.unit;
                                  }
                              }
                          }
                      },
                      rangeSelector: {
                          selected: 1
                      },
                      tooltip: {
                          formatter: function () {
                              if(this.point) {
                                  return "Distance: " + options.xAxis.categories[this.point.x] +
                                      "<br />Callsign(s) worked (max 5 shown): " + myComments[this.point.x] +
                                      "<br />Number of QSOs: <strong>" + series.data[this.point.x] + "</strong>";
                              }
                          }
                      },
                      series: []
                  };
                  var myComments=[];

                  var series = {
                      data: [],
                      showInNavigator: true
                  };

                  $.each(tmp.qsodata, function(){
                      myComments.push(this.calls);
                      options.xAxis.categories.push(this.dist);
                      series.name = 'Number of QSOs';
                      series.data.push(this.count);

                  });

                  options.series.push(series);

                  $('#information').html(tmp.qrb.Qsoes + " contacts were plotted.<br /> Your furthest contact was with " + tmp.qrb.Callsign
                      + " in gridsquare "+ tmp.qrb.Grid
                      +"; the distance was "
                      +tmp.qrb.Distance + tmp.unit +".");

                  var chart = new Highcharts.Chart(options);
              }
              else {
                  if (($('#information').length > 0)) {
                      $("#information").remove();
                      $("#graphcontainer").remove();
                  }
                  $("#distances_div").append('<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + tmp.Error + '</div>');
              }
          }
      });
  }

</script>
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
        <script>
            function ExportQrz(station_id) {
                if ($(".alert").length > 0) {
                    $(".alert").remove();
                }
                if ($(".errormessages").length > 0) {
                    $(".errormessages").remove();
                }
                $(".ld-ext-right").addClass('running');
                $(".ld-ext-right").prop('disabled', true);
                var baseURL= "<?php echo base_url();?>";
                $.ajax({
                    url: baseURL + 'index.php/qrz/upload_station',
                    type: 'post',
                    data: {'station_id': station_id},
                    success: function (data) {
                        $(".ld-ext-right").removeClass('running');
                        $(".ld-ext-right").prop('disabled', false);
                        if (data.status == 'OK') {
                            $.each(data.info, function(index, value){
                                $('#modcount'+value.station_id).html(value.modcount);
                                $('#notcount'+value.station_id).html(value.notcount);
                                $('#totcount'+value.station_id).html(value.totcount);
                            });
                            $(".card-body").append('<div class="alert alert-success" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + data.infomessage + '</div>');
                        }
                        else {
                            $(".card-body").append('<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + data.info + '</div>');
                        }

                        if (data.errormessages.length > 0) {
                            $(".card-body").append('' +
                                '<div class="errormessages"><p>\n' +
                                '                            <button class="btn btn-danger" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">\n' +
                                '                                Show error messages\n' +
                                '                            </button>\n' +
                                '                            </p>\n' +
                                '                            <div class="collapse" id="collapseExample">\n' +
                                '                                <div class="card card-body"><div class="errors"></div>\n' +
                                '                            </div>\n' +
                                '                            </div></div>');
                            $.each(data.errormessages, function(index, value) {
                                $(".errors").append('<li>' + value);
                            });
                        }
                    }
                });
            }

        </script>
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

                                L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
                                    maxZoom: 18,
                                    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a>, ' +
                                        'Generated by <a href="http://www.cloudlog.co.uk/">Cloudlog</a>',
                                    id: 'mapbox.streets'
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

    // using this to change color of csv-button if dark mode is chosen
    var background = $('body').css( "background-color");

    if (background != ('rgb(255, 255, 255)')) {
        $(".buttons-csv").css("color", "white");
    }

        function displayDxccContacts(country, band) {
            var baseURL = "<?php echo base_url();?>";
            $.ajax({
                url: baseURL + 'index.php/awards/dxcc_details_ajax',
                type: 'post',
                data: {
                    'Country': country,
                    'Band': band
                },
                success: function (html) {
                    BootstrapDialog.show({
                        title: 'QSO Data',
                        size: BootstrapDialog.SIZE_WIDE,
                        cssClass: 'qso-dxcc-dialog',
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

    // using this to change color of csv-button if dark mode is chosen
    var background = $('body').css( "background-color");

    if (background != ('rgb(255, 255, 255)')) {
        $(".buttons-csv").css("color", "white");
    }

            function displayVuccContacts(gridsquare, band) {
                var baseURL= "<?php echo base_url();?>";
                $.ajax({
                    url: baseURL + 'index.php/awards/vucc_details_ajax',
                    type: 'post',
                    data: {'Gridsquare': gridsquare,
                        'Band': band
                    },
                    success: function(html) {
                        BootstrapDialog.show({
                            title: 'QSO Data',
                            size: BootstrapDialog.SIZE_WIDE,
                            cssClass: 'qso-vucc-dialog',
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
    </script>
<?php } ?>


<?php if ($this->uri->segment(2) == "dok") { ?>
    <script>
        function displayDokContacts(dok, band) {
            var baseURL= "<?php echo base_url();?>";
            $.ajax({
                url: baseURL + 'index.php/awards/dok_details_ajax',
                type: 'post',
                data: {'Dok': dok,
                    'Band': band
                },
                success: function(html) {
                    BootstrapDialog.show({
                        title: 'QSO Data',
                        size: BootstrapDialog.SIZE_WIDE,
                        cssClass: 'qso-dok-dialog',
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

        // using this to change color of csv-button if dark mode is chosen
        var background = $('body').css( "background-color");

        if (background != ('rgb(255, 255, 255)')) {
            $(".buttons-csv").css("color", "white");
        }

        function displayIotaContacts(iota, band) {
            var baseURL= "<?php echo base_url();?>";
            $.ajax({
                url: baseURL + 'index.php/awards/iota_details_ajax',
                type: 'post',
                data: {'Iota': iota,
                    'Band': band
                },
                success: function(html) {
                    BootstrapDialog.show({
                        title: 'QSO Data',
                        size: BootstrapDialog.SIZE_WIDE,
                        cssClass: 'qso-iota-dialog',
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

        // using this to change color of csv-button if dark mode is chosen
        var background = $('body').css( "background-color");

        if (background != ('rgb(255, 255, 255)')) {
            $(".buttons-csv").css("color", "white");
        }

            function displayCqContacts(cqzone, band) {
                var baseURL= "<?php echo base_url();?>";
                $.ajax({
                    url: baseURL + 'index.php/awards/cq_details_ajax',
                    type: 'post',
                    data: {'CQZone': cqzone,
                        'Band': band
                    },
                    success: function(html) {
                        BootstrapDialog.show({
                            title: 'QSO Data',
                            size: BootstrapDialog.SIZE_WIDE,
                            cssClass: 'qso-cq-dialog',
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

        // using this to change color of csv-button if dark mode is chosen
        var background = $('body').css( "background-color");

        if (background != ('rgb(255, 255, 255)')) {
            $(".buttons-csv").css("color", "white");
        }

        function displayWasContacts(was, band) {
            var baseURL= "<?php echo base_url();?>";
            $.ajax({
                url: baseURL + 'index.php/awards/was_details_ajax',
                type: 'post',
                data: {'State': was,
                    'Band': band
                },
                success: function(html) {
                    BootstrapDialog.show({
                        title: 'QSO Data',
                        size: BootstrapDialog.SIZE_WIDE,
                        cssClass: 'qso-was-dialog',
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
                        $(".qsl_" + id).remove(); // removes choice from menu
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
                    <?php if ($this->uri->segment(1) != "search" && $this->uri->segment(2) != "filter") { ?>location.reload();<?php } ?>
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

            // using this to change color of csv-button if dark mode is chosen
            var background = $('body').css( "background-color");

            if (background != ('rgb(255, 255, 255)')) {
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
        <script>
            $('.modetable').DataTable({
                "pageLength": 25,
                responsive: false,
                ordering: false,
                "scrollY":        "500px",
                "scrollCollapse": true,
                "paging":         false,
                "scrollX": true
            });

            function createModeDialog() {
                var baseURL= "<?php echo base_url();?>";
                $.ajax({
                    url: baseURL + 'index.php/mode/create',
                    type: 'post',
                    success: function(html) {
                        BootstrapDialog.show({
                            title: 'Create mode',
                            size: BootstrapDialog.SIZE_WIDE,
                            cssClass: 'create-mode-dialog',
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

            function createMode(form) {
                var baseURL= "<?php echo base_url();?>";
                if (form.mode.value != '') {
                    $.ajax({
                        url: baseURL + 'index.php/mode/create',
                        type: 'post',
                        data: {'mode': form.mode.value,
                            'submode': form.submode.value,
                            'qrgmode': form.qrgmode.value,
                            'active': form.active.value},
                        success: function(html) {
                            location.reload();
                        }
                    });
                }
            }

            function deactivateMode(modeid) {
                var baseURL= "<?php echo base_url();?>";
                $.ajax({
                    url: baseURL + 'index.php/mode/deactivate',
                    type: 'post',
                    data: {'id': modeid },
                    success: function(html) {
                        $(".mode_" + modeid).text('not active');
                        $('.btn_'+modeid).html('Activate');
                        $('.btn_'+modeid).attr('onclick', 'activateMode('+modeid+')')
                    }
                });
            }

            function activateMode(modeid) {
                var baseURL= "<?php echo base_url();?>";
                $.ajax({
                    url: baseURL + 'index.php/mode/activate',
                    type: 'post',
                    data: {'id': modeid },
                    success: function(html) {
                        $('.mode_'+modeid).text('active');
                        $('.btn_'+modeid).html('Deactivate');
                        $('.btn_'+modeid).attr('onclick', 'deactivateMode('+modeid+')')
                    }
                });
            }

            function deleteMode(id, mode) {
                BootstrapDialog.confirm({
                    title: 'DANGER',
                    message: 'Warning! Are you sure you want to delete the following mode: ' + mode + '?'  ,
                    type: BootstrapDialog.TYPE_DANGER,
                    closable: true,
                    draggable: true,
                    btnOKClass: 'btn-danger',
                    callback: function(result) {
                        if(result) {
                            var baseURL= "<?php echo base_url();?>";
                            $.ajax({
                                url: baseURL + 'index.php/mode/delete',
                                type: 'post',
                                data: {'id': id
                                },
                                success: function(data) {
                                    $(".mode_" + id).parent("tr:first").remove(); // removes mode from table
                                }
                            });
                        }
                    }
                });
            }
        </script>
    <?php } ?>

<?php if ($this->uri->segment(1) == "accumulated") { ?>
    <script src="<?php echo base_url(); ?>assets/js/chart.js"></script>
    <script>
        function accumulatePlot(form) {
            $(".ld-ext-right").addClass('running');
            $(".ld-ext-right").prop('disabled', true);

            // using this to change color of legend and label according to background color
            var background = $('body').css( "background-color");
            var color = 'grey';
            if (background != ('rgb(255, 255, 255)')) {
                color = 'white';
            }

            var baseURL= "<?php echo base_url();?>";
            var award = form.awardradio.value;
            var mode = form.mode.value;
            var period = form.periodradio.value;
            $.ajax({
                url: baseURL+'index.php/accumulated/get_accumulated_data',
                type: 'post',
                data: {'Band': form.band.value, 'Award': award, 'Mode': mode, 'Period': period},
                success: function(data) {
                    // used for switching award text in the table and the chart
                    switch(award) {
                        case 'dxcc': var awardtext = "DXCC\'s"; break;
                        case 'was':  var awardtext = "states";break;
                        case 'iota': var awardtext = "IOTA\'s";break;
                        case 'waz':  var awardtext = "CQ zones"; break;
                    }

                    var periodtext = 'Year';
                    if (period == 'month') {
                        periodtext += ' + month';
                    }
                    // removing the old chart so that it will not interfere when loading chart again
                    $("#accumulateContainer").empty();
                    $("#accumulateContainer").append("<canvas id=\"myChartAccumulate\" width=\"400\" height=\"150\"></canvas><div id=\"accumulateTable\"></div>");

                    // appending table to hold the data
                    $("#accumulateTable").append('<table style="width:100%" class="accutable table table-sm table-bordered table-hover table-striped table-condensed text-center"><thead>' +
                        '<tr>' +
                        '<td>#</td>' +
                        '<td>' + periodtext + '</td>' +
                        '<td>Accumulated # of ' + awardtext + ' worked </td>'+
                        '</tr>' +
                        '</thead>' +
                        '<tbody></tbody></table>');
                    var labels = [];
                    var dataDxcc = [];

                    var $myTable = $('.accutable');
                    var i = 1;

                    // building the rows in the table
                    var rowElements = data.map(function ( row ) {

                        var $row = $('<tr></tr>');

                        var $iterator = $('<td></td>').html(i++);
                        var $type = $('<td></td>').html(row.year);
                        var $content = $('<td></td>').html(row.total);

                        $row.append($iterator, $type, $content);

                        return $row;
                    });

                    // finally inserting the rows
                    $myTable.append(rowElements);

                    $.each(data, function(){
                        labels.push(this.year);
                        dataDxcc.push(this.total);
                    });

                    var ctx = document.getElementById("myChartAccumulate").getContext('2d');
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Accumulated number of ' + awardtext + ' worked each ' + period,
                                data: dataDxcc,
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 2,
                            }]
                        },
                        options: {
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero:true,
                                        fontColor: color
                                    }
                                }],
                                xAxes: [{
                                    ticks: {
                                        fontColor: color
                                    }
                                }]
                            },
                            legend: {
                                labels: {
                                    fontColor: color,
                                }
                            },
                        }
                    });
                    $(".ld-ext-right").removeClass('running');
                    $(".ld-ext-right").prop('disabled', false);
                    $('.accutable').DataTable({
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

                    // using this to change color of csv-button if dark mode is chosen
                    var background = $('body').css( "background-color");

                    if (background != ('rgb(255, 255, 255)')) {
                        $(".buttons-csv").css("color", "white");
                    }
                }
            });
        }
    </script>
<?php } ?>

<?php if ($this->uri->segment(1) == "timeplotter") { ?>
    <script src="<?php echo base_url(); ?>assets/js/highstock.js"></script>
    <script>

        function timeplot(form) {
            $(".ld-ext-right").addClass('running');
            $(".ld-ext-right").prop('disabled', true);
            $(".alert").remove();
            var baseURL= "<?php echo base_url();?>";
            $.ajax({
                url: baseURL+'index.php/timeplotter/getTimes',
                type: 'post',
                data: {'band': form.band.value, 'dxcc': form.dxcc.value, 'cqzone': form.cqzone.value},
                success: function(tmp) {
                    $(".ld-ext-right").removeClass('running');
                    $(".ld-ext-right").prop('disabled', false);
                    if (tmp.ok == 'OK') {
                        plotTimeplotterChart(tmp);
                    }
                    else {
                        $("#container").remove();
                        $("#info").remove();
                        $("#timeplotter_div").append('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n' +
                            tmp.error +
                            '</div>');
                    }
                }
            });
        }

        function plotTimeplotterChart(tmp) {
            $("#container").remove();
            $("#info").remove();
            $("#timeplotter_div").append('<p id="info">' + tmp.qsocount + ' contacts were plotted.</p><div id="container" style="height: 600px;"></div>');
            var options = {
                chart: {
                    type: 'column',
                    zoomType: 'xy',
                    renderTo: 'container'
                },
                title: {
                    text: 'Time distribution'
                },
                xAxis: {
                    categories: [],
                    crosshair: true,
                    type: "category",
                    min:0,
                    max:47,
                },
                yAxis: {
                    title: {
                        text: '# QSOs'
                    }
                },
                rangeSelector: {
                    selected: 1
                },
                tooltip: {
                    formatter: function () {
                        if(this.point) {
                            return "Time: " + options.xAxis.categories[this.point.x] +
                                "<br />Callsign(s) worked (max 5): " + myComments[this.point.x] +
                                "<br />Number of QSOs: <strong>" + series.data[this.point.x] + "</strong>";
                        }
                    }
                },
                series: []
            };
            var myComments=[];

            var series = {
                data: []
            };

            $.each(tmp.qsodata, function(){
                myComments.push(this.calls);
                options.xAxis.categories.push(this.time);
                series.name = 'Number of QSOs';
                series.data.push(this.count);
            });

            options.series.push(series);

            var chart = new Highcharts.Chart(options);
        }

    </script>
<?php } ?>

<?php if ($this->uri->segment(1) == "qsl") { ?>
    <script>
        $('.qsltable').DataTable({
            "pageLength": 25,
            responsive: false,
            ordering: false,
            "scrollY":        "500px",
            "scrollCollapse": true,
            "paging":         false,
            "scrollX": true
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
                    $("#qslupload").append('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n' +
                        data.status.front +
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
                    $("#qslupload").append('<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>\n' +
                        data.status.back +
                        '</div>');
                }
            }
        });
    }
</script>
  </body>
</html>
