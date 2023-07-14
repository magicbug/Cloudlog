<!-- General JS Files used across Cloudlog -->
<script src="<?php echo base_url(); ?>assets/js/jquery-3.3.1.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/popper.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.fancybox.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.bundle.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/leaflet/leaflet.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/leaflet/L.Maidenhead.qrb.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/leaflet/leaflet.geodesic.js"></script>
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

    <script type="text/javascript" src="<?php echo base_url();?>assets/js/leaflet/L.Maidenhead.js"></script>
    <script id="leafembed" type="text/javascript" src="<?php echo base_url();?>assets/js/leaflet/leafembed.js" tileUrl="<?php echo $this->optionslib->get_option('map_tile_server');?>"></script>
    <script type="text/javascript">
      $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      });

        <?php if(isset($qra) && $qra == "set") { ?>
        var q_lat = <?php echo $qra_lat; ?>;
        var q_lng = <?php echo $qra_lng; ?>;
        <?php } else { ?>
        var q_lat = 40.313043;
        var q_lng = -32.695312;
        <?php } ?>

        <?php if(isset($slug)) { ?>
        var qso_loc = '<?php echo site_url('visitor/map/'.$slug);?>';
        <?php } ?>
        var q_zoom = 3;

      $(document).ready(function(){
            <?php if ($this->config->item('map_gridsquares') != FALSE) { ?>
              var grid = "Yes";
            <?php } else { ?>
              var grid = "No";
            <?php } ?>
            <?php if ($this->uri->segment(2) != "search" && $this->uri->segment(2) != "satellites") { ?>
            initmap(grid);
            <?php } ?>

      });

      </script>

<?php if ($this->uri->segment(2) == "satellites") { ?>

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
     span.innerText = span.textContent = '('+grid_four_confirmed_count+' grid squares) ';
  }
  if ((grid_four_count-grid_four_confirmed_count) > 0) {
     var span = document.getElementById('worked_grids');
     span.innerText = span.textContent = '('+(grid_four_count-grid_four_confirmed_count)+' grid squares) ';
  }
  var span = document.getElementById('sum_grids');
  span.innerText = span.textContent = ' Total Count: '+grid_four_count+' grid squares';

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
<?php } ?>
    </script>
    <?php if ($this->CI->public_search_enabled($slug) || $this->session->userdata('user_type') >= 2) { ?>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/datatables.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/datetime-moment.js"></script>
    <script>
            <?php switch($this->config->item('qso_date_format')) {
               case 'd/m/y': $usethisformat = 'D/MM/YY';break;
               case 'd/m/Y': $usethisformat = 'D/MM/YYYY';break;
               case 'm/d/y': $usethisformat = 'MM/D/YY';break;
               case 'm/d/Y': $usethisformat = 'MM/D/YYYY';break;
               case 'd.m.Y': $usethisformat = 'D.MM.YYYY';break;
               case 'y/m/d': $usethisformat = 'YY/MM/D';break;
               case 'Y-m-d': $usethisformat = 'YYYY-MM-D';break;
               case 'M d, Y': $usethisformat = 'MMM D, YYYY';break;
               case 'M d, y': $usethisformat = 'MMM D, YY';break;
               default: $usethisformat = 'YYYY-MM-D';
            } ?>

            $.fn.dataTable.moment('<?php echo $usethisformat ?>');
            $.fn.dataTable.ext.buttons.clear = {
                className: 'buttons-clear',
                action: function ( e, dt, node, config ) {
                   dt.search('').draw();
                }
            };
            $('#publicsearchtable').DataTable({
                "pageLength": 25,
                responsive: false,
                ordering: true,
                "scrollY":        "500px",
                "scrollCollapse": true,
                "paging":         true,
                "scrollX": true,
                "order": [ 0, 'desc' ],
                dom: 'Bfrtip',
                buttons: [
                   {
                      extend: 'csv',
                      text: 'CSV'
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
        <script type="text/javascript">
            $(function () {
                $(document).on('shown.bs.tooltip', function (e) {
                    setTimeout(function () {
                        $(e.target).tooltip('hide');
                    }, 3000);
                });
            });
            function validateForm() {
                let x = document.forms["searchForm"]["callsign"].value;
                if (x.trim() == "") {
                    $('#searchcall').tooltip('show')
                    return false;
                }
            }
        </script>
    <?php } ?>

  </body>
</html>
