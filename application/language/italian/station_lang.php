<?php

defined('BASEPATH') OR exit('Non è permesso l\'accesso diretto allo script');

/*
___________________________________________________________________________________________
Station Logbooks
___________________________________________________________________________________________
*/

$lang['station_logbooks'] = "Registri di stazione";
$lang['station_logbooks_description_header'] = "Cosa sono i registri di stazione";
$lang['station_logbooks_description_text'] = "I registri delle stazioni ti consentono di raggruppare diversi luoghi delle stazioni, questo ti consente di vedere tutte le località in una sessione dalle aree del registro alle analisi. Ottimo per quando operi da più postazioni che fanno parte dello stesso DXCC o VUCC Circle.";
$lang['station_logbooks_create'] = "Crea registro di stazione";
$lang['station_logbooks_status'] = "Stato";
$lang['station_logbooks_link'] = "Collegamento";
$lang['station_logbooks_public_search'] = "Ricerca pubblica";
$lang['station_logbooks_set_active'] = "Imposta come registro attivo";
$lang['station_logbooks_active_logbook'] = "Registro attivo";
$lang['station_logbooks_edit_logbook'] = "Modifica registro di stazione"; // Verrà generata la frase completa "Modifica registro stazione: [Nome registro]"
$lang['station_logbooks_confirm_delete'] = "Sei sicuro di voler eliminare il seguente registro di stazione? Dovrai successivamente ricollegare tutti i luoghi delle stazioni esistenti ad un altro registro di stazione.: ";
$lang['station_logbooks_view_public'] = "Visualizza la pagina pubblica del registro: ";
$lang['station_logbooks_create_name'] = "Nome registro di stazione";
$lang['station_logbooks_create_name_hint'] = "Puoi dare qualsiasi nome al registro di stazione.";
$lang['station_logbooks_edit_name_hint'] = "Nome breve per il registro di stazione. Ad esempio: Home Log (IO87IP)";
$lang['station_logbooks_edit_name_update'] = "Aggiorna nome registro stazione";
$lang['station_logbooks_public_slug'] = "Slug pubblico";
$lang['station_logbooks_public_slug_hint'] = "L'impostazione di uno slug pubblico ti consente di condividere il tuo registro con chiunque tramite un indirizzo web personalizzato, questo slug può contenere solo lettere e numeri.";
$lang['station_logbooks_public_slug_format1'] = "Più tardi apparirà così:";
$lang['station_logbooks_public_slug_format2'] = "[il tuo slug]";
$lang['station_logbooks_public_slug_input'] = "Digita la scelta Slug pubblica";
$lang['station_logbooks_public_slug_visit'] = "Visita la pagina pubblica";
$lang['station_logbooks_public_search_hint'] = "L'abilitazione della funzione di ricerca pubblica offre una casella di input per la ricerca sulla pagina del registro pubblico accessibile tramite slug pubblico. La ricerca copre solo questo registro.";
$lang['station_logbooks_public_search_enabled'] = "Ricerca pubblica abilitata";
$lang['station_logbooks_select_avail_loc'] = "Seleziona i luoghi di stazione disponibili";
$lang['station_logbooks_link_loc'] = "Luogo collegamento";
$lang['station_logbooks_linked_loc'] = "Luoghi collegate";
$lang['station_logbooks_no_linked_loc'] = "Nessun luogo collegato";
$lang['station_logbooks_unlink_station_location'] = "Scollega luogo di stazione";



/*
___________________________________________________________________________________________
Station Locations
___________________________________________________________________________________________
*/

$lang['station_location'] = 'Luogo di stazione';
$lang['station_location_plural'] = "Luoghi di stazione";
$lang['station_location_header_ln1'] = 'I Luoghi di stazione definiscono le posizioni operative, come il tuo QTH, un QTH di amici o una stazione portatile.';
$lang['station_location_header_ln2'] = 'Simile ai registri, il profilo di una stazione tiene insieme una serie di QSO.';
$lang['station_location_header_ln3'] = 'Può essere attiva solo una stazione alla volta. Nella tabella sottostante questa è mostrata con il badge -Stazione attiva-.';
$lang['station_location_create_header'] = 'Crea Luogo di stazione';
$lang['station_location_create'] = 'Crea una luogo di stazione';
$lang['station_location_edit'] = 'Modifica luogo di stazione: ';
$lang['station_location_updated_suff'] = 'Aggiornato.';
$lang['station_location_warning'] = 'Attenzione: è necessario impostare il luogo della stazione attiva. Vai su Segnale di chiamata->Posizione della stazione per selezionarne uno.';
$lang['station_location_reassign_at'] = 'Per favore riassegnali a ';
$lang['station_location_warning_reassign'] = 'A causa dei recenti cambiamenti all\'interno di Cloudlog è necessario riassegnare i QSO ai profili della stazione.';
$lang['station_location_name'] = 'Nome profilo';
$lang['station_location_name_hint'] = 'Nome breve per il luogo della stazione. Ad esempio: Casa (IO87IP)';
$lang['station_location_callsign'] = 'Nominativo della stazione';
$lang['station_location_callsign_hint'] = 'Nominativo della stazione. Ad esempio: 2M0SQL/P';
$lang['station_location_power'] = 'Potenza della stazione (W)';
$lang['station_location_power_hint'] = 'Potenza della stazione predefinita in Watt. Sovrascritto da CAT.';
$lang['station_location_emptylog'] = 'Registro vuoto';
$lang['station_location_confirm_active'] = 'Sei sicuro di voler rendere attiva la seguente stazione: ';
$lang['station_location_set_active'] = 'Imposta attivo';
$lang['station_location_active'] = 'Stazione attiva';
$lang['station_location_claim_ownership'] = 'Rivendica proprietà';
$lang['station_location_confirm_del_qso'] = 'Sei sicuro di voler eliminare tutti i QSO nel profilo di questa stazione?';
$lang['station_location_confirm_del_stationlocation'] = 'Sei sicuro di voler eliminare il profilo della stazione ';
$lang['station_location_confirm_del_stationlocation_qso'] = 'Questo cancellerà tutti i QSO all\'interno di questo profilo della stazione?';
$lang['station_location_dxcc'] = 'Stazione DXCC';
$lang['station_location_dxcc_hint'] = 'Entità DXCC della stazione. Ad esempio: Scozia';
$lang['station_location_dxcc_warning'] = "Fermati qui per un momento. Il DXCC scelto è obsoleto e non più valido. Controlla quale DXCC per questo particolare luogo è quello corretto. Se sei sicuro, ignora questo avviso.";
$lang['station_location_city'] = 'Città stazione';
$lang['station_location_city_hint'] = 'Città della stazione. Ad esempio: Inverness';
$lang['station_location_state'] = 'Stato della stazione';
$lang['station_location_state_hint'] = 'Stato della stazione. Si applica solo ad alcuni paesi. Lascia vuoto se non applicabile.';
$lang['station_location_county'] = 'Contea della stazione';
$lang['station_location_county_hint'] = 'Contea di Station (utilizzato solo per USA/Alaska/Hawaii).';
$lang['station_location_gridsquare'] = 'Griglia della Stazione';
$lang['station_location_gridsquare_hint_ln1'] = "Griglia della Stazione. Ad esempio: IO87IP. Se non conosci il tuo quadrato della griglia, allora <a href='https://zone-check.eu/?m=loc' target=' _blank'>clicca qui</a>!";
$lang['station_location_gridsquare_hint_ln2'] = "Se ti trovi su una linea della griglia, inserisci più riquadri della griglia separati da virgole. Ad esempio: IO77,IO78,IO87,IO88.";
$lang['station_location_iota_hint_ln1'] = "Riferimento IOTA della stazione. Ad esempio: EU-005";
$lang['station_location_iota_hint_ln2'] = "Puoi cercare i riferimenti IOTA su <a target='_blank' href='https://www.iota-world.org/iota-directory/annex-f-short-title -iota-reference-number-list.html'>IOTA World</a> sito web.";
$lang['station_location_sota_hint_ln1'] = "Riferimento SOTA della stazione. Puoi cercare i riferimenti SOTA sul sito web <a target='_blank' href='https://www.sotamaps.org/'>SOTA Maps</a> .";
$lang['station_location_wwff_hint_ln1'] = "Riferimento WWFF della stazione. Puoi cercare i riferimenti WWFF nella <a target='_blank' href='https://www.cqgma.org/mvs/'>Mappa GMA</a > sito web.";
$lang['station_location_pota_hint_ln1'] = "Riferimento POTA della stazione. Puoi cercare i riferimenti POTA nella <a target='_blank' href='https://pota.app/#/map/'>Mappa POTA</a > sito web.";
$lang['station_location_signature'] = "Firma";
$lang['station_location_signature_name'] = "Nome firma";
$lang['station_location_signature_name_hint'] = "Firma della stazione (ad es. GMA).";
$lang['station_location_signature_info'] = "Informazioni sulla firma";
$lang['station_location_signature_info_hint'] = "Informazioni sulla firma della stazione (ad esempio DA/NW-357).";
$lang['station_location_eqsl_hint'] = 'Il nickname QTH configurato nel tuo profilo eQSL';
$lang['station_location_eqsl_defaultqslmsg'] = "QSLMSG predefinito";
$lang['station_location_eqsl_defaultqslmsg_hint'] = "Definisci un messaggio predefinito che verrà popolato e inviato per ogni QSO da questo luogo di stazione.";
$lang['station_location_qrz_subscription'] = 'Abbonamento richiesto';
$lang['station_location_qrz_hint'] = "Trova la tua chiave API nella <a href='https://logbook.qrz.com/logbook' target='_blank'>pagina delle impostazioni del registro di QRZ.com";
$lang['station_location_qrz_realtime_upload'] = 'Caricamento in tempo reale del registro di QRZ.com';
$lang['station_location_hrdlog_username'] = "Nome utente HRDLog.net";
$lang['station_location_hrdlog_username_hint'] = "Il nome utente con cui sei registrato su HRDlog.net (solitamente il tuo nominativo).";
$lang['station_location_hrdlog_code'] = "Chiave API HRDLog.net";
$lang['station_location_hrdlog_realtime_upload'] = "Caricamento in tempo reale del registro di HRDLog.net";
$lang['station_location_hrdlog_code_hint'] = "Crea il tuo codice API sulla <a href='http://www.hrdlog.net/EditUser.aspx' target='_blank'>pagina del profilo utente HRDLog.net";
$lang['station_location_qo100_hint'] = "Crea la tua chiave API sulla <a href='https://qo100dx.club' target='_blank'>pagina del profilo del tuo QO-100 Dx Club";
$lang['station_location_qo100_realtime_upload'] = "Caricamento in tempo reale di QO-100 Dx Club";
$lang['station_location_oqrs_enabled'] = "OQRS abilitato";
$lang['station_location_oqrs_email_alert'] = "Avviso e-mail OQRS";
$lang['station_location_oqrs_email_hint'] = "Assicurati che l'e-mail sia impostata nelle opzioni di amministrazione e globali.";
$lang['station_location_oqrs_text'] = "Testo OQRS";
$lang['station_location_oqrs_text_hint'] = "Alcune informazioni che vuoi aggiungere riguardo alla QSL.";
$lang['station_location_clublog_realtime_upload']='Caricamento in tempo reale di ClubLog';


