<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$lang['options_cloudlog_options'] = 'Cloudlog-alternativ';
$lang['options_message1'] = 'Cloudlog-alternativ är globala inställningar som används för alla användare av installationen, som åsidosätts om det finns en inställning på användarnivå.';

$lang['options_appearance'] = 'Utseende';
$lang['options_theme'] = 'Tema';
$lang['options_global_theme_choice_this_is_used_when_users_arent_logged_in'] = 'Globalt temaval, detta används när användare inte är inloggade.';
$lang['options_public_search_bar'] = 'Offentlig sökfält';
$lang['options_this_allows_non_logged_in_users_to_access_the_search_functions'] = 'Detta tillåter icke-inloggade användare att komma åt sökfunktionerna.';
$lang['options_dashboard_notification_banner'] = 'Dashboard Notification Banner';
$lang['options_this_allows_to_disable_the_global_notification_banner_on_the_dashboard'] = 'This allows to disable the global notification banner on the dashboard.';
$lang['options_dashboard_map'] = 'Dashboard karta';
$lang['options_this_allows_the_map_on_the_dashboard_to_be_disabled_or_placed_on_the_right'] = 'This allows the map on the dashboard to be disabled or placed on the right.';
$lang['options_logbook_map'] = 'Loggbok Karta';
$lang['options_this_allows_to_disable_the_map_in_the_logbook'] = 'Detta gör det möjligt att inaktivera kartan i loggboken.';
$lang['options_theme_changed_to'] = 'Temat ändrades till ';
$lang['options_global_search_changed_to'] = 'Global sökning ändrad till ';
$lang['options_dashboard_banner_changed_to'] = 'Dashboard banner ändrad till ';
$lang['options_dashboard_map_changed_to'] = 'Dashboard karta ändrad till ';
$lang['options_logbook_map_changed_to'] = 'Loggbokskarta ändrad till ';

$lang['options_radios'] = 'Radios';
$lang['options_radio_settings'] = 'Radioinställningar';
$lang['options_radio_timeout_warning'] = 'Radio Timeout Varning';
$lang['options_the_radio_timeout_warning_is_used_on_the_qso_entry_panel_to_alert_you_to_radio_interface_disconnects'] = 'Radiotimeout-varningen används på QSO-ingångspanelen för att uppmärksamma dig på frånkopplingar av radiogränssnittet.';
$lang['options_this_number_is_in_seconds'] = 'Detta nummer är i sekunder.';
$lang['options_radio_timeout_warning_changed_to'] = 'Radio Timeout Warning ändras till ';

$lang['options_email'] = 'E-post';
$lang['options_outgoing_protocol'] = 'Utgående protokoll';
$lang['options_smtp_encryption'] = 'SMTP-kryptering';
$lang['options_email_address'] = 'E-postadress';
$lang['options_email_sender_name'] = 'E-postavsändarens namn';
$lang['options_smtp_host'] = 'SMTP Host';
$lang['options_smtp_port'] = 'SMTP Port';
$lang['options_smtp_username'] = 'SMTP Användarnamn';
$lang['options_smtp_password'] = 'SMTP Lösenord';
$lang['options_mail_settings_saved'] = "The settings were saved successfully.";
$lang['options_mail_settings_failed'] = "Something went wrong with saving the settings. Try again.";
$lang['options_outgoing_protocol_hint'] = "The protocol that will be used to send out emails.";
$lang['options_smtp_encryption_hint'] = "Choose whether emails should be sent with TLS or SSL.";
$lang['options_email_address_hint'] = "The email address from which the emails are sent, e.g. 'cloudlog@example.com'";
$lang['options_email_sender_name_hint'] = "The email sender name, e.g. 'Cloudlog'";
$lang['options_smtp_host_hint'] = "The hostname of the mail server, e.g. 'mail.example.com' (without 'ssl://' or 'tls://')";
$lang['options_smtp_port_hint'] = "The SMTP port of the mail server, e.g. if TLS is used -> '587', if SSL is used -> '465'";
$lang['options_smtp_username_hint'] = "The username to log in to the mail server, usually this is the email address that is used.";
$lang['options_smtp_password_hint'] = "The password to log in to the mail server.";
$lang['options_send_testmail'] = "Send Test-Mail";
$lang['options_send_testmail_hint'] = "The email will be sent to the address defined in your account settings.";
$lang['options_send_testmail_failed'] = "Testmail failed. Something went wrong.";
$lang['options_send_testmail_success'] = "Testmail sent. Email settings seem to be correct.";

$lang['options_oqrs'] = 'OQRS Alternativ';
$lang['options_global_text'] = 'Global text';
$lang['options_this_text_is_an_optional_text_that_can_be_displayed_on_top_of_the_oqrs_page'] = 'Denna text är en valfri text som kan visas överst på OQRS-sidan.';
$lang['options_grouped_search'] = 'Grupperad sökning';
$lang['options_when_this_is_on_all_station_locations_with_oqrs_active_will_be_searched_at_once'] = 'När detta är på kommer alla stationsplatser med OQRS aktiv att sökas på en gång.';
$lang['options_grouped_search_show_station_name'] = "Show station location name in grouped search results";
$lang['options_grouped_search_show_station_name_hint'] = "If grouped search is ON, you can decide if the name of the station location shall be shown in the results table.";
$lang['options_oqrs_options_have_been_saved'] = 'OQRS-alternativ har sparats.';

$lang['options_dxcluster'] = 'DXCluster';
$lang['options_dxcluster_provider'] = 'Leverantör av DXClusterCache';
$lang['options_dxcluster_longtext'] = 'Tleverantören av DXCluster-cachen. Du kan ställa in din egen cache med <a href="https://github.com/int2001/DXClusterAPI">DXClusterAPI</a> eller använda en offentlig cache';
$lang['options_dxcluster_hint'] = 'URL för DXCluster-cachen. t.ex. https://dxc.jo30.de/dxcache';
$lang['options_dxcluster_settings'] = 'DXCluster';
$lang['options_dxcache_url_changed_to'] = 'DXCluster Cache URL ändrad till ';
$lang['options_dxcluster_maxage'] = 'Maximal ålder för fläckar som tas om hand';
$lang['options_dxcluster_maxage_hint'] = 'Åldern i minuter av fläckar, som kommer att tas om hand vid bandplan/uppslag';
$lang['options_dxcluster_decont'] = 'Visa fläckar som ses från följande kontinent';
$lang['options_dxcluster_maxage_changed_to']='Maximal ålder för fläckar ändrad till ';
$lang['options_dxcluster_decont_changed_to']='kontinenten ändrats till ';
$lang['options_dxcluster_decont_hint']='Only spots by spotters from this continent are shown';

$lang['options_version_dialog'] = "Version Info";
$lang['options_version_dialog_close'] = "Close";
$lang['options_version_dialog_dismiss'] = "Don't show again";
$lang['options_version_dialog_settings'] = "Version Info Settings";
$lang['options_version_dialog_header'] = "Version Info Header";
$lang['options_version_dialog_header_hint'] = "You can change the header of the version info dialog.";
$lang['options_version_dialog_header_changed_to'] = "Version Info Header changed to";
$lang['options_version_dialog_mode'] = "Version Info Mode";
$lang['options_version_dialog_mode_release_notes'] = "Only Release Notes";
$lang['options_version_dialog_mode_custom_text'] = "Only Custom Text";
$lang['options_version_dialog_mode_both'] = "Release Notes and Custom Text";
$lang['options_version_dialog_mode_disabled'] = "Disabled";
$lang['options_version_dialog_mode_hint'] = "The Version Info is shown to every user. The user has the option to dismiss the dialog after they read it. Select if you want to show only release notes (fetched from github), only custom text or both.";
$lang['options_version_dialog_custom_text'] = "Version Info Custom Text";
$lang['options_version_dialog_custom_text_hint'] = "This is the custom text which is shown in the dialog.";
$lang['options_version_dialog_mode_changed_to'] = "Version Info Mode changed to";
$lang['options_version_dialog_custom_text_saved'] = "Version Info Custom Text saved!";
$lang['options_version_dialog_success_show_all'] = "Version Info will be shown to all users again";
$lang['options_version_dialog_success_hide_all'] = "Version Info will not be shown to any user";
$lang['options_version_dialog_show_hide'] = "Show/Hide Version Info Dialog for all Users";
$lang['options_version_dialog_show_all'] = "Show for all Users";
$lang['options_version_dialog_hide_all'] = "Hide for all Users";
$lang['options_version_dialog_show_all_hint'] = "This will show the version dialog automatically to all users on their next page reload.";
$lang['options_version_dialog_hide_all_hint'] = "This will deactivate the automatic popup of the version dialog for all users.";

$lang['options_save'] = 'Spara';

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

