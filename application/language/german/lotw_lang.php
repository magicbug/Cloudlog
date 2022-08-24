<?php

defined('BASEPATH') OR exit('Direkter Zugriff auf Skripte ist nicht erlaubt');

$lang['lotw_short'] = 'LoTW';
$lang['lotw_title'] = 'Logbook of the World';
$lang['lotw_title_available_cert'] = 'Verfügbare Zertifikate';
$lang['lotw_title_information'] = 'Information';
$lang['lotw_title_upload_p12_cert'] = 'Lade Logbook of the World .p12 Zertifikat hoch';
$lang['lotw_title_export_p12_file_instruction'] = 'Anleitung für den Export einer .p12 Datei';
$lang['lotw_title_adif_import'] = 'ADIF-Import';
$lang['lotw_title_adif_import_options'] = 'Importoptionen';

$lang['lotw_beta_warning'] = 'Bitte beachte, dass sich die LotW-Synchronisation im Betastadium befindet. Siehe Wiki für weitere Hilfe.';
$lang['lotw_no_certs_uploaded'] = 'Du musst mindestens ein LoTW-p12-Zertifikat hochladen, um diesen Bereich nutzen zu können.';

$lang['lotw_date_created'] = 'Ausstellungsdatum';
$lang['lotw_date_expires'] = 'Ablaufdatum';
$lang['lotw_qso_start_date'] = 'QSO Startdatum';
$lang['lotw_qso_end_date'] = 'QSO Enddatum';
$lang['lotw_status'] = 'Status';
$lang['lotw_options'] = 'Optionen';
$lang['lotw_valid'] = 'Gültig';
$lang['lotw_expired'] = 'Abgelaufen';
$lang['lotw_not_synced'] = 'Nicht synchronisiert';

$lang['lotw_certificate_dxcc'] = 'Zertifikats-DXCC';
$lang['lotw_certificate_dxcc_help_text'] = 'DXCC-Entität des Zertifikats. Zum Beispiel: Schottland';

$lang['lotw_input_a_file'] = 'Lade eine Datei hoch';

$lang['lotw_upload_exported_adif_file_from_lotw'] = 'Lade die exportierte ADIF-Datei aus LotW vom <a href="https://p1k.arrl.org/lotwuser/qsos?qsoscmd=adif" target="_blank">Download Report</a> Bereich, um die QSOs als via LotW bestätigt zu markieren.';
$lang['lotw_upload_type_must_be_adi'] = 'Logdateien müssen den Dateityp .adi haben';

$lang['lotw_pull_lotw_data_for_me'] = 'Lade LotW-Daten für mich';
$lang['lotw_import_missing_qsos_text'] = 'Importiere fehlende QSOs in das Log. Rufzeichzeichen und Locator werden geprüft, um das passende Profil zu erkennen, in das importiert werden soll. Kann das nicht gefunden werden, wird das QSO übersprungen.';

$lang['lotw_report_download_overview_helptext'] = 'Cloudlog nutzt Benutzername und Passwort, welche in Deinem Benutzerprofil gespeichert sind, um einen Report vom LotW zu laden. Der Report, den Cloudlog lädt, enthält alle Bestätigungen seit dem gewählten Datum oder seit der letzen LotW-Bestätigung (wird aus Deinem Log extrahiert) bis jetzt.';

// Buttons
$lang['lotw_btn_lotw_import'] = 'LoTW Import';
$lang['lotw_btn_upload_certificate'] = 'Zertifikats-Upload';
$lang['lotw_btn_delete'] = 'Lösche';
$lang['lotw_btn_manual_sync'] = 'Manuelle Synchronisation';
$lang['lotw_btn_upload_file'] = 'Lade Datei hoch';
$lang['lotw_btn_import_matches'] = 'Importiere LoTW-Übereinstimmungen';

// P12 Export Text
$lang['lotw_p12_export_step_one'] = 'Öffne TQSL &amp; und gehe zum Rufzeichen Zertifikate Reiter';
$lang['lotw_p12_export_step_two'] = 'Klicke rechts auf das gewünschte Rufzeichen';
$lang['lotw_p12_export_step_three'] = 'Klick "Save Callsign Certificate File" und füge kein Passwort hinzu';
$lang['lotw_p12_export_step_four'] = 'Lade untenstehende Datei hoch.';

$lang['lotw_confirmed'] = 'Dieses QSO wurde via LotW bestätigt am';
