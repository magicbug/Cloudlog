<?php

defined('BASEPATH') OR exit('Non è permesso l\'accesso diretto allo script');

// Tiles
$lang['qso_title_qso_map'] = 'Mappa QSO';
$lang['qso_title_suggestions'] = 'Suggerimenti';
$lang['qso_title_previous_contacts'] = 'Contatti Precedenti';
$lang['qso_title_times_worked_before'] = "volte lavorato prima";
$lang['qso_title_image'] = 'Immagine Profilo';
$lang['qso_previous_max_shown'] = "Vengono visualizzati max. i 5 contatti precedenti";

// Quicklog on Dashboard
$lang['qso_quicklog_enter_callsign'] = 'QUICKLOG Inserisci Nominativo';

// Input Help Text on the /QSO Display
$lang['qso_transmit_power_helptext'] = 'Valore di potenza in Watt. Includere solo numeri nell input.';

$lang['qso_sota_ref_helptext'] = 'Per esempio: GM/NS-001.';
$lang['qso_wwff_ref_helptext'] = 'Per esempio: DLFF-0069.';
$lang['qso_pota_ref_helptext'] = 'Per esempio: PA-0150.';

$lang['qso_sig_helptext'] = 'Per esempio: GMA';
$lang['qso_sig_info_helptext'] = 'Per esempio: DA/NW-357';

$lang['qso_dok_helptext'] = 'Per esempio: Q03';

$lang['qso_notes_helptext'] = 'Il contenuto della nota viene utilizzato solo all\'interno di Cloudlog e non viene esportato in altri servizi.';
$lang['qsl_notes_helptext'] = 'Il contenuto di questa nota è esportato a servizi QSL come eqsl.cc.';

$lang['qso_eqsl_qslmsg_helptext'] = "Ottieni il messaggio predefinito per eQSL, per questa stazione.";

// error text //
$lang['qso_error_timeoff_less_timeon'] = "Orario di fine minore di quello di Inizio";

// Button Text on /qso Display

$lang['qso_btn_reset_qso'] = 'Reset';
$lang['qso_btn_save_qso'] = 'Salva QSO';
$lang['qso_btn_edit_qso'] = 'Modifica QSO';
$lang['qso_delete_warning'] = "Attenzione! Sei sicuro di voler eliminare QSO con ";

// QSO Details

$lang['qso_details'] = 'Dettagli QSO';

$lang['fav_add'] = 'Aggiungi Banda/Modo ai preferiti';
$lang['qso_operator_callsign'] = 'Nominativo Operatore';

// Simple FLE (FastLogEntry)
$lang['qso_simplefle_info'] = "Che cos'è?";
$lang['qso_simplefle_info_ln1'] = "Voce di registro veloce semplice (FLE)";
$lang['qso_simplefle_info_ln2'] = "'Fast Log Entry', o semplicemente 'FLE' è un sistema per registrare i QSO in modo molto rapido ed efficiente. Grazie alla sua sintassi, è richiesto solo un minimo di input per registrare molti QSO con il minimo sforzo possibile.";
$lang['qso_simplefle_info_ln3'] = "FLE è stato originariamente scritto da DF3CB. Offre un programma per Windows sul suo sito web. Simple FLE è stato scritto da OK2CQR sulla base di FLE di DF3CB e fornisce un'interfaccia web per registrare i QSO.";
$lang['qso_simplefle_info_ln4'] = "Un caso d'uso comune è se devi importare i tuoi paperlog da una sessione esterna e ora SimpleFLE è disponibile anche in Cloudlog. Informazioni sulla sintassi e su come funziona FLE possono essere trovate <a href= 'https://df3cb.com/fle/documentation/' target='_blank'>qui</a>.";
$lang['qso_simplefle_qso_data'] = "Dati QSO";
$lang['qso_simplefle_qso_date_hint'] = "Se non scegli una data, verrà utilizzata la data odierna.";
$lang['qso_simplefle_qso_list'] = "Lista QSO";
$lang['qso_simplefle_qso_list_total'] = "Totale";
$lang['qso_simplefle_qso_date'] = "Data del QSO";
$lang['qso_simplefle_operator'] = "Operatore";
$lang['qso_simplefle_operator_hint'] = "es. OK2CQR";
$lang['qso_simplefle_station_call_location'] = "Chiamata/Luogo della stazione";
$lang['qso_simplefle_station_call_location_hint'] = "Se operi da una nuovo Luogo, crea prima un nuovo <a href=". site_url('station') . ">Luoghi delle stazioni</a>";
$lang['qso_simplefle_utc_time'] = "Ora UTC attuale";
$lang['qso_simplefle_enter_the_data'] = "Inserisci la data";
$lang['qso_simplefle_syntax_help_close_w_sample'] = "Chiudi e carica dati di esempio";
$lang['qso_simplefle_reload'] = "Ricarica la lista dei QSO";
$lang['qso_simplefle_save'] = "Salva in Cloudlog";
$lang['qso_simplefle_clear'] = "Cancella sessione di registrazione";
$lang['qso_simplefle_refs_hint'] = "I Ref possono essere <u>S</u>OTA, <u>I</u>OTA, <u>P</u>OTA or <u>W</u>WFF";

$lang['qso_simplefle_error_band'] = "Manca la banda!";
$lang['qso_simplefle_error_mode'] = "Modo mancante!";
$lang['qso_simplefle_error_time'] = "L'ora non è impostata!";
$lang['qso_simplefle_error_stationcall'] = "Chiamata stazione non selezionata";
$lang['qso_simplefle_error_operator'] = "Il campo 'Operatore' è vuoto";
$lang['qso_simplefle_warning_reset'] = "Attenzione! Vuoi davvero resettare tutto?";
$lang['qso_simplefle_warning_missing_band_mode'] = "Attenzione! Non è possibile accedere alla lista dei QSO, perché per alcuni QSO non è definita la banda e/o la modalità!";
$lang['qso_simplefle_warning_missing_time'] = "Attenzione! Non è possibile accedere alla lista dei QSO, perché alcuni QSO non hanno un orario definito!";
$lang['qso_simplefle_warning_example_data'] = "Attenzione! Il campo dati contiene dati di esempio. Prima sessione di registrazione cancellata!";
$lang['qso_simplefle_confirm_save_to_log'] = "Sei sicuro di voler aggiungere questi QSO al log e cancellare la sessione?";
$lang['qso_simplefle_success_save_to_log_header'] = "QSO registrato!";
$lang['qso_simplefle_success_save_to_log'] = "I QSO sono stati registrati con successo nel registro!";
$lang['qso_simplefle_error_date'] = "Data non valida";

$lang['qso_simplefle_syntax_help_button'] = "Aiuto sulla sintassi";
$lang['qso_simplefle_syntax_help_title'] = "Sintassi per FLE";
$lang['qso_simplefle_syntax_help_ln1'] = "Prima di iniziare a registrare un QSO, notare le regole di base.";
$lang['qso_simplefle_syntax_help_ln2'] = "- Ogni nuovo QSO dovrebbe essere su una nuova riga.";
$lang['qso_simplefle_syntax_help_ln3'] = "- Su ogni nuova riga, scrivi solo i dati che sono cambiati rispetto al QSO precedente.";
$lang['qso_simplefle_syntax_help_ln4'] = "Per iniziare assicurati di aver già compilato il modulo a sinistra con la data, la chiamata della stazione e la chiamata dell'operatore. I dati principali includono la banda (o QRG in MHz, ad esempio, '7.145 '), modalità e ora. Dopo l'ora, fornisci il primo QSO, che è essenzialmente il nominativo.";
$lang['qso_simplefle_syntax_help_ln5'] = "Ad esempio, un QSO iniziato alle 21:34 (UTC) con 2M0SQL sui 20 metri SSB.";
$lang['qso_simplefle_syntax_help_ln6'] = "Se non fornisci alcuna informazione RST, la sintassi utilizzerà 59 (599 per i dati). Il nostro prossimo QSO non era 59 su entrambi i lati, quindi forniamo le informazioni con l'RST inviato primo. È stato 2 minuti dopo il primo QSO.";
$lang['qso_simplefle_syntax_help_ln7'] = "Il primo QSO è stato alle 21:34, e il secondo 2 minuti dopo alle 21:36. Scriviamo 6 perché questo è l'unico dato che è cambiato qui. Le informazioni su banda e modalità non è cambiato, quindi questo dato viene omesso.";
$lang['qso_simplefle_syntax_help_ln8'] = "Per il nostro prossimo QSO alle 21:40 del 14 maggio 2021, abbiamo cambiato la banda a 40 metri ma sempre in SSB. Se non vengono fornite informazioni RST, la sintassi utilizzerà 59 per ogni nuovo QSO . Pertanto possiamo aggiungere un altro QSO avvenuto alla stessa ora due giorni dopo. La data deve essere nel formato AAAA-MM-GG.";
$lang['qso_simplefle_syntax_help_ln9'] = "Per ulteriori informazioni sulla sintassi, consultare il sito web di DF3CB <a href='https://df3cb.com/fle/documentation/' target='_blank'>qui.</ a>";   
