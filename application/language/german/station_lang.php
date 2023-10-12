<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// Station Location

$lang['station_location'] = 'Stationsstandort';
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
$lang['station_location_set_active'] = 'Aktiviere';
$lang['station_location_active'] = 'Aktive Station';
$lang['station_location_claim_ownership'] = 'Claim Ownership';
$lang['station_location_confirm_del_qso'] = 'Bist du sicher, dass du alle QSO an diesem Stationsstandort löschen möchtest?';
$lang['station_location_confirm_del_stationlocation'] = 'Bist du sicher, dass du diesen Stationsstandort löschen willst?:';
$lang['station_location_confirm_del_stationlocation_qso'] = 'Es werden alle QSO an diesem Stationsstandort gelöscht!';
$lang['station_location_dxcc'] = 'Station DXCC';
$lang['station_location_dxcc_hint'] = 'Station DXCC Einteilung. Zum Beispiel: Germany';
$lang['station_location_city'] = 'Station Stadt';
$lang['station_location_city_hint'] = 'Station Stadt. Zum Beispiel: Berlin';
$lang['station_location_state'] = 'Station Staat';
$lang['station_location_state_hint'] = 'Station Staat. Nur verfügbar für einige Länder. Leer lassen falls nicht verfügbar.';
$lang['station_location_county'] = 'Station County';
$lang['station_location_county_hint'] = 'Station County (Nur für USA/Alaska/Hawaii).';
