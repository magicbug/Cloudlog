<?php

defined('BASEPATH') OR exit('No direct script access allowed');


/*
___________________________________________________________________________________________
Topbar
___________________________________________________________________________________________
*/

$lang['filter_quickfilters'] = 'Filtros rápidos';
$lang['filter_qsl_filters'] = 'Filtros QSL';
$lang['filter_filters'] = 'Filtros';
$lang['filter_actions'] = 'Actiones';
$lang['filter_results'] = 'No. Resultados';
$lang['filter_search'] = 'Buscar';
$lang['filter_dupes'] = "Duplicados";
$lang['filter_map'] = 'Mapa';
$lang['filter_options'] = 'Opciones';
$lang['filter_reset'] = 'Reinicializar';

/*
___________________________________________________________________________________________
Quickilters
___________________________________________________________________________________________
*/

$lang['filter_quicksearch_w_sel'] = 'Búsqueda rápida con seleccionados: ';
$lang['filter_search_callsign'] = 'Buscar Indicativo';
$lang['filter_search_dxcc'] = 'Buscar DXCC';
$lang['filter_search_state'] = 'Buscar Estado';
$lang['filter_search_gridsquare'] = 'Buscar Gridsquare';
$lang['filter_search_cq_zone'] = 'Buscar Zona CQ';
$lang['filter_search_mode'] = 'Buscar Modo';
$lang['filter_search_band'] = 'Buscar Banda';
$lang['filter_search_iota'] = 'Buscar IOTA';
$lang['filter_search_sota'] = 'Buscar SOTA';
$lang['filter_search_wwff'] = 'Buscar WWFF';
$lang['filter_search_pota'] = 'Buscar POTA';

/*
___________________________________________________________________________________________
QSL Filters
___________________________________________________________________________________________
*/

$lang['filter_qsl_sent'] = 'QSL enviadas';
$lang['filter_qsl_recv'] = 'QSL recibidas';
$lang['filter_qsl_sent_method'] = 'Método de Envío de QSL';
$lang['filter_qsl_recv_method'] = 'Método de Recepción de QSL';
$lang['filter_lotw_sent'] = 'Enviado por LoTW';
$lang['filter_lotw_recv'] = 'Recibido por LoTW';
$lang['filter_eqsl_sent'] = 'Enviado por eQSL';
$lang['filter_eqsl_recv'] = 'Recibido por eQSL';
$lang['filter_qsl_via'] = 'QSL via';
$lang['filter_qsl_images'] = 'Imágenes QSL';

// $lang['general_word_all']                --> application/language/english/general_words_lang.php
// $lang['general_word_yes']                --> application/language/english/general_words_lang.php
// $lang['general_word_no']                 --> application/language/english/general_words_lang.php
// $lang['general_word_requested']          --> application/language/english/general_words_lang.php
// $lang['general_word_queued']             --> application/language/english/general_words_lang.php
// $lang['general_word_invalid_ignore']     --> application/language/english/general_words_lang.php
$lang['filter_qsl_verified'] = 'Verificado';

// $lang['general_word_qslcard_bureau']     --> application/language/english/general_words_lang.php
// $lang['general_word_qslcard_direct']     --> application/language/english/general_words_lang.php
// $lang['general_word_qslcard_electronic'] --> application/language/english/general_words_lang.php
// $lang['general_word_qslcard_manager']    --> application/language/english/general_words_lang.php

/*
___________________________________________________________________________________________
General Filters
___________________________________________________________________________________________
*/

$lang['filter_general_from'] = 'Desde';
$lang['filter_general_to'] = 'a';
// $lang['gen_hamradio_de']             --> application/language/english/general_words_lang.php 
// $lang['gen_hamradio_dx']             --> application/language/english/general_words_lang.php 
// $lang['gen_hamradio_dxcc']           --> application/language/english/general_words_lang.php
$lang['filter_general_none'] = '- Ninguno - (ej. /MM, /AM)';
// $lang['gen_hamradio_state']          --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_gridsquare']     --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_mode']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_band']           --> application/language/english/general_words_lang.php

$lang['filter_general_propagation'] = 'Propagación';
// $lang['gen_hamradio_cq_zone']        --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_iota']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_sota']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_wwff']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_pota']           --> application/language/english/general_words_lang.php

/*
___________________________________________________________________________________________
Actions
___________________________________________________________________________________________
*/

$lang['filter_actions_w_selected'] = 'Con los seleccionados: ';
$lang['filter_actions_update_f_callbook'] = 'Actualizar de Callbook';
$lang['filter_actions_queue_bureau'] = 'En Cola por Buró';
$lang['filter_actions_queue_direct'] = 'En Cola por Directa';
$lang['filter_actions_queue_electronic'] = 'En Cola por Electrónico';
$lang['filter_actions_sent_bureau'] = 'Enviado (Buró)';
$lang['filter_actions_sent_direct'] = 'Enviado (Directa)';
$lang['filter_actions_sent_electronic'] = 'Enviado (Electrónico)';
$lang['filter_actions_not_sent'] = 'No Enviado';
$lang['filter_actions_qsl_n_required'] = 'QSL no Requerida';
$lang['filter_actions_recv_bureau'] = 'Recibido (Buró)';
$lang['filter_actions_recv_direct'] = 'Recibido (Directa)';
$lang['filter_actions_recv_electronic'] = 'Recibido (Electrónico)';
$lang['filter_actions_create_adif'] = 'Crear ADIF';
$lang['filter_actions_print_label'] = 'Imprimir Etiqueta';
$lang['filter_actions_start_print_title'] = 'Imprimir Etiquetas';
$lang['filter_actions_print_include_via'] = "Incluir Vía";
$lang['filter_actions_start_print'] = '¿Iniciar impresión desde?';
$lang['filter_actions_print'] = 'Imprimir';
$lang['filter_actions_qsl_slideshow'] = 'Presentación QSL';
$lang['filter_actions_delete'] = 'Eliminar';
$lang['filter_actions_delete_warning'] = "¡Advertencia! ¿Está seguro que desea eliminar las QSO marcadas?";


/*
___________________________________________________________________________________________
Options
___________________________________________________________________________________________
*/

$lang['filter_options_title'] = 'Opciones para el Libro de Guardia Avanzado';
$lang['filter_options_column'] = 'Columna';
$lang['filter_options_show'] = 'Mostrar';
// $lang['general_word_datetime']       --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_de']             --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_dx']             --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_mode']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_rsts']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_rstr']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_band']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_myrefs']         --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_refs']           --> application/language/english/general_words_lang.php
// $lang['general_word_name']           --> application/language/english/general_words_lang.php
// $lang['filter_qsl_via']              --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_qsl']            --> application/language/english/general_words_lang.php
// $lang['lotw_short']                  --> application/language/english/lotw_lang.php
// $lang['eqsl_short']                  --> application/language/english/eqsl_lang.php
// $lang['gen_hamradio_qslmsg']         --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_dxcc']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_state']          --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_cq_zone']        --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_iota']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_sota']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_wwff']           --> application/language/english/general_words_lang.php
// $lang['gen_hamradio_pota']           --> application/language/english/general_words_lang.php
// $lang['options_save']                --> application/language/english/options_lang.php
$lang['filter_search_operator'] = 'Buscar Operador';
$lang['filter_options_close'] = 'Cerrar';
