<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$lang['db_invalid_connection_str']     = 'Impossible de déterminer les paramètres de connection à la base de données avec les chaînes que vous avez fournies.';
$lang['db_unable_to_connect']          = 'Impossible de se connecter à votre base de données avec les paramètres fournis.';
$lang['db_unable_to_select']           = 'Impossible de sélectionner la base de données spécifiée : %s';
$lang['db_unable_to_create']           = 'Impossible de créer la base de données spécifiée : %s';
$lang['db_invalid_query']              = 'La requête que vous avez soumis n\'est pas valide.';
$lang['db_must_set_table']             = 'Vous devez préciser dans votre requête la table à utiliser dans la base de données.';
$lang['db_must_use_set']               = 'Vous devez utiliser la méthode "set" pour mettre à jour une entrée.';
$lang['db_must_use_index']             = 'Vous devez spécifier un index correspondant pour vos modifications en lot (batch).';
$lang['db_batch_missing_index']        = 'Il manque une ou plusieurs lignes dans l\'index spécifié pour pouvoir faire la modification en lot (batch).';
$lang['db_must_use_where']             = 'Les modifications ne sont pas autorisées quand elles ne contiennent pas une clause "where".';
$lang['db_del_must_use_where']         = 'Les suppressions ne sont pas autorisées quand elles ne contiennent pas une clause "where" ou une clause "like".';
$lang['db_field_param_missing']        = 'Il manque le nom de la table comme paramètre pour pouvoir récupérer les champs.';
$lang['db_unsupported_function']       = 'Cette fonction n\'est pas disponible pour la base de données que vous utilisez.';
$lang['db_transaction_failure']        = 'Echec de transaction : un rollback a été exécuté.';
$lang['db_unable_to_drop']             = 'Impossible de détruire (drop) la base de données spécifiée.';
$lang['db_unsupported_feature']        = 'Caractéristique non supportée par la plateforme qui héberge votre base de données.';
$lang['db_unsupported_compression']    = 'Le format de compression de fichier que vous avez choisi n\'est pas supporté par votre serveur.';
$lang['db_filepath_error']             = 'Impossible d\'écrire des données dans le chemin du fichier que vous avez fourni.';
$lang['db_invalid_cache_path']         = 'Le chemin du cache que vous avez fourni n\'est pas valide ou n\'est pas accessible en écriture.';
$lang['db_table_name_required']        = 'Un nom de table est requis pour cette opération.';
$lang['db_column_name_required']       = 'Un nom de colonne est requis pour cette opération.';
$lang['db_column_definition_required'] = 'Une définition de colonne est requise pour cette opération.';
$lang['db_unable_to_set_charset']      = 'Impossible d\'utiliser le jeu de caractères de la connection client : %s';
$lang['db_error_heading']              = 'Il y a eu une erreur avec la base de données';
