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
                            <label for="globalSearch">OQRS Global text</label>
                            <p>This text is an optional text that can be displayed on top of the OQRS page.</p>
                            <input type="text" name="global_oqrs_text" class="form-control" id="global_oqrs_text" aria-describedby="global_oqrs_text" value="<?php echo $this->optionslib->get_option('global_oqrs_text'); ?>">
                        </div>

                        <!-- Save the Form -->
                        <input class="btn btn-primary" type="submit" value="Save" />
                    </form>
                </div>
            </div>
		</div>
	</div>

</div>