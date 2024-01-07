<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
___________________________________________________________________________________________
Station Logbooks
___________________________________________________________________________________________
*/

$lang['station_logbooks'] = "台站日志";
$lang['station_logbooks_description_header'] = "什么是台站日志";
$lang['station_logbooks_description_text'] = "台站日志可以让您对自己的台站位置进行分组，这对在同一DXCC或VUCC下的不同站点位置非常方便";
$lang['station_logbooks_create'] = "新建台站日志";
$lang['station_logbooks_status'] = "状态";
$lang['station_logbooks_link'] = "链接";
$lang['station_logbooks_public_search'] = "公开搜索";
$lang['station_logbooks_set_active'] = "设置为正在使用的日志";
$lang['station_logbooks_active_logbook'] = "正在使用的日志";
$lang['station_logbooks_edit_logbook'] = "编辑台站日志";    // Full sentence will be generated 'Edit Station Logbook: [Logbook Name]'
$lang['station_logbooks_confirm_delete'] = "确定删除此台站日志？你可能需要重新将台站位置链接到其他台站日志中: ";
$lang['station_logbooks_view_public'] = "浏览日志公开页: ";
$lang['station_logbooks_create_name'] = "台站日志名称";
$lang['station_logbooks_create_name_hint'] = "你可以随意称呼你的台站日志";
$lang['station_logbooks_edit_name_hint'] = "台站位置简称，例如：Home Log (IO87IP)";
$lang['station_logbooks_edit_name_update'] = "更新台站日志名称";
$lang['station_logbooks_public_slug'] = "自定义日志链接";
$lang['station_logbooks_public_slug_hint'] = "通过自定义日志链接，你可以通过此链接让别人访问你的日志";
$lang['station_logbooks_public_slug_format1'] = "他将会看起来像这样：";
$lang['station_logbooks_public_slug_format2'] = "[your slug]";
$lang['station_logbooks_public_slug_input'] = "Type in Public Slug choice";
$lang['station_logbooks_public_slug_visit'] = "Visit Public Page";
$lang['station_logbooks_public_search_hint'] = "Enabling public search function offers a search input box on the public logbook page accessed via public slug. Search only covers this logbook.";
$lang['station_logbooks_public_search_enabled'] = "Public search enabled";
$lang['station_logbooks_select_avail_loc'] = "Select Available Station Locations";
$lang['station_logbooks_link_loc'] = "链接的台站位置";
$lang['station_logbooks_linked_loc'] = "已链接的台站位置";
$lang['station_logbooks_no_linked_loc'] = "未链接的台站位置";
$lang['station_logbooks_unlink_station_location'] = "取消链接的台站位置";



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
$lang['station_location_updated_suff'] = ' Updated.';
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
$lang['station_location_eqsl_defaultqslmsg'] = "Default QSLMSG";
$lang['station_location_eqsl_defaultqslmsg_hint'] = "Define a default message that will be populated and sent for each QSO for this station location.";
$lang['station_location_qrz_subscription'] = 'Subscription Required';
$lang['station_location_qrz_hint'] = "Find your API key on <a href='https://logbook.qrz.com/logbook' target='_blank'>the QRZ.com Logbook settings page";
$lang['station_location_qrz_realtime_upload'] = 'QRZ.com Logbook Realtime Upload';
$lang['station_location_hrdlog_username'] = "HRDLog.net Username";
$lang['station_location_hrdlog_username_hint'] = "The username you are registered with at HRDlog.net (usually your callsign).";
$lang['station_location_hrdlog_code'] = "HRDLog.net API Key";
$lang['station_location_hrdlog_realtime_upload'] = "HRDLog.net Logbook Realtime Upload";
$lang['station_location_hrdlog_code_hint'] = "Create your API Code on <a href='http://www.hrdlog.net/EditUser.aspx' target='_blank'>HRDLog.net Userprofile page";
$lang['station_location_qo100_hint'] = "Create your API key on <a href='https://qo100dx.club' target='_blank'>your QO-100 Dx Club's profile page";
$lang['station_location_qo100_realtime_upload'] = "QO-100 Dx Club Realtime Upload";
$lang['station_location_oqrs_enabled'] = "OQRS Enabled";
$lang['station_location_oqrs_email_alert'] = "OQRS Email alert";
$lang['station_location_oqrs_email_hint'] = "Make sure email is set up under admin and global options.";
$lang['station_location_oqrs_text'] = "OQRS Text";
$lang['station_location_oqrs_text_hint'] = "Some info you want to add regarding QSL'ing.";
$lang['station_location_clublog_realtime_upload']='ClubLog Realtime Upload';


