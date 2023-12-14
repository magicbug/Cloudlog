<div class="container lotw">

  <h2><?php echo lang('lotw_title'); ?> - <?php echo lang('lotw_title_adif_import'); ?></h2>

  <?php if (isset($errormsg)) { ?>
    <div class="alert alert-danger" role="alert">
    <?php echo $errormsg; ?>
    </div>
  <?php } ?>

  <div class="card">
    <div class="card-header"><?php echo lang('lotw_title_adif_import_options'); ?></div>
    <div class="card-body">


      <?php $this->load->view('layout/messages'); ?>

      <?php echo form_open_multipart('lotw/import'); ?>

      <div class="form-check">
        <input type="radio" id="lotwimport" name="lotwimport" class="form-check-input">
        <label class="form-check-label" for="lotwimport"><?php echo lang('lotw_input_a_file'); ?></label>
        <br><br>
        <p><?php echo lang('lotw_upload_exported_adif_file_from_lotw'); ?></p>
        <p><span class="badge text-bg-info"><?php echo lang('general_word_important'); ?></span> <?php echo lang('lotw_upload_type_must_be_adi'); ?></p>

        <label class="visually-hidden" for="adiffile"><?php echo lang('general_word_choose_file'); ?></label>
        <input type="file" class="file-input mb-2 me-sm-2" id="adiffile" name="userfile" size="20" />
      </div>

      <br><br>

      <div>
        <div class="form-check">
          <input type="radio" name="lotwimport" id="fetch" class="form-check-input" value="fetch" checked="checked" />
          <label class="form-check-label" for="fetch"><?php echo lang('lotw_pull_lotw_data_for_me'); ?></label>
          <br><br>
          <p class="card-text"><?php echo lang('gen_from_date'); ?>:</p>
          <div class="row">
            <div class="col-md-3">
              <input name="from" id="from" type="date" class="form-control w-auto">
            </div>
          </div>
          <br />
          <div class="row">
            <div class="col-md-3">
              <label class="form-check-label" for="callsign"><?php echo lang('lotw_select_callsign'); ?></label>
              <?php
              $options = [];
              foreach ($callsigns->result() as $call) {
                $options[$call->callsign] = $call->callsign;
              }
              ksort($options);
              array_unshift($options, 'All');
              echo form_dropdown('callsign', $options, 'All');
              ?>
            </div>
          </div>
          <br />

          <p class="form-text text-muted"><?php echo lang('lotw_report_download_overview_helptext'); ?></p>
        </div>

        <input class="btn btn-primary" type="submit" value="<?php echo lang('lotw_btn_import_matches'); ?>" />

        </form>
      </div>
    </div>

  </div>
