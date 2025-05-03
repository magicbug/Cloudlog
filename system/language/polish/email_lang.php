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

$lang['email_must_be_array'] = 'Metodzie walidacji adresu e-mail należy przekazać tablicę.';
$lang['email_invalid_address'] = 'Nieprawidłowy adres e-mail: %s';
$lang['email_attachment_missing'] = 'Nie można znaleźć następującego załącznika e-mail: %s';
$lang['email_attachment_unreadable'] = 'Nie można otworzyć tego załącznika: %s';
$lang['email_no_from'] = 'Nie można wysłać wiadomości bez nagłówka „Od”.';
$lang['email_no_recipients'] = 'Musisz uwzględnić odbiorców: Do, DW lub UDW';
$lang['email_send_failure_phpmail'] = 'Nie można wysłać wiadomości e-mail za pomocą PHP mail(). Twój serwer może nie być skonfigurowany do wysyłania wiadomości e-mail za pomocą tej metody.';
$lang['email_send_failure_sendmail'] = 'Nie można wysłać wiadomości e-mail za pomocą PHP Sendmail. Twój serwer może nie być skonfigurowany do wysyłania wiadomości e-mail za pomocą tej metody.';
$lang['email_send_failure_smtp'] = 'Nie można wysłać wiadomości e-mail za pomocą PHP SMTP. Twój serwer może nie być skonfigurowany do wysyłania wiadomości e-mail za pomocą tej metody.';
$lang['email_sent'] = 'Twoja wiadomość została pomyślnie wysłana za pomocą następującego protokołu: %s';
$lang['email_no_socket'] = 'Nie można otworzyć gniazda dla Sendmail. Sprawdź ustawienia.';
$lang['email_no_hostname'] = 'Nie określono nazwy hosta SMTP.';
$lang['email_smtp_error'] = 'Napotkano następujący błąd SMTP: %s';
$lang['email_no_smtp_unpw'] = 'Błąd: Musisz przypisać nazwę użytkownika i hasło SMTP.';
$lang['email_failed_smtp_login'] = 'Nie udało się wysłać polecenia AUTH LOGIN. Błąd: %s';
$lang['email_smtp_auth_un'] = 'Nie udało się uwierzytelnić nazwy użytkownika. Błąd: %s';
$lang['email_smtp_auth_pw'] = 'Nie udało się uwierzytelnić hasła. Błąd: %s';
$lang['email_smtp_data_failure'] = 'Nie można wysłać danych: %s';
$lang['email_exit_status'] = 'Kod statusu wyjścia: %s';