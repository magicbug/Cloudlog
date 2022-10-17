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
defined('BASEPATH') OR exit('Non è permesso l\'accesso diretto allo script');

$lang['migration_none_found'] = 'Non sono state trovate migrazioni.';
$lang['migration_not_found'] = 'Impossibile trovare la migrazione con il numero di versione: %s.';
$lang['migration_sequence_gap'] = 'C\'è una lacuna nella sequenza di migrazione vicino al numero di versione: %s.';
$lang['migration_multiple_version'] = 'Esistono più migrazioni con lo stesso numero di versione: %s.';
$lang['migration_class_doesnt_exist'] = 'Impossibile trovare la classe di migrazione "%s".';
$lang['migration_missing_up_method'] = 'Nella classe di migrazione "%s" manca un metodo "up".';
$lang['migration_missing_down_method'] = 'Nella classe di migrazione "%s" manca un metodo "down".';
$lang['migration_invalid_filename'] = 'La migrazione "%s" ha un nome file non valido.';
