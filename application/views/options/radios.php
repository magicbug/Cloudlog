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
                    <?php if($this->session->flashdata('success')) { ?>
                        <!-- Display Success Message -->
                        <div class="alert alert-success">
                        <?php echo $this->session->flashdata('success'); ?>
                        </div>
                    <?php } ?>

                    <?php if($this->session->flashdata('message')) { ?>
                        <!-- Display Message -->
                        <div class="alert-message error">
                        <?php echo $this->session->flashdata('message'); ?>
                        </div>
                    <?php } ?>

                    <?php if(validation_errors()) { ?>
                    <div class="alert alert-danger">
                        <a class="btn-close" data-bs-dismiss="alert">x</a>
                        <?php echo validation_errors(); ?>
                    </div>
                    <?php } ?>

                    <?php echo form_open('options/radio_save'); ?>

                        <div class="mb-3">
                            <label for="globalSearch"><?php echo lang('options_radio_timeout_warning'); ?></label>
                            <p><?php echo lang('options_the_radio_timeout_warning_is_used_on_the_qso_entry_panel_to_alert_you_to_radio_interface_disconnects'); ?></p>
                            <input type="text" name="radioTimeout" class="form-control" id="radioTimeout" aria-describedby="radioTimeoutHelp" value="<?php echo $this->optionslib->get_option('cat_timeout_interval'); ?>">
                            <small id="radioTimeoutHelp" class="form-text text-muted"><?php echo lang('options_this_number_is_in_seconds'); ?></small>
                        </div>

                        <!-- Save the Form -->
                        <input class="btn btn-primary" type="submit" value="<?php echo lang('options_save'); ?>" />
                    </form>
                </div>
            </div>
		</div>
	</div>

</div>