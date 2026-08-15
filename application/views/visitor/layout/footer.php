<!-- General JS Files used across Cloudlog -->
<script src="<?php echo base_url(); ?>assets/js/jquery-3.3.1.min.js"></script>
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
  var base_url = "<?php echo base_url(); ?>";
  var site_url = "<?php echo site_url(); ?>";
  var icon_dot_url = "<?php echo base_url();?>assets/images/dot.png";
  var visitor = true;
  function getDataTablesLanguageUrl() {
    var language = (typeof lang_datatables_language !== 'undefined') ? lang_datatables_language : 'english';
    return base_url + "/assets/json/datatables_languages/" + language + ".json";
  }
</script>

<?php
	$slug = isset($slug) ? $slug : '';
	$public_search_enabled = !empty($public_search_enabled);
	$seg1 = $this->uri->segment(1);
	$seg2 = $this->uri->segment(2);
	$reserved = array('search', 'satellites', 'gridsquares');
	$is_visitor_home = ($seg1 == 'visitor' && !in_array($seg2, $reserved));
	$is_gridmap = ($seg1 == 'visitor' && in_array($seg2, array('satellites', 'gridsquares')));
	$is_oqrs = ($seg1 == 'oqrs');
	$is_search = ($seg1 == 'visitor' && $seg2 == 'search');
?>

    <script type="text/javascript" src="<?php echo base_url();?>assets/js/leaflet/L.Maidenhead.js"></script>
    <script id="leafembed" type="text/javascript" src="<?php echo base_url();?>assets/js/leaflet/leafembed.js" tileUrl="<?php echo $this->optionslib->get_option('map_tile_server');?>"></script>
    <script type="text/javascript">
      $(function () {
        $('[data-bs-toggle="tooltip"]').tooltip()
      });

        <?php if(isset($qra) && $qra == "set") { ?>
        var q_lat = <?php echo $qra_lat; ?>;
        var q_lng = <?php echo $qra_lng; ?>;
        <?php } else { ?>
        var q_lat = 40.313043;
        var q_lng = -32.695312;
        <?php } ?>

        <?php if ($is_visitor_home && $slug !== '') {
          $offset = $this->uri->segment(4);
        ?>
        var qso_loc = '<?php echo site_url('visitor/map/'.$slug.'/'.$offset);?>';
        <?php } ?>
        var q_zoom = 3;

      $(document).ready(function(){
            <?php if ($this->config->item('map_gridsquares') != FALSE) { ?>
              var grid = "Yes";
            <?php } else { ?>
              var grid = "No";
            <?php } ?>
            <?php if ($is_visitor_home) { ?>
            initmap(grid);
            <?php } ?>

      });

      </script>

<?php if ($is_gridmap) { ?>

<script type="text/javascript" src="<?php echo base_url();?>assets/js/leaflet/geocoding.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/leaflet/L.MaidenheadColouredGridMap.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/js/sections/gridmap.js?"></script>

<script>

  var grid_two = <?php echo $grid_2char; ?>;
  var grid_four = <?php echo $grid_4char; ?>;
  var grid_six = <?php echo $grid_6char; ?>;

  var grid_two_confirmed = <?php echo $grid_2char_confirmed; ?>;
  var grid_four_confirmed = <?php echo $grid_4char_confirmed; ?>;
  var grid_six_confirmed = <?php echo $grid_6char_confirmed; ?>;

  var jslayer = '<?php echo $this->optionslib->get_option('option_map_tile_server');?>';
  var jsattribution = '<?php echo $this->optionslib->get_option('option_map_tile_server_copyright');?>';
  var gridsquares_gridsquares = "<?php echo lang('gridsquares_gridsquares'); ?>";
  var gridsquares_gridsquares_confirmed = "<?php echo lang('gridsquares_gridsquares_confirmed'); ?>";
  var gridsquares_gridsquares_not_confirmed = "<?php echo lang('gridsquares_gridsquares_not_confirmed'); ?>";
  var gridsquares_gridsquares_total_worked = "<?php echo lang('gridsquares_gridsquares_total_worked'); ?>";
  
  var visitor = true;
  var type = "worked";

  $(document).ready(function() {
    plot(visitor, grid_two, grid_four, grid_six, grid_two_confirmed, grid_four_confirmed, grid_six_confirmed);
  });
    </script>
<?php } ?>

    <?php if ($public_search_enabled && $is_search) { ?>
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
            if ($('#publicsearchtable').length) {
            $('#publicsearchtable').DataTable({
                "pageLength": 25,
                responsive: false,
                ordering: true,
                "scrollY":        "500px",
                "scrollCollapse": true,
                "paging":         true,
                "scrollX": true,
                "language": {
                    url: getDataTablesLanguageUrl(),
                },
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
            }
            if (typeof isDarkModeTheme === 'function' && isDarkModeTheme()) {
               $('[class*="buttons"]').css("color", "white");
            }
        </script>
    <?php } ?>
        <script type="text/javascript">
            $(function () {
                $(document).on('shown.bs.tooltip', function (e) {
                    setTimeout(function () {
                        $(e.target).tooltip('hide');
                    }, 3000);
                });
            });
            function validateForm() {
                var form = document.forms["searchForm"];
                if (!form) { return true; }
                let x = form["callsign"].value;
                if (x.trim() == "") {
                    $('#searchcall').tooltip('show')
                    return false;
                }
            }
        </script>
    <?php if ($is_oqrs) { ?>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/datatables.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/sections/oqrs.js"></script>
    <?php } ?>
    <script>
      <?php
      echo "var lang_datatables_language = '" . lang("datatables_language") . "';"
      ?>
    </script>
    <footer class="visitor-site-footer">
      <div class="container">
        <p class="mb-0 text-muted"><?php echo lang('visitor_powered_by'); ?></p>
      </div>
    </footer>
  </body>
</html>
