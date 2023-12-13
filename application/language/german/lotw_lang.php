<?php

defined('BASEPATH') OR exit('Direkter Zugriff auf Skripte ist nicht erlaubt');

$lang['lotw_short'] = 'LoTW';
$lang['lotw_title'] = 'Logbook of the World';
$lang['lotw_title_available_cert'] = 'Verfügbare Zertifikate';
$lang['lotw_title_information'] = 'Information';
$lang['lotw_title_upload_p12_cert'] = 'Lade LoTW .p12-Zertifikat hoch';
$lang['lotw_title_export_p12_file_instruction'] = 'Anleitung für den Export einer .p12 Datei';
$lang['lotw_title_adif_import'] = 'ADIF-Import';
$lang['lotw_title_adif_import_options'] = 'Importoptionen';

$lang['lotw_beta_warning'] = 'Bitte beachte, dass sich die LoTW-Synchronisation im Betastadium befindet. Siehe Wiki für weitere Hilfe.';
$lang['lotw_no_certs_uploaded'] = 'Du musst mindestens ein LoTW-p12-Zertifikat hochladen, um diesen Bereich nutzen zu können.';

$lang['lotw_date_created'] = 'Ausstellungsdatum';
$lang['lotw_date_expires'] = 'Ablaufdatum';
$lang['lotw_qso_start_date'] = 'QSO Startdatum';
$lang['lotw_qso_end_date'] = 'QSO Enddatum';
$lang['lotw_status'] = 'Status / Letzter Upload';
$lang['lotw_options'] = 'Optionen';
$lang['lotw_valid'] = 'Gültig';
$lang['lotw_expired'] = 'Abgelaufen';
$lang['lotw_expiring'] = 'Läuft ab';
$lang['lotw_not_synced'] = 'Nicht synchronisiert';

$lang['lotw_certificate_dxcc'] = 'Zertifikats-DXCC';
$lang['lotw_certificate_dxcc_help_text'] = 'DXCC-Entität des Zertifikats. Zum Beispiel: Schottland';

$lang['lotw_input_a_file'] = 'Lade eine Datei hoch';

$lang['lotw_upload_exported_adif_file_from_lotw'] = 'Lade die exportierte ADIF-Datei aus LoTW vom <a href="https://p1k.arrl.org/lotwuser/qsos?qsoscmd=adif" target="_blank">Download Report</a> Bereich, um die QSOs als via LoTW bestätigt zu markieren.';
$lang['lotw_upload_type_must_be_adi'] = 'Logdateien müssen den Dateityp .adi haben';

$lang['lotw_pull_lotw_data_for_me'] = 'Lade LoTW-Daten für mich';
$lang['lotw_select_callsign'] = 'Rufzeichen, für das LoTW Bestätigungen geladen werden sollen';

$lang['lotw_report_download_overview_helptext'] = 'Cloudlog nutzt Benutzername und Passwort, welche in Deinem Benutzerprofil gespeichert sind, um einen Report vom LoTW zu laden. Der Report, den Cloudlog lädt, enthält alle Bestätigungen seit dem gewählten Datum oder seit der letzen LoTW-Bestätigung (wird aus Deinem Log extrahiert) bis jetzt.';

// Buttons
$lang['lotw_btn_lotw_import'] = 'LoTW-Import';
$lang['lotw_btn_upload_certificate'] = 'Zertifikats-Upload';
$lang['lotw_btn_delete'] = 'Lösche';
$lang['lotw_btn_manual_sync'] = 'Manuelle Synchronisation';
$lang['lotw_btn_upload_file'] = 'Lade Datei hoch';
$lang['lotw_btn_import_matches'] = 'Importiere LoTW-Übereinstimmungen';

// P12 Export Text
$lang['lotw_p12_export_step_one'] = 'Öffne TQSL &amp; und gehe zum "Rufzeichen-Zertifikate"-Reiter';
$lang['lotw_p12_export_step_two'] = 'Klicke links auf das gewünschte Rufzeichen';
$lang['lotw_p12_export_step_three'] = 'Klicke links auf "Speichern Sie das Rufzeichen-Zertifikat für <RUFZEICHEN>" und füge kein Passwort hinzu';
$lang['lotw_p12_export_step_four'] = 'Lade untenstehende Datei hoch.';

$lang['lotw_confirmed'] = 'Dieses QSO wurde via LoTW bestätigt am';

// LoTW Expiry
$lang['lotw_cert_expiring'] = 'Mindestens eines deiner LoTW-Zertifikate läuft bald ab!';
$lang['lotw_cert_expired'] = 'Mindestens eines deiner LoTW-Zertifikate ist abgelaufen!';

// Lotw User
$lang['lotw_user'] = 'Diese Station nutzt LoTW.';
$lang['lotw_last_upload'] = 'Letzter Upload';
