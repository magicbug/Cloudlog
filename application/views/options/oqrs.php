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

                    <?php echo form_open('options/oqrs_save'); ?>

                        <div class="form-group">
                            <label for="globalSearch">Global text</label>
                            <input type="text" name="global_oqrs_text" class="form-control" id="global_oqrs_text" aria-describedby="global_oqrs_text" value="<?php echo $this->optionslib->get_option('global_oqrs_text'); ?>">
                            <small id="global_oqrs_text_help" class="form-text text-muted">This text is an optional text that can be displayed on top of the OQRS page.</small>
                        </div>

                        <div class="form-group">
                            <label for="groupedSearch">Grouped search</label>
                            <select name="groupedSearch" class="form-control" id="groupedSearch">
                                <option value="off" <?php if($this->optionslib->get_option('groupedSearch') == "off") { echo "selected=\"selected\""; } ?>>Off</option>
                                <option value="on" <?php if($this->optionslib->get_option('groupedSearch') == "on") { echo "selected=\"selected\""; } ?>>On</option>
                            </select>
                            <small id="groupedSearchHelp" class="form-text text-muted">When this is on, all station locations with OQRS active, will be searched at once.</small>
                        </div>

                        <!-- Save the Form -->
                        <input class="btn btn-primary" type="submit" value="Save" />
                    </form>
                </div>
            </div>
		</div>
	</div>

</div>