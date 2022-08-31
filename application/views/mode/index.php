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
    Modes
  </div>
  <div class="card-body">
    <p class="card-text">
		Using the modes list you can control which modes are shown when creating a new QSO.
	</p>
    <p class="card-text">
		Active modes will be shown in the QSO "Mode" drop-down, while inactive modes will be hidden and cannot be selected.
	</p>
    <div class="table-responsive">
		<table style="width:100%" class="modetable table table-striped">
			<thead>
				<tr>
					<th class="select-filter" scope="col">Mode</th>
					<th class="select-filter" scope="col">Sub-Mode</th>
					<th class="select-filter" scope="col">SSB / DATA / CW</th>
					<th class="select-filter" scope="col">Status</th>
                    <th scope="col"></th>
					<th scope="col"></th>
					<th scope="col"></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($modes->result() as $row) { ?>
				<tr>
					<td><?php echo $row->mode;?></td>
					<td><?php echo $row->submode;?></td>
					<td><?php echo $row->qrgmode;?></td>
                    <td class='mode_<?php echo $row->id ?>'><?php if ($row->active == 1) { echo "active";} else { echo "not active";};?></td>
                    <td style="text-align: center">
                        <?php if ($row->active == 1) {
                            echo "<button onclick='javascript:deactivateMode(". $row->id . ")' class='btn_" . $row->id . " btn btn-secondary btn-sm'>Deactivate</button>";
                        } else {
                            echo "<button onclick='javascript:activateMode(". $row->id . ")' class='btn_" . $row->id . " btn btn-primary btn-sm'>Activate</button>";
                        };?>
                    </td>
					<td>
						<a href="<?php echo site_url('mode/edit')."/".$row->id; ?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i> Edit</a>
					</td>
					<td>
						<a href="javascript:deleteMode('<?php echo $row->id; ?>', '<?php echo $row->mode; ?>');" class="btn btn-danger btn-sm" ><i class="fas fa-trash-alt"></i> Delete</a>
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
	  	<button onclick="createModeDialog();" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Create a Mode</button>
  		<button onclick="activateAllModes();" class="btn btn-primary btn-sm">Activate All</button>
		<button onclick="deactivateAllModes();" class="btn btn-primary btn-sm">Deactivate All </button>
	</p>
</div>
</div>
