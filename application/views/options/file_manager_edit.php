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
					<div class="bootstrap-dialog-message"></div>
					<form onsubmit="return false;">
						<div class="form-group">
							<label for="fm_name">File Manager Name</label>
							<input id="fm_name" name="name" type="text" class="form-control" value="<?php echo file_manager_get_key("name", $file_manager) ?>"/>
						</div>
						<div class="form-group">
							<label for="fm_driver">Driver</label>
							<select class="custom-select" id="fm_driver" name="driver" required>
								<option value="local" <?php if (file_manager_get_key("driver", $file_manager) == "local") echo 'selected="selected"' ?>>Local</option>
								<option value="aws_s3" <?php if (file_manager_get_key("driver", $file_manager) == "aws_s3") echo 'selected="selected"' ?>>S3-like object storage</option>
							</select>
						</div>
						<div class="form-group">
							<label for="fm_url_prefix">URL Prefix</label>
							<input id="fm_url_prefix" name="url_prefix" type="text" class="form-control" value="<?php echo file_manager_get_key("url_prefix", $file_manager) ?>"/>
							<small id="url-prefix-help" class="form-text text-muted">All new file uploaded to this file manager will add this url prefix automatically. Please make sure the file can be public access via this url prefix.</small>
						</div>
						<div class="form-group" id="form-group-akid">
							<label for="access_key_id">Access Key ID</label>
							<input id="fm_access_key_id" type="text" class="form-control" value=""/>
							<small id="access-key-id-help" class="form-text text-muted">Leave blank to keep existing access key ID.</small>
						</div>
						<div class="form-group" id="form-group-aksec">
							<label for="access_key_secret">Access Key Secret</label>
							<input id="fm_access_key_secret" type="text" class="form-control" value=""/>
							<small id="access-key-secret-help" class="form-text text-muted">Leave blank to keep existing access key secrete.</small>
						</div>
						<div class="form-group" id="form-group-region">
							<label for="region">Region</label>
							<input id="fm_region" type="text" class="form-control" value="<?php echo file_manager_get_key("region", $file_manager) ?>"/>
						</div>
						<div class="form-group" id="form-group-bucket">
							<label for="bucket_name">Bucket Name (Optional)</label>
							<input id="fm_bucket_name" type="text" class="form-control" value="<?php echo file_manager_get_key("bucket_name", $file_manager) ?>"/>
						</div>
						<div class="form-group" id="form-group-hostname">
							<label for="hostname">Hostname</label>
							<input id="fm_hostname" type="text" class="form-control" value="<?php echo file_manager_get_key("hostname", $file_manager) ?>"/>
						</div>
						<div class="form-group" id="form-group-dir">
							<label for="dir_path">Local Dir Path</label>
							<input id="fm_dir_path" type="text" class="form-control" value="<?php echo file_manager_get_key("dir_path", $file_manager) ?>"/>
							<small id="dir-path-help" class="form-text text-muted">Absolute or relative (to cloudlog root path) path of local dir.</small>
						</div>
						<input class="btn btn-primary" type="submit" value="Save" onclick="fm_do_save()"/>
					</form>
				</div>

            </div>
		</div>
	</div>

</div>
