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
                        <div class="alert alert-info">
                        <?php echo $this->session->flashdata('message'); ?>
                        </div>
                    <?php } ?>

                    <?php if($this->session->flashdata('testmailFailed')) { ?>
                        <!-- Display testmailFailed Message -->
                        <div class="alert alert-danger">
                            <?php echo $this->session->flashdata('testmailFailed'); ?>
                        </div>
                    <?php } ?>

                    <?php if($this->session->flashdata('testmailSuccess')) { ?>
                        <!-- Display testmailSuccess Message -->
                        <div class="alert alert-success">
                            <?php echo $this->session->flashdata('testmailSuccess'); ?>
                        </div>
                    <?php } ?>
                        
                    <?php echo form_open('options/email_save'); ?>

                        <div class="mb-3">
                            <label for="emailProtocol"><?php echo lang('options_outgoing_protocol'); ?></label>
                            <select name="emailProtocol" class="form-select" id="emailProtocol">
                                <option value="sendmail" <?php if($this->optionslib->get_option('emailProtocol')== "sendmail") { echo "selected=\"selected\""; } ?>>Sendmail</option>
                                <option value="smtp" <?php if($this->optionslib->get_option('emailProtocol')== "smtp") { echo "selected=\"selected\""; } ?>>SMTP</option>
                            </select>
                            <small class="form-text text-muted"><?php echo lang('options_outgoing_protocol_hint'); ?></small>
                        </div>

                        <div class="mb-3">
                            <label for="smtpEncryption"><?php echo lang('options_smtp_encryption'); ?></label>
                            <select name="smtpEncryption" class="form-select" id="smtpEncryption">
                                <option value="" <?php if($this->optionslib->get_option('smtpEncryption') == "") { echo "selected=\"selected\""; } ?>>None</option>
                                <option value="tls" <?php if($this->optionslib->get_option('smtpEncryption') == "tls") { echo "selected=\"selected\""; } ?>>TLS</option>
                                <option value="ssl" <?php if($this->optionslib->get_option('smtpEncryption') == "ssl") { echo "selected=\"selected\""; } ?>>SSL</option>
                            </select>
                            <small class="form-text text-muted"><?php echo lang('options_smtp_encryption_hint'); ?></small>
                        </div>

                        <div class="mb-3 row">
                        <label for="emailSenderName" class="col-sm-2 col-form-label"><?php echo lang('options_email_sender_name'); ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="emailSenderName" class="form-control" id="emailSenderName" value="<?php if($this->optionslib->get_option('emailSenderName') != "") { echo $this->optionslib->get_option('emailSenderName'); } ?>">
                                <small class="form-text text-muted"><?php echo lang('options_email_sender_name_hint'); ?></small>
                            </div>
                        </div>

                        <div class="mb-3 row">
                        <label for="emailAddress" class="col-sm-2 col-form-label"><?php echo lang('options_email_address'); ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="emailAddress" class="form-control" id="emailAddress" value="<?php if($this->optionslib->get_option('emailAddress') != "") { echo $this->optionslib->get_option('emailAddress'); } ?>">
                                <small class="form-text text-muted"><?php echo lang('options_email_address_hint'); ?></small>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="smtpHost" class="col-sm-2 col-form-label"><?php echo lang('options_smtp_host'); ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="smtpHost" class="form-control" id="smtpHost" value="<?php if($this->optionslib->get_option('smtpHost') != "") { echo $this->optionslib->get_option('smtpHost'); } ?>">
                                <small class="form-text text-muted"><?php echo lang('options_smtp_host_hint'); ?></small>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="smtpPort" class="col-sm-2 col-form-label"><?php echo lang('options_smtp_port'); ?></label>
                            <div class="col-sm-10">
                                <input type="number" name="smtpPort" class="form-control" id="smtpPort" value="<?php if($this->optionslib->get_option('smtpPort') != "") { echo $this->optionslib->get_option('smtpPort'); } ?>">
                                <small class="form-text text-muted"><?php echo lang('options_smtp_port_hint'); ?></small>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="smtpUsername" class="col-sm-2 col-form-label"><?php echo lang('options_smtp_username'); ?></label>
                            <div class="col-sm-10">
                                <input type="text" name="smtpUsername" class="form-control" id="smtpUsername" value="<?php if($this->optionslib->get_option('smtpUsername') != "") { echo $this->optionslib->get_option('smtpUsername'); } ?>">
                                <small class="form-text text-muted"><?php echo lang('options_smtp_username_hint'); ?></small>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="smtpPassword" class="col-sm-2 col-form-label"><?php echo lang('options_smtp_password'); ?></label>
                            <div class="col-sm-10">
                                <input type="password" name="smtpPassword" class="form-control" id="smtpPassword"  value="<?php if($this->optionslib->get_option('smtpPassword') != "") { echo $this->optionslib->get_option('smtpPassword'); } ?>">
                                <small class="form-text text-muted"><?php echo lang('options_smtp_password_hint'); ?></small>
                            </div>
                        </div>

                        <!-- Save the Form -->
                        <input class="btn btn-primary" type="submit" value="<?php echo lang('options_save'); ?>" />
                    </form>
                    <br>
                    <?php echo form_open('options/sendTestMail'); ?>
                        <input class="btn btn-primary" type="submit" value="<?php echo lang('options_send_testmail'); ?>" />
                        <small class="form-text text-muted"><?php echo lang('options_send_testmail_hint'); ?></small>
                    </form>
                </div>
            </div>
		</div>
	</div>

</div>