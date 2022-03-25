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

                    <?php echo form_open('options/appearance_save'); ?>
                        <!-- <div class="form-group">
                            <label for="themeSelect">Language</label>
                            <select class="custom-select" id="langSelect" name="language" aria-describedby="langHelp" required>
                                <?php foreach ($language_options as &$lang_opt) { ?>
                                    <option value='<?php echo $lang_opt; ?>' <?php if($this->optionslib->get_option('language')== $lang_opt) { echo "selected=\"selected\""; } ?>><?php echo ucfirst($lang_opt); ?></option>
                                <?php } ?>
                            </select>
                            <small id="langHelp" class="form-text text-muted">Select the default language for Cloudlog.</small>
                        </div> -->

                        <!-- Form options for selecting global theme choice -->
                        <div class="form-group">
                            <label for="themeSelect">Theme</label>
                            <select class="custom-select" id="themeSelect" name="theme" aria-describedby="themeHelp" required>
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
                            <small id="themeHelp" class="form-text text-muted">Global Theme Choice, this is used when users arent logged in.</small>
                        </div>

                        <div class="form-group">
                            <label for="globalSearch">Public Search Bar</label>
                            <select class="custom-select" id="globalSearch" name="globalSearch" aria-describedby="globalSearchHelp" required>
                                <option value='true' <?php if($this->optionslib->get_option('global_search') == "true") { echo "selected=\"selected\""; } ?>>Enabled</option>
                                <option value='false' <?php if($this->optionslib->get_option('global_search') == "false") { echo "selected=\"selected\""; } ?>>Disabled</option>
                            </select>
                            <small id="globalSearchHelp" class="form-text text-muted">This allows non logged in users to access the search functions.</small>
                        </div>

                        <!-- Save the Form -->
                        <input class="btn btn-primary" type="submit" value="Save" />
                    </form>
                </div>
            </div>
		</div>
	</div>

</div>
