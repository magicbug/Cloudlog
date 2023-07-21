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
                        <a class="close" data-dismiss="alert">x</a>
                        <?php echo validation_errors(); ?>
                    </div>
                    <?php } ?>

                    <?php echo form_open('options/dxcluster_save'); ?>

                        <div class="form-group">
                            <label for="globalSearch"><?php echo lang('options_dxcluster_provider'); ?></label>
                            <p><?php echo lang('options_dxcluster_longtext'); ?></p>
                            <input type="text" name="dxcache_url" class="form-control" id="dxcache_url" aria-describedby="dxcache_urlHelp" value="<?php echo $this->optionslib->get_option('dxcache_url'); ?>">
                            <small id="dxcache_urlHelp" class="form-text text-muted"><?php echo lang('options_dxcluster_hint'); ?></small>
                        </div>
                        <div class="form-group">
                            <label for="maxAgeSelect"><?php echo lang('options_dxcluster_maxage'); ?></label>
                            <select class="custom-select" id="maxAgeSelect" name="dxcluster_maxage" aria-describedby="dxcluster_maxageHelp" required>
				<option value="120"<?php if ($this->optionslib->get_option('dxcluster_maxage') == '120') { echo " selected"; } ?>>2 Hours</option>
				<option value="60"<?php if ($this->optionslib->get_option('dxcluster_maxage') == '60') { echo " selected"; } ?>>60 Minutes</option>
				<option value="30"<?php if ($this->optionslib->get_option('dxcluster_maxage') == '30') { echo " selected"; } ?>>30 Minutes</option>
                                </select>
                            <small id="dxcluster_maxageHelp" class="form-text text-muted"><?php echo lang('options_dxcluster_maxage_hint'); ?></small>
                        </div>
			<div class="form-group">
                            <label for="decontSelect"><?php echo lang('options_dxcluster_decont'); ?></label>
                            <select class="custom-select" id="decontSelect" name="dxcluster_decont" aria-describedby="dxcluster_decontHelp" required>
				<option value="AF"<?php if ($this->optionslib->get_option('dxcluster_decont') == 'AF') { echo " selected"; } ?>>Africa</option>
				<option value="AN"<?php if ($this->optionslib->get_option('dxcluster_decont') == 'AN') { echo " selected"; } ?>>Antarctica</option>
				<option value="AS"<?php if ($this->optionslib->get_option('dxcluster_decont') == 'AS') { echo " selected"; } ?>>Asia</option>
				<option value="EU"<?php if ($this->optionslib->get_option('dxcluster_decont') == 'EU') { echo " selected"; } ?>>Europe</option>
				<option value="NA"<?php if ($this->optionslib->get_option('dxcluster_decont') == 'NA') { echo " selected"; } ?>>North America</option>
				<option value="OC"<?php if ($this->optionslib->get_option('dxcluster_decont') == 'OC') { echo " selected"; } ?>>Oceania</option>
				<option value="SA"<?php if ($this->optionslib->get_option('dxcluster_decont') == 'SA') { echo " selected"; } ?>>South America</option>
                                </select>
                            <small id="dxcluster_decontHelp" class="form-text text-muted"><?php echo lang('options_dxcluster_decont_hint'); ?></small>
                        </div>
 
                        <!-- Save the Form -->
                        <input class="btn btn-primary" type="submit" value="<?php echo lang('options_save'); ?>" />
                    </form>
                </div>
            </div>
		</div>
	</div>

</div>
