<div class="container lotw">

  <h2><?php echo $this->lang->line('lotw_title'); ?> - <?php echo $this->lang->line('lotw_title_adif_import'); ?></h2>
  
<div class="card">
	<div class="card-header"><?php echo $this->lang->line('lotw_title_adif_import_options'); ?></div>
  <div class="card-body">

    <?php $this->load->view('layout/messages'); ?>

    <?php echo form_open_multipart('lotw/import');?>

    <div class="form-check">
      <input type="radio" id="lotwimport" name="lotwimport" class="form-check-input">
      <label class="form-check-label" for="lotwimport"><?php echo $this->lang->line('lotw_input_a_file'); ?></label>

      <p><?php echo $this->lang->line('lotw_upload_exported_adif_file_from_lotw'); ?></p>
      <p><span class="badge badge-info"><?php echo $this->lang->line('general_word_important'); ?></span> <?php echo $this->lang->line('lotw_upload_type_must_be_adi'); ?></p>
      
      <div class="custom-file">
          <input type="file" class="custom-file-input" id="adiffile" name="userfile" size="20" />
        <label class="custom-file-label" for="adiffile"><?php echo $this->lang->line('general_word_choose_file'); ?></label>
      </div>

    </div>


		<br><br>

		<div class="custom-control custom-radio">
			<input type="radio" name="lotwimport" id="fetch" class="custom-control-input" value="fetch" checked="checked" />
			<label class="custom-control-label" for="fetch"><?php echo $this->lang->line('lotw_pull_lotw_data_for_me'); ?></label>
		</div>
      <p class="card-text"><?php echo $this->lang->line('gen_from_date'); ?>:</p>
      <div class="row">
          <div class="input-group date col-md-3" id="datetimepicker1" data-target-input="nearest">
              <input name="from" type="text" placeholder="DD/MM/YYYY" class="form-control datetimepicker-input" data-target="#datetimepicker1"/>
              <div class="input-group-append"  data-target="#datetimepicker1" data-toggle="datetimepicker">
                  <div class="input-group-text"><i class="fa fa-calendar"></i></div>
              </div>
          </div>
      </div>
      <br/>
      <div class="form-group row" style="display: none">
          <div class="col-md-10">
              <div class="form-check-inline">
                  <input class="form-check-input" type="checkbox" name="importMissing" value="1" id="importMissing">
                  <label class="form-check-label" for="importMissing"><?php echo $this->lang->line('lotw_import_missing_qsos_text'); ?></label>
              </div>
          </div>
      </div>

		<p class="form-text text-muted"><?php echo $this->lang->line('lotw_report_download_overview_helptext'); ?></p>

		<input class="btn btn-primary" type="submit" value="<?php echo $this->lang->line('lotw_btn_import_matches'); ?>" />

	</form>
  </div>
</div>

</div>
