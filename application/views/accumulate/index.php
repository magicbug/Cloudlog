<div class="container awards-page">
    <?php $this->load->view('awards/components/topbar', array(
        'title' => $page_title,
        'subtitle' => 'Track cumulative award progress over time by band, mode, and period.',
        'icon' => 'fas fa-chart-line',
    )); ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h2 class="h5 mb-0">Filters</h2>
        </div>
        <div class="card-body">
            <form id="accumulatedFiltersForm"
                  class="form"
                  hx-post="<?php echo site_url('accumulated/component_accumulated_results'); ?>"
                  hx-target="#accumulateResults"
                  hx-swap="innerHTML"
                  hx-trigger="change from:select, change from:input[type='radio'], submit">
                <div class="row g-3 align-items-end mb-2">
                    <div class="col-md-4">
                        <label class="form-label" for="band">Band</label>
                        <select id="band" name="band" class="form-select">
                            <option value="All">All</option>
                            <?php foreach($worked_bands as $band) {
                                echo '<option value="' . $band . '">' . $band . '</option>' . "\n";
                            } ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="mode">Mode</label>
                        <select id="mode" name="mode" class="form-select">
                            <option value="All">All</option>
                            <?php
                            foreach($modes->result() as $mode){
                                if ($mode->submode == null) {
                                    printf("<option value=\"%s\">%s</option>", $mode->mode, $mode->mode);
                                } else {
                                    printf("<option value=\"%s\">%s</option>", $mode->submode, $mode->submode);
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label d-block mb-2">Award</label>
                        <div class="d-flex flex-wrap gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="awardradio" id="dxcc" value="dxcc" checked>
                                <label class="form-check-label" for="dxcc">DXCC</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="awardradio" id="was" value="was">
                                <label class="form-check-label" for="was">WAS</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="awardradio" id="iota" value="iota">
                                <label class="form-check-label" for="iota">IOTA</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="awardradio" id="waz" value="waz">
                                <label class="form-check-label" for="waz">WAZ</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label d-block mb-2">Period</label>
                        <div class="d-flex flex-wrap gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="periodradio" id="yearly" value="year" checked>
                                <label class="form-check-label" for="yearly">Yearly</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="periodradio" id="monthly" value="month">
                                <label class="form-check-label" for="monthly">Monthly</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 d-flex justify-content-md-end gap-2">
                        <button id="accumulateShowButton" type="submit" class="btn btn-primary ld-ext-right">
                            Show
                            <div class="ld ld-ring ld-spin"></div>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="accumulateResults"
         hx-post="<?php echo site_url('accumulated/component_accumulated_results'); ?>"
         hx-target="this"
         hx-swap="innerHTML"
         hx-include="#accumulatedFiltersForm"
         hx-trigger="load"></div>
</div>

