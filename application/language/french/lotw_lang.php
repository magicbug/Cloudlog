<?php

defined('BASEPATH') OR exit('No direct script access allowed');

$lang['lotw_short'] = "LoTW";
$lang['lotw_title'] = "Logbook of the World";
$lang['lotw_title_available_cert'] = "Certificats disponibles";
$lang['lotw_title_information'] = "Information";
$lang['lotw_title_upload_p12_cert'] = "Envoyer certificat .p12 de LoTW";
$lang['lotw_title_export_p12_file_instruction'] = "Instruction pour l'export des fichiers .p12";
$lang['lotw_title_adif_import'] = "Import ADIF";
$lang['lotw_title_adif_import_options'] = "Options d'import";

$lang['lotw_beta_warning'] = "Attention la synchro LoTW est au stage BETA, voir le wiki pour l'aide.";
$lang['lotw_no_certs_uploaded'] = "Vous devez envoyer des certificats p1 pour utiliser cette zone.";

$lang['lotw_date_created'] = "Date de création";
$lang['lotw_date_expires'] = "Date d'expiration";
$lang['lotw_qso_start_date'] = "Date de début des QSO";
$lang['lotw_qso_end_date'] = "Date de fin des QSO";
$lang['lotw_status'] = "Statut";
$lang['lotw_options'] = "Options";
$lang['lotw_valid'] = "Valide";
$lang['lotw_expired'] = "Expiré";
$lang['lotw_expiring'] = "Expiration";
$lang['lotw_not_synced'] = "Non synchronisé";

$lang['lotw_certificate_dxcc'] = "Certificat DXCC";
$lang['lotw_certificate_dxcc_help_text'] = "Entité certificat DXCC. (Par exemple: Scotland)";

$lang['lotw_input_a_file'] = "Envoyer un fichier";

$lang['lotw_upload_exported_adif_file_from_lotw'] = "Envoyer le fichier ADIF exporté depuis la zone LoTW à l'adresse  <a href=\"https://p1k.arrl.org/lotwuser/qsos?qsoscmd=adif\" target=\"_blank\">Download Report</a>, pour marquer les QSO comme \"confirmés\" sur LoTW.";
$lang['lotw_upload_type_must_be_adi'] = "Les fichiers de log doivent être au format .adi";

$lang['lotw_pull_lotw_data_for_me'] = "Récuperer mes données LoTW";
$lang['lotw_select_callsign'] = 'Select callsign to pull LoTW confirmations for';

$lang['lotw_report_download_overview_helptext'] = "Cloudlog utilisera le nom d'utilisateur et le mot de passe LoTW stockés dans votre profil utilisateur pour télécharger le journal vers LoTW.<br/> Les téléchargements du journal Cloudlog auront toutes les confirmations depuis la date choisie, ou depuis votre dernière confirmation LoTW (récupérée de votre journal), jusqu'à présent.";

// Buttons
$lang['lotw_btn_lotw_import'] = "Import LoTW";
$lang['lotw_btn_upload_certificate'] = "Envoyer un certificat";
$lang['lotw_btn_delete'] = "Supprimer";
$lang['lotw_btn_manual_sync'] = "Synchro manuelle";
$lang['lotw_btn_upload_file'] = "Envoyer le fichier";
$lang['lotw_btn_import_matches'] = "Import LoTW correspondants";

// P12 Export Text
$lang['lotw_p12_export_step_one'] = "Ouvrir  TQSL et aller à l'onglet  Certificats Indicatifs";
$lang['lotw_p12_export_step_two'] = "Click droit sur l'indicatif désiré";
$lang['lotw_p12_export_step_three'] = "Cliquer \"Enregistrer le fichier des certificats d'indicatif sans ajouter de mot de passe\"";
$lang['lotw_p12_export_step_four'] = "Envoyer le fichier ci-dessous";

$lang['lotw_confirmed'] = "QSO confirmé sur LoTW";

// LoTW Expiry
$lang['lotw_cert_expiring'] = "Au moins un de vos certificats LoTW est sur le point d'expirer !";
$lang['lotw_cert_expired'] = "Au moins un de vos certificats LoTW est expiré !";

// Lotw User
$lang['lotw_user'] = "Cette station utilise LoTW";
$lang['lotw_last_upload'] = "Dernier téléchargement";
