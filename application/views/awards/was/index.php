
<div class="container">
    <h1><?php echo $page_title; ?></h1>

    <!-- Sub Nav for Awards -->

    <?php $this->load->view("awards/nav_bar")?>
    <form class="form" action="<?php echo site_url('awards/was'); ?>" method="post" enctype="multipart/form-data">
        <fieldset>

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
    if ($was_array) {
        $i = 1;
    echo '
    <table class="table table-bordered table-hover table-striped table-condensed text-center">
        <thead>
        <tr>
            <td>#</td>
            <td>State</td>';
        foreach($bands as $band) {
            echo '<td>' . $band . '</td>';
            }
            echo '</tr>
        </thead>
        <tbody>';

        foreach ($was_array as $was => $value) {      // Fills the table with the data
        echo '<tr>
            <td>' . $i++ . '</td>
            <td>'. $was .'</td>';
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

        foreach ($was_summary as $was) {      // Fills the table with the data
            echo '<td style="text-align: center">' . $was->col_band . '</td>';
        }

        echo '</tr>
        </thead>
        <tbody>

        <tr><td>Total worked</td>';

        foreach ($was_summary as $was) {      // Fills the table with the data
            echo '<td style="text-align: center">' . $was->count . '</td>';
        }

        echo '</tr><tr>
        <td>Total confirmed</td>';
        foreach ($was_summary as $was) {      // Fills the table with the data
            echo '<td style="text-align: center">' . $was->cfmwas . '</td>';
        }

        echo '</tr>
        </table>
        </div>';
    }
    else {
        echo '<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Nothing found!</div>';
    }