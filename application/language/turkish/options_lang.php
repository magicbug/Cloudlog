<?php
defined('BASEPATH') OR exit('Doğrudan komut dosyası erişimine izin verilmiyor');

$lang['options_cloudlog_options'] = 'Cloudlog Seçenekleri';
$lang['options_message1'] = 'Cloudlog Seçenekleri, kurulumun tüm kullanıcıları için kullanılan genel ayarlardır ve kullanıcı düzeyinde bir ayar varsa bunlar geçersiz kılınır.';

$lang['options_appearance'] = 'Görünüm';
$lang['options_theme'] = 'Tema';
$lang['options_global_theme_choice_this_is_used_when_users_arent_logged_in'] = 'Global Tema Seçimi, bu, kullanıcılar oturum açmadığında kullanılır.';
$lang['options_public_search_bar'] = 'Genel Arama Çubuğu';
$lang['options_this_allows_non_logged_in_users_to_access_the_search_functions'] = 'Bu, oturum açmamış kullanıcıların arama işlevlerine erişmesine olanak tanır.';
$lang['options_dashboard_notification_banner'] = "Kontrol Paneli Bildirim Banner'ı";
$lang['options_this_allows_to_disable_the_global_notification_banner_on_the_dashboard'] = 'Bu, kontrol panelindeki genel bildirim başlığının devre dışı bırakılmasına olanak tanır.';
$lang['options_dashboard_map'] = 'Kontrol Paneli Haritası';
$lang['options_this_allows_the_map_on_the_dashboard_to_be_disabled_or_placed_on_the_right'] = 'Bu, kontrol panelindeki haritanın devre dışı bırakılmasına veya sağa yerleştirilmesine olanak tanır.';
$lang['options_logbook_map'] = 'Kayıt Defteri Haritası';
$lang['options_this_allows_to_disable_the_map_in_the_logbook'] = 'Bu, kayıt defterindeki haritanın devre dışı bırakılmasına olanak tanır.';
$lang['options_theme_changed_to'] = "Tema değiştirildi ";
$lang['options_global_search_changed_to'] = "Genel Arama değiştirildi ";
$lang['options_dashboard_banner_changed_to'] = "Kontrol paneli başlığı değiştirildi ";
$lang['options_dashboard_map_changed_to'] = "Kontrol paneli haritası değiştirildi ";
$lang['options_logbook_map_changed_to'] = "Kayıt defteri haritası olarak değiştirildi ";

$lang['options_radios'] = 'Radyolar';
$lang['options_radio_settings'] = 'Radyo Ayarları';
$lang['options_radio_timeout_warning'] = 'Radyo Zaman Aşımı Uyarısı';
$lang['options_the_radio_timeout_warning_is_used_on_the_qso_entry_panel_to_alert_you_to_radio_interface_disconnects'] = 'Radyo Zaman Aşımı Uyarısı, radyo arayüzü bağlantısı kesintilerinde sizi uyarmak için QSO giriş panelinde kullanılır.';
$lang['options_this_number_is_in_seconds'] = 'Bu sayı saniye cinsindendir.';
$lang['options_radio_timeout_warning_changed_to'] = 'Radyo Zaman Aşımı Uyarısı değiştirildi ';

$lang['options_email'] = 'E-posta';
$lang['options_outgoing_protocol'] = 'Giden Protokol';
$lang['options_smtp_encryption'] = 'SMTP Şifrelemesi';
$lang['options_email_address'] = 'E-posta Adresi';
$lang['options_email_sender_name'] = 'E-posta Gönderenin Adı';
$lang['options_smtp_host'] = 'SMTP Ana Bilgisayarı';
$lang['options_smtp_port'] = 'SMTP Bağlantı Noktası';
$lang['options_smtp_username'] = 'SMTP Kullanıcı Adı';
$lang['options_smtp_password'] = 'SMTP Şifresi';
$lang['options_mail_settings_saved'] = "Ayarlar başarıyla kaydedildi.";
$lang['options_mail_settings_failed'] = "Ayarlar kaydedilirken bir şeyler ters gitti. Tekrar deneyin.";
$lang['options_outgoing_protocol_hint'] = "E-posta göndermek için kullanılacak protokol.";
$lang['options_smtp_encryption_hint'] = "E-postaların TLS ile mi yoksa SSL ile mi gönderileceğini seçin.";
$lang['options_email_address_hint'] = "E-postaların gönderildiği e-posta adresi, örneğin 'cloudlog@example.com'";
$lang['options_email_sender_name_hint'] = "E-postayı gönderenin adı, örneğin 'Cloudlog'";
$lang['options_smtp_host_hint'] = "Posta sunucusunun ana bilgisayar adı, örneğin 'mail.example.com' ('ssl://' veya 'tls://' olmadan)";
$lang['options_smtp_port_hint'] = "Posta sunucusunun SMTP bağlantı noktası, örneğin TLS kullanılıyorsa -> '587', SSL kullanılıyorsa -> '465'";
$lang['options_smtp_username_hint'] = "Posta sunucusuna giriş yapmak için kullanılan kullanıcı adı, genellikle kullanılan e-posta adresidir.";
$lang['options_smtp_password_hint'] = "Posta sunucusuna giriş yapmak için gereken şifre.";
$lang['options_send_testmail'] = "Test Postası Gönder";
$lang['options_send_testmail_hint'] = "Eposta, hesap ayarlarınızda tanımlanan adrese gönderilecektir.";
$lang['options_send_testmail_failed'] = "Test postası başarısız oldu. Bir şeyler ters gitti.";
$lang['options_send_testmail_success'] = "Test postası gönderildi. E-posta ayarları doğru görünüyor.";

$lang['options_oqrs'] = 'OQRS Seçenekleri';
$lang['options_global_text'] = 'Genel metin';
$lang['options_this_text_is_an_opsiyonel_text_that_can_be_displayed_on_top_of_the_oqrs_page'] = 'Bu metin, OQRS sayfasının üstünde görüntülenebilecek isteğe bağlı bir metindir.';
$lang['options_grouped_search'] = 'Gruplandırılmış arama';
$lang['options_when_this_is_on_all_station_locations_with_oqrs_active_will_be_searched_at_once'] = 'Bu açık olduğunda, OQRS aktif olan tüm istasyon konumları aynı anda aranacaktır.';
$lang['options_grouped_search_show_station_name'] = "Gruplandırılmış arama sonuçlarında istasyon konumu adını göster";
$lang['options_grouped_search_show_station_name_hint'] = "Gruplandırılmış arama AÇIK ise, istasyon konumunun adının sonuçlar tablosunda gösterilip gösterilmeyeceğine karar verebilirsiniz.";
$lang['options_oqrs_options_have_been_saved'] = 'OQRS seçenekleri kaydedildi.';
$lang['options_dxcluster'] = 'DXCluster';
$lang['options_dxcluster_provider'] = 'DXClusterCache Sağlayıcısı';
$lang['options_dxcluster_longtext'] = 'DXCluster-Cache Sağlayıcısı. <a href="https://github.com/int2001/DXClusterAPI">DXClusterAPI</a> ile kendi Önbelleğinizi oluşturabilir veya herkese açık bir önbellek kullanabilirsiniz';
$lang['options_dxcluster_hint'] = "DXCluster-Cache'in URL'si. Örneğin. https://dxc.jo30.de/dxcache";
$lang['options_dxcluster_settings'] = 'DXCluster';
$lang['options_dxcache_url_changed_to'] = "DXCluster Önbellek URL'si değiştirildi";
$lang['options_dxcluster_maxage'] = 'Dikkate alınan noktaların Maksimum Yaşı';
$lang['options_dxcluster_maxage_hint'] = 'Bant planında/aramada dikkate alınacak noktaların Dakika Cinsinden Yaşı';
$lang['options_dxcluster_decont'] = 'Aşağıdaki kıtadan tespit edilen noktaları göster';
$lang['options_dxcluster_maxage_changed_to']="Noktaların maksimum yaşı değiştirildi";
$lang['options_dxcluster_decont_changed_to']="kıta olarak değiştirildi";
$lang['options_dxcluster_decont_hint']='Yalnızca bu kıtadaki gözcülerin noktaları gösteriliyor';

$lang['options_version_dialog'] = "Sürüm Bilgisi";
$lang['options_version_dialog_close'] = "Kapat";
$lang['options_version_dialog_dismiss'] = "Bir daha gösterme";
$lang['options_version_dialog_settings'] = "Sürüm Bilgisi Ayarları";
$lang['options_version_dialog_header'] = "Sürüm Bilgisi Başlığı";
$lang['options_version_dialog_header_hint'] = "Sürüm bilgisi iletişim kutusunun başlığını değiştirebilirsiniz.";
$lang['options_version_dialog_header_changed_to'] = "Sürüm Bilgisi Başlığı şu şekilde değiştirildi";
$lang['options_version_dialog_mode'] = "Sürüm Bilgisi Modu";
$lang['options_version_dialog_mode_release_notes'] = "Yalnızca Sürüm Notları";
$lang['options_version_dialog_mode_custom_text'] = "Yalnızca Özel Metin";
$lang['options_version_dialog_mode_both'] = "Sürüm Notları ve Özel Metin";
$lang['options_version_dialog_mode_disabled'] = "Devre Dışı";
$lang['options_version_dialog_mode_hint'] = "Sürüm Bilgisi her kullanıcıya gösterilir. Kullanıcının, iletişim kutusunu okuduktan sonra kapatma seçeneği vardır. Yalnızca sürüm notlarını (githubdan alınan), yalnızca özel metni göstermek istiyorsanız seçin ya da her ikisini.";
$lang['options_version_dialog_custom_text'] = "Sürüm Bilgisi Özel Metni";
$lang['options_version_dialog_custom_text_hint'] = "Bu, iletişim kutusunda gösterilen özel metindir.";
$lang['options_version_dialog_mode_changed_to'] = "Sürüm Bilgisi Modu şu şekilde değiştirildi";
$lang['options_version_dialog_custom_text_saved'] = "Sürüm Bilgisi Özel Metni kaydedildi!";
$lang['options_version_dialog_success_show_all'] = "Sürüm Bilgisi tüm kullanıcılara yeniden gösterilecek";
$lang['options_version_dialog_success_hide_all'] = "Sürüm Bilgisi hiçbir kullanıcıya gösterilmeyecek";
$lang['options_version_dialog_show_hide'] = "Tüm Kullanıcılar için Sürüm Bilgisi İletişim Kutusunu Göster/Gizle";
$lang['options_version_dialog_show_all'] = "Tüm Kullanıcılar için Göster";
$lang['options_version_dialog_hide_all'] = "Tüm Kullanıcılar için Gizle";
$lang['options_version_dialog_show_all_hint'] = "Bu, bir sonraki sayfa yeniden yüklemelerinde tüm kullanıcılara sürüm iletişim kutusunu otomatik olarak gösterecektir.";
$lang['options_version_dialog_hide_all_hint'] = "Bu, tüm kullanıcılar için sürüm iletişim kutusunun otomatik olarak açılmasını devre dışı bırakacaktır.";

$lang['options_save'] = 'Kaydet';

// Bantlar

$lang['options_bands'] = "Bantlar";
$lang['options_bands_text_ln1'] = "Bant listesini kullanarak yeni bir QSO oluştururken hangi bantların gösterileceğini kontrol edebilirsiniz.";
$lang['options_bands_text_ln2'] = "Aktif bantlar QSO 'Bant' açılır menüsünde gösterilecek, aktif olmayan bantlar ise gizlenecek ve seçilemeyecek.";
$lang['options_bands_create'] = "Bir grup oluşturun";
$lang['options_bands_edit'] = "Bandı Düzenle";
$lang['options_bands_activate_all'] = "Tümünü Etkinleştir";
$lang['options_bands_activateall_warning'] = "Uyarı! Tüm bantları etkinleştirmek istediğinizden emin misiniz?";
$lang['options_bands_deactivate_all'] = "Tümünü Devre Dışı Bırak";
$lang['options_bands_deactivateall_warning'] = "Uyarı! Tüm bantları devre dışı bırakmak istediğinizden emin misiniz?";
$lang['options_bands_ssb_qrg'] = "SSB QRG";
$lang['options_bands_ssb_qrg_hint'] = "Banttaki SSB QRG frekansı (Hz cinsinden olmalıdır)";
$lang['options_bands_data_qrg'] = "VERİ QRG";
$lang['options_bands_data_qrg_hint'] = "Banttaki DATA QRG frekansı (Hz cinsinden olmalıdır)";
$lang['options_bands_cw_qrg'] = "CW QRG";
$lang['options_bands_cw_qrg_hint'] = "Banttaki CW QRG frekansı (Hz cinsinden olmalıdır)";

$lang['options_bands_name_band'] = "Bant Adı (Örn. 20m)";
$lang['options_bands_name_bandgroup'] = "Bant grubunun adı (Örn. hf, vhf, uhf, shf)";
$lang['options_bands_delete_warning'] = "Uyarı! Aşağıdaki bandı silmek istediğinizden emin misiniz: ";
