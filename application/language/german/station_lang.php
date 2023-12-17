<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
___________________________________________________________________________________________
Station Logbooks
___________________________________________________________________________________________
*/

$lang['station_logbooks'] = "Stationslogbücher";
$lang['station_logbooks_description_header'] = "Was sind Stationslogbücher";
$lang['station_logbooks_description_text'] = "Stationslogbücher ermöglichen es dir, Stationsstandorte zu gruppieren. Dadurch können Sie alle Standorte innerhalb einer Sitzung von den Logbuchbereichen bis zur Analyse sehen. Das ist besonders nützlich, wenn Sie an verschiedenen Standorten arbeiten, die jedoch zu derselben DXCC- oder VUCC-Zone gehören.";
$lang['station_logbooks_create'] = "Erstelle Stationslogbuch";
$lang['station_logbooks_status'] = "Status";
$lang['station_logbooks_link'] = "Verknüpfung";
$lang['station_logbooks_public_search'] = "Öffentl. Suche";
$lang['station_logbooks_set_active'] = "Setze als aktives Logbuch";
$lang['station_logbooks_active_logbook'] = "Aktives Logbuch";
$lang['station_logbooks_edit_logbook'] = "Bearbeite Stationslogbuch";    // Full sentence will be generated 'Edit Station Logbook: [Logbook Name]'
$lang['station_logbooks_confirm_delete'] = "Bist du sicher, dass du das folgende Logbuch löschen willst? Du wirst alle Standorte, welche hier verknüpft sind mit einem anderen Logbuch verknüpfen müssen.: ";
$lang['station_logbooks_view_public'] = "Zeige die öffentl. Seite für das Logbuch: ";
$lang['station_logbooks_create_name'] = "Stationslogbuch Name";
$lang['station_logbooks_create_name_hint'] = "Du kannst das Stationslogbuch völlig frei benennen.";
$lang['station_logbooks_edit_name_hint'] = "Kurzname für das Stationslogbuch. Zum Beispiel: Home Log (IO87IP)";
$lang['station_logbooks_edit_name_update'] = "Aktualisiere Stationslogbuch Name";
$lang['station_logbooks_public_slug'] = "Öffentlicher Link";
$lang['station_logbooks_public_slug_hint'] = "Mit einem öffentlichen Link kannst du dieses Logbuch mit jedem über eine eigene Seite teilen. Dieser Linkzusatz darf jedoch nur Buchstaben & Zahlen enthalten.";
$lang['station_logbooks_public_slug_format1'] = "So wird der Link aussehen:";
$lang['station_logbooks_public_slug_format2'] = "[dein Link]";
$lang['station_logbooks_public_slug_input'] = "Gib ein, wie der öffentliche Link lauten soll:";
$lang['station_logbooks_public_slug_visit'] = "Besuche die öffentl. Seite";
$lang['station_logbooks_public_search_hint'] = "Einschalten der Suchfunktion gibt Besuchern deiner öffentlichen Logbuch Seite die Möglichkeit über ein Suchfeld Einträge zu suchen. Die Suche deckt dabei nur dieses Logbuch ab.";
$lang['station_logbooks_public_search_enabled'] = "Öffentliche Suche eingeschaltet";
$lang['station_logbooks_select_avail_loc'] = "Wähle verfügbare Stationsstandorte";
$lang['station_logbooks_link_loc'] = "Verknüpfe Standort";
$lang['station_logbooks_linked_loc'] = "Verknüpfte Standorte";
$lang['station_logbooks_no_linked_loc'] = "Keine verknüpften Standorte";
$lang['station_logbooks_unlink_station_location'] = "Entferne Verknüpfung";



/*
___________________________________________________________________________________________
Station Locations
___________________________________________________________________________________________
*/

$lang['station_location'] = 'Stationsstandort';
$lang['station_location_plural'] = "Stationsstandorte";
$lang['station_location_header_ln1'] = 'Stationsstandorte definieren die Orte, von denen aus du QRV bist. Dein Zuhause, Bei Freunden oder Unterwegs';
$lang['station_location_header_ln2'] = 'Ähnlich wie Logbücher trennen die Stationsstandorte die entsprechenden QSO voneinander ab.';
$lang['station_location_header_ln3'] = 'Es kann immer nur ein Stationsstandort aktiv sein. Welches das aktuell ist siehst du in der Liste an dem "Aktive Station" Symbol';
$lang['station_location_create_header'] = 'Erstellung Stationsstandort';
$lang['station_location_create'] = 'Erstelle einen neuen Stationsstandort';
$lang['station_location_edit'] = 'Bearbeite Stationsstandort: ';
$lang['station_location_updated_suff'] = ' aktualisiert.'; // nur letztes Wort im Satz "XYZ wurde aktualisiert"
$lang['station_location_warning'] = 'Achtung: Du musst einen aktiven Stationsstandort auswählen. Gehe zu Rufzeichen -> Stationsstandorte um einen zu aktivieren.';
$lang['station_location_reassign_at'] = 'Bitte mache die Zuordnung in ';
$lang['station_location_warning_reassign'] = 'Aufgrund von Änderungen in Cloudlog musst du QSOs wieder einem Stationsstandort zuordnen.';
$lang['station_location_name'] = 'Station Name';
$lang['station_location_name_hint'] = 'Kurzname für den Stationsstandort. Zum Beispiel: Home (IO87IP)';
$lang['station_location_callsign'] = 'Station Rufzeichen';
$lang['station_location_callsign_hint'] = 'Station Rufzeichen. Zum Beispiel: HB9HIL/P';
$lang['station_location_power'] = 'Station Sendeleistung (W)';
$lang['station_location_power_hint'] = 'Standardmässig eingestellte Sendeleistung in Watt. Wird von CAT-Daten überschrieben.';
$lang['station_location_emptylog'] = 'Lösche Log';
$lang['station_location_confirm_active'] = 'Bist du sicher, dass du den folgenden Stationsstandort zum aktiven Standort machen möchtest?: ';
$lang['station_location_set_active'] = 'Aktivieren';
$lang['station_location_active'] = 'Aktive Station';
$lang['station_location_claim_ownership'] = 'Claim Ownership';
$lang['station_location_confirm_del_qso'] = 'Bist du sicher, dass du alle QSO an diesem Stationsstandort löschen möchtest?';
$lang['station_location_confirm_del_stationlocation'] = 'Bist du sicher, dass du diesen Stationsstandort löschen willst?:';
$lang['station_location_confirm_del_stationlocation_qso'] = 'Es werden alle QSO an diesem Stationsstandort gelöscht!';
$lang['station_location_dxcc'] = 'Station DXCC';
$lang['station_location_dxcc_hint'] = "Station DXCC Einteilung. Zum Beispiel: 'Federal Republic of Germany'";
$lang['station_location_dxcc_warning'] = "Stoppe hier für einen Moment. Das von dir gewählte DXCC ist abgelaufen und nicht mehr gültig. Überprüfe, welches das richtige DXCC für den Standort der Station ist. Als Beispiel: Deutschland ist nicht mehr \'Germany\' sondern \'Federal Republic of Germany\'. Wenn du dir sicher bist, ignoriere diese Warnung.";
$lang['station_location_city'] = 'Station Stadt';
$lang['station_location_city_hint'] = 'Station Stadt. Zum Beispiel: Berlin';
$lang['station_location_state'] = 'Station Staat';
$lang['station_location_state_hint'] = 'Station Staat. Nur verfügbar für einige Länder. Leer lassen falls nicht verfügbar.';
$lang['station_location_county'] = 'Station County';
$lang['station_location_county_hint'] = 'Station County (Nur für USA/Alaska/Hawaii).';
$lang['station_location_gridsquare'] = 'Station Planquadrat';
$lang['station_location_gridsquare_hint_ln1'] = "Station Planquadrat. Zum Beispiel: JO40IC. Wenn du dein Planquadrat nicht kennst <a href='https://zone-check.eu/?m=loc' target='_blank'>klicke hier</a>!";
$lang['station_location_gridsquare_hint_ln2'] = "Wenn du genau auf der Linie eines Planquadrates bist kannst du mehrere Planquadrate mit Kommas getrennt eingeben. Zum Beispiel: IO77,IO78,IO87,IO88.";
$lang['station_location_iota_hint_ln1'] = "IOTA Referenznummer der Station. Zum Beispiel: EU-005";
$lang['station_location_iota_hint_ln2'] = "Du kannst IOTA Referenznummern auf der <a target='_blank' href='https://www.iota-world.org/iota-directory/annex-f-short-title-iota-reference-number-list.html'>IOTA World Website</a> nachschlagen.";
$lang['station_location_sota_hint_ln1'] = "SOTA Referenznummer der Station. Du kannst SOTA Referenznummern auf der <a target='_blank' href='https://www.sotamaps.org/'>SOTA Maps Webseite</a> nachschlagen.";
$lang['station_location_wwff_hint_ln1'] = "WWFF Referenznummer der Station. Du kannst WWFF Referenznummern auf der <a target='_blank' href='https://www.cqgma.org/mvs/'>GMA Map Webseite</a> nachschlagen.";
$lang['station_location_pota_hint_ln1'] = "POTA Referenznummer der Station. Du kannst POTA Referenznummern auf der <a target='_blank' href='https://pota.app/#/map/'>POTA Map Webseite</a> nachschlagen.";
$lang['station_location_signature'] = "Signaturen";
$lang['station_location_signature_name'] = "Signatur Bezeichnung";
$lang['station_location_signature_name_hint'] = "Signatur/Referenz der Station (z.B. GMA)..";
$lang['station_location_signature_info'] = "Signatur Information";
$lang['station_location_signature_info_hint'] = "Signatur/Referenz Information der Station (z.B. DA/NW-357).";
$lang['station_location_eqsl_hint'] = "Der 'QTH Nickname' wie er in deinem eQSL Profil konfiguriert ist.";
$lang['station_location_eqsl_defaultqslmsg'] = "Standard QSLMSG";
$lang['station_location_eqsl_defaultqslmsg_hint'] = "Definiere eine Standard-Nachricht, welche für jedes QSO in diesem Stationsstandort an eQSL übertragen wird.";
$lang['station_location_qrz_subscription'] = 'Abonnement erforderlich';
$lang['station_location_qrz_hint'] = "Finde deinen 'QRZ Logbook API Key' in den <a href='https://logbook.qrz.com/logbook' target='_blank'>QRZ.com Logbuch Einstellungen";
$lang['station_location_qrz_realtime_upload'] = 'QRZ.com Logbuch Echtzeit Upload';
$lang['station_location_hrdlog_username'] = "HRDLog.net Benutzername";
$lang['station_location_hrdlog_username_hint'] = "Der Benutzername mit dem du bei HRDlog.net registriert bist (normalerweise dein Rufzeichen).";
$lang['station_location_hrdlog_code'] = "HRDLog.net API Key";
$lang['station_location_hrdlog_realtime_upload'] = "HRDLog.net Logbuch Echtzeit Upload";
$lang['station_location_hrdlog_code_hint'] = "Erstelle deinen API Key auf <a href='http://www.hrdlog.net/EditUser.aspx' target='_blank'>HRDLog.net Benutzerprofil Seite";
$lang['station_location_qo100_hint'] = "Erstelle deinen API Key auf <a href='https://qo100dx.club' target='_blank'>deiner QO-100 Dx Club's Profil Seite";
$lang['station_location_qo100_realtime_upload'] = "QO-100 Dx Club Echtzeit Upload";
$lang['station_location_oqrs_enabled'] = "OQRS aktivieren";
$lang['station_location_oqrs_email_alert'] = "OQRS Email Benachrichtigung";
$lang['station_location_oqrs_email_hint'] = "Stelle sicher, dass du E-Mail unter Admin/Globale Optionen konfiguriert hast.";
$lang['station_location_oqrs_text'] = "OQRS Text";
$lang['station_location_oqrs_text_hint'] = "Einige Informationen, die du zum QSL-Vorgang hinzufügen möchtest.";
$lang['station_location_clublog_realtime_upload']='ClubLog Realtime Upload';
