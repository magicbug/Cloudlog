<?php

defined('BASEPATH') OR exit('No direct script access allowed');


/*
___________________________________________________________________________________________
Topbar
___________________________________________________________________________________________
*/

$lang['adif_import'] = "Importer ADIF";
$lang['adif_export'] = "Exporter ADIF";
// $lang['lotw_title']                      --> application/language/english/lotw_lang.php
$lang['darc_dcl'] = "DARC DCL";


/*
___________________________________________________________________________________________
ADIF Import
___________________________________________________________________________________________
*/

// $lang['general_word_important']           --> application/language/english/general_words_lang.php
$lang['adif_alert_log_files_type'] = "Les fichiers de Log doivent avoir l'extention : *.adi";
// $lang['general_word_warning']            --> application/language/english/general_words_lang.php "PHP Upload Warning"
// $lang['gen_max_file_upload_size']        --> application/language/english/general_words_lang.php "PHP Upload Warning"

$lang['adif_select_stationlocation'] = "Sélectionner une Localisation";
// $lang['gen_hamradio_callsign']           --> application/language/english/general_words_lang.php

// The File Input is translated by the Browser
$lang['adif_file_label'] = "Fichier ADIF";

$lang['adif_hint_no_info_in_file'] = "(A cocher,  si l'ADIF à importer ne contient pas ces informations)";

$lang['adif_import_dup'] = "Importer les QSO en double";
$lang['adif_mark_imported_lotw'] = "Indiquer que les QSO importés ont été téléchargés sur LoTW";
$lang['adif_mark_imported_hrdlog'] = "Indiquer que les QSO importés ont été téléchargés dans le journal HRDLog.net";
$lang['adif_mark_imported_qrz'] = "Indiquer que les QSO importés ont été téléchargés dans le journal QRZ.com";
$lang['adif_mark_imported_clublog'] = "Indiquer que les QSO importés ont été téléchargés dans le journal Clublog";

$lang['adif_dxcc_from_adif'] = "Utiliser l'information du DXCC issue du fichier ADIF";
$lang['adif_dxcc_from_adif_hint'] = "(Si cochée, Cloudlog tentera de déterminer automatiquement les informations DXCC.)";

$lang['adif_always_use_login_call_as_op'] = "Toujours utiliser l'indicatif de connexion comme nom d'opérateur lors de l'import";

$lang['adif_ignore_station_call'] = "Ignorer l'indicatif de la station lors de l'import";
$lang['adif_ignore_station_call_hint'] = "(Si cochée, Cloudlog tentera d'importer <b><u>TOUS</u></b> les QSO de l'ADIF, qu'ils correspondent ou non à l'emplacement de la station choisie)";

$lang['adif_upload'] = "Importer";

/*
___________________________________________________________________________________________
ADIF Export
___________________________________________________________________________________________
*/

$lang['adif_export_take_it_anywhere'] = "Emportez votre fichier journal de trafic partout !";
$lang['adif_export_take_it_anywhere_hint'] = "L'export de fichier ADIF vous permet d'importer vos QSO dans des applications tierces comme LoTW, Awards ou simplement pour conserver une sauvegarde.";


$lang['adif_mark_exported_lotw'] = "Indiquer que les QSO exportés ont été téléchargés sur LoTW";
$lang['adif_mark_exported_no_lotw'] = "Exporter les QSO non téléchargés sur LoTW";

$lang['adif_export_qso'] = "Exporter";

$lang['adif_export_sat_only_qso'] = "Export des QSO par Satellite seulement";
$lang['adif_export_sat_only_qso_all'] = "Exporter tous les QSO par satellite";
$lang['adif_export_sat_only_qso_lotw'] = "Exporter tous les QSO par satellite et confirmés sur LoTW";

/*
___________________________________________________________________________________________
Logbook of the World
___________________________________________________________________________________________
*/

$lang['adif_lotw_export_if_selected'] = "Si aucune date n'est renseignées, tous les QSO seront marqués !";
$lang['adif_mark_qso_as_exported_to_lotw'] = "Marquer les QSO comme étant \"téléchargé sur LoTW\"";

$lang['adif_qso_marked'] = "QSO marqués";
$lang['adif_yay_its_done'] = "Ok, réalisé !";
$lang['adif_qso_lotw_marked_confirm'] = "Les QSO ont été marqués comme téléchargés sur LoTW.";

/*
___________________________________________________________________________________________
DARC DCL
___________________________________________________________________________________________
*/
$lang['adif_dcl_text_pre'] = "Allez sur ";
$lang['adif_dcl_text_post'] = "et exportez votre journal de trafic avec les DOK confirmés. <br>Pour accélérer le processus, vous pouvez sélectionner uniquement les QSO DL à télécharger (c'est-à-dire mettre \"DL\" dans la liste des préfixes). <br>Le fichier ADIF téléchargé peut être téléchargé sur cette page afin de mettre à jour les QSO avec les informations DOK.";

$lang['only_confirmed_qsos'] = "Importez uniquement les données DOK des QSO confirmés sur DCL";
$lang['only_confirmed_qsos_hint'] = "(Décoché, si vous souhaitez également mettre à jour DOK, avec les données des QSO non confirmés dans DCL)";

$lang['overwrite_by_dcl'] = "Remplacer le DOC existant dans le journal par DCL (si différent)";
$lang['overwrite_by_dcl_hint'] = "(Si cochée, Cloudlog écrasera de force le DOK existant par le DOK du journal DCL)";

$lang['ignore_ambiguous'] = "Ignorer les QSO qui ne correspondent pas";
$lang['ignore_ambiguous_hint'] = "(Si non cochée, les informations sur les QSO qui n'ont pas pu être trouvées dans Cloudlog seront affichées)";

/*
___________________________________________________________________________________________
Import Success
___________________________________________________________________________________________
*/

$lang['adif_imported'] = "ADIF importé";
$lang['adif_yay_its_imported'] = "Ok, c'est importé !";
$lang['adif_import_confirm'] = "Le fichier ADIF a été importé.";

$lang['adif_import_dupes_inserted'] = "<b>Des doublons ont été insérés !</b>";
$lang['adif_import_dupes_skipped'] = "Les doublons ont été ignorés.";

$lang['adif_import_errors'] = "Erreurs ADIF ";
$lang['adif_import_errors_hint'] = "Vous avez des erreurs ADIF, les QSO ont quand même été ajoutés mais ces champs n'ont pas été renseignés.";

/*
___________________________________________________________________________________________
DCL Success
___________________________________________________________________________________________
*/

$lang['dcl_results'] = "Résultats de la mise jour pour DCL DOK";
$lang['dcl_info_updated'] = "Les informations DCL pour les DOK ont été mis à jours.";
$lang['dcl_qsos_updated'] = "QSO mis à jour";
$lang['dcl_qsos_ignored'] = "QSO ignorés";
$lang['dcl_qsos_unmatched'] = "QSO sans correspondance";
$lang['dcl_no_qsos_updated'] = "Aucun QSO trouvé qui pourrait être mis à jour.";
$lang['dcl_dok_errors'] = "Erreurs DOK";
$lang['dcl_dok_errors_details'] = "Différence de données DOK entre votre journal de travail et DCL";
$lang['dcl_qsl_status'] = "DCL QSL Statut";
$lang['dcl_qsl_status_c'] = "confirmé par LoTW/Clublog/eQSL/Contest";
$lang['dcl_qsl_status_mno'] = "confirmé par le manager de l'Award";
$lang['dcl_qsl_status_i'] = "confirmé par recoupement des données DCL";
$lang['dcl_qsl_status_w'] = "confirmation en attente";
$lang['dcl_qsl_status_x'] = "non confirmé";
$lang['dcl_qsl_status_unknown'] = "inconnu";
$lang['dcl_no_match'] = "pas de correspondance pour le QSO";


