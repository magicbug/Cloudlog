<?php
defined('BASEPATH') OR exit('Doğrudan komut dosyası erişimine izin verilmiyor');

// Döşemeler
$lang['qso_title_qso_map'] = 'QSO Haritası';
$lang['qso_title_suggestions'] = 'Öneriler';
$lang['qso_title_predivis_contacts'] = 'Önceki Görüşmeler';
$lang['qso_title_times_worked_before'] = "daha önce kaç kez çalışıldı";
$lang['qso_title_image'] = 'Profil Resmi';
$lang['qso_predivis_max_shown'] = "Önceki en fazla 5 kişi gösterilir";

// Dashboard'da Quicklog
$lang['qso_quicklog_enter_callsign'] = 'QUICKLOG Çağrı İşaretini Girin';

// /QSO Ekranına Yardım Metnini Girin
$lang['qso_transmit_power_helptext'] = 'Gücü Watt olarak veriniz. Sadece bilgilerinizi giriniz.';

$lang['qso_sota_ref_helptext'] = 'Örnek: GM/NS-001.';
$lang['qso_wwff_ref_helptext'] = 'Örnek: DLFF-0069.';
$lang['qso_pota_ref_helptext'] = 'Örnek: PA-0150.';

$lang['qso_sig_helptext'] = 'Örnek: GMA';
$lang['qso_sig_info_helptext'] = 'Örnek: DA/NW-357';

$lang['qso_dok_helptext'] = 'Örnek: Q03';

$lang['qso_notes_helptext'] = 'İçerik değil yalnızca Cloudlog içinde kullanılır ve diğer servislere aktarılmaz';
$lang['qsl_notes_helptext'] = 'Bu içeriğin içeriği eqsl.cc gibi QSL servislerine aktarılır';

$lang['qso_eqsl_qslmsg_helptext'] = "Bu istasyon için eQSL'ye ilişkin varsayılan mesajı alın.";

// hata metni //
$lang['qso_error_timeoff_less_timeon'] = "TimeOff, TimeOn'dan küçük";

// /qso Ekranındaki Düğme Metni

$lang['qso_btn_reset_qso'] = 'Baştan başla';
$lang['qso_btn_save_qso'] = 'QSO\'yu kaydet';
$lang['qso_btn_edit_qso'] = 'QSO\'yu düzenleyin';
$lang['qso_delete_warning'] = "Uyarı! QSO'yu silmek istediğinizden emin misiniz?";

// QSO Ayrıntıları

$lang['qso_details'] = 'QSO ayrıntıları';

$lang['fav_add'] = 'Favorilere Bant/Mod Ekle';
$lang['qso_operator_callsign'] = 'Operatör Çağrı İmzası';
// Basit FLE (FastLogEntry)

$lang['qso_simplefle_info'] = "Bu nedir?";
$lang['qso_simplefle_info_ln1'] = "Basit Hızlı Günlük Girişi (FLE)";
$lang['qso_simplefle_info_ln2'] = "'Hızlı Günlük Girişi' veya basitçe 'FLE', QSO'ları çok hızlı ve verimli bir şekilde günlüğe kaydeden bir sistemdir. Söz dizimi nedeniyle, çok az sayıda QSO'yu günlüğe kaydetmek için yalnızca minimum düzeyde giriş gerekir mümkün olduğunca çaba sarf edin.";
$lang['qso_simplefle_info_ln3'] = "FLE, orijinal olarak DF3CB tarafından yazılmıştır. Web sitesinde Windows için bir program sunmaktadır. Basit FLE, DF3CB'nin FLE'sini temel alarak OK2CQR tarafından yazılmıştır ve QSO'ları kaydetmek için bir web arayüzü sağlar.";
$lang['qso_simplefle_info_ln4'] = "Kağıt günlüklerinizi bir dış mekan oturumundan içe aktarmanız gerekiyorsa yaygın bir kullanım durumudur ve artık SimpleFLE, Cloudlog'da da mevcuttur. Sözdizimi ve FLE'nin nasıl çalıştığı hakkında bilgi şu adreste bulunabilir: <a href= 'https://df3cb.com/fle/documentation/' target='_blank'>buraya</a>.";
$lang['qso_simplefle_qso_data'] = "QSO Verisi";
$lang['qso_simplefle_qso_date_hint'] = "Tarih seçmezseniz bugünün tarihi kullanılacaktır.";
$lang['qso_simplefle_qso_list'] = "QSO Listesi";
$lang['qso_simplefle_qso_list_total'] = "Toplam";
$lang['qso_simplefle_qso_date'] = "QSO Tarihi";
$lang['qso_simplefle_operator'] = "Operatör";
$lang['qso_simplefle_operator_hint'] = "örn. OK2CQR";
$lang['qso_simplefle_station_call_location'] = "İstasyon Çağrısı/Konumu";
$lang['qso_simplefle_station_call_location_hint'] = "Yeni bir konumdan işlem yaptıysanız, önce yeni bir <a href=". site_url('istasyon') . ">İstasyon Konumu</a> oluşturun.";
$lang['qso_simplefle_utc_time'] = "Geçerli UTC Saati";
$lang['qso_simplefle_enter_the_data'] = "Verileri Girin";
$lang['qso_simplefle_syntax_help_close_w_sample'] = "Örnek Veriyi Kapat ve Yükle";
$lang['qso_simplefle_reload'] = "QSO Listesini Yeniden Yükle";
$lang['qso_simplefle_save'] = "Cloudlog'a Kaydet";
$lang['qso_simplefle_clear'] = "Günlük Oturumunu Temizle";
$lang['qso_simplefle_refs_hint'] = "Referanslar <u>S</u>OTA, <u>I</u>OTA, <u>P</u>OTA veya <u>W</ olabilir u>WFF";

$lang['qso_simplefle_error_band'] = "Bant eksik!";
$lang['qso_simplefle_error_mode'] = "Mod eksik!";
$lang['qso_simplefle_error_time'] = "Zaman ayarlı değil!";
$lang['qso_simplefle_error_stationcall'] = "İstasyon Çağrısı seçilmedi";
$lang['qso_simplefle_error_operator'] = "'Operatör' Alanı boş";
$lang['qso_simplefle_warning_reset'] = "Uyarı! Gerçekten her şeyi sıfırlamak istiyor musunuz?";
$lang['qso_simplefle_warning_missing_band_mode'] = "Uyarı! QSO Listesini günlüğe kaydedemezsiniz çünkü bazı QSO'larda tanımlanmış bant ve/veya mod yoktur!";
$lang['qso_simplefle_warning_missing_time'] = "Uyarı! QSO Listesini günlüğe kaydedemezsiniz çünkü bazı QSO'ların tanımlanmış bir zamanı yoktur!";
$lang['qso_simplefle_warning_example_data'] = "Dikkat! Veri Alanı örnek veriler içerir. İlk Kayıt Oturumunu Temizleyin!";
$lang['qso_simplefle_confirm_save_to_log'] = "Bu QSO'yu Günlüğe eklemek ve oturumu temizlemek istediğinizden emin misiniz?";
$lang['qso_simplefle_success_save_to_log_header'] = "QSO Günlüğe Kaydedildi!";
$lang['qso_simplefle_success_save_to_log'] = "QSO, kayıt defterine başarıyla kaydedildi!";
$lang['qso_simplefle_error_date'] = "Geçersiz tarih";

$lang['qso_simplefle_syntax_help_button'] = "Söz Dizimi Yardımı";
$lang['qso_simplefle_syntax_help_title'] = "FLE için Sözdizimi";
$lang['qso_simplefle_syntax_help_ln1'] = "QSO'yu günlüğe kaydetmeye başlamadan önce lütfen temel kurallara dikkat edin.";
$lang['qso_simplefle_syntax_help_ln2'] = "- Her yeni QSO yeni bir satırda olmalıdır.";
$lang['qso_simplefle_syntax_help_ln3'] = "- Her yeni satıra yalnızca önceki QSO'dan değişen verileri yazın.";
$lang['qso_simplefle_syntax_help_ln4'] = "Başlamak için soldaki formu tarih, istasyon çağrısı ve operatörün çağrısıyla birlikte doldurduğunuzdan emin olun. Ana veriler bandı (veya MHz cinsinden QRG'yi, örneğin '7.145) içerir. '), mod ve zaman. Zamandan sonra, aslında çağrı işareti olan ilk QSO'yu sağlarsınız.";
$lang['qso_simplefle_syntax_help_ln5'] = "Örneğin, 20m SSB'de 2M0SQL ile 21:34 (UTC)'de başlayan bir QSO.";
$lang['qso_simplefle_syntax_help_ln6'] = "Eğer herhangi bir RST bilgisi sağlamazsanız, sözdizimi 59'u (veri için 599) kullanacaktır. Bir sonraki QSO'muz her iki tarafta da 59 değildi, dolayısıyla bilgileri gönderilen RST ile sağlıyoruz ilki, ilk QSO'dan 2 dakika sonraydı.";
$lang['qso_simplefle_syntax_help_ln7'] = "İlk QSO 21:34'teydi, ikincisi ise 2 dakika sonra 21:36'daydı. Burada değişen tek veri bu olduğu için 6 yazdık. Bant ve mod hakkında bilgiler değişmediğinden bu veriler atlandı.";
$lang['qso_simplefle_syntax_help_ln8'] = "14 Mayıs 2021 saat 21:40'taki bir sonraki QSO'muz için bandı 40m olarak değiştirdik ancak hala SSB'deyiz. RST bilgisi verilmemişse sözdizimi her yeni QSO için 59'u kullanacak Bu nedenle iki gün sonra tam olarak aynı saatte gerçekleşen başka bir QSO'yu ekleyebiliriz. Tarih YYYY-AA-GG biçiminde olmalıdır.";
$lang['qso_simplefle_syntax_help_ln9'] = "Sözdizimi hakkında daha fazla bilgi için lütfen DF3CB web sitesine bakın <a href='https://df3cb.com/fle/documentation/' target='_blank'>.</a>";
