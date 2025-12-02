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
                        <div class="alert alert-success">
                            <?php echo $this->session->flashdata('success'); ?>
                        </div>
                    <?php } ?>

                    <?php echo form_open('options/registration_save'); ?>

                    <div class="mb-3">
                        <label for="openRegistration"><?php echo lang('options_open_registration'); ?></label>
                        <select class="form-select" id="openRegistration" name="open_registration" aria-describedby="openRegistrationHelp" required>
                            <option value='true' <?php if($this->optionslib->get_option('open_registration') == "true") { echo "selected=\"selected\""; } ?>>Enabled</option>
                            <option value='false' <?php if($this->optionslib->get_option('open_registration') != "true") { echo "selected=\"selected\""; } ?>>Disabled</option>
                        </select>
                        <small id="openRegistrationHelp" class="form-text text-muted"><?php echo lang('options_open_registration_hint'); ?></small>
                    </div>

                    <input class="btn btn-primary" type="submit" value="<?php echo lang('options_save'); ?>" />
                    </form>
                </div>
            </div>
		</div>
	</div>

</div>
