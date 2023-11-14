<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
___________________________________________________________________________________________
Station Logbooks
___________________________________________________________________________________________
*/

$lang['station_logbooks'] = "Journal de trafic des stations";
$lang['station_logbooks_description_header'] = "Présentation du journal de trafic des stations";
$lang['station_logbooks_description_text'] = "Les \"journaux de trafic\" des stations vous permettent de regrouper les emplacements des stations.<br>Cela vous permet d'avoir tous les emplacements au sein d'une même session ; visible dans le journal de trafic,  jusqu'aux statistiques. <br>Idéal lorsque vous travaillez sur plusieurs sites, mais qu'ils font partie du même cercle DXCC ou VUCC.";
$lang['station_logbooks_create'] = "Ajouter un nouveau";
$lang['station_logbooks_status'] = "Statut";
$lang['station_logbooks_link'] = "Lien";
$lang['station_logbooks_public_search'] = "Recherche publique";
$lang['station_logbooks_set_active'] = "Activer ce journal";
$lang['station_logbooks_active_logbook'] = "Activé";
$lang['station_logbooks_edit_logbook'] = "Editer ce journal";    // Full sentence will be generated 'Edit Station Logbook: [Logbook Name]'
$lang['station_logbooks_confirm_delete'] = "Are you sure you want to delete the following station logbook? You must re-link any locations linked here to another logbook.: ";
$lang['station_logbooks_view_public'] = "View Public Page for Logbook: ";
$lang['station_logbooks_create_name'] = "Station Logbook Name";
$lang['station_logbooks_create_name_hint'] = "You can call a station logbook anything.";
$lang['station_logbooks_edit_name_hint'] = "Shortname for the station logbook. For example: Home Log (IO87IP)";
$lang['station_logbooks_edit_name_update'] = "Update Station Logbook Name";
$lang['station_logbooks_public_slug'] = "Public Slug";
$lang['station_logbooks_public_slug_hint'] = "Setting a public slug allows you to share your logbook with anyone via a custom website address, this slug can contain letters & numbers only.";
$lang['station_logbooks_public_slug_format1'] = "Later it looks like this:";
$lang['station_logbooks_public_slug_format2'] = "[your slug]";
$lang['station_logbooks_public_slug_input'] = "Type in Public Slug choice";
$lang['station_logbooks_public_slug_visit'] = "Visit Public Page";
$lang['station_logbooks_public_search_hint'] = "Enabling public search function offers a search input box on the public logbook page accessed via public slug. Search only covers this logbook.";
$lang['station_logbooks_public_search_enabled'] = "Public search enabled";
$lang['station_logbooks_select_avail_loc'] = "Select Available Station Locations";
$lang['station_logbooks_link_loc'] = "Link Location";
$lang['station_logbooks_linked_loc'] = "Linked Locations";
$lang['station_logbooks_no_linked_loc'] = "No Linked Locations";



/*
___________________________________________________________________________________________
Station Locations
___________________________________________________________________________________________
*/

$lang['station_location'] = 'Station Location';
$lang['station_location_plural'] = "Station Locations";
$lang['station_location_header_ln1'] = 'Station Locations define operating locations, such as your QTH, a friends QTH, or a portable station.';
$lang['station_location_header_ln2'] = 'Similar to logbooks, a station profile keeps a set of QSOs together.';
$lang['station_location_header_ln3'] = 'Only one station may be active at a time. In the table below this is shown with the -Active Station- badge.';
$lang['station_location_create_header'] = 'Create Station Location';
$lang['station_location_create'] = 'Create a Station Location';
$lang['station_location_edit'] = 'Edit Station Location: ';
$lang['station_location_updated_suff'] = "mis à jour.";
$lang['station_location_warning'] = 'Attention: You need to set an active station location. Go to Callsign->Station Location to select one.';
$lang['station_location_reassign_at'] = 'Please reassign them at ';
$lang['station_location_warning_reassign'] = 'Due to recent changes within Cloudlog you need to reassign QSOs to your station profiles.';
$lang['station_location_name'] = 'Profile Name';
$lang['station_location_name_hint'] = 'Shortname for the station location. For example: Home (IO87IP)';
$lang['station_location_callsign'] = 'Station Callsign';
$lang['station_location_callsign_hint'] = 'Station callsign. For example: 2M0SQL/P';
$lang['station_location_power'] = 'Station Power (W)';
$lang['station_location_power_hint'] = 'Default station power in Watt. Overwritten by CAT.';
$lang['station_location_emptylog'] = 'Empty Log';
$lang['station_location_confirm_active'] = 'Are you sure you want to make the following station the active station: ';
$lang['station_location_set_active'] = 'Set Active';
$lang['station_location_active'] = 'Active Station';
$lang['station_location_claim_ownership'] = 'Claim Ownership';
$lang['station_location_confirm_del_qso'] = 'Are you sure you want to delete all QSOs within this station profile?';
$lang['station_location_confirm_del_stationlocation'] = 'Are you sure you want delete station profile  ';
$lang['station_location_confirm_del_stationlocation_qso'] = 'This will delete all QSOs within this station profile?';
$lang['station_location_dxcc'] = 'Station DXCC';
$lang['station_location_dxcc_hint'] = 'Station DXCC entity. For example: Scotland';
$lang['station_location_dxcc_warning'] = "Stop here for a Moment. Your chosen DXCC is outdated and not valid anymore. Check which DXCC for this particular location is the correct one. If you are sure, ignore this warning.";
$lang['station_location_city'] = 'Station City';
$lang['station_location_city_hint'] = 'Station city. For example: Inverness';
$lang['station_location_state'] = 'Station State';
$lang['station_location_state_hint'] = 'Station state. Applies to certain countries only. Leave blank if not applicable.';
$lang['station_location_county'] = 'Station County';
$lang['station_location_county_hint'] = 'Station County (Only used for USA/Alaska/Hawaii).';
$lang['station_location_gridsquare'] = 'Station Gridsquare';
$lang['station_location_gridsquare_hint_ln1'] = "Station gridsquare. For example: IO87IP. If you don't know your grid square then <a href='https://zone-check.eu/?m=loc' target='_blank'>click here</a>!";
$lang['station_location_gridsquare_hint_ln2'] = "If you are located on a grid line, enter multiple grid squares separated with commas. For example: IO77,IO78,IO87,IO88.";
$lang['station_location_iota_hint_ln1'] = "Station IOTA reference. For example: EU-005";
$lang['station_location_iota_hint_ln2'] = "You can look up IOTA references at the <a target='_blank' href='https://www.iota-world.org/iota-directory/annex-f-short-title-iota-reference-number-list.html'>IOTA World</a> website.";
$lang['station_location_sota_hint_ln1'] = "Station SOTA reference. You can look up SOTA references at the <a target='_blank' href='https://www.sotamaps.org/'>SOTA Maps</a> website.";
$lang['station_location_wwff_hint_ln1'] = "Station WWFF reference. You can look up WWFF references at the <a target='_blank' href='https://www.cqgma.org/mvs/'>GMA Map</a> website.";
$lang['station_location_pota_hint_ln1'] = "Station POTA reference. You can look up POTA references at the <a target='_blank' href='https://pota.app/#/map/'>POTA Map</a> website.";
$lang['station_location_signature'] = "Signature";
$lang['station_location_signature_name'] = "Signature Name";
$lang['station_location_signature_name_hint'] = "Station Signature (e.g. GMA)..";
$lang['station_location_signature_info'] = "Signature Information";
$lang['station_location_signature_info_hint'] = "Station Signature Info (e.g. DA/NW-357).";
$lang['station_location_eqsl_hint'] = 'The QTH Nickname which is configured in your eQSL Profile';
$lang['station_location_qrz_subscription'] = 'Subscription Required';
$lang['station_location_qrz_hint'] = "Find your API key on <a href='https://logbook.qrz.com/logbook' target='_blank'>the QRZ.com Logbook settings page";
$lang['station_location_qrz_realtime_upload'] = 'QRZ.com Logbook Realtime Upload';
$lang['station_location_hrdlog_realtime_upload'] = "HRDLog.net Logbook Realtime Upload";
$lang['station_location_hrdlog_hint'] = "Create your API Code on <a href='http://www.hrdlog.net/EditUser.aspx' target='_blank'>HRDLog.net Userprofile page";
$lang['station_location_qo100_hint'] = "Create your API key on <a href='https://qo100dx.club' target='_blank'>your QO-100 Dx Club's profile page";
$lang['station_location_qo100_realtime_upload'] = "QO-100 Dx Club Realtime Upload";
$lang['station_location_oqrs_enabled'] = "OQRS Enabled";
$lang['station_location_oqrs_email_alert'] = "OQRS Email alert";
$lang['station_location_oqrs_email_hint'] = "Make sure email is set up under admin and global options.";
$lang['station_location_oqrs_text'] = "OQRS Text";
$lang['station_location_oqrs_text_hint'] = "Some info you want to add regarding QSL'ing.";


