<div class="container settings">

	<div class="row">
		<!-- Nav Start -->
		<?php $this->load->view('options/sidebar') ?>
		<!-- Nav End -->

		<!-- Content -->
		<div class="col-md-9">
            <div class="card">
                <div class="card-header"><h2><i class="fas fa-map"></i> <?php echo $page_title; ?> - <?php echo $sub_heading; ?></h2></div>

                <div class="card-body">
                    <?php if($this->session->flashdata('success')) { ?>
                        <div class="alert alert-success">
                            <?php echo $this->session->flashdata('success'); ?>
                        </div>
                    <?php } ?>

                    <?php echo form_open('options/public_map_show_confirmations_save'); ?>

                    <div class="mb-3">
                        <label for="publicMapShowConfirmations"><?php echo lang('options_public_map_show_confirmations_enabled'); ?></label>
                        <select class="form-select" id="publicMapShowConfirmations" name="public_map_show_confirmations" aria-describedby="publicMapShowConfirmationsHelp" required>
                            <option value='1' <?php if($this->optionslib->get_option('public_map_show_confirmations') == "1" || $this->optionslib->get_option('public_map_show_confirmations') == "true") { echo "selected=\"selected\""; } ?>><?php echo lang('options_enabled'); ?></option>
                            <option value='0' <?php if($this->optionslib->get_option('public_map_show_confirmations') != "1" && $this->optionslib->get_option('public_map_show_confirmations') != "true") { echo "selected=\"selected\""; } ?>><?php echo lang('options_disabled'); ?></option>
                        </select>
                        <small id="publicMapShowConfirmationsHelp" class="form-text text-muted"><?php echo lang('options_public_map_show_confirmations_hint'); ?></small>
                    </div>

                    <input class="btn btn-primary" type="submit" value="<?php echo lang('options_save'); ?>" />
                    </form>
                </div>
            </div>
		</div>
	</div>

</div>
