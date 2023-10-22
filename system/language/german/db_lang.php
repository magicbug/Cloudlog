<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2017, British Columbia Institute of Technology
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
 * @copyright	Copyright (c) 2014 - 2017, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('Direkter Skriptzugriff ist nicht erlaubt');

$lang['db_invalid_connection_str'] = 'Die Datenbankkonfiguration konnte für die angegebene Verbindung nicht ermittelt werden.';
$lang['db_unable_to_connect'] = 'Die Verbindung zur Datenbank konnte mit der angegebenen Konfiguration nicht hergestellt werden.';
$lang['db_unable_to_select'] = 'Die Datenbank %s konnte nicht ausgewählt werden.';
$lang['db_unable_to_create'] = 'Die Datenbank %s konnte nicht erstellt werden.';
$lang['db_invalid_query'] = 'Die angegebene Abfrage ist ungültig.';
$lang['db_must_set_table'] = 'Diese Abfrage erfordert die Angabe einer Datenbanktabelle.';
$lang['db_must_use_set'] = 'Der Befehl "set" muss verwendet werden, um einen Eintrag zu aktualisieren.';
$lang['db_must_use_index'] = 'Batch-Updates erfordern einen passenden.';
$lang['db_batch_missing_index'] = 'Einem oder mehreren Einträgen für das Batch-Update fehlt ein Index.';
$lang['db_must_use_where'] = 'Aktualisierungen von Datensätzen erfordern eine "WHERE"-Klausel.';
$lang['db_del_must_use_where'] = 'Das Löschen von Datensätzen erfordert eine "WHERE"-Klausel.';
$lang['db_field_param_missing'] = 'Der Name der Tabelle, aus der Daten abgefragt werden sollen, muss angegeben werden.';
$lang['db_unsupported_function'] = 'Dieser Befehl wird von der verwendeten Datenbank nicht unterstützt.';
$lang['db_transaction_failure'] = 'Die Transaktion ist fehlgeschlagen: Der vorherige Zustand wurde wiederhergestellt.';
$lang['db_unable_to_drop'] = 'Die Datenbank konnte nicht gelöscht werden.';
$lang['db_unsuported_feature'] = 'Dieser Befehl wird von der verwendeten Datenbank nicht unterstützt.';
$lang['db_unsuported_compression'] = 'Das verwendete Kompressions-Dateiformat wird von der Datenbank nicht unterstützt.';
$lang['db_filepath_error'] = 'Die Ausgabe von Daten in den angegebenen Dateipfad ist fehlgeschlagen.';
$lang['db_invalid_cache_path'] = 'Der Cache-Pfad ist ungültig oder schreibgeschützt.';
$lang['db_table_name_required'] = 'Dieser Befehl erfordert die Angabe eines Tabellennamens.';
$lang['db_column_name_required'] = 'Dieser Befehl erfordert die Angabe eines Spaltennamens.';
$lang['db_column_definition_required'] = 'Dieser Befehl erfordert die Angabe einer Spaltendefinition.';
$lang['db_unable_to_set_charset'] = 'Der Zeichensatz für die Datenbankverbindung konnte nicht festgelegt werden: %s';
$lang['db_error_heading'] = 'Ein Datenbankfehler ist aufgetreten';
