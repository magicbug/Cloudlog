<?php

defined('BASEPATH') OR exit('Brak bezpośredniego dostępu do skryptu');

// Kafelki
$lang['qso_title_qso_map'] = 'Mapa łączności';
$lang['qso_title_suggestions'] = 'Sugestie';
$lang['qso_title_previous_contacts'] = 'Poprzednie łączności';
$lang['qso_title_times_worked_before'] = "times working before";
$lang['qso_title_image'] = 'Zdjęcie profilowe';
$lang['qso_previous_max_shown'] = "Wyświetlono maks. 5 poprzednich kontaktów";

// Quicklog na pulpicie nawigacyjnym
$lang['qso_quicklog_enter_callsign'] = 'QUICKLOG Enter Callsign';

// Wprowadź tekst pomocy na wyświetlaczu /QSO
$lang['qso_transmit_power_helptext'] = 'Wpisz wartość mocy w zegarku. W polu podaj same cyfry.';

$lang['qso_sota_ref_helptext'] = 'Na przykład: GM/NS-001.';
$lang['qso_wwff_ref_helptext'] = 'Na przykład: DLFF-0069.';
$lang['qso_pota_ref_helptext'] = 'Na przykład: PA-0150.';

$lang['qso_sig_helptext'] = 'Na przykład: GMA';
$lang['qso_sig_info_helptext'] = 'Na przykład: DA/NW-357';

$lang['qso_dok_helptext'] = 'Na przykład: Q03';

$lang['qso_notes_helptext'] = 'Notatka jest tylko udostępniona w Cloudlog, nie jest wysyłana z potwierdzeniem.';
$lang['qsl_notes_helptext'] = 'Treść tej notatki jest eksportowana do usług QSL, takich jak eqsl.cc.';

$lang['qso_eqsl_qslmsg_helptext'] = "Pobierz domyślną wiadomość dla eQSL dla tej stacji.";

// tekst błędu //
$lang['qso_error_timeoff_less_timeon'] = "Czas wolny jest krótszy niż czas włączenia";

// Tekst przycisku na wyświetlaczu /qso

$lang['qso_btn_reset_qso'] = 'Resetuj';
$lang['qso_btn_save_qso'] = 'Zapisz QSO';
$lang['qso_btn_edit_qso'] = 'Edytuj QSO';
$lang['qso_delete_warning'] = "Uwaga! Czy na pewno chcesz usunąć QSO z ";

// Szczegóły QSO

$lang['qso_details'] = 'Szczegóły QSO';

$lang['fav_add'] = 'Dodaj pasmo/moduł do ulubionych';
$lang['qso_operator_callsign'] = 'Znak wywoławczy operatora';

// Prosty FLE (FastLogEntry)

$lang['qso_simplefle_info'] = "Co to jest?";
$lang['qso_simplefle_info_ln1'] = "Prosty szybki wpis do dziennika (FLE)";
$lang['qso_simplefle_info_ln2'] = "'Szybki wpis do dziennika' lub po prostu 'FLE' to system do bardzo szybkiego i wydajnego rejestrowania QSO. Ze względu na składnię, do rejestrowania wielu QSO przy jak najmniejszym wysiłku wymagane jest tylko minimalne wprowadzanie danych.";
$lang['qso_simplefle_info_ln3'] = "FLE został pierwotnie napisany przez DF3CB. Oferuje on program dla systemu Windows na swojej stronie internetowej. Simple FLE został napisany przez OK2CQR na podstawie FLE DF3CB i zapewnia interfejs sieciowy do rejestrowania QSO.";
$lang['qso_simplefle_info_ln4'] = "Powszechnym przypadkiem użycia jest importowanie dzienników papierowych z sesji na świeżym powietrzu, a teraz SimpleFLE jest również dostępny w Cloudlog. Informacje o składni i sposobie działania FLE można znaleźć <a href='https://df3cb.com/fle/documentation/' target='_blank'>tutaj</a>.";
$lang['qso_simplefle_qso_data'] = "Dane QSO";
$lang['qso_simplefle_qso_date_hint'] = "Jeśli nie wybierzesz daty, zostanie użyta dzisiejsza data.";
$lang['qso_simplefle_qso_list'] = "Lista QSO";
$lang['qso_simplefle_qso_list_total'] = "Suma";
$lang['qso_simplefle_qso_date'] = "Data QSO";
$lang['qso_simplefle_operator'] = "Operator";
$lang['qso_simplefle_operator_hint'] = "np. OK2CQR";
$lang['qso_simplefle_station_call_location'] = "Wywołanie stacji/Lokalizacja";
$lang['qso_simplefle_station_call_location_hint'] = "Jeśli operujesz z nowej lokalizacji, najpierw utwórz nową <a href=". site_url('station') . ">Lokalizację stacji</a>";
$lang['qso_simplefle_utc_time'] = "Bieżący czas UTC";
$lang['qso_simplefle_enter_the_data'] = "Wprowadź dane";
$lang['qso_simplefle_syntax_help_close_w_sample'] = "Zamknij i wczytaj przykładowe dane";
$lang['qso_simplefle_reload'] = "Ponownie wczytaj listę QSO";
$lang['qso_simplefle_save'] = "Zapisz w Cloudlog";
$lang['qso_simplefle_clear'] = "Wyczyść sesję rejestrowania";
$lang['qso_simplefle_refs_hint'] = "Referencje mogą być <u>S</u>OTA, <u>I</u>OTA, <u>P</u>OTA lub <u>W</u>WFF";

$lang['qso_simplefle_error_band'] = "Brakuje pasma!";
$lang['qso_simplefle_error_mode'] = "Brakuje trybu!";
$lang['qso_simplefle_error_time'] = "Czas nie jest ustawiony!";
$lang['qso_simplefle_error_stationcall'] = "Wywołanie stacji nie jest wybrane";
$lang['qso_simplefle_error_operator'] = "Pole 'Operator' jest puste";
$lang['qso_simplefle_warning_reset'] = "Ostrzeżenie! Czy na pewno chcesz zresetować wszystko?";
$lang['qso_simplefle_warning_missing_band_mode'] = "Ostrzeżenie! Nie możesz zalogować listy QSO, ponieważ niektóre QSO nie mają zdefiniowanego pasma i/lub trybu!";
$lang['qso_simplefle_warning_missing_time'] = "Ostrzeżenie! Nie możesz zalogować listy QSO, ponieważ niektóre QSO nie mają zdefiniowanego czasu!";$lang['qso_simplefle_warning_example_data'] = "Uwaga! Pole danych zawiera przykładowe dane. Najpierw wyczyść sesję rejestrowania!";
$lang['qso_simplefle_confirm_save_to_log'] = "Czy na pewno chcesz dodać te QSO do dziennika i wyczyścić sesję?";
$lang['qso_simplefle_success_save_to_log_header'] = "QSO zarejestrowane!";
$lang['qso_simplefle_success_save_to_log'] = "QSO zostały pomyślnie zarejestrowane w dzienniku!";
$lang['qso_simplefle_error_date'] = "Nieprawidłowa data";

$lang['qso_simplefle_syntax_help_button'] = "Pomoc dotycząca składni";
$lang['qso_simplefle_syntax_help_title'] = "Składnia dla FLE";
$lang['qso_simplefle_syntax_help_ln1'] = "Przed rozpoczęciem rejestrowania QSO zapoznaj się z podstawowymi zasadami.";
$lang['qso_simplefle_syntax_help_ln2'] = "- Każde nowe QSO powinno znajdować się w nowym wierszu.";
$lang['qso_simplefle_syntax_help_ln3'] = "- W każdym nowym wierszu zapisuj tylko dane, które uległy zmianie od poprzedniego QSO.";
$lang['qso_simplefle_syntax_help_ln4'] = "Na początek upewnij się, że wypełniłeś formularz po lewej stronie, podając datę, wywołanie stacji i wywołanie operatora. Główne dane obejmują pasmo (lub QRG w MHz, np. '7.145'), tryb i czas. Po czasie podajesz pierwsze QSO, które jest zasadniczo znakiem wywoławczym.";
$lang['qso_simplefle_syntax_help_ln5'] = "Na przykład QSO, które rozpoczęło się o 21:34 (UTC) z 2M0SQL na 20m SSB.";
$lang['qso_simplefle_syntax_help_ln6'] = "Jeśli nie podasz żadnych informacji RST, składnia użyje 59 (599 dla danych). Nasze następne QSO nie było 59 po obu stronach, więc najpierw podajemy informacje z wysłanym RST. Było to 2 minuty później niż pierwsze QSO.";
$lang['qso_simplefle_syntax_help_ln7'] = "Pierwsze QSO było o 21:34, a drugie 2 minuty później o 21:36. Zapisujemy 6, ponieważ są to jedyne dane, które się tutaj zmieniły. Informacje o paśmie i trybie nie uległy zmianie, więc te dane są pomijane.";
$lang['qso_simplefle_syntax_help_ln8'] = "W przypadku naszego następnego QSO o 21:40 14 maja 2021 r. zmieniliśmy pasmo na 40 m, ale nadal na SSB. Jeśli nie podano informacji RST, składnia będzie używać 59 dla każdego nowego QSO. Dlatego możemy dodać kolejne QSO, które miało miejsce dokładnie o tej samej porze dwa dni później. Data musi być w formacie RRRR-MM-DD.";
$lang['qso_simplefle_syntax_help_ln9'] = "Aby uzyskać więcej informacji na temat składni, sprawdź stronę internetową DF3CB <a href='https://df3cb.com/fle/documentation/' target='_blank'>tutaj.</a>";