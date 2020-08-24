
<div class="container">
    <h1><?php echo $page_title; ?></h1>

    <!-- Sub Nav for Awards -->

    <?php $this->load->view("awards/nav_bar")?>
    <form class="form" action="<?php echo site_url('awards/dxcc'); ?>" method="post" enctype="multipart/form-data">
        <fieldset>

            <!-- Multiple Checkboxes (inline) -->
            <div class="form-group row">
                <div class="col-md-2 control-label" for="checkboxes">Deleted DXCC</div>
                <div class="col-md-10">
                    <div class="form-check-inline">
                        <input class="form-check-input" type="checkbox" name="includedeleted" id="includedeleted" value="1" <?php if ($this->input->post('includedeleted') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                        <label class="form-check-label" for="includedeleted">Include Deleted</label>
                    </div>
                </div>
            </div>

            <!-- Multiple Checkboxes (inline) -->
            <div class="form-group row">
                <div class="col-md-2" for="checkboxes">Worked / confirmed</div>
                <div class="col-md-10">
                    <div class="form-check-inline">
                        <input class="form-check-input" type="checkbox" name="worked" id="worked" value="1" <?php if ($this->input->post('worked') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                        <label class="form-check-label" for="worked">Show worked</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input" type="checkbox" name="confirmed" id="confirmed" value="1" <?php if ($this->input->post('confirmed') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                        <label class="form-check-label" for="confirmed">Show confirmed</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input" type="checkbox" name="notworked" id="notworked" value="1" <?php if ($this->input->post('notworked') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                        <label class="form-check-label" for="notworked">Show not worked</label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-2">QSL / LoTW</div>
                <div class="col-md-10">
                    <div class="form-check-inline">
                        <input class="form-check-input" type="checkbox" name="qsl" value="1" id="qsl" <?php if ($this->input->post('qsl') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                        <label class="form-check-label" for="qsl">QSL</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input" type="checkbox" name="lotw" value="1" id="lotw" <?php if ($this->input->post('lotw') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                        <label class="form-check-label" for="lotw">LoTW</label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-2">Continents</div>
                <div class="col-md-10">
                    <div class="form-check-inline">
                        <input class="form-check-input" type="checkbox" name="Antarctica" id="Antarctica" value="1" <?php if ($this->input->post('Antarctica') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                        <label class="form-check-label" for="Antarctica">Antarctica</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input"  type="checkbox" name="Africa" id="Africa" value="1" <?php if ($this->input->post('Africa') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                        <label class="form-check-label" for="Africa">Africa</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input"  type="checkbox" name="Asia" id="Asia" value="1" <?php if ($this->input->post('Asia') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                        <label class="form-check-label" for="Asia">Asia</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input"  type="checkbox" name="Europe" id="Europe" value="1" <?php if ($this->input->post('Europe') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                        <label class="form-check-label" for="Europe">Europe</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input"  type="checkbox" name="NorthAmerica" id="NorthAmerica" value="1" <?php if ($this->input->post('NorthAmerica') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                        <label class="form-check-label" for="NorthAmerica">North America</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input"  type="checkbox" name="SouthAmerica" id="SouthAmerica" value="1" <?php if ($this->input->post('SouthAmerica') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                        <label class="form-check-label" for="SouthAmerica">South America</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input"  type="checkbox" name="Oceania" id="Oceania" value="1" <?php if ($this->input->post('Oceania') || $this->input->method() !== 'post') echo ' checked="checked"'; ?> >
                        <label class="form-check-label" for="Oceania">Oceania</label>
                    </div>
                </div>
            </div>

            <!-- Select Basic -->
            <div class="form-group row">
                <label class="col-md-2 control-label" for="band">Band</label>
                <div class="col-md-2">
                    <select id="band2" name="band" class="form-control">
                        <option value="All" <?php if ($this->input->post('band') == "All" || $this->input->method() !== 'post') echo ' selected'; ?> >Every band</option>
                        <?php foreach($worked_bands as $band) {
                            echo '<option value="' . $band . '"';
                            if ($this->input->post('band') == $band) echo ' selected';
                            echo '>' . $band . '</option>'."\n";
                        } ?>
                    </select>
                </div>
            </div>

            <!-- Button (Double) -->
            <div class="form-group row">
                <label class="col-md-2 control-label" for="button1id"></label>
                <div class="col-md-10">
                    <button id="button2id" type="reset" name="button2id" class="btn btn-danger">Reset</button>
                    <button id="button1id" type="submit" name="button1id" class="btn btn-success btn-primary">Show</button>
                </div>
            </div>

        </fieldset>
    </form>
    <?php
    $i = 1;
    if ($dxcc_array) {
        echo '
                
                <table class="table table-bordered table-hover table-striped table-condensed text-center">
                    <thead>
                    <tr>
                        <td>#</td>
                        <td>DXCCName</td>
                        <td>Prefix</td>';
        if ($this->input->post('includedeleted') || $this->input->method() !== 'post')
            echo '
                        <td>Deleted</td>';
        foreach($bands as $band) {
            echo '<td>' . $band . '</td>';
        }
        echo '</tr>
                    </thead>
                    <tbody>';
        foreach ($dxcc_array as $dxcc => $value) {      // Fills the table with the data
            echo '<tr>
                        <td>'. $i++ .'</td>';
            foreach ($value  as $key) {
                echo '<td style="text-align: center">' . $key . '</td>';
            }
            echo '</tr>';
        }
        echo '</table>
        <h1>Summary</h1>

        <table class="table table-bordered table-hover table-striped table-condensed text-center">
        <thead>
        <tr><td></td>';

        foreach($bands as $band) {
            echo '<td>' . $band . '</td>';
        }
        echo '</tr>';

        echo '</tr>
        </thead>
        <tbody>

        <tr><td>Total worked</td>';

        foreach ($dxcc_summary['worked'] as $dxcc) {      // Fills the table with the data
            echo '<td style="text-align: center">' . $dxcc . '</td>';
        }

        echo '</tr><tr>
        <td>Total confirmed</td>';
        foreach ($dxcc_summary['confirmed'] as $dxcc) {      // Fills the table with the data
            echo '<td style="text-align: center">' . $dxcc . '</td>';
        }

        echo '</tr>
        </table>
        </div>';

    }
    else {
        echo '<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Nothing found!</div>';
    }
    ?>
</div>
