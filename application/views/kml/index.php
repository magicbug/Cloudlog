<div class="container">
<br>
    <h2><?php echo lang('export_kml_header'); ?></h2>

    <div class="card">
        <div class="card-header">
            <?php echo lang('export_kml_description'); ?>
        </div>

        <div class="alert alert-warning" role="alert">
            <?php echo lang('export_kml_grisquare_warning'); ?>
        </div>

        <div class="card-body">

            <form class="form" action="<?php echo site_url('kmlexport/export'); ?>" method="post" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="band"><?php echo lang('gen_hamradio_band'); ?></label>
                            <select id="band" name="band" class="custom-select">
                                <option value="All" <?php if ($this->input->post('band') == "All" || $this->input->method() !== 'post') echo ' selected'; ?> ><?php echo lang('general_word_all'); ?></option>
                                <?php foreach($worked_bands as $band) {
                                    echo '<option value="' . $band . '"';
                                    if ($this->input->post('band') == $band) echo ' selected';
                                    echo '>' . $band . '</option>'."\n";
                                } ?>
                            </select>
                    </div>

                    <div class="form-group col-md-3">
                    <label for="mode"><?php echo lang('gen_hamradio_mode'); ?></label>
                        <select id="mode" name="mode" class="form-control custom-select">
                            <option value="All"><?php echo lang('general_word_all'); ?></option>
                            <?php
                            foreach($modes->result() as $mode){
                                if ($mode->submode == null) {
                                    echo '<option value="' . $mode->mode . '">'. $mode->mode . '</option>'."\n";
                                } else {
                                    echo '<option value="' . $mode->submode . '">' . $mode->submode . '</option>'."\n";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="dxcc_id"><?php echo lang('gen_hamradio_dxcc'); ?></label>
                        <select class="custom-select" id="dxcc_id" name="dxcc_id">
                            <option value="All"><?php echo lang('general_word_all'); ?></option>
                            <?php
                                foreach($dxcc as $d){
                                    echo '<option value=' . $d->adif . '>' . $d->prefix . ' - ' . ucwords(strtolower($d->name), "- (/");
                                    if ($d->Enddate != null) {
                                        echo ' ('.lang('gen_hamradio_deleted_dxcc').')';
                                    }
                                    echo '</option>';
                                }
                            ?>

                        </select>
                    </div>
                </div>
                <div class="form-row">                
                    <div class="form-group col-md-3">
                        <label for="cqz"><?php echo lang('gen_hamradio_cq_zone'); ?></label>
                        <select class="custom-select" id="cqz" name="cqz">
                            <option value="All"><?php echo lang('general_word_all'); ?></option>
                            <?php
                            for ($i = 1; $i<=40; $i++) {
                                echo '<option value="'. $i . '">'. $i .'</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group col-md-5">
                        <label for="selectPropagation"><?php echo lang('gen_hamradio_propagation_mode'); ?></label>
                        <select class="custom-select" id="selectPropagation" name="prop_mode">
                            <option value="All"><?php echo lang('general_word_all'); ?></option>
                            <option value="AS">Aircraft Scatter</option>
                            <option value="AUR">Aurora</option>
                            <option value="AUE">Aurora-E</option>
                            <option value="BS">Back scatter</option>
                            <option value="ECH">EchoLink</option>
                            <option value="EME">Earth-Moon-Earth</option>
                            <option value="ES">Sporadic E</option>
                            <option value="FAI">Field Aligned Irregularities</option>
                            <option value="F2">F2 Reflection</option>
                            <option value="INTERNET">Internet-assisted</option>
                            <option value="ION">Ionoscatter</option>
                            <option value="IRL">IRLP</option>
                            <option value="MS">Meteor scatter</option>
                            <option value="RPT">Terrestrial or atmospheric repeater or transponder</option>
                            <option value="RS">Rain scatter</option>
                            <option value="SAT">Satellite</option>
                            <option value="TEP">Trans-equatorial</option>
                            <option value="TR">Tropospheric ducting</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="datetimepicker1"><?php echo lang('gen_from_date') . ':'; ?></label>
                        <div class="exportdatepicker input-group date col-md-12" id="datetimepicker1" data-target-input="nearest">
                            <input name="fromdate" type="text" placeholder="DD/MM/YYYY" class="form-control datetimepicker-input" data-target="#datetimepicker1"/>
                            <div class="input-group-append"  data-target="#datetimepicker1" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="datetimepicker2"><?php echo lang('gen_to_date') . ':'; ?></label>
                        <div class="exportdatepicker input-group date col-md-12" id="datetimepicker2" data-target-input="nearest">
                            <input name="todate" totype="text" placeholder="DD/MM/YYYY" class="form-control datetimepicker-input" data-target="#datetimepicker2"/>
                            <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>    
                <br>
                <button type="submit" class="btn btn-primary mb-2" value="Export"><?php echo lang('general_word_export'); ?></button>
            </form>
        </div>
    </div>
</div>
