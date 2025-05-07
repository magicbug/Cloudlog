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

$lang['imglib_source_image_required'] = 'Musisz określić obraz źródłowy w swoich preferencjach.';
$lang['imglib_gd_required'] = 'Biblioteka obrazów GD jest wymagana dla tej funkcji.';
$lang['imglib_gd_required_for_props'] = 'Twój serwer musi obsługiwać bibliotekę obrazów GD, aby określić właściwości obrazu.';
$lang['imglib_unsupported_imagecreate'] = 'Twój serwer nie obsługuje funkcji GD wymaganej do przetworzenia tego typu obrazu.';
$lang['imglib_gif_not_supported'] = 'Obrazy GIF często nie są obsługiwane z powodu ograniczeń licencyjnych. Zamiast tego może być konieczne użycie obrazów JPG lub PNG.';
$lang['imglib_jpg_not_supported'] = 'Obrazki JPG nie są obsługiwane.';
$lang['imglib_png_not_supported'] = 'Obrazki PNG nie są obsługiwane.';
$lang['imglib_webp_not_supported'] = 'Obrazki WEBP nie są obsługiwane.';
$lang['imglib_jpg_or_png_required'] = 'Protokół zmiany rozmiaru obrazu określony w preferencjach działa tylko z obrazami JPEG lub PNG.';
$lang['imglib_copy_error'] = 'Wystąpił błąd podczas próby zastąpienia pliku. Upewnij się, że katalog pliku jest zapisywalny.';
$lang['imglib_rotate_unsupported'] = 'Obrót obrazu nie jest obsługiwany przez serwer.';
$lang['imglib_libpath_invalid'] = 'Ścieżka do biblioteki obrazów jest nieprawidłowa. Ustaw prawidłową ścieżkę w preferencjach obrazu.';
$lang['imglib_image_process_failed'] = 'Przetwarzanie obrazu nie powiodło się. Sprawdź, czy serwer obsługuje wybrany protokół i czy ścieżka do biblioteki obrazów jest prawidłowa.';
$lang['imglib_rotation_angle_required'] = 'Do obrócenia obrazu wymagany jest kąt obrotu.';
$lang['imglib_invalid_path'] = 'Ścieżka do obrazu jest nieprawidłowa.';
$lang['imglib_invalid_image'] = 'Dostarczony obraz jest nieprawidłowy.';
$lang['imglib_copy_failed'] = 'Procedura kopiowania obrazu nie powiodła się.';
$lang['imglib_missing_font'] = 'Nie można znaleźć czcionki do użycia.';
$lang['imglib_save_failed'] = 'Nie można zapisać obrazu. Upewnij się, że obraz i katalog pliku są zapisywalne.';
