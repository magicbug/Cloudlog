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
    <p class="card-text">This is the place you can customize your modes-list by activating/deactivating modes to be shown in the select-list.</p>
      <p><button onclick="createModeDialog();" class="btn btn-primary"><i class="fas fa-plus"></i> Create a Mode</button></p>
    <div class="table-responsive">
		<table style="width:100%" class="modetable table table-striped">
			<thead>
				<tr>
					<th scope="col">Mode</th>
					<th scope="col">Sub-Mode</th>
					<th scope="col">SSB/DATA/CW</th>
					<th scope="col">Active</th>
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
                    <td>
                        <?php if ($row->active == 1) {
                            echo "<button onclick='javascript:deactivateMode(". $row->id . ")' class='btn_" . $row->id . " btn btn-success btn-sm'><i class='fas fa-edit-alt'></i> Deactivate</button>";
                        } else {
                            echo "<button onclick='javascript:activateMode(". $row->id . ")' class='btn_" . $row->id . " btn btn-success btn-sm'><i class='fas fa-edit-alt'></i> Activate</button>";
                        };?>
                    </td>
					<td>
						<a href="<?php echo site_url('mode/edit')."/".$row->id; ?>" class="btn btn-info btn-sm"><i class="fas fa-edit-alt"></i> Edit</a>
					</td>
					<td>
						<a href="javascript:deleteMode('<?php echo $row->id; ?>', '<?php echo $row->mode; ?>');" class="btn btn-danger btn-sm" ><i class="fas fa-trash-alt"></i> Delete</a>
                    </td>
				</tr>

				<?php } ?>
			</tbody>
		<table>
	</table>
	

  </div>
</div>


</div>