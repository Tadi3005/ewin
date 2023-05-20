<?php
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
function envoieCourriel($courriel, $intitule, $message, &$resultat) {
    // Nouvelle objet de la classe PHPMailer
    $mail = new PHPMailer(true);

    try {
        $mail->CharSet = 'UTF-8'; // Encodage du courriel
        $mail->setFrom($courriel); // L'adresse email de l'expéditeur
        $mail->addAddress('a.tadino@tsudent.helmo.be'); // L'adresse email du destinataire
        $mail->addReplyTo('a.tadino@student.helmo.be'); // L'adresse email de la personne qui répondra au destinataire
        $mail->isHTML(false); // Set email format to HTML
        $mail->Subject = $intitule; // Le sujet du courriel
        $mail->Body = $message; // Le contenu du courriel
        $mail->addCC($courriel); // L'adresse email du destinataire en copie
        $mail->send();
        $resultat = 'Le message a été envoyé'; // Message de confirmation
    } catch (Exception $e) {
        // Message d'erreur
        $resultat = "Erreur survenue lors de l'envoie du courriel: {$mail->ErrorInfo}";
    }
}

?>