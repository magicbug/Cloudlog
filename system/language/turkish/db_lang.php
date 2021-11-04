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
defined('BASEPATH') OR exit('Doğrudan komut dosyası erişimine izin verilmez');

$lang['db_invalid_connection_str'] = 'Gönderdiğiniz bağlantı dizesine göre veritabanı ayarları belirlenemedi.';
$lang['db_unable_to_connect'] = 'Verilen ayarlar kullanılarak veritabanı sunucunuza bağlanılamıyor.';
$lang['db_unable_to_select'] = 'Belirtilen veritabanı seçilemiyor: %s';
$lang['db_unable_to_create'] = 'Belirtilen veritabanı oluşturulamadı: %s';
$lang['db_invalid_query'] = 'Gönderdiğiniz sorgu geçerli değil.';
$lang['db_must_set_table'] = 'Sorgunuzla kullanılacak veritabanı tablosunu ayarlamalısınız.';
$lang['db_must_use_set'] = 'Bir girişi güncellemek için "set" yöntemini kullanmalısınız.';
$lang['db_must_use_index'] = 'Toplu güncellemeler için eşleştirilecek bir dizin belirtmelisiniz.';
$lang['db_batch_missing_index'] = 'Toplu güncelleme için gönderilen bir veya daha fazla satırda belirtilen dizin eksik.';
$lang['db_must_use_where'] = 'Bir "where" maddesi içermedikçe güncellemelere izin verilmez.';
$lang['db_del_must_use_where'] = '"where" veya "like" cümlesi içermedikçe silmelere izin verilmez.';
$lang['db_field_param_missing'] = 'Alanları getirmek için parametre olarak tablo adı gerekir.';
$lang['db_unsupported_function'] = 'Bu özellik, kullandığınız veritabanı için mevcut değil.';
$lang['db_transaction_failure'] = 'İşlem hatası: Geri alma gerçekleştirildi.';
$lang['db_unable_to_drop'] = 'Belirtilen veritabanı bırakılamadı.';
$lang['db_unsupported_feature'] = 'Kullandığınız veritabanı platformunun desteklenmeyen özelliği.';
$lang['db_unsupported_compression'] = 'Seçtiğiniz dosya sıkıştırma formatı sunucunuz tarafından desteklenmiyor.';
$lang['db_filepath_error'] = 'Gönderdiğiniz dosya yoluna veri yazılamıyor.';
$lang['db_invalid_cache_path'] = 'Gönderdiğiniz önbellek yolu geçerli veya yazılabilir değil.';
$lang['db_table_name_required'] = 'Bu işlem için bir tablo adı gerekli.';
$lang['db_column_name_required'] = 'Bu işlem için bir sütun adı gerekli.';
$lang['db_column_definition_required'] = 'Bu işlem için bir sütun tanımı gerekli.';
$lang['db_unable_to_set_charset'] = 'İstemci bağlantı karakter kümesi ayarlanamadı: %s';
$lang['db_error_heading'] = 'Bir Veritabanı Hatası Oluştu';
