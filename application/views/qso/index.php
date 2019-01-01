<!-- JS -->

  <script type="text/javascript" src="<?php echo base_url() ;?>/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
  <script type="text/javascript" src="<?php echo base_url() ;?>/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
  <script type="text/javascript" src="<?php echo base_url() ;?>/js/jquery.jclock.js"></script>
  <script type="text/javascript" src="<?php echo base_url() ;?>/js/radiohelpers.js"></script>

  <link rel="stylesheet" type="text/css" href="<?php echo base_url() ;?>/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

  <script type="text/javascript">

    var manual = <?php echo $_GET['manual']; ?>;

    $(document).ready(function() {
      $(".qsobox").fancybox({
        'autoDimensions'  : false,
        'width'           : 700,
        'height'          : 300,
        'transitionIn'    : 'fade',
        'transitionOut'   : 'fade',
        'type'            : 'iframe'
      });

      if ( ! manual ) {
        $(function($) {
          var options = {
            utc: true,
            format: '%H:%M'
          }
          $('.input_time').jclock(options);
        });
      }
    });

  </script>

<div id="container">



<?php if($notice) { ?>
<div class="alert-message info">
        <?php echo $notice; ?>
</div>
<?php } ?>

<?php if(validation_errors()) { ?>
<div class="alert-message error">
        <?php echo validation_errors(); ?>
</div>
<?php } ?>

  <div class="row show-grid">
    <div class="span6">
   
    <h2>Add QSO <?php echo ($_GET['manual'] == 1 ? "(post entry)" : "") ?></h2>
    
    <form id="qso_input" method="post" action="<?php echo site_url('qso') . "?manual=" . $_GET['manual']; ?>" name="qsos">
      <input type="hidden" id="dxcc_id" name="dxcc_id" value=""/>
      <input type="hidden" id="cqz" name="cqz" value=""/>

    <table style="margin-bottom: 0px;">

      <tr>
        <td class="title">Date</td>
        <td>
          <input class="input_date" type="text" name="start_date" value="<?php echo date('d-m-Y'); ?>" size="10" <?php echo ($_GET['manual'] == 0 ? "disabled" : "");  ?> /> 
          <input class="input_time" type="text" id="start_date" name="start_time" value="<?php echo date('H:i'); ?>" size="7" <?php echo ($_GET['manual'] == 0 ? "disabled" : "");  ?> />

          <?php if ( $_GET['manual'] == 0 ) { ?>
            <input class="input_time" type="hidden" id="start_time"  name="start_time"value="<?php echo date('H:i'); ?>" />
            <input class="input_date" type="hidden" id="start_date" name="start_date" value="<?php echo date('d-m-Y'); ?>" />
          <?php } ?>

        </td>
      </tr>

      <tr>
        <td class="title">Callsign</td>
        <td><input size="10" id="callsign" type="text" name="callsign" value="" /></td>
      </tr>  

      <tr>
        <td class="title">Mode</td>
        <td><select name="mode" class="mode">
<?php
    $this->load->library('frequency');
    foreach(Frequency::modes as $mode){
        printf("<option value=\"%s\" %s>%s</option>",
            $mode,
            $this->session->userdata('mode')==$mode?"selected=\"selected\"":"",
            $mode);
        }
?>
      </select>

      <span class="title">Band</span>
        <select name="band" class="band">
          <option value="160m" <?php if($this->session->userdata('band') == "160m") { echo "selected=\"selected\""; } ?>>160m</option>
          <option value="80m" <?php if($this->session->userdata('band') == "80m") { echo "selected=\"selected\""; } ?>>80m</option>
          <option value="60m" <?php if($this->session->userdata('band') == "60m") { echo "selected=\"selected\""; } ?>>60m</option>
          <option value="40m" <?php if($this->session->userdata('band') == "40m") { echo "selected=\"selected\""; } ?>>40m</option>
          <option value="30m" <?php if($this->session->userdata('band') == "30m") { echo "selected=\"selected\""; } ?>>30m</option>
          <option value="20m" <?php if($this->session->userdata('band') == "20m") { echo "selected=\"selected\""; } ?>>20m</option>
          <option value="17m" <?php if($this->session->userdata('band') == "17m") { echo "selected=\"selected\""; } ?>>17m</option>
          <option value="15m" <?php if($this->session->userdata('band') == "15m") { echo "selected=\"selected\""; } ?>>15m</option>
          <option value="12m" <?php if($this->session->userdata('band') == "12m") { echo "selected=\"selected\""; } ?>>12m</option>
          <option value="10m" <?php if($this->session->userdata('band') == "10m") { echo "selected=\"selected\""; } ?>>10m</option>
          <option value="6m" <?php if($this->session->userdata('band') == "6m") { echo "selected=\"selected\""; } ?>>6m</option>
          <option value="4m" <?php if($this->session->userdata('band') == "4m") { echo "selected=\"selected\""; } ?>>4m</option>
          <option value="2m" <?php if($this->session->userdata('band') == "2m") { echo "selected=\"selected\""; } ?>>2m</option>
          <option value="70cm" <?php if($this->session->userdata('band') == "70cm") { echo "selected=\"selected\""; } ?>>70cm</option>
          <option value="23cm" <?php if($this->session->userdata('band') == "23cm") { echo "selected=\"selected\""; } ?>>23cm</option>
          <option value="13cm" <?php if($this->session->userdata('band') == "14cm") { echo "selected=\"selected\""; } ?>>13cm</option>
          <option value="9cm" <?php if($this->session->userdata('band') == "9cm") { echo "selected=\"selected\""; } ?>>9cm</option>
          <option value="3cm" <?php if($this->session->userdata('band') == "3cm") { echo "selected=\"selected\""; } ?>>3cm</option>
        </select></td>
      </tr>

      <tr>
        <td class="title">RST (S)</td>
        <td><input id="rst_sent" class="rst" name="rst_sent" type="text" size="3" value="59"> <span class="title">RST (R)</span> <input id="rst_recv" class="rst" name="rst_recv" type="text"  size="3"  value="59"></td>
      </tr>

      <tr>
        <td class="title">Name</td>
        <td><input id="name" type="text" name="name" value="" /></td>
      </tr>  

      <tr>
        <td class="title">Location</td>
        <td><input id="qth" type="text" name="qth" value="" /></td>
      </tr>

      <tr>
        <td class="title">Locator</td>
        <td><input id="locator" type="text" name="locator" value="" size="7" /></td>
      </tr>

      <tr>
        <td class="title">Comment</td>
        <td><input id="comment" type="text" name="comment" value="" /></td>
      </tr>
    </table>


    <div class="info">
      <input style="border: none; -webkit-box-shadow: none;" size="20" id="country" type="text" name="country" value="" /> <span id="locator_info"></span>
    </div>

    <ul class="tabs">
      <li class="active"><a href="#home">Home</a></li>
      <li><a href="#station">Station</a></li>
      <li><a href="#satellite">Satellite</a></li>
      <li><a href="#qsl">QSL</a></li>
    </ul>
     
    <div class="pill-content">
      <div class="active" id="home">
        <table>
          <tr>
            <td>Propagation Mode</td>
            <td>
              <select name="prop_mode">
                <option value="" selected="selected"></option>
                <option value="AUR">Aurora</option>
                <option value="AUE">Aurora-E</option>
                <option value="BS">Back scatter</option>
                <option value="ECH">EchoLink</option>
                <option value="EME">Earth-Moon-Earth</option>
                <option value="ES">Sporadic E</option>
                <option value="FAI">Field Aligned Irregularities</option>
                <option value="F2">F2 Reflection</option>
                <option value="INTERNET">Internet-assisted</option>
                <option value="ION">Ionoscatter</option>
                <option value="IRL">IRLP</option>
                <option value="MS">Meteor scatter</option>
                <option value="RPT">Terrestrial or atmospheric repeater or transponder</option>
                <option value="RS">Rain scatter</option>
                <option value="SAT">Satellite</option>
                <option value="TEP">Trans-equatorial</option>
                <option value="TR">Tropospheric ducting</option>
              </select>
            </td>
          </tr>
          <tr>
            <td>IOTA</td>
            <td><input id="iota_ref" type="text" name="iota_ref" value="" /> e.g: EU-005</td>
          </tr>
        </table>
      </div>
      <div id="station">
        <table>
          <tr>
            <td>Station Profile</td>
            <td>
              <select class="station_profile" name="station_profile">
              <option value="0" selected="selected">None</option>
              <?php foreach ($stations->result() as $stationrow) { ?>
              <option value="<?php echo $stationrow->station_id; ?>" <?php if($this->session->userdata('station_profile_id') == $stationrow->station_id) { echo "selected=\"selected\""; } ?>><?php echo $stationrow->station_profile_name; ?></option>
              <?php } ?>
              </select>
            </td>
          </tr>
          <tr>
            <td>Radio</td>
            <td>
              <select class="radios" name="radio">
              <option value="0" selected="selected">None</option>
              <?php foreach ($radios->result() as $row) { ?>
              <option value="<?php echo $row->id; ?>" <?php if($this->session->userdata('radio') == $row->id) { echo "selected=\"selected\""; } ?>><?php echo $row->radio; ?></option>
              <?php } ?>
              </select>
            </td>
          </tr>
          <tr>
            <td>Frequency</td>
            <td><input type="text" id="frequency" name="freq_display" value="" /></td>
          </tr>
        </table>
      </div>
      <div id="satellite">
        <table>
          <tr>
            <td>Sat Name</td>
            <td><input id="sat_name" type="text" name="sat_name" class="sat_name" value="<?php echo $this->session->userdata('sat_name'); ?>" /></td>
          </tr>
  
          <tr>
            <td>Sat Mode</td>
            <td><input id="sat_mode" type="text" name="sat_mode" class="sat_mode" value="<?php echo $this->session->userdata('sat_mode'); ?>" /></td>
          </tr>
        </table>
      </div>
      <div id="qsl">
        <table>
          <tr>
            <td>Sent</td>
            <td><select name="qsl_sent">
              <option value="N" selected="selected">No</option>
              <option value="Y">Yes</option>
              <option value="R">Requested</option>
            </select></td>
          <tr>
            <td>Method</td>
            <td><select name="qsl_sent_method">
              <option value="" selected="selected">Method</option>
              <option value="D">Direct</option>
              <option value="B">Bureau</option>
            </select></td>
          </tr>
          
          <tr>
            <td>Via</td>
            <td><input type="text" name="qsl_via" value="" /></td>
          </tr>
        </table>
      </div>
    </div>

    <div class="actions"><input class="btn primary" type="submit" value="Add QSO" /> <input type="reset" value="Reset" class="btn" /></div>
    

    </form>
    </div>
    <div class="span9 offset1">

     <div id="partial_view">
       <h2>Last 16 QSOs</h2>

       <table class="zebra-striped" width="100%">
        <tr class="log_title titles">
          <td>Date/Time</td>
          <td>Call</td>
          <td>Mode</td>
          <td>Sent</td>
          <td>Recv</td>
          <td>Band</td>
        </tr>

        <?php $i = 0; 
       foreach ($query->result() as $row) { ?>
          <?php  echo '<tr class="tr'.($i & 1).'">'; ?>
          <td><?php echo $row->COL_TIME_ON; ?></td>
          <td><a class="qsobox" href="<?php echo site_url('logbook/view')."/".$row->COL_PRIMARY_KEY; ?>"><?php echo strtoupper($row->COL_CALL); ?></a></td>
          <td><?php echo $row->COL_MODE; ?></td>
          <td><?php echo $row->COL_RST_SENT; ?></td>
          <td><?php echo $row->COL_RST_RCVD; ?></td>
          <?php if($row->COL_SAT_NAME != null) { ?>
          <td><?php echo $row->COL_SAT_NAME; ?></td>
          <?php } else { ?>
          <td><?php echo $row->COL_BAND; ?></td>
          <?php } ?>
        </tr>
        <?php $i++; } ?>

      </table></div>

    </div>
  </div>

</div>



<script type="text/javascript">

  function delay(callback, ms) {
    var timer = 0;
    return function() {
      var context = this, args = arguments;
      clearTimeout(timer);
      timer = setTimeout(function () {
        callback.apply(context, args);
      }, ms || 0);
    };
  }

  i=0;
  typeDelay=1000;

  $(document).ready(function(){

  // Set the focus input to the callsign field
  $("#callsign").focus();

  /* Javascript for controlling rig frequency. */
<?php if ( $_GET['manual'] == 0 ) { ?>
  var updateFromCAT = function() {
    if($('select.radios option:selected').val() != '0') {
      // Get frequency
      $.get('radio/frequency/' + $('select.radios option:selected').val(), function(result) {

        if(result == "0") {
        } else {
          $('#frequency').val(result);
          $(".band").val(frequencyToBand(result));
        }
      });
      
      // Get Mode
      $.get('radio/mode/' + $('select.radios option:selected').val(), function(result) {
        if (result == "LSB" || result == "USB" || result == "SSB") {
          $(".mode").val('SSB');
        } else {
          $(".mode").val(result);  
        }
      });

        // Get SAT_Name
      $.get('radio/satname/' + $('select.radios option:selected').val(), function(result) {
        $(".sat_name").val(result);  
      });

        // Get SAT_Name
      $.get('radio/satmode/' + $('select.radios option:selected').val(), function(result) {
        $(".sat_mode").val(result);  
      });

    }
  };

  // Update frequency every second
  setInterval(updateFromCAT, 1000);

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
    
    $.get('qso/band_to_freq/' + $('.band').val() + '/' + $('.mode').val(), function(result) {
            $('#frequency').val(result);
    });  
  
    /* Calculate Frequency */
      /* on band change */
      $('.band').change(function() {
        $.get('qso/band_to_freq/' + $(this).val() + '/' + $('.mode').val(), function(result) {
            $('#frequency').val(result);
          });  
      });
      
      /* on mode change */
      $('.mode').change(function() {
        $.get('qso/band_to_freq/' + $('.band').val() + '/' + $('.mode').val(), function(result) {
            $('#frequency').val(result);
          });  
      });
  
    /* On Key up Calculate Bearing and Distance */
    $("#locator").keyup(function(){
      if ($(this).val()) {
        $('#locator_info').load("logbook/bearing/" + $(this).val()).fadeIn("slow");
      }
    });
  
    /* On Callsign Change */
    $("#callsign").keyup(delay(function(){
      if ($(this).val()) {
        /* Find and populate DXCC */
        $.get('logbook/find_dxcc/' + $(this).val(), function(result) {
          //$('#country').val(result);
          obj = JSON.parse(result);
          $('#country').val(convert_case(obj.Name));
          $('#dxcc_id').val(obj.DXCC);
          $('#cqz').val(obj.CQZ);

        });
  
        /* Find Locator if the field is empty */
        if($('#locator').val() == "") {
          $.get('logbook/callsign_qra/' + $(this).val(), function(result) {
            $('#locator').val(result);
            $('#locator_info').load("logbook/bearing/" + result).fadeIn("slow");
          });

        }
  
        /* Find Operators Name */
        if($('#name').val() == "") {
          $.get('logbook/callsign_name/' + $(this).val(), function(result) {
            $('#name').val(result);
          });
        }

        if($('#qth').val() == "") {
          $.get('logbook/callsign_qth/' + $(this).val(), function(result) {
            $('#qth').val(result);
          });
        }
    
        if($('#qth').val() == "") {
          $.get('logbook/callsign_iota/' + $(this).val(), function(result) {
            $('#iota_ref').val(result);
          });
        }

        /* Find Callsign Matches */
        $('#partial_view').load("logbook/partial/" + $(this).val()).fadeIn("slow");
  
      } else {
        /* Reset fields ... */
        $('#country').val("");
        $('#dxcc_id').val("");
        $('#cqz').val("");
        $('#name').val("");
        $('#qth').val("");
        $('#locator').val("");
        $('#iota_ref').val("");
        $('#partial_view').load("logbook/partial/");
      }
    }, typeDelay));
    
    
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
  });


  function convert_case(str) {
    var lower = str.toLowerCase();
    return lower.replace(/(^| )(\w)/g, function(x) {
      return x.toUpperCase();
    });
  }


</script>