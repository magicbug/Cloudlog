<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$lang['lotw_short'] = 'LoTW';
$lang['lotw_title'] = 'Logbook of the World';
$lang['lotw_title_available_cert'] = 'Kutsumerkin varmenteet';
$lang['lotw_title_information'] = 'Info';
$lang['lotw_title_upload_p12_cert'] = 'Tuo palveluun LoTW:n .p12 kutsumerkin varmenne';
$lang['lotw_title_export_p12_file_instruction'] = 'Vie .p12 tiedoston ohjeistus';
$lang['lotw_title_adif_import'] = 'ADIF tuonti';
$lang['lotw_title_adif_import_options'] = 'Tuonnin asetukset';

$lang['lotw_beta_warning'] = 'HUOM! LoTW synkronointi on vasta BETA-vaiheessa, katso wikistä lisätietoa ja apua.';
$lang['lotw_no_certs_uploaded'] = 'Sinun täytyy ladata ainakin yksi LoTW:n P12 tiedostomuotoinen kutsumerkin varmenne käyttääksesi tätä toimintoaluetta.';

$lang['lotw_date_created'] = 'Päivä jolloin luotu';
$lang['lotw_date_expires'] = 'Päivä joloin vanhenee';
$lang['lotw_qso_start_date'] = 'QSO aloituspäivä';
$lang['lotw_qso_end_date'] = 'QSO lopetuspäivä';
$lang['lotw_status'] = 'Status / Last upload';
$lang['lotw_options'] = 'Lisävaihtoehdot';
$lang['lotw_valid'] = 'Voimassa';
$lang['lotw_expired'] = 'Vanhentunut';
$lang['lotw_expiring'] = 'Vanhenee';
$lang['lotw_not_synced'] = 'Ei synkronoitu';

$lang['lotw_certificate_dxcc'] = 'Kutsumerkin varmenteen DXCC';
$lang['lotw_certificate_dxcc_help_text'] = 'Kutsumerkin varmenteen DXCC, esimerkiksi Finland.';

$lang['lotw_input_a_file'] = 'Lataa tiedosto';

$lang['lotw_upload_exported_adif_file_from_lotw'] = 'Lataa ADIF-tiedosto LoTW:n palvelusta: <a href="https://p1k.arrl.org/lotwuser/qsos?qsoscmd=adif" target="_blank">LATAA TÄSTÄ</a>, merkataksesi yhteydet kuitatuksi LoTW:ssa.';
$lang['lotw_upload_type_must_be_adi'] = 'Lokitiedoston pitää olla ADI-tiedosto --> .adi';

$lang['lotw_pull_lotw_data_for_me'] = 'Lataa ja tuo LoTW:n data';

$lang['lotw_report_download_overview_helptext'] ='Cloudlog käyttää profiiliisi tallennettuja LoTW:n käyttäjätietoja lokikoosteen lataamiseen. Lokikooste sisältää tähän päivään asti kaikki kuitatut yhteydet, alkaen valitsemastasi päivästä tai siitä päivästä kun olet viimeksi ne palvelusta ladannut .';

// Buttons
$lang['lotw_btn_lotw_import'] = 'LoTW Tuonti';
$lang['lotw_btn_upload_certificate'] = 'Lataa ja tuo kutsumerkin varmenne';
$lang['lotw_btn_delete'] = 'Poista';
$lang['lotw_btn_manual_sync'] = 'Synkronoi yhteydet manuaalisesti';
$lang['lotw_btn_upload_file'] = 'Lataa tiedosto';
$lang['lotw_btn_import_matches'] = 'Tuo LoTW:n osumat';

// P12 Export Text
$lang['lotw_p12_export_step_one'] = 'Avaa TQSL &amp; ja siirry kutsumerkin varmeenteen / Callsign Certificates välilehdelle';
$lang['lotw_p12_export_step_two'] = 'Klikkaa hiiren oikealla näppäimella haluamaasi kutsua';
$lang['lotw_p12_export_step_three'] = 'Klikkaa "Tallenna kutsumerkin varmenne / "Save Callsign Certificate File" äläkä aseta salasanaa';
$lang['lotw_p12_export_step_four'] = 'Lataa ja tuo äsken tallentamasi tiedosto tänne.';

$lang['lotw_confirmed'] = 'Tämä QSO on kuitattu LoTW:ssa';

// LoTW Expiry
$lang['lotw_cert_expiring'] = 'Vähintään yksi LoTW:n kutsumerkin varmenteesi on vanhentumassa!';
$lang['lotw_cert_expired'] = 'Vähintään yksi LoTW:n kutsumerkin varmenteesi on vanhentunut!!';

// Lotw User
$lang['lotw_user'] = 'Tämä asmea käyttää LoTW-palvelua. Viimeinen yhteyksien lähetys oli';
$lang['lotw_last_upload'] = 'Last upload';
