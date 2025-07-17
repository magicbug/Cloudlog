<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$lang['lotw_short'] = 'LoTW';
$lang['lotw_title'] = 'Logbook of the World';
$lang['lotw_title_available_cert'] = 'Certificados Disponíveis';
$lang['lotw_title_information'] = 'Informações';
$lang['lotw_title_upload_p12_cert'] = 'Enviar Certificado .p12 do Logbook of the World';
$lang['lotw_title_export_p12_file_instruction'] = 'Instruções para Exportar Arquivo .p12';
$lang['lotw_title_adif_import'] = 'Importação ADIF';
$lang['lotw_title_adif_import_options'] = 'Opções de Importação';

$lang['lotw_beta_warning'] = 'Atenção: a sincronização com o LoTW está em fase BETA. Veja a wiki para ajuda.';
$lang['lotw_no_certs_uploaded'] = 'Você precisa enviar certificados .p12 do LoTW para usar esta área.';

$lang['lotw_date_created'] = 'Data de Criação';
$lang['lotw_date_expires'] = 'Data de Expiração';
$lang['lotw_qso_start_date'] = 'Data Inicial do QSO';
$lang['lotw_qso_end_date'] = 'Data Final do QSO';
$lang['lotw_status'] = 'Status / Último Envio';
$lang['lotw_options'] = 'Opções';
$lang['lotw_valid'] = 'Válido';
$lang['lotw_expired'] = 'Expirado';
$lang['lotw_expiring'] = 'A Expirar';
$lang['lotw_not_synced'] = 'Não Sincronizado';

$lang['lotw_certificate_dxcc'] = 'DXCC do Certificado';
$lang['lotw_certificate_dxcc_help_text'] = 'Entidade DXCC do certificado. Exemplo: Escócia';

$lang['lotw_input_a_file'] = 'Enviar um Arquivo';

$lang['lotw_upload_exported_adif_file_from_lotw'] = 'Envie o arquivo ADIF exportado do LoTW da área <a href="https://p1k.arrl.org/lotwuser/qsos?qsoscmd=adif" target="_blank">Download Report</a> para marcar QSOs como confirmados via LoTW.';
$lang['lotw_upload_type_must_be_adi'] = 'Arquivos de log devem ser do tipo .adi';

$lang['lotw_pull_lotw_data_for_me'] = 'Buscar dados do LoTW para mim';
$lang['lotw_select_callsign'] = 'Selecione o indicativo para buscar confirmações no LoTW';

$lang['lotw_report_download_overview_helptext'] = 'O Cloudlog utilizará o nome de usuário e senha do LoTW armazenados no seu perfil para baixar um relatório. Esse relatório conterá todas as confirmações desde a data escolhida (ou desde a última confirmação no LoTW registrada no seu log) até o momento atual.';

// Botões
$lang['lotw_btn_lotw_import'] = 'Importar LoTW';
$lang['lotw_btn_upload_certificate'] = 'Enviar Certificado';
$lang['lotw_btn_delete'] = 'Excluir';
$lang['lotw_btn_manual_sync'] = 'Sincronização Manual';
$lang['lotw_btn_upload_file'] = 'Enviar Arquivo';
$lang['lotw_btn_import_matches'] = 'Importar QSOs Confirmados no LoTW';

// Instruções para exportar P12
$lang['lotw_p12_export_step_one'] = 'Abra o TQSL e vá até a aba "Certificados de Indicativo"';
$lang['lotw_p12_export_step_two'] = 'Clique com o botão direito sobre o indicativo desejado';
$lang['lotw_p12_export_step_three'] = 'Clique em "Salvar Arquivo de Certificado de Indicativo" e não adicione uma senha';
$lang['lotw_p12_export_step_four'] = 'Envie o arquivo abaixo.';

$lang['lotw_confirmed'] = 'Este QSO está confirmado no LoTW';

// Avisos de validade
$lang['lotw_cert_expiring'] = 'Pelo menos um dos seus certificados do LoTW está prestes a expirar!';
$lang['lotw_cert_expired'] = 'Pelo menos um dos seus certificados do LoTW está expirado!';

// Usuário do LoTW
$lang['lotw_user'] = 'Esta estação usa o LoTW.';
$lang['lotw_last_upload'] = 'Último envio';
