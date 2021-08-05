<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$lang['migration_none_found']          = 'Aucune migration n\' été trouvée.';
$lang['migration_not_found']           = 'Aucune migration n\' été trouvée avec le numéro de version : %s.';
$lang['migration_sequence_gap']        = 'Il y a un trou dans la séquence des migrations, aux environs du numéro de version : %s.';
$lang['migration_multiple_version']    = 'Il y a plusieurs migrations qui ont le même numéro de version : %s.';
$lang['migration_class_doesnt_exist']  = 'La classe de migration "%s" n\'a pas pu être trouvée.';
$lang['migration_missing_up_method']   = 'La classe de migration "%s" n\'a pas de méthode "up".';
$lang['migration_missing_down_method'] = 'La classe de migration "%s" n\'a pas de méthode "down".';
$lang['migration_invalid_filename']    = 'La migration "%s" a un nom de fichier non valide.';
