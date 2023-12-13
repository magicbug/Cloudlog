<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$lang['lotw_short'] = 'LoTW';
$lang['lotw_title'] = 'Logbook of the World';
$lang['lotw_title_available_cert'] = 'Имеющиеся сертификаты';
$lang['lotw_title_information'] = 'Информация';
$lang['lotw_title_upload_p12_cert'] = 'Загрузка LotW .p12 сертификата';
$lang['lotw_title_export_p12_file_instruction'] = 'Инструкции по экспорту .p12 файла';
$lang['lotw_title_adif_import'] = 'Импорт ADIF';
$lang['lotw_title_adif_import_options'] = 'Опции импорта';

$lang['lotw_beta_warning'] = 'Обратите внимание, что синхронизация с LoTW пока в статусе бета, подробнее в wiki.';
$lang['lotw_no_certs_uploaded'] = 'Вам необходимо загрузить сертификат LoTW в формате  p12 для использования этих функций.';

$lang['lotw_date_created'] = 'Дата создания';
$lang['lotw_date_expires'] = 'Дата окончания срока действия';
$lang['lotw_qso_start_date'] = 'Дата начала QSO';
$lang['lotw_qso_end_date'] = 'Дата окончания QSO';
$lang['lotw_status'] = 'Статус';
$lang['lotw_options'] = 'Опции';
$lang['lotw_valid'] = 'Действует';
$lang['lotw_expired'] = 'Истёк';
$lang['lotw_expiring'] = 'Истекает';
$lang['lotw_not_synced'] = 'Не синхронизирован';

$lang['lotw_certificate_dxcc'] = 'Сертификат DXCC';
$lang['lotw_certificate_dxcc_help_text'] = 'Организация сертификата DXCC. Для примера, Шотландия';

$lang['lotw_input_a_file'] = 'Загрузить файл';

$lang['lotw_upload_exported_adif_file_from_lotw'] = 'Загрузить экспортированный из LoTW ADIF файл из <a href="https://p1k.arrl.org/lotwuser/qsos?qsoscmd=adif" target="_blank">области скачивания журнала</a> , чтоб отметить QSO подтверждёнными через LoTW.';
$lang['lotw_upload_type_must_be_adi'] = 'Файлы журнала должны быть с расширением .adi';

$lang['lotw_pull_lotw_data_for_me'] = 'Подтянуть мои данные из LoTW';
$lang['lotw_select_callsign'] = 'Select callsign to pull LoTW confirmations for';

$lang['lotw_report_download_overview_helptext'] ='Cloudlog использует логин и пароль для LoTW, сохранённые в вашем профиле, для того чтобы скачивать ваши журналы с LoTW. Журнал, скачанный в Cloudlog будет содержать все подтверждения, начиная с выбранной даты, или начиная с последнего подтверждения в LoTW (загруженного из вашего журнала), до настоящего момента.';

// Buttons
$lang['lotw_btn_lotw_import'] = 'Импорт LoTW';
$lang['lotw_btn_upload_certificate'] = 'Загрузить сертификат';
$lang['lotw_btn_delete'] = 'Удалить';
$lang['lotw_btn_manual_sync'] = 'Ручная синхронизация';
$lang['lotw_btn_upload_file'] = 'Загрузить файл';
$lang['lotw_btn_import_matches'] = 'Импортировать совпадения с LoTW';

// P12 Export Text
$lang['lotw_p12_export_step_one'] = 'Откройте TQSL &amp; перейдите на вкладку  "Сертификаты позывных"';
$lang['lotw_p12_export_step_two'] = 'Кликните правой кнопкой по выбранному позывному';
$lang['lotw_p12_export_step_three'] = 'Кликните "Сохранить Сертификат позывного" и не задавайте пароль';
$lang['lotw_p12_export_step_four'] = 'Загрузите полученный файл ниже.';

$lang['lotw_confirmed'] = 'Это QSO подтверждено на LoTW';

// LoTW Expiry
$lang['lotw_cert_expiring'] = 'Как минимум, один из ваших сертификатов LoTW скоро истечёт!';
$lang['lotw_cert_expired'] = 'Один из ваших сертификатов LoTW истёк!';

// Lotw User
$lang['lotw_user'] = 'Эта станция использует LoTW.';
$lang['lotw_last_upload'] = 'Последняя загрузка';
