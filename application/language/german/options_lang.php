<?php

defined('BASEPATH') OR exit('Direkter Zugriff auf Skripte ist nicht erlaubt');

$lang['options_cloudlog_options'] = 'Cloudlog Optionen';
$lang['options_message1'] = 'Cloudlog Optionen sind globe Einstellungen, die für alle Benutzer der Installation genutzt werden. Sie können auf Benutzerebene überschrieben werden, wenn eine entsprechende Option auf Benutzerebene vorhanden ist.';

$lang['options_appearance'] = 'Erscheinungsbild';
$lang['options_theme'] = 'Thema';
$lang['options_global_theme_choice_this_is_used_when_users_arent_logged_in'] = 'Globales Thema. Dies wird verwendet, wenn keine Benutzer angemeldet sind.';
$lang['options_public_search_bar'] = 'Öffentliches Suchfeld';
$lang['options_this_allows_non_logged_in_users_to_access_the_search_functions'] = 'Dies erlaubt nicht angemeldeten Benutzern, die Suchfunktion zu nutzen.';
$lang['options_dashboard_notification_banner'] = 'Dashboard Benachrichtigungsbanner';
$lang['options_this_allows_to_disable_the_global_notification_banner_on_the_dashboard'] = 'Dies ermöglicht es, die globalen Benachrichtigungsbanner auf dem Dashboard zu deaktivieren.';
$lang['options_dashboard_map'] = 'Dashboard Karte';
$lang['options_this_allows_the_map_on_the_dashboard_to_be_disabled_or_placed_on_the_right'] = 'Dies erlaubt es, die Karte auf dem Dashboard rechts anzuzeigen oder zu deaktivieren.';
$lang['options_logbook_map'] = 'Logbook Karte';
$lang['options_this_allows_to_disable_the_map_in_the_logbook'] = 'Dies erlaubt, die Karte im Logbuch zu deaktivieren.';
$lang['options_theme_changed_to'] = 'Thema geändert zu ';
$lang['options_global_search_changed_to'] = 'Globale Suche geändert zu ';
$lang['options_dashboard_banner_changed_to'] = 'Dashboard Benachrichtigungsbanner geändert zu ';
$lang['options_dashboard_map_changed_to'] = 'Dashboard Karte geändert zu ';
$lang['options_logbook_map_changed_to'] = 'Logbook Karte geändert zu ';

$lang['options_radios'] = 'Funkgeräte';
$lang['options_radio_settings'] = 'Funkgeräteeinstellungen';
$lang['options_radio_timeout_warning'] = 'Funkgeräte Timeout Warnung';
$lang['options_the_radio_timeout_warning_is_used_on_the_qso_entry_panel_to_alert_you_to_radio_interface_disconnects'] = 'Die Funkgeräte Timeout Warnung wird im QSOs Eingabepanel verwendet, um anzuzeigen, wenn die Funkgeräteschnittstelle getrennt wurde.';
$lang['options_this_number_is_in_seconds'] = 'Die Angabe erfolgt in Sekunden.';
$lang['options_radio_timeout_warning_changed_to'] = 'Radio Timeout Warnung geändert zu ';

$lang['options_email'] = 'E-Mail';
$lang['options_outgoing_protocol'] = 'Protokoll für ausgehende E-Mails';
$lang['options_smtp_encryption'] = 'SMTP Verschlüsselung';
$lang['options_email_address'] = 'E-Mailaddresse';
$lang['options_email_sender_name'] = 'E-Mail Absendername';
$lang['options_smtp_host'] = 'SMTP Host';
$lang['options_smtp_port'] = 'SMTP Port';
$lang['options_smtp_username'] = 'SMTP Benutzername';
$lang['options_smtp_password'] = 'SMTP Passwort';
$lang['options_crlf'] = 'CRLF';
$lang['options_newline'] = 'Zeilenvorschub (Newline)';
$lang['options_outgoing_email_protocol_changed_to'] = 'Protokoll für ausgehende E-Mails geändert zu ';
$lang['options_smtp_encryption_changed_to'] = 'SMTP Verschlüsselung geändert zu ';
$lang['options_email_address_changed_to'] = 'E-Mailadresse geändert zu ';
$lang['options_email_sender_name_changed_to'] = 'E-Mail Absendername geändert zu ';
$lang['options_smtp_host_changed_to'] = 'SMTP Host geändert zu ';
$lang['options_smtp_port_changed_to'] = 'SMTP Port geändert zu ';
$lang['options_smtp_username_changed_to'] = 'SMTP Benutzername geändert zu ';
$lang['options_smtp_password_changed_to'] = 'SMTP Passwort geändert zu ';
$lang['options_email_crlf_changed_to'] = 'E-Mail CRLF geändert zu ';
$lang['options_email_newline_changed_to'] = 'E-Mail Zeilenvorschub geändert zu ';

$lang['options_oqrs'] = 'OQRS Optionen';
$lang['options_global_text'] = 'Globaler Text';
$lang['options_this_text_is_an_optional_text_that_can_be_displayed_on_top_of_the_oqrs_page'] = 'Dies ist ein optionaler Text, der auf oben auf der OQRS Seite angezeigt werden kann.';
$lang['options_grouped_search'] = 'Gruppierte Suche';
$lang['options_when_this_is_on_all_station_locations_with_oqrs_active_will_be_searched_at_once'] = 'Wenn aktiviert, werden alle Stationsstandorte auf einmal durchsucht.';
$lang['options_oqrs_options_have_been_saved'] = 'OQRS Einstellungen wurden gespeichert.';

$lang['options_dxcluster'] = 'DXCluster';
$lang['options_dxcluster_provider'] = 'Provider des DXClusterCache';
$lang['options_dxcluster_longtext'] = 'Der Provider des DXCluster-Caches. Du kannst Deinen eigenen Cache mit <a href="https://github.com/int2001/DXClusterAPI">DXClusterAPI</a> aufsetzen, oder einen Öffentlichen nutzen';
$lang['options_dxcluster_hint'] = 'URL des DXCluster-Caches. z.B. https://dxc.jo30.de/dxcache';
$lang['options_dxcluster_settings'] = 'DXCluster';
$lang['options_dxcache_url_changed_to'] = 'DXCluster Cache URL geändert zu ';
$lang['options_dxcluster_maxage'] = 'Maximales Alter bis zu dem Spots berücksichtigt werden';
$lang['options_dxcluster_maxage_hint'] = 'Das Alter von Spots in Minuten, welche im Bandplan/Lookup berücksichtigt werden';
$lang['options_dxcluster_decont'] = 'Nur Spots berücksichtigen, die in folgendem Kontinent erfasst wurden';
$lang['options_dxcluster_maxage_changed_to']='Maximales Spot-Alter geändert auf ';
$lang['options_dxcluster_decont_changed_to']='Spotterkontinent geändert auf ';
$lang['options_dxcluster_decont_hint']='Nur Spots von Spottern dieses Kontinents werden angezeigt';

$lang['options_save'] = 'Speichern';

// Bands

$lang['options_bands'] = "Bänder";
$lang['options_bands_text_ln1'] = "Mit dieser Bänder-Liste kannst du steuern, welche Bänder beim Erstellen eines neuen QSO angezeigt werden.";
$lang['options_bands_text_ln2'] = "Aktive Bänder werden im QSO Band Auswahlfeld angezeigt, während inaktive Bänder ausgeblendet werden und nicht ausgewählt werden können.";
$lang['options_bands_create'] = "Erstelle ein neues Band";
$lang['options_bands_edit'] = "Bearbeite Band";
$lang['options_bands_activate_all'] = "Aktiviere Alle";
$lang['options_bands_activateall_warning'] = "Warnung! Bist du sicher, dass du alle Bänder aktivieren willst?";
$lang['options_bands_deactivate_all'] = "Deaktiviere Alle";
$lang['options_bands_deactivateall_warning'] = "Warnung! Bist du sicher, dass du alle Bänder deaktivieren willst?";
$lang['options_bands_ssb_qrg'] = "SSB QRG";
$lang['options_bands_ssb_qrg_hint'] = "Frequenz für die SSB QRG auf dem Band (Muss in Hz angegeben werden)";
$lang['options_bands_data_qrg'] = "DATA QRG";
$lang['options_bands_data_qrg_hint'] = "Frequenz für die DATA QRG auf dem Band (Muss in Hz angegeben werden";
$lang['options_bands_cw_qrg'] = "CW QRG";
$lang['options_bands_cw_qrg_hint'] = "Frequenz für die CW QRG auf dem Band (Muss in Hz angegeben werden";

$lang['options_bands_name_band'] = "Name des Bandes (z.B. 20m)";
$lang['options_bands_name_bandgroup'] = "Name der Bandgruppe (z.B. hf, vhf, uhf, shf)";
$lang['options_bands_delete_warning'] = "Warnung! Bist du dir sicher, dass du das folgende Band löschen willst: ";

