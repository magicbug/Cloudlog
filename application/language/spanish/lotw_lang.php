<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$lang['lotw_short'] = 'LoTW';
$lang['lotw_title'] = 'Logbook of the World';
$lang['lotw_title_available_cert'] = 'Certificados disponibles';
$lang['lotw_title_information'] = 'Información';
$lang['lotw_title_upload_p12_cert'] = 'Subir certificado LoTW .p12';
$lang['lotw_title_export_p12_file_instruction'] = 'Instrucciones para exportar archivos .p12';
$lang['lotw_title_adif_import'] = 'Importar ADIF (Formato de Intercambio de Datos Amateur)';
$lang['lotw_title_adif_import_options'] = 'Opciones de importación';

$lang['lotw_beta_warning'] = 'Por favor, tenga en cuenta que la sincronización con LoTW está en fase de pruebas. Visite la wiki del proyecto si necesita ayuda o quiere saber más.';
$lang['lotw_no_certs_uploaded'] = 'Es necesario subir algunos certificados p12 de LoTW para usar este área.';

$lang['lotw_date_created'] = 'Fecha de creación';
$lang['lotw_date_expires'] = 'Fecha de caducidad';
$lang['lotw_qso_start_date'] = 'Fecha de Inicio de QSO';
$lang['lotw_qso_end_date'] = 'Fecha Fin de QSO';
$lang['lotw_status'] = 'Estado';
$lang['lotw_options'] = 'Opciones';
$lang['lotw_valid'] = 'Válido';
$lang['lotw_expired'] = 'Caducado';
$lang['lotw_expiring'] = 'Caduca pronto';
$lang['lotw_not_synced'] = 'No sincronizado';

$lang['lotw_certificate_dxcc'] = 'Certificado DXCC';
$lang['lotw_certificate_dxcc_help_text'] = 'Entidad del certificado DXCC. Por ejemplo: Escocia';

$lang['lotw_input_a_file'] = 'Subir un archivo';

$lang['lotw_upload_exported_adif_file_from_lotw'] = 'Suba el archivo ADIF exportado en LoTW a través del área <a href="https://p1k.arrl.org/lotwuser/qsos?qsoscmd=adif" target="_blank">Download Report</a> para marcar QSOs como confirmados en LoTW.';
$lang['lotw_upload_type_must_be_adi'] = 'Los archivos de registro deben ser del tipo .adi';

$lang['lotw_pull_lotw_data_for_me'] = 'Extraer los datos LoTW por mí';
$lang['lotw_select_callsign'] = 'Seleccione indicativo para obtener confirmaciones de LoTW';

$lang['lotw_report_download_overview_helptext'] ='Cloudlog usará el usuario y contraseña de LoTW guardado en su perfil para descargar un informe de LoTW por usted. El informe contendrá todas las confirmaciones desde la fecha elegida o desde su última confirmación LoTW hasta ahora.';

// Buttons
$lang['lotw_btn_lotw_import'] = 'Importar LoTW';
$lang['lotw_btn_upload_certificate'] = 'Subir certificado';
$lang['lotw_btn_delete'] = 'Borrar';
$lang['lotw_btn_manual_sync'] = 'Sincronización manual';
$lang['lotw_btn_upload_file'] = 'Subir archivo';
$lang['lotw_btn_import_matches'] = 'Importar coincidencias LoTW';

// P12 Export Text
$lang['lotw_p12_export_step_one'] = 'Abrir TQSL e ir a la pestaña "Callsign Certificates".';
$lang['lotw_p12_export_step_two'] = 'Clic derecho en el indicativo deseado.';
$lang['lotw_p12_export_step_three'] = 'Clic en "Save Callsign Certificate File" sin añadir la contraseña.';
$lang['lotw_p12_export_step_four'] = 'Subir aquí el archivo descargado.';

$lang['lotw_confirmed'] = 'Este QSO está confirmado en LoTW';

// LoTW Expiry
$lang['lotw_cert_expiring'] = '¡Al menos uno de sus certificados de LoTW caduca pronto!';
$lang['lotw_cert_expired'] = '¡Al menos uno de sus certificados de LoTW ya ha caducado!';

// Lotw User
$lang['lotw_user'] = 'Esta estacion usa LoTW.';
$lang['lotw_last_upload'] = 'Última subida';

$lang['lotw_active'] = 'activos';
$lang['lotw_not_found'] = 'no encontrado';
