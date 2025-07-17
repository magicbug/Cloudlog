<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*
___________________________________________________________________________________________
Topbar
___________________________________________________________________________________________
*/

$lang['adif_import'] = "Importar ADIF";
$lang['adif_export'] = "Exportar ADIF";
$lang['darc_dcl'] = "DARC DCL";

/*
___________________________________________________________________________________________
Importação ADIF
___________________________________________________________________________________________
*/

$lang['adif_alert_log_files_type'] = "Os arquivos de log devem ter o tipo *.adi";

$lang['adif_select_stationlocation'] = "Selecionar Local da Estação";

$lang['adif_file_label'] = "Arquivo ADIF";

$lang['adif_hint_no_info_in_file'] = "Selecione se o ADIF importado não contém essas informações.";

$lang['adif_import_dup'] = "Importar QSOs duplicados";
$lang['adif_mark_imported_lotw'] = "Marcar QSOs importados como enviados para o LoTW";
$lang['adif_mark_imported_hrdlog'] = "Marcar QSOs importados como enviados para o HRDLog.net";
$lang['adif_mark_imported_qrz'] = "Marcar QSOs importados como enviados para o QRZ Logbook";
$lang['adif_mark_imported_clublog'] = "Marcar QSOs importados como enviados para o Clublog";

$lang['adif_dxcc_from_adif'] = "Usar informações DXCC do ADIF";
$lang['adif_dxcc_from_adif_hint'] = "Se não selecionado, o Cloudlog tentará determinar o DXCC automaticamente.";

$lang['adif_always_use_login_call_as_op'] = "Sempre usar o indicativo de login como operador na importação";

$lang['adif_ignore_station_call'] = "Ignorar indicativo da estação na importação";
$lang['adif_ignore_station_call_hint'] = "Se selecionado, o Cloudlog tentará importar <b>todos</b> os QSOs do ADIF, independentemente de corresponderem ao local da estação escolhida.";

$lang['adif_upload'] = "Enviar";

/*
___________________________________________________________________________________________
Exportação ADIF
___________________________________________________________________________________________
*/

$lang['adif_export_take_it_anywhere'] = "Leve seu arquivo de logbook para qualquer lugar!";
$lang['adif_export_take_it_anywhere_hint'] = "Exportar ADIFs permite importar contatos em aplicações de terceiros como LoTW, prêmios ou para backup.";

$lang['adif_mark_exported_lotw'] = "Marcar QSOs exportados como enviados para o LoTW";
$lang['adif_mark_exported_no_lotw'] = "Exportar QSOs não enviados para o LoTW";

$lang['adif_export_qso'] = "Exportar QSOs";

$lang['adif_export_sat_only_qso'] = "Exportar apenas QSOs via satélite";
$lang['adif_export_sat_only_qso_all'] = "Exportar todos os QSOs via satélite";
$lang['adif_export_sat_only_qso_lotw'] = "Exportar todos os QSOs via satélite confirmados no LoTW";

/*
___________________________________________________________________________________________
Logbook of the World
___________________________________________________________________________________________
*/

$lang['adif_lotw_export_if_selected'] = "Se nenhum intervalo de datas for selecionado, todos os QSOs serão marcados!";
$lang['adif_mark_qso_as_exported_to_lotw'] = "Marcar QSOs como exportados para o LoTW";

$lang['adif_qso_marked'] = "QSOs marcados";
$lang['adif_yay_its_done'] = "Pronto, está feito!";
$lang['adif_qso_lotw_marked_confirm'] = "Os QSOs foram marcados como exportados para o LoTW.";

/*
___________________________________________________________________________________________
DARC DCL
___________________________________________________________________________________________
*/

$lang['adif_dcl_text_pre'] = "Vá para";
$lang['adif_dcl_text_post'] = "e exporte seu log com DOKs confirmados. Para agilizar o processo, você pode selecionar apenas QSOs com DL (ex: insira \"DL\" na lista de prefixos). O arquivo ADIF baixado pode ser enviado aqui para atualizar os QSOs com informações de DOK.";

$lang['only_confirmed_qsos'] = "Importar DOK apenas de QSOs confirmados no DCL.";
$lang['only_confirmed_qsos_hint'] = "Desmarque se também quiser atualizar DOK com dados de QSOs não confirmados no DCL.";

$lang['overwrite_by_dcl'] = "Sobrescrever DOK existente no log com DOK do DCL (se diferente).";
$lang['overwrite_by_dcl_hint'] = "Se marcado, o Cloudlog irá sobrescrever forçadamente o DOK existente com o do log DCL.";

$lang['ignore_ambiguous'] = "Ignorar QSOs que não puderem ser correspondidos.";
$lang['ignore_ambiguous_hint'] = "Se desmarcado, informações dos QSOs não encontrados no Cloudlog serão exibidas.";

/*
___________________________________________________________________________________________
Importação com Sucesso
___________________________________________________________________________________________
*/

$lang['adif_imported'] = "ADIF Importado";
$lang['adif_yay_its_imported'] = "Pronto, foi importado!";
$lang['adif_import_confirm'] = "O arquivo ADIF foi importado com sucesso.";

$lang['adif_import_dupes_inserted'] = " <b>Duplicados foram inseridos!</b>";
$lang['adif_import_dupes_skipped'] = " Duplicados foram ignorados.";

$lang['adif_import_errors'] = "Erros de ADIF";
$lang['adif_import_errors_hint'] = "Você tem erros no ADIF. Os QSOs foram adicionados, mas alguns campos não foram preenchidos.";

/*
___________________________________________________________________________________________
Sucesso DCL
___________________________________________________________________________________________
*/

$lang['dcl_results'] = "Resultados da Atualização DOK pelo DCL";
$lang['dcl_info_updated'] = "Informações DCL dos DOKs atualizadas.";
$lang['dcl_qsos_updated'] = "QSOs atualizados";
$lang['dcl_qsos_ignored'] = "QSOs ignorados";
$lang['dcl_qsos_unmatched'] = "QSOs não encontrados";
$lang['dcl_no_qsos_updated'] = "Nenhum QSO foi encontrado para atualização.";
$lang['dcl_dok_errors'] = "Erros de DOK";
$lang['dcl_dok_errors_details'] = "Há dados diferentes de DOK no seu log em comparação com o DCL";
$lang['dcl_qsl_status'] = "Status de QSL no DCL";
$lang['dcl_qsl_status_c'] = "confirmado por LoTW/Clublog/eQSL/Concurso";
$lang['dcl_qsl_status_mno'] = "confirmado por gerente de prêmio";
$lang['dcl_qsl_status_i'] = "confirmado por comparação cruzada com dados do DCL";
$lang['dcl_qsl_status_w'] = "confirmação pendente";
$lang['dcl_qsl_status_x'] = "não confirmado";
$lang['dcl_qsl_status_unknown'] = "desconhecido";
$lang['dcl_no_match'] = "QSO não pôde ser correspondido";
