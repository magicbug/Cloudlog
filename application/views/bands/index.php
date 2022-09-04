<div class="container">

<br>
	<?php if($this->session->flashdata('message')) { ?>
		<!-- Display Message -->
		<div class="alert-message error">
		  <p><?php echo $this->session->flashdata('message'); ?></p>
		</div>
	<?php } ?>

<h2><?php echo $page_title; ?></h2>

<div class="card">
  <div class="card-header">
    Bands
  </div>
  <div class="card-body">
    <p class="card-text">
		Using the band list you can control which bands are shown when creating a new QSO.
	</p>
    <p class="card-text">
		Active bands will be shown in the QSO "Band" drop-down, while inactive bands will be hidden and cannot be selected.
	</p>
    <div class="table-responsive">
		
    <table style="width:100%" class="modetable table table-striped">
			<thead>
				<tr>
					<th class="select-filter" scope="col">Band</th>
					<th class="select-filter" scope="col">Status</th>
					<th scope="col">CQ</th>
                    <th scope="col">DOK</th>
                    <th scope="col">DXCC</th>
                    <th scope="col">IOTA</th>
					<th scope="col">SIG</th>
                    <th scope="col">SOTA</th>
                    <th scope="col">US Counties</th>
                    <th scope="col">WAS</th>
                    <th scope="col">VUCC</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>

				</tr>
			</thead>
			<tbody>
				<?php foreach ($bands as $band) { ?>
				<tr>
					<td><?php echo $band->band?></td>
                    <td><?php if ($band->active == 1) {echo 'Active';} else {echo 'Not Active';}; ?></td>
					<td><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="customCheck1" <?php if ($band->cq == 1) {echo 'checked';}?>><label class="custom-control-label" for="customCheck1"></label></div></td>
                    <td><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="customCheck1" <?php if ($band->dok == 1) {echo 'checked';}?>><label class="custom-control-label" for="customCheck1"></label></div></td>
                    <td><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="customCheck1" <?php if ($band->dxcc == 1) {echo 'checked';}?>><label class="custom-control-label" for="customCheck1"></label></div></td>
                    <td><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="customCheck1" <?php if ($band->iota == 1) {echo 'checked';}?>><label class="custom-control-label" for="customCheck1"></label></div></td>
                    <td><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="customCheck1" <?php if ($band->sig == 1) {echo 'checked';}?>><label class="custom-control-label" for="customCheck1"></label></div></td>
                    <td><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="customCheck1" <?php if ($band->sota == 1) {echo 'checked';}?>><label class="custom-control-label" for="customCheck1"></label></div></td>
                    <td><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="customCheck1" <?php if ($band->uscounties == 1) {echo 'checked';}?>><label class="custom-control-label" for="customCheck1"></label></div></td>
                    <td><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="customCheck1" <?php if ($band->was == 1) {echo 'checked';}?>><label class="custom-control-label" for="customCheck1"></label></div></td>
                    <td><div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" id="customCheck1" <?php if ($band->vucc == 1) {echo 'checked';}?>><label class="custom-control-label" for="customCheck1"></label></div></td>
                    <td style="text-align: center">
                        <button onclick='javascript:deactivateMode()' class=' btn btn-secondary btn-sm'>Deactivate</button>
                    </td>
					<td>
						<a href="<?php echo site_url('mode/edit')?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i> Edit</a>
					</td>
					<td>
						<a href="javascript:deleteMode('');" class="btn btn-danger btn-sm" ><i class="fas fa-trash-alt"></i> Delete</a>
                    </td>
				</tr>

				<?php } ?>
			</tbody>
			<tfoot>
				<tr>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
					<th></th>
				</tr>
			</tfoot>
		<table>
	</div>
  <br/>
  <p>
	  	<button onclick="createBandDialog();" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Create a Band</button>
  		<button onclick="activateAllBands();" class="btn btn-primary btn-sm">Activate All</button>
		<button onclick="deactivateAllBands();" class="btn btn-primary btn-sm">Deactivate All </button>
	</p>
</div>
</div>
