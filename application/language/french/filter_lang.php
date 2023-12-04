<?php

defined('BASEPATH') OR exit('No direct script access allowed');


/*
___________________________________________________________________________________________
Topbar
___________________________________________________________________________________________
*/

$lang['filter_quickfilters'] = "Filtres rapides";
$lang['filter_qsl_filters'] = "Filtres QSL";
$lang['filter_filters'] = "Filtres";
$lang['filter_actions'] = "Actions";
$lang['filter_results'] = "Nb lignes";
$lang['filter_search'] = "Rechercher";
$lang['filter_dupes'] = "Doublon(s)";
$lang['filter_map'] = "Carte";
$lang['filter_options'] = "Options";
$lang['filter_reset'] = "Réinitialiser";

/*
___________________________________________________________________________________________
Quickilters
___________________________________________________________________________________________
*/

$lang['filter_quicksearch_w_sel'] = "Recherche rapide (avec infos de la ligne sélectionnée) : ";
$lang['filter_search_callsign'] = "Même Indicatif";
$lang['filter_search_dxcc'] = "Même DXCC";
$lang['filter_search_state'] = "Même Etat";
$lang['filter_search_gridsquare'] = "Même Locator";
$lang['filter_search_cq_zone'] = "Même Zone CQ";
$lang['filter_search_mode'] = "Même Mode";
$lang['filter_search_band'] = "Même Band";
$lang['filter_search_iota'] = "Même IOTA";
$lang['filter_search_sota'] = "Même SOTA";
$lang['filter_search_wwff'] = "Même WWFF";
$lang['filter_search_pota'] = "Même POTA";

/*
___________________________________________________________________________________________
QSL Filters
___________________________________________________________________________________________
*/

$lang['filter_qsl_sent'] = "QSL Envoyée";
$lang['filter_qsl_recv'] = "QSL Reçue";
$lang['filter_qsl_sent_method'] = "QSL Méthode (envoi)";
$lang['filter_qsl_recv_method'] = "QSL Méthode (reçue)";
$lang['filter_lotw_sent'] = "LoTW Envoyé";
$lang['filter_lotw_recv'] = "LoTW Reçu";
$lang['filter_eqsl_sent'] = "eQSL Envoyée";
$lang['filter_eqsl_recv'] = "eQSL Reçue";
$lang['filter_qsl_via'] = "QSL via";
$lang['filter_qsl_images'] = "QSL Images";

// $lang['general_word_all']                --> application/language/english/general_words_lang.php
// $lang['general_word_yes']                --> application/language/english/general_words_lang.php
// $lang['general_word_no']                 --> application/language/english/general_words_lang.php
// $lang['general_word_requested']          --> application/language/english/general_words_lang.php
// $lang['general_word_queued']             --> application/language/english/general_words_lang.php
// $lang['general_word_invalid_ignore']     --> application/language/english/general_words_lang.php
$lang['filter_qsl_verified'] = "Vérifiée";

// $lang['general_word_qslcard_bureau']     --> application/language/english/general_words_lang.php
// $lang['general_word_qslcard_direct']     --> application/language/english/general_words_lang.php
// $lang['general_word_qslcard_electronic'] --> application/language/english/general_words_lang.php
// $lang['general_word_qslcard_manager']    --> application/language/english/general_words_lang.php

/*
___________________________________________________________________________________________
General Filters
___________________________________________________________________________________________
*/

$lang['filter_general_from'] = "Date, du";
$lang['filter_general_to'] = "au";
// $lang['gen_hamradio_de']             --> application/language/english/general_words_lang.php 
// $lang['gen_hamradio_dx']             --> application/language/english/general_words_lang.php 
// $lang['gen_hamradio_dxcc']           --> application/language/english/general_words_lang.php
$lang['filter_general_none'] = "- AUCUN - (ex: /MM, /AM)";
// $lang['gen_hamradio_state']          --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_gridsquare']     --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_mode']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_band']           --> application/language/english/general_words_lang.php

$lang['filter_general_propagation'] = "Propagation";
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

$lang['filter_actions_w_selected'] = "Action pour lignes sélectionnées :";
$lang['filter_actions_update_f_callbook'] = "Mise à jour depuis Callbook";
$lang['filter_actions_queue_bureau'] = "En attente (Bureau)";
$lang['filter_actions_queue_direct'] = "En attente (Direct)";
$lang['filter_actions_queue_electronic'] = "En attente (Electronic)";
$lang['filter_actions_sent_bureau'] = "Envoyée (Bureau)";
$lang['filter_actions_sent_direct'] = "Envoyée (Direct)";
$lang['filter_actions_sent_electronic'] = "Envoyée (Electronic)";
$lang['filter_actions_not_sent'] = "Non envoyée";
$lang['filter_actions_qsl_n_required'] = "QSL Non requis";
$lang['filter_actions_recv_bureau'] = "Reçue (Bureau)";
$lang['filter_actions_recv_direct'] = "Reçue (Direct)";
$lang['filter_actions_recv_electronic'] = "Reçue (Numérique)";
$lang['filter_actions_create_adif'] = "Exporter en ADIF";
$lang['filter_actions_print_label'] = "Imprimer Etiquette";
$lang['filter_actions_start_print_title'] = "Impression d'étiquettes";
$lang['filter_actions_print_include_via'] = "Ajouter Via";
$lang['filter_actions_print_include_grid'] = "Ajouter Locator";
$lang['filter_actions_start_print'] = "Commencer à imprimer à";
$lang['filter_actions_print'] = "Imprimer";
$lang['filter_actions_qsl_slideshow'] = "Diaporama QSL";
$lang['filter_actions_delete'] = "Supprimer";
$lang['filter_actions_delete_warning'] = "ATTENTION ! Etes vous certain de vouloir <b><u>supprimer</u></b> les QSO sélectionnés ?";


/*
___________________________________________________________________________________________
Options
___________________________________________________________________________________________
*/

$lang['filter_options_title'] = "Options pour la recherche avancée";
$lang['filter_options_column'] = "Colonne";
$lang['filter_options_show'] = "Afficher";
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
$lang['filter_search_operator']='Search Operator';
$lang['filter_options_close'] = "Fermer";