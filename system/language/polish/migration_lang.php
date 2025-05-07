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
 * @since	Version 3.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('Bezpośredni dostęp do skryptu nie jest dozwolony');

$lang['migration_none_found'] = 'Nie znaleziono migracji.';
$lang['migration_not_found'] = 'Nie znaleziono migracji o numerze wersji: %s.';
$lang['migration_sequence_gap'] = 'W sekwencji migracji jest przerwa w pobliżu numeru wersji: %s.';
$lang['migration_multiple_version'] = 'Istnieją liczne migracje o tym samym numerze wersji: %s.';
$lang['migration_class_doesnt_exist'] = 'Nie znaleziono klasy migracji "%s".';
$lang['migration_missing_up_method'] = 'W klasie migracji "%s" brakuje metody "up".';
$lang['migration_missing_down_method'] = 'Klasa migracji "%s" nie ma metody "down".';
$lang['migration_invalid_filename'] = 'Migracja "%s" ma nieprawidłową nazwę pliku.';
