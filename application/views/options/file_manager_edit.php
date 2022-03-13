<?php
function file_manager_get_key($key, $fm)
{
	if (array_key_exists($key, $fm))
		return $fm[$key];
	else return "";
}
?>
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
					<form>
						<div class="form-group">
							<label for="name">File Manager Name</label>
							<input type="text" class="form-control" value="<?php echo file_manager_get_key("name", $file_manager) ?>"/>
						</div>
						<div class="form-group">
							<label for="url-prefix">URL Prefix</label>
							<input type="text" class="form-control" value="<?php echo file_manager_get_key("url_prefix", $file_manager) ?>"/>
							<small id="qslHelp" class="form-text text-muted">All file uploaded to this file manager will add this url prefix automatically.</small>
						</div>
						<div class="form-group">
							<label for="driver">Driver</label>
							<select class="custom-select" id="driver" name="driver" required>
								<option value="local">Local</option>
								<option value="aws_s3">S3-like object storage</option>
							</select>
						</div>
						<div class="form-group" id="form-group-akid">
							<label for="access_key_id">Access Key ID</label>
							<input type="text" class="form-control" value=""/>
						</div>
						<div class="form-group" id="form-group-aksec">
							<label for="access_key_secret">Access Key Secret</label>
							<input type="text" class="form-control" value=""/>
						</div>
						<div class="form-group" id="form-group-region">
							<label for="region">Region</label>
							<input type="text" class="form-control" value="<?php echo file_manager_get_key("region", $file_manager) ?>"/>
						</div>
						<div class="form-group" id="form-group-bucket">
							<label for="bucket_name">Bucket Name</label>
							<input type="text" class="form-control" value="<?php echo file_manager_get_key("bucket_name", $file_manager) ?>"/>
						</div>
						<div class="form-group" id="form-group-hostname">
							<label for="hostname">Hostname</label>
							<input type="text" class="form-control" value="<?php echo file_manager_get_key("hostname", $file_manager) ?>"/>
						</div>
						<div class="form-group" id="form-group-dir">
							<label for="dir_path">Local Dir Path</label>
							<input type="text" class="form-control" value="<?php echo file_manager_get_key("dir_path", $file_manager) ?>"/>
						</div>
						<input class="btn btn-primary" type="submit" value="Save" />
					</form>
				</div>

            </div>
		</div>
	</div>

</div>
