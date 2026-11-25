<div class="container awards-page">
    <?php $this->load->view('awards/components/topbar', array(
        'title' => $page_title,
        'subtitle' => 'Analyze when your QSOs happen across the day by band, DXCC, and CQ zone.',
        'icon' => 'fas fa-clock',
    )); ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h2 class="h5 mb-0">Filters</h2>
        </div>
        <div class="card-body">
            <form id="timeplotterFiltersForm"
                  class="form"
                  hx-post="<?php echo site_url('timeplotter/component_timeplot_results'); ?>"
                  hx-target="#timeplotterResults"
                  hx-swap="innerHTML"
                  hx-trigger="change from:select, submit">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label" for="band">Band</label>
                        <select id="band" name="band" class="form-select">
                            <option value="All">All</option>
                            <?php foreach ($worked_bands as $band) {
                                echo '<option value="' . $band . '">' . $band . '</option>' . "\n";
                            } ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="dxcc">DXCC</label>
                        <select id="dxcc" name="dxcc" class="form-select">
                            <option value="All">All</option>
                            <?php
                            if ($dxcc_list->num_rows() > 0) {
                                foreach ($dxcc_list->result() as $dxcc) {
                                    echo '<option value="' . $dxcc->adif . '">' . ucwords(strtolower($dxcc->name)) . ' - ' . $dxcc->prefix;
                                    if ($dxcc->end != null) {
                                        echo ' (' . lang('gen_hamradio_deleted_dxcc') . ')';
                                    }
                                    echo '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="cqzone">CQ Zone</label>
                        <select id="cqzone" name="cqzone" class="form-select">
                            <option value="All">All</option>
                            <?php
                            for ($i = 1; $i <= 40; $i++) {
                                echo '<option value="' . $i . '">' . $i . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-12 d-flex justify-content-md-end gap-2">
                        <button id="timeplotShowButton" type="submit" class="btn btn-primary ld-ext-right">
                            Show
                            <div class="ld ld-ring ld-spin"></div>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="timeplotterResults"
         hx-post="<?php echo site_url('timeplotter/component_timeplot_results'); ?>"
         hx-target="this"
         hx-swap="innerHTML"
         hx-include="#timeplotterFiltersForm"
         hx-trigger="load"></div>
</div>