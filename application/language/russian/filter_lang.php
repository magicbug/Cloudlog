<?php

defined('BASEPATH') OR exit('No direct script access allowed');


/*
___________________________________________________________________________________________
Topbar
___________________________________________________________________________________________
*/

$lang['filter_quickfilters'] = 'Быстрые фильтры';
$lang['filter_qsl_filters'] = 'QSL фильтры';
$lang['filter_filters'] = 'Фильтры';
$lang['filter_actions'] = 'Действия';
$lang['filter_results'] = '# Результаты';
$lang['filter_search'] = 'Поиск';
$lang['filter_dupes'] = "Дубликаты";
$lang['filter_map'] = 'Карта';
$lang['filter_options'] = 'Опции';
$lang['filter_reset'] = 'Сброс';
/*
___________________________________________________________________________________________
Quickilters
___________________________________________________________________________________________
*/

$lang['filter_quicksearch_w_sel'] = 'Быстрый поиск с выбранными: ';
$lang['filter_search_callsign'] = 'Поиск позывного';
$lang['filter_search_dxcc'] = 'Поиск DXCC';
$lang['filter_search_state'] = 'Поиск штата';
$lang['filter_search_gridsquare'] = 'Поиск квадрата';
$lang['filter_search_cq_zone'] = 'Поиск зоны CQ';
$lang['filter_search_mode'] = 'Поиск вида модуляции';
$lang['filter_search_band'] = 'Поиск диапазона';
$lang['filter_search_iota'] = 'Поиск IOTA';
$lang['filter_search_sota'] = 'Поиск SOTA';
$lang['filter_search_wwff'] = 'Поиск WWFF';
$lang['filter_search_pota'] = 'Поиск POTA';

/*
___________________________________________________________________________________________
QSL Filters
___________________________________________________________________________________________
*/

$lang['filter_qsl_sent'] = 'QSL отправлено';
$lang['filter_qsl_recv'] = 'QSL получено';
$lang['filter_qsl_sent_method'] = 'Способ отправки QSL';
$lang['filter_qsl_recv_method'] = 'Способ получения QSL';
$lang['filter_lotw_sent'] = 'LoTW отправлен';
$lang['filter_lotw_recv'] = 'LoTW получен';
$lang['filter_eqsl_sent'] = 'eQSL отправлено';
$lang['filter_eqsl_recv'] = 'eQSL получено';
$lang['filter_qsl_via'] = 'QSL через';
$lang['filter_qsl_images'] = 'Изображения QSL';

// $lang['general_word_all']                --> application/language/english/general_words_lang.php
// $lang['general_word_yes']                --> application/language/english/general_words_lang.php
// $lang['general_word_no']                 --> application/language/english/general_words_lang.php
// $lang['general_word_requested']          --> application/language/english/general_words_lang.php
// $lang['general_word_queued']             --> application/language/english/general_words_lang.php
// $lang['general_word_invalid_ignore']     --> application/language/english/general_words_lang.php
$lang['filter_qsl_verified'] = 'Верифицировано';

// $lang['general_word_qslcard_bureau']     --> application/language/english/general_words_lang.php
// $lang['general_word_qslcard_direct']     --> application/language/english/general_words_lang.php
// $lang['general_word_qslcard_electronic'] --> application/language/english/general_words_lang.php
// $lang['general_word_qslcard_manager']    --> application/language/english/general_words_lang.php

/*
___________________________________________________________________________________________
General Filters
___________________________________________________________________________________________
*/

$lang['filter_general_from'] = 'От';
$lang['filter_general_to'] = 'к';
// $lang['gen_hamradio_de']             --> application/language/english/general_words_lang.php 
// $lang['gen_hamradio_dx']             --> application/language/english/general_words_lang.php 
// $lang['gen_hamradio_dxcc']           --> application/language/english/general_words_lang.php
$lang['filter_general_none'] = '- без - (т.е. /MM, /AM)';
// $lang['gen_hamradio_state']          --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_gridsquare']     --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_mode']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_band']           --> application/language/english/general_words_lang.php

$lang['filter_general_propagation'] = 'Распространение';
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

$lang['filter_actions_w_selected'] = 'С выбранными: ';
$lang['filter_actions_update_f_callbook'] = 'Обновить из колбука';
$lang['filter_actions_queue_bureau'] = 'В очередь (бюро)';
$lang['filter_actions_queue_direct'] = 'В очередь (напрямую)';
$lang['filter_actions_queue_electronic'] = 'В очередь (электронно)';
$lang['filter_actions_sent_bureau'] = 'Отправлено (бюро)';
$lang['filter_actions_sent_direct'] = 'Отправле (напрямую)';
$lang['filter_actions_sent_electronic'] = 'Отправлено (электронно)';
$lang['filter_actions_not_sent'] = 'Не отправлено';
$lang['filter_actions_qsl_n_required'] = 'QSL не требуется';
$lang['filter_actions_recv_bureau'] = 'Получено (бюро)';
$lang['filter_actions_recv_direct'] = 'Получено (напрямую)';
$lang['filter_actions_recv_electronic'] = 'Получено (электронно)';
$lang['filter_actions_create_adif'] = 'Создать ADIF';
$lang['filter_actions_print_label'] = 'Напечатать наклейки';
$lang['filter_actions_start_print_title'] = 'Печать наклеек';
$lang['filter_actions_print_include_via'] = "Включить через?";
$lang['filter_actions_print_include_grid'] = 'Включить квадрат?';
$lang['filter_actions_start_print'] = 'Начать печать в?';
$lang['filter_actions_print'] = 'Печать';
$lang['filter_actions_qsl_slideshow'] = 'Слайдшоу QSL';
$lang['filter_actions_delete'] = 'Удалить';
$lang['filter_actions_delete_warning'] = "Предупреждение! Вы уверены, что хотите удалить отмеченные QSO?";
/*
___________________________________________________________________________________________
Options
___________________________________________________________________________________________
*/

$lang['filter_options_title'] = 'Опции для расширенного вида журнала';
$lang['filter_options_column'] = 'Столбец';
$lang['filter_options_show'] = 'Показать';
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
// $lang['eqsl_short']                  --> application/language/english/eqsl_lang.phpназвания
// $lang['gen_hamradio_qslmsg']         --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_dxcc']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_state']          --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_cq_zone']        --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_iota']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_sota']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_wwff']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_pota']           --> application/language/english/general_words_lang.php
// $lang['options_save']                --> application/language/english/options_lang.php
$lang['filter_options_close'] = 'Закрыть';
