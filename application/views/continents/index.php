<style>
#continentChart {
    margin: 0 auto;
}
</style>
<div class="container statistics">

    <h2>
        <?php echo $page_title; ?>
    </h2>

    <br>
    <div hidden class="tabs">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="continents-tab" data-toggle="tab" href="#continents" role="tab"
                    aria-controls="continents" aria-selected="true">No of QSOs</a>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="myTabContent">

        <div class="tab-pane fade active show" id="continents" role="tabpanel" aria-labelledby="continents-tab">
            <br />
            <form id="searchForm" name="searchForm" action="<?php echo base_url()."index.php/continents/get_continents";?>" method="post">
                <div class="form-row">

                    <div class="form-group col-lg-2">
                        <label class="form-label" for="band">Band</label>
                        <select id="band" name="band" class="form-control form-control-sm">
                            <option value="">All</option>
                            <?php foreach($bands as $band){ ?>
								<option value="<?php echo htmlentities($band);?>"><?php echo htmlspecialchars($band);?>	</option>
							<?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-lg-2">
                        <label class="form-label" for="mode">Mode</label>
                        <select id="mode" name="mode" class="form-control form-control-sm">
                            <option value="">All</option>
                            <?php foreach($modes as $modeId => $mode){ ?>
								<option value="<?php echo htmlspecialchars($mode);?>"><?php echo htmlspecialchars($mode);?></option>
							<?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-lg-2 col-md-3 col-sm-3 col-xl-21">
                        <label>&nbsp;</label><br>
                        <button type="submit" class="btn btn-sm btn-primary" id="searchButton">Search</button>
                        <button type="reset" class="btn btn-sm btn-danger" id="resetButton">Reset</button>
                    </div>
                </div>
			</form>
    <div style="display: flex;" id="continentContainer">
        <div style="flex: 1;"><canvas id="continentChart" width="500" height="500"></canvas></div>
        <div style="flex: 1;" id="continentTable">

            <table style="width:100%" class="continentstable table table-sm table-bordered table-hover table-striped table-condensed text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Continent</th>
                        <th># of QSO's worked</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>