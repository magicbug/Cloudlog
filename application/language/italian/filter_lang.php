<?php

defined('BASEPATH') OR exit('Non è consentito l\'accesso diretto allo script');


/*
___________________________________________________________________________________________
Topbar
___________________________________________________________________________________________
*/

$lang['filter_quickfilters'] = 'Filtri rapidi';
$lang['filter_qsl_filters'] = 'Filtri QSL';
$lang['filter_filters'] = 'Filtri';
$lang['filter_actions'] = 'Azioni';
$lang['filter_results'] = '# Risultati';
$lang['filter_search'] = 'Cerca';
$lang['filter_dupes'] = "Duplicati";
$lang['filter_map'] = 'Mappa';
$lang['filter_options'] = 'Opzioni';
$lang['filter_reset'] = 'Ripristina';

/*
___________________________________________________________________________________________
Quickilters
___________________________________________________________________________________________
*/

$lang['filter_quicksearch_w_sel'] = 'Ricerca rapida con selezionato: ';
$lang['filter_search_callsign'] = 'Cerca Nominativo';
$lang['filter_search_dxcc'] = 'Cerca DXCC';
$lang['filter_search_state'] = 'Cerca US Stato';
$lang['filter_search_gridsquare'] = 'Cerca in Griglia';
$lang['filter_search_cq_zone'] = 'Cerca zona CQ';
$lang['filter_search_mode'] = 'Cerca Modo';
$lang['filter_search_band'] = 'Cerca Banda';
$lang['filter_search_iota'] = 'Cerca IOTA';
$lang['filter_search_sota'] = 'Cerca SOTA';
$lang['filter_search_wwff'] = 'Cerca WWFF';
$lang['filter_search_pota'] = 'Cerca POTA';

/*
___________________________________________________________________________________________
QSL Filters
___________________________________________________________________________________________
*/

$lang['filter_qsl_sent'] = 'QSL inviata';
$lang['filter_qsl_recv'] = 'QSL ricevuta';
$lang['filter_qsl_sent_method'] = 'Metodo Invio QSL';
$lang['filter_qsl_recv_method'] = 'Metodo Ricezione QSL';
$lang['filter_lotw_sent'] = 'LoTW inviato';
$lang['filter_lotw_recv'] = 'LoTW ricevuto';
$lang['filter_eqsl_sent'] = 'eQSL inviata';
$lang['filter_eqsl_recv'] = 'eQSL ricevuta';
$lang['filter_qsl_via'] = 'QSL via';
$lang['filter_qsl_images'] = 'Immagini QSL';

// $lang['general_word_all']                --> application/language/english/general_words_lang.php
// $lang['general_word_yes']                --> application/language/english/general_words_lang.php
// $lang['general_word_no']                 --> application/language/english/general_words_lang.php
// $lang['general_word_requested']          --> application/language/english/general_words_lang.php
// $lang['general_word_queued']             --> application/language/english/general_words_lang.php
// $lang['general_word_invalid_ignore']     --> application/language/english/general_words_lang.php
$lang['filter_qsl_verified'] = 'Verificato';

// $lang['general_word_qslcard_bureau']     --> application/language/english/general_words_lang.php
// $lang['general_word_qslcard_direct']     --> application/language/english/general_words_lang.php
// $lang['general_word_qslcard_electronic'] --> application/language/english/general_words_lang.php
// $lang['general_word_qslcard_manager']    --> application/language/english/general_words_lang.php

/*
___________________________________________________________________________________________
General Filters
___________________________________________________________________________________________
*/

$lang['filter_general_from'] = 'Da';
$lang['filter_general_to'] = 'a';
// $lang['gen_hamradio_de']             --> application/language/english/general_words_lang.php 
// $lang['gen_hamradio_dx']             --> application/language/english/general_words_lang.php 
// $lang['gen_hamradio_dxcc']           --> application/language/english/general_words_lang.php
$lang['filter_general_none'] = '- Nessuno - (e.g. /MM, /AM)';
// $lang['gen_hamradio_state']          --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_gridsquare']     --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_mode']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_band']           --> application/language/english/general_words_lang.php

$lang['filter_general_propagation'] = 'Propagazione';
// $lang['gen_hamradio_cq_zone']        --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_iota']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_sota']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_wwff']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_pota']           --> application/language/english/general_words_lang.php

/*
___________________________________________________________________________________________
Actions
___________________________________________________________________________________________
*/

$lang['filter_actions_w_selected'] = 'Con selezionato: ';
$lang['filter_actions_update_f_callbook'] = 'Aggiorna da Callbook';
$lang['filter_actions_queue_bureau'] = 'Coda Bureau';
$lang['filter_actions_queue_direct'] = 'Coda diretta';
$lang['filter_actions_queue_electronic'] = 'Coda elettronica';
$lang['filter_actions_sent_bureau'] = 'Inviato (Bureau)';
$lang['filter_actions_sent_direct'] = 'Inviato (diretto)';
$lang['filter_actions_sent_electronic'] = 'Inviato (elettronico)';
$lang['filter_actions_not_sent'] = 'Non inviato';
$lang['filter_actions_qsl_n_required'] = 'QSL non richiesta';
$lang['filter_actions_recv_bureau'] = 'Ricevuto (Bureau)';
$lang['filter_actions_recv_direct'] = 'Ricevuto (diretto)';
$lang['filter_actions_recv_electronic'] = 'Ricevuto (elettronico)';
$lang['filter_actions_create_adif'] = 'Crea ADIF';
$lang['filter_actions_print_label'] = 'Stampa etichetta';
$lang['filter_actions_start_print_title'] = 'Stampa etichette';
$lang['filter_actions_print_include_via'] = "Includi tramite";
$lang['filter_actions_print_include_grid'] = 'Includi griglia?';
$lang['filter_actions_start_print'] = 'Inizia a stampare alle?';
$lang['filter_actions_print'] = 'Stampa';
$lang['filter_actions_qsl_slideshow'] = 'Presentazione QSL';
$lang['filter_actions_delete'] = 'Elimina';
$lang['filter_actions_delete_warning'] = "Attenzione! Sei sicuro di voler eliminare i QSO contrassegnati?";


/*
___________________________________________________________________________________________
Options
___________________________________________________________________________________________
*/

$lang['filter_options_title'] = 'Opzioni per il registro avanzato';
$lang['filter_options_column'] = 'Colonna';
$lang['filter_options_show'] = 'Mostra';
// $lang['general_word_datetime']       --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_de']             --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_dx']             --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_mode']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_rsts']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_rstr']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_band']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_myrefs']         --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_refs']           --> application/language/english/general_words_lang.php
// $lang['general_word_name']           --> application/language/english/general_words_lang.php
// $lang['filter_qsl_via']              --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_qsl']            --> application/language/english/general_words_lang.php
// $lang['lotw_short']                  --> application/language/english/lotw_lang.php
// $lang['eqsl_short']                  --> application/language/english/eqsl_lang.php
// $lang['gen_hamradio_qslmsg']         --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_dxcc']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_state']          --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_cq_zone']        --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_iota']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_sota']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_wwff']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_pota']           --> application/language/english/general_words_lang.php
// $lang['options_save']                --> application/language/english/options_lang.php
$lang['filter_search_operator']='Operatore di ricerca';
$lang['filter_options_close'] = 'Chiudi';
