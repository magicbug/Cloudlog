<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$lang['lotw_title'] = 'Logbook du monde';
$lang['lotw_title_available_cert'] = 'Certificats disponibles';
$lang['lotw_title_information'] = 'Information';
$lang['lotw_title_upload_p12_cert'] = 'Envoyer certificat .p12 du Logbook du monde';
$lang['lotw_title_export_p12_file_instruction'] = 'Instruction pour l\'export des fichiers .p12';
$lang['lotw_title_adif_import'] = 'Import ADIF';
$lang['lotw_title_adif_import_options'] = 'Options d\'import';

$lang['lotw_beta_warning'] = 'Attention la synchro LOTW est au stage BETA, voir le wiki pour l\'aide.';
$lang['lotw_no_certs_uploaded'] = 'Vous devez envoyer des certificats p1 pour utiliser cette zone.';

$lang['lotw_date_created'] = 'Date Création';
$lang['lotw_date_expires'] = 'Date Expiration';
$lang['lotw_status'] = 'Status';
$lang['lotw_options'] = 'Options';
$lang['lotw_valid'] = 'Valide';
$lang['lotw_expired'] = 'Expiré';
$lang['lotw_not_synced'] = 'Non Synchronisé';

$lang['lotw_certificate_dxcc'] = 'Certificat DXCC';
$lang['lotw_certificate_dxcc_help_text'] = 'Entité Certificat DXCC. Par example: Scotland';

$lang['lotw_input_a_file'] = 'Envoyer un fichier';

$lang['lotw_upload_exported_adif_file_from_lotw'] = 'Envoyer le fichier ADIF exporté depuis la zone LoTW à l\'adresse  <a href="https://p1k.arrl.org/lotwuser/qsos?qsoscmd=adif" target="_blank">Download Report</a>, pour marquer les QSO comme "confirmés" sur LOTW.';
$lang['lotw_upload_type_must_be_adi'] = 'Les fichiers de log doivent être au format .adi';

$lang['lotw_pull_lotw_data_for_me'] = 'Récuperer mes données LoTW';
$lang['lotw_import_missing_qsos_text'] = 'Importer les QSO manquants dans le log. Les indicatifs et gridsquare seront testés pour essayer de trouver le profil d\'importation correct. Les QSO seront ignorés si non trouvé.';

$lang['lotw_report_download_overview_helptext'] ='Cloudlog utilise le nom d\'utilisateur et mot de passe enregistré dans votre profil utilisateur pour télécharger les rapport depuis LoTw. Les rapports téléchargés seront confirmés depuis la date de votre dernière confirmation (extraite de vos log) jusqu\'à aujourd\'hui';

// Buttons
$lang['lotw_btn_lotw_import'] = 'Import LoTW';
$lang['lotw_btn_upload_certificate'] = 'Envoi Certificat';
$lang['lotw_btn_delete'] = 'Supprimer';
$lang['lotw_btn_manual_sync'] = 'Synchro manuelle';
$lang['lotw_btn_upload_file'] = 'Envoi fichier';
$lang['lotw_btn_import_matches'] = 'Import LoTW correspondants';

// P12 Export Text
$lang['lotw_p12_export_step_one'] = 'Ouvrir  TQSL et aller à l\'onglet  Certificats Indicatifs';
$lang['lotw_p12_export_step_two'] = 'Click droit sur l\indicatif désiré';
$lang['lotw_p12_export_step_three'] = 'Cliquer "Enregistrer le fichier des certificats d\'indicatif sans ajouter de mot de passe';
$lang['lotw_p12_export_step_four'] = 'Envoyer le fichier ci-dessous.';
$lang['lotw_confirmed'] = 'Ce QSO est confirmé sur LoTW';
