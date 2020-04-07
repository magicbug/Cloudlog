<div class="container">

  <h1><?php echo $page_title?></h1>

  <?php $this->load->view("awards/nav_bar")?>

<h3>CQ Zones worked:</h3>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="table-tab" data-toggle="tab" href="#table" role="tab" aria-controls="table" aria-selected="true">Table</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="map-tab" data-toggle="tab" href="#map" role="tab" aria-controls="home" aria-selected="false">Map</a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade" id="map" role="tabpanel" aria-labelledby="home-tab">
    <br />
            <map name="CQ">
                <area href="cq_details?CQZone=1" title="zone_1" class="zone_1" shape="poly" coords="306,0,306,25,322,37,322,41,291,62,291,76,291,84,364,84,364,67,368,64,373,60,378,60,378,57,377,57,372,52,366,52,366,51,393,51,419,51,419,37,422,35,422,33,420,33,417,30,417,26,408,17,408,0">
                <area href="cq_details?CQZone=2" title="zone_2" class="zone_2" shape="poly" coords="408,0,408,17,417,26,417,30,420,33,422,33,422,35,419,37,419,51,444,51,452,64,452,67,483,67,487,64,495,61,494,53,491,48,490,46,486,40,485,39,461,21,461,19,471,16,477,15,480,14,480,0">
                <area href="cq_details?CQZone=3" title="zone_3" class="zone_3" shape="poly" coords="366,51,366,52,372,52,377,57,378,57,378,60,373,60,368,64,364,67,364,84,364,97,401,97,404,99,409,99,409,83,406,83,406,77,403,77,399,72,400,67,393,61,393,51">
                <area href="cq_details?CQZone=4" title="zone_4" class="zone_4" shape="poly" coords="393,51,393,61,400,67,399,72,403,77,406,77,406,83,409,83,409,99,411,98,414,99,415,102,420,101,424,107,427,109,433,110,438,111,440,111,440,100,441,99,444,99,444,93,448,90,448,88,451,81,454,78,457,78,458,75,452,73,452,67,452,64,444,51,419,51">
                <area href="cq_details?CQZone=5" title="zone_5" class="zone_5" shape="poly" coords="452,67,452,73,458,75,457,78,454,78,451,81,448,88,448,90,444,93,444,99,441,99,440,100,440,111,451,111,452,110,452,108,453,103,458,102,459,101,517,101,517,91,517,53,495,61,487,64,483,67">
                <area href="cq_details?CQZone=6" title="zone_6" class="zone_6" shape="poly" coords="364,97,364,128,433,128,434,126,435,121,440,121,441,120,444,118,440,111,438,111,433,110,427,109,424,107,420,101,416,102,414,99,411,98,409,99,404,99,401,97">
                <area href="cq_details?CQZone=7" title="zone_7" class="zone_7" shape="poly" coords="364,128,364,148,432,148,454,139,456,136,456,130,444,118,441,120,440,121,435,121,434,126,433,128">
                <area href="cq_details?CQZone=8" title="zone_8" class="zone_8" shape="poly" coords="524,116,517,116,517,101,459,101,458,102,453,103,452,109,451,111,440,111,444,118,456,130,465,130,475,132,524,132">
                <area href="cq_details?CQZone=8" title="zone_8" class="zone_8" shape="poly" coords="0,116,0,132,6,132,6,116">
                <area href="cq_details?CQZone=9" title="zone_9" class="zone_9" shape="poly" coords="524,132,475,132,465,130,456,130,456,130,456,136,454,139,432,148,432,149,453,149,455,151,459,152,461,155,467,156,466,149,473,150,475,148,474,145,481,144,481,149,487,148,491,148,493,143,524,143">
                <area href="cq_details?CQZone=9" title="zone_9" class="zone_9" shape="poly" coords="0,132,0,143,6,143,6,132">
                <area href="cq_details?CQZone=10" title="zone_10" class="zone_10" shape="poly" coords="364,148,364,163,371,163,371,168,393,168,393,185,460,185,466,182,468,184,468,187,470,190,472,189,474,189,476,188,477,186,479,184,483,184,482,179,480,179,480,175,476,173,473,172,473,168,466,169,463,168,459,163,467,156,461,155,459,152,455,151,453,149,432,149,432,148">
                <area href="cq_details?CQZone=11" title="zone_11" class="zone_11" shape="poly" coords="524,143,493,143,491,148,489,148,487,148,481,149,481,144,474,145,475,148,473,150,466,149,467,156,459,163,463,168,466,169,473,168,473,172,476,173,480,175,480,179,482,179,483,184,479,184,477,186,476,188,483,193,483,197,487,197,489,195,490,196,485,201,484,201,485,202,490,206,490,209,524,209">
                <area href="cq_details?CQZone=11" title="zone_11" class="zone_11" shape="poly" coords="0,143,0,209,6,209,6,185,6,151,6,143">
                <area href="cq_details?CQZone=12" title="zone_12" class="zone_12" shape="poly" coords="466,303,466,249,471,244,471,243,468,243,468,239,463,239,463,237,460,236,462,233,463,225,464,213,465,211,465,200,468,197,468,194,470,192,470,190,468,187,468,184,466,182,460,185,393,185,393,303">
                <area href="cq_details?CQZone=13" title="zone_13" class="zone_13" shape="poly" coords="524,209,490,209,490,206,485,202,484,201,485,201,490,196,489,195,487,197,483,197,483,194,476,188,474,189,472,189,470,190,470,192,468,194,468,197,465,200,465,211,464,213,463,225,462,233,460,236,463,237,463,239,468,239,468,243,471,243,471,244,466,249,466,303,524,303">
                <area href="cq_details?CQZone=13" title="zone_13" class="zone_13" shape="poly" coords="0,209,0,303,6,303,6,209">
                <area href="cq_details?CQZone=14" title="zone_14" class="zone_14" shape="poly" coords="0,91,42,91,51,88,54,81,54,74,63,70,62,67,65,65,65,58,70,58,72,56,72,47,78,42,78,38,75,36,75,35,76,35,85,34,88,34,88,28,0,51">
                <area href="cq_details?CQZone=14" title="zone_14" class="zone_14" shape="poly" coords="524,51,517,53,517,91,524,91">
                <area href="cq_details?CQZone=15" title="zone_15" class="zone_15" shape="poly" coords="52,87,59,87,63,91,66,92,68,92,72,85,76,82,76,77,74,75,76,70,78,66,78,62,84,56,84,50,88,46,87,43,86,35,85,34,76,35,75,35,75,36,78,38,78,42,72,47,72,56,70,58,65,58,65,65,62,67,63,70,54,74,54,81,52,86">
                <area href="cq_details?CQZone=16" title="zone_16" class="zone_16" shape="poly" coords="146,0,146,15,145,15,89,28,89,35,86,35,87,43,88,46,84,50,84,56,78,62,78,66,76,70,83,71,85,74,101,81,102,78,113,81,114,81,116,79,114,72,111,69,111,68,118,65,131,65,129,63,129,57,123,56,121,54,121,50,115,51,115,48,111,44,115,42,115,40,119,39,135,39,138,36,138,27,152,21,152,0">
                <area href="cq_details?CQZone=17" title="zone_17" class="zone_17" shape="poly" coords="152,21,138,27,138,36,135,39,119,39,115,40,115,42,111,44,115,48,115,51,121,50,121,54,123,56,129,57,129,63,131,65,118,65,111,68,111,69,114,72,116,79,121,88,129,88,133,91,136,91,139,88,144,88,147,87,148,89,152,88,150,84,157,82,160,80,160,76,163,75,165,72,167,72,169,69,166,66,159,65,154,59,154,51,156,49,163,49,167,48,168,43,164,39,163,35,160,34,159,31,156,28,153,24,153,21">
                <area href="cq_details?CQZone=18" title="zone_18" class="zone_18" shape="poly" coords="153,0,153,24,156,28,159,31,160,34,163,35,164,39,168,43,167,48,163,49,156,49,154,51,154,59,159,65,166,66,169,69,172,68,173,65,180,63,182,62,187,62,188,64,192,65,193,66,199,67,201,68,208,68,217,67,219,62,222,61,221,58,218,57,216,52,212,50,209,51,203,51,203,46,199,42,198,42,199,35,206,31,206,27,213,24,213,0">
                <area href="cq_details?CQZone=19" title="zone_19" class="zone_19" shape="poly" coords="213,0,213,24,206,27,206,31,199,35,198,42,199,42,203,46,203,51,209,51,212,50,216,52,218,57,221,58,222,61,224,61,227,63,228,66,230,68,232,68,234,70,239,70,239,72,235,76,234,79,238,82,240,83,248,75,255,75,255,79,257,79,262,76,291,76,291,62,322,41,322,37,306,25,306,0">
                <area href="cq_details?CQZone=20" title="zone_20" class="zone_20" shape="poly" coords="76,71,74,75,76,77,76,82,72,85,69,91,69,93,92,98,94,101,97,101,98,100,98,98,100,98,100,95,103,93,104,90,108,88,108,84,105,81,101,81,85,74,83,71">
                <area href="cq_details?CQZone=21" title="zone_21" class="zone_21" shape="poly" coords="94,101,94,105,107,131,111,131,131,126,144,110,146,110,146,108,145,107,145,105,147,105,149,103,153,97,151,93,156,92,152,88,148,89,147,87,144,88,139,88,136,91,133,91,129,88,121,88,116,79,114,81,112,81,102,78,101,81,105,81,108,84,108,88,104,90,103,93,100,95,100,98,98,98,98,100,97,101">
                <area href="cq_details?CQZone=22" title="zone_22" class="zone_22" shape="poly" coords="131,151,144,151,148,154,152,154,156,151,160,151,178,151,178,141,178,116,180,111,183,106,185,104,183,102,178,104,169,104,158,98,158,94,156,92,151,93,153,97,149,103,147,105,145,105,145,107,146,108,146,110,144,110,131,126">
                <area href="cq_details?CQZone=23" title="zone_23" class="zone_23" shape="poly" coords="158,94,158,98,169,104,178,104,183,102,185,104,187,104,187,96,192,96,194,95,197,96,199,94,199,93,201,91,201,88,203,86,212,81,217,81,223,79,222,75,222,72,224,70,226,67,226,65,223,65,221,63,222,61,219,62,217,67,208,68,201,68,199,67,193,66,192,65,188,64,187,62,182,62,180,63,173,65,172,68,169,69,167,72,165,72,163,75,160,76,160,80,157,82,150,84,152,88,156,92">
                <area href="cq_details?CQZone=24" title="zone_24" class="zone_24" shape="poly" coords="188,111,188,114,191,115,196,112,198,113,200,115,201,121,202,122,205,122,212,119,213,119,220,115,223,113,223,108,225,90,225,84,228,82,234,79,235,76,239,72,239,70,234,70,232,68,230,68,228,66,227,63,224,61,222,61,221,63,223,65,226,65,226,67,224,70,222,72,222,75,223,79,217,81,212,81,203,86,201,88,201,91,199,93,199,94,197,96,194,95,192,96,187,96,186,111">
                <area href="cq_details?CQZone=25" title="zone_25" class="zone_25" shape="poly" coords="223,113,233,113,249,103,262,84,284,84,291,84,291,76,262,76,257,79,255,79,255,75,248,75,240,83,238,82,234,79,227,82,225,84,225,90,223,108">
                <area href="cq_details?CQZone=26" title="zone_26" class="zone_26" shape="poly" coords="178,116,178,141,192,141,212,140,212,119,205,122,202,122,201,121,200,115,198,113,196,112,191,115,188,114,188,111,186,111,187,104,185,104,183,106,180,111">
                <area href="cq_details?CQZone=27" title="zone_27" class="zone_27" shape="poly" coords="222,113,222,115,213,119,213,140,215,140,219,144,229,144,233,148,242,148,242,151,284,151,284,141,277,135,277,126,284,126,284,84,262,84,249,103,233,113">
                <area href="cq_details?CQZone=28" title="zone_28" class="zone_28" shape="poly" coords="178,141,178,168,213,168,222,171,224,171,231,168,250,168,253,167,253,173,284,173,284,168,284,151,241,151,241,148,233,148,229,144,219,144,215,140,212,140,192,141">
                <area href="cq_details?CQZone=29" title="zone_29" class="zone_29" shape="poly" coords="231,303,231,195,245,195,245,168,231,168,224,171,222,171,213,168,178,168,178,151,160,151,160,303">
                <area href="cq_details?CQZone=30" title="zone_30" class="zone_30" shape="poly" coords="291,303,291,252,284,244,284,244,284,191,279,191,279,180,284,180,284,173,253,173,253,167,250,168,245,168,245,195,231,195,231,303">
                <area href="cq_details?CQZone=31" title="zone_31" class="zone_31" shape="poly" coords="284,84,284,126,277,126,277,135,284,141,284,151,284,168,295,168,296,172,301,171,303,170,303,172,311,172,311,168,327,168,339,166,339,172,371,172,371,168,371,163,364,163,364,148,364,128,364,97,364,84,291,84">
                <area href="cq_details?CQZone=32" title="zone_32" class="zone_32" shape="poly" coords="393,303,393,185,393,168,371,168,371,172,339,172,339,166,327,168,311,168,311,172,303,172,303,170,301,171,296,172,295,168,284,168,284,173,284,180,283,180,279,180,279,191,284,191,284,244,291,252,291,303">
                <area href="cq_details?CQZone=33" title="zone_33" class="zone_33" shape="poly" coords="0,116,6,116,25,116,26,108,31,108,31,106,35,108,38,110,48,118,53,118,56,116,61,112,57,108,58,100,63,91,59,87,52,87,51,88,42,91,0,91">
                <area href="cq_details?CQZone=33" title="zone_33" class="zone_33" shape="poly" coords="524,91,517,91,517,101,517,116,524,116">
                <area href="cq_details?CQZone=34" title="zone_34" class="zone_34" shape="poly" coords="63,91,58,100,57,108,61,112,65,113,67,112,77,118,78,119,78,124,76,127,76,132,81,140,84,143,89,145,92,145,95,143,95,142,92,137,97,128,97,123,100,121,101,120,94,105,94,101,92,98,69,93,68,92,66,92">
                <area href="cq_details?CQZone=35" title="zone_35" class="zone_35" shape="poly" coords="6,151,49,151,55,145,56,142,58,140,60,140,63,133,64,132,64,127,66,120,66,112,61,112,56,116,53,118,48,118,38,110,35,108,31,106,31,108,26,108,25,116,6,116">
                <area href="cq_details?CQZone=36" title="zone_36" class="zone_36" shape="poly" coords="6,185,49,185,62,180,64,181,83,181,88,177,89,177,91,175,91,171,92,168,88,165,86,160,88,157,87,150,88,149,89,145,84,143,81,140,76,132,76,127,78,124,78,119,77,118,67,112,66,112,66,120,64,127,64,132,63,133,60,140,58,140,56,142,55,145,49,151,6,151">
                <area href="cq_details?CQZone=37" title="zone_37" class="zone_37" shape="poly" coords="89,145,88,149,87,150,88,157,86,160,88,165,92,168,91,171,91,175,89,177,91,179,91,186,90,189,90,196,95,201,96,201,100,186,105,177,105,168,120,151,131,151,131,126,111,131,107,131,101,120,100,121,97,123,97,128,92,137,95,142,95,143,92,145">
                <area href="cq_details?CQZone=38" title="zone_38" class="zone_38" shape="poly" coords="96,303,96,235,101,235,101,224,96,224,96,201,95,201,90,196,90,189,91,186,91,179,89,177,88,177,83,181,64,181,62,180,49,185,6,185,6,209,6,303">
                <area href="cq_details?CQZone=39" title="zone_39" class="zone_39" shape="poly" coords="160,303,160,151,156,151,152,154,148,154,144,151,131,151,120,151,105,168,105,177,100,186,96,201,96,224,101,224,101,235,96,235,96,303">
                <area href="cq_details?CQZone=40" title="zone_40" class="zone_40" shape="poly" coords="0,0,0,51,88,28,89,28,145,15,145,0">
                <area href="cq_details?CQZone=40" title="zone_40" class="zone_40" shape="poly" coords="480,0,480,14,477,15,471,16,461,19,461,21,485,39,486,40,490,46,491,48,494,53,495,61,517,53,524,51,524,0">
            </map>

            <img class="map" src="<?php echo site_url("../images/CQzone.gif"); ?>" usemap="#CQ" border="0">

            Maps from <a href="http://www4.plala.or.jp/nomrax/hammaps.htm">JF9EXF</a> site.
            <h4>Notes:</h4>
            <ul>
                <li>All US callsigns are allocated zone 5 by the FCC. This may not be correct</li>
            </ul>

        </div>

        <div class="tab-pane fade show active" id="table" role="tabpanel" aria-labelledby="table-tab">
        <br />
            <form class="form" action="<?php echo site_url('awards/cq'); ?>" method="post" enctype="multipart/form-data">
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
    if ($cq_array) {
    echo '
    <table class="table table-bordered table-hover table-striped table-condensed text-center">
        <thead>
        <tr>
            <td>CQ Zone</td>';
        foreach($bands as $band) {
            echo '<td>' . $band . '</td>';
            }
            echo '</tr>
        </thead>
        <tbody>';
        foreach ($cq_array as $cq => $value) {      // Fills the table with the data
        echo '<tr>
            <td>'. $cq .'</td>';
            foreach ($value  as $key) {
            echo '<td style="text-align: center">' . $key . '</td>';
            }
            echo '</tr>';
        }
        echo '</tfoot></table></div>';

    }
    else {
        echo '<div class="alert alert-danger" role="alert"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Nothing found!</div>';
    }
    ?>

            </div>
        </div>
</div>
