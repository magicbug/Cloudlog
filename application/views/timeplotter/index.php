<div class="container">
    <h2><?php echo $page_title; ?></h1>
        <p>The Timeplotter is used to analyze your logbook and find out when you have worked a certain CQ zone or DXCC on a chosen band.</p>
        <form class="form">

            <div class="form-group row">
                <label class="col-md-1 control-label" for="band">Band</label>
                <div class="col-md-3">
                    <select id="band" name="band" class="form-control custom-select">
                        <option value="All">All</option>
                        <?php foreach($worked_bands as $band) {
                            echo '<option value="' . $band . '">' . $band . '</option>'."\n";
                        } ?>
                    </select>
                </div>

                <label class="col-md-1 control-label" for="dxcc">DXCC</label>
                <div class="col-md-3">
                    <select id="dxcc" name="dxcc" class="form-control custom-select">
                        <option value = 'All'>All</option>
                        <?php
                        if ($dxcc_list->num_rows() > 0) {
                                foreach ($dxcc_list->result() as $dxcc) {
                                    echo '<option value=' . $dxcc->adif . '> ' . ucwords(strtolower($dxcc->name)) . ' - ' . $dxcc->prefix;
                                    if ($dxcc->end != null) {
                                        echo ' ('.lang('gen_hamradio_deleted_dxcc').')';
                                    }
                                    echo '</option>';
                                }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-md-1 control-label" for="cqzone">CQ Zone</label>
                <div class="col-md-3">
                    <select id="cqzone" name="cqzone" class="form-control custom-select">
                        <option value = 'All'>All</option>
                        <?php
                        for ($i = 1; $i<=40; $i++) {
                            echo '<option value='. $i . '>'. $i .'</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-3">
                    <button id="button1id" type="button" name="button1id" class="btn btn-primary ld-ext-right" onclick="timeplot(this.form);">Show<div class="ld ld-ring ld-spin"></div></button>
                </div>
            </div>

        </form>

        <div id="timeplotter_div">
        </div>
</div>
