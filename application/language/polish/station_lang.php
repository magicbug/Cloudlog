<?php

defined('BASEPATH') OR exit('Brak bezpośredniego dostępu do skryptu');

/*
______________________________________________________________________________________________________
Dzienniki stacji
___________________________________________________________________________________________
*/

$lang['station_logbooks'] = "Dzienniki stacji";
$lang['station_logbooks_description_header'] = "Czym są dzienniki stacji";
$lang['station_logbooks_description_text'] = "Dzienniki stacji umożliwiają grupowanie lokalizacji stacji, co pozwala zobaczyć wszystkie lokalizacje w jednej sesji, od obszarów dziennika po analizy. Świetne rozwiązanie, gdy działasz w wielu lokalizacjach, ale są one częścią tego samego okręgu DXCC lub VUCC.";
$lang['station_logbooks_create'] = "Utwórz dziennik stacji";
$lang['station_logbooks_status'] = "Status";
$lang['station_logbooks_link'] = "Link";
$lang['station_logbooks_public_search'] = "Wyszukiwanie publiczne";
$lang['station_logbooks_set_active'] = "Ustaw jako aktywny dziennik";
$lang['station_logbooks_active_logbook'] = "Aktywny dziennik";
$lang['station_logbooks_edit_logbook'] = "Edytuj dziennik stacji"; // Zostanie wygenerowane pełne zdanie 'Edytuj dziennik stacji: [Nazwa dziennika]'
$lang['station_logbooks_confirm_delete'] = "Czy na pewno chcesz usunąć następujący dziennik stacji? Musisz ponownie połączyć wszystkie lokalizacje połączone tutaj z innym dziennikiem.: ";
$lang['station_logbooks_view_public'] = "Wyświetl stronę publiczną dla dziennika: ";
$lang['station_logbooks_create_name'] = "Nazwa dziennika stacji";
$lang['station_logbooks_create_name_hint'] = "Możesz nazwać dziennik stacji w dowolny sposób.";
$lang['station_logbooks_edit_name_hint'] = "Skrócona nazwa dziennika stacji. Na przykład: Home Log (IO87IP)";
$lang['station_logbooks_edit_name_update'] = "Aktualizuj nazwę dziennika stacji";
$lang['station_logbooks_public_slug'] = "Publiczny slug";
$lang['station_logbooks_public_slug_hint'] = "Ustawienie publicznego slug pozwala na udostępnianie dziennika dowolnej osobie za pośrednictwem niestandardowego adresu witryny, ten slug może zawierać tylko litery i cyfry.";
$lang['station_logbooks_public_slug_format1'] = "Później wygląda to tak:";
$lang['station_logbooks_public_slug_format2'] = "[Twój slug]";
$lang['station_logbooks_public_slug_input'] = "Wpisz wybór Public Slug";
$lang['station_logbooks_public_slug_visit'] = "Odwiedź stronę publiczną";
$lang['station_logbooks_public_search_hint'] = "Włączenie funkcji wyszukiwania publicznego oferuje pole wprowadzania wyszukiwania na stronie dziennika publicznego dostępnej za pośrednictwem publicznego slug. Wyszukiwanie obejmuje tylko ten dziennik.";
$lang['station_logbooks_public_search_enabled'] = "Wyszukiwanie publiczne włączone";
$lang['station_logbooks_select_avail_loc'] = "Wybierz dostępne lokalizacje stacji";
$lang['station_logbooks_link_loc'] = "Lokalizacja łącza";
$lang['station_logbooks_linked_loc'] = "Połączone lokalizacje";
$lang['station_logbooks_no_linked_loc'] = "Brak połączonych lokalizacji";
$lang['station_logbooks_unlink_station_location'] = "Odłącz lokalizację stacji";

/*
___________________________________________________________________________________________________________
Lokalizacje stacji
___________________________________________________________________________________________
*/

$lang['station_location'] = 'Lokalizacja stacji';
$lang['station_location_plural'] = "Lokalizacje stacji";
$lang['station_location_header_ln1'] = 'Lokalizacje stacji definiują lokalizacje operacyjne, takie jak Twoje QTH, QTH znajomego lub stacja przenośna.';
$lang['station_location_header_ln2'] = 'Podobnie jak dzienniki pokładowe, profil stacji przechowuje zestaw QSO razem.';
$lang['station_location_header_ln3'] = 'Tylko jedna stacja może być aktywna w danym momencie. W poniższej tabeli jest to pokazane za pomocą odznaki -Aktywna stacja-.';
$lang['station_location_create_header'] = 'Utwórz lokalizację stacji';
$lang['station_location_create'] = 'Utwórz lokalizację stacji';
$lang['station_location_edit'] = 'Edytuj lokalizację stacji: ';
$lang['station_location_updated_suff'] = 'Zaktualizowano.';
$lang['station_location_warning'] = 'Uwaga: Musisz ustawić aktywną lokalizację stacji. Przejdź do Callsign->Station Location, aby wybrać jedną.';$lang['station_location_reassign_at'] = 'Przypisz je ponownie w ';
$lang['station_location_warning_reassign'] = 'Ze względu na ostatnie zmiany w Cloudlog musisz ponownie przypisać QSO do swoich profili stacji.';
$lang['station_location_name'] = 'Nazwa profilu';
$lang['station_location_name_hint'] = 'Skrócona nazwa lokalizacji stacji. Na przykład: Home (IO87IP)';
$lang['station_location_callsign'] = 'Znak wywoławczy stacji';
$lang['station_location_callsign_hint'] = 'Znak wywoławczy stacji. Na przykład: 2M0SQL/P';
$lang['station_location_power'] = 'Moc stacji (W)';
$lang['station_location_power_hint'] = 'Domyślna moc stacji w watach. Nadpisane przez CAT.';
$lang['station_location_emptylog'] = 'Pusty dziennik';
$lang['station_location_confirm_active'] = 'Czy na pewno chcesz ustawić następującą stację jako aktywną: ';
$lang['station_location_set_active'] = 'Ustaw jako aktywną';
$lang['station_location_active'] = 'Aktywna stacja';
$lang['station_location_claim_ownership'] = 'Zgłoś własność';
$lang['station_location_confirm_del_qso'] = 'Czy na pewno chcesz usunąć wszystkie QSO w tym profilu stacji?';
$lang['station_location_confirm_del_stationlocation'] = 'Czy na pewno chcesz usunąć profil stacji ';
$lang['station_location_confirm_del_stationlocation_qso'] = 'Czy to spowoduje usunięcie wszystkich QSO w tym profilu stacji?';
$lang['station_location_dxcc'] = 'Stacja DXCC';
$lang['station_location_dxcc_hint'] = 'Jednostka stacji DXCC. Na przykład: Szkocja';
$lang['station_location_dxcc_warning'] = "Zatrzymaj się na chwilę. Wybrany przez Ciebie DXCC jest nieaktualny i nieważny. Sprawdź, który DXCC dla tej konkretnej lokalizacji jest poprawny. Jeśli masz pewność, zignoruj ​​to ostrzeżenie.";$lang['station_location_city'] = 'Miasto stacji';
$lang['station_location_city_hint'] = 'Miasto stacji. Na przykład: Inverness';
$lang['station_location_state'] = 'Stan stacji';
$lang['station_location_state_hint'] = 'Stan stacji. Dotyczy tylko niektórych krajów. Pozostaw puste, jeśli nie dotyczy.';
$lang['station_location_county'] = 'Hrabstwo stacji';
$lang['station_location_county_hint'] = 'Hrabstwo stacji (używane tylko dla USA/Alaski/Hawajów).';
$lang['station_location_gridsquare'] = 'Station Gridsquare';
$lang['station_location_gridsquare_hint_ln1'] = "Siatka stacji. Na przykład: IO87IP. Jeśli nie znasz swojego kwadratu siatki, <a href='https://zone-check.eu/?m=loc' target='_blank'>kliknij tutaj</a>!";
$lang['station_location_gridsquare_hint_ln2'] = "Jeśli znajdujesz się na linii siatki, wprowadź wiele kwadratów siatki oddzielonych przecinkami. Na przykład: IO77,IO78,IO87,IO88.";
$lang['station_location_iota_hint_ln1'] = "Odniesienie do stacji IOTA. Na przykład: EU-005";
$lang['station_location_iota_hint_ln2'] = "Możesz sprawdzić odniesienia IOTA na stronie <a target='_blank' href='https://www.iota-world.org/iota-directory/annex-f-short-title-iota-reference-number-list.html'>IOTA World</a>.";
$lang['station_location_sota_hint_ln1'] = "Odniesienia do SOTA stacji. Możesz sprawdzić odniesienia SOTA na stronie <a target='_blank' href='https://www.sotamaps.org/'>SOTA Maps</a>.";
$lang['station_location_wwff_hint_ln1'] = "Odniesienie do stacji WWFF. Odniesienia do WWFF można sprawdzić na stronie <a target='_blank' href='https://www.cqgma.org/mvs/'>GMA Map</a>.";
$lang['station_location_pota_hint_ln1'] = "Odniesienie do stacji POTA. Odniesienia do POTA można sprawdzić na stronie <a target='_blank' href='https://pota.app/#/map/'>POTA Map</a>.";
$lang['station_location_signature'] = "Podpis";
$lang['station_location_signature_name'] = "Nazwa podpisu";
$lang['station_location_signature_name_hint'] = "Podpis stacji (np. GMA).";
$lang['station_location_signature_info'] = "Informacje o podpisie";
$lang['station_location_signature_info_hint'] = "Informacje o podpisie stacji (np. DA/NW-357).";
$lang['station_location_eqsl_hint'] = 'Pseudonim QTH skonfigurowany w profilu eQSL';
$lang['station_location_eqsl_defaultqslmsg'] = "Domyślny QSLMSG";
$lang['station_location_eqsl_defaultqslmsg_hint'] = "Zdefiniuj domyślną wiadomość, która zostanie wypełniona i wysłana dla każdego QSO dla tej lokalizacji stacji.";
$lang['station_location_qrz_subscription'] = 'Wymagana subskrypcja';
$lang['station_location_qrz_hint'] = "Znajdź swój klucz API na <a href='https://logbook.qrz.com/logbook' target='_blank'>stronie ustawień dziennika QRZ.com";
$lang['station_location_qrz_realtime_upload'] = 'Przesyłanie dziennika QRZ.com w czasie rzeczywistym';
$lang['station_location_hrdlog_username'] = "Nazwa użytkownika HRDLog.net";
$lang['station_location_hrdlog_username_hint'] = "Nazwa użytkownika, pod którą jesteś zarejestrowany w HRDlog.net (zwykle jest to Twój znak wywoławczy).";
$lang['station_location_hrdlog_code'] = "Klucz API HRDLog.net";
$lang['station_location_hrdlog_realtime_upload'] = "Przesyłanie dziennika HRDLog.net w czasie rzeczywistym";
$lang['station_location_hrdlog_code_hint'] = "Utwórz swój kod API na <a href='http://www.hrdlog.net/EditUser.aspx' target='_blank'>stronie profilu użytkownika HRDLog.net";
$lang['station_location_qo100_hint'] = "Utwórz swój klucz API na <a href='https://qo100dx.club' target='_blank'>stronie profilu QO-100 Dx Club";
$lang['station_location_qo100_realtime_upload'] = "Przesyłanie danych w czasie rzeczywistym QO-100 Dx Club";
$lang['station_location_oqrs_enabled'] = "Włączono OQRS";
$lang['station_location_oqrs_email_alert'] = "Alert e-mail OQRS";
$lang['station_location_oqrs_email_hint'] = "Upewnij się, że e-mail jest skonfigurowany w opcjach administratora i globalnych.";
$lang['station_location_oqrs_text'] = "Tekst OQRS";
$lang['station_location_oqrs_text_hint'] = "Kilka informacji, które chcesz dodać odnośnie QSL'ing.";
$lang['station_location_clublog_realtime_upload']='Przesyłanie w czasie rzeczywistym ClubLog';


