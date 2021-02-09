<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$lang['imglib_source_image_required']   = 'Vous devez spécifier une image source dans vos préférences.';
$lang['imglib_gd_required']             = 'La bibliothèque de gestion d\'images GD est requise pour cette fonctionnalité.';
$lang['imglib_gd_required_for_props']   = 'Votre serveur doit supporter la bibliothèque de gestion d\'images GD, pour pouvoir déterminer les propriétés de l\'image.';
$lang['imglib_unsupported_imagecreate'] = 'Votre serveur ne dispose pas de la fonction GD nécessaire pour traiter ce type d\'image.';
$lang['imglib_gif_not_supported']       = 'Les images GIF ne sont pas supportées (souvent à cause de restrictions de licence). Vous devriez utiliser à la place des images JPG ou PNG.';
$lang['imglib_jpg_not_supported']       = 'Les images JPG ne sont pas supportées.';
$lang['imglib_png_not_supported']       = 'Les images PNG ne sont pas supportées.';
$lang['imglib_jpg_or_png_required']     = 'Le protocole de redimensionnement spécifié dans vos préférences ne fonctionne qu\'avec les formats d\'image JPG ou PNG.';
$lang['imglib_copy_error']              = 'Une erreur est survenue lors du remplacement du fichier. Veuillez vérifier que votre dossier est accessible en écriture.';
$lang['imglib_rotate_unsupported']      = 'La rotation d\'image ne semble pas être supportée par votre serveur.';
$lang['imglib_libpath_invalid']         = 'Le chemin d\'accès à votre librairie de traitement d\'image n\'est pas correct. Veuillez indiquer le bon chemin dans vos préférences d\'images.';
$lang['imglib_image_process_failed']    = 'Le traitement de l\'image a échoué. Veuillez vérifier que votre serveur supporte le protocole choisi et que le chemin d\'accès à votre librairie de traitement d\'image est correct.';
$lang['imglib_rotation_angle_required'] = 'Un angle de rotation doit être fourni pour pouvoir pivoter l\'image.';
$lang['imglib_invalid_path']            = 'Le chemin d\'accès à l\'image n\'est pas correct.';
$lang['imglib_invalid_image']           = 'L\'image fournie n\'est pas valide.';
$lang['imglib_copy_failed']             = 'Le processus de copie d\'image a échoué.';
$lang['imglib_missing_font']            = 'Impossible de trouver une police de caractères à utiliser.';
$lang['imglib_save_failed']             = 'Impossible de sauver l\'image. Veuillez vous assurer que l\'image et le dossier sont bien accessibles en écriture.';
