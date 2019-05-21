    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="<?php echo base_url(); ?>assets/js/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/jquery.jclock.js"></script>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/leaflet/leaflet.js"></script>
    <script type="text/javascript" src="<?php echo base_url() ;?>assets/js/radiohelpers.js"></script>

<script>
$(document).ready(function() {
	$('#create_station_profile #country').val($("#dxcc_select option:selected").text());
	$("#create_station_profile #dxcc_select" ).change(function() {
	  $('#country').val($("#dxcc_select option:selected").text());
	});
});
</script>

<script>
$(document).ready(function() {
    $('.fancybox').fancybox({
      toolbar  : false,
      smallBtn : true,
      iframe : {
        preload : false
      }
    });
});
</script>

<?php if ($this->uri->segment(1) == "" || $this->uri->segment(1) == "dashboard" ) { ?>
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
            initmap();
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
$(document).ready(function(){

    $('#partial_view').load("logbook/search_result/<?php echo $this->input->post('callsign'); ?>", function() {
    });

  $("#callsign").keyup(function(){
    if ($(this).val()) {

        $('#partial_view').load("logbook/search_result/" + $(this).val(), function() {
        });
    }

  });
});
</script>
<?php } ?>

<?php if ($this->uri->segment(1) == "logbook") { ?>
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

        initmap();

    </script>
<?php } ?>

<?php if ($this->uri->segment(1) == "qso") { ?>
  <script type="text/javascript">

    var manual = <?php echo $_GET['manual']; ?>;

    console.log(manual);

    $(document).ready(function() {

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
            format: '%H:%M'
          }
          $('#start_time').jclock(options);
        });
      }
    });

  jQuery(function($) {
  var input = $('#callsign');
  input.on('keydown', function() {
    var key = event.keyCode || event.charCode;

    if( key == 8 || key == 46 ) {
      console.log("trigger");
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
    }
  });
});


    $("#callsign").focusout(function() {
        if ($(this).val()) {
            /* Find and populate DXCC */
            $.getJSON('logbook/json/' + $(this).val(), function(result)
            {
              //$('#country').val(result);
              $('#country').val(convert_case(result.dxcc.Name));
              $('#callsign_info').text(convert_case(result.dxcc.Name));
              $('#dxcc_id').val(result.dxcc.DXCC);
              $('#cqz').val(result.dxcc.CQZ);

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
            if($('#name').val() == "") {
                $('#name').val(result.callsign_name);
            }

            if($('#qth').val() == "") {
                $('#qth').val(result.callsign_qth);
            }

            if($('#qth').val() == "") {
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
        $('#locator_info').load("logbook/searchbearing/" + $(this).val()).fadeIn("slow");
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
      } else if ($(this).val() == 'CW') {
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
          if (data.mode == "LSB" || data.mode == "USB" || data.mode == "SSB") {
            $(".mode").val('SSB');
          } else {
            $(".mode").val(data.mode);  
          }

          $("#sat_name").val(data.satname);  
          $("#sat_mode").val(data.satmode);  
      });
    }
  };

  // Update frequency every second
  setInterval(updateFromCAT, 2000);

  // If a radios selected from drop down select radio update.
  $('.radios').change(updateFromCAT);

  // If radio isn't SatPC32 clear sat_name and sat_mode
  $( ".radios" ).change(function() {
      if ($("#yourdropdownid option:selected").text() != "SatPC32") {
        $(".sat_name").val("");  
        $(".sat_mode").val("");  
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

  </body>
</html>