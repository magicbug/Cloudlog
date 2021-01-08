<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$lang['email_must_be_array']         = 'La méthode de validation d\'e-mail doit être passée en tableau (array).';
$lang['email_invalid_address']       = 'Adresse e-mail non valide : %s';
$lang['email_attachment_missing']    = 'Impossible de localiser le fichier joint suivant : %s';
$lang['email_attachment_unreadable'] = 'Impossible d\'ouvrir le fichier joint : %s';
$lang['email_no_from']               = 'Impossible d\'envoyer un e-mail sans spécifier le champ "From".';
$lang['email_no_recipients']         = 'Vous devez spécifier les destinataires : To, Cc, ou Bcc';
$lang['email_send_failure_phpmail']  = 'Impossible d\'envoyer un e-mail en utilisant PHP mail(). Votre serveur n\'est peut-être pas configuré pour envoyer des e-mails selon cette méthode.';
$lang['email_send_failure_sendmail'] = 'Impossible d\'envoyer un e-mail en utilisant PHP Sendmail. Votre serveur n\'est peut-être pas configuré pour envoyer des e-mails selon cette méthode.';
$lang['email_send_failure_smtp']     = 'Impossible d\'envoyer un e-mail en utilisant PHP SMTP. Votre serveur n\'est peut-être pas configuré pour envoyer des e-mails selon cette méthode.';
$lang['email_sent']                  = 'Votre message a bien été envoyé, en utilisant le protocole suivant : %s';
$lang['email_no_socket']             = 'Impossible d\'ouvrir un socket via Sendmail. Veuillez vérifier les paramètres.';
$lang['email_no_hostname']           = 'Vous n\'avez pas spécifié un hôte SMTP.';
$lang['email_smtp_error']            = 'L\'erreur SMTP suivante a été rencontrée : %s';
$lang['email_no_smtp_unpw']          = 'Erreur : vous devez spécifier un nom d\'utilisateur et un mot de passe pour le protocole SMTP.';
$lang['email_failed_smtp_login']     = 'Echec dans l\'envoi de la commande AUTH LOGIN. Erreur : %s';
$lang['email_smtp_auth_un']          = 'Echec dans l\'authentification du nom d\'utilisateur. Erreur : %s';
$lang['email_smtp_auth_pw']          = 'Echec dans l\'authentification du mot de passe. Erreur : %s';
$lang['email_smtp_data_failure']     = 'Impossible d\'envoyer les données : %s';
$lang['email_exit_status']           = 'Code d\'état de sortie : %s';
