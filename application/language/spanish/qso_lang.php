<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// Tiles
$lang['qso_title_qso_map'] = 'Mapa de QSO';
$lang['qso_title_suggestions'] = 'Sugerencias';
$lang['qso_title_previous_contacts'] = 'Contactos previos';
$lang['qso_title_times_worked_before'] = "times worked before";
$lang['qso_title_image'] = 'Imagen de Perfil';
$lang['qso_previous_max_shown'] = "Se muestra máx. de 5 contactos previos";

// Quicklog on Dashboard
$lang['qso_quicklog_enter_callsign'] = 'REGISTRO RÁPIDO Introduzca Indicativo';

// Input Help Text on the /QSO Display
$lang['qso_transmit_power_helptext'] = 'Especifique el valor de potencia en Watios (W). Incluya solo números.';

$lang['qso_sota_ref_helptext'] = 'Por ejemplo: GM/NS-001.';
$lang['qso_wwff_ref_helptext'] = 'Por ejemplo: DLFF-0069.';
$lang['qso_pota_ref_helptext'] = 'Por ejemplo: PA-0150.';

$lang['qso_sig_helptext'] = 'Por ejemplo: GMA';
$lang['qso_sig_info_helptext'] = 'Por ejemplo: DA/NW-357';

$lang['qso_dok_helptext'] = 'Por ejemplo: Q03';

$lang['qso_notes_helptext'] = 'El contenido es usado solo dentro de Cloudlog y no es exportado a otros servicios.';
$lang['qsl_notes_helptext'] = 'El contenido de esta nota es exportado a servicios QSL como eqsl.cc.';

$lang['qso_eqsl_qslmsg_helptext'] = "Obtener el mensaje por defecto para eQSL, para esta estación.";

// error text //
$lang['qso_error_timeoff_less_timeon'] = "TimeOff es menor que TimeOn";

// Button Text on /qso Display

$lang['qso_btn_reset_qso'] = 'Reinicializar';
$lang['qso_btn_save_qso'] = 'Guardar QSO';
$lang['qso_btn_edit_qso'] = 'Editar QSO';
$lang['qso_delete_warning'] = "¡Advertencia! ¿Está seguro que desea eliminar QSOs con ";

// QSO Details

$lang['qso_details'] = 'Detalles de QSO';

$lang['fav_add'] = 'Añadir Banda/Modo a Favoritos';
$lang['qso_operator_callsign'] = 'Indicativo de Operador';

// Simple FLE (FastLogEntry)

$lang['qso_simplefle_info'] = "¿Qué es esto?";
$lang['qso_simplefle_info_ln1'] = "Simple Fast Log Entry (FLE)";
$lang['qso_simplefle_info_ln2'] = "'Fast Log Entry', o simplemente 'FLE' es un sistema para registrar QSO muy rápida y eficientemente. Debido a su sintaxis, solo se requieren pocos campos para introducir muchos QSOs con la menor cantidad de esfuerzo posible.";
$lang['qso_simplefle_info_ln3'] = "FLE fue escrito originalmente por DF3CB. Él ofreció un programa para Windows en su sitio. Simple FLE fue escrito por OK2CQR basado en el FLE de DF3CB y provee una interfaz web para registrar QSOs.";
$lang['qso_simplefle_info_ln4'] = "Un caso de uso común es si necesita importar sus registros manuales de una sesión a las afueras y ahora SimpleFLE está disponible en Cloudlog. La información acerca de la sintaxis y como funciona FLE se pueden encontrar <a href='https://df3cb.com/fle/documentation/' target='_blank'>aquí</a>.";
$lang['qso_simplefle_qso_data'] = "Datos de QSO";
$lang['qso_simplefle_qso_date_hint'] = "Si no escoge una fecha, se usará la fecha de hoy.";
$lang['qso_simplefle_qso_list'] = "Lista de QSOs";
$lang['qso_simplefle_qso_list_total'] = "Total";
$lang['qso_simplefle_qso_date'] = "Fecha de QSO";
$lang['qso_simplefle_operator'] = "Operador";
$lang['qso_simplefle_operator_hint'] = "ej. OK2CQR";
$lang['qso_simplefle_station_call_location'] = "Indicativo/Localización de la Estación";
$lang['qso_simplefle_station_call_location_hint'] = "Si ha operado desde una nueva localización, primero cree una nueva <a href=". site_url('station') . ">Localización de Estación</a>";
$lang['qso_simplefle_utc_time'] = "Hora UTC Actual";
$lang['qso_simplefle_enter_the_data'] = "Introduzca los Datos";
$lang['qso_simplefle_syntax_help_close_w_sample'] = "Cerrar y Cargar Datos de Muestra";
$lang['qso_simplefle_reload'] = "Recargar Lista de QSO";
$lang['qso_simplefle_save'] = "Guardar en Cloudlog";
$lang['qso_simplefle_clear'] = "Limpiar Sesión de Registro";
$lang['qso_simplefle_refs_hint'] = "Las Referencias deben ser <u>S</u>OTA, <u>I</u>OTA, <u>P</u>OTA o <u>W</u>WFF";

$lang['qso_simplefle_error_band'] = "¡Falta la Banda!";
$lang['qso_simplefle_error_mode'] = "¡Falta el Modo!";
$lang['qso_simplefle_error_time'] = "¡No se configuró la Hora!";
$lang['qso_simplefle_error_stationcall'] = "El Indicativo de la Estación no fue seleccionado";
$lang['qso_simplefle_error_operator'] = "El campo 'Operador' está vacío";
$lang['qso_simplefle_warning_reset'] = "¡Advertencia! ¿Está seguro que desea reinicializar todo?";
$lang['qso_simplefle_warning_missing_band_mode'] = "¡Advertencia! ¡No puede registrar la lista de QSOs, porque algunos QSO no tienen banda y/o modo definido!";
$lang['qso_simplefle_warning_missing_time'] = "¡Advertencia! ¡No puede registrar la lista de QSOs, porque algunos QSO no tienen hora definida!";
$lang['qso_simplefle_warning_example_data'] = "¡Atención! ¡El campo Datos contiene datos de muestra. Primero Limpie la Sesión de Registro!";
$lang['qso_simplefle_confirm_save_to_log'] = "¿Está seguro que desea añadir estos QSOs al Libro de Guardia y limpiar la sesión?";
$lang['qso_simplefle_success_save_to_log_header'] = "¡QSOs Registradas!";
$lang['qso_simplefle_success_save_to_log'] = "¡Las QSOs fueron satisfactoriamente guardadas en el Libro de Guardia!";
$lang['qso_simplefle_error_date'] = "Fecha inválida";

$lang['qso_simplefle_syntax_help_button'] = "Ayuda de Sintaxis";
$lang['qso_simplefle_syntax_help_title'] = "Sintaxis para FLE";
$lang['qso_simplefle_syntax_help_ln1'] = "Antes de empezar a registrar un QSO, observe las reglas básicas.";
$lang['qso_simplefle_syntax_help_ln2'] = "- Cada QSO nuevo debe estar en una línea nueva.";
$lang['qso_simplefle_syntax_help_ln3'] = "- En cada nueva línea, solo escriba datos que hayan cambiado desde el QSO anterior.";
$lang['qso_simplefle_syntax_help_ln4'] = "Para comenzar, asegúrese que ya ha rellenado el formulario en la izquierda con la fecha, el indicativo de la estación y el indicativo del operador. Los datos principales incluyen la banda (o QRG en MHz, ej., '7.145'), modo, y hora. Después de la hora, usted debe introducir el primer QSO, que es esencialmente el indicativo.";
$lang['qso_simplefle_syntax_help_ln5'] = "Por ejemplo, un QSO que ha iniciado a las 21:34 (UTC) con 2M0SQL en 20m SSB.";
$lang['qso_simplefle_syntax_help_ln6'] = "Si no introduce ninguna información RST, la sintaxis usará 59 (o 599 para datos). Nuestro siguiente QSO no era 59 en ambos lados, así que proveemos la información con el RST enviado primero. Fue 2 minutos después del primer QSO.";
$lang['qso_simplefle_syntax_help_ln7'] = "El primer QSL fue a las 21:34, y el segundo 2 minutos después a las 21:36. Escribimos 6 porque fue el único dato que cambió. La información de banda y modo no cambió, así que estos datos se omiten.";
$lang['qso_simplefle_syntax_help_ln8'] = "Para nuestro siguiente QSO a las 21:40 el 14 de mayo de 2021, cambiamos la banda a 40m pero seguimos en SSB. Si no se ingresa datos de RST, la sintaxis usará 59 para cada nuevo QSO. Por lo tanto podemos añadir un nuevo QSO que ocurrió a la misma hora dos días después. La fecha debe estar en el formato YYYY-MM-DD.";
$lang['qso_simplefle_syntax_help_ln9'] = "Para mayor información acerca de la sintaxis, por favor consulte el sitio de DF3CB <a href='https://df3cb.com/fle/documentation/' target='_blank'>aquí.</a>";
    
