<?php
session_start();
require 'php/envoieCourriel.php';
require 'php/user.php';
use EWIN\User;

if (isset($_POST['mail'])) {
    // Récupérer l'adresse courriel
    $courriel = $_POST['mail'];
    // Object du courriel
    $intitule = 'Réinitialisation de votre mot de passe';
    // Générer un mot de passe aléatoire
        // longueur du mot de passe
        $longueurMotDePasse = 9;
        // générer une chaîne de caractères aléatoires
        $bytes = openssl_random_pseudo_bytes($longueurMotDePasse);
        // convertir la chaîne de caractères en une chaîne lisible
        $motDePasse = base64_encode($bytes);

    $message = 'Bonjour, voici votre nouveau mot de passe : ' . $motDePasse;
    // Actualiser le mot de passe dans la base de données
    $user = new User();
    $user->setCourriel($courriel);
    if ($user->exist($resultat)) {
        if ($user->updatePassword($motDePasse, $resultat)) {
            // Envoyer le courriel
            envoieCourriel($courriel, $intitule, $message, $resultat);
        } else {
            $resultat = 'Le mot de passe n\'a pas pu être réinitialisé';
        }
    } else {
        $resultat = 'L\'adresse courriel n\'existe pas';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Oubli mot de passe</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
</head>
<body>
    <header class="headerInscription">
        <img src="images/logo.png" alt="LOGO" width="100" height="100">
    </header>
    <main class="mainFormulaire">
        <section class="formulaire">
            <h1>J'ai oublié mon mot de passe !</h1>
            <form method="post">
                <label for="mail">Adresse mail</label><input id="mail" name="mail" type="email" required>
                <button type="submit" name="oubliMotDePasse">Envoyer</button>
            </form>
            <?php
            if (isset($resultat)) {
                echo '<p class="messageSucces">' . $resultat . '</p>';
            }
            ?>
        </section>
    </main>
    <?php require("inc/footer.inc.php"); ?>
</body>
</html>