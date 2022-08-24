<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$lang['lotw_short'] = 'LoTW';
$lang['lotw_title'] = 'Logbook of the World';
$lang['lotw_title_available_cert'] = 'Available Certificates';
$lang['lotw_title_information'] = 'Information';
$lang['lotw_title_upload_p12_cert'] = 'Upload Logbook of the World .p12 Certificate';
$lang['lotw_title_export_p12_file_instruction'] = 'Export .p12 File Instructions';
$lang['lotw_title_adif_import'] = 'ADIF Import';
$lang['lotw_title_adif_import_options'] = 'Import Options';

$lang['lotw_beta_warning'] = 'Please be aware that LoTW Sync is BETA, see wiki for help.';
$lang['lotw_no_certs_uploaded'] = 'You need to upload some LoTW p12 certificates to use this area.';

$lang['lotw_date_created'] = 'Date Created';
$lang['lotw_date_expires'] = 'Date Expires';
$lang['lotw_qso_start_date'] = 'QSO Start Date';
$lang['lotw_qso_end_date'] = 'QSO End Date';
$lang['lotw_status'] = 'Status';
$lang['lotw_options'] = 'Options';
$lang['lotw_valid'] = 'Valid';
$lang['lotw_expired'] = 'Expired';
$lang['lotw_not_synced'] = 'Not Synced';

$lang['lotw_certificate_dxcc'] = 'Certificate DXCC';
$lang['lotw_certificate_dxcc_help_text'] = 'Certificate DXCC entity. For example: Scotland';

$lang['lotw_input_a_file'] = 'Upload a File';

$lang['lotw_upload_exported_adif_file_from_lotw'] = 'Upload the Exported ADIF file from LoTW from the <a href="https://p1k.arrl.org/lotwuser/qsos?qsoscmd=adif" target="_blank">Download Report</a> Area, to mark QSOs as confirmed on LOTW.';
$lang['lotw_upload_type_must_be_adi'] = 'Log files must have the file type .adi';

$lang['lotw_pull_lotw_data_for_me'] = 'Pull LoTW data for me';
$lang['lotw_import_missing_qsos_text'] = 'Import missing QSOs into the log. Call and gridsquare will be checked to try to find the correct profile to import the QSO into. If not found, the QSO will be skipped.';

$lang['lotw_report_download_overview_helptext'] = 'Cloudlog will use the LoTW username and password stored in your user profile to download a report from LoTW for you. The report Cloudlog downloads will have all confirmations since chosen date, or since your last LoTW confirmation (fetched from your log), up until now.';

// Buttons
$lang['lotw_btn_lotw_import'] = 'LoTW Import';
$lang['lotw_btn_upload_certificate'] = 'Upload Certificate';
$lang['lotw_btn_delete'] = 'Delete';
$lang['lotw_btn_manual_sync'] = 'Manual Sync';
$lang['lotw_btn_upload_file'] = 'Upload File';
$lang['lotw_btn_import_matches'] = 'Import LoTW Matches';

// P12 Export Text
$lang['lotw_p12_export_step_one'] = 'Open TQSL &amp; go to the Callsign Certificates Tab';
$lang['lotw_p12_export_step_two'] = 'Right click on desired Callsign';
$lang['lotw_p12_export_step_three'] = 'Click "Save Callsign Certificate File" and do not add a password';
$lang['lotw_p12_export_step_four'] = 'Upload File below.';

$lang['lotw_confirmed'] = 'This QSO is confirmed on LoTW';
