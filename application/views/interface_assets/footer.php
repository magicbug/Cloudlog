    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="<?php echo base_url(); ?>assets/js/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="<?php echo base_url(); ?>js/leaflet/leaflet.js"></script>

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
    <script type="text/javascript" src="<?php echo base_url();?>js/leaflet/leafembed.js"></script>
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
        }, 1000);
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
    <script type="text/javascript" src="<?php echo base_url();?>js/leaflet/leafembed.js"></script>
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

  </body>
</html>