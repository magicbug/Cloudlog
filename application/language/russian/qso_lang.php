<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// Tiles
$lang['qso_title_qso_map'] = 'Карта QSO';
$lang['qso_title_suggestions'] = 'Предложения';
$lang['qso_title_previous_contacts'] = 'Предыдущие контакты';
$lang['qso_title_times_worked_before'] = "раз сработано раньше";
$lang['qso_title_image'] = 'Изображение профиля';
$lang['qso_previous_max_shown'] = "Отображается до 5 предыдущих QSO";

// Quicklog on Dashboard
$lang['qso_quicklog_enter_callsign'] = 'Для быстрой записи в журнал введите позывной';

// Input Help Text on the /QSO Display
$lang['qso_transmit_power_helptext'] = 'Укажите мощность в Ваттах (только цифры).';

$lang['qso_sota_ref_helptext'] = 'Например: GM/NS-001.';
$lang['qso_wwff_ref_helptext'] = 'Например: DLFF-0069.';
$lang['qso_pota_ref_helptext'] = 'Например: PA-0150.';

$lang['qso_sig_helptext'] = 'Например: GMA';
$lang['qso_sig_info_helptext'] = 'Например: DA/NW-357';

$lang['qso_dok_helptext'] = 'Например: Q03';

$lang['qso_notes_helptext'] = 'Содержание заметки используется только Cloudlog и не экспортируется на другие сервисы.';
$lang['qsl_notes_helptext'] = 'СОдержимое этой заметки экспортируется в QSL сервисы, к примеру, eqsl.cc и т.п.';

$lang['qso_eqsl_qslmsg_helptext'] = "Сообщение в eQSL по умолчанию для этой станции.";

// error text //
$lang['qso_error_timeoff_less_timeon'] = "Время окончания раньше, чем время начала";

// Button Text on /qso Display

$lang['qso_btn_reset_qso'] = 'Сброс';
$lang['qso_btn_save_qso'] = 'Сохранить QSO';
$lang['qso_btn_edit_qso'] = 'Изменить QSO';
$lang['qso_delete_warning'] = "Предупреждение! Вы уверены в том, что хотите удалить QSO c ";

// QSO Details

$lang['qso_details'] = 'Основная информация';

$lang['fav_add'] = 'Добавить диапазон/вид модуляции в избранное';
$lang['qso_operator_callsign'] = 'Позывной оператора';

// Simple FLE (FastLogEntry)

$lang['qso_simplefle_info'] = "Что это?";
$lang['qso_simplefle_info_ln1'] = "Простая быстрая запись в журнал (FLE)";
$lang['qso_simplefle_info_ln2'] = "Быстрая запись в журнал (Fast Log Entry), или просто FLE, - это система, позволяющая очень быстро и эффективно регистрировать QSO. Благодаря своему синтаксису, для регистрации большого количества QSO требуется минимум вводных данных при минимальных усилиях.";
$lang['qso_simplefle_info_ln3'] = "Изначально FLE была написана DF3CB. На своем сайте он предлагает программу для Windows. Программа Simple FLE была написана OK2CQR на основе FLE от DF3CB и предоставляет веб-интерфейс для регистрации QSO.";
$lang['qso_simplefle_info_ln4'] = "Часто используется для импорта бумажных журналов из открытых сессий, и теперь SimpleFLE также доступен в Cloudlog. Информацию о синтаксисе и принципах работы FLE можно найти <a href='https://df3cb.com/fle/documentation/' target='_blank'>здесь</a>.";
$lang['qso_simplefle_qso_data'] = "Данные QSOa";
$lang['qso_simplefle_qso_date_hint'] = "Если вы не выберете дату, будет использована сегодняшняя.";
$lang['qso_simplefle_qso_list'] = "Список QSO";
$lang['qso_simplefle_qso_list_total'] = "Всего";
$lang['qso_simplefle_qso_date'] = "Дата QSO";
$lang['qso_simplefle_operator'] = "Оператор";
$lang['qso_simplefle_operator_hint'] = "к примеру, OK2CQR";
$lang['qso_simplefle_station_call_location'] = "Позывной/Местоположение станции";
$lang['qso_simplefle_station_call_location_hint'] = "Если вы работаете из нового местоположения станции, сначала создайте его здесь: <a href=". site_url('station') . ">Местоположение станции</a>";
$lang['qso_simplefle_utc_time'] = "Текущее время UTC";
$lang['qso_simplefle_enter_the_data'] = "Введите данные";
$lang['qso_simplefle_syntax_help_close_w_sample'] = "Закройте и загрузите образец данных";
$lang['qso_simplefle_reload'] = "Перезагрузить список QSO";
$lang['qso_simplefle_save'] = "Сохранить в Cloudlog";
$lang['qso_simplefle_clear'] = "Очистить сессию записи";
$lang['qso_simplefle_refs_hint'] = "Референции могут быть: <u>S</u>OTA, <u>I</u>OTA, <u>P</u>OTA или <u>W</u>WFF";

$lang['qso_simplefle_error_band'] = "Отсутствует диапазон!";
$lang['qso_simplefle_error_mode'] = "Отсутствует вид модуляции!";
$lang['qso_simplefle_error_time'] = "Время не установлено!";
$lang['qso_simplefle_error_stationcall'] = "Позывной не выбран";
$lang['qso_simplefle_error_operator'] = "Поле 'Оператор' пусто";
$lang['qso_simplefle_warning_reset'] = "Предупреждение! Вы уверены, что хотите всё сбросить?";
$lang['qso_simplefle_warning_missing_band_mode'] = "Предупреждение! Вы не можете записать в журнал список QSO потому, что для некоторых QSO не указаны диапазон и/или вид модуляции!";
$lang['qso_simplefle_warning_missing_time'] = "Предупреждение! Вы не можете записать в журнал список QSO потому, что для некоторых QSO не определено время!";
$lang['qso_simplefle_warning_example_data'] = "Внимание! Поле данных содержит пример данных. Сначала очистите сессию логгирования!";
$lang['qso_simplefle_confirm_save_to_log'] = "Вы уверены, что хотите записаь данные QSO в журнал и очистить сессию?";
$lang['qso_simplefle_success_save_to_log_header'] = "QSO записаны!";
$lang['qso_simplefle_success_save_to_log'] = "QSO были успешно записаны в журнал!";
$lang['qso_simplefle_error_date'] = "Некорректная дата";

$lang['qso_simplefle_syntax_help_button'] = "Помощь по синтаксису";
$lang['qso_simplefle_syntax_help_title'] = "Синтакс FLE";
$lang['qso_simplefle_syntax_help_ln1'] = "Прежде чем начать регистрировать QSO, обратите внимание на основные правила:";
$lang['qso_simplefle_syntax_help_ln2'] = "- Каждое новое QSO должно проводиться на новой строке.";
$lang['qso_simplefle_syntax_help_ln3'] = "- На каждой новой строке записывайте только те данные, которые изменились по сравнению с предыдущим QSO.";
$lang['qso_simplefle_syntax_help_ln4'] = "Для начала убедитесь, что вы уже заполнили форму слева, указав дату, позывной станции и позывной оператора. Основные данные включают диапазон (или QRG в МГц, например, '7.145'), режим и время. После времени указывается первое QSO, которое, по сути, является позывным.";
$lang['qso_simplefle_syntax_help_ln5'] = "Например, QSO, начавшееся в 21:34 (UTC) с 2M0SQL на 20 м SSB.";
$lang['qso_simplefle_syntax_help_ln6'] = "Если вы не предоставите никакой RST-информации, синтаксис будет использовать 59 (599 для данных). В нашем следующем QSO не было 59 с обеих сторон, поэтому мы предоставляем информацию с отправленным RST первыми. Это произошло на 2 минуты позже, чем первое QSO.";
$lang['qso_simplefle_syntax_help_ln7'] = "Первое QSO состоялось в 21:34, а второе - на 2 минуты позже, в 21:36. Мы записываем 6, потому что это единственные данные, которые здесь изменились. Информация о диапазоне и режиме не изменилась, поэтому эти данные опущены.";
$lang['qso_simplefle_syntax_help_ln8'] = "Для следующего QSO в 21:40 14 мая 2021 года мы сменили диапазон на 40 м, но по-прежнему на SSB. Если информация о RST не указана, то синтаксис будет использовать 59 для каждого нового QSO. Поэтому мы можем добавить еще одно QSO, состоявшееся точно в это же время двумя днями позже. Дата должна быть в формате YYYY-MM-DD.";
$lang['qso_simplefle_syntax_help_ln9'] = "Более подробную информацию о синтаксисе можно найти на сайте DF3CB <a href='https://df3cb.com/fle/documentation/' target='_blank'>здесь.</a>";
    
