<?php

defined('BASEPATH') OR exit('No direct script access allowed');


/*
___________________________________________________________________________________________
Topbar
___________________________________________________________________________________________
*/

$lang['filter_quickfilters'] = '快速过滤';
$lang['filter_qsl_filters'] = '过滤 QSL';
$lang['filter_filters'] = '过滤器';
$lang['filter_actions'] = '操作';
$lang['filter_results'] = '每页结果数';
$lang['filter_search'] = '搜索';
$lang['filter_dupes'] = "重复QSO";
$lang['filter_map'] = '地图';
$lang['filter_options'] = '显示列';
$lang['filter_reset'] = '重置筛选条件';

/*
___________________________________________________________________________________________
Quickilters
___________________________________________________________________________________________
*/

$lang['filter_quicksearch_w_sel'] = '用选中行的条件进行快速搜索：';
$lang['filter_search_callsign'] = '搜索呼号';
$lang['filter_search_dxcc'] = '搜索 DXCC';
$lang['filter_search_state'] = '搜索 州/省';
$lang['filter_search_gridsquare'] = '搜索 网格';
$lang['filter_search_cq_zone'] = '搜索 CQ 分区';
$lang['filter_search_mode'] = '搜索 模式';
$lang['filter_search_band'] = '搜索 频段';
$lang['filter_search_iota'] = '搜索 IOTA';
$lang['filter_search_sota'] = '搜索 SOTA';
$lang['filter_search_wwff'] = '搜索 WWFF';
$lang['filter_search_pota'] = '搜索 POTA';

/*
___________________________________________________________________________________________
QSL Filters
___________________________________________________________________________________________
*/

$lang['filter_qsl_sent'] = 'QSL 发送状态';
$lang['filter_qsl_recv'] = 'QSL 接收状态';
$lang['filter_qsl_sent_method'] = 'QSL 发送方式';
$lang['filter_qsl_recv_method'] = 'QSL 接收方式';
$lang['filter_lotw_sent'] = 'LoTW 发送状态';
$lang['filter_lotw_recv'] = 'LoTW 接收状态';
$lang['filter_eqsl_sent'] = 'eQSL 发送状态';
$lang['filter_eqsl_recv'] = 'eQSL 接收状态';
$lang['filter_qsl_via'] = '通过（via）……发送QSL';
$lang['filter_qsl_images'] = 'QSL 图片';

// $lang['general_word_all']                --> application/language/english/general_words_lang.php
// $lang['general_word_yes']                --> application/language/english/general_words_lang.php
// $lang['general_word_no']                 --> application/language/english/general_words_lang.php
// $lang['general_word_requested']          --> application/language/english/general_words_lang.php
// $lang['general_word_queued']             --> application/language/english/general_words_lang.php
// $lang['general_word_invalid_ignore']     --> application/language/english/general_words_lang.php
$lang['filter_qsl_verified'] = '已验证';

// $lang['general_word_qslcard_bureau']     --> application/language/english/general_words_lang.php
// $lang['general_word_qslcard_direct']     --> application/language/english/general_words_lang.php
// $lang['general_word_qslcard_electronic'] --> application/language/english/general_words_lang.php
// $lang['general_word_qslcard_manager']    --> application/language/english/general_words_lang.php

/*
___________________________________________________________________________________________
General Filters
___________________________________________________________________________________________
*/

$lang['filter_general_from'] = '开始日期';
$lang['filter_general_to'] = '截止日期';
// $lang['gen_hamradio_de']             --> application/language/english/general_words_lang.php 
// $lang['gen_hamradio_dx']             --> application/language/english/general_words_lang.php 
// $lang['gen_hamradio_dxcc']           --> application/language/english/general_words_lang.php
$lang['filter_general_none'] = '- 无 - (例如 /MM, /AM)';
// $lang['gen_hamradio_state']          --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_gridsquare']     --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_mode']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_band']           --> application/language/english/general_words_lang.php

$lang['filter_general_propagation'] = '传播方式';
// $lang['gen_hamradio_cq_zone']        --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_iota']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_sota']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_wwff']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_pota']           --> application/language/english/general_words_lang.php

/*
___________________________________________________________________________________________
Actions
___________________________________________________________________________________________
*/

$lang['filter_actions_w_selected'] = '用选中行进行：';
$lang['filter_actions_update_f_callbook'] = '从 Callbook 更新';
$lang['filter_actions_queue_bureau'] = '卡片局队列';
$lang['filter_actions_queue_direct'] = '直邮卡片队列';
$lang['filter_actions_queue_electronic'] = '电子卡片队列';
$lang['filter_actions_sent_bureau'] = '已发送 (卡片局)';
$lang['filter_actions_sent_direct'] = '已发送 (直邮)';
$lang['filter_actions_sent_electronic'] = '已发送 (电子)';
$lang['filter_actions_not_sent'] = '未发送';
$lang['filter_actions_qsl_n_required'] = '未获取 QSL';
$lang['filter_actions_recv_bureau'] = '已接收 (卡片局)';
$lang['filter_actions_recv_direct'] = '已接收 (直邮)';
$lang['filter_actions_recv_electronic'] = '已接收 (电子)';
$lang['filter_actions_create_adif'] = '创建 ADIF';
$lang['filter_actions_print_label'] = '打印标签';
$lang['filter_actions_start_print_title'] = '打印标签';
$lang['filter_actions_print_include_via'] = "包含通过";
$lang['filter_actions_print_include_grid'] = '包含网格？';
$lang['filter_actions_start_print'] = '开始打印编号';
$lang['filter_actions_print'] = '打印';
$lang['filter_actions_qsl_slideshow'] = 'QSL 展示窗';
$lang['filter_actions_delete'] = '删除';
$lang['filter_actions_delete_warning'] = "警告！确定要删除选中的QSO吗？";


/*
___________________________________________________________________________________________
Options
___________________________________________________________________________________________
*/

$lang['filter_options_title'] = '高级日志选项';
$lang['filter_options_column'] = '列';
$lang['filter_options_show'] = '显示';
// $lang['general_word_datetime']       --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_de']             --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_dx']             --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_mode']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_rsts']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_rstr']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_band']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_myrefs']         --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_refs']           --> application/language/english/general_words_lang.php
// $lang['general_word_name']           --> application/language/english/general_words_lang.php
// $lang['filter_qsl_via']              --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_qsl']            --> application/language/english/general_words_lang.php
// $lang['lotw_short']                  --> application/language/english/lotw_lang.php
// $lang['eqsl_short']                  --> application/language/english/eqsl_lang.php
// $lang['gen_hamradio_qslmsg']         --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_dxcc']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_state']          --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_cq_zone']        --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_iota']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_sota']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_wwff']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_pota']           --> application/language/english/general_words_lang.php
// $lang['options_save']                --> application/language/english/options_lang.php
$lang['filter_search_operator']='搜素操作员';
$lang['filter_options_close'] = '关闭';
