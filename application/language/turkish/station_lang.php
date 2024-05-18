<?php

defined('BASEPATH') OR exit('Doğrudan komut dosyası erişimine izin verilmiyor');

/*
___________________________________________________________________________________________
İstasyon Kayıt Defterleri
___________________________________________________________________________________________
*/

$lang['station_logbooks'] = "İstasyon Kayıt Defterleri";
$lang['station_logbooks_description_header'] = "İstasyon Kayıt Defterleri nedir";
$lang['station_logbooks_description_text'] = "İstasyon Kayıt Defterleri İstasyon Konumlarını gruplandırmanıza olanak tanır; bu, kayıt defteri alanlarından analitiklere kadar tek bir oturumdaki tüm konumları görmenizi sağlar. Birden fazla konumda çalıştığınızda harikadır ancak bunlar bir bölümün parçasıdır aynı DXCC veya VUCC Çevresi.";
$lang['station_logbooks_create'] = "İstasyon Kayıt Defterini Oluşturun";
$lang['station_logbooks_status'] = "Durum";
$lang['station_logbooks_link'] = "Bağlantı";
$lang['station_logbooks_public_search'] = "Genel Arama";
$lang['station_logbooks_set_active'] = "Aktif Kayıt Defteri Olarak Ayarla";
$lang['station_logbooks_active_logbook'] = "Aktif Kayıt Defteri";
$lang['station_logbooks_edit_logbook'] = "İstasyon Kayıt Defterini Düzenle"; // Tam cümle oluşturulacak 'İstasyon Kayıt Defterini Düzenle: [Kayıt Defteri Adı]'
$lang['station_logbooks_confirm_delete'] = "Aşağıdaki istasyon kayıt defterini silmek istediğinizden emin misiniz? Burada bağlantılı tüm konumları başka bir kayıt defterine yeniden bağlamanız gerekir.: ";
$lang['station_logbooks_view_public'] = "Kayıt Defteri için Genel Sayfayı Görüntüle: ";
$lang['station_logbooks_create_name'] = "İstasyon Kayıt Defteri Adı";
$lang['station_logbooks_create_name_hint'] = "İstasyon kayıt defterini istediğiniz şekilde arayabilirsiniz.";
$lang['station_logbooks_edit_name_hint'] = "İstasyon kayıt defterinin kısa adı. Örneğin: Ev Günlüğü (IO87IP)";
$lang['station_logbooks_edit_name_update'] = "İstasyon Kayıt Defteri Adını Güncelle";
$lang['station_logbooks_public_slug'] = "Genel Slug";
$lang['station_logbooks_public_slug_hint'] = "Genel bilgi notu ayarlamak, kayıt defterinizi özel bir web sitesi adresi aracılığıyla herhangi biriyle paylaşmanıza olanak tanır, bu bilgi yalnızca harf ve rakam içerebilir.";
$lang['station_logbooks_public_slug_format1'] = "Daha sonra şöyle görünecek:";
$lang['station_logbooks_public_slug_format2'] = "[bilginiz]";
$lang['station_logbooks_public_slug_input'] = "Genel Slug seçimini yazın";
$lang['station_logbooks_public_slug_visit'] = "Genel Sayfayı Ziyaret Edin";
$lang['station_logbooks_public_search_hint'] = "Genel arama işlevinin etkinleştirilmesi, genel bilgi yoluyla erişilen genel kayıt defteri sayfasında bir arama giriş kutusu sunar. Arama yalnızca bu kayıt defterini kapsar.";
$lang['station_logbooks_public_search_enabled'] = "Genel arama etkin";
$lang['station_logbooks_select_avail_loc'] = "Mevcut İstasyon Konumlarını Seçin";
$lang['station_logbooks_link_loc'] = "Bağlantı Konumu";
$lang['station_logbooks_linked_loc'] = "Bağlantılı Konumlar";
$lang['station_logbooks_no_linked_loc'] = "Bağlantılı Konum Yok";
$lang['station_logbooks_unlink_station_location'] = "İstasyon Konumunun Bağlantısını Kaldır";



/*
___________________________________________________________________________________________
Station Locations
___________________________________________________________________________________________
*/

$lang['station_location'] = 'İstasyon Konumu';
$lang['station_location_plural'] = "İstasyon Konumları";
$lang['station_location_header_ln1'] = "İstasyon Konumları, sizin QTH'niz, bir arkadaşınızın QTH'si veya taşınabilir bir istasyon gibi çalışma konumlarını tanımlar.";
$lang['station_location_header_ln2'] = "Kayıt defterlerine benzer şekilde, istasyon profili bir dizi QSO'yu bir arada tutar.";
$lang['station_location_header_ln3'] = 'Aynı anda yalnızca bir istasyon etkin olabilir. Aşağıdaki tabloda bu, -Aktif İstasyon- etiketiyle gösterilmektedir.';
$lang['station_location_create_header'] = 'İstasyon Konumunu Oluşturun';
$lang['station_location_create'] = 'Bir İstasyon Konumu Oluşturun';
$lang['station_location_edit'] = 'İstasyon Konumunu Düzenle: ';
$lang['station_location_updated_suff'] = ' Güncellendi.';
$lang['station_location_warning'] = "Dikkat: Aktif bir istasyon konumu ayarlamanız gerekiyor. Birini seçmek için Çağrı İşareti->İstasyon Konumu'na gidin.";
$lang['station_location_resign_at'] = 'Lütfen bunları şu adrese yeniden atayın';
$lang['station_location_warning_resign'] = "Cloudlog'daki son değişiklikler nedeniyle QSO'ları istasyon profillerinize yeniden atamanız gerekiyor.";
$lang['station_location_name'] = 'Profil Adı';
$lang['station_location_name_hint'] = 'İstasyon konumunun kısa adı. Örneğin: Ev (IO87IP)';
$lang['station_location_callsign'] = 'İstasyon Çağrı İşareti';
$lang['station_location_callsign_hint'] = 'İstasyon çağrı işareti. Örneğin: 2M0SQL/P';
$lang['station_location_power'] = 'İstasyon Gücü (W)';
$lang['station_location_power_hint'] = 'Watt cinsinden varsayılan istasyon gücü. CAT tarafından üzerine yazıldı.';
$lang['station_location_emptylog'] = 'Günlüğü Boşalt';
$lang['station_location_confirm_active'] = 'Aşağıdaki istasyonu aktif istasyon yapmak istediğinizden emin misiniz: ';
$lang['station_location_set_active'] = 'Etkin Olarak Ayarla';
$lang['station_location_active'] = 'Aktif İstasyon';
$lang['station_location_claim_ownership'] = 'Sahiplik Talebi';
$lang['station_location_confirm_del_qso'] = "Bu istasyon profilindeki tüm QSO'ları silmek istediğinizden emin misiniz?";
$lang['station_location_confirm_del_stationlocation'] = 'İstasyon profilini silmek istediğinizden emin misiniz?';
$lang['station_location_confirm_del_stationlocation_qso'] = "Bu, bu istasyon profilindeki tüm QSO'ları silecek mi?";
$lang['station_location_dxcc'] = 'DXCC İstasyonu';
$lang['station_location_dxcc_hint'] = 'İstasyon DXCC varlığı. Örneğin: İskoçya';
$lang['station_location_dxcc_warning'] = "Burada bir dakika durun. Seçtiğiniz DXCC'nin tarihi geçmiş ve artık geçerli değil. Bu konum için hangi DXCC'nin doğru olduğunu kontrol edin. Eminseniz bu uyarıyı dikkate almayın.";
$lang['station_location_city'] = 'İstasyon Şehri';
$lang['station_location_city_hint'] = 'İstasyon şehri. Örneğin: Inverness';
$lang['station_location_state'] = 'İstasyon Durumu';
$lang['station_location_state_hint'] = 'İstasyon durumu. Yalnızca belirli ülkeler için geçerlidir. Uygun değilse boş bırakın.';
$lang['station_location_county'] = 'İstasyon İlçesi';
$lang['station_location_county_hint'] = 'İstasyon İlçesi (Yalnızca ABD/Alaska/Hawaii için kullanılır).';
$lang['station_location_gridsquare'] = 'İstasyon Izgara Meydanı';
$lang['station_location_gridsquare_hint_ln1'] = "İstasyon kare kare. Örneğin: IO87IP. Izgara karenizi bilmiyorsanız <a href='https://zone-check.eu/?m=loc' target=' _blank'>burayı tıklayın</a>!";
$lang['station_location_gridsquare_hint_ln2'] = "Eğer bir ızgara çizgisi üzerinde bulunuyorsanız, virgüllerle ayrılmış birden fazla ızgara karesi girin. Örneğin: IO77,IO78,IO87,IO88.";
$lang['station_location_iota_hint_ln1'] = "İstasyon IOTA referansı. Örneğin: EU-005";
$lang['station_location_iota_hint_ln2'] = "IOTA referanslarını <a target='_blank' href='https://www.iota-world.org/iota-directory/annex-f-short-title adresinden arayabilirsiniz -iota-reference-number-list.html'>IOTA World</a> web sitesi.";
$lang['station_location_sota_hint_ln1'] = "İstasyon SOTA referansı. SOTA referanslarını <a target='_blank' href='https://www.sotamaps.org/'>SOTA Haritaları</a> web sitesinde arayabilirsiniz. .";
$lang['station_location_wwff_hint_ln1'] = "İstasyon WWFF referansı. WWFF referanslarını <a target='_blank' href='https://www.cqgma.org/mvs/'>GMA Haritası</a'nda arayabilirsiniz. > web sitesi.";
$lang['station_location_pota_hint_ln1'] = "İstasyon POTA referansı. POTA referanslarını <a target='_blank' href='https://pota.app/#/map/'>POTA Haritası</a'nda arayabilirsiniz. > web sitesi.";
$lang['station_location_signature'] = "İmza";
$lang['station_location_signature_name'] = "İmza Adı";
$lang['station_location_signature_name_hint'] = "İstasyon İmzası (örn. GMA)..";
$lang['station_location_signature_info'] = "İmza Bilgileri";
$lang['station_location_signature_info_hint'] = "İstasyon İmza Bilgisi (örn. DA/NW-357).";
$lang['station_location_eqsl_hint'] = 'eQSL Profilinizde yapılandırılan QTH Takma Adı';
$lang['station_location_eqsl_defaultqslmsg'] = "Varsayılan QSLMSG";
$lang['station_location_eqsl_defaultqslmsg_hint'] = "Bu istasyon konumu için her QSO için doldurulacak ve gönderilecek varsayılan bir mesaj tanımlayın.";$lang['station_location_qrz_subscription'] = 'Abonelik Gerekli';
$lang['station_location_qrz_hint'] = "API anahtarınızı <a href='https://logbook.qrz.com/logbook' target='_blank'>QRZ.com Kayıt Defteri ayarları sayfasında bulun";
$lang['station_location_qrz_realtime_upload'] = 'QRZ.com Kayıt Defteri Gerçek Zamanlı Yükleme';
$lang['station_location_hrdlog_username'] = "HRDLog.net Kullanıcı Adı";
$lang['station_location_hrdlog_username_hint'] = "HRDlog.net'te kayıtlı olduğunuz kullanıcı adı (genellikle çağrı kodunuz).";
$lang['station_location_hrdlog_code'] = "HRDLog.net API Anahtarı";
$lang['station_location_hrdlog_realtime_upload'] = "HRDLog.net Kayıt Defteri Gerçek Zamanlı Yükleme";
$lang['station_location_hrdlog_code_hint'] = "API Kodunuzu <a href='http://www.hrdlog.net/EditUser.aspx' target='_blank'>HRDLog.net Kullanıcı Profili sayfasında oluşturun";
$lang['station_location_qo100_hint'] = "API anahtarınızı <a href='https://qo100dx.club' target='_blank'>QO-100 Dx Club'ınızın profil sayfasında oluşturun";
$lang['station_location_qo100_realtime_upload'] = "QO-100 Dx Club Gerçek Zamanlı Yükleme";
$lang['station_location_oqrs_enabled'] = "OQRS Etkin";
$lang['station_location_oqrs_email_alert'] = "OQRS E-posta uyarısı";
$lang['station_location_oqrs_email_hint'] = "Yönetici ve genel seçenekler altında e-postanın ayarlandığından emin olun.";
$lang['station_location_oqrs_text'] = "OQRS Metni";
$lang['station_location_oqrs_text_hint'] = "QSL'leme ile ilgili eklemek istediğiniz bazı bilgiler.";
$lang['station_location_clublog_realtime_upload']='ClubLog Gerçek Zamanlı Yükleme';
