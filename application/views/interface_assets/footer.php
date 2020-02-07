    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="<?php echo base_url(); ?>assets/js/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.jclock.js"></script>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/leaflet/leaflet.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ;?>assets/js/radiohelpers.js"></script>

    <?php if ($this->uri->segment(1) == "adif") { ?>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>
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

<?php if ($this->uri->segment(1) == "notes" && ($this->uri->segment(2) == "add" || $this->uri->segment(2) == "edit") ) { ?>
    <script src="<?php echo base_url() ;?>assets/plugins/quill/quill.min.js"></script>
    
    <script>
      var quill = new Quill('#quillArea', { 
        placeholder: 'Compose an epic...',
        theme: 'snow'
      });

      $("#notes_add").on("submit",function(){
        $("#hiddenArea").val(quill.root.innerHTML);
      })
    </script>
<?php } ?>

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

          var callsign = '<a data-fancybox data-type="iframe" data-src="<?php echo site_url('logbook/view');?>/' + item.COL_PRIMARY_KEY + '" data-src="" href="javascript:;">' + item.COL_CALL + '</a>';

          $('#results').append('<tr class="qso"><td>' + item.COL_TIME_ON + '</td><td>' + callsign + '</td><td>' + item.COL_MODE + '</td><td>' + item.COL_RST_SENT + '</td><td>' + item.COL_RST_RCVD + '</td><td>' + band + '</td><td>' + item.COL_COUNTRY + '</td><td></td></tr>');
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
      
        <?php if($qra == "set") { ?>
        var q_lat = <?php echo $qra_lat; ?>;
        var q_lng = <?php echo $qra_lng; ?>;    
        <?php } else { ?>
        var q_lat = 40.313043;
        var q_lng = -32.695312;
        <?php } ?>

        var qso_loc = '<?php echo site_url('dashboard/map');?>';
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
        var q_zoom = 2;

        <?php if ($this->config->item('map_gridsquares') != FALSE) { ?>
              var grid = "Yes";
        <?php } else { ?>
              var grid = "No";
        <?php } ?>
            initmap(grid);

    </script>
<?php } ?>

<?php if ($this->uri->segment(1) == "qso") { ?>

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
				
			  
				
				if($("#sat_name" ).val() != "") {
					//logbook/jsonlookupgrid/io77/SAT/0/0
					$.getJSON('logbook/jsonlookupdxcc/' + convert_case(result.dxcc.entity) + '/SAT/0/0', function(result)
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
					$.getJSON('logbook/jsonlookupdxcc/' + convert_case(result.dxcc.entity) + '/0/' + $("#band").val() +'/' + $("#mode").val(), function(result)
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

              if(result.lotw_member == "active") {
                $('#lotw_info').text("LoTW");
              }

              $('#dxcc_id').val(result.dxcc.adif);
              $('#cqz').val(result.dxcc.cqz);



              // Set Map to Lat/Long
              markers.clearLayers();
              if (typeof result.latlng !== "undefined" && result.latlng !== false) {
                var marker = L.marker([result.latlng[0], result.latlng[1]]);
                mymap.panTo([result.latlng[0], result.latlng[1]], 8);
              } else {
                var marker = L.marker([result.dxcc.lat, result.dxcc.long]);
                mymap.panTo([result.dxcc.lat, result.dxcc.long], 8);
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

            if($('#iota_ref').val() == "") {
                $('#iota_ref').val(result.callsign_iota);
            }

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

        if(qra_input.length >= 4) {
          $.getJSON('logbook/qralatlngjson/' + $(this).val(), function(result)
          {
            // Set Map to Lat/Long
            markers.clearLayers();
            if (typeof result !== "undefined") {
              var marker = L.marker([result[0], result[1]]);
              mymap.setView([result[0], result[1]], 8);
            }
            markers.addLayer(marker).addTo(mymap);
          })
          
          $('#locator_info').load("logbook/searchbearing/" + $(this).val()).fadeIn("slow");
        }
      }
    });

    // Change report based on mode
    $('.mode').change(function(){
      if($(this).val() == 'JT65' || $(this).val() == 'JT65B' || $(this).val() == 'JT6C' || $(this).val() == 'JTMS' || $(this).val() == 'ISCAT' || $(this).val() == 'MSK144' || $(this).val() == 'JTMSK' || $(this).val() == 'QRA64'){
      $('#rst_sent').val('-5');
      $('#rst_recv').val('-5');
      } else if ($(this).val() == 'FSK441' || $(this).val() == 'JT6M') {
        $('#rst_sent').val('26');
      $('#rst_recv').val('26');
      } else if ($(this).val() == 'CW' || $(this).val() == 'RTTY' || $(this).val() == 'PSK31' || $(this).val() == 'PSK63') {
      $('#rst_sent').val('599');
      $('#rst_recv').val('599');
      } else {
        $('#rst_sent').val('59');
      $('#rst_recv').val('59');
      }
    });

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
            if(data.mode == 'JT65' || data.mode == 'JT65B' || data.mode == 'JT6C' || data.mode == 'JTMS' || data.mode == 'ISCAT' || data.mode == 'MSK144' || data.mode == 'JTMSK' || data.mode == 'QRA64'){
              $('#rst_sent').val('-5');
              $('#rst_recv').val('-5');
            } else if (data.mode == 'FSK441' || data.mode == 'JT6M') {
              $('#rst_sent').val('26');
              $('#rst_recv').val('26');
            } else if (data.mode == 'CW') {
              $('#rst_sent').val('599');
              $('#rst_recv').val('599');
            } else {
              $('#rst_sent').val('59');
              $('#rst_recv').val('59');
            }
          }
          $("#sat_name").val(data.satname);  
          $("#sat_mode").val(data.satmode);  
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

  L.marker([lat,long]).addTo(mymap)
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
    zoom: 2
  });
  
  var grid_two = <?php echo $grid_2char; ?>;
  var grid_four = <?php echo $grid_4char; ?>;

  var grid_two_confirmed = <?php echo $grid_2char_confirmed; ?>;
  var grid_four_confirmed = <?php echo $grid_4char_confirmed; ?>;

  var maidenhead = L.maidenhead().addTo(map);

<?php if ($this->uri->segment(1) == "gridsquares" && $this->uri->segment(2) == "band") { ?>

  var bands_available = <?php echo $bands_available; ?>;

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

  </body>
</html>
