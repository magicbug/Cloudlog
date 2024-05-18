<?php

defined('BASEPATH') OR exit('Doğrudan komut dosyası erişimine izin verilmiyor');


/*
___________________________________________________________________________________________
Üst çubuk
___________________________________________________________________________________________
*/

$lang['filter_quickfilters'] = 'Hızlı filtreler';
$lang['filter_qsl_filters'] = 'QSL Filtreleri';
$lang['filter_filters'] = 'Filtreler';
$lang['filter_actions'] = 'Eylemler';
$lang['filter_results'] = '# Sonuçlar';
$lang['filter_search'] = 'Ara';
$lang['filter_dupes'] = "Yinelenenler";
$lang['filter_map'] = 'Harita';
$lang['filter_options'] = 'Seçenekler';
$lang['filter_reset'] = 'Sıfırla';

/*
___________________________________________________________________________________________
Hızlı filtreler
___________________________________________________________________________________________
*/

$lang['filter_quicksearch_w_sel'] = 'Seçiliyken hızlı arama: ';
$lang['filter_search_callsign'] = 'Çağrı İşaretini Ara';
$lang['filter_search_dxcc'] = "DXCC'de ara";
$lang['filter_search_state'] = 'Arama Durumu';
$lang['filter_search_gridsquare'] = "Gridsquare'de Ara";
$lang['filter_search_cq_zone'] = 'CQ Bölgesini Ara';
$lang['filter_search_mode'] = 'Arama Modu';
$lang['filter_search_band'] = 'Arama Bandı';
$lang['filter_search_iota'] = "IOTA'yı ara";
$lang['filter_search_sota'] = "SOTA'da ara";
$lang['filter_search_wwff'] = "WWFF'de ara";
$lang['filter_search_pota'] = "POTA'yı ara";

/*
___________________________________________________________________________________________
QSL Filtreleri
___________________________________________________________________________________________
*/

$lang['filter_qsl_sent'] = 'QSL gönderildi';
$lang['filter_qsl_recv'] = 'QSL alındı';
$lang['filter_qsl_sent_method'] = 'QSL gönder. yöntem';
$lang['filter_qsl_recv_method'] = 'QSL recv. yöntem';
$lang['filter_lotw_sent'] = 'LoTW gönderildi';
$lang['filter_lotw_recv'] = 'LoTW alındı';
$lang['filter_eqsl_sent'] = 'eQSL gönderildi';
$lang['filter_eqsl_recv'] = 'eQSL alındı';
$lang['filter_qsl_via'] = 'QSL yoluyla';
$lang['filter_qsl_images'] = 'QSL Resimleri';

// $lang['general_word_all'] --> uygulama/dil/english/general_words_lang.php
// $lang['general_word_yes'] --> uygulama/dil/english/general_words_lang.php
// $lang['general_word_no'] --> uygulama/dil/english/general_words_lang.php
// $lang['general_word_requested'] --> uygulama/dil/english/general_words_lang.php
// $lang['general_word_queued'] --> uygulama/dil/english/general_words_lang.php
// $lang['general_word_invalid_ignore'] --> uygulama/dil/english/general_words_lang.php
$lang['filter_qsl_verified'] = 'Doğrulandı';

// $lang['general_word_qslcard_bureau'] --> uygulama/dil/english/general_words_lang.php
// $lang['general_word_qslcard_direct'] --> uygulama/dil/english/general_words_lang.php
// $lang['general_word_qslcard_electronic'] --> uygulama/dil/english/general_words_lang.php
// $lang['general_word_qslcard_manager'] --> uygulama/dil/english/general_words_lang.php
/*
___________________________________________________________________________________________
General Filters
___________________________________________________________________________________________
*/

$lang['filter_general_from'] = 'From';
$lang['filter_general_to'] = 'to';
// $lang['gen_hamradio_de']             --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_dx']             --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_dxcc']           --> application/language/english/general_words_lang.php
$lang['filter_general_none'] = '- NONE - (e.g. /MM, /AM)';
// $lang['gen_hamradio_state']          --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_gridsquare']     --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_mode']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_band']           --> application/language/english/general_words_lang.php

$lang['filter_general_propagation'] = 'Yayılma';
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
$lang['filter_actions_w_selected'] = 'Seçiliyken: ';
$lang['filter_actions_update_f_callbook'] = 'Arama Defterinden Güncelle';
$lang['filter_actions_queue_bureau'] = 'Bürodan Kuyruk';
$lang['filter_actions_queue_direct'] = 'Doğrudan Kuyruk';
$lang['filter_actions_queue_electronic'] = 'Elektronik Sıra';
$lang['filter_actions_sent_bureau'] = 'Gönderildi (Büro)';
$lang['filter_actions_sent_direct'] = 'Gönderildi (Doğrudan)';
$lang['filter_actions_sent_electronic'] = 'Gönderildi (Elektronik)';
$lang['filter_actions_not_sent'] = 'Gönderilmedi';
$lang['filter_actions_qsl_n_required'] = 'QSL Gerekli Değil';
$lang['filter_actions_recv_bureau'] = 'Alındı (Büro)';
$lang['filter_actions_recv_direct'] = 'Alındı (Doğrudan)';
$lang['filter_actions_recv_electronic'] = 'Alındı (Elektronik)';
$lang['filter_actions_create_adif'] = 'ADIF Oluştur';
$lang['filter_actions_print_label'] = 'Etiketi Yazdır';
$lang['filter_actions_start_print_title'] = 'Etiketleri Yazdır';
$lang['filter_actions_print_include_via'] = "Şununla Ekle";
$lang['filter_actions_print_include_grid'] = 'Kılavuz karesi dahil edilsin mi?';
$lang['filter_actions_start_print'] = 'Yazdırmaya şu saatte başlansın mı?';
$lang['filter_actions_print'] = 'Yazdır';
$lang['filter_actions_qsl_slideshow'] = 'QSL Slayt Gösterisi';
$lang['filter_actions_delete'] = 'Sil';
$lang['filter_actions_delete_warning'] = "Uyarı! İşaretli QSO'ları silmek istediğinizden emin misiniz?";


/*
___________________________________________________________________________________________
Seçenekler
___________________________________________________________________________________________
*/

$lang['filter_options_title'] = 'Gelişmiş Kayıt Defteri Seçenekleri';
$lang['filter_options_column'] = 'Sütun';
$lang['filter_options_show'] = 'Göster';
// $lang['general_word_datetime'] --> uygulama/dil/english/general_words_lang.php
// $lang['gen_hamradio_de'] --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_dx'] --> uygulama/dil/english/general_words_lang.php
// $lang['gen_hamradio_mode'] --> uygulama/dil/english/general_words_lang.php
// $lang['gen_hamradio_rsts'] --> uygulama/dil/english/general_words_lang.php
// $lang['gen_hamradio_rstr'] --> uygulama/dil/english/general_words_lang.php
// $lang['gen_hamradio_band'] --> uygulama/dil/english/general_words_lang.php
// $lang['gen_hamradio_myrefs'] --> uygulama/dil/english/general_words_lang.php
// $lang['gen_hamradio_refs'] --> uygulama/dil/english/general_words_lang.php
// $lang['general_word_name'] --> uygulama/dil/english/general_words_lang.php
// $lang['filter_qsl_via'] --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_qsl'] --> uygulama/dil/english/general_words_lang.php
// $lang['lotw_short'] --> uygulama/dil/english/lotw_lang.php
// $lang['eqsl_short'] --> uygulama/dil/english/eqsl_lang.php
// $lang['gen_hamradio_qslmsg'] --> uygulama/dil/english/general_words_lang.php
// $lang['gen_hamradio_dxcc'] --> uygulama/dil/english/general_words_lang.php
// $lang['gen_hamradio_state'] --> uygulama/dil/english/general_words_lang.php
// $lang['gen_hamradio_cq_zone'] --> uygulama/dil/english/general_words_lang.php
// $lang['gen_hamradio_iota'] --> uygulama/dil/english/general_words_lang.php
// $lang['gen_hamradio_sota'] --> uygulama/dil/english/general_words_lang.php
// $lang['gen_hamradio_wwff'] --> uygulama/dil/english/general_words_lang.php
// $lang['gen_hamradio_pota'] --> uygulama/dil/english/general_words_lang.php
// $lang['options_save'] --> application/language/english/options_lang.php
$lang['filter_search_operator']='Arama Operatörü';
$lang['filter_options_close'] = 'Kapat';
$lang['filter_options_close'] = 'Close';
