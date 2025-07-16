<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$lang['options_cloudlog_options'] = 'Opções do Cloudlog';
$lang['options_message1'] = 'As opções do Cloudlog são configurações globais usadas por todos os usuários da instalação e são substituídas se houver uma configuração no nível do usuário.';

$lang['options_appearance'] = 'Aparência';
$lang['options_theme'] = 'Tema';
$lang['options_global_theme_choice_this_is_used_when_users_arent_logged_in'] = 'Escolha de tema global, usada quando os usuários não estão logados.';
$lang['options_public_search_bar'] = 'Barra de busca pública';
$lang['options_this_allows_non_logged_in_users_to_access_the_search_functions'] = 'Permite que usuários não logados acessem as funções de busca.';
$lang['options_dashboard_notification_banner'] = 'Banner de notificação no painel';
$lang['options_this_allows_to_disable_the_global_notification_banner_on_the_dashboard'] = 'Permite desativar o banner de notificação global no painel.';
$lang['options_dashboard_map'] = 'Mapa do painel';
$lang['options_this_allows_the_map_on_the_dashboard_to_be_disabled_or_placed_on_the_right'] = 'Permite que o mapa no painel seja desativado ou posicionado à direita.';
$lang['options_logbook_map'] = 'Mapa do log';
$lang['options_this_allows_to_disable_the_map_in_the_logbook'] = 'Permite desativar o mapa no log.';
$lang['options_theme_changed_to'] = 'Tema alterado para ';
$lang['options_global_search_changed_to'] = 'Busca global alterada para ';
$lang['options_dashboard_banner_changed_to'] = 'Banner do painel alterado para ';
$lang['options_dashboard_map_changed_to'] = 'Mapa do painel alterado para ';
$lang['options_logbook_map_changed_to'] = 'Mapa do log alterado para ';

$lang['options_radios'] = 'Rádios';
$lang['options_radio_settings'] = 'Configurações do Rádio';
$lang['options_radio_timeout_warning'] = 'Aviso de tempo limite do rádio';
$lang['options_the_radio_timeout_warning_is_used_on_the_qso_entry_panel_to_alert_you_to_radio_interface_disconnects'] = 'O aviso de tempo limite do rádio é usado no painel de entrada de QSO para alertar sobre desconexões da interface do rádio.';
$lang['options_this_number_is_in_seconds'] = 'Este número está em segundos.';
$lang['options_radio_timeout_warning_changed_to'] = 'Aviso de tempo limite do rádio alterado para ';

$lang['options_email'] = 'Email';
$lang['options_outgoing_protocol'] = 'Protocolo de envio';
$lang['options_smtp_encryption'] = 'Criptografia SMTP';
$lang['options_email_address'] = 'Endereço de email';
$lang['options_email_sender_name'] = 'Nome do remetente';
$lang['options_smtp_host'] = 'Servidor SMTP';
$lang['options_smtp_port'] = 'Porta SMTP';
$lang['options_smtp_username'] = 'Usuário SMTP';
$lang['options_smtp_password'] = 'Senha SMTP';
$lang['options_mail_settings_saved'] = "As configurações foram salvas com sucesso.";
$lang['options_mail_settings_failed'] = "Algo deu errado ao salvar as configurações. Tente novamente.";
$lang['options_outgoing_protocol_hint'] = "O protocolo usado para envio de emails.";
$lang['options_smtp_encryption_hint'] = "Escolha se os emails devem ser enviados com TLS ou SSL.";
$lang['options_email_address_hint'] = "O endereço de email usado para enviar os emails, por exemplo, 'cloudlog@exemplo.com'";
$lang['options_email_sender_name_hint'] = "Nome exibido como remetente, por exemplo, 'Cloudlog'";
$lang['options_smtp_host_hint'] = "Nome do servidor de email, ex: 'mail.exemplo.com' (sem 'ssl://' ou 'tls://')";
$lang['options_smtp_port_hint'] = "Porta SMTP, ex: se usar TLS -> '587', se usar SSL -> '465'";
$lang['options_smtp_username_hint'] = "Nome de usuário para autenticação no servidor de email. Geralmente é o mesmo do endereço de email.";
$lang['options_smtp_password_hint'] = "Senha para autenticação no servidor de email.";
$lang['options_send_testmail'] = "Enviar email de teste";
$lang['options_send_testmail_hint'] = "O email será enviado para o endereço definido nas configurações da sua conta.";
$lang['options_send_testmail_failed'] = "Falha ao enviar email de teste. Algo deu errado.";
$lang['options_send_testmail_success'] = "Email de teste enviado. As configurações parecem corretas.";

$lang['options_oqrs'] = 'Opções OQRS';
$lang['options_global_text'] = 'Texto global';
$lang['options_this_text_is_an_optional_text_that_can_be_displayed_on_top_of_the_oqrs_page'] = 'Texto opcional exibido no topo da página OQRS.';
$lang['options_grouped_search'] = 'Busca agrupada';
$lang['options_when_this_is_on_all_station_locations_with_oqrs_active_will_be_searched_at_once'] = 'Quando ativado, todas as localizações com OQRS ativo serão buscadas ao mesmo tempo.';
$lang['options_grouped_search_show_station_name'] = "Exibir nome da estação nos resultados da busca agrupada";
$lang['options_grouped_search_show_station_name_hint'] = "Se busca agrupada estiver ativa, é possível exibir o nome da estação nos resultados.";
$lang['options_oqrs_options_have_been_saved'] = 'As opções do OQRS foram salvas.';

$lang['options_dxcluster'] = 'DXCluster';
$lang['options_dxcluster_provider'] = 'Provedor do DXClusterCache';
$lang['options_dxcluster_longtext'] = 'Provedor do DXCluster-Cache. Você pode configurar seu próprio cache com <a href="https://github.com/int2001/DXClusterAPI">DXClusterAPI</a> ou usar um público';
$lang['options_dxcluster_hint'] = 'URL do DXCluster-Cache. Ex: https://dxc.jo30.de/dxcache';
$lang['options_dxcluster_settings'] = 'DXCluster';
$lang['options_dxcache_url_changed_to'] = 'URL do DXCluster alterada para ';
$lang['options_dxcluster_maxage'] = 'Idade máxima dos spots considerados';
$lang['options_dxcluster_maxage_hint'] = 'Idade (em minutos) dos spots que serão considerados no plano de bandas/lookup';
$lang['options_dxcluster_decont'] = 'Mostrar spots originados do seguinte continente';
$lang['options_dxcluster_maxage_changed_to']='Idade máxima dos spots alterada para ';
$lang['options_dxcluster_decont_changed_to']='Continente alterado para ';
$lang['options_dxcluster_decont_hint']='Somente os spots de spotters deste continente serão exibidos';

$lang['options_version_dialog'] = "Informações de Versão";
$lang['options_version_dialog_close'] = "Fechar";
$lang['options_version_dialog_dismiss'] = "Não mostrar novamente";
$lang['options_version_dialog_settings'] = "Configurações da Versão";
$lang['options_version_dialog_header'] = "Cabeçalho da versão";
$lang['options_version_dialog_header_hint'] = "Você pode alterar o cabeçalho da janela de versão.";
$lang['options_version_dialog_header_changed_to'] = "Cabeçalho da versão alterado para";
$lang['options_version_dialog_mode'] = "Modo da versão";
$lang['options_version_dialog_mode_release_notes'] = "Somente notas de versão";
$lang['options_version_dialog_mode_custom_text'] = "Somente texto personalizado";
$lang['options_version_dialog_mode_both'] = "Notas de versão e texto personalizado";
$lang['options_version_dialog_mode_disabled'] = "Desativado";
$lang['options_version_dialog_mode_hint'] = "As informações de versão são exibidas a todos os usuários, com opção de ocultar após leitura.";
$lang['options_version_dialog_custom_text'] = "Texto personalizado da versão";
$lang['options_version_dialog_custom_text_hint'] = "Texto personalizado exibido na janela de versão.";
$lang['options_version_dialog_mode_changed_to'] = "Modo da versão alterado para";
$lang['options_version_dialog_custom_text_saved'] = "Texto personalizado salvo!";
$lang['options_version_dialog_success_show_all'] = "Informações de versão serão exibidas novamente a todos os usuários";
$lang['options_version_dialog_success_hide_all'] = "Informações de versão não serão exibidas a nenhum usuário";
$lang['options_version_dialog_show_hide'] = "Exibir/Ocultar versão para todos os usuários";
$lang['options_version_dialog_show_all'] = "Exibir para todos os usuários";
$lang['options_version_dialog_hide_all'] = "Ocultar para todos os usuários";
$lang['options_version_dialog_show_all_hint'] = "A janela será exibida automaticamente a todos na próxima recarga.";
$lang['options_version_dialog_hide_all_hint'] = "Desativa a exibição automática da janela para todos os usuários.";

$lang['options_save'] = 'Salvar';

// Bandas

$lang['options_bands'] = "Bandas";
$lang['options_bands_text_ln1'] = "Use a lista de bandas para controlar quais aparecem ao criar um novo QSO.";
$lang['options_bands_text_ln2'] = "Bandas ativas são exibidas no menu 'Banda', enquanto inativas ficam ocultas.";
$lang['options_bands_create'] = "Criar uma banda";
$lang['options_bands_edit'] = "Editar banda";
$lang['options_bands_activate_all'] = "Ativar todas";
$lang['options_bands_activateall_warning'] = "Atenção! Deseja mesmo ativar todas as bandas?";
$lang['options_bands_deactivate_all'] = "Desativar todas";
$lang['options_bands_deactivateall_warning'] = "Atenção! Deseja mesmo desativar todas as bandas?";
$lang['options_bands_ssb_qrg'] = "Frequência SSB";
$lang['options_bands_ssb_qrg_hint'] = "Frequência SSB da banda (em Hz)";
$lang['options_bands_data_qrg'] = "Frequência DATA";
$lang['options_bands_data_qrg_hint'] = "Frequência para modos digitais da banda (em Hz)";
$lang['options_bands_cw_qrg'] = "Frequência CW";
$lang['options_bands_cw_qrg_hint'] = "Frequência CW da banda (em Hz)";
$lang['options_bands_name_band'] = "Nome da Banda (ex: 20m)";
$lang['options_bands_name_bandgroup'] = "Nome do grupo da banda (ex: hf, vhf, uhf, shf)";
$lang['options_bands_delete_warning'] = "Atenção! Deseja mesmo excluir a banda: ";
