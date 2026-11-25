<style>
#continentChart {
    margin: 0 auto;
}
</style>

<div class="container statistics awards-page">
    <?php $this->load->view('awards/components/topbar', array(
        'title' => $page_title,
        'subtitle' => 'See how your QSOs are distributed across continents by band and mode.',
        'icon' => 'fas fa-globe-europe',
    )); ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h2 class="h5 mb-0">Filters</h2>
        </div>
        <div class="card-body">
            <form id="continentFiltersForm"
                  name="continentFiltersForm"
                  class="form"
                  hx-post="<?php echo site_url('continents/component_continent_results'); ?>"
                  hx-target="#continentResults"
                  hx-swap="innerHTML"
                  hx-trigger="change from:select, submit">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label" for="band">Band</label>
                        <select id="band" name="band" class="form-select">
                            <option value="">All</option>
                            <?php foreach($bands as $band){ ?>
                                <option value="<?php echo htmlentities($band); ?>"><?php echo htmlspecialchars($band); ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="mode">Mode</label>
                        <select id="mode" name="mode" class="form-select">
                            <option value="">All</option>
                            <?php foreach($modes as $modeId => $mode){ ?>
                                <option value="<?php echo htmlspecialchars($mode); ?>"><?php echo htmlspecialchars($mode); ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-md-4 d-flex justify-content-md-end gap-2">
                        <button type="button" class="btn btn-outline-secondary" id="resetButton">Reset</button>
                        <button type="submit" class="btn btn-primary" id="searchButton">Show</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="continentResults"
         hx-post="<?php echo site_url('continents/component_continent_results'); ?>"
         hx-target="this"
         hx-swap="innerHTML"
         hx-include="#continentFiltersForm"
         hx-trigger="load"></div>
</div>