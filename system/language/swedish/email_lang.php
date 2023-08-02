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

$lang['email_must_be_array'] = 'E-postvalideraren kräver en array som indata.';
$lang['email_invalid_address'] = 'Ogiltig e-postadress: %s';
$lang['email_attachment_missing'] = 'Kan inte hitta bilagan %s';
$lang['email_attachment_unreadable'] = 'Kan inte öppna bilagan %s';
$lang['email_no_from'] = 'Kan inte skicka e-post utan en sändaraddress';
$lang['email_no_recipients'] = 'Du måste inkludera mottagare, antingen som mottagare, kopia eller dold kopia.';
$lang['email_send_failure_phpmail'] = 'Kan inte skicka e-post med PHP:s mail(). Din server kanske inte är konfigurerad att skicka e-post med denna metod.';
$lang['email_send_failure_sendmail'] = 'Kan inte skicka e-post med PHP Sendmail. Din server kanske inte är konfigurerad att skicka e-post med denna metod.';
$lang['email_send_failure_smtp'] = 'Kan inte skicka e-post med PHP SMTP. Din server kanske inte är konfigurerad att skicka e-post med denna metod.';
$lang['email_sent'] = 'Ditt meddelande har skickats med hjälp av protokollet %s';
$lang['email_no_socket'] = 'Kan inte öppna en socket för Sendmail. Kontrollera dina inställningar.';
$lang['email_no_hostname'] = 'Du har inte angett ett SMTP-värdnamn.';
$lang['email_smtp_error'] = 'Följande SMTP-fel påträffades: %s';
$lang['email_no_smtp_unpw'] = 'Du måste sätta ett SMTP-användarnamn och lösenord.';
$lang['email_failed_smtp_login'] = 'Det gick inte att skicka kommandot AUTH LOGIN. Fel: %s';
$lang['email_smtp_auth_un'] = 'Det gick inte att autentisera användarnamnet. Fel: %s';
$lang['email_smtp_auth_pw'] = 'Det gick inte att autentisera lösenordet. Fel: %s';
$lang['email_smtp_data_failure'] = 'Kan inte skicka data: %s';
$lang['email_exit_status'] = 'Exit-statuskod: %s';
