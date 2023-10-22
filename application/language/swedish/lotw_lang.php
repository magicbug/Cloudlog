<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$lang['lotw_short'] = 'LoTW';
$lang['lotw_title'] = 'Logbook of the World';
$lang['lotw_title_available_cert'] = 'Tillgängliga certifikat';
$lang['lotw_title_information'] = 'Information';
$lang['lotw_title_upload_p12_cert'] = 'Ladda upp Logbook of the World .p12 certifikat';
$lang['lotw_title_export_p12_file_instruction'] = 'Exportera .p12 filinstruktioner';
$lang['lotw_title_adif_import'] = 'ADIF Import';
$lang['lotw_title_adif_import_options'] = 'Importinställningar';

$lang['lotw_beta_warning'] = 'Observera att LoTW Sync är i BETA-version, see wiki för hjälp.';
$lang['lotw_no_certs_uploaded'] = 'Du behöver ladda upp LoTW p12 certifikat för att kunna använda denna area.';

$lang['lotw_date_created'] = 'Skapad datum';
$lang['lotw_date_expires'] = 'Utgår datum';
$lang['lotw_qso_start_date'] = 'QSO startdatum';
$lang['lotw_qso_end_date'] = 'QSO slutdatum';
$lang['lotw_status'] = 'Status';
$lang['lotw_options'] = 'Inställningar';
$lang['lotw_valid'] = 'Giltig';
$lang['lotw_expired'] = 'Utgått';
$lang['lotw_expiring'] = 'Utgår';
$lang['lotw_not_synced'] = 'Ej synkad';

$lang['lotw_certificate_dxcc'] = 'Certifikat DXCC';
$lang['lotw_certificate_dxcc_help_text'] = 'Certifikat DXCC entity. Som exemple: Scotland';

$lang['lotw_input_a_file'] = 'Ladda upp en fil';

$lang['lotw_upload_exported_adif_file_from_lotw'] = 'Ladda upp exporterad ADIF fil från LoTW från <a href="https://p1k.arrl.org/lotwuser/qsos?qsoscmd=adif" target="_blank">Download Report</a> area, för att markera QSOs som bekräftade på LoTW.';
$lang['lotw_upload_type_must_be_adi'] = 'Loggfil måste vara av format .adi';

$lang['lotw_pull_lotw_data_for_me'] = 'Hämta LoTW data';

$lang['lotw_report_download_overview_helptext'] = 'Cloudlog använder LoTW användarnamn och lösenord som är sparat i din användarprofil för att ladda ner repport från LoTW. Rapporten Cloudlog kommer att ladda ner kommer att ha alla bekräftelser sedan valt datum, eller sedan din senaste LoTW-bekräftelse (hämtad från din logg), fram till nu.';

// Buttons
$lang['lotw_btn_lotw_import'] = 'LoTW import';
$lang['lotw_btn_upload_certificate'] = 'Ladda upp certifikat';
$lang['lotw_btn_delete'] = 'Radera';
$lang['lotw_btn_manual_sync'] = 'Manuell synk';
$lang['lotw_btn_upload_file'] = 'Ladda upp fil';
$lang['lotw_btn_import_matches'] = 'Importera LoTW Matches';

// P12 Export Text
$lang['lotw_p12_export_step_one'] = 'Öppna TQSL &amp; gå till Callsign Certificates tabben';
$lang['lotw_p12_export_step_two'] = 'Högerklicka på önskad signal';
$lang['lotw_p12_export_step_three'] = 'Klicka "Save Callsign Certificate File" men ange inget lösenord';
$lang['lotw_p12_export_step_four'] = 'Ladda upp filen nedan.';

$lang['lotw_confirmed'] = 'Detta QSO är bekräftat på LoTW';

// LoTW Expiry
$lang['lotw_cert_expiring'] = 'Minst ett av dina LoTW-certifikat är på väg att löpa ut!';
$lang['lotw_cert_expired'] = 'Minst ett av dina LoTW-certifikat har löpt ut!';

// Lotw User
$lang['lotw_user'] = 'Denna station använder LoTW. Den senaste uppladdningen var';
$lang['lotw_last_upload'] = 'Last upload';
