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
$lang['station_logbooks_public_slug_format2'] = "[你的日志]";
$lang['station_logbooks_public_slug_input'] = "输入公共日志选项";
$lang['station_logbooks_public_slug_visit'] = "访问公开日志页面";
$lang['station_logbooks_public_search_hint'] = "启用公共搜索功能可在通过公共 slug 访问的公共日志页面上提供搜索输入框。 搜索仅涵盖此日志。";
$lang['station_logbooks_public_search_enabled'] = "启用公共搜索";
$lang['station_logbooks_select_avail_loc'] = "选择可用的台站位置";
$lang['station_logbooks_link_loc'] = "链接的台站位置";
$lang['station_logbooks_linked_loc'] = "已链接的台站位置";
$lang['station_logbooks_no_linked_loc'] = "未链接的台站位置";
$lang['station_logbooks_unlink_station_location'] = "取消链接的台站位置";



/*
___________________________________________________________________________________________
Station Locations
___________________________________________________________________________________________
*/

$lang['station_location'] = '台站地址';
$lang['station_location_plural'] = "台站地址";
$lang['station_location_header_ln1'] = '台站地址定义的是操作位置，例如您的 QTH、朋友的 QTH 或野外架台的地址。';
$lang['station_location_header_ln2'] = '与日志类似，台站地址配置将一组 QSO 保存在一起。';
$lang['station_location_header_ln3'] = '一次只能有一个台站地址处于活动状态。 在下表中，这显示为带有“活动站”（"Active Station"）标识。';
$lang['station_location_create_header'] = '创建台站地址';
$lang['station_location_create'] = '新建台站地址';
$lang['station_location_edit'] = '编辑台站地址';
$lang['station_location_updated_suff'] = '已更新';
$lang['station_location_warning'] = '注意：您需要设置活动的电台站位置（QTH）。 前往呼号->电台位置选择一个。';
$lang['station_location_reassign_at'] = '请在这里重新分配：';
$lang['station_location_warning_reassign'] = '由于 Cloudlog 中最近发生的变化，您需要将 QSO 重新分配给您的电台站配置文件。';
$lang['station_location_name'] = '个人资料名称';
$lang['station_location_name_hint'] = '台站位置的简称。 例如：学校（PN35GT）';
$lang['station_location_callsign'] = '电台站的呼号';
$lang['station_location_callsign_hint'] = '电台的呼号。 例如：B1/BG2FFJ';
$lang['station_location_power'] = '电台功率';
$lang['station_location_power_hint'] = '默认的电台功率（以瓦特为单位）。 这个设置会被从电台 CAT 读取到的数据所覆盖。';
$lang['station_location_emptylog'] = '空日志';
$lang['station_location_confirm_active'] = '您确定要将以下电台站位置设为活动的（在用的）电台站位置吗：';
$lang['station_location_set_active'] = '设置为活动电台站位置';
$lang['station_location_active'] = '活动的电台站';
$lang['station_location_claim_ownership'] = '认领所有权';
$lang['station_location_confirm_del_qso'] = '您确定要删除此电台配置文件中的所有 QSO 吗？';
$lang['station_location_confirm_del_stationlocation'] = '您确定要删除电台配置文件吗 ';
$lang['station_location_confirm_del_stationlocation_qso'] = '这将会删除该电台配置文件中的所有 QSO，确定删除 ？';
$lang['station_location_dxcc'] = '电台站的 DXCC 实体';
$lang['station_location_dxcc_hint'] = '电台站的 DXCC 实体。 例如：中国大陆';
$lang['station_location_dxcc_warning'] = "等一下。 您选择的 DXCC 已过时并且不再有效。 检查一下您要选择的位置所属的 DXCC 哪一个是正确的。 如果您确定没选错，我就要选这个，请忽略此警告。";
$lang['station_location_city'] = '电台站的城市';
$lang['station_location_city_hint'] = '电台站的城市。 例如：长春';
$lang['station_location_state'] = '电台站的州/省份';
$lang['station_location_state_hint'] = '电台站的省份。 仅适用于某些国家。，如果不适用则留空。如：吉林';
$lang['station_location_county'] = '电台站的县/区';
$lang['station_location_county_hint'] = '电台站的县/区。（仅用于美国/阿拉斯加/夏威夷）。';
$lang['station_location_gridsquare'] = '电台站的网格方格（梅登海德网格）';
$lang['station_location_gridsquare_hint_ln1'] = "电台站所属的网格。 例如：PN35GT。 如果您不知道自己的网格，请<a href='https://zone-check.eu/?m=loc' target='_blank'>点击此处</a>！";
$lang['station_location_gridsquare_hint_ln2'] = "如果您正好位于网格线上，请输入多个网格方块，并用逗号分隔。 例如：PN24、PN34、PN23、PN33。";
$lang['station_location_iota_hint_ln1'] = "电台站的IOTA编号。 例如：BY-001";
$lang['station_location_iota_hint_ln2'] = "您可以在 <a target='_blank' href='https://www.iota-world.org/iota-directory/annex-f-short-title-iota-reference-number-list.html'>IOTA World</a> 网站查找 IOTA 编号。";
$lang['station_location_sota_hint_ln1'] = "电台站内的 SOTA 编号. 您可以在 <a target='_blank' href='https://www.sotamaps.org/'>SOTA 地图</a> 网站查找 SOTA 编号。";
$lang['station_location_wwff_hint_ln1'] = "电台站内的 WWFF 编号. 您可以在 <a target='_blank' href='https://www.cqgma.org/mvs/'>GMA 地图</a> 网站查找 WWFF 编号。";
$lang['station_location_pota_hint_ln1'] = "电台站内的 POTA 编号. 您可以在 <a target='_blank' href='https://pota.app/#/map/'>POTA 地图</a> 网站查找 POTA 编号。";
$lang['station_location_signature'] = "签名";
$lang['station_location_signature_name'] = "签名名称";
$lang['station_location_signature_name_hint'] = "电台签名（例如 GMA）..";
$lang['station_location_signature_info'] = "签名信息";
$lang['station_location_signature_info_hint'] = "电台签名信息（例如 DA/NW-357）。";
$lang['station_location_eqsl_hint'] = '在您的 eQSL 配置文件中配置的 QTH 昵称';
$lang['station_location_eqsl_defaultqslmsg'] = "默认的QSL信息";
$lang['station_location_eqsl_defaultqslmsg_hint'] = "该设置将为此电台站站位置的每个 QSO 设置一个默认填充和发送的消息。";
$lang['station_location_qrz_subscription'] = '需要 QRZ.com 的订阅';
$lang['station_location_qrz_hint'] = "在 <a href='https://logbook.qrz.com/logbook' target='_blank'>QRZ.com的日志簿界面查找您的API KEY。";
$lang['station_location_qrz_realtime_upload'] = 'QRZ.com 日志簿实时上传';
$lang['station_location_hrdlog_username'] = "HRDLog.net 用户名";
$lang['station_location_hrdlog_username_hint'] = "您在 HRDlog.net 注册时使用的用户名（通常是您的呼号）。";
$lang['station_location_hrdlog_code'] = "HRDLog.net API Key";
$lang['station_location_hrdlog_realtime_upload'] = "HRDLog.net 日志簿实时上传";
$lang['station_location_hrdlog_code_hint'] = "在 <a href='http://www.hrdlog.net/EditUser.aspx' target='_blank'>hrdlog.net的用户资料页面创建你的API key。";
$lang['station_location_qo100_hint'] = "在 <a href='https://qo100dx.club' target='_blank'>qo100dx.club的用户资料页面创建你的API key";
$lang['station_location_qo100_realtime_upload'] = "QO-100 DX Club 实时上传";
$lang['station_location_oqrs_enabled'] = "OQRS 启用";
$lang['station_location_oqrs_email_alert'] = "OQRS 邮件提醒";
$lang['station_location_oqrs_email_hint'] = "请确保已经和在管理-全局选项下设置了电子邮件。";
$lang['station_location_oqrs_text'] = "OQRS 提示文本";
$lang['station_location_oqrs_text_hint'] = "您想要的添加一些有关 QSL'ing 的信息。";
$lang['station_location_clublog_realtime_upload']='ClubLog 日志实时上传';


