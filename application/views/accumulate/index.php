<div class="container">
    <h2><?php echo $page_title; ?></h1>

        <form class="form">

                <!-- Select Basic -->
                <div class="form-group row">
                    <label class="col-md-1 control-label" for="band">Band</label>
                    <div class="col-md-2">
                        <select id="band" name="band" class="form-control custom-select">
                            <option value="All">All</option>
                            <?php foreach($worked_bands as $band) {
                                echo '<option value="' . $band . '">' . $band . '</option>'."\n";
                            } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="awardradio" id="dxcc" value="dxcc" checked>
                        <label class="form-check-label" for="dxcc">
                            DX Century Club (DXCC)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="awardradio" id="was" value="was">
                        <label class="form-check-label" for="was">
                            Worked all states (WAS)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="awardradio" id="iota" value="iota">
                        <label class="form-check-label" for="iota">
                            Islands on the air (IOTA)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="awardradio" id="waz" value="waz">
                        <label class="form-check-label" for="waz">
                            Worked all zones (WAZ)
                        </label>
                    </div>
                </div>


                <!-- Button (Double) -->
                <div class="form-group row">
                    <div class="col-md-10">
                        <button id="button1id" type="button" name="button1id" class="btn btn-success btn-primary" onclick="accumulatePlot(this.form)">Show</button>
                    </div>
                </div>


        </form>

        <div id="accumulateContainer">
        <canvas id="myChartAccumulate" width="400" height="150"></canvas>
        <div id="accumulateTable"></div>
        </div>

</div>

