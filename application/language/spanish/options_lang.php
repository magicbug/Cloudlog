<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$lang['options_cloudlog_options'] = 'Opciones de Cloudlog';
$lang['options_message1'] = 'las Opciones de Cloudlog son configuraciones globales, configuradas para todos los usuarios de la instalación, que se sobreescribirán si alguna configuración se activa a nivel de usuario.';

$lang['options_appearance'] = 'Apariencia';
$lang['options_theme'] = 'Tema';
$lang['options_global_theme_choice_this_is_used_when_users_arent_logged_in'] = 'Elección global de Tema, se utiliza cuando ningún usuario ha iniciado sesión.';
$lang['options_public_search_bar'] = 'Barra de Búsqueda Pública';
$lang['options_this_allows_non_logged_in_users_to_access_the_search_functions'] = 'Esto le permite acceder a las funciones de búsqueda a usuarios que no han iniciado sesión.';
$lang['options_dashboard_notification_banner'] = 'Marquesina de Notificación en la Vista General';
$lang['options_this_allows_to_disable_the_global_notification_banner_on_the_dashboard'] = 'Esto permite deshabilitar la marquesina de notificaciones globales que se muestra en la Vista General.';
$lang['options_dashboard_map'] = 'Mapa en la Vista General';
$lang['options_this_allows_the_map_on_the_dashboard_to_be_disabled_or_placed_on_the_right'] = 'Esto permite que el mapa de la vsita general no aparezca o se muestre a la derecha.';
$lang['options_logbook_map'] = 'Mapa en Libro de Guardia';
$lang['options_this_allows_to_disable_the_map_in_the_logbook'] = 'Esto pemrite deshabilitar el mapa en el libro de guardia.';
$lang['options_theme_changed_to'] = 'Tema cambiado a ';
$lang['options_global_search_changed_to'] = 'Búsqueda Global cambiado a ';
$lang['options_dashboard_banner_changed_to'] = 'Marquesina en la Vista General cambiada a ';
$lang['options_dashboard_map_changed_to'] = 'Mapa en la Vista General cambiado a ';
$lang['options_logbook_map_changed_to'] = 'Mapa en Libro de Guardia cambiado a ';

$lang['options_radios'] = 'Radios';
$lang['options_radio_settings'] = 'Configuración de Radio';
$lang['options_radio_timeout_warning'] = 'Advertencia de Tiempo de Espera de Radio';
$lang['options_the_radio_timeout_warning_is_used_on_the_qso_entry_panel_to_alert_you_to_radio_interface_disconnects'] = 'La Advertencia de Tiempo de Espera de Radio se usa en el panel de entrada de QSO para alertarlo de desconexiones de la interfaz de radio.';
$lang['options_this_number_is_in_seconds'] = 'Este número es en segundos.';
$lang['options_radio_timeout_warning_changed_to'] = 'Advertencia de Tiempo de Espera de Radio cambiada a ';

$lang['options_email'] = 'Email';
$lang['options_outgoing_protocol'] = 'Protocolo de Salida';
$lang['options_smtp_encryption'] = 'Encriptación SMTP';
$lang['options_email_address'] = 'Dirección de Email';
$lang['options_email_sender_name'] = 'Nombre de remitente de Email';
$lang['options_smtp_host'] = 'Servidor SMTP';
$lang['options_smtp_port'] = 'Puerto SMTP';
$lang['options_smtp_username'] = 'Nombre de usuario SMTP';
$lang['options_smtp_password'] = 'Contraseña SMTP';
$lang['options_crlf'] = 'CRLF';
$lang['options_newline'] = 'Nuevas Líneas';
$lang['options_outgoing_email_protocol_changed_to'] = 'Protocolo de Salida cambiado a ';
$lang['options_smtp_encryption_changed_to'] = 'Encriptación SMTP cambiada a ';
$lang['options_email_address_changed_to'] = 'Dirección de Email cambiada a ';
$lang['options_email_sender_name_changed_to'] = 'Nombre de remitente de Email cambiado a ';
$lang['options_smtp_host_changed_to'] = 'Servidor SMTP cambiado a ';
$lang['options_smtp_port_changed_to'] = 'Puerto SMTP cambiado a ';
$lang['options_smtp_username_changed_to'] = 'Nombre de usuario SMTP cambiado a ';
$lang['options_smtp_password_changed_to'] = 'Contraseña SMTP cambiada a ';
$lang['options_email_crlf_changed_to'] = 'CRLF en el Email cambiado a ';
$lang['options_email_newline_changed_to'] = 'Nuevas líneas en el Email cambiado a ';

$lang['options_oqrs'] = 'Opciones OQRS';
$lang['options_global_text'] = 'Texto global';
$lang['options_this_text_is_an_optional_text_that_can_be_displayed_on_top_of_the_oqrs_page'] = 'Este texto es un texto opcional que se mostrará en la parte superior de la página de OQRS.';
$lang['options_grouped_search'] = 'Búsqueda agrupada';
$lang['options_when_this_is_on_all_station_locations_with_oqrs_active_will_be_searched_at_once'] = 'Cuando está activo, se buscarán en simultáneo todas las localizaciones de estación con OQRS activo';
$lang['options_oqrs_options_have_been_saved'] = 'Las opciones de OQRS se han guardado.';

$lang['options_dxcluster'] = 'DXCluster';
$lang['options_dxcluster_provider'] = 'Proveedor de DXClusterCache';
$lang['options_dxcluster_longtext'] = 'El proveedor de DXCluster-Cache. Puede configurar su propio caché con <a href="https://github.com/int2001/DXClusterAPI">DXClusterAPI</a> o usar un caché público.';
$lang['options_dxcluster_hint'] = 'URL del DXCluster-Cache. ej. https://dxc.jo30.de/dxcache';
$lang['options_dxcluster_settings'] = 'DXCluster';
$lang['options_dxcache_url_changed_to'] = 'URL del DXCluster-Cache cambiada a ';
$lang['options_dxcluster_maxage'] = 'Máxima edad de puntos a tomarse en cuenta';
$lang['options_dxcluster_maxage_hint'] = 'La edad en minutos de los puntos que se tomarán en cuenta en el plan de bandas/búsqueda';
$lang['options_dxcluster_decont'] = 'Mostrar puntos que se observen en el siguiente continente';
$lang['options_dxcluster_maxage_changed_to']='Máxima edad de puntos cambiada a ';
$lang['options_dxcluster_decont_changed_to']='de continente cambiado a ';
$lang['options_dxcluster_decont_hint']='Solo se muestran puntos por observadores de este continente';

$lang['options_save'] = 'Guardar';

// Bands

$lang['options_bands'] = "Bandas";
$lang['options_bands_text_ln1'] = "Usando la lista de bandas puede controlar que bandas se muestran cuando se crea un nuevo QSO.";
$lang['options_bands_text_ln2'] = "Las bandas activas se mostrarán en la lista desplegable de 'Bandas' en QSO, mientras que las bandas inactivas se ocultarán y no pueden ser seleccionadas.";
$lang['options_bands_create'] = "Crear una Banda";
$lang['options_bands_edit'] = "Editar Banda";
$lang['options_bands_activate_all'] = "Activar Todas";
$lang['options_bands_activateall_warning'] = "¡Advertencia! ¿Está seguro que desea activar todas las bandas?";
$lang['options_bands_deactivate_all'] = "Desactivar Todas";
$lang['options_bands_deactivateall_warning'] = "¡Advertencia! ¿Está seguro que desea desactivar todas las bandas?";
$lang['options_bands_ssb_qrg'] = "SSB QRG";
$lang['options_bands_ssb_qrg_hint'] = "Frecuencia para el QRG de SSB en la banda (debe ser en Hz)";
$lang['options_bands_data_qrg'] = "DATA QRG";
$lang['options_bands_data_qrg_hint'] = "Frecuencia para el QRG de DATA en la banda (debe ser en Hz)";
$lang['options_bands_cw_qrg'] = "CW QRG";
$lang['options_bands_cw_qrg_hint'] = "Frecuencia para el QRG de CW en la banda (debe ser en Hz)";

$lang['options_bands_name_band'] = "Nombre de la Banda (ej. 20m)";
$lang['options_bands_name_bandgroup'] = "Nombre del grupo de bandas (ej. hf, vhf, uhf, shf)";
$lang['options_bands_delete_warning'] = "¡Advertencia! ¿Está seguro que desea eliminar la banda a continuación: ";

