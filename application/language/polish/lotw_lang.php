<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$lang['lotw_short'] = 'LoTW';
$lang['lotw_title'] = 'Logbook of the World';
$lang['lotw_title_available_cert'] = 'Dostępne certyfikaty';
$lang['lotw_title_information'] = 'Informacje';
$lang['lotw_title_upload_p12_cert'] = 'Wyślij certyfikat .p12 Logbook of the World';
$lang['lotw_title_export_p12_file_instruction'] = 'Pobierz instrukcje pliku .p12';
$lang['lotw_title_adif_import'] = 'Wyślij plik w formacie ADIF';
$lang['lotw_title_adif_import_options'] = 'Opcje wysyłania';

$lang['lotw_beta_warning'] = 'Miej na uwadze, że wynchroinizacja z LoTW nie jest stabilna. Więcej na WIKI.';
$lang['lotw_no_certs_uploaded'] = 'Musisz wysłać certyfikat .p12 aby używać tych pól';

$lang['lotw_date_created'] = 'Data utworzenia';
$lang['lotw_date_expires'] = 'Data wygaśnięcia';
$lang['lotw_qso_start_date'] = 'QSO Start Date';
$lang['lotw_qso_end_date'] = 'QSO End Date';
$lang['lotw_status'] = 'Status';
$lang['lotw_options'] = 'Opcje';
$lang['lotw_valid'] = 'Ważny';
$lang['lotw_expired'] = 'wygaśnięty';
$lang['lotw_expiring'] = 'Expiring';
$lang['lotw_not_synced'] = 'Nie zsynchronizowany';

$lang['lotw_certificate_dxcc'] = 'Podmiot DXCC certyfikatu';
$lang['lotw_certificate_dxcc_help_text'] = 'Podmiot DXCC dla którego wydany został certyfikat, na przykład Poland';

$lang['lotw_input_a_file'] = 'Prześlij plik';

$lang['lotw_upload_exported_adif_file_from_lotw'] = 'Wyślij pobrany plik ADIF z LoTW z <a href="https://p1k.arrl.org/lotwuser/qsos?qsoscmd=adif" target="_blank">Linku</a>, aby oznaczyć łączności jako potwierdzone przez LoTW.';
$lang['lotw_upload_type_must_be_adi'] = 'Pliki logu muszą mieć rozszerzenie .adi';

$lang['lotw_pull_lotw_data_for_me'] = 'Pobierz dane z LoTW za mnie';
$lang['lotw_select_callsign'] = 'Select callsign to pull LoTW confirmations for';

$lang['lotw_report_download_overview_helptext'] ='Cloudlog będzie używał loginu i hasła podanego w profilu, aby pobierać raporty z LoTW.Raport będzie zawierał wszystkie potwierdzenia od wybranej daty, lub ostatniej potwierdzonej łączności z LoTW (wybranej z logiu), do teraz.';

// Buttons
$lang['lotw_btn_lotw_import'] = 'LoTW Import';
$lang['lotw_btn_upload_certificate'] = 'Wyślij certyfikat';
$lang['lotw_btn_delete'] = 'Usuń';
$lang['lotw_btn_manual_sync'] = 'Ręczna synchronizacja';
$lang['lotw_btn_upload_file'] = 'Wyślij plik';
$lang['lotw_btn_import_matches'] = 'Import LoTW Matches';

// P12 Export Text
$lang['lotw_p12_export_step_one'] = 'Otwórz aplikacje TQSL &amp; przejdź dp zakładki Callsign Certificates';
$lang['lotw_p12_export_step_two'] = 'Naciśnij prawym klawiszem myszny na żądany znak';
$lang['lotw_p12_export_step_three'] = 'wybierz "Save Callsign Certificate File"i nie podawaj hasła';
$lang['lotw_p12_export_step_four'] = 'Wyślij plik poniżej.';

$lang['lotw_confirmed'] = 'Ta łączność jest potwierdzona przez LoTW';

// LoTW Expiry
$lang['lotw_cert_expiring'] = 'At least one of your LoTW certificates is about to expire!';
$lang['lotw_cert_expired'] = 'At least one of your LoTW certificates is expired!';

// Lotw User
$lang['lotw_user'] = 'This station uses LoTW.';
$lang['lotw_last_upload'] = 'Last upload';
