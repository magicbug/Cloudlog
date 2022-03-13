<div class="container settings">

	<div class="row">
		<!-- Nav Start -->
		<?php $this->load->view('options/sidebar') ?>
		<!-- Nav End -->

		<!-- Content -->
		<div class="col-md-9">
            <div class="card">
                <div class="card-header"><h2><?php echo $page_title; ?> - <?php echo $sub_heading; ?></h2></div>

                <div class="card-body">
					<ul class="nav nav-pills option-nav" id="mgrTab" role="tablist">
						<li class="nav-item" role="presentation">
							<a class="nav-link active" id="list-tab" data-toggle="tab" href="#list" role="tab" aria-controls="list" aria-selected="true">File Manager List</a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link" id="default-mgr-tab" data-toggle="tab" href="#default-mgr" role="tab" aria-controls="default-mgr" aria-selected="false">Default File Manager</a>
						</li>
					</ul>
					<div class="tab-content" id="mgrTabContent">
						<div class="tab-pane fade show active" id="list" role="tabpanel" aria-labelledby="list-tab">
							<table class="table">
								<tr>
									<th>Name</th>
									<th>Driver</th>
									<th>Operation</th>
								</tr>
								<?php foreach($file_managers as $fm) { ?>
								<tr>
									<td><?php echo $fm["name"] ?></td>
									<td><?php switch($fm["driver"])
										{
											case "local":
												echo "Local";
												break;
											case "aws_s3":
												echo "S3-like object storage";
												break;
											default:
												echo "Unknown";
												break;
										} ?>
									</td>
									<td>
										<a href="#" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i> Edit</a>
										<a href="#" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Remove</a>
									</td>
								</tr>
								<?php } ?>
								<tr class="file-manager-add-new" onclick="switch_to_file_manager_add()">
									<td colspan="3">
										<div style="text-align: center">
											<i class="fas fa-plus"></i>
											Add New
										</div>
									</td>
								</tr>
							</table>
						</div>
						<div class="tab-pane fade" id="default-mgr" role="tabpanel" aria-labelledby="default-mgr">
							<p>Cloudlog allows you to use different File Manager to store different kind of static files, such as QSL card.</p>
							<p>You can use a local File Manager to store the files locally, or use a cloud File Manager as AWS S3 to store them on cloud providers.</p>
							<form>
								<div class="form-group">
									<label for="qslSelect">QSL Card Default File Manager</label>
									<select class="custom-select" id="qslSelect" name="qsl" aria-describedby="qslHelp" required>
										<?php foreach($file_managers as $fm) { ?>
										<option <?php if ($fm["id"] == $qsl_default_filemgr) echo 'selected="selected"' ?> value="<?php echo $fm["id"] ?>"><?php echo $fm["name"] ?></option>
										<?php } ?>
									</select>
									<small id="qslHelp" class="form-text text-muted">Default File Manager to handle QSL card.</small>
								</div>
								<input class="btn btn-primary" type="submit" value="Save" />
							</form>
						</div>
					</div>
                </div>
            </div>
		</div>
	</div>

</div>
