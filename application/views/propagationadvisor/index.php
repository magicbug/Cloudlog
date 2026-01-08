<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<div class="container propagation-advisor mt-4 mb-4">
  <h2 class="mb-2"><?php echo lang('propagation_title'); ?></h2>
  <p class="text-muted mb-4"><?php echo lang('propagation_description'); ?></p>

  <?php if ($this->session->flashdata('notice')) { ?>
    <div class="alert alert-info" role="alert"><?php echo $this->session->flashdata('notice'); ?></div>
  <?php } ?>

  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <form id="propagation-filters" class="row gy-3 gx-3 align-items-end" novalidate>
        <div class="col-12 col-md-5">
          <label class="form-label" for="propagation-dxcc"><?php echo lang('propagation_select_dxcc'); ?> *</label>
          <select class="form-select" id="propagation-dxcc" name="dxcc" required>
            <option value=""><?php echo lang('propagation_required_filters'); ?></option>
            <?php foreach ($dxcc_list as $dxcc) { ?>
              <option value="<?php echo $dxcc->adif; ?>"><?php echo $dxcc->prefix; ?> - <?php echo $dxcc->name; ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="col-6 col-md-3">
          <label class="form-label" for="propagation-band"><?php echo lang('propagation_select_band'); ?></label>
          <select class="form-select" id="propagation-band" name="band">
            <option value=""><?php echo lang('general_word_all'); ?></option>
            <?php foreach ($bands as $band) { ?>
              <option value="<?php echo $band; ?>"><?php echo strtoupper($band); ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="col-6 col-md-3">
          <label class="form-label" for="propagation-mode"><?php echo lang('propagation_select_mode'); ?></label>
          <select class="form-select" id="propagation-mode" name="mode">
            <option value=""><?php echo lang('general_word_all'); ?></option>
            <?php foreach ($modes as $mode) { ?>
              <option value="<?php echo $mode->COL_MODE; ?>"><?php echo $mode->COL_MODE; ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="col-12 col-md-1 d-grid">
          <button type="submit" class="btn btn-primary w-100" id="propagation-apply"><?php echo lang('general_word_ok'); ?></button>
        </div>
      </form>
    </div>
  </div>

  <div id="propagation-status" class="alert alert-info" role="alert"><?php echo lang('propagation_required_filters'); ?></div>

  <div class="card shadow-sm mb-3" id="propagation-trend-card" style="display:none;">
    <div class="card-body d-flex flex-column flex-lg-row align-items-start align-items-lg-center gap-3">
      <div>
        <div class="text-muted small mb-1"><?php echo lang('propagation_activity_last_30'); ?></div>
        <div id="propagation-trend-sparkline" class="trend-sparkline" aria-label="<?php echo lang('propagation_activity_last_30'); ?>"></div>
      </div>
      <div class="d-flex flex-wrap gap-2 small text-muted" id="propagation-trend-stats">
        <span class="trend-pill" id="trend-7d"></span>
        <span class="trend-pill" id="trend-30d"></span>
        <span class="trend-pill" id="trend-90d"></span>
      </div>
    </div>
  </div>

  <div class="row g-3 mb-3" id="propagation-summary" style="display:none;">
    <div class="col-6 col-md-3">
      <div class="card summary-card h-100">
        <div class="card-body">
          <p class="text-muted small mb-1"><?php echo lang('propagation_best_window'); ?></p>
          <h5 class="mb-0" id="summary-best-window">-</h5>
          <span class="text-muted small" id="summary-best-window-count"></span>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card summary-card h-100">
        <div class="card-body">
          <p class="text-muted small mb-1"><?php echo lang('propagation_best_band'); ?></p>
          <h5 class="mb-0" id="summary-best-band">-</h5>
          <span class="text-muted small" id="summary-best-band-count"></span>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card summary-card h-100">
        <div class="card-body">
          <p class="text-muted small mb-1"><?php echo lang('propagation_best_mode'); ?></p>
          <h5 class="mb-0" id="summary-best-mode">-</h5>
          <span class="text-muted small" id="summary-best-mode-count"></span>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card summary-card h-100">
        <div class="card-body">
          <p class="text-muted small mb-1"><?php echo lang('propagation_last_worked'); ?></p>
          <h5 class="mb-0" id="summary-last-worked">-</h5>
          <span class="text-muted small" id="summary-total-qsos"></span>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow-sm mb-3" id="propagation-heatmap-card" style="display:none;">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span><?php echo lang('propagation_heatmap_title'); ?></span>
      <a class="btn btn-outline-secondary btn-sm" id="propagation-csv" href="#"><?php echo lang('propagation_download_csv'); ?></a>
    </div>
    <div class="card-body" style="min-height: 200px;">
      <div class="heatmap-legend mb-3">
        <span class="text-muted small me-2"><?php echo lang('propagation_relative_intensity'); ?></span>
        <span class="legend-item"><span class="legend-swatch none"></span><?php echo lang('propagation_intensity_none'); ?></span>
        <span class="legend-item"><span class="legend-swatch low"></span><?php echo lang('propagation_intensity_low'); ?></span>
        <span class="legend-item"><span class="legend-swatch mid"></span><?php echo lang('propagation_intensity_medium'); ?></span>
        <span class="legend-item"><span class="legend-swatch high"></span><?php echo lang('propagation_intensity_high'); ?></span>
        <span class="legend-item"><span class="legend-swatch max"></span><?php echo lang('propagation_intensity_very_high'); ?></span>
        <span id="strongest-band-chip" class="strongest-band d-none"></span>
      </div>
      <div class="heatmap-grid-wrapper">
        <div id="propagationHeatmapFallback" class="heatmap-grid" style="display:none;"></div>
      </div>
    </div>
  </div>
</div>

<script>
  // Language strings for propagationadvisor.js
  var propagation_lang = {
    required_filters: "<?php echo lang('propagation_required_filters'); ?>",
    no_data: "<?php echo lang('propagation_no_data'); ?>",
    data_url: "<?php echo site_url('propagationadvisor/data'); ?>",
    export_url: "<?php echo site_url('propagationadvisor/export'); ?>",
    intensity_none: "<?php echo lang('propagation_intensity_none'); ?>",
    intensity_low: "<?php echo lang('propagation_intensity_low'); ?>",
    intensity_medium: "<?php echo lang('propagation_intensity_medium'); ?>",
    intensity_high: "<?php echo lang('propagation_intensity_high'); ?>",
    intensity_very_high: "<?php echo lang('propagation_intensity_very_high'); ?>",
    strongest_band: "<?php echo lang('propagation_strongest_band_label'); ?>",
    activity_last_30: "<?php echo lang('propagation_activity_last_30'); ?>",
    trend_7d: "<?php echo lang('propagation_trend_7d'); ?>",
    trend_30d: "<?php echo lang('propagation_trend_30d'); ?>",
    trend_90d: "<?php echo lang('propagation_trend_90d'); ?>"
  };
</script>

<style>
  .summary-card { border: 1px solid #eef0f2; }
  .summary-card h5 { font-weight: 600; }
  .heatmap-grid-wrapper { overflow-x: auto; padding-top: 4px; }
  .heatmap-grid { display: grid; grid-template-columns: repeat(24, minmax(0, 1fr)); gap: 2px; max-width: 100%; }
  .heatmap-header {
    text-align: center;
    padding: 6px 4px;
    font-size: 0.75rem;
    font-weight: 700;
    color: #586069;
    background: #f8f9fa;
    border: 1px solid #e1e4e8;
  }
  .heatmap-cell { 
    text-align: center; 
    padding: 8px 4px; 
    font-size: 0.75rem; 
    font-weight: 600;
    border-radius: 3px; 
    color: #1f2d3d; 
    background: #ebedf0; 
    border: 1px solid #d1d5da;
    cursor: default;
  }
  .heatmap-legend { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; }
  .legend-item { display: inline-flex; align-items: center; gap: 4px; font-size: 0.8rem; color: #4a5568; }
  .legend-swatch { width: 16px; height: 16px; border: 1px solid #d1d5da; border-radius: 3px; display: inline-block; }
  .legend-swatch.none { background: #ebedf0; }
  .legend-swatch.low { background: #9be9a8; }
  .legend-swatch.mid { background: #40c463; }
  .legend-swatch.high { background: #30a14e; }
  .legend-swatch.max { background: #216e39; }
  .strongest-band { background: #e6f4ea; color: #1f2d3d; border: 1px solid #b7d9c8; border-radius: 999px; padding: 4px 10px; font-size: 0.8rem; font-weight: 600; }
  .trend-sparkline { min-width: 220px; height: 70px; }
  .trend-sparkline svg { width: 100%; height: 70px; overflow: visible; }
  .trend-line { fill: none; stroke: #30a14e; stroke-width: 2; }
  .trend-area { fill: rgba(64, 196, 99, 0.12); stroke: none; }
  .trend-pill { background: #f5f6f7; border: 1px solid #e1e4e8; border-radius: 999px; padding: 3px 8px; }
  .trend-pill.positive { border-color: #30a14e; color: #216e39; background: #e6f4ea; }
  .trend-pill.negative { border-color: #d73a49; color: #a11925; background: #fcebea; }
  .heatmap-row-label {
    text-align: right;
    padding: 12px 8px;
    font-size: 0.75rem;
    font-weight: 600;
    color: #586069;
    border-right: 2px solid #e1e4e8;
  }
  @media (max-width: 992px) {
    .heatmap-grid { grid-template-columns: repeat(12, minmax(0, 1fr)); }
  }
</style>
