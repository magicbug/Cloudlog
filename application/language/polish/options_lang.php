<?php

defined('BASEPATH') OR exit('Bezpośredni dostęp do skryptu nie jest dozwolony');

$lang['options_cloudlog_options'] = 'Opcje Cloudlog';
$lang['options_message1'] = 'Opcje Cloudlog to globalne ustawienia używane dla wszystkich użytkowników instalacji, które są zastępowane, jeśli istnieje ustawienie na poziomie użytkownika.';

$lang['options_appearance'] = 'Wygląd';
$lang['options_theme'] = 'Motyw';
$lang['options_global_theme_choice_this_is_used_when_users_arent_logged_in'] = 'Globalny wybór motywu, jest używany, gdy użytkownicy nie są zalogowani.';
$lang['options_public_search_bar'] = 'Publiczny pasek wyszukiwania';
$lang['options_this_allows_non_logged_in_users_to_access_the_search_functions'] = 'Umożliwia to niezalogowanym użytkownikom dostęp do funkcji wyszukiwania.';
$lang['options_dashboard_notification_banner'] = 'Baner powiadomień na pulpicie';
$lang['options_this_allows_to_disable_the_global_notification_banner_on_the_dashboard'] = 'Umożliwia to wyłączenie globalnego banera powiadomień na pulpicie.';
$lang['options_dashboard_map'] = 'Mapa pulpitu';
$lang['options_this_allows_the_map_on_the_dashboard_to_be_disabled_or_placed_on_the_right'] = 'Umożliwia to wyłączenie mapy na pulpicie lub umieszczenie jej po prawej stronie.';
$lang['options_logbook_map'] = 'Mapa dziennika';
$lang['options_this_allows_to_disable_the_map_in_the_logbook'] = 'Pozwala wyłączyć mapę w dzienniku.';
$lang['options_theme_changed_to'] = 'Motyw zmieniony na ';
$lang['options_global_search_changed_to'] = 'Wyszukiwanie globalne zmienione na ';
$lang['options_dashboard_banner_changed_to'] = 'Baner pulpitu zmieniony na ';
$lang['options_dashboard_map_changed_to'] = 'Mapa pulpitu zmieniona na ';
$lang['options_logbook_map_changed_to'] = 'Mapa dziennika zmieniona na ';

$lang['options_radios'] = 'Radia';
$lang['options_radio_settings'] = 'Ustawienia radia';
$lang['options_radio_timeout_warning'] = 'Ostrzeżenie o przekroczeniu limitu czasu radia';
$lang['options_the_radio_timeout_warning_is_used_on_the_qso_entry_panel_to_alert_you_to_radio_interface_disconnects'] = 'Ostrzeżenie o przekroczeniu limitu czasu radia jest używane na panelu wejściowym QSO, aby informować o rozłączeniach interfejsu radiowego.';
$lang['options_this_number_is_in_seconds'] = 'Ta liczba jest w sekundach.';
$lang['options_radio_timeout_warning_changed_to'] = 'Ostrzeżenie o przekroczeniu limitu czasu radia zmieniono na ';

$lang['options_email'] = 'E-mail';
$lang['options_outgoing_protocol'] = 'Protokół wychodzący';
$lang['options_smtp_encryption'] = 'Szyfrowanie SMTP';
$lang['options_email_address'] = 'Adres e-mail';
$lang['options_email_sender_name'] = 'Nazwa nadawcy e-mail';
$lang['options_smtp_host'] = 'Host SMTP';
$lang['options_smtp_port'] = 'Port SMTP';
$lang['options_smtp_username'] = 'Nazwa użytkownika SMTP';
$lang['options_smtp_password'] = 'Hasło SMTP';
$lang['options_mail_settings_saved'] = "Ustawienia zostały pomyślnie zapisane.";
$lang['options_mail_settings_failed'] = "Wystąpił błąd podczas zapisywania ustawień. Spróbuj ponownie.";
$lang['options_outgoing_protocol_hint'] = "Protokół, który będzie używany do wysyłania wiadomości e-mail.";
$lang['options_smtp_encryption_hint'] = "Wybierz, czy wiadomości e-mail mają być wysyłane przy użyciu protokołu TLS czy SSL.";
$lang['options_email_address_hint'] = "Adres e-mail, z którego wiadomości e-mail są wysyłane, np. 'cloudlog@example.com'";
$lang['options_email_sender_name_hint'] = "Nazwa nadawcy wiadomości e-mail, np. 'Cloudlog'";
$lang['options_smtp_host_hint'] = "Nazwa hosta serwera pocztowego, np. 'mail.example.com' (bez 'ssl://' lub 'tls://')";
$lang['options_smtp_port_hint'] = "Port SMTP serwera pocztowego, np. jeśli używany jest TLS -> '587', jeśli używany jest SSL -> '465'";
$lang['options_smtp_username_hint'] = "Nazwa użytkownika do logowania się na serwerze pocztowym, zwykle jest to używany adres e-mail.";
$lang['options_smtp_password_hint'] = "Hasło do logowania się na serwerze pocztowym.";
$lang['options_send_testmail'] = "Wyślij wiadomość testową";
$lang['options_send_testmail_hint'] = "E-mail zostanie wysłany na adres zdefiniowany w ustawieniach konta.";

$lang['options_send_testmail_failed'] = "Testmail nie powiódł się. Coś poszło nie tak.";

$lang['options_send_testmail_success'] = "Testmail został wysłany. Ustawienia e-maila wydają się być poprawne.";

$lang['options_oqrs'] = 'Opcje OQRS';

$lang['options_global_text'] = 'Tekst globalny';

$lang['options_this_text_is_an_optional_text_that_can_be_displayed_on_top_of_the_oqrs_page'] = 'Ten tekst jest opcjonalnym tekstem, który można wyświetlić na górze strony OQRS.';

$lang['options_grouped_search'] = 'Wyszukiwanie grupowe';
$lang['options_when_this_is_on_all_station_locations_with_oqrs_active_will_be_searched_at_once'] = 'Gdy ta opcja jest włączona, wszystkie lokalizacje stacji z aktywnym OQRS zostaną przeszukane jednocześnie.';
$lang['options_grouped_search_show_station_name'] = "Pokaż nazwę lokalizacji stacji w wynikach wyszukiwania grupowego";
$lang['options_grouped_search_show_station_name_hint'] = "Jeśli wyszukiwanie grupowe jest włączone, możesz zdecydować, czy nazwa lokalizacji stacji ma być wyświetlana w tabeli wyników.";
$lang['options_oqrs_options_have_been_saved'] = 'Opcje OQRS zostały zapisane.';
$lang['options_dxcluster'] = 'DXCluster';
$lang['options_dxcluster_provider'] = 'Dostawca DXClusterCache';
$lang['options_dxcluster_longtext'] = 'Dostawca DXCluster-Cache. Możesz skonfigurować własną pamięć podręczną za pomocą <a href="https://github.com/int2001/DXClusterAPI">DXClusterAPI</a> lub użyć publicznej';
$lang['options_dxcluster_hint'] = 'Adres URL pamięci podręcznej DXCluster. np. https://dxc.jo30.de/dxcache';
$lang['options_dxcluster_settings'] = 'DXCluster';
$lang['options_dxcache_url_changed_to'] = 'Adres URL pamięci podręcznej DXCluster zmieniono na ';
$lang['options_dxcluster_maxage'] = 'Maksymalny wiek obsługiwanych spotów';
$lang['options_dxcluster_maxage_hint'] = 'Wiek w minutach spotów, które zostaną uwzględnione w bandplanie/wyszukiwaniu';
$lang['options_dxcluster_decont'] = 'Pokaż spoty, które zostały oznaczone z następującego kontynentu';
$lang['options_dxcluster_maxage_changed_to']='Maksymalny wiek spotów zmieniono na ';
$lang['options_dxcluster_decont_changed_to']='kontynent zmieniono na ';
$lang['options_dxcluster_decont_hint']='Pokazywane są tylko spoty spotterów z tego kontynentu';

$lang['options_version_dialog'] = "Informacje o wersji";
$lang['options_version_dialog_close'] = "Zamknij";
$lang['options_version_dialog_dismiss'] = "Nie pokazuj ponownie";
$lang['options_version_dialog_settings'] = "Ustawienia informacji o wersji";
$lang['options_version_dialog_header'] = "Nagłówek informacji o wersji";
$lang['options_version_dialog_header_hint'] = "Możesz zmienić nagłówek okna dialogowego informacji o wersji.";
$lang['options_version_dialog_header_changed_to'] = "Nagłówek informacji o wersji został zmieniony na";
$lang['options_version_dialog_mode'] = "Tryb informacji o wersji";
$lang['options_version_dialog_mode_release_notes'] = "Tylko informacje o wydaniu";
$lang['options_version_dialog_mode_custom_text'] = "Tylko tekst niestandardowy";
$lang['options_version_dialog_mode_both'] = "Informacje o wydaniu i tekst niestandardowy";
$lang['options_version_dialog_mode_disabled'] = "Wyłączone";
$lang['options_version_dialog_mode_hint'] = "Informacje o wersji są wyświetlane każdemu użytkownikowi. Użytkownik ma możliwość zamknięcia okna dialogowego po jego przeczytaniu. Wybierz, czy chcesz wyświetlić tylko informacje o wydaniu (pobrane z github), tylko tekst niestandardowy czy oba.";
$lang['options_version_dialog_custom_text'] = "Niestandardowy tekst informacji o wersji";
$lang['options_version_dialog_custom_text_hint'] = "To jest niestandardowy tekst wyświetlany w oknie dialogowym.";
$lang['options_version_dialog_mode_changed_to'] = "Zmieniono tryb informacji o wersji na";
$lang['options_version_dialog_custom_text_saved'] = "Niestandardowy tekst informacji o wersji zapisano!";
$lang['options_version_dialog_success_show_all'] = "Informacje o wersji zostaną ponownie wyświetlone wszystkim użytkownikom";
$lang['options_version_dialog_success_hide_all'] = "Informacje o wersji nie zostaną wyświetlone żadnemu użytkownikowi";
$lang['options_version_dialog_show_hide'] = "Pokaż/ukryj okno dialogowe informacji o wersji dla wszystkich użytkowników";
$lang['options_version_dialog_show_all'] = "Pokaż dla wszystkich użytkowników";
$lang['options_version_dialog_hide_all'] = "Ukryj dla wszystkich użytkowników";
$lang['options_version_dialog_show_all_hint'] = "Spowoduje to automatyczne wyświetlenie okna dialogowego wersji wszystkim użytkownikom przy następnym przeładowaniu strony.";
$lang['options_version_dialog_hide_all_hint'] = "Spowoduje to wyłączenie automatycznego wyświetlania okna dialogowego wersji dla wszystkich użytkowników.";

$lang['options_save'] = 'Zapisz';

// Pasma

$lang['options_bands'] = "Pasma";
$lang['options_bands_text_ln1'] = "Używając listy pasm możesz kontrolować, które pasma są wyświetlane podczas tworzenia nowego QSO.";
$lang['options_bands_text_ln2'] = "Aktywne pasma będą wyświetlane w rozwijanym menu QSO 'Pasmo', natomiast nieaktywne pasma będą ukryte i nie będzie można ich wybrać.";
$lang['options_bands_create'] = "Utwórz pasmo";
$lang['options_bands_edit'] = "Edytuj pasmo";
$lang['options_bands_activate_all'] = "Aktywuj wszystkie";
$lang['options_bands_activateall_warning'] = "Ostrzeżenie! Czy na pewno chcesz aktywować wszystkie pasma?";
$lang['options_bands_deactivate_all'] = "Dezaktywuj wszystkie";
$lang['options_bands_deactivateall_warning'] = "Ostrzeżenie! Czy na pewno chcesz dezaktywować wszystkie pasma?";

$lang['options_bands_ssb_qrg'] = "SSB QRG";

$lang['options_bands_ssb_qrg_hint'] = "Częstotliwość dla SSB QRG w paśmie (musi być w Hz)";

$lang['options_bands_data_qrg'] = "DATA QRG";

$lang['options_bands_data_qrg_hint'] = "Częstotliwość dla DATA QRG w paśmie (musi być w Hz)";

$lang['options_bands_cw_qrg'] = "CW QRG";

$lang['options_bands_cw_qrg_hint'] = "Częstotliwość dla CW QRG w paśmie (musi być w Hz)";

$lang['options_bands_name_band'] = "Nazwa pasma (np. 20m)";
$lang['options_bands_name_bandgroup'] = "Nazwa grupy pasm (np. hf, vhf, uhf, shf)";
$lang['options_bands_delete_warning'] = "Ostrzeżenie! Czy na pewno chcesz usunąć następujące pasmo: ";

