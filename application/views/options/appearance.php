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

                    <?php echo form_open('options/appearance_save'); ?>
                        <!-- <div class="mb-3">
                            <label for="themeSelect">Language</label>
                            <select class="form-select" id="langSelect" name="language" aria-describedby="langHelp" required>
                                <?php foreach ($language_options as &$lang_opt) { ?>
                                    <option value='<?php echo $lang_opt; ?>' <?php if($this->optionslib->get_option('language')== $lang_opt) { echo "selected=\"selected\""; } ?>><?php echo ucfirst($lang_opt); ?></option>
                                <?php } ?>
                            </select>
                            <small id="langHelp" class="form-text text-muted">Select the default language for Cloudlog.</small>
                        </div> -->

                        <!-- Form options for selecting global theme choice -->
                        <div class="mb-3">
                            <label for="themeSelect"><?php echo lang('options_theme'); ?></label>
                            <select class="form-select" id="themeSelect" name="theme" aria-describedby="themeHelp" required>
								<?php
								foreach ($themes as $theme) {
									echo '<option value="' . $theme->foldername . '"';
									if ($this->optionslib->get_option('option_theme') == $theme->foldername) {
										echo 'selected="selected"';
									}
									echo '>' . $theme->name . '</option>';
								}
								?>
                                </select>
                            <small id="themeHelp" class="form-text text-muted"><?php echo lang('options_global_theme_choice_this_is_used_when_users_arent_logged_in'); ?></small>
                        </div>
                        
                        
                            <select class="form-select" id="globalSearch" name="globalSearch" style="display: none;">
                                <option value='true' <?php if($this->optionslib->get_option('global_search') == "true") { echo "selected=\"selected\""; } ?>>Enabled</option>
                                <option value='false' <?php if($this->optionslib->get_option('global_search') == "false") { echo "selected=\"selected\""; } ?>>Disabled</option>
                            </select>

                        <div class="mb-3">
                            <label for="dashboardBanner"><?php echo lang('options_dashboard_notification_banner'); ?></label>
                            <select class="form-select" id="dashboardBanner" name="dashboardBanner" aria-describedby="dashboardBannerHelp" required>
                                <option value='true' <?php if($this->optionslib->get_option('dashboard_banner') == "true") { echo "selected=\"selected\""; } ?>>Enabled</option>
                                <option value='false' <?php if($this->optionslib->get_option('dashboard_banner') == "false") { echo "selected=\"selected\""; } ?>>Disabled</option>
                            </select>
                            <small id="dashboardBannerHelp" class="form-text text-muted"><?php echo lang('options_this_allows_to_disable_the_global_notification_banner_on_the_dashboard'); ?></small>
                        </div>

                        <div class="mb-3">
                            <label for="dashboardMap"><?php echo lang('options_dashboard_map'); ?></label>
                            <select class="form-select" id="dashboardMap" name="dashboardMap" aria-describedby="dashboardMapHelp" required>
                                <option value='true' <?php if($this->optionslib->get_option('dashboard_map') == "true") { echo "selected=\"selected\""; } ?>>Enabled</option>
                                <option value='false' <?php if($this->optionslib->get_option('dashboard_map') == "false") { echo "selected=\"selected\""; } ?>>Disabled</option>
                                <option value='map_at_right' <?php if($this->optionslib->get_option('dashboard_map') == "map_at_right") { echo "selected=\"selected\""; } ?>>Map at right</option>
                            </select>
                            <small id="dashboardMapHelp" class="form-text text-muted"><?php echo lang('options_this_allows_the_map_on_the_dashboard_to_be_disabled_or_placed_on_the_right'); ?></small>
                        </div>

                        <div class="mb-3">
                            <label for="logbookMap"><?php echo lang('options_logbook_map'); ?></label>
                            <select class="form-select" id="logbookMap" name="logbookMap" aria-describedby="logbookMapHelp" required>
                                <option value='true' <?php if($this->optionslib->get_option('logbook_map') == "true") { echo "selected=\"selected\""; } ?>>Enabled</option>
                                <option value='false' <?php if($this->optionslib->get_option('logbook_map') == "false") { echo "selected=\"selected\""; } ?>>Disabled</option>
                            </select>
                            <small id="logbookMapHelp" class="form-text text-muted"><?php echo lang('options_this_allows_to_disable_the_map_in_the_logbook'); ?></small>
                        </div>

                        <!-- Save the Form -->
                        <input class="btn btn-primary" type="submit" value="<?php echo lang('options_save'); ?>" />
                    </form>
                </div>
            </div>
		</div>
	</div>

</div>
