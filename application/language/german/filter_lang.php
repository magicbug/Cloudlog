<?php

defined('BASEPATH') OR exit('Direkter Zugriff auf Skripte ist nicht erlaubt');


/*
___________________________________________________________________________________________
Topbar
___________________________________________________________________________________________
*/

$lang['filter_quickfilters'] = 'Schnellfilter';
$lang['filter_qsl_filters'] = 'QSL Filter';
$lang['filter_filters'] = 'Filter';
$lang['filter_actions'] = 'Aktionen';
$lang['filter_results'] = '# Resultate';
$lang['filter_search'] = 'Suche';
$lang['filter_dupes'] = "Dupes";
$lang['filter_map'] = 'Karte';
$lang['filter_options'] = 'Optionen';
$lang['filter_reset'] = 'Reset';

/*
___________________________________________________________________________________________
Quickilters
___________________________________________________________________________________________
*/

$lang['filter_quicksearch_w_sel'] = 'Schnellsuche in Markierten: ';
$lang['filter_search_callsign'] = 'Suche Rufzeichen';
$lang['filter_search_dxcc'] = 'Suche DXCC';
$lang['filter_search_state'] = 'Suche Staat';
$lang['filter_search_gridsquare'] = 'Suche Planquadrat';
$lang['filter_search_cq_zone'] = 'Suche CQ Zone';
$lang['filter_search_mode'] = 'Suche Mode';
$lang['filter_search_band'] = 'Suche Band';
$lang['filter_search_iota'] = 'Suche IOTA';
$lang['filter_search_sota'] = 'Suche SOTA';
$lang['filter_search_wwff'] = 'Suche WWFF';
$lang['filter_search_pota'] = 'Suche POTA';

/*
___________________________________________________________________________________________
QSL Filters
___________________________________________________________________________________________
*/

$lang['filter_qsl_sent'] = 'QSL gesendet';
$lang['filter_qsl_recv'] = 'QSL erhalten';
$lang['filter_qsl_sent_method'] = 'QSL-Sende Methode';
$lang['filter_qsl_recv_method'] = 'QSL-Empfangs Methode';
$lang['filter_lotw_sent'] = 'LoTW gesendet';
$lang['filter_lotw_recv'] = 'LoTW erhalten';
$lang['filter_eqsl_sent'] = 'eQSL gesendet';
$lang['filter_eqsl_recv'] = 'eQSL erhalten';
$lang['filter_qsl_via'] = 'QSL via';
$lang['filter_qsl_images'] = 'QSL Bilder';

// $lang['general_word_all']                --> application/language/english/general_words_lang.php
// $lang['general_word_yes']                --> application/language/english/general_words_lang.php
// $lang['general_word_no']                 --> application/language/english/general_words_lang.php
// $lang['general_word_requested']          --> application/language/english/general_words_lang.php
// $lang['general_word_queued']             --> application/language/english/general_words_lang.php
// $lang['general_word_invalid_ignore']     --> application/language/english/general_words_lang.php
$lang['filter_qsl_verified'] = 'Verifiziert';

// $lang['general_word_qslcard_bureau']     --> application/language/english/general_words_lang.php
// $lang['general_word_qslcard_direct']     --> application/language/english/general_words_lang.php
// $lang['general_word_qslcard_electronic'] --> application/language/english/general_words_lang.php
// $lang['general_word_qslcard_manager']    --> application/language/english/general_words_lang.php

/*
___________________________________________________________________________________________
General Filters
___________________________________________________________________________________________
*/

$lang['filter_general_from'] = 'Von';
$lang['filter_general_to'] = 'bis';
// $lang['gen_hamradio_de']             --> application/language/english/general_words_lang.php 
// $lang['gen_hamradio_dx']             --> application/language/english/general_words_lang.php 
// $lang['gen_hamradio_dxcc']           --> application/language/english/general_words_lang.php
$lang['filter_general_none'] = '- Kein - (für z.B. /MM, /AM)';
// $lang['gen_hamradio_state']          --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_gridsquare']     --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_mode']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_band']           --> application/language/english/general_words_lang.php

$lang['filter_general_propagation'] = 'Ausbreitungsart';
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

$lang['filter_actions_w_selected'] = 'Bei Markierten: ';
$lang['filter_actions_update_f_callbook'] = 'Aktualisieren aus dem Callbook';
$lang['filter_actions_queue_bureau'] = 'Angefordert (Büro)';
$lang['filter_actions_queue_direct'] = 'Angefordert (Direkt)';
$lang['filter_actions_queue_electronic'] = 'Angefordert (Elektronisch)';
$lang['filter_actions_sent_bureau'] = 'Gesendet (Büro)';
$lang['filter_actions_sent_direct'] = 'Gesendet (Direkt)';
$lang['filter_actions_sent_electronic'] = 'Gesendet (Elektronisch)';
$lang['filter_actions_not_sent'] = 'Nicht gesendet';
$lang['filter_actions_qsl_n_required'] = 'QSL nicht erforderlich';
$lang['filter_actions_recv_bureau'] = 'Erhalten (Büro)';
$lang['filter_actions_recv_direct'] = 'Erhalten (Direkt)';
$lang['filter_actions_recv_electronic'] = 'Erhalten (Elektronisch)';
$lang['filter_actions_create_adif'] = 'Erstelle ADIF';
$lang['filter_actions_print_label'] = 'Label drucken';
$lang['filter_actions_start_print_title'] = 'Label Drucken';
$lang['filter_actions_print_include_via'] = "Include Via";
$lang['filter_actions_print_include_grid'] = 'Mit Planquadrat?';
$lang['filter_actions_start_print'] = 'Druck starten bei?';
$lang['filter_actions_print'] = 'Drucken';
$lang['filter_actions_qsl_slideshow'] = 'QSL Präsentation';
$lang['filter_actions_delete'] = 'Löschen';
$lang['filter_actions_delete_warning'] = "Warnung! Bist du sicher, dass du die markierten QSO löschen willst?";


/*
___________________________________________________________________________________________
Options
___________________________________________________________________________________________
*/

$lang['filter_options_title'] = 'Optionen für das erweiterte Logbuch';
$lang['filter_options_column'] = 'Spalte';
$lang['filter_options_show'] = 'Anzeigen';
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
$lang['filter_options_close'] = 'Schliessen';
