<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
___________________________________________________________________________________________
Station Logbooks
___________________________________________________________________________________________
*/

$lang['station_logbooks'] = "Журналы";
$lang['station_logbooks_description_header'] = "Что такое журнал";
$lang['station_logbooks_description_text'] = "Журналы позволяют вам группировать местоположения станции, это позволяет вам видеть все местоположения станции в одном журнале для аналитики. Удобно если вы работает из нескольких местоположений, но все они -- часть одного и того же DXCC или VUCC Circle.";
$lang['station_logbooks_create'] = "Создать журнал";
$lang['station_logbooks_status'] = "Статус";
$lang['station_logbooks_link'] = "Ссылка";
$lang['station_logbooks_public_search'] = "Публичный поиск";
$lang['station_logbooks_set_active'] = "Установить как активный журнал";
$lang['station_logbooks_active_logbook'] = "Активный журнал";
$lang['station_logbooks_edit_logbook'] = "Редактировать журнал";    // Full sentence will be generated 'Edit Station Logbook: [Logbook Name]'
$lang['station_logbooks_confirm_delete'] = "Вы уверены, что хотите удалить следующий журнал? Вы должны связать все привязанные к нему местоположения станций к другим журналам.: ";
$lang['station_logbooks_view_public'] = "Просмотр публичной страницы журнала: ";
$lang['station_logbooks_create_name'] = "Имя журнала";
$lang['station_logbooks_create_name_hint'] = "Вы можете назвать журнал как угодно.";
$lang['station_logbooks_edit_name_hint'] = "Короткое название для журнала, например: Home Log (IO87IP)";
$lang['station_logbooks_edit_name_update'] = "Обновить имя журнала";
$lang['station_logbooks_public_slug'] = "Публичная метка";
$lang['station_logbooks_public_slug_hint'] = "Установка публичной метки позволит вам поделиться вашим журналом, используя веб-ссылку, это имя должно содержать только буквы и цифры.";
$lang['station_logbooks_public_slug_format1'] = "Ссылка будет выглядеть так:";
$lang['station_logbooks_public_slug_format2'] = "[публичная метка]";
$lang['station_logbooks_public_slug_input'] = "Введите публичную метку";
$lang['station_logbooks_public_slug_visit'] = "Посетите публичную страницу";
$lang['station_logbooks_public_search_hint'] = "Включение функции публичного поиска открывает поле ввода для поиска на странице публичного журнала, доступ к которой осуществляется по публичной метке. Поиск производится только в данном журнале.";
$lang['station_logbooks_public_search_enabled'] = "Публичный поиск включен";
$lang['station_logbooks_select_avail_loc'] = "Выберите доступное местоположение станции";
$lang['station_logbooks_link_loc'] = "Привяжите местоположение станции";
$lang['station_logbooks_linked_loc'] = "Привязанные местоположения станции";
$lang['station_logbooks_no_linked_loc'] = "Нет привязанных местоположений станции";
$lang['station_logbooks_unlink_station_location'] = "Отвязать местоположение станции";



/*
___________________________________________________________________________________________
Station Locations
___________________________________________________________________________________________
*/

$lang['station_location'] = 'Местоположение станции';
$lang['station_location_plural'] = "Местоположения станции";
$lang['station_location_header_ln1'] = 'Местоположения станции определяют места работы, например, ваш QTH, QTH друзей или портативная станция.';
$lang['station_location_header_ln2'] = 'Местоположение станции хранит в одном месте набор QSO, журнал хранит набор местоположений станции.';
$lang['station_location_header_ln3'] = 'Только одно местоположение станция может быть активно в каждый момент. В таблице ниже она отмечена меткой "Активное".';
$lang['station_location_create_header'] = 'Создать местоположение станции';
$lang['station_location_create'] = 'Создать местоположение станции';
$lang['station_location_edit'] = 'Редактировать местоположение станции: ';
$lang['station_location_updated_suff'] = ' Обновлено.';
$lang['station_location_warning'] = 'Внимание. Вам нужно установить активное местоположение станции. Перейдите в меню Позывной->Местоположение станции, чтобы выбрать.';
$lang['station_location_reassign_at'] = 'Пожалуйста, переназначьте их в ';
$lang['station_location_warning_reassign'] = 'Из-за недавних изменений в Cloudlog вам нужно переназначить QSO вашим сестоположениям станции.';
$lang['station_location_name'] = 'Название профиля';
$lang['station_location_name_hint'] = 'Короткое название местоположения станции, к примеру: Home (IO87IP)';
$lang['station_location_callsign'] = 'Позывной станции';
$lang['station_location_callsign_hint'] = 'Позывной станции, например: 2M0SQL/P';
$lang['station_location_power'] = 'Мощность станции (Вт)';
$lang['station_location_power_hint'] = 'Мощность станции по умолчанию в Вт. Перезаписывается данными из CAT.';
$lang['station_location_emptylog'] = 'Очистить лог';
$lang['station_location_confirm_active'] = 'Вы уверены, что хотите сделать это местоположение станции активным: ';
$lang['station_location_set_active'] = 'Установить активным';
$lang['station_location_active'] = 'Активное';
$lang['station_location_claim_ownership'] = 'Заявить собственность';
$lang['station_location_confirm_del_qso'] = 'Вы уверены, что хотите удалить все QSO из этого местоположения станции?';
$lang['station_location_confirm_del_stationlocation'] = 'Вы уверены, что хотите удалить местоположение станции  ';
$lang['station_location_confirm_del_stationlocation_qso'] = ', это удалит все QSO, содержащиеся в этом местоположении станции?';
$lang['station_location_dxcc'] = 'DXCC';
$lang['station_location_dxcc_hint'] = 'DXCC станции, к примеру: Scotland';
$lang['station_location_dxcc_warning'] = "Остановитесь на мгновение. Выбранный вами DXCC устарел и больше не действителен. Проверьте, какой DXCC для данного конкретного места является правильным. Если вы уверены, проигнорируйте это предупреждение.";
$lang['station_location_city'] = 'город';
$lang['station_location_city_hint'] = 'Город станции, к примеру: Inverness';
$lang['station_location_state'] = 'Штат';
$lang['station_location_state_hint'] = 'Штат станции. Примени только в некоторых странах. Оставьте пустым, если неприменим.';
$lang['station_location_county'] = 'Графство';
$lang['station_location_county_hint'] = 'Графство станции (Используется только для США/Аляски/Гавайев).';
$lang['station_location_gridsquare'] = 'Квадрат';
$lang['station_location_gridsquare_hint_ln1'] = "Квадрат станции, к примеру: IO87IP. Если вы не знаете свой квадрат, то <a href='https://zone-check.eu/?m=loc' target='_blank'>кликните сюда</a>!";
$lang['station_location_gridsquare_hint_ln2'] = "Если вы располагаетесь на границе квадратов, то укажите квадраты через запятую, к примеру: IO77,IO78,IO87,IO88.";
$lang['station_location_iota_hint_ln1'] = "Референция IOTA, к примеру: EU-005";
$lang['station_location_iota_hint_ln2'] = "Вы можете посмотреть референции IOTA на сайте <a target='_blank' href='https://www.iota-world.org/iota-directory/annex-f-short-title-iota-reference-number-list.html'>IOTA World</a>.";
$lang['station_location_sota_hint_ln1'] = "Референция SOTA. Вы можете посмотреть референции SOTA на сайте <a target='_blank' href='https://www.sotamaps.org/'>SOTA Maps</a>.";
$lang['station_location_wwff_hint_ln1'] = "Референция WWFF. Вы можете посмотреть референции WWFF на сайте <a target='_blank' href='https://www.cqgma.org/mvs/'>GMA Map</a>.";
$lang['station_location_pota_hint_ln1'] = "Референция POTA. Вы можете посмотреть референции POTA на сайте <a target='_blank' href='https://pota.app/#/map/'>POTA Map</a>.";
$lang['station_location_signature'] = "Подпись";
$lang['station_location_signature_name'] = "Имя подписи";
$lang['station_location_signature_name_hint'] = "Подпись станции (т.е. GMA)..";
$lang['station_location_signature_info'] = "Информация о подписи";
$lang['station_location_signature_info_hint'] = "Информация о подписи станции (т.е. DA/NW-357).";
$lang['station_location_eqsl_hint'] = 'Название профиля, который сконфигурирован в eQSL для данного QTH';
$lang['station_location_eqsl_defaultqslmsg'] = "Сообшение в eQSL по умолчанию";
$lang['station_location_eqsl_defaultqslmsg_hint'] = "Задайте сообщение по умолчанию, которое будет отправлено в eQSL для данного местоположения станции.";
$lang['station_location_qrz_subscription'] = 'Требуется подписка';
$lang['station_location_qrz_hint'] = "Ваш ключ API находится на  <a href='https://logbook.qrz.com/logbook' target='_blank'>странице настроек журнала QRZ.com";
$lang['station_location_qrz_realtime_upload'] = 'Загрузка в журнал QRZ.com в реальном времени';
$lang['station_location_hrdlog_username'] = "Имя пользователя HRDLog.net";
$lang['station_location_hrdlog_username_hint'] = "Имя пользователя, под которым вы зарегистрированы на HRDlog.net (обычно это -- ваш позывной).";
$lang['station_location_hrdlog_code'] = "Ключ API HRDLog.net";
$lang['station_location_hrdlog_realtime_upload'] = "Загрузка в журнал HRDLog.net в реальном времени";
$lang['station_location_hrdlog_code_hint'] = "Создайте свой код к API наn <a href='http://www.hrdlog.net/EditUser.aspx' target='_blank'>странице профиля пользователя HRDLog.net";
$lang['station_location_qo100_hint'] = "Создайте свой ключ API на <a href='https://qo100dx.club' target='_blank'>странице вашего профиля в QO-100 Dx Club";
$lang['station_location_qo100_realtime_upload'] = "Загрузка QO-100 Dx Club в реальном времени";
$lang['station_location_oqrs_enabled'] = "OQRS включен";
$lang['station_location_oqrs_email_alert'] = "Оповещение о OQRS по емэйл";
$lang['station_location_oqrs_email_hint'] = "Убедитесь, что емэйл сконфигурирован администратором в общих настройках.";
$lang['station_location_oqrs_text'] = "Текст OQRS";
$lang['station_location_oqrs_text_hint'] = "Информация, которую вы хотите добавить, касающаяся QSL.";
$lang['station_location_clublog_realtime_upload']="Загрузка в ClubLog в реальном времени";
