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

$lang['email_must_be_array'] = 'The email validation method must be passed an array.';
$lang['email_invalid_address'] = 'Sähköpostiosoite ei kelpaa: %s';
$lang['email_attachment_missing'] = 'Seuraavaa sähköpostin liitettä ei löydy: %s';
$lang['email_attachment_unreadable'] = 'Tätä liitettä ei voi avata: %s';
$lang['email_no_from'] = 'Viestejä ei voi lähettää ilman Lähettäjä-tietoa.';
$lang['email_no_recipients'] = 'Sinun on sisällytettävä vastaanottajat: To, Cc, or Bcc';
$lang['email_send_failure_phpmail'] = 'Sähköpostin lähetys epäonnistui käyttäen: PHP mail(). Palvelinta ei ehkä ole määritetty lähettämään sähköpostia tällä menetelmällä.';
$lang['email_send_failure_sendmail'] = 'Sähköpostin lähetys epäonnistui käyttäen: PHP Sendmail. Palvelinta ei ehkä ole määritetty lähettämään sähköpostia tällä menetelmällä.';
$lang['email_send_failure_smtp'] = 'Sähköpostin lähetys epäonnistui käyttäen: PHP SMTP. Palvelinta ei ehkä ole määritetty lähettämään sähköpostia tällä menetelmällä.';
$lang['email_sent'] = 'Viestisi on lähetetty onnistuneesti käyttäen protokollaa: %s';
$lang['email_no_socket'] = 'Unable to open a socket to Sendmail. Tarkista asetukset.';
$lang['email_no_hostname'] = 'SMTP isäntänimeä ei ole määritelty.';
$lang['email_smtp_error'] = 'Ilmeni seuraava SMTP virhe: %s';
$lang['email_no_smtp_unpw'] = 'Virhe: Sinun täytyy määritellä SMTP käyttäjätunnus ja salasana.';
$lang['email_failed_smtp_login'] = 'Failed to send AUTH LOGIN command. Virhe: %s';
$lang['email_smtp_auth_un'] = 'Käyttäjänimen todentaminen epäonnistui. Virhe: %s';
$lang['email_smtp_auth_pw'] = 'Salasanan todennus epäonnistui. Virhe: %s';
$lang['email_smtp_data_failure'] = 'Tietojen lähettäminen epäonnistui: %s';
$lang['email_exit_status'] = 'Poistu tilakoodista: %s';
