<?php

defined('BASEPATH') OR exit('Не е разрешен директен достъп до скрипта');

$lang['lotw_short'] = 'LoTW';
$lang['lotw_title'] = 'Logbook of the World';
$lang['lotw_title_available_cert'] = 'Налични сертификати';
$lang['lotw_title_information'] = 'Информация';
$lang['lotw_title_upload_p12_cert'] = 'Качване на Logbook of the World .p12 сертификат';
$lang['lotw_title_export_p12_file_instruction'] = 'Инструкции за експортиране на .p12 файл';
$lang['lotw_title_adif_import'] = 'ADIF импорт';
$lang['lotw_title_adif_import_options'] = 'Опции за импортиране';

$lang['lotw_beta_warning'] = 'Моля, имайте предвид, че LoTW Sync е БЕТА, вижте wiki за помощ.';
$lang['lotw_no_certs_uploaded'] = 'Трябва да качите някои LoTW p12 сертификати, за да използвате тази област.';

$lang['lotw_date_created'] = 'Дата на създаване';
$lang['lotw_date_expires'] = 'Дата изтичане';
$lang['lotw_qso_start_date'] = 'QSO Start Date';
$lang['lotw_qso_end_date'] = 'QSO End Date';
$lang['lotw_status'] = 'Състояние';
$lang['lotw_options'] = 'Опции';
$lang['lotw_valid'] = 'Валиден';
$lang['lotw_expired'] = 'Изтекъл';
$lang['lotw_expiring'] = 'Expiring';
$lang['lotw_not_synced'] = 'Не е синхронизиран';

$lang['lotw_certificate_dxcc'] = 'DXCC сертификат';
$lang['lotw_certificate_dxcc_help_text'] = 'Certificate DXCC entity. Например: Scotland';

$lang['lotw_input_a_file'] = 'Качете файл';

$lang['lotw_upload_exported_adif_file_from_lotw'] = 'Upload the Exported ADIF file from LoTW from the <a href="https://p1k.arrl.org/lotwuser/qsos?qsoscmd=adif" target="_blank">Download Report</a> Area, to mark QSOs as confirmed on LoTW.';
$lang['lotw_upload_type_must_be_adi'] = 'Log files must have the file type .adi';

$lang['lotw_pull_lotw_data_for_me'] = 'Pull LoTW data for me';
$lang['lotw_select_callsign'] = 'Select callsign to pull LoTW confirmations for';

$lang['lotw_report_download_overview_helptext'] = 'Cloudlog will use the LoTW username and password stored in your user profile to download a report from LoTW for you. The report Cloudlog downloads will have all confirmations since chosen date, or since your last LoTW confirmation (fetched from your log), up until now.';

// Buttons
$lang['lotw_btn_lotw_import'] = 'LoTW импорт';
$lang['lotw_btn_upload_certificate'] = 'Качване на сертификат';
$lang['lotw_btn_delete'] = 'Изтрий';
$lang['lotw_btn_manual_sync'] = 'Ръчно синхронизиране';
$lang['lotw_btn_upload_file'] = 'Качи файлa';
$lang['lotw_btn_import_matches'] = 'Import LoTW Matches';

// P12 Export Text
$lang['lotw_p12_export_step_one'] = 'Отворете TQSL &amp; отидете на раздела Callsign Certificates';
$lang['lotw_p12_export_step_two'] = 'Щракнете с десния бутон върху желания опознавателен знак';
$lang['lotw_p12_export_step_three'] = 'Щракнете върху "Save Callsign Certificate File" и не добавяйте парола';
$lang['lotw_p12_export_step_four'] = 'Качете файла по-долу.';

$lang['lotw_confirmed'] = 'Това QSO е потвърдено на LoTW';

// LoTW Expiry
$lang['lotw_cert_expiring'] = 'At least one of your LoTW certificates is about to expire!';
$lang['lotw_cert_expired'] = 'At least one of your LoTW certificates is expired!';

// Lotw User
$lang['lotw_user'] = 'This station uses LoTW.';
$lang['lotw_last_upload'] = 'Last upload';
