<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$lang['lotw_short'] = 'LoTW';
$lang['lotw_title'] = 'Logbook of the World';
$lang['lotw_title_available_cert'] = '可用证书';
$lang['lotw_title_information'] = '信息';
$lang['lotw_title_upload_p12_cert'] = '上传 LoTW .p12 证书';
$lang['lotw_title_export_p12_file_instruction'] = '导出 .p12 文件流程';
$lang['lotw_title_adif_import'] = 'ADIF 导入';
$lang['lotw_title_adif_import_options'] = '导入选项';

$lang['lotw_beta_warning'] = '请明确 LoTW 同步处于 BETA 测试阶段, 请查看 wiki 寻求帮助';
$lang['lotw_no_certs_uploaded'] = '你需要上传 LoTW p12 证书以使用该功能';

$lang['lotw_date_created'] = '创建日期';
$lang['lotw_date_expires'] = '过期日期';
$lang['lotw_qso_start_date'] = 'QSO 起始日期';
$lang['lotw_qso_end_date'] = 'QSO 结束日期';
$lang['lotw_status'] = '状态';
$lang['lotw_options'] = '选项';
$lang['lotw_valid'] = '有效';
$lang['lotw_expired'] = '过期';
$lang['lotw_expiring'] = '即将到期';
$lang['lotw_not_synced'] = '未同步';

$lang['lotw_certificate_dxcc'] = '证书 DXCC';
$lang['lotw_certificate_dxcc_help_text'] = '证书的 DXCC 实体例如: Scotland';

$lang['lotw_input_a_file'] = '上传文件';

$lang['lotw_upload_exported_adif_file_from_lotw'] = '下载从 LoTW <a href="https://p1k.arrl.org/lotwuser/qsos?qsoscmd=adif" target="_blank">Download Report</a> 中导出的 ADIF 文件，并且标记在 LoTW上已得到确认的QSO';
$lang['lotw_upload_type_must_be_adi'] = '日志文件的类型必须为 .adi';

$lang['lotw_pull_lotw_data_for_me'] = '为我拉取 LoTW 数据';
$lang['lotw_select_callsign'] = '选择呼号以获取 LoTW 确认';

$lang['lotw_report_download_overview_helptext'] ='Cloudlog 将会使用储存在你个人用户信息当中的 LoTW 用户名和密码从 LoTW 上为你下载报告Cloudlog 下载的这份报告将会包括自你所选之日以来的或者你最后的 LoTW 确认信息（目前日志当中）以来的所有确认';

// Buttons
$lang['lotw_btn_lotw_import'] = 'LoTW 导入';
$lang['lotw_btn_upload_certificate'] = '上传证书';
$lang['lotw_btn_delete'] = '删除';
$lang['lotw_btn_manual_sync'] = '手动同步';
$lang['lotw_btn_upload_file'] = '上传文件';
$lang['lotw_btn_import_matches'] = '导入 LoTW 匹配';

// P12 Export Text
$lang['lotw_p12_export_step_one'] = '打开 TQSL &amp; 选择 呼号证书 选项';
$lang['lotw_p12_export_step_two'] = '右键选择目标呼号';
$lang['lotw_p12_export_step_three'] = '单击 "保存呼号证书文件" 并不要指定密码';
$lang['lotw_p12_export_step_four'] = '在下方上传文件';

$lang['lotw_confirmed'] = '该 QSO 已在 LoTW 确认';

// LoTW Expiry
$lang['lotw_cert_expiring'] = '至少有一个LoTW证书即将过期!';
$lang['lotw_cert_expired'] = '至少有一个LoTW证书已经过期!';

// Lotw User
$lang['lotw_user'] = '这个电台使用 LOTW';
$lang['lotw_last_upload'] = '最后一次上传是';
