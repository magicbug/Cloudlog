<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
___________________________________________________________________________________________
Station Logbooks
___________________________________________________________________________________________
*/

$lang['station_logbooks'] = "Libros de Guardia de Estación";
$lang['station_logbooks_description_header'] = "¿Qué son Libros de Guardia de Estación";
$lang['station_logbooks_description_text'] = "Los Libros de Guardia de Estación le permiten agrupar Localizaciones de Estación, observando todas las localizaciones en una sola sesión, desde las áreas del libro de guardia hasta las analíticas. Muy útil cuando está operando en múltiples localizaciones, pero que son parte del mismo Círculo DXCC o VUCC.";
$lang['station_logbooks_create'] = "Crear Libro de Guardia de Estación";
$lang['station_logbooks_status'] = "Estado";
$lang['station_logbooks_link'] = "Enlace";
$lang['station_logbooks_public_search'] = "Búsqueda Pública";
$lang['station_logbooks_set_active'] = "Establecer como Libro de Guardia Activo";
$lang['station_logbooks_active_logbook'] = "Libro de Guardia Activo";
$lang['station_logbooks_edit_logbook'] = "Editar Libro de Guardia de Estación";    // Full sentence will be generated 'Edit Station Logbook: [Logbook Name]'
$lang['station_logbooks_confirm_delete'] = "¿Está seguro que desea eliminar el libro de guardia de estación a continuación? Deberá re-enlazar todas las localizaciones enlazadas aquí a otro Libro de Guardia: ";
$lang['station_logbooks_view_public'] = "Ver Página Pública para el Libro de Guardia: ";
$lang['station_logbooks_create_name'] = "Nombre del Libro de Guardia de Estación";
$lang['station_logbooks_create_name_hint'] = "Puede llamar el libro de guardia de estación como desee.";
$lang['station_logbooks_edit_name_hint'] = "Nombre corto para el libro de guardia de estación. Ejemplo: Libro Casa (IO87IP)";
$lang['station_logbooks_edit_name_update'] = "Actualizar el Nombre del Libro de Guardia de Estación";
$lang['station_logbooks_public_slug'] = "Abreviatura Pública";
$lang['station_logbooks_public_slug_hint'] = "El configurar una abreviatura pública le permite compartir su libro de guardia con cualquier persona usando una dirección personalizada del sitio web, esta abreviatura debe contener solo letras y números.";
$lang['station_logbooks_public_slug_format1'] = "Después se verá de esta manera:";
$lang['station_logbooks_public_slug_format2'] = "[su abreviatura]";
$lang['station_logbooks_public_slug_input'] = "Introduzca la elección de Abreviatura Pública";
$lang['station_logbooks_public_slug_visit'] = "Visitar Página Pública";
$lang['station_logbooks_public_search_hint'] = "El permitir la función de búsqueda pública le permite poner una casilla de búsqueda en la página pública del libro de guardia que sea accedida por una abreviatura pública. La búsqueda solo cubre este libro de guardia.";
$lang['station_logbooks_public_search_enabled'] = "Activar búsqueda pública";
$lang['station_logbooks_select_avail_loc'] = "Seleccionar Localizaciones de Estación Disponibles";
$lang['station_logbooks_link_loc'] = "Enlazar Localización";
$lang['station_logbooks_linked_loc'] = "Localizaciones Enlazadas";
$lang['station_logbooks_no_linked_loc'] = "No hay Localizaciones Enlazadas";
$lang['station_logbooks_unlink_station_location'] = "Desenlazar la Localización de la Estación";



/*
___________________________________________________________________________________________
Station Locations
___________________________________________________________________________________________
*/

$lang['station_location'] = 'Localización de Estación';
$lang['station_location_plural'] = "Localizaciones de Estaciones";
$lang['station_location_header_ln1'] = 'Las localizaciones de Estación le permiten definir localizaciones de operación, como su QTH, el QTH de un amigo o una estación portable.';
$lang['station_location_header_ln2'] = 'De forma similar a los libros de guardia, un perfil de estación mantiene asociado un conjunto de QSOs.';
$lang['station_location_header_ln3'] = 'Solo una estación puede estar activa en cualquier momento. En la tabla de abajo esta se muestra con la etiqueta de -Estación Activa-.';
$lang['station_location_create_header'] = 'Crear Localización de Estación';
$lang['station_location_create'] = 'Crear una Localización de Estación';
$lang['station_location_edit'] = 'Editar Localización de Estación: ';
$lang['station_location_updated_suff'] = ' Actualizada.';
$lang['station_location_warning'] = 'Atención: Debe configurar una Localización de Estación como activa. vaya a Indicativo->Localización de Estación para seleccionar una.';
$lang['station_location_reassign_at'] = 'Por favor, reasignelas en ';
$lang['station_location_warning_reassign'] = 'Debido a cambios recientes en Cloudlog, debe reasignar sus QSO a sus perfiles de estación.';
$lang['station_location_name'] = 'Nombre de Perfil';
$lang['station_location_name_hint'] = 'Nombre corto para la Localización de Estación. Ejemplo: Casa (IO87IP)';
$lang['station_location_callsign'] = 'Indicativo de la Estación';
$lang['station_location_callsign_hint'] = 'Indicativo de llamada de la Estación. Ejemplo: 2M0SQL/P';
$lang['station_location_power'] = 'Potencia de la Estación (W)';
$lang['station_location_power_hint'] = 'Potencia de la estación por defecto en Vatios. Puede ser sobreescrito por CAT.';
$lang['station_location_emptylog'] = 'Libro Vacío';
$lang['station_location_confirm_active'] = '¿Está seguro que desea poner como activa la siguiente estación: ';
$lang['station_location_set_active'] = 'Poner como Activa';
$lang['station_location_active'] = 'Estación Activa';
$lang['station_location_claim_ownership'] = 'Reclamar Propiedad';
$lang['station_location_confirm_del_qso'] = '¿Está seguro que desea eliminar todos los QSOs en este perfil de estación?';
$lang['station_location_confirm_del_stationlocation'] = '¿Está seguro que desea eliminar el perfil de estación  ';
$lang['station_location_confirm_del_stationlocation_qso'] = '? Esto eliminará todos los QSOs dentro este perfil de estación.';
$lang['station_location_dxcc'] = 'DXCC de la Estación';
$lang['station_location_dxcc_hint'] = 'Entidad DXCC de la Estación. Por ejemplo: Escocia';
$lang['station_location_dxcc_warning'] = "Deténgase un momento. El DXCC escogido está desactualizado y ya no es válido. Revise cuál DXCC es el correcto para su localización particular. Si está totalmente seguro, ignore esta advertencia.";
$lang['station_location_city'] = 'Ciudad de la Estación';
$lang['station_location_city_hint'] = 'Ciudad donde está ubicada la estación. Ejemplo: Inverness';
$lang['station_location_state'] = 'Estado/Departamento/Provincia de la Estación';
$lang['station_location_state_hint'] = 'Estado/Departamento/Provincia de la estación. Aplica solo para ciertos países. Déjelo vacío si no aplica.';
$lang['station_location_county'] = 'Condado de la Estación';
$lang['station_location_county_hint'] = 'Condado de la Estación (Solo se usa en USA/Alaska/Hawaii).';
$lang['station_location_gridsquare'] = 'Gridsquare de la Estación';
$lang['station_location_gridsquare_hint_ln1'] = "Gridsquare de la estación. Ejemplo: IO87IP. ¡Si no conoce su grid square haga <a href='https://zone-check.eu/?m=loc' target='_blank'>clic aquí</a>!";
$lang['station_location_gridsquare_hint_ln2'] = "Si está localizado justo en una línea de la malla, introduzca múltiples gridsquares separados con comas. por ejemplo: IO77,IO78,IO87,IO88.";
$lang['station_location_iota_hint_ln1'] = "Referencia IOTA de la Estación. Ejemplo: EU-005";
$lang['station_location_iota_hint_ln2'] = "Puede buscar las referencias IOTA en el sitio web de <a target='_blank' href='https://www.iota-world.org/iota-directory/annex-f-short-title-iota-reference-number-list.html'>IOTA World</a>.";
$lang['station_location_sota_hint_ln1'] = "Referencia SOTA de la Estación. Puede buscar las referencias SOTA en el sitio web de <a target='_blank' href='https://www.sotamaps.org/'>SOTA Maps</a>.";
$lang['station_location_wwff_hint_ln1'] = "Referencia WWFF de la Estación. Puede buscar las referencias WWFF en el sitio web de <a target='_blank' href='https://www.cqgma.org/mvs/'>GMA Map</a>.";
$lang['station_location_pota_hint_ln1'] = "Referencia POTA de la Estación. Puede buscar las referencias POTA en el sitio web de <a target='_blank' href='https://pota.app/#/map/'>POTA Map</a>.";
$lang['station_location_signature'] = "Firma";
$lang['station_location_signature_name'] = "Nombre de la Firma";
$lang['station_location_signature_name_hint'] = "Firma de la Estación (ej. GMA).";
$lang['station_location_signature_info'] = "Información de la Firma";
$lang['station_location_signature_info_hint'] = "Información de la Firma de la Estación (ej. DA/NW-357).";
$lang['station_location_eqsl_hint'] = 'El Apodo del QTH como está configurado en su perfil de eQSL';
$lang['station_location_eqsl_defaultqslmsg'] = "QSLMSG por Defecto";
$lang['station_location_eqsl_defaultqslmsg_hint'] = "Defina un mensaje por defecto que será añadido y enviado para cada QSO para esta localización de estación.";
$lang['station_location_qrz_subscription'] = 'Requiere Suscripción';
$lang['station_location_qrz_hint'] = "Encuentre su clave API en la <a href='https://logbook.qrz.com/logbook' target='_blank'>página de Configuración de libro de guardia en QRZ.com";
$lang['station_location_qrz_realtime_upload'] = 'Subida en Tiempo Real del Libro de Guardia a QRZ.com';
$lang['station_location_hrdlog_username'] = "Nombre de Usuario de HRDLog.net";
$lang['station_location_hrdlog_username_hint'] = "El nombre de usuario con el que se registró en HRDlog.net (usualmente su indicativo).";
$lang['station_location_hrdlog_code'] = "Código API de HRDLog.net";
$lang['station_location_hrdlog_realtime_upload'] = "Subida en Tiempo Real del Libro de Guardia a HRDLog.net";
$lang['station_location_hrdlog_code_hint'] = "Cree su código API en <a href='http://www.hrdlog.net/EditUser.aspx' target='_blank'>la página de perfil de usuario de HRDLog.net";
$lang['station_location_qo100_hint'] = "Cree su llave API en <a href='https://qo100dx.club' target='_blank'>su página perfil de QO-100 Dx Club";
$lang['station_location_qo100_realtime_upload'] = "Subida en Tiempo Real del Libro de Guardia a QO-100 Dx Club";
$lang['station_location_oqrs_enabled'] = "Activar OQRS";
$lang['station_location_oqrs_email_alert'] = "Alerta de Email de OQRS";
$lang['station_location_oqrs_email_hint'] = "Asegúrese que su correo está bien configurado en las opciones globales y de administrador.";
$lang['station_location_oqrs_text'] = "Texto OQRS";
$lang['station_location_oqrs_text_hint'] = "Algúna información que desee agregar acerca de su forma de hacer QSL.";
$lang['station_location_clublog_realtime_upload']='Subida en Tiempo Real en ClubLog';


