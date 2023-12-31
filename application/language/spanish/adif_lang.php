<?php

defined('BASEPATH') OR exit('No direct script access allowed');


/*
___________________________________________________________________________________________
Topbar
___________________________________________________________________________________________
*/

$lang['adif_import'] = "Importar ADIF";
$lang['adif_export'] = "Exportar ADIF";
// $lang['lotw_title']                      --> application/language/english/lotw_lang.php
$lang['darc_dcl'] = "DARC DCL";


/*
___________________________________________________________________________________________
ADIF Import
___________________________________________________________________________________________
*/

// $lang['general_word_important']           --> application/language/english/general_words_lang.php
$lang['adif_alert_log_files_type'] = "Los archivos de registro deben tener el tipo de archivo *.adi";
// $lang['general_word_warning']            --> application/language/english/general_words_lang.php "PHP Upload Warning"
// $lang['gen_max_file_upload_size']        --> application/language/english/general_words_lang.php "PHP Upload Warning"

$lang['adif_select_stationlocation'] = "Seleccione la Localización de la Estación";
// $lang['gen_hamradio_callsign']           --> application/language/english/general_words_lang.php

// The File Input is translated by the Browser
$lang['adif_file_label'] = "Archivo ADIF";

$lang['adif_hint_no_info_in_file'] ="Seleccione si el archivo ADIF que se va a importar no incluye esta información.";

$lang['adif_import_dup'] = "Importar QSOs duplicadas";
$lang['adif_mark_imported_lotw'] = "Marcar QSOs importadas como subidas a LoTW";
$lang['adif_mark_imported_hrdlog'] = "Marcar QSOs importadas como subidas al libro de guardia de HRDLog.net";
$lang['adif_mark_imported_qrz'] = "Marcar QSOs importadas como subidas al libro de guardia de QRZ Logbook";
$lang['adif_mark_imported_clublog'] = "Marcar QSOs importadas como subidas al libro de guardia de Clublog Logbook";

$lang['adif_dxcc_from_adif'] = "Usar la información DXCC del ADIF";
$lang['adif_dxcc_from_adif_hint'] = "Si no se selecciona, Cloudlog intentará determinar la información DXCC automáticamente.";

$lang['adif_always_use_login_call_as_op'] = "Siempre use el indicativo usado para iniciar sesión como el nombre de operador al importar";

$lang['adif_ignore_station_call'] = "Ignorar el indicativo de la Estación al importar";
$lang['adif_ignore_station_call_hint'] = "Si se selecciona, Cloudlog intentará importar <b>todos</b> los QSOs del ADIF, sin importar si concuerdan con la estación/localización seleccionada.";

$lang['adif_upload'] = "Subir";

/*
___________________________________________________________________________________________
ADIF Export
___________________________________________________________________________________________
*/

$lang['adif_export_take_it_anywhere'] = "¡Lleve su archivo de libro de guardia a cualquier lugar!";
$lang['adif_export_take_it_anywhere_hint'] = "Exportar archivos ADIF le permite importar contactos en aplicaciones de terceros como LoTW, Diplomas o para simplemente tener una copia de seguridad.";


$lang['adif_mark_exported_lotw'] = "Marcar QSOs exportados como subidos a LoTW";
$lang['adif_mark_exported_no_lotw'] = "Exportar QSOs que no se hayan subido a LoTW";

$lang['adif_export_qso'] = "Exportar QSOs";

$lang['adif_export_sat_only_qso'] = "Exportar solo QSOs de satélite";
$lang['adif_export_sat_only_qso_all'] = "Exportar todos los QSOs de satélite";
$lang['adif_export_sat_only_qso_lotw'] = "Exportar todos los QSOs de satélite confirmados en LoTW";

/*
___________________________________________________________________________________________
Logbook of the World
___________________________________________________________________________________________
*/

$lang['adif_lotw_export_if_selected'] = "¡Si no selecciona un rango de fechas entonces todos los QSOs serán marcados!";
$lang['adif_mark_qso_as_exported_to_lotw'] = "Marcar QSOs como exportados a LoTW";

$lang['adif_qso_marked'] = "QSOs marcados";
$lang['adif_yay_its_done'] = "¡Bien, hemos terminado!";
$lang['adif_qso_lotw_marked_confirm'] = "Los QSOs fueron marcados como exportados a LoTW.";

/*
___________________________________________________________________________________________
DARC DCL
___________________________________________________________________________________________
*/
$lang['adif_dcl_text_pre'] = "Vaya a";
$lang['adif_dcl_text_post'] = "y exporte su libro de guardia con DOKs confirmados. Para acelerar el proceso, puede seleccionar solo QSOs tipo DL para que sean descargados (ej. ponga \"DL\" en la Lista de Prefijos). El archivo ADIF descargado puede ser subido aquí para actualizar los QSOs con información DOK.";

$lang['only_confirmed_qsos'] = "Solo importar datos DOK de los QSO confirmados en DCL.";
$lang['only_confirmed_qsos_hint'] = "Si se deja desactivado, se actualizarán los datos DOK con datos de los QSO no confirmados en DCL.";

$lang['overwrite_by_dcl'] = "Sobreescribir los DOK existentes en el registro por DCL (si son diferentes)";
$lang['overwrite_by_dcl_hint'] = "Si está activo, Cloudlog forzará la sobreescritura de los DOK existentes con los DOK desde el libro de DCL.";

$lang['ignore_ambiguous'] = "Ignorar QSOs que no concuerden";
$lang['ignore_ambiguous_hint'] = "Si se deja desactivado, se mostrará la información de QSOs que no se encuentren en Cloudlog.";

/*
___________________________________________________________________________________________
Import Success
___________________________________________________________________________________________
*/

$lang['adif_imported'] = "ADIF Importado";
$lang['adif_yay_its_imported'] = "¡Bien, lo hemos importado!";
$lang['adif_import_confirm'] = "El archivo ADIF fue importado.";

$lang['adif_import_dupes_inserted'] = " <b>¡Se insertaron registros duplicados!</b>";
$lang['adif_import_dupes_skipped'] = " Se descartaron registros duplicados.";

$lang['adif_import_errors'] = "Errores ADIF";
$lang['adif_import_errors_hint'] = "Tiene errores en su archivo ADIF. Los QSOs han sido adicionados, pero estos campos no han sido importados.";

/*
___________________________________________________________________________________________
DCL Success
___________________________________________________________________________________________
*/

$lang['dcl_results'] = "Resultados de la actualización DOK de DCL";
$lang['dcl_info_updated'] = "Se ha actualizado la información para DOKs desde DCL.";
$lang['dcl_qsos_updated'] = "QSOs actualizados";
$lang['dcl_qsos_ignored'] = "QSOs ignorados";
$lang['dcl_qsos_unmatched'] = "QSOs que no concuerdan";
$lang['dcl_no_qsos_updated'] = "No se encontraron QSOs a actualizar.";
$lang['dcl_dok_errors'] = "Errores DOK";
$lang['dcl_dok_errors_details'] = "Hay datos diferentes para DOKs en su libro comparados con DCL";
$lang['dcl_qsl_status'] = "Estado de QSLs en DCL QSL";
$lang['dcl_qsl_status_c'] = "confirmados por LoTW/Clublog/eQSL/Concurso";
$lang['dcl_qsl_status_mno'] = "confirmados por el administrador del premio";
$lang['dcl_qsl_status_i'] = "confirmados al hacer chequeo cruzadoc on datos de DCL";
$lang['dcl_qsl_status_w'] = "pendiente de confirmación";
$lang['dcl_qsl_status_x'] = "sin confirmar";
$lang['dcl_qsl_status_unknown'] = "desconocido";
$lang['dcl_no_match'] = "QSOs que no concuerdan";
