<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$lang['options_cloudlog_options'] = 'Настройки';
$lang['options_message1'] = 'Это глобальные настройки, используемые для всех пользователей, которые могут быть переопределены, если есть соответствующие настройки на уровне пользователя.';

$lang['options_appearance'] = 'Внешний вид';
$lang['options_theme'] = 'Тема оформления';
$lang['options_global_theme_choice_this_is_used_when_users_arent_logged_in'] = 'Тема оформления по умолчанию, используется, когда пользователи не вошли в систему.';
$lang['options_public_search_bar'] = 'Публично доступный поиск по журналу';
$lang['options_this_allows_non_logged_in_users_to_access_the_search_functions'] = 'Разрешение пользователям, не вошедшим в систему, получить доступ к функциям поиска.';
$lang['options_dashboard_notification_banner'] = 'Баннер на экране сводных данных';
$lang['options_this_allows_to_disable_the_global_notification_banner_on_the_dashboard'] = 'Включение отображения баннера на экране сводных данных';
$lang['options_dashboard_map'] = 'Карта на экране сводных данных';
$lang['options_this_allows_the_map_on_the_dashboard_to_be_disabled_or_placed_on_the_right'] = 'Включение отображения карты на экране сводных данных во всю ширину или справа';
$lang['options_logbook_map'] = 'Карта на экране обычного вида журнала ';
$lang['options_this_allows_to_disable_the_map_in_the_logbook'] = 'Включение отображения карты на экране обычного вида журнала.';
$lang['options_theme_changed_to'] = 'Тема оформления переключена на ';
$lang['options_global_search_changed_to'] = 'Публично доступный поиск переключен в состояние ';
$lang['options_dashboard_banner_changed_to'] = 'Баннер на экране сводных данных переключен в состояние ';
$lang['options_dashboard_map_changed_to'] = 'Карта на экране сводных данных переключена в состояние ';
$lang['options_logbook_map_changed_to'] = 'Карта на экране обычного вида журнала переключена в состояние ';

$lang['options_radios'] = 'Радиоинтерфейсы';
$lang['options_radio_settings'] = 'Радиоинтерфейсы';
$lang['options_radio_timeout_warning'] = 'Предупреждение о таймауте радиоинтерфейса';
$lang['options_the_radio_timeout_warning_is_used_on_the_qso_entry_panel_to_alert_you_to_radio_interface_disconnects'] = 'Используется для отображения на панели ввода QSO предупреждения об отключении радиоинтерфейса.';
$lang['options_this_number_is_in_seconds'] = 'Значение в секундах.';
$lang['options_radio_timeout_warning_changed_to'] = 'Значение таймаута радиоинтерфейса изменено на ';

$lang['options_email'] = 'Емэйл';
$lang['options_outgoing_protocol'] = 'Протокол отправки емэйл';
$lang['options_smtp_encryption'] = 'Шифрование SMTP';
$lang['options_email_address'] = 'Адрес электронной почты';
$lang['options_email_sender_name'] = 'Имя отправителя';
$lang['options_smtp_host'] = 'SMTP хост';
$lang['options_smtp_port'] = 'SMTP порт';
$lang['options_smtp_username'] = 'SMTP логин';
$lang['options_smtp_password'] = 'SMTP пароль';
$lang['options_mail_settings_saved'] = "The settings were saved successfully.";
$lang['options_mail_settings_failed'] = "Something went wrong with saving the settings. Try again.";
$lang['options_outgoing_protocol_hint'] = "The protocol that will be used to send out emails.";
$lang['options_smtp_encryption_hint'] = "Choose whether emails should be sent with TLS or SSL.";
$lang['options_email_address_hint'] = "The email address from which the emails are sent, e.g. 'cloudlog@example.com'";
$lang['options_email_sender_name_hint'] = "The email sender name, e.g. 'Cloudlog'";
$lang['options_smtp_host_hint'] = "The hostname of the mail server, e.g. 'mail.example.com' (without 'ssl://' or 'tls://')";
$lang['options_smtp_port_hint'] = "The SMTP port of the mail server, e.g. if TLS is used -> '587', if SSL is used -> '465'";
$lang['options_smtp_username_hint'] = "The username to log in to the mail server, usually this is the email address that is used.";
$lang['options_smtp_password_hint'] = "The password to log in to the mail server.";
$lang['options_send_testmail'] = "Send Test-Mail";
$lang['options_send_testmail_hint'] = "The email will be sent to the address defined in your account settings.";
$lang['options_send_testmail_failed'] = "Testmail failed. Something went wrong.";
$lang['options_send_testmail_success'] = "Testmail sent. Email settings seem to be correct.";

$lang['options_oqrs'] = 'OQRS';
$lang['options_global_text'] = 'Сообщение на странице OQRS';
$lang['options_this_text_is_an_optional_text_that_can_be_displayed_on_top_of_the_oqrs_page'] = 'Необязательный текст, который может быть отображён в верхней части страницы OQRS.';
$lang['options_grouped_search'] = 'Объединённый поиск';
$lang['options_when_this_is_on_all_station_locations_with_oqrs_active_will_be_searched_at_once'] = 'Если включено, то поиск будет осуществляться во всех местоположениях станций, где активен OQRS.';
$lang['options_oqrs_options_have_been_saved'] = 'Настройки OQRS сохранены.';

$lang['options_dxcluster'] = 'DXCluster';
$lang['options_dxcluster_provider'] = 'Провайдер кэша DXCluster';
$lang['options_dxcluster_longtext'] = 'Провайдер кэша DXCluster. Вы можете настроить собственный кэш: <a href="https://github.com/int2001/DXClusterAPI">DXClusterAPI</a> или использовать публичный';
$lang['options_dxcluster_hint'] = 'URL кэша DXCluster-Cache. К примеру, https://dxc.jo30.de/dxcache';
$lang['options_dxcluster_settings'] = 'DXCluster';
$lang['options_dxcache_url_changed_to'] = 'URL кэша DXCluster изменён на  ';
$lang['options_dxcluster_maxage'] = 'Максимальный возраст спотов';
$lang['options_dxcluster_maxage_hint'] = 'Возраст спотов, которые будут отображаться в плане диапазонов/поиске';
$lang['options_dxcluster_decont'] = 'Отображать споты, которые присланы с континента  ';
$lang['options_dxcluster_maxage_changed_to']='Максимальный возраст спотов изменён на ';
$lang['options_dxcluster_decont_changed_to']='континент изменён на ';
$lang['options_dxcluster_decont_hint']='Only spots by spotters from this continent are shown';

$lang['options_save'] = 'Сохранить';

// Bands

$lang['options_bands'] = "Bands";
$lang['options_bands_text_ln1'] = "Using the band list you can control which bands are shown when creating a new QSO.";
$lang['options_bands_text_ln2'] = "Active bands will be shown in the QSO 'Band' drop-down, while inactive bands will be hidden and cannot be selected.";
$lang['options_bands_create'] = "Create a band";
$lang['options_bands_edit'] = "Edit Band";
$lang['options_bands_activate_all'] = "Activate All";
$lang['options_bands_activateall_warning'] = "Warning! Are you sure you want to activate all bands?";
$lang['options_bands_deactivate_all'] = "Deactivate All";
$lang['options_bands_deactivateall_warning'] = "Warning! Are you sure you want to deactivate all bands?";
$lang['options_bands_ssb_qrg'] = "SSB QRG";
$lang['options_bands_ssb_qrg_hint'] = "Frequency for SSB QRG in band (must be in Hz)";
$lang['options_bands_data_qrg'] = "DATA QRG";
$lang['options_bands_data_qrg_hint'] = "Frequency for DATA QRG in band (must be in Hz)";
$lang['options_bands_cw_qrg'] = "CW QRG";
$lang['options_bands_cw_qrg_hint'] = "Frequency for CW QRG in band (must be in Hz)";

$lang['options_bands_name_band'] = "Name of Band (E.g. 20m)";
$lang['options_bands_name_bandgroup'] = "Name of bandgroup (E.g. hf, vhf, uhf, shf)";
$lang['options_bands_delete_warning'] = "Warning! Are you sure you want to delete the following band: ";

