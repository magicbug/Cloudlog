<style>
  #wab-map {
    width: 100%;
    height: calc(80vh);
    min-height: 520px;
  }

  .legend {
    padding: 6px 8px;
    font: 14px Arial, Helvetica, sans-serif;
    background: rgba(255, 255, 255, 0.85);
    line-height: 24px;
    color: #555;
    border-radius: 4px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  }

  .legend h4 {
    text-align: center;
    font-size: 16px;
    margin: 2px 12px 8px;
    color: #555;
  }

  .legend span {
    position: relative;
    bottom: 3px;
    color: #555;
  }

  .legend i {
    width: 18px;
    height: 18px;
    float: left;
    margin: 0 8px 0 0;
    opacity: 0.7;
    color: #555;
  }

  .stat-pill {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    border-radius: 6px;
    background: #f8f9fa;
    border: 1px solid #e5e7eb;
    height: 100%;
  }

  .stat-pill .value {
    font-size: 26px;
    font-weight: 700;
    margin: 0;
    color: #0d6efd;
  }

  .stat-pill .label {
    margin: 0;
    color: #6c757d;
  }

  .tab-content {
    margin-top: 12px;
  }
</style>

<div class="container">
  <br>
  <div id="awardInfoButton">
    <script>
      var lang_awards_info_button = "<?php echo lang('awards_info_button'); ?>";
      var lang_award_info_ln1 = "<?php echo lang('awards_wab_description_ln1'); ?>";
      var lang_award_info_ln2 = "<?php echo lang('awards_wab_description_ln2'); ?>";
      var lang_award_info_ln3 = "<?php echo lang('awards_wab_description_ln3'); ?>";
      var lang_award_info_ln4 = "<?php echo lang('awards_wab_description_ln4'); ?>";
    </script>

    <h2 class="mb-3"><?php echo $page_title; ?></h2>
    <button type="button" class="btn btn-sm btn-primary me-2" id="displayAwardInfo"><?php echo lang('awards_info_button'); ?></button>
  </div>

  <?php if ($worked_count == 0 && $confirmed_count == 0) { ?>
  <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
    <i class="fas fa-info-circle me-2"></i>
    <strong><?php echo lang('awards_wab_no_squares_title'); ?></strong>
    <?php echo lang('awards_wab_no_squares_message'); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php } ?>

  <div class="card mb-4">
    <div class="card-body">
      <form class="row gy-3 align-items-end" action="<?php echo site_url('awards/wab'); ?>" method="post">
        <div class="col-sm-4 col-md-3">
          <label class="form-label" for="band"><?php echo lang('awards_wab_filter_band'); ?></label>
          <select id="band" name="band" class="form-select form-select-sm">
            <option value="All" <?php if (($filters['band'] ?? 'All') === 'All') echo 'selected'; ?>><?php echo lang('general_word_all'); ?></option>
            <?php foreach($worked_bands as $band) { ?>
              <option value="<?php echo $band; ?>" <?php if (($filters['band'] ?? 'All') === $band) echo 'selected'; ?>><?php echo $band; ?></option>
            <?php } ?>
          </select>
        </div>

        <div class="col-sm-4 col-md-3">
          <label class="form-label" for="mode"><?php echo lang('awards_wab_filter_mode'); ?></label>
          <select id="mode" name="mode" class="form-select form-select-sm">
            <option value="All" <?php if (($filters['mode'] ?? 'All') === 'All') echo 'selected'; ?>><?php echo lang('general_word_all'); ?></option>
            <?php foreach($modes->result() as $mode) { ?>
              <?php $modeValue = ($mode->submode == null) ? $mode->mode : $mode->submode; ?>
              <option value="<?php echo $modeValue; ?>" <?php if (($filters['mode'] ?? 'All') === $modeValue) echo 'selected'; ?>><?php echo $modeValue; ?></option>
            <?php } ?>
          </select>
        </div>

        <div class="col-sm-4 col-md-3">
          <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" name="confirmed_only" id="confirmed_only" value="1" <?php if (!empty($filters['confirmed_only'])) echo 'checked'; ?>>
            <label class="form-check-label" for="confirmed_only"><?php echo lang('awards_wab_filter_confirmed_only'); ?></label>
          </div>
        </div>

        <div class="col-sm-12 col-md-3 text-md-end">
          <button type="reset" class="btn btn-sm btn-outline-secondary me-1" onclick="window.location='<?php echo site_url('awards/wab'); ?>'\"><?php echo lang('awards_wab_action_reset'); ?></button>
          <button type="submit" class="btn btn-sm btn-primary"><?php echo lang('awards_wab_action_apply'); ?></button>
        </div>
      </form>
    </div>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-md-6">
      <div class="stat-pill">
        <div class="text-primary"><i class="fas fa-map-marked-alt fa-lg"></i></div>
        <div>
          <p class="value mb-0"><?php echo $worked_count ?? 0; ?></p>
          <p class="label mb-0"><?php echo lang('awards_wab_stat_worked'); ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="stat-pill">
        <div class="text-success"><i class="fas fa-check-circle fa-lg"></i></div>
        <div>
          <p class="value mb-0"><?php echo $confirmed_count ?? 0; ?></p>
          <p class="label mb-0"><?php echo lang('awards_wab_stat_confirmed'); ?></p>
        </div>
      </div>
    </div>
  </div>

  <ul class="nav nav-tabs" id="wabTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="map-tab" data-bs-toggle="tab" data-bs-target="#tab-map" type="button" role="tab" aria-controls="tab-map" aria-selected="true"><i class="fas fa-map"></i> <?php echo lang('awards_wab_tab_map'); ?></button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="table-tab" data-bs-toggle="tab" data-bs-target="#tab-table" type="button" role="tab" aria-controls="tab-table" aria-selected="false"><i class="fas fa-list"></i> <?php echo lang('awards_wab_tab_table'); ?></button>
    </li>
  </ul>

  <div class="tab-content" id="wabTabsContent">
    <div class="tab-pane fade show active" id="tab-map" role="tabpanel" aria-labelledby="map-tab">
      <div id="wab-map"></div>
    </div>
    <div class="tab-pane fade" id="tab-table" role="tabpanel" aria-labelledby="table-tab">
      <div class="mt-3">
        <h5 class="mb-3"><?php echo lang('awards_wab_table_heading'); ?> <small class="text-muted">(<?php echo $filter_summary; ?>)</small></h5>
        <?php if ($wab_qsos && $wab_qsos->num_rows() > 0) { ?>
          <?php $this->load->view('view_log/partial/log_ajax', array('results' => $wab_qsos, 'filter' => $filter_summary)); ?>
        <?php } else { ?>
          <div class="alert alert-info mb-0"><?php echo lang('awards_wab_no_qsos'); ?></div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>

<script>
  window.wabConfig = {
    workedSquares: <?php echo json_encode(array_values($worked_squares ?? [])); ?>,
    confirmedSquares: <?php echo json_encode(array_values($confirmed_squares ?? [])); ?>,
    confirmedOnly: <?php echo !empty($filters['confirmed_only']) ? 'true' : 'false'; ?>,
    geojsonUrl: <?php echo json_encode(base_url('assets/json/WABSquares.geojson')); ?>,
    tileUrl: <?php echo json_encode($this->optionslib->get_option('option_map_tile_server')); ?>,
    tileAttribution: <?php echo json_encode($this->optionslib->get_option('option_map_tile_server_copyright')); ?>,
    detailUrl: <?php echo json_encode(site_url('awards/wab_details_ajax')); ?>,
    mapContainer: 'wab-map',
    filters: <?php echo json_encode($filters ?? []); ?>,
  };
</script>