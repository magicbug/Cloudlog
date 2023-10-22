<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$lang['lotw_short'] = 'LoTW';
$lang['lotw_title'] = 'Logbook of the World';
$lang['lotw_title_available_cert'] = 'Dostupné certifikáty';
$lang['lotw_title_information'] = 'Informace';
$lang['lotw_title_upload_p12_cert'] = 'Nahraj LoTW .p12 certifikát';
$lang['lotw_title_export_p12_file_instruction'] = 'Instrukce k exportu souboru .p12';
$lang['lotw_title_adif_import'] = 'Import ADIF';
$lang['lotw_title_adif_import_options'] = 'Nastavení importu';

$lang['lotw_beta_warning'] = 'Prosím pozor, synchronizace s LoTW je zatím v testování, podívej se do Wiki pro nápovědu.';
$lang['lotw_no_certs_uploaded'] = 'Musíte nahrát nějké LoTW certifikáty ve formátu p12.';

$lang['lotw_date_created'] = 'Datum vytvoření';
$lang['lotw_date_expires'] = 'Datum vypršení';
$lang['lotw_qso_start_date'] = 'QSO Start Date';
$lang['lotw_qso_end_date'] = 'QSO End Date';
$lang['lotw_status'] = 'Stav';
$lang['lotw_options'] = 'Možnosti';
$lang['lotw_valid'] = 'Platný';
$lang['lotw_expired'] = 'Vypršel';
$lang['lotw_expiring'] = 'Expiring';
$lang['lotw_not_synced'] = 'Není synchronizováno';

$lang['lotw_certificate_dxcc'] = 'Certifikát DXCC';
$lang['lotw_certificate_dxcc_help_text'] = 'Certifikát zěmě DXCC. Například: Scotsko';

$lang['lotw_input_a_file'] = 'Nahraj soubor';

$lang['lotw_upload_exported_adif_file_from_lotw'] = 'Nahraj exportovaný soubor z LoTW z <a href="https://p1k.arrl.org/lotwuser/qsos?qsoscmd=adif" target="_blank">Download Report</a> Area, to mark QSOs as confirmed on LOTW.';
$lang['lotw_upload_type_must_be_adi'] = 'Deník musí být ve formátu .adi';

$lang['lotw_pull_lotw_data_for_me'] = 'Stáhni data z LoTW';

$lang['lotw_report_download_overview_helptext'] ='Cloudlog použije uživatelské jméno a heslo LoTW uložené ve vašem uživatelském profilu, aby vám stáhl datz LoTW.Stažen budu data od nastaveného datumu nebo od posledního stažení až do teď.';

// Buttons
$lang['lotw_btn_lotw_import'] = 'Import z LoTW';
$lang['lotw_btn_upload_certificate'] = 'Nahraj certifikát';
$lang['lotw_btn_delete'] = 'Smazat';
$lang['lotw_btn_manual_sync'] = 'Ruční synchronizace';
$lang['lotw_btn_upload_file'] = 'Nahraj soubor';
$lang['lotw_btn_import_matches'] = 'Import QSL z LoTW';

// P12 Export Text
$lang['lotw_p12_export_step_one'] = 'Otevři TQSL &amp; bež do záložky Callsign Certificates';
$lang['lotw_p12_export_step_two'] = 'pravé tlačítko myši na vyprané značce';
$lang['lotw_p12_export_step_three'] = 'Klikni "Save Callsign Certificate File" a nezadavej heslo';
$lang['lotw_p12_export_step_four'] = 'Nahraj soubor níže.';

$lang['lotw_confirmed'] = 'This QSO is confirmed on LoTW';

// LoTW Expiry
$lang['lotw_cert_expiring'] = 'At least one of your LoTW certificates is about to expire!';
$lang['lotw_cert_expired'] = 'At least one of your LoTW certificates is expired!';

// Lotw User
$lang['lotw_user'] = 'This station uses LoTW.';
$lang['lotw_last_upload'] = 'Last upload';
