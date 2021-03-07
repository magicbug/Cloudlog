<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2021, British Columbia Institute of Technology
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
 * @copyright	Copyright (c) 2014 - 2021, British Columbia Institute of Technology (https://bcit.ca/)
 * @license	https://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

$lang['form_validation_required']		= '{field} vaaditaan.';
$lang['form_validation_isset']			= '{field} -kenttä pitää sisältää arvon.';
$lang['form_validation_valid_email']		= '{field} -kentän pitää sisältää kelvollisen sähköpostiosoitteen.';
$lang['form_validation_valid_emails']		= '{field} -kentän pitää sisältää vain kelvollisia sähköpostiosoitteita.';
$lang['form_validation_valid_url']		= '{field} -kentän pitää sisältää kelvollinen URL-osoite.';
$lang['form_validation_valid_ip']		= '{field} -kentän pitää sisältää kelvollinen IP.';
$lang['form_validation_valid_base64']		= '{field} -kentän pitää sisältää kelvollinen  Base64 string.';
$lang['form_validation_min_length']		= '{field} -kentän pitää olla vähintään {param} merkkiä pitkä.';
$lang['form_validation_max_length']		= '{field} -kenttä ei voi ylittää {param} merkkiä.';
$lang['form_validation_exact_length']		= '{field} -kentän täytyy olla tasan {param} merkkiä pitkä.';
$lang['form_validation_alpha']			= '{field} -kenttä voi sisältää vain aakkosia.';
$lang['form_validation_alpha_numeric']		= '{field} -kenttä voi sisältää vain aakkosia ja numeroita.';
$lang['form_validation_alpha_numeric_spaces']	= '{field} -kenttä voi sisältää vain aakkosia, numeroita sekä välilyöntejä.';
$lang['form_validation_alpha_dash']		= '{field} -kenttä voi sisältää vain aakkosia, numeroita, alaviivoja tai väliviivoja.';
$lang['form_validation_numeric']		= '{field} -kentän täytyy sisältää vain numeroita.';
$lang['form_validation_is_numeric']		= '{field} -kentän täytyy sisältää vain numeroita.';
$lang['form_validation_integer']		= '{field} -kenttä voi sisältää vain kokonaisluvun.';
$lang['form_validation_regex_match']		= '{field} -kenttä ei ole ole oikeassa muodossa.';
$lang['form_validation_matches']		= '{field} -kenttä ei vastaa {param} -kenttää.';
$lang['form_validation_differs']		= '{field} -kentän on oltava erilainen kuin {param}.';
$lang['form_validation_is_unique'] 		= '{field} -kentän on sisällettävä yksilöllinen arvo.';
$lang['form_validation_is_natural']		= '{field} -kenttä saa sisältää vain numeroita.';
$lang['form_validation_is_natural_no_zero']	= '{field} -kenttä saa sisältää vain numeroita ja sen on oltava suurempi kuin nolla.';
$lang['form_validation_decimal']		= '{field} -kentässä on oltava desimaaliluku.';
$lang['form_validation_less_than']		= '{field} -kentän on sisällettävä numero, joka on pienempi kuin {param}.';
$lang['form_validation_less_than_equal_to']	= '{field} -kentän tulee sisältää numero, joka on pienempi tai yhtä suuri kuin {param}.';
$lang['form_validation_greater_than']		= '{field} -kentän on oltava suurempi kuin {param}.';
$lang['form_validation_greater_than_equal_to']	= '{field} -kentän tulee sisältää numero, joka on suurempi tai yhtä suuri kuin {param}.';
$lang['form_validation_error_message_not_set']	= 'Kentän {field} nimeä vastaavaa virhesanomaa ei voida käyttää.';
$lang['form_validation_in_list']		= '{field} -kentän on oltava yksi: {param}.';
