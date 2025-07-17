<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// Tiles
$lang['qso_title_qso_map'] = 'Mapa de QSOs';
$lang['qso_title_suggestions'] = 'Sugestões';
$lang['qso_title_previous_contacts'] = 'Contatos Anteriores';
$lang['qso_title_times_worked_before'] = "vezes contatado antes";
$lang['qso_title_image'] = 'Foto de Perfil';
$lang['qso_previous_max_shown'] = "Máx. 5 contatos anteriores são exibidos";

// Quicklog no Painel
$lang['qso_quicklog_enter_callsign'] = 'QUICKLOG - Insira o Indicativo';

// Texto de Ajuda (Entrada)
$lang['qso_transmit_power_helptext'] = 'Informe a potência em Watts. Digite apenas números.';

$lang['qso_sota_ref_helptext'] = 'Exemplo: GM/NS-001.';
$lang['qso_wwff_ref_helptext'] = 'Exemplo: DLFF-0069.';
$lang['qso_pota_ref_helptext'] = 'Exemplo: PA-0150.';

$lang['qso_sig_helptext'] = 'Exemplo: GMA';
$lang['qso_sig_info_helptext'] = 'Exemplo: DA/NW-357';

$lang['qso_dok_helptext'] = 'Exemplo: Q03';

$lang['qso_notes_helptext'] = 'As notas são utilizadas apenas no Cloudlog e não são exportadas para outros serviços.';
$lang['qsl_notes_helptext'] = 'Essas notas são exportadas para serviços como eqsl.cc.';

$lang['qso_eqsl_qslmsg_helptext'] = "Mensagem padrão para o eQSL desta estação.";

// Mensagens de erro
$lang['qso_error_timeoff_less_timeon'] = "TimeOff é menor que TimeOn";

// Botões na Tela de QSO
$lang['qso_btn_reset_qso'] = 'Limpar';
$lang['qso_btn_save_qso'] = 'Salvar QSO';
$lang['qso_btn_edit_qso'] = 'Editar QSO';
$lang['qso_delete_warning'] = "Atenção! Deseja realmente apagar o QSO com ";

// Detalhes do QSO
$lang['qso_details'] = 'Detalhes do QSO';

$lang['fav_add'] = 'Adicionar Banda/Modo aos Favoritos';
$lang['qso_operator_callsign'] = 'Indicativo do Operador';

$lang['qso_simplefle_info'] = "O que é isso?";
$lang['qso_simplefle_info_ln1'] = "Entrada Rápida Simples de Log (FLE)";
$lang['qso_simplefle_info_ln2'] = "'Fast Log Entry', ou simplesmente 'FLE', é um sistema para registrar QSOs de forma rápida e eficiente, exigindo o mínimo de digitação.";
$lang['qso_simplefle_info_ln3'] = "O FLE foi originalmente criado por DF3CB. Ele oferece um programa para Windows em seu site. A versão Simple FLE foi escrita por OK2CQR com base no FLE e oferece uma interface web.";
$lang['qso_simplefle_info_ln4'] = "Um uso comum é para importar logs em papel de operações portáteis. Agora o SimpleFLE também está disponível no Cloudlog. Mais detalhes sobre a sintaxe você encontra <a href='https://df3cb.com/fle/documentation/' target='_blank'>aqui</a>.";

$lang['qso_simplefle_qso_data'] = "Dados do QSO";
$lang['qso_simplefle_qso_date_hint'] = "Se você não escolher uma data, será usada a data de hoje.";
$lang['qso_simplefle_qso_list'] = "Lista de QSOs";
$lang['qso_simplefle_qso_list_total'] = "Total";
$lang['qso_simplefle_qso_date'] = "Data do QSO";
$lang['qso_simplefle_operator'] = "Operador";
$lang['qso_simplefle_operator_hint'] = "Ex.: OK2CQR";
$lang['qso_simplefle_station_call_location'] = "Indicativo/Local da Estação";
$lang['qso_simplefle_station_call_location_hint'] = "Se operou de um novo local, crie antes uma nova <a href=". site_url('station') . ">Localização da Estação</a>";
$lang['qso_simplefle_utc_time'] = "Hora UTC Atual";
$lang['qso_simplefle_enter_the_data'] = "Digite os Dados";
$lang['qso_simplefle_syntax_help_close_w_sample'] = "Fechar e Carregar Exemplo";
$lang['qso_simplefle_reload'] = "Recarregar Lista";
$lang['qso_simplefle_save'] = "Salvar no Cloudlog";
$lang['qso_simplefle_clear'] = "Limpar Sessão";
$lang['qso_simplefle_refs_hint'] = "Os Refs podem ser <u>S</u>OTA, <u>I</u>OTA, <u>P</u>OTA ou <u>W</u>WFF";

// Erros e Avisos
$lang['qso_simplefle_error_band'] = "Falta informar a Banda!";
$lang['qso_simplefle_error_mode'] = "Falta informar o Modo!";
$lang['qso_simplefle_error_time'] = "Hora não definida!";
$lang['qso_simplefle_error_stationcall'] = "Indicativo da estação não selecionado";
$lang['qso_simplefle_error_operator'] = "Campo 'Operador' está vazio";
$lang['qso_simplefle_warning_reset'] = "Atenção! Deseja realmente limpar tudo?";
$lang['qso_simplefle_warning_missing_band_mode'] = "Atenção! Não é possível salvar a lista pois faltam Banda e/ou Modo em algum QSO!";
$lang['qso_simplefle_warning_missing_time'] = "Atenção! Não é possível salvar a lista pois há QSOs sem horário definido!";
$lang['qso_simplefle_warning_example_data'] = "Atenção! O campo de dados contém dados de exemplo. Limpe a sessão antes!";
$lang['qso_simplefle_confirm_save_to_log'] = "Deseja mesmo adicionar esses QSOs ao log e limpar a sessão?";
$lang['qso_simplefle_success_save_to_log_header'] = "QSOs Salvos!";
$lang['qso_simplefle_success_save_to_log'] = "Os QSOs foram registrados com sucesso!";
$lang['qso_simplefle_error_date'] = "Data inválida";

// Ajuda de Sintaxe
$lang['qso_simplefle_syntax_help_button'] = "Ajuda de Sintaxe";
$lang['qso_simplefle_syntax_help_title'] = "Sintaxe do FLE";
$lang['qso_simplefle_syntax_help_ln1'] = "Antes de registrar, atente-se às regras básicas:";
$lang['qso_simplefle_syntax_help_ln2'] = "- Cada QSO deve estar em uma nova linha.";
$lang['qso_simplefle_syntax_help_ln3'] = "- Em cada linha, escreva apenas os dados que mudaram em relação ao QSO anterior.";
$lang['qso_simplefle_syntax_help_ln4'] = "Preencha o formulário à esquerda com data, indicativo da estação e do operador. Depois, insira banda (ou QRG, como '7.145'), modo e horário. O primeiro dado do QSO é o indicativo do contato.";
$lang['qso_simplefle_syntax_help_ln5'] = "Exemplo: QSO às 21:34 UTC com 2M0SQL em 20m SSB.";
$lang['qso_simplefle_syntax_help_ln6'] = "Se não informar RST, o padrão é 59 (ou 599 para modos digitais). Para o próximo QSO, com RST diferente e 2 minutos depois, apenas o horário muda.";
$lang['qso_simplefle_syntax_help_ln7'] = "Primeiro QSO foi às 21:34, o segundo às 21:36. Basta escrever '6' como o novo horário. Banda e modo não mudaram.";
$lang['qso_simplefle_syntax_help_ln8'] = "Para o QSO às 21:40 em 14 de maio de 2021, mudamos para 40m mas mantivemos SSB. Se outro QSO ocorreu no mesmo horário dois dias depois, informe a nova data no formato AAAA-MM-DD.";
$lang['qso_simplefle_syntax_help_ln9'] = "Mais detalhes na documentação do DF3CB <a href='https://df3cb.com/fle/documentation/' target='_blank'>aqui</a>.";