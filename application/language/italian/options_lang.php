<?php

defined('BASEPATH') OR exit('Non è permesso l\'accesso diretto allo script');

$lang['options_cloudlog_options'] = 'Opzioni Cloudlog';
$lang['options_message1'] = 'Le opzioni di Cloudlog sono impostazioni globali utilizzate per tutti gli utenti dell\'installazione, che vengono sovrascritte se esiste un\'impostazione a livello di utente.';

$lang['options_appearance'] = 'Aspetto';
$lang['options_theme'] = 'Tema';
$lang['options_global_theme_choice_this_is_used_when_users_arent_logged_in'] = 'Scelta tema globale, viene utilizzato quando gli utenti non hanno effettuato l\'accesso.';
$lang['options_public_search_bar'] = 'Barra di ricerca pubblica';
$lang['options_this_allows_non_logged_in_users_to_access_the_search_functions'] = 'Ciò consente agli utenti non registrati di accedere alle funzioni di ricerca.';
$lang['options_dashboard_notification_banner'] = 'Banner di notifica sulla dashboard';
$lang['options_this_allows_to_disable_the_global_notification_banner_on_the_dashboard'] = 'Ciò consente di disabilitare il banner di notifica globale sulla dashboard.';
$lang['options_dashboard_map'] = 'Mappa nella dashboard';
$lang['options_this_allows_the_map_on_the_dashboard_to_be_disabled_or_placed_on_the_right'] = 'Ciò consente di disabilitare o posizionare a destra la mappa sulla dashboard.';
$lang['options_logbook_map'] = 'Mappa nel Registro';
$lang['options_this_allows_to_disable_the_map_in_the_logbook'] = 'Ciò permette di disabilitare la mappa nel registro.';
$lang['options_theme_changed_to'] = 'Tema cambiato in ';
$lang['options_global_search_changed_to'] = 'La ricerca globale è cambiata in ';
$lang['options_dashboard_banner_changed_to'] = 'Banner della dashboard è cambiato in ';
$lang['options_dashboard_map_changed_to'] = 'La mappa nella dashboard è cambiata in ';
$lang['options_logbook_map_changed_to'] = 'La mappa nel Registro è cambiata in ';

$lang['options_radios'] = 'Radio';
$lang['options_radio_settings'] = 'Impostazioni Radio';
$lang['options_radio_timeout_warning'] = 'Avviso di timeout della radio';
$lang['options_the_radio_timeout_warning_is_used_on_the_qso_entry_panel_to_alert_you_to_radio_interface_disconnects'] = 'L\'avviso di timeout radio viene utilizzato sul pannello di ingresso del QSO per avvisare l\'utente della disconnessione dell\'interfaccia radio.';
$lang['options_this_number_is_in_seconds'] = 'Questo numero è in secondi.';
$lang['options_radio_timeout_warning_changed_to'] = 'Avviso di timeout della radio cambiato in ';

$lang['options_email'] = 'E-mail';
$lang['options_outgoing_protocol'] = 'Protocol in uscita';
$lang['options_smtp_encryption'] = 'Crittografia SMTP';
$lang['options_email_address'] = 'Indirizzo email';
$lang['options_email_sender_name'] = 'Nome mittente email';
$lang['options_smtp_host'] = 'Host SMTP';
$lang['options_smtp_port'] = 'Porta SMTP';
$lang['options_smtp_username'] = 'Nome utente SMTP';
$lang['options_smtp_password'] = 'Password SMTP';
$lang['options_mail_settings_saved'] = "Le impostazioni sono state salvate con successo.";
$lang['options_mail_settings_failed'] = "Qualcosa è andato storto durante il salvataggio delle impostazioni. Riprova.";
$lang['options_outgoing_protocol_hint'] = "Il protocollo che verrà utilizzato per inviare le email.";
$lang['options_smtp_encryption_hint'] = "Scegli se le email devono essere inviate con TLS o SSL.";
$lang['options_email_address_hint'] = "L'indirizzo email da cui vengono inviate le email, ad esempio 'cloudlog@example.com'";
$lang['options_email_sender_name_hint'] = "Il nome del mittente dell'email, ad esempio 'Cloudlog'";
$lang['options_smtp_host_hint'] = "Il nome host del server di posta, ad esempio 'mail.example.com' (senza 'ssl://' o 'tls://')";
$lang['options_smtp_port_hint'] = "La porta SMTP del server di posta, ad esempio se viene utilizzato TLS -> '587', se viene utilizzato SSL -> '465'";
$lang['options_smtp_username_hint'] = "Il nome utente per accedere al server di posta, solitamente questo è l'indirizzo email utilizzato.";
$lang['options_smtp_password_hint'] = "La password per accedere al server di posta.";
$lang['options_send_testmail'] = "Invia mail di prova";
$lang['options_send_testmail_hint'] = "L'e-mail verrà inviata all'indirizzo definito nelle impostazioni del tuo account.";
$lang['options_send_testmail_failed'] = "Testmail fallito. Qualcosa è andato storto.";
$lang['options_send_testmail_success'] = "MAIL di prova inviata. Le impostazioni email sembrano corrette.";

$lang['options_oqrs'] = 'Opzioni OQRS';
$lang['options_global_text'] = 'Testo globale';
$lang['options_this_text_is_an_optional_text_that_can_be_displayed_on_top_of_the_oqrs_page'] = 'Questo testo è un testo opzionale che può essere visualizzato in cima alla pagina OQRS.';
$lang['options_grouped_search'] = 'Ricerca raggruppata';
$lang['options_when_this_is_on_all_station_locations_with_oqrs_active_will_be_searched_at_once'] = 'Quando è attivo, tutti luoghi delle stazioni con OQRS attivo verranno cercate contemporaneamente.';
$lang['options_grouped_search_show_station_name'] = "Mostra il nome del luogo della stazione nei risultati di ricerca raggruppati";
$lang['options_grouped_search_show_station_name_hint'] = "Se la ricerca raggruppata è attiva, puoi decidere se il nome del luogo della stazione deve essere mostrato nella tabella dei risultati.";
$lang['options_oqrs_options_have_been_saved'] = 'Le opzioni OQRS sono state salvate.';

$lang['options_dxcluster'] = 'DXCluster';
$lang['options_dxcluster_provider'] = 'Fornitore di DXClusterCache';
$lang['options_dxcluster_longtext'] = 'Il provider della cache DXCluster. Puoi impostare la tua cache con <a href="https://github.com/int2001/DXClusterAPI">DXClusterAPI</a> o utilizzarne una pubblica';
$lang['options_dxcluster_hint'] = 'URL della cache DXCluster. per esempio. https://dxc.jo30.de/dxcache';
$lang['options_dxcluster_settings'] = 'DXCluster';
$lang['options_dxcache_url_changed_to'] = 'URL della cache DXCluster cambiato in ';
$lang['options_dxcluster_maxage'] = 'Età massima degli spot gestiti';
$lang['options_dxcluster_maxage_hint'] = 'L\'età in minuti degli spot, che verrà gestita al bandplan/lookup';
$lang['options_dxcluster_decont'] = 'Mostra gli spot individuati dal seguente continente';
$lang['options_dxcluster_maxage_changed_to']='Età massima degli spot modificata in ';
$lang['options_dxcluster_decont_changed_to']='il continente è cambiato in ';
$lang['options_dxcluster_decont_hint']='Vengono mostrati solo gli spot degli osservatori di questo continente';

$lang['options_version_dialog'] = "Informazioni sulla versione";
$lang['options_version_dialog_close'] = "Chiudi";
$lang['options_version_dialog_dismiss'] = "Non mostrare più";
$lang['options_version_dialog_settings'] = "Impostazioni informazioni sulla versione";
$lang['options_version_dialog_header'] = "Intestazione informazioni sulla versione";
$lang['options_version_dialog_header_hint'] = "Puoi cambiare l'intestazione della finestra di dialogo delle informazioni sulla versione.";
$lang['options_version_dialog_header_changed_to'] = "Intestazione delle informazioni sulla versione modificata in";
$lang['options_version_dialog_mode'] = "Modalità informazioni sulla versione";
$lang['options_version_dialog_mode_release_notes'] = "Solo note di rilascio";
$lang['options_version_dialog_mode_custom_text'] = "Solo testo personalizzato";
$lang['options_version_dialog_mode_both'] = "Note sulla versione e testo personalizzato";
$lang['options_version_dialog_mode_disabled'] = "Disabilitato";
$lang['options_version_dialog_mode_hint'] = "Le informazioni sulla versione vengono mostrate a ogni utente. L'utente ha la possibilità di chiudere la finestra di dialogo dopo averla letta. Seleziona se vuoi mostrare solo le note di rilascio (recuperate da github), solo il testo personalizzato o entrambi.";
$lang['options_version_dialog_custom_text'] = "Testo personalizzato informazioni sulla versione";
$lang['options_version_dialog_custom_text_hint'] = "Questo è il testo personalizzato che viene mostrato nella finestra di dialogo.";
$lang['options_version_dialog_mode_changed_to'] = "La modalità Informazioni sulla versione è cambiata in";
$lang['options_version_dialog_custom_text_saved'] = "Testo personalizzato informazioni sulla versione salvato!";
$lang['options_version_dialog_success_show_all'] = "Le informazioni sulla versione verranno mostrate nuovamente a tutti gli utenti";
$lang['options_version_dialog_success_hide_all'] = "Le informazioni sulla versione non verranno mostrate a nessun utente";
$lang['options_version_dialog_show_hide'] = "Mostra/Nascondi la finestra di dialogo delle informazioni sulla versione per tutti gli utenti";
$lang['options_version_dialog_show_all'] = "Mostra per tutti gli utenti";
$lang['options_version_dialog_hide_all'] = "Nascondi per tutti gli utenti";
$lang['options_version_dialog_show_all_hint'] = "Questo mostrerà automaticamente la finestra di dialogo della versione a tutti gli utenti al successivo ricaricamento della pagina.";
$lang['options_version_dialog_hide_all_hint'] = "Questo disattiverà il popup automatico della finestra di dialogo della versione per tutti gli utenti.";

$lang['options_save'] = 'Salva';

// Bands

$lang['options_bands'] = "Bande";
$lang['options_bands_text_ln1'] = "Utilizzando l'elenco delle bande puoi controllare quali bande verranno visualizzate quando crei un nuovo QSO.";
$lang['options_bands_text_ln2'] = "Le bande attive verranno mostrate nel menu a discesa 'Banda' del QSO, mentre le bande inattive saranno nascoste e non potranno essere selezionate.";
$lang['options_bands_create'] = "Crea una band";
$lang['options_bands_edit'] = "Modifica banda";
$lang['options_bands_activate_all'] = "Attiva tutto";
$lang['options_bands_activateall_warning'] = "Attenzione! Sei sicuro di voler attivare tutte le bande?";
$lang['options_bands_deactivate_all'] = "Disattiva tutto";
$lang['options_bands_deactivateall_warning'] = "Attenzione! Sei sicuro di voler disattivare tutte le bande?";
$lang['options_bands_ssb_qrg'] = "SSB QRG";
$lang['options_bands_ssb_qrg_hint'] = "Frequenza per SSB QRG in banda (deve essere in Hz)";
$lang['options_bands_data_qrg'] = "DATA QRG";
$lang['options_bands_data_qrg_hint'] = "Frequenza per DATA QRG in banda (deve essere in Hz)";
$lang['options_bands_cw_qrg'] = "CW QRG";
$lang['options_bands_cw_qrg_hint'] = "Frequenza per CW QRG in banda (deve essere in Hz)";

$lang['options_bands_name_band'] = "Nome della banda (es. 20m)";
$lang['options_bands_name_bandgroup'] = "Nome del gruppo di banda (es. hf, vhf, uhf, shf)";
$lang['options_bands_delete_warning'] = "Attenzione! Sei sicuro di voler eliminare la seguente banda: ";

