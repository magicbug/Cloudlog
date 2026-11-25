<div class="container awards-page">
    <?php $this->load->view('awards/components/topbar', array(
        'title' => lang('statistics_emeinitials'),
        'subtitle' => 'Track first EME callsign initials by band and mode.',
        'icon' => 'fas fa-moon',
    )); ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header py-3">
            <h2 class="h5 mb-0">Filters</h2>
        </div>
        <div class="card-body">
            <form id="emeFiltersForm"
                  class="form"
                  hx-post="<?php echo site_url('emeinitials/component_eme_results'); ?>"
                  hx-target="#emeResults"
                  hx-swap="innerHTML"
                  hx-trigger="change from:select, submit">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label" for="band"><?php echo lang('gen_hamradio_band') ?></label>
                        <select id="band" name="band" class="form-select">
                            <option value="All"><?php echo lang('general_word_all') ?></option>
                            <?php foreach($worked_bands as $band) {
                                echo '<option value="' . $band . '">' . $band . '</option>' . "\n";
                            } ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="mode"><?php echo lang('gen_hamradio_mode') ?></label>
                        <select id="mode" name="mode" class="form-select">
                            <option value="All"><?php echo lang('general_word_all') ?></option>
                            <?php
                            foreach($modes->result() as $mode){
                                if ($mode->submode == null) {
                                    echo '<option value="' . $mode->mode . '">' . $mode->mode . '</option>' . "\n";
                                } else {
                                    echo '<option value="' . $mode->submode . '">' . $mode->submode . '</option>' . "\n";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4 d-flex justify-content-md-end gap-2">
                        <button type="button" class="btn btn-outline-secondary" id="emeResetButton">Reset</button>
                        <button id="button1id" type="submit" class="btn btn-primary"><?php echo lang('filter_options_show') ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="emeResults"
         hx-post="<?php echo site_url('emeinitials/component_eme_results'); ?>"
         hx-target="this"
         hx-swap="innerHTML"
         hx-include="#emeFiltersForm"
         hx-trigger="load"></div>
</div>

<script>
document.addEventListener('click', function (event) {
    if (event.target && event.target.id === 'emeResetButton') {
        const form = document.getElementById('emeFiltersForm');
        if (!form) {
            return;
        }

        form.reset();
        if (window.htmx) {
            htmx.trigger(form, 'submit');
        }
    }
});
</script>
