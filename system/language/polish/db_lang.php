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
defined('BASEPATH') OR exit('Bezpośredni dostęp do skryptu nie jest dozwolony');

$lang['db_invalid_connection_str'] = 'Nie można określić ustawień bazy danych na podstawie przesłanego ciągu połączenia.';
$lang['db_unable_to_connect'] = 'Nie można połączyć się z serwerem bazy danych przy użyciu podanych ustawień.';
$lang['db_unable_to_select'] = 'Nie można wybrać określonej bazy danych: %s';
$lang['db_unable_to_create'] = 'Nie można utworzyć określonej bazy danych: %s';
$lang['db_invalid_query'] = 'Przesłane zapytanie jest nieprawidłowe.';
$lang['db_must_set_table'] = 'Musisz ustawić tabelę bazy danych, która będzie używana z zapytaniem.';
$lang['db_must_use_set'] = 'Aby zaktualizować wpis, należy użyć metody „set”.';
$lang['db_must_use_index'] = 'Należy określić indeks, do którego mają być dopasowane aktualizacje wsadowe.';
$lang['db_batch_missing_index'] = 'W jednym lub większej liczbie wierszy przesłanych do aktualizacji wsadowej brakuje określonego indeksu.';
$lang['db_must_use_where'] = 'Aktualizacje są niedozwolone, chyba że zawierają klauzulę „where”.';
$lang['db_del_must_use_where'] = 'Usunięcia są niedozwolone, chyba że zawierają klauzulę „where” lub „like”.';
$lang['db_field_param_missing'] = 'Aby pobrać pola, wymagana jest nazwa tabeli jako parametr.';
$lang['db_unsupported_function'] = 'Ta funkcja nie jest dostępna dla używanej bazy danych.';
$lang['db_transaction_failure'] = 'Błąd transakcji: wykonano wycofanie.';
$lang['db_unable_to_drop'] = 'Nie można usunąć określonej bazy danych.';
$lang['db_unsupported_feature'] = 'Nieobsługiwana funkcja używanej platformy bazy danych.';
$lang['db_unsupported_compression'] = 'Wybrany format kompresji pliku nie jest obsługiwany przez serwer.';
$lang['db_filepath_error'] = 'Nie można zapisać danych w przesłanej ścieżce pliku.';
$lang['db_invalid_cache_path'] = 'Przesłana ścieżka pamięci podręcznej jest nieprawidłowa lub niemożliwa do zapisu.';
$lang['db_table_name_required'] = 'Do tej operacji wymagana jest nazwa tabeli.';
$lang['db_column_name_required'] = 'Do tej operacji wymagana jest nazwa kolumny.';
$lang['db_column_definition_required'] = 'Do tej operacji wymagana jest definicja kolumny.';
$lang['db_unable_to_set_charset'] = 'Nie można ustawić zestawu znaków połączenia klienta: %s';
$lang['db_error_heading'] = 'Wystąpił błąd bazy danych';
