<?php

defined('BASEPATH') OR exit('Не е разрешен директен достъп до скрипта');

$lang['options_cloudlog_options'] = 'Cloudlog Options';
$lang['options_message1'] = 'Cloudlog Options are global settings used for all users of the installation, which are overridden if there\'s a setting on a user level.';

$lang['options_appearance'] = 'Appearance';
$lang['options_theme'] = 'Theme';
$lang['options_global_theme_choice_this_is_used_when_users_arent_logged_in'] = 'Global Theme Choice, this is used when users arent logged in.';
$lang['options_public_search_bar'] = 'Public Search Bar';
$lang['options_this_allows_non_logged_in_users_to_access_the_search_functions'] = 'This allows non logged in users to access the search functions.';
$lang['options_dashboard_notification_banner'] = 'Dashboard Notification Banner';
$lang['options_this_allows_to_disable_the_global_notification_banner_on_the_dashboard'] = 'This allows to disable the global notification banner on the dashboard.';
$lang['options_dashboard_map'] = 'Dashboard Map';
$lang['options_this_allows_the_map_on_the_dashboard_to_be_disabled_or_placed_on_the_right'] = 'This allows the map on the dashboard to be disabled or placed on the right.';
$lang['options_logbook_map'] = 'Logbook Map';
$lang['options_this_allows_to_disable_the_map_in_the_logbook'] = 'This allows to disable the map in the logbook.';
$lang['options_theme_changed_to'] = 'Theme changed to ';
$lang['options_global_search_changed_to'] = 'Global Search changed to ';
$lang['options_dashboard_banner_changed_to'] = 'Dashboard banner changed to ';
$lang['options_dashboard_map_changed_to'] = 'Dashboard map changed to ';
$lang['options_logbook_map_changed_to'] = 'Logbook map changed to ';

$lang['options_radios'] = 'Radios';
$lang['options_radio_settings'] = 'Radio Settings';
$lang['options_radio_timeout_warning'] = 'Radio Timeout Warning';
$lang['options_the_radio_timeout_warning_is_used_on_the_qso_entry_panel_to_alert_you_to_radio_interface_disconnects'] = 'The Radio Timeout Warning is used on the QSO entry panel to alert you to radio interface disconnects.';
$lang['options_this_number_is_in_seconds'] = 'This number is in seconds.';
$lang['options_radio_timeout_warning_changed_to'] = 'Radio Timeout Warning changed to ';

$lang['options_email'] = 'Email';
$lang['options_outgoing_protocol'] = 'Outgoing Protocol';
$lang['options_smtp_encryption'] = 'SMTP Encryption';
$lang['options_email_address'] = 'Email Address';
$lang['options_email_sender_name'] = 'Email Sender Name';
$lang['options_smtp_host'] = 'SMTP Host';
$lang['options_smtp_port'] = 'SMTP Port';
$lang['options_smtp_username'] = 'SMTP Username';
$lang['options_smtp_password'] = 'SMTP Password';
$lang['options_crlf'] = 'CRLF';
$lang['options_newline'] = 'Newline';
$lang['options_outgoing_email_protocol_changed_to'] = 'Outgoing Email Protocol changed to ';
$lang['options_smtp_encryption_changed_to'] = 'SMTP Encryption changed to ';
$lang['options_email_address_changed_to'] = 'Email Address changed to ';
$lang['options_email_sender_name_changed_to'] = 'Email Sender Name changed to ';
$lang['options_smtp_host_changed_to'] = 'SMTP Host changed to ';
$lang['options_smtp_port_changed_to'] = 'SMTP Post changed to ';
$lang['options_smtp_username_changed_to'] = 'SMTP Username changed to ';
$lang['options_smtp_password_changed_to'] = 'SMTP Password changed to ';
$lang['options_email_crlf_changed_to'] = 'Email CRLF changed to ';
$lang['options_email_newline_changed_to'] = 'Email Newline changed to ';

$lang['options_oqrs'] = 'OQRS Options';
$lang['options_global_text'] = 'Global text';
$lang['options_this_text_is_an_optional_text_that_can_be_displayed_on_top_of_the_oqrs_page'] = 'This text is an optional text that can be displayed on top of the OQRS page.';
$lang['options_grouped_search'] = 'Grouped search';
$lang['options_when_this_is_on_all_station_locations_with_oqrs_active_will_be_searched_at_once'] = 'When this is on, all station locations with OQRS active, will be searched at once.';
$lang['options_oqrs_options_have_been_saved'] = 'OQRS options have been saved.';

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

$lang['options_save'] = 'Save';

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

