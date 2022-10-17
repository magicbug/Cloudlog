<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2019, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2019, British Columbia Institute of Technology (https://bcit.ca/)
 * @license	https://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('Non è permesso l\'accesso diretto allo script');

$lang['db_invalid_connection_str'] = 'Impossibile determinare le impostazioni del database in base alla stringa di connessione inviata.';
$lang['db_unable_to_connect'] = 'Impossibile connettersi al server del database utilizzando le impostazioni fornite.';
$lang['db_unable_to_select'] = 'Impossibile selezionare il database specificato: %s';
$lang['db_unable_to_create'] = 'Impossibile creare il database specificato: %s';
$lang['db_invalid_query'] = 'La query che hai inviato non è valida.';
$lang['db_must_set_table'] = 'È necessario impostare la tabella del database da utilizzare con la query.';
$lang['db_must_use_set'] = 'È necessario utilizzare il metodo "set" per aggiornare una voce.';
$lang['db_must_use_index'] = 'È necessario specificare un indice su cui eseguire la corrispondenza per gli aggiornamenti batch.';
$lang['db_batch_missing_index'] = 'In una o più righe inviate per l\'aggiornamento batch manca l\'indice specificato.';
$lang['db_must_use_where'] = 'Gli aggiornamenti non sono consentiti a meno che non contengano una clausola "where".';
$lang['db_del_must_use_where'] = 'Le eliminazioni non sono consentite a meno che non contengano una clausola "where" o "like"';
$lang['db_field_param_missing'] = 'Per recuperare i campi è necessario il nome della tabella come parametro.';
$lang['db_unsupported_function'] = 'Questa funzione non è disponibile per il database in uso.';
$lang['db_transaction_failure'] = 'Transazione non riuscita: eseguito Rollback.';
$lang['db_unable_to_drop'] = 'Impossibile eliminare il database specificato.';
$lang['db_unsupported_feature'] = 'Funzionalità non supportata della piattaforma di database in uso.';
$lang['db_unsupported_compression'] = 'Il formato di compressione file che hai scelto non è supportato dal tuo server.';
$lang['db_filepath_error'] = 'Impossibile scrivere i dati nel percorso del file che hai inviato.';
$lang['db_invalid_cache_path'] = 'Il percorso della cache che hai inviato non è valido o scrivibile.';
$lang['db_table_name_required'] = 'Per tale operazione è richiesto un nome di tabella.';
$lang['db_column_name_required'] = 'Per tale operazione è richiesto un nome di colonna.';
$lang['db_column_definition_required'] = 'Per tale operazione è necessaria una definizione di colonna.';
$lang['db_unable_to_set_charset'] = 'Impossibile impostare il set di caratteri di connessione client: %s';
$lang['db_error_heading'] = 'Si è verificato un errore nel database';
