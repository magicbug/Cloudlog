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
defined('BASEPATH') OR exit('No direct script access allowed');


$lang['db_invalid_connection_str'] = 'Det går inte att upptäcka databasinställningarna med hjälp av strängen du specifierat.';
$lang['db_unable_to_connect'] = 'Det går ej att koppla till din databasserver med de givna inställningarna.';
$lang['db_unable_to_select'] = 'Går inte att välja databasen "%s"';
$lang['db_unable_to_create'] = 'Går inte att skapa databasen "%s"';
$lang['db_invalid_query'] = 'Förfrågan som du skickat är inte giltig.';
$lang['db_must_set_table'] = 'Du måste specifiera tabell i din förfrågan.';
$lang['db_must_use_set'] = 'Du måste använda metoden "set" för att uppdatera en post i databasen.';
$lang['db_must_use_index'] = 'Du måste ange ett index att matcha på för massuppdateringar.';
$lang['db_batch_missing_index'] = 'En eller flera poster som skickats för massuppdatering saknar det givna indexet.';
$lang['db_must_use_where'] = 'Uppdatering tillåts ej utan ett "where"-block.';
$lang['db_del_must_use_where'] = 'Borttagning tillåts ej utan "where"- eller "like"-block.';
$lang['db_field_param_missing'] = 'För att hämta fält krävs tabellnamnet som en parameter.';
$lang['db_unsupported_function'] = 'Denna tjänst är inte tillgänglig i din databas.';
$lang['db_transaction_failure'] = 'Fel i transaktionen: Data återställd.';
$lang['db_unable_to_drop'] = 'Det gick inte att rensa bort databasen.';
$lang['db_unsupported_feature'] = 'Denna tjänst är inte tillgänglig i din databasserver.';
$lang['db_unsupported_compression'] = 'Filkomprimeringsformatet är inte tillgänglig på din server.';
$lang['db_filepath_error'] = 'Det gick inte att skriva data till den filsökvägen du specifierat.';
$lang['db_invalid_cache_path'] = 'Cachesökvägen du specifierat är inte giltig eller skrivbar.';
$lang['db_table_name_required'] = 'Ett tabellnamn krävs för detta utförande.';
$lang['db_column_name_required'] = 'Ett kolumnamn krävs för detta utförande.';
$lang['db_column_definition_required'] = 'En kolumndefinition krävs för detta utförande.';
$lang['db_unable_to_set_charset'] = 'Det gick inte att välja klientkopplingens karaktärsgrupp: %s';
$lang['db_error_heading'] = 'Ett databasfel hände';
