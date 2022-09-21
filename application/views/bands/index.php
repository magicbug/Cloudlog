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
		
    <table style="width:100%" class="bandtable table table-sm table-striped">
			<thead>
				<tr>
					<th></th>
					<th>Band</th>
					<th>CQ</th>
                    <th>DOK</th>
                    <th>DXCC</th>
                    <th>IOTA</th>
					<th>SIG</th>
                    <th>SOTA</th>
                    <th>US Counties</th>
                    <th>VUCC</th>
                    <th>WAS</th>
					<th>WWFF</th>
					<th>Bandgroup</th>
					<th>SSB QRG</th>
					<th>DATA QRG</th>
					<th>CW QRG</th>
                    <th></th>
                    <th></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($bands as $band) { ?>
				<tr>
                    <td class='band_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->active == 1) {echo 'checked';}?>></td>
					<td><?php echo $band->band;?></td>
					<td class='cq_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->cq == 1) {echo 'checked';}?>></td>
                    <td class='dok_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->dok == 1) {echo 'checked';}?>></td>
                    <td class='dxcc_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->dxcc == 1) {echo 'checked';}?>></td>
                    <td class='iota_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->iota == 1) {echo 'checked';}?>></td>
                    <td class='sig_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->sig == 1) {echo 'checked';}?>></td>
                    <td class='sota_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->sota == 1) {echo 'checked';}?>></td>
                    <td class='uscounties_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->uscounties == 1) {echo 'checked';}?>></td>
                    <td class='vucc_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->vucc == 1) {echo 'checked';}?>></td>
                    <td class='was_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->was == 1) {echo 'checked';}?>></td>
					<td class='wwff_<?php echo $band->id ?>'><input type="checkbox" <?php if ($band->wwff == 1) {echo 'checked';}?>></td>
					<td><?php echo $band->bandgroup;?></td>
					<td><?php echo $band->ssb;?></td>
					<td><?php echo $band->data;?></td>
					<td><?php echo $band->cw;?></td>
					<td>
						<a href="javascript:editBandDialog('<?php echo $band->bandid ?>');" class="btn btn-outline-primary btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
					</td>
					<td>
						<a href="javascript:deleteBand('<?php echo $band->id . '\',\'' . $band->band ?>');" class="btn btn-danger btn-sm" title="Delete"><i class="fas fa-trash-alt"></i></a>
                    </td>
				</tr>

				<?php } ?>
			</tbody>
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
