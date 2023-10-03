<?php

defined('BASEPATH') OR exit('Non è permesso l\'accesso diretto allo script');

$lang['lotw_short'] = 'LoTW';
$lang['lotw_title'] = 'Logbook of the World';
$lang['lotw_title_available_cert'] = 'Certificati Disponibili';
$lang['lotw_title_information'] = 'Informazione';
$lang['lotw_title_upload_p12_cert'] = 'Carica Certificato .p12 Logbook of the World';
$lang['lotw_title_export_p12_file_instruction'] = 'Esporta Istruzioni File .p12';
$lang['lotw_title_adif_import'] = 'Importa ADIF';
$lang['lotw_title_adif_import_options'] = 'Importa Opzioni';

$lang['lotw_beta_warning'] = 'Attenzione, la Sincronia LoTW è in BETA, guarda la documentazione per un aiuto.';
$lang['lotw_no_certs_uploaded'] = 'Devi caricare dei certificati p12 LoTW per abilitare questa area.';

$lang['lotw_date_created'] = 'Data di Creazione';
$lang['lotw_date_expires'] = 'Data di Scadenza';
$lang['lotw_qso_start_date'] = 'Data di inizio QSO';
$lang['lotw_qso_end_date'] = 'Data di fine QSO';
$lang['lotw_status'] = 'Stato';
$lang['lotw_options'] = 'Opzioni';
$lang['lotw_valid'] = 'Valido';
$lang['lotw_expired'] = 'Scaduto';
$lang['lotw_expiring'] = 'Expiring';
$lang['lotw_not_synced'] = 'Non Sincronizzato';

$lang['lotw_certificate_dxcc'] = 'Certificato DXCC';
$lang['lotw_certificate_dxcc_help_text'] = 'Certificato entità DXCC. Per esempio: Italia';

$lang['lotw_input_a_file'] = 'Carica un File';

$lang['lotw_upload_exported_adif_file_from_lotw'] = 'Carica il file ADIF Esportato da LoTW dalla area <a href="https://p1k.arrl.org/lotwuser/qsos?qsoscmd=adif" target="_blank">Download Report</a>, per segnare i QSO come confermati su LoTW.';
$lang['lotw_upload_type_must_be_adi'] = 'I file di log devono essere di tipo .adi';

$lang['lotw_pull_lotw_data_for_me'] = 'Ottieni dati da LoTW per me';

$lang['lotw_report_download_overview_helptext'] ='Cloudlog userà il nome utente e password LoTW memorizzato nel tuo profilo per scaricare un report da LoTW per te. Il report scaricato da Cloudlog avrà tutte le conferme fino alla data scelta, o fino alla ultima conferma su LoTW (recuperato dal tuo log), fino ad ora.';

// Buttons
$lang['lotw_btn_lotw_import'] = 'Importa LoTW';
$lang['lotw_btn_upload_certificate'] = 'Carica Certificato';
$lang['lotw_btn_delete'] = 'Cancella';
$lang['lotw_btn_manual_sync'] = 'Sincronizzazione Manuale';
$lang['lotw_btn_upload_file'] = 'Carica File';
$lang['lotw_btn_import_matches'] = 'Importa Combinazioni LoTW';

// P12 Export Text
$lang['lotw_p12_export_step_one'] = 'Apri TQSL e vai sulla schermata Callsign Certificates';
$lang['lotw_p12_export_step_two'] = 'Clicca con tasto destro sul Nominativo desiderato';
$lang['lotw_p12_export_step_three'] = 'Clicca "Save Callsign Certificate File" e non aggiungere nessuna password';
$lang['lotw_p12_export_step_four'] = 'Carica file qui sotto.';

$lang['lotw_confirmed'] = 'Questo QSO è confermato su LoTW';

// LoTW Expiry
$lang['lotw_cert_expiring'] = 'At least one of your LoTW certificates is about to expire!';
$lang['lotw_cert_expired'] = 'At least one of your LoTW certificates is expired!';

// Lotw User
$lang['lotw_user'] = 'This station uses LoTW.';
$lang['lotw_last_upload'] = 'Last upload';
