<div class="container">
    <h2><?php echo $page_title; ?></h1>

        <form class="form" action="<?php echo site_url('accumulated'); ?>" method="post" enctype="multipart/form-data">
            <fieldset>
                <!-- Select Basic -->
                <div class="form-group row">
                    <label class="col-md-1 control-label" for="band">Band</label>
                    <div class="col-md-2">
                        <select id="band2" name="band" class="form-control">
                            <option value="All" <?php if ($this->input->post('band') == "All" || $this->input->method() !== 'post') echo ' selected'; ?> >All</option>
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
                    <label class="col-md-1 control-label" for="button1id"></label>
                    <div class="col-md-10">
                        <button id="button1id" type="submit" name="button1id" class="btn btn-success btn-primary">Show</button>
                    </div>
                </div>

            </fieldset>
        </form>

        <?php
        $i = 1;
        if ($accumulated_dxcc_array) {
            echo '<table class="table table-sm table-bordered table-hover table-striped table-condensed text-center">
              <thead>
                    <tr>
                        <td>#</td>
                        <td>Year</td>
                        <td>Accumulated # of DXCC\'s worked</td>
                    </tr>
                </thead>
                <tbody>';

            foreach ($accumulated_dxcc_array as $line) {
                echo '<tr>
                <td>' . $i++ . '</td>
                <td>' . $line->year . '</td>
                <td>' . $line->total . '</td>
               </tr>';
            }
            echo '</tfoot></table></div>';
        }
        else {
            echo '<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Nothing found!</div>';
        }
        ?>
</div>

