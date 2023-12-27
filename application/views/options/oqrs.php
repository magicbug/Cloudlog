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

                    <?php echo form_open('options/oqrs_save'); ?>

                        <div class="mb-3">
                            <label for="globalSearch"><?php echo lang('options_global_text'); ?></label>
                            <input type="text" name="global_oqrs_text" class="form-control" id="global_oqrs_text" aria-describedby="global_oqrs_text" value="<?php echo $this->optionslib->get_option('global_oqrs_text'); ?>">
                            <small id="global_oqrs_text_help" class="form-text text-muted"><?php echo lang('options_this_text_is_an_optional_text_that_can_be_displayed_on_top_of_the_oqrs_page'); ?></small>
                        </div>

                        <div class="mb-3">
                            <label for="groupedSearch"><?php echo lang('options_grouped_search'); ?></label>
                            <select name="groupedSearch" class="form-select" id="groupedSearch">
                                <option value="off" <?php if($this->optionslib->get_option('groupedSearch') == "off") { echo "selected=\"selected\""; } ?>>Off</option>
                                <option value="on" <?php if($this->optionslib->get_option('groupedSearch') == "on") { echo "selected=\"selected\""; } ?>>On</option>
                            </select>
                            <small id="groupedSearchHelp" class="form-text text-muted"><?php echo lang('options_when_this_is_on_all_station_locations_with_oqrs_active_will_be_searched_at_once'); ?></small>
                        </div>

                        <div class="mb-3">
                            <label for="groupedSearchShowStationName"><?php echo lang('options_grouped_search_show_station_name'); ?></label>
                            <select name="groupedSearchShowStationName" class="form-select" id="groupedSearchShowStationName">
                                <option value="off" <?php if($this->optionslib->get_option('groupedSearchShowStationName') == "off") { echo "selected=\"selected\""; } ?>>Off</option>
                                <option value="on" <?php if($this->optionslib->get_option('groupedSearchShowStationName') == "on") { echo "selected=\"selected\""; } ?>>On</option>
                            </select>
                            <small id="groupedSearchShowStationNameHelp" class="form-text text-muted"><?php echo lang('options_grouped_search_show_station_name_hint'); ?></small>
                        </div>

                        <!-- Save the Form -->
                        <input class="btn btn-primary" type="submit" value="<?php echo lang('options_save'); ?>" />
                    </form>
                </div>
            </div>
		</div>
	</div>

</div>