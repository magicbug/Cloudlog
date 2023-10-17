<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
___________________________________________________________________________________________
Station Logbooks
___________________________________________________________________________________________
*/

$lang['station_logbooks'] = "Stationslogbücher";
$lang['station_log_description_header'] = "Was sind Stationslogbücher";
$lang['station_log_description_text'] = "Stationslogbücher ermöglichen es dir, Stationsstandorte zu gruppieren. Dadurch können Sie alle Standorte innerhalb einer Sitzung von den Logbuchbereichen bis zur Analyse sehen. Das ist besonders nützlich, wenn Sie an verschiedenen Standorten arbeiten, die jedoch zu derselben DXCC- oder VUCC-Zone gehören.";
$lang['station_log_create'] = "Erstelle Stationslogbuch";
$lang['station_log_status'] = "Status";
$lang['station_log_link'] = "Verknüpfung";
$lang['station_log_public_search'] = "Öffentl. Suche";
$lang['station_log_set_active'] = "Setze als aktives Logbuch";
$lang['station_log_active_logbook'] = "Aktives Logbuch";
$lang['station_log_edit_logbook'] = "Bearbeite Stationslogbuch";    // Full sentence will be generated 'Edit Station Logbook: [Logbook Name]'
$lang['station_log_confirm_delete'] = "Bist du sicher, dass du das folgende Logbuch löschen willst? Du wirst alle Standorte, welche hier verknüpft sind mit einem anderen Logbuch verknüpfen müssen.: ";
$lang['station_log_view_public'] = "Zeige die öffentl. Seite für das Logbuch: ";
$lang['station_log_create_name'] = "Stationslogbuch Name";
$lang['station_log_create_name_hint'] = "Du kannst das Stationslogbuch völlig frei benennen.";
$lang['station_log_edit_name_hint'] = "Kurzname für das Stationslogbuch. Zum Beispiel: Home Log (IO87IP)";
$lang['station_log_edit_name_update'] = "Aktualisiere Stationslogbuch Name";
$lang['station_log_public_slug'] = "Öffentlicher Link";
$lang['station_log_public_slug_hint'] = "Mit einem öffentlichen Link kannst du dieses Logbuch mit jedem über eine eigene Seite teilen. Dieser Linkzusatz darf jedoch nur Buchstaben & Zahlen enthalten.";
$lang['station_log_public_slug_format1'] = "So wird der Link aussehen:";
$lang['station_log_public_slug_format2'] = "[dein Link]";
$lang['station_log_public_slug_input'] = "Gib ein, wie der öffentliche Link lauten soll:";
$lang['station_log_public_slug_visit'] = "Besuche die öffentl. Seite";
$lang['station_log_public_search_hint'] = "Einschalten der Suchfunktion gibt Besuchern deiner öffentlichen Logbuch Seite die Möglichkeit über ein Suchfeld Einträge zu suchen. Die Suche deckt dabei nur dieses Logbuch ab.";
$lang['station_log_public_search_enabled'] = "Öffentliche Suche eingeschaltet";
$lang['station_log_select_avail_loc'] = "Wähle verfügbare Stationsstandorte";
$lang['station_log_link_loc'] = "Verknüpfe Standort";
$lang['station_log_linked_loc'] = "Verknüpfte Standorte";
$lang['station_log_no_linked_loc'] = "Keine verknüpften Standorte";




/*
___________________________________________________________________________________________
Station Locations
___________________________________________________________________________________________
*/

$lang['station_loc'] = 'Stationsstandort';
$lang['station_loc_plural'] = "Stationsstandorte";
$lang['station_loc_header_ln1'] = 'Stationsstandorte definieren die Orte, von denen aus du QRV bist. Dein Zuhause, Bei Freunden oder Unterwegs';
$lang['station_loc_header_ln2'] = 'Ähnlich wie Logbücher trennen die Stationsstandorte die entsprechenden QSO voneinander ab.';
$lang['station_loc_header_ln3'] = 'Es kann immer nur ein Stationsstandort aktiv sein. Welches das aktuell ist siehst du in der Liste an dem "Aktive Station" Symbol';
$lang['station_loc_create_header'] = 'Erstellung Stationsstandort';
$lang['station_loc_create'] = 'Erstelle einen neuen Stationsstandort';
$lang['station_loc_edit'] = 'Bearbeite Stationsstandort: ';
$lang['station_loc_updated_suff'] = ' aktualisiert.'; // nur letztes Wort im Satz "XYZ wurde aktualisiert"
$lang['station_loc_warning'] = 'Achtung: Du musst einen aktiven Stationsstandort auswählen. Gehe zu Rufzeichen -> Stationsstandorte um einen zu aktivieren.';
$lang['station_loc_reassign_at'] = 'Bitte mache die Zuordnung in ';
$lang['station_loc_warning_reassign'] = 'Aufgrund von Änderungen in Cloudlog musst du QSOs wieder einem Stationsstandort zuordnen.';
$lang['station_loc_name'] = 'Station Name';
$lang['station_loc_name_hint'] = 'Kurzname für den Stationsstandort. Zum Beispiel: Home (IO87IP)';
$lang['station_loc_callsign'] = 'Station Rufzeichen';
$lang['station_loc_callsign_hint'] = 'Station Rufzeichen. Zum Beispiel: HB9HIL/P';
$lang['station_loc_power'] = 'Station Sendeleistung (W)';
$lang['station_loc_power_hint'] = 'Standardmässig eingestellte Sendeleistung in Watt. Wird von CAT-Daten überschrieben.';
$lang['station_loc_emptylog'] = 'Lösche Log';
$lang['station_loc_confirm_active'] = 'Bist du sicher, dass du den folgenden Stationsstandort zum aktiven Standort machen möchtest?: ';
$lang['station_loc_set_active'] = 'Aktivieren';
$lang['station_loc_active'] = 'Aktive Station';
$lang['station_loc_claim_ownership'] = 'Claim Ownership';
$lang['station_loc_confirm_del_qso'] = 'Bist du sicher, dass du alle QSO an diesem Stationsstandort löschen möchtest?';
$lang['station_loc_confirm_del_stationlocation'] = 'Bist du sicher, dass du diesen Stationsstandort löschen willst?:';
$lang['station_loc_confirm_del_stationlocation_qso'] = 'Es werden alle QSO an diesem Stationsstandort gelöscht!';
$lang['station_loc_dxcc'] = 'Station DXCC';
$lang['station_loc_dxcc_hint'] = "Station DXCC Einteilung. Zum Beispiel: 'Federal Republic of Germany'";
$lang['station_loc_city'] = 'Station Stadt';
$lang['station_loc_city_hint'] = 'Station Stadt. Zum Beispiel: Berlin';
$lang['station_loc_state'] = 'Station Staat';
$lang['station_loc_state_hint'] = 'Station Staat. Nur verfügbar für einige Länder. Leer lassen falls nicht verfügbar.';
$lang['station_loc_county'] = 'Station County';
$lang['station_loc_county_hint'] = 'Station County (Nur für USA/Alaska/Hawaii).';
$lang['station_loc_gridsquare'] = 'Station Planquadrat';
$lang['station_loc_gridsquare_hint_ln1'] = "Station Planquadrat. Zum Beispiel: JO40IC. Wenn du dein Planquadrat nicht kennst <a href='https://zone-check.eu/?m=loc' target='_blank'>klicke hier</a>!";
$lang['station_loc_gridsquare_hint_ln2'] = "Wenn du genau auf der Linie eines Planquadrates bist kannst du mehrere Planquadrate mit Kommas getrennt eingeben. Zum Beispiel: IO77,IO78,IO87,IO88.";
$lang['station_loc_iota_hint_ln1'] = "IOTA Referenznummer der Station. Zum Beispiel: EU-005";
$lang['station_loc_iota_hint_ln2'] = "Du kannst IOTA Referenznummern auf der <a target='_blank' href='https://www.iota-world.org/iota-directory/annex-f-short-title-iota-reference-number-list.html'>IOTA World Website</a> nachschlagen.";
$lang['station_loc_sota_hint_ln1'] = "SOTA Referenznummer der Station. Du kannst SOTA Referenznummern auf der <a target='_blank' href='https://www.sotamaps.org/'>SOTA Maps Webseite</a> nachschlagen.";
$lang['station_loc_wwff_hint_ln1'] = "WWFF Referenznummer der Station. Du kannst WWFF Referenznummern auf der <a target='_blank' href='https://www.cqgma.org/mvs/'>GMA Map Webseite</a> nachschlagen.";
$lang['station_loc_pota_hint_ln1'] = "POTA Referenznummer der Station. Du kannst POTA Referenznummern auf der <a target='_blank' href='https://pota.app/#/map/'>POTA Map Webseite</a> nachschlagen.";
$lang['station_loc_signature'] = "Signaturen";
$lang['station_loc_signature_name'] = "Signatur Bezeichnung";
$lang['station_loc_signature_name_hint'] = "Signatur/Referenz der Station (z.B. GMA)..";
$lang['station_loc_signature_info'] = "Signatur Information";
$lang['station_loc_signature_info_hint'] = "Signatur/Referenz Information der Station (z.B. DA/NW-357).";
$lang['station_loc_eqsl_hint'] = "Der 'QTH Nickname' wie er in deinem eQSL Profil konfiguriert ist.";
$lang['station_loc_qrz_subscription'] = 'Abonnement erforderlich';
$lang['station_loc_qrz_hint'] = "Finde deinen 'QRZ Logbook API Key' in den <a href='https://logbook.qrz.com/logbook' target='_blank'>QRZ.com Logbuch Einstellungen";
$lang['station_loc_qrz_realtime_upload'] = 'QRZ.com Logbuch Echtzeit Upload';
$lang['station_loc_hrdlog_realtime_upload'] = "HRDLog.net Logbuch Echtzeit Upload";
$lang['station_loc_hrdlog_hint'] = "Erstelle deinen API Key auf <a href='http://www.hrdlog.net/EditUser.aspx' target='_blank'>HRDLog.net Benutzerprofil Seite";
$lang['station_loc_qo100_hint'] = "Erstelle deinen API Key auf <a href='https://qo100dx.club' target='_blank'>deiner QO-100 Dx Club's Profil Seite";
$lang['station_loc_qo100_realtime_upload'] = "QO-100 Dx Club Echtzeit Upload";
$lang['station_loc_oqrs_enabled'] = "OQRS aktivieren";
$lang['station_loc_oqrs_email_alert'] = "OQRS Email Benachrichtigung";
$lang['station_loc_oqrs_email_hint'] = "Stelle sicher, dass du E-Mail unter Admin/Globale Optionen konfiguriert hast.";
$lang['station_loc_oqrs_text'] = "OQRS Text";
$lang['station_loc_oqrs_text_hint'] = "Einige Informationen, die du zum QSL-Vorgang hinzufügen möchtest.";



