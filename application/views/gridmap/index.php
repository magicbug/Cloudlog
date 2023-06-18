<div class="container">

	<br>

	<h2><?php echo $page_title; ?></h2>

<form class="form-inline">
            <label class="my-1 mr-2" for="band"><?php echo lang('gen_band_selection'); ?></label>
            <select class="custom-select my-1 mr-sm-2"  id="band">
                <option value="All">All</option>
				<?php foreach($bands as $band) {
					echo '<option value="' . $band . '"' . '>' . $band . '</option>'."\n";
                } ?>
            </select>
			<label class="my-1 mr-2" for="mode">Mode selection</label>
            <select class="custom-select my-1 mr-sm-2"  id="mode">
			<option value="All">All</option>
                            <?php
                            foreach($modes as $mode){
                                if ($mode->submode == null) {
                                    echo '<option value="' . $mode . '">' . strtoupper($mode) . '</option>'."\n";
                                }
                            }
                            ?>
            </select>
			<label class="my-1 mr-2">Confirmation</label>
                <div>
                    <div class="form-check-inline">
                        <input class="form-check-input" type="checkbox" name="qsl" id="qsl" checked>
                        <label class="form-check-label" for="qsl">QSL</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input" type="checkbox" name="lotw" id="lotw" checked>
                        <label class="form-check-label" for="lotw">LoTW</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="form-check-input" type="checkbox" name="eqsl" id="eqsl">
                        <label class="form-check-label" for="eqsl">eQSL</label>
                    </div>
                </div>

            <button id="plot" type="button" name="plot" class="btn btn-primary" onclick="gridPlot(this.form)">Plot</button>
</form>

		<?php if($this->session->flashdata('message')) { ?>
			<!-- Display Message -->
			<div class="alert-message error">
			  <p><?php echo $this->session->flashdata('message'); ?></p>
			</div>
		<?php } ?>
</div>

<div id="gridmapcontainer">
	<div id="gridsquare_map" style="width: 100%; height: 800px"></div>
</div>
<script>var gridsquaremap = true;</script>