<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$lang['options_cloudlog_options'] = 'Opciones de Cloudlog';
$lang['options_message1'] = 'Las Opciones de Cloudlog son configuraciones globales, configuradas para todos los usuarios de la instalación, que se sobreescribirán si alguna configuración se activa a nivel de usuario.';

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
$lang['options_mail_settings_saved'] = "Las configuraciones fueron guardadas exitosamente.";
$lang['options_mail_settings_failed'] = "Algo salió mal guardando las configuraciones. Intente de nuevo.";
$lang['options_outgoing_protocol_hint'] = "El protocolo que será usado para enviar los correos electrónicos.";
$lang['options_smtp_encryption_hint'] = "Escoja si los correos electrónicos serán enviados con TLS o SSL.";
$lang['options_email_address_hint'] = "La dirección desde la cual se enviarán los correos electrónicos, ej. 'cloudlog@example.com'";
$lang['options_email_sender_name_hint'] = "El nombre de quien envía los correos, ej. 'Cloudlog'";
$lang['options_smtp_host_hint'] = "El nombre de dominio del servidor de correo, ej. 'mail.example.com' (sin 'ssl://' o 'tls://')";
$lang['options_smtp_port_hint'] = "El puerto SMTP del servidor de correo, ej. si está usando TLS -> '587', si está usando SSL -> '465'";
$lang['options_smtp_username_hint'] = "El nombre de usuario para iniciar sesión en el servidor de correo, usualmente esta es la dirección de correo electrónico a usar.";
$lang['options_smtp_password_hint'] = "La contraseña para iniciar sesión en el servidor de correo.";
$lang['options_send_testmail'] = "Enviar correo de prueba";
$lang['options_send_testmail_hint'] = "El correo será enviado a la dirección definida en su configuración de cuenta.";
$lang['options_send_testmail_failed'] = "El correo de prueba ha fallado. Algo salió mal.";
$lang['options_send_testmail_success'] = "El correo de prueba fue enviado. La configuración de correo electrónico parece correcta.";

$lang['options_oqrs'] = 'Opciones OQRS';
$lang['options_global_text'] = 'Texto global';
$lang['options_this_text_is_an_optional_text_that_can_be_displayed_on_top_of_the_oqrs_page'] = 'Este texto es un texto opcional que se mostrará en la parte superior de la página de OQRS.';
$lang['options_grouped_search'] = 'Búsqueda agrupada';
$lang['options_when_this_is_on_all_station_locations_with_oqrs_active_will_be_searched_at_once'] = 'Cuando está activo, se buscarán en simultáneo todas las localizaciones de estación con OQRS activo';
$lang['options_grouped_search_show_station_name'] = "Mostrar localización de la estación en los resultados de búsqueda agrupados";
$lang['options_grouped_search_show_station_name_hint'] = "Si la búsqueda agrupada esta ACTIVA, puede decidir si el nombre de la localización de la estación se mostrará en la tabla de resultados.";
$lang['options_oqrs_options_have_been_saved'] = 'Las opciones de OQRS se han guardado.';

$lang['options_dxcluster'] = 'DXCluster';
$lang['options_dxcluster_provider'] = 'Proveedor de DXClusterCache';
$lang['options_dxcluster_longtext'] = 'El proveedor de DXCluster-Cache. Puede configurar su propio caché con <a href="https://github.com/int2001/DXClusterAPI">DXClusterAPI</a> o usar un caché público.';
$lang['options_dxcluster_hint'] = 'URL del DXCluster-Cache. ej. https://dxc.jo30.de/dxcache';
$lang['options_dxcluster_settings'] = 'DXCluster';
$lang['options_dxcache_url_changed_to'] = 'URL del DXCluster-Cache cambiada a ';
$lang['options_dxcluster_maxage'] = 'Máxima edad de spots a tomarse en cuenta';
$lang['options_dxcluster_maxage_hint'] = 'La edad en minutos de los spots que se tomarán en cuenta en el plan de bandas/búsqueda';
$lang['options_dxcluster_decont'] = 'Mostrar spots que se observen en el siguiente continente';
$lang['options_dxcluster_maxage_changed_to']='Máxima edad de spots cambiada a ';
$lang['options_dxcluster_decont_changed_to']='de continente cambiado a ';
$lang['options_dxcluster_decont_hint']='Solo se muestran spots por observadores de este continente';

$lang['options_version_dialog'] = "Información de Versión";
$lang['options_version_dialog_close'] = "Cerrar";
$lang['options_version_dialog_dismiss'] = "No mostrar de nuevo";
$lang['options_version_dialog_settings'] = "Configuración de Información de Versión";
$lang['options_version_dialog_header'] = "Cabecera de la Información de Versión";
$lang['options_version_dialog_header_hint'] = "Puede cambiar el encabezado del diálogo de información de versión.";
$lang['options_version_dialog_header_changed_to'] = "El Encabezado de Información de Versión cambió a";
$lang['options_version_dialog_mode'] = "Modo de Información de Versión";
$lang['options_version_dialog_mode_release_notes'] = "Solo Notas de la Versión";
$lang['options_version_dialog_mode_custom_text'] = "Solo Texto Personalizado";
$lang['options_version_dialog_mode_both'] = "Notas de la Versión y Texto Personalizado";
$lang['options_version_dialog_mode_disabled'] = "Desactivado";
$lang['options_version_dialog_mode_hint'] = "La Información de Versión es mostrada a todos los usuarios. El usuario tiene la opción de cerrar el diálogo después de leerlo. Seleccione si solo quiere mostrar las notas de la versión (extraídas de GitHub), solo texto personalizado o ambos.";
$lang['options_version_dialog_custom_text'] = "Texto Personalizado de la Información de Versión";
$lang['options_version_dialog_custom_text_hint'] = "Este es el texto personalizado que se muestra en el diálogo.";
$lang['options_version_dialog_mode_changed_to'] = "Modo de Información de Versión ha cambiado a";
$lang['options_version_dialog_custom_text_saved'] = "¡Texto Personalizado de la Información de Versión guardado!";
$lang['options_version_dialog_success_show_all'] = "La Información de Versión será mostrada a todos los usuarios de nuevo";
$lang['options_version_dialog_success_hide_all'] = "La Información de Versión no será mostrada a nadie";
$lang['options_version_dialog_show_hide'] = "Mostrar/Ocultar el Diálogo de Información de Versión para todos los Usuarios";
$lang['options_version_dialog_show_all'] = "Mostrar a todos los Usuarios";
$lang['options_version_dialog_hide_all'] = "Ocultar a todos los Usuarios";
$lang['options_version_dialog_show_all_hint'] = "Esto mostrará el diálogo de versión automáticamente a todos los usuarios la próxima vez que recarguen la página.";
$lang['options_version_dialog_hide_all_hint'] = "Esto desactivará que se muestre automáticamente el diálogo de versión para todos los usuarios.";

$lang['options_save'] = 'Guardar';

// Bands

$lang['options_bands'] = "Bandas";
$lang['options_bands_text_ln1'] = "Usando la lista de bandas puede controlar qué bandas se muestran cuando se crea un nuevo QSO.";
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

