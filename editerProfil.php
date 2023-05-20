<?php
session_start();

include 'php/User.php';
use EWIN\User;
$erreur = '';

if (isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['pseudo']) && isset($_POST['courriel']) && isset($_FILES['photoProfil'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $pseudo = $_POST['pseudo'];
    $courriel = $_POST['courriel'];
    $photoProfil = $_FILES['photoProfil'];
    $user = new User();

    if ($photoProfil) {
        // Vérifier si le fichier téléversé a été téléversé sans erreur.
        if (!$_FILES['photoProfil']['error'] == UPLOAD_ERR_NO_FILE) {
            if (!$_FILES['photoProfil']['error'] == UPLOAD_ERR_INI_SIZE) {
                if (!$_FILES['photoProfil']['error'] == UPLOAD_ERR_FORM_SIZE) {
                    if (!$_FILES['photoProfil']['error'] == UPLOAD_ERR_PARTIAL) {
                        // Vérifier la taille du fichier téléversé
                        if ($_FILES['photoProfil']['size'] <= 10000000) {
                            if (getimagesize($_FILES['photoProfil']['tmp_name'])) {
                                $tailleImage = getimagesize($_FILES['photoProfil']['tmp_name']);
                                if ($tailleImage[0] == $tailleImage[1]) {
                                    $extensions_autorisees = array('jpg', 'jpeg', 'png');
                                    if (in_array(pathinfo($_FILES['photoProfil']['name'])['extension'], $extensions_autorisees)) {
                                        $extension = pathinfo($_FILES['photoProfil']['name'], PATHINFO_EXTENSION);
                                        $nomFichier = uniqid().'.'.$extension;
                                        $destination = 'upload/' . $nomFichier;
                                        move_uploaded_file($_FILES['photoProfil']['tmp_name'], $destination);
                                        $user->setUrlPhoto($nomFichier);
                                    } else {
                                        $erreur = 'extension non autorisée';
                                    }
                                } else {
                                    $erreur = 'image non carrée';
                                }
                            } else {
                                $erreur = 'fichier non image';
                            }
                        } else {
                            $erreur = 'fichier trop volumineux';
                        }
                    } else {
                        $erreur = 'fichier partiellement téléchargé';
                    }
                } else {
                    $erreur = 'taille fichier dépassant la limite';
                }
            } else {
                $erreur = 'pas de fichier spécifié';
            }
        } else {
            $erreur = 'pas de fichier spécifié';
        }
    }
    $user->setNom($nom);
    $user->setPrenom($prenom);
    $user->setPseudo($pseudo);
    $user->setCourriel($courriel);
    $user->setUrlPhoto($nomFichier);

    if ($erreur == '') {
        $user->editerProfil($nom, $prenom, $pseudo, $courriel, $nomFichier);
        $user->updateSessionAfterEditProfil($nom, $prenom, $pseudo, $courriel, $nomFichier);
    } else {
        header('Location: editerProfil.php?erreur=' . $erreur);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Editer profil</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
</head>
<body>
    <?php require("inc/header.inc.php"); ?>
    <main class="mainFormulaire">
        <section class="formulaire">
            <h1>Editer votre profil</h1>
            <form method="post" enctype="multipart/form-data">
                <label for="nom">Nom</label><input id="nom" name="nom" type="text">
                <label for="prenom">Prenom</label><input id="prenom" name="prenom" type="text">
                <label for="pseudo">Pseudo</label><input id="pseudo" name="pseudo" type="text">
                <label for="courriel">Email</label><input id="courriel" name="courriel" type="email">
                <label for="photoProfil">Photo de profil</label><input type="file" id="photoProfil" name="photoProfil" accept="image/*, .jpg, .png">
                <button type="submit">Modifier</button>
            </form>
            <?php
                echo '<p class="erreur">' . $erreur . '</p>';
            ?>
        </section>
    </main>
    <?php require("inc/footer.inc.php"); ?>
</body>
</html>