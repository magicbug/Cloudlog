<?php

defined('BASEPATH') OR exit('Direkter Zugriff auf Skripte ist nicht erlaubt');

// Tiles
$lang['qso_title_qso_map'] = 'QSO-Karte';
$lang['qso_title_suggestions'] = 'Vorschläge';
$lang['qso_title_previous_contacts'] = 'Vorherige Kontakte';
$lang['qso_title_times_worked_before'] = "mal vorher gearbeitet";
$lang['qso_title_image'] = 'Profilbild';
$lang['qso_previous_max_shown'] = "Es werden maximal 5 Kontakte angezeigt.";

// Quicklog on Dashboard
$lang['qso_quicklog_enter_callsign'] = 'QUICKLOG Rufzeichen';

// Input Help Text on the /QSO Display
$lang['qso_transmit_power_helptext'] = 'Gib die Ausgangsleistung in Watt an. Erfasse nur Zahlen bei der Eingabe.';

$lang['qso_sota_ref_helptext'] = 'Zum Beispiel: GM/NS-001.';
$lang['qso_wwff_ref_helptext'] = 'Zum Beispiel: DLFF-0069.';
$lang['qso_pota_ref_helptext'] = 'Zum Beispiel: PA-0150.';

$lang['qso_sig_helptext'] = 'Zum Beispiel: GMA';
$lang['qso_sig_info_helptext'] = 'Zum Beispiel: DA/NW-357';

$lang['qso_dok_helptext'] = 'Zum Beispiel: Q03';

$lang['qso_notes_helptext'] = 'Notizeninhalt wird nur innerhalb von Cloudlog genutzt und nicht an andere Dienste weitergegeben.';
$lang['qsl_notes_helptext'] = 'Dieser Notizeninhalt wird an QSL Services wie eqsl.cc exportiert.';

$lang['qso_eqsl_qslmsg_helptext'] = "Setze die eQSL Nachricht auf den Standardtext zurück.";

// error text //
$lang['qso_error_timeoff_less_timeon'] = "TimeOff is less than TimeOn";

// Button Text on /qso Display

$lang['qso_btn_reset_qso'] = 'Zurücksetzen';
$lang['qso_btn_save_qso'] = 'Speichere QSO';
$lang['qso_btn_edit_qso'] = 'Editiere QSO';
$lang['qso_delete_warning'] = "Warnung! Bist du sicher, dass du dieses QSO löschen willst mit ";

// QSO Details

$lang['qso_details'] = 'QSO Details';

$lang['fav_add'] = 'Band/Mode zu Favoriten hinzufügen';
$lang['qso_operator_callsign']='Operator Rufzeichen';

// Simple FLE (FastLogEntry)

$lang['qso_simplefle_info'] = "Was ist das?";
$lang['qso_simplefle_info_ln1'] = "Einfaches Fast Log Entry (FLE)";
$lang['qso_simplefle_info_ln2'] = "'Fast Log Entry' oder einfach 'FLE' ist ein System um QSO sehr schnell und effizient zu loggen. Aufgrund seiner Syntax sind nur minimale Eingaben erforderlich, um mit möglichst geringem Aufwand viele QSOs zu erfassen.";
$lang['qso_simplefle_info_ln3'] = "FLE wurde ursprünglich von DF3CB geschrieben. Auf seiner Website bietet er ein Programm für Windows an. Simple FLE wurde von OK2CQR auf Basis des FLE von DF3CB geschrieben und bietet eine Webapplikation zum Erfassen von QSOs.";
$lang['qso_simplefle_info_ln4'] = "Ein üblicher Anwendungsfall ist, wenn Sie Ihre Papier-Logbücher von einer Outdoor-Aktion erfassen müssen und SimpleFLE nun auch in Cloudlog verfügbar. Informationen über die allgemeine Syntax und Handhabung gibt es <a href='https://df3cb.com/fle/documentation/' target='_blank'>hier</a>.";
$lang['qso_simplefle_qso_data'] = "QSO Daten";
$lang['qso_simplefle_qso_date_hint'] = "Wenn du kein Datum auswählst, wird das heutige Datum verwendet.";
$lang['qso_simplefle_qso_list'] = "QSO Liste";
$lang['qso_simplefle_qso_list_total'] = "Total";
$lang['qso_simplefle_qso_date'] = "QSO Datum";
$lang['qso_simplefle_operator'] = "Operator";
$lang['qso_simplefle_operator_hint'] = "z.B. OK2CQR";
$lang['qso_simplefle_station_call_location'] = "Stationsstandort";
$lang['qso_simplefle_station_call_location_hint'] = "Falls du von einem neuen Standort oder mit einem neuen Rufzeichen gefunkt hast, erstelle erst einen neuen <a href=". site_url('station') . ">Stationsstandort</a>";
$lang['qso_simplefle_utc_time'] = "Aktuelle UTC Zeit";
$lang['qso_simplefle_enter_the_data'] = "Gibt hier die Daten ein";
$lang['qso_simplefle_syntax_help_close_w_sample'] = "Schliesse und Lade Beispiel Daten";
$lang['qso_simplefle_reload'] = "Aktualisiere QSO Liste";
$lang['qso_simplefle_save'] = "Speichere in Cloudlog";
$lang['qso_simplefle_clear'] = "Lösche QSO Daten";
$lang['qso_simplefle_refs_hint'] = "Die Ref. kann entweder <u>S</u>OTA, <u>I</u>OTA, <u>P</u>OTA oder <u>W</u>WFF sein.";

$lang['qso_simplefle_error_band'] = "Band fehlt!";
$lang['qso_simplefle_error_mode'] = "Mode fehlt!";
$lang['qso_simplefle_error_time'] = "Zeit nicht gesetzt!";
$lang['qso_simplefle_error_stationcall'] = "Stationsstandort nicht ausgewählt";
$lang['qso_simplefle_error_operator'] = "'Operator' Feld ist leer";
$lang['qso_simplefle_warning_reset'] = "Warnung! Willst du wirklich alles zurücksetzen?";
$lang['qso_simplefle_warning_missing_band_mode'] = "Warnung! Du kannst die QSO Liste nicht loggen, da bei manchen QSO das Band und/oder der Mode fehlt!";
$lang['qso_simplefle_warning_missing_time'] = "Warnung! Du kannst die QSO Liste nicht loggen, da bei manchen QSO die Zeit fehlt!";
$lang['qso_simplefle_warning_example_data'] = "Achtung! Das Daten Feld enthält Beispiel Daten. Lösche zuerst die QSO Daten!";
$lang['qso_simplefle_confirm_save_to_log'] = "Bist du dir sicher, dass du diese QSO loggen und die Eingabe zurücksetzen willst?";
$lang['qso_simplefle_success_save_to_log_header'] = "QSO geloggt!";
$lang['qso_simplefle_success_save_to_log'] = "Die QSO wurden erfolgreich im Logbuch gespeichert!";
$lang['qso_simplefle_error_date'] = "Ungültiges Datum";

$lang['qso_simplefle_syntax_help_button'] = "Syntax Hilfe";
$lang['qso_simplefle_syntax_help_title'] = "Syntax für FLE";
$lang['qso_simplefle_syntax_help_ln1'] = "Bevor du ein QSO loggst, beachte bitte die grundlegenden Regeln.";
$lang['qso_simplefle_syntax_help_ln2'] = "- Jedes neue QSO sollte in einer neuen Zeile stehen.";
$lang['qso_simplefle_syntax_help_ln3'] = "- In jeder neuen Zeile schreibst du nur Daten, die sich vom vorherigen QSO geändert haben.";
$lang['qso_simplefle_syntax_help_ln4'] = "Um zu beginnen, stelle sicher, dass du die Felder auf der linken Seite bereits mit Datum, Stationsrufzeichen/-standort und Rufzeichen des Operators ausgefüllt hast. Die wichtigsten Daten umfassen das Band (oder QRG in MHz, z.B. '7.145'), Mode und Zeit. Nach der Zeit gibst du das erste QSO an, was im wesentlichen das Rufzeichen ist.";
$lang['qso_simplefle_syntax_help_ln5'] = "Zum Beispiel ein QSO um 21:34 Uhr (UTC) mit 2M0SQL auf 20m SSB.";
$lang['qso_simplefle_syntax_help_ln6'] = "Wenn du keine RST-Informationen angibst, verwendet die Syntax 59 (599 für Daten). Unser nächstes QSO war nicht auf beiden Seiten 59, also geben wir die Informationen zuerst mit gesendeter RST an. Es war 2 Minuten später als das erste QSO.";
$lang['qso_simplefle_syntax_help_ln7'] = "Das erste QSO war um 21:34 Uhr, und das zweite 2 Minuten später um 21:36 Uhr. Wir schreiben '6' für die geänderte Minute, da dies die einzige geänderte Information ist. Die Eingaben zum Band und Mode haben sich nicht geändert, daher entfallen diese Daten hier.";
$lang['qso_simplefle_syntax_help_ln8'] = "Für unser nächstes QSO um 21:40 Uhr am 14.05.2021 haben wir das Band auf 40m geändert, sind aber immer noch auf SSB. Wenn keine RST-Informationen angegeben sind, verwendet die Syntax bei jedem neuen QSO 59. Daher können wir ein weiteres QSO hinzufügen welches um die exakt selbe Zeit zwei Tage später stattfand. Das Datum muss im Format YYYY-MM-DD eingegeben werden.";
$lang['qso_simplefle_syntax_help_ln9'] = "Für weitere Informationen zur Syntax siehe die Website von DF3CB <a href='https://df3cb.com/fle/documentation/' target='_blank'>hier.</a>";
         
