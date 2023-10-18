<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$lang['options_cloudlog_options'] = 'Možnosti Cloudlogu';
$lang['options_message1'] = 'Možnosti Cloudlogu jsou globální nastavení používaná pro všechny uživatele instalace, která jsou přepsána, pokud je nastavení na úrovni uživatele.';

$lang['options_appearance'] = 'Vzhled';
$lang['options_theme'] = 'Téma';
$lang['options_global_theme_choice_this_is_used_when_users_arent_logged_in'] = 'Globální volba motivu, která se používá, když uživatelé nejsou přihlášeni.';
$lang['options_public_search_bar'] = 'Veřejná vyhledávací lišta';
$lang['options_this_allows_non_logged_in_users_to_access_the_search_functions'] = 'Tímto se umožní ne přihlášeným uživatelům přístup ke vyhledávacím funkcím.';
$lang['options_dashboard_notification_banner'] = 'Oznámení na palubní desce';
$lang['options_this_allows_to_disable_the_global_notification_banner_on_the_dashboard'] = 'Tímto lze zakázat globální oznámení na palubní desce.';
$lang['options_dashboard_map'] = 'Mapa na palubní desce';
$lang['options_this_allows_the_map_on_the_dashboard_to_be_disabled_or_placed_on_the_right'] = 'Toto umožňuje zakázat mapu na palubní desce nebo ji umístit na pravou stranu.';
$lang['options_logbook_map'] = 'Mapa v logbooku';
$lang['options_this_allows_to_disable_the_map_in_the_logbook'] = 'Toto umožňuje zakázat mapu v logbooku.';
$lang['options_theme_changed_to'] = 'Téma změněno na ';
$lang['options_global_search_changed_to'] = 'Globální vyhledávání změněno na ';
$lang['options_dashboard_banner_changed_to'] = 'Banner na palubní desce změněn na ';
$lang['options_dashboard_map_changed_to'] = 'Mapa na palubní desce změněna na ';
$lang['options_logbook_map_changed_to'] = 'Mapa v logbooku změněna na ';

$lang['options_radios'] = 'Rádia';
$lang['options_radio_settings'] = 'Nastavení rádia';
$lang['options_radio_timeout_warning'] = 'Upozornění na časový limit rádia';
$lang['options_the_radio_timeout_warning_is_used_on_the_qso_entry_panel_to_alert_you_to_radio_interface_disconnects'] = 'Upozornění na časový limit rádia se používá na panelu vstupu QSO k upozornění na odpojení rozhraní rádia.';
$lang['options_this_number_is_in_seconds'] = 'Toto číslo je v sekundách.';
$lang['options_radio_timeout_warning_changed_to'] = 'Upozornění na časový limit rádia změněno na ';

$lang['options_email'] = 'E-mail';
$lang['options_outgoing_protocol'] = 'Odchozí protokol';
$lang['options_smtp_encryption'] = 'SMTP šifrování';
$lang['options_email_address'] = 'E-mailová adresa';
$lang['options_email_sender_name'] = 'Jméno odesílatele e-mailu';
$lang['options_smtp_host'] = 'SMTP hostitel';
$lang['options_smtp_port'] = 'SMTP port';
$lang['options_smtp_username'] = 'SMTP uživatelské jméno';
$lang['options_smtp_password'] = 'SMTP heslo';
$lang['options_crlf'] = 'CRLF';
$lang['options_newline'] = 'Nový řádek';
$lang['options_outgoing_email_protocol_changed_to'] = 'Protokol odchozího e-mailu změněn na ';
$lang['options_smtp_encryption_changed_to'] = 'SMTP šifrování změněno na ';
$lang['options_email_address_changed_to'] = 'E-mailová adresa změněna na ';
$lang['options_email_sender_name_changed_to'] = 'Jméno odesílatele e-mailu změněno na ';
$lang['options_smtp_host_changed_to'] = 'SMTP hostitel změněn na ';
$lang['options_smtp_port_changed_to'] = 'SMTP port změněn na ';
$lang['options_smtp_username_changed_to'] = 'SMTP uživatelské jméno změněno na ';
$lang['options_smtp_password_changed_to'] = 'SMTP heslo změněno na ';
$lang['options_email_crlf_changed_to'] = 'CRLF e-mail změněn na ';
$lang['options_email_newline_changed_to'] = 'Nový řádek e-mailu změněn na ';

$lang['options_oqrs'] = 'OQRS možnosti';
$lang['options_global_text'] = 'Globální text';
$lang['options_this_text_is_an_optional_text_that_can_be_displayed_on_top_of_the_oqrs_page'] = 'Tento text je nepovinný text, který lze zobrazit na horní části stránky OQRS.';
$lang['options_grouped_search'] = 'Seskupené vyhledávání';
$lang['options_when_this_is_on_all_station_locations_with_oqrs_active_will_be_searched_at_once'] = 'Když je tato možnost zapnutá, budou všechny stanice s aktivním OQRS vyhledávány najednou.';
$lang['options_oqrs_options_have_been_saved'] = 'Možnosti OQRS byly uloženy.';

$lang['options_dxcluster'] = 'DXCluster';
$lang['options_dxcluster_provider'] = 'Provider of DXClusterCache';
$lang['options_dxcluster_longtext'] = 'The Provider of the DXCluster-Cache. You can set up your own Cache with <a href="https://github.com/int2001/DXClusterAPI">DXClusterAPI</a> or use a public one';
$lang['options_dxcluster_hint'] = 'URL of the DXCluster-Cache. e.g. https://dxc.jo30.de/dxcache';
$lang['options_dxcluster_settings'] = 'DXCluster';
$lang['options_dxcache_url_changed_to'] = 'DXCluster Cache URL changed to ';
$lang['options_dxcluster_maxage'] = 'Maximum Age of spots taken care of';
$lang['options_dxcluster_maxage_hint'] = 'The Age in Minutes of spots, that will be taken care at bandplan/lookup';
$lang['options_dxcluster_decont'] = 'Show spots which are spotted from following continent';
$lang['options_dxcluster_maxage_changed_to']='Maximum age of spots changed to ';
$lang['options_dxcluster_decont_changed_to']='de continent changed to ';
$lang['options_dxcluster_decont_hint']='Only spots by spotters from this continent are shown';

$lang['options_save'] = 'Uložit';

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

