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
			Contests
		</div>
		<div class="card-body">
			<p class="card-text">
				Using the contest list, you can control which Contests are shown when logging QSOs in a contest.
			</p>
			<p class="card-text">
				Active contests will be shown in the Contest Name drop-down, while inactive contests will be hidden and cannot be selected.
			</p>
			<div class="table-responsive">
				<table style="width:100%" class="contesttable table table-sm table-striped">
					<thead>
					<tr>
						<th scope="col">Name</th>
						<th scope="col">Adif mode</th>
						<th scope="col">Active</th>
						<th scope="col"></th>
						<th scope="col"></th>
						<th scope="col"></th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($contests as $row) { ?>
						<tr>
							<td><?php echo $row['name'];?></td>
							<td><?php echo $row['adifname'];?></td>
							<td class='contest_<?php echo $row['id'] ?>'><?php if ($row['active'] == 1) { echo "active";} else { echo "not active";};?></td>
							<td style="text-align: center">
								<?php if ($row['active'] == 1) {
									echo "<button onclick='javascript:deactivateContest(". $row['id'] . ")' class='btn_" . $row['id'] . " btn btn-secondary btn-sm'>Deactivate</button>";
								} else {
									echo "<button onclick='javascript:activateContest(". $row['id'] . ")' class='btn_" . $row['id'] . " btn btn-primary btn-sm'>Activate</button>";
								};?>
							</td>
							<td>
								<a href="<?php echo site_url('contesting/edit')."/".$row['id']; ?>" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i> Edit</a>
							</td>
							<td>
								<a href="javascript:deleteContest('<?php echo $row['id']; ?>', '<?php echo $row['name']; ?>');" class="btn btn-danger btn-sm" ><i class="fas fa-trash-alt"></i> Delete</a>
							</td>
						</tr>

					<?php } ?>
					</tbody>
					<table>
			</div>
			<br/>
			<p>
				<button onclick="createContestDialog();" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add a Contest</button>
				<button onclick="activateAllContests();" class="btn btn-primary btn-sm">Activate All</button>
				<button onclick="deactivateAllContests();" class="btn btn-primary btn-sm">Deactivate All </button>
			</p>
		</div>
	</div>
