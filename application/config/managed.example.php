<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| Hosted Cloudlog overrides. Copy this file to application/config/managed.php.
| managed.php is not part of the git tree.
|
| Any email key set here replaces the same setting from the database, including
| values saved at index.php/options/email. Setting managed_email_protocol also
| locks that page so a hosted customer cannot change the mail transport.
| Leave a key out to keep using the value from the Email page.
|
| Amazon SES API:
|
| $config['managed_email_protocol'] = 'ses';
| $config['managed_email_address'] = 'noreply@example.com';
| $config['managed_email_sender_name'] = 'Cloudlog';
| $config['managed_email_ses_region'] = 'eu-west-1';
| $config['managed_email_ses_access_key'] = '';
| $config['managed_email_ses_secret_key'] = '';
|
| SMTP, including Amazon SES over SMTP:
|
| $config['managed_email_protocol'] = 'smtp';
| $config['managed_email_smtp_encryption'] = 'tls';
| $config['managed_email_address'] = 'noreply@example.com';
| $config['managed_email_sender_name'] = 'Cloudlog';
| $config['managed_email_smtp_host'] = 'email-smtp.eu-west-1.amazonaws.com';
| $config['managed_email_smtp_port'] = '587';
| $config['managed_email_smtp_username'] = '';
| $config['managed_email_smtp_password'] = '';
|
| $config['managed_service'] = true; // also locks the Email page
*/
