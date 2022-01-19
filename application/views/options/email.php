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

                    <?php echo form_open('options/email_save'); ?>

                        <div class="form-group">
                            <label for="emailProtocol">Outgoing Protocol</label>
                            <select name="emailProtocol" class="form-control" id="emailProtocol">
                                <option value="sendmail" <?php if($this->optionslib->get_option('emailProtocol')== "sendmail") { echo "selected=\"selected\""; } ?>>Sendmail</option>
                                <option value="smtp" <?php if($this->optionslib->get_option('emailProtocol')== "smtp") { echo "selected=\"selected\""; } ?>>SMTP</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="smtpEncryption">SMTP Encryption</label>
                            <select name="smtpEncryption" class="form-control" id="smtpEncryption">
                                <option value="" <?php if($this->optionslib->get_option('smtpEncryption') == "") { echo "selected=\"selected\""; } ?>>None</option>
                                <option value="tls" <?php if($this->optionslib->get_option('smtpEncryption') == "tls") { echo "selected=\"selected\""; } ?>>TLS</option>
                                <option value="ssl" <?php if($this->optionslib->get_option('smtpEncryption') == "ssl") { echo "selected=\"selected\""; } ?>>SSL</option>
                            </select>
                        </div>


                        <div class="form-group row">
                            <label for="smtpHost" class="col-sm-2 col-form-label">SMTP Host</label>
                            <div class="col-sm-10">
                                <input type="text" name="smtpHost" class="form-control" id="smtpHost" value="<?php if($this->optionslib->get_option('smtpHost') != "") { echo $this->optionslib->get_option('smtpHost'); } ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="smtpPort" class="col-sm-2 col-form-label">SMTP Port</label>
                            <div class="col-sm-10">
                                <input type="number" name="smtpPort" class="form-control" id="smtpPort" value="<?php if($this->optionslib->get_option('smtpPort') != "") { echo $this->optionslib->get_option('smtpPort'); } ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="smtpUsername" class="col-sm-2 col-form-label">SMTP Username</label>
                            <div class="col-sm-10">
                                <input type="text" name="smtpUsername" class="form-control" id="smtpUsername" value="<?php if($this->optionslib->get_option('smtpUsername') != "") { echo $this->optionslib->get_option('smtpUsername'); } ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="smtpPassword" class="col-sm-2 col-form-label">SMTP Password</label>
                            <div class="col-sm-10">
                                <input type="password" name="smtpPassword" class="form-control" id="smtpPassword"  value="<?php if($this->optionslib->get_option('smtpPassword') != "") { echo $this->optionslib->get_option('smtpPassword'); } ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="emailcrlf" class="col-sm-2 col-form-label">crlf</label>
                            <div class="col-sm-10">
                                <input type="text" name="emailcrlf" class="form-control" id="emailcrlf" value="<?php if($this->optionslib->get_option('emailcrlf') != "") { echo $this->optionslib->get_option('emailcrlf'); } ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="emailnewline" class="col-sm-2 col-form-label">newline</label>
                            <div class="col-sm-10">
                                <input type="text" name="emailnewline" class="form-control" id="emailnewline" value="<?php if($this->optionslib->get_option('emailnewline') != "") { echo $this->optionslib->get_option('emailnewline'); } ?>">
                            </div>
                        </div>

                        <!-- Save the Form -->
                        <input class="btn btn-primary" type="submit" value="Save" />
                    </form>
                </div>
            </div>
		</div>
	</div>

</div>