<?php

defined('BASEPATH') OR exit('Bezpośredni dostęp do skryptu nie jest dozwolony');

/*
______________________________________________________________________________________________________
Topbar
___________________________________________________________________________________________
*/

$lang['filter_quickfilters'] = 'Szybkie filtry';
$lang['filter_qsl_filters'] = 'Filtry QSL';
$lang['filter_filters'] = 'Filtry';
$lang['filter_actions'] = 'Akcje';
$lang['filter_results'] = '# Wyniki';
$lang['filter_search'] = 'Szukaj';
$lang['filter_dupes'] = "Dupes";
$lang['filter_map'] = 'Mapa';
$lang['filter_options'] = 'Opcje';
$lang['filter_reset'] = 'Resetuj';

/*
___________________________________________________________________________________________
Quickilters
___________________________________________________________________________________________
*/

$lang['filter_quicksearch_w_sel'] = 'Szybkie wyszukiwanie z wybranymi: ';
$lang['filter_search_callsign'] = 'Wyszukaj znak wywoławczy';
$lang['filter_search_dxcc'] = 'Wyszukaj DXCC';
$lang['filter_search_state'] = 'Wyszukaj stan';
$lang['filter_search_gridsquare'] = 'Wyszukaj siatkę kwadratową';
$lang['filter_search_cq_zone'] = 'Wyszukaj strefę CQ';
$lang['filter_search_mode'] = 'Tryb wyszukiwania';
$lang['filter_search_band'] = 'Pasmo wyszukiwania';
$lang['filter_search_iota'] = 'Szukaj IOTA';
$lang['filter_search_sota'] = 'Szukaj SOTA';
$lang['filter_search_wwff'] = 'Szukaj WWFF';
$lang['filter_search_pota'] = 'Szukaj POTA';

/*
___________________________________________________________________________________________________________
Filtry QSL
___________________________________________________________________________________________
*/

$lang['filter_qsl_sent'] = 'Wysłano QSL';
$lang['filter_qsl_recv'] = 'Otrzymano QSL';
$lang['filter_qsl_sent_method'] = 'Metoda wysyłania QSL';
$lang['filter_qsl_recv_method'] = 'Metoda odbierania QSL';
$lang['filter_lotw_sent'] = 'Wysłano LoTW';
$lang['filter_lotw_recv'] = 'Otrzymano LoTW';
$lang['filter_eqsl_sent'] = 'Wysłano eQSL';
$lang['filter_eqsl_recv'] = 'Otrzymano eQSL';
$lang['filter_qsl_via'] = 'QSL via';
$lang['filter_qsl_images'] = 'Obrazy QSL';

// $lang['general_word_all'] --> aplikacja/język/angielski/general_words_lang.php
// $lang['general_word_yes'] --> aplikacja/język/angielski/general_words_lang.php
// $lang['general_word_no'] --> aplikacja/język/angielski/general_words_lang.php
// $lang['general_word_requested'] --> aplikacja/język/angielski/general_words_lang.php
// $lang['general_word_queued'] --> aplikacja/język/angielski/general_words_lang.php
// $lang['general_word_invalid_ignore'] --> aplikacja/język/angielski/general_words_lang.php
$lang['filter_qsl_verified'] = 'Zweryfikowano';

// $lang['general_word_qslcard_bureau'] --> aplikacja/język/angielski/general_words_lang.php
// $lang['general_word_qslcard_direct'] --> aplikacja/język/angielski/general_words_lang.php
// $lang['general_word_qslcard_electronic'] --> aplikacja/język/angielski/general_words_lang.php
// $lang['general_word_qslcard_manager'] --> aplikacja/język/angielski/general_words_lang.php

/*
___________________________________________________________________________________________
Filtry ogólne
___________________________________________________________________________________________
*/

$lang['filter_general_from'] = 'Od';
$lang['filter_general_to'] = 'do';
// $lang['gen_hamradio_de'] --> aplikacja/język/english/general_words_lang.php
// $lang['gen_hamradio_dx'] --> aplikacja/język/english/general_words_lang.php
// $lang['gen_hamradio_dxcc'] --> aplikacja/język/angielski/general_words_lang.php
$lang['filter_general_none'] = '- BRAK - (np. /MM, /AM)';
// $lang['gen_hamradio_state'] --> aplikacja/język/angielski/general_words_lang.php
// $lang['gen_hamradio_gridsquare'] --> aplikacja/język/angielski/general_words_lang.php
// $lang['gen_hamradio_mode'] --> aplikacja/język/angielski/general_words_lang.php
// $lang['gen_hamradio_band'] --> aplikacja/język/angielski/general_words_lang.php

$lang['filter_general_propagation'] = 'Propagacja';
// $lang['gen_hamradio_cq_zone'] --> aplikacja/język/angielski/general_words_lang.php
// $lang['gen_hamradio_iota'] --> aplikacja/język/english/general_words_lang.php
// $lang['gen_hamradio_sota'] --> aplikacja/język/english/general_words_lang.php
// $lang['gen_hamradio_wwff'] --> aplikacja/język/angielski/general_words_lang.php
// $lang['gen_hamradio_pota'] --> aplikacja/język/english/general_words_lang.php

/*
___________________________________________________________________________________________
Działania
___________________________________________________________________________________________
*/
$lang['filter_actions_w_selected'] = 'Z wybranymi: ';
$lang['filter_actions_update_f_callbook'] = 'Aktualizacja z książki telefonicznej';
$lang['filter_actions_queue_bureau'] = 'Biuro kolejek';
$lang['filter_actions_queue_direct'] = 'Kolejka bezpośrednia';
$lang['filter_actions_queue_electronic'] = 'Kolejka elektroniczna';
$lang['filter_actions_sent_bureau'] = 'Wysłano (Biuro)';
$lang['filter_actions_sent_direct'] = 'Wysłano (Bezpośrednio)';
$lang['filter_actions_sent_electronic'] = 'Wysłano (elektronicznie)';
$lang['filter_actions_not_sent'] = 'Nie wysłano';
$lang['filter_actions_qsl_n_required'] = 'Karta QSL nie jest wymagana';
$lang['filter_actions_recv_bureau'] = 'Otrzymano (biuro)';
$lang['filter_actions_recv_direct'] = 'Otrzymano (bezpośrednio)';
$lang['filter_actions_recv_electronic'] = 'Otrzymano (elektronicznie)';
$lang['filter_actions_create_adif'] = 'Utwórz ADIF';
$lang['filter_actions_print_label'] = 'Drukuj etykietę';
$lang['filter_actions_start_print_title'] = 'Drukuj etykiety';
$lang['filter_actions_print_include_via'] = "Dołącz przez";
$lang['filter_actions_print_include_grid'] = 'Dołącz siatkę?';
$lang['filter_actions_start_print'] = 'Rozpocznij drukowanie o?';
$lang['filter_actions_print'] = 'Drukuj';
$lang['filter_actions_qsl_slideshow'] = 'Pokaz slajdów QSL';
$lang['filter_actions_delete'] = 'Usuń';
$lang['filter_actions_delete_warning'] = "Ostrzeżenie! Czy na pewno chcesz usunąć zaznaczone QSO?";

/*
___________________________________________________________________________________________
Opcje
___________________________________________________________________________________________
*/

$lang['filter_options_title'] = 'Opcje zaawansowanego dziennika';
$lang['filter_options_column'] = 'Kolumna';
$lang['filter_options_show'] = 'Pokaż';
// $lang['general_word_datetime'] --> aplikacja/język/angielski/general_words_lang.php
// $lang['gen_hamradio_de'] --> aplikacja/język/angielski/general_words_lang.php
// $lang['gen_hamradio_dx'] --> aplikacja/język/angielski/general_words_lang.php
// $lang['gen_hamradio_mode'] --> aplikacja/język/angielski/general_words_lang.php
// $lang['gen_hamradio_rsts'] --> aplikacja/język/angielski/general_words_lang.php
// $lang['gen_hamradio_rstr'] --> aplikacja/język/angielski/general_words_lang.php
// $lang['gen_hamradio_band'] --> aplikacja/język/angielski/język_ogólny_słow.php
// $lang['gen_hamradio_myrefs'] --> aplikacja/język/angielski/język_ogólny_słow.php
// $lang['gen_hamradio_refs'] --> aplikacja/język/angielski/język_ogólny_słow.php
// $lang['nazwa_słowna_ogólna'] --> aplikacja/język/angielski/język_ogólny_słow.php
// $lang['filter_qsl_via'] --> aplikacja/język/angielski/język_ogólny_słow.php
// $lang['gen_hamradio_qsl'] --> aplikacja/język/angielski/język_ogólny_słow.php
// $lang['lotw_short'] --> aplikacja/język/angielski/lotw_lang.php
// $lang['eqsl_short'] --> aplikacja/język/angielski/eqsl_lang.php
// $lang['gen_hamradio_qslmsg'] --> aplikacja/język/angielski/general_words_lang.php
// $lang['gen_hamradio_dxcc'] --> aplikacja/język/angielski/general_words_lang.php
// $lang['gen_hamradio_state'] --> aplikacja/język/angielski/general_words_lang.php
// $lang['gen_hamradio_cq_zone'] --> aplikacja/język/angielski/general_words_lang.php
// $lang['gen_hamradio_iota'] --> aplikacja/język/angielski/język_ogólny.php
// $lang['gen_hamradio_sota'] --> aplikacja/język/angielski/język_ogólny.php
// $lang['gen_hamradio_wwff'] --> aplikacja/język/angielski/język_ogólny.php
// $lang['gen_hamradio_pota'] --> aplikacja/język/angielski/język_ogólny.php
// $lang['options_save'] --> aplikacja/język/angielski/język_ogólny.php
$lang['filter_search_operator']='Operator wyszukiwania';
$lang['filter_options_close'] = 'Zamknij';